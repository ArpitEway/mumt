<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Preexam extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
		$this->load->model('users/User_model');
		if(!$this->session->has_userdata('loged_in')){
			exit;
		}
	}
	
	public function upload_exam_paper(){
		$this->load->view('admin/script/header',array('title' => 'Upload Exam Paper'));
		/* class which dose not have elective papers */
		
		$classes = $this->Common_model->get_record('class_master','GROUP_CONCAT(id) as class_id',array('class_group' => 'N'));
		//,'exam_form_permission' => 'Y','admission_permission' => 'Y'
		$class_ids = $classes[0]['class_id'];
		
		$this->db->select('count(class_id) as num,course_name,class_name,class_id');
		$this->db->where('class_id in ('.$class_ids.') and temp_exam_form="N"');// and payment_status="Y"
		$this->db->group_by('class_id');
		$this->db->order_by('course_group_id');
		$studentClasses = $this->db->get('student')->result();
		$i=0;
		//echo $this->db->last_query();
		foreach($studentClasses as $row){
			$where = array('class_id' => $row->class_id,
							'temp_exam_form' => "N",
							'university_mode'=>'REG',
						);
			$studentsReg = $this->Common_model->getCountByWhere('student',$where);
			$where = array('class_id' => $row->class_id,
				'temp_exam_form' => "N",
				'university_mode'=>'PVT',
				);
			$studentsPvt = $this->Common_model->getCountByWhere('student',$where);
			
			$row->privateCount=$studentsPvt;
			$row->regularCount=$studentsReg;
			$studentClasses[$i]=$row;
			
			$i++;
		}
		
		
		// $this->Common_model->last_query();
		$data = array(
			'studentClasses' => $studentClasses,
		);
		$this->load->view('admin/script/upload_exam_paper',$data);
		$this->load->view('admin/script/footer');
	}

	public function upload_exam_paper_sub($class_id,$university_mode)
	{
		$where = array('class_id' => $class_id,
					//'payment_status' => 'Y',
					'temp_exam_form' => "N",
					'university_mode'=>$university_mode,
		);
	
		
		$students = $this->Common_model->get_record('student','*',$where);
		$classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		
		$cbcs = ($classData->cbcs == 'Y')?'Y':'N';
		if($university_mode=='PVT') 
					$paperWhere=array('class_id'=>$class_id,'type'=>'theory', 'cbcs_paper'=> $cbcs);
			else			
					$paperWhere=array('class_id'=>$class_id,'cbcs_paper'=> $cbcs);
			$papers = $this->Common_model->get_record('paper_master','*',$paperWhere);
		foreach ($students as $student) {
			$where = array('student_id'=>$student['student_id']);
			$data = array(
				'student_id' => $student['student_id'],
				'course_group_id' => $student['course_group_id'],
				'class_id' => $student['class_id'],
				);
			
			foreach ($papers as $paper) {
				$data['paper_id'] = $paper['id'];
				$data['paper_code'] = $paper['paper_code'];
				$data['paper_type'] = $paper['type'];
				$data['paper_order'] = $paper['paper_no'];
				$data['sub_group_id'] = $paper['sub_group_id'];
				$this->Common_model->insertAll('new_exam_form',$data);
			echo $this->db->last_query().'<br>';
			}
			$this->Common_model->updateRecordByConditions('student',$where,array('temp_exam_form' => "Y"));
		}
	}

	public function new_exam_form_permission()
	{
		$this->load->view('admin/script/header',array('title' => 'New Exam Form Permission'));
		$classes = $this->Common_model->get_record('class_master','GROUP_CONCAT(id) as class_id',array('exam_form_permission' => 'Y'));
		$class_ids = $classes[0]['class_id'];
		
		$this->db->select('count(class_id) as num,course_name,class_name,class_id');
		$this->db->where('class_id in ('.$class_ids.') and new_exam_form="D" and new_admission_permission="N" and enrolled = "Y" and (( session="July 2022" and class_name = "I Year") || ( session="Jan 2023" and class_name = "I SEM"))');
		$this->db->group_by('class_id');
		$this->db->order_by('course_group_id');
		$studentClasses = $this->db->get('student')->result();
		$data = array('studentClasses' => $studentClasses);
		$this->load->view('admin/script/new_exam_form_permission',$data);
		$this->load->view('admin/script/footer');
	}


	public function new_exam_form_permission_sub($class_id){
		$classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		$where = array('class_id' => $class_id,
					'enrolled' => "Y",
					'new_exam_form' => "D",
					'enrollment_no !=' =>'-',
					'new_admission_permission' => "N",
					// 'session'=>"July 2022"
					
				);
		//'temp_exam_form' => "Y",
		$this->db->where('((session="July 2022" and class_name = "I Year") || ( session="Jan 2023" and class_name = "I SEM"))');
		
		$students = $this->Common_model->get_record('student','*',$where);
		// $this->Common_model->last_query();

		foreach ($students as $student) {
			$where = array('student_id'=>$student['student_id']);
			$this->Common_model->updateRecordByConditions('student',$where,array('new_exam_form' => "N"));
		}
	}

	
	// Fetching Student record & Update  exam center by Center ID 
    public function update_stdent_exam_center($startlimit=1){
        echo "update_stdent_allottment_exam_center<br>";
        $this->db->select('*');
        $this->db->from('student');
        $this->db->where('exam_center_id','0');
        //$this->db->where('examcentercode','NU');
        $start=0;
		//$start=($startlimit-1)*1000;
		$this->db->limit(2000,$start);
        $rows=$this->db->get()->result();
        //echo $this->db->last_query();
        $i=1;
         foreach($rows as $row){
            $this->db->select('*');
            $this->db->from('allot_exam_center');
            $this->db->where('center_id',$row->center_id);
            $allottment=$this->db->get()->result();
            
            
            if(!empty($allottment)){
             
                $data  = array('exam_center_id'=>$allottment[0]->exam_center_id ,'examcentercode'=>$allottment[0]->examcentercode );
                $where = array('student_id'=>$row->student_id);
                $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
                echo $i." ".$row->	center_code." ".$row->student_id." ".$row->name." Exam Code =>".$allottment[0]->examcentercode." <br>";
               $i++;
            }
           
           
         }
    }

	// backlog
	public function update_backlog_stdent_allottment_exam_center($startlimit=1){
        echo "update_stdent_allottment_exam_center<br>";
        $this->db->select('*');
        $this->db->from('backlog_student');
        $this->db->where('exam_center_id','0');
		$this->db->where('exam_year','June 2023');
        //$this->db->where('examcentercode','NU');
        $start=0;
		//$start=($startlimit-1)*1000;
		$this->db->limit(2000,$start);
        $rows=$this->db->get()->result();
        //echo $this->db->last_query();
        $i=1;
         foreach($rows as $row){
            $this->db->select('*');
            $this->db->from('allot_exam_center');
            $this->db->where('center_id',$row->center_id);
            $allottment=$this->db->get()->result();
            
            
            if(!empty($allottment)){
             
                $data  = array('exam_center_id'=>$allottment[0]->exam_center_id ,'exam_center_code'=>$allottment[0]->examcentercode );
                $where = array('student_id'=>$row->student_id,'exam_year'=>'June 2023');
                $update =$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
                echo $i." ".$row->	center_code." ".$row->student_id." ".$row->name." Exam Code =>".$allottment[0]->examcentercode." <br>";
               $i++;
            }
           
           
         }
    }
	
	


	
	public function generate_roll_no(){		
		if(isset($_POST['action']) && $_POST['action']=='generate'){
			$data = array(			
				 'action' => 'generate',
			);
		}else if( isset($_POST['action']) && $_POST['action']=='view'){
			$data = array(
				 'action' => 'view',
			);
		}
		else{
			$data = array(
				'student' => '',
				'action' => '',
			);
		}

		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
			
		$this->load->view('header',array('title' => 'Generate Roll Number'));
		$this->load->view('admin/script/generate_roll_no',$data);
		$this->load->view('footer');
	}

	public function generate_backlog_roll_no(){		
		if(isset($_POST['action']) && $_POST['action']=='generate'){
			$data = array(			
				 'action' => 'generate',
			);
		}else if( isset($_POST['action']) && $_POST['action']=='view'){
			$data = array(
				 'action' => 'view',
			);
		}
		else{
			$data = array(
				'student' => '',
				'action' => '',
			);
		}

		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
			
		$this->load->view('header',array('title' => 'Generate Backlog Roll Number'));
		$this->load->view('admin/script/generate_backlog_roll_no',$data);
		$this->load->view('footer');
	}


	public function add_practical(){
		$this->db->select('paper_master.id ,paper_master.class_id , student_id ,paper_master.course_group_id,paper_master.paper_code,type,paper_master.paper_no');
		$this->db->from('student');
		$this->db->join('paper_master', 'paper_master.class_id = student.class_id');
		$this->db->where('temp_exam_form','Y');
		$this->db->where('paper_master.class_id IN(154,181,223,225,199)');      
		$this->db->where('type!=','theory');  
		// $this->db->limit(4);
		$new_exam_forms = $this->db->get()->result();
   		// $this->Common_model->last_query();

		foreach($new_exam_forms as $new_exam_form){
			$data = array(
				'student_id'=>$new_exam_form->student_id ,
				'course_group_id'=>$new_exam_form->course_group_id ,
				'class_id'=>$new_exam_form->class_id ,
				'paper_code'=>$new_exam_form->paper_code ,
				'theory_marks'=>"" ,
				'paper_id'=>$new_exam_form->id ,
				'int_marks'=>"N",
				'p_marks'=>'N',
				'paper_type'=>$new_exam_form->type,
				'paper_order'=>$new_exam_form->paper_no,
				'group_id'=>"" ,
				'book_code'=>"0" ,
			);

			$count = $this->Common_model->getCountByWhere('new_exam_form',array('paper_code'=>$new_exam_form->paper_code, 'paper_type'=>'practical','class_id'=>$new_exam_form->class_id,'student_id'=>$new_exam_form->student_id));
			
			if($count<1){
				$q = $this->Common_model->insertAll('new_exam_form',$data);
				echo "<br>";
				echo $this->db->last_query();  
			}
		}
	}	
	// Update Exam fields in Paper Master from paper_paper_master_sub table
	public function update_exam_fields_from_paper_master_sub_table(){
		echo "Update Exam Data in Paper Master";
		$this->db->select('*');
       		$this->db->from('paper_master_sub');
		$rows=$this->db->get()->result();
		$i=1;
		foreach($rows as $row){

			echo "<br> ".$i." ".$row->papersname ." ". $row->papercode ." ". $row->new_test_id;
			$data  = array('exam_date'=>$row->new_exam_date , 'exam_day'=>$row->new_exam_day, 'exam_shift'=>$row->new_exam_shift);
            $where = array('test_id'=>$row->new_test_id,'paper_code'=> $row->papercode,'exam_date'=>'0000-00-00');
            $update =$this->Common_model->updateRecordByConditions('paper_master',$where,$data);
		//	echo  $this->db->last_query(); die;
			$i++;
		}
	}
	//Update exam center code in allot_exam_center table
	public function update_exam_center_code_in_allot(){
		$this->db->select('*');
        $this->db->from('allot_exam_center');
		$this->db->where('examcentercode','0');
		$rows=$this->db->get()->result();
		$i=1;
		foreach($rows as $row){

			echo "<br> ".$i." ".$row->exam_center_id ." ". $row->center_id ." ". $row->examcentercode;
			$examCenterData = $this->Common_model->getRecordById('exam_center','id',$row->exam_center_id);
			$data  = array('examcentercode'=>$examCenterData->examcentercode ,);
            $where = array('id'=>$row->id,'exam_center_id'=> $row->exam_center_id, );
            $update =$this->Common_model->updateRecordByConditions('allot_exam_center',$where,$data);
	
			$i++;
		}

	}


	public function upload_group_exam_paper(){
		$this->load->view('admin/script/header',array('title' => 'Upload Exam Paper'));
		/* class which dose not have elective papers */
		
		//$classes = $this->Common_model->get_record('class_master','GROUP_CONCAT(id) as class_id',array('class_group' => 'N'));
		//,'exam_form_permission' => 'Y','admission_permission' => 'Y'
		//$class_ids = $classes[0]['class_id'];
		$class_ids =216; //105;
		$this->db->select('count(class_id) as num,course_name,class_name,class_id');
		$this->db->where('class_id in ('.$class_ids.') and temp_exam_form="N" and demo="N"');
		// and payment_status="Y" // and new_exam_form="N"
		$this->db->group_by('class_id');
		$this->db->order_by('course_group_id');
		$studentClasses = $this->db->get('student')->result();
		$i=0;
		
		
		
		// $this->Common_model->last_query();
		$data = array(
			'studentClasses' => $studentClasses,
		);
		$this->load->view('admin/script/upload_group_exam_paper',$data);
		$this->load->view('admin/script/footer');
	}

	public function upload_group_exam_paper_sub($class_id)
	{
		$where = array('class_id' => $class_id,
					//'payment_status' => 'Y',
					'temp_exam_form' => "N",
					'new_exam_form'=>"N" ,
					'demo'=>"N",
					'group_id!='=>"",
		);
		$this->db->limit(2000,0);
		$students = $this->Common_model->get_record('student','*',$where);
		
		$paperWhere=array('class_id'=>$class_id,'ce'=>'compulsory');
		$papers = $this->Common_model->get_record('paper_master','*',$paperWhere);
		$stCount=0;
		foreach ($students as $student) {
			$stCount++;
			$where = array('student_id'=>$student['student_id']);
			
			$group_id=$student['group_id'];
		     echo "<br> Sr No.  ".$stCount."<br> Check Group ID ".$student['group_id']."<br>";
				//for elective
				
					$electiveWhere=array('id'=>$student['group_id'],'class_id'=>$student['old_class_id'],'course_group_id'=>$student['course_group_id']);
					$elective = $this->Common_model->get_record('group','*',$electiveWhere);
					
					
					$electiveGroupWhere=array('class_id'=>$student['class_id'],'course_group_id'=>$student['course_group_id'],'group_name'=>$elective[0]['group_name']);
					$electiveGroup = $this->Common_model->get_record('group','*',$electiveGroupWhere);
					
					$data = array(
						'student_id' => $student['student_id'],
						'course_group_id' => $student['course_group_id'],
						'class_id' => $student['class_id'],
						);
					
					foreach ($papers as $paper) {
						if($student['university_mode']=="PVT" && $paper['type']=='theory' )
						{ 
						$data['paper_id'] = $paper['id'];
						$data['paper_code'] = $paper['paper_code'];
						$data['paper_type'] = $paper['type'];
						$data['paper_order'] = $paper['paper_no'];
						$data['sub_group_id'] = $paper['sub_group_id'];
						$data['group_id'] = $electiveGroup[0]['id'];
						$this->Common_model->insertAll('new_exam_form',$data);
						}
						if($student['university_mode']=="REG"  )
						{ 
						$data['paper_id'] = $paper['id'];
						$data['paper_code'] = $paper['paper_code'];
						$data['paper_type'] = $paper['type'];
						$data['paper_order'] = $paper['paper_no'];
						$data['sub_group_id'] = $paper['sub_group_id'];
						$data['group_id'] = $electiveGroup[0]['id'];
						$this->Common_model->insertAll('new_exam_form',$data);
						}
						
						echo $this->db->last_query().'<br>';
											
					}

					$electivePaperWhere=array('group_id'=>$electiveGroup[0]['id']);
					$electivePapers = $this->Common_model->get_record('group_paper','*',$electivePaperWhere);
					$electiveData = array(
						'student_id' => $student['student_id'],
						'course_group_id' => $student['course_group_id'],
						'class_id' => $student['class_id'],
						);
					echo "Elective paper";
					echo $this->db->last_query().'<br>';
					
					foreach ($electivePapers as $electivePaper) {

						$paperMasterWhere=array('id'=>$electivePaper['paper_id']);
						$paperMaster = $this->Common_model->get_record('paper_master','*',$paperMasterWhere);
						echo $paperMaster[0]['type'];
						if($student['university_mode']=="PVT" && $paperMaster[0]['type']=='theory' )
						{ 
							$electiveData['paper_id'] = $electivePaper['paper_id'];
							$electiveData['paper_code'] = $electivePaper['paper_code'];
							$electiveData['paper_type'] = $paperMaster[0]['type'];
							$electiveData['paper_order'] = $paperMaster[0]['paper_no'];
							$electiveData['sub_group_id'] = $electivePaper['sub_group_id'];
							$electiveData['group_id'] = $electivePaper['group_id'];
							$this->Common_model->insertAll('new_exam_form',$electiveData);
						}
						if($student['university_mode']=="REG"){
							$electiveData['paper_id'] = $electivePaper['paper_id'];
							$electiveData['paper_code'] = $electivePaper['paper_code'];
							$electiveData['paper_type'] = $paperMaster[0]['type'];
							$electiveData['paper_order'] = $paperMaster[0]['paper_no'];
							$electiveData['sub_group_id'] = $electivePaper['sub_group_id'];
							$electiveData['group_id'] = $electivePaper['group_id'];
							$this->Common_model->insertAll('new_exam_form',$electiveData);
						}
						
						echo $this->db->last_query().'<br>';
						$group_id=$electivePaper['group_id'];
					}	
					
					
				
				$this->Common_model->updateRecordByConditions('student',$where,array('group_id'=>$group_id,'temp_exam_form' => "Y"));
				//if($stCount==1)
				//break;	
			
			
		}
	}

}

?>
