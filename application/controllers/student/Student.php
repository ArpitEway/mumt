<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
	}
	
	public function index(){
		if($this->session->has_userdata('studentdata')){
			redirect(base_url('dashboard'));
		}else{
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('students/login',$csrf);
		}
	}
	
	public function dashboard(){
		if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url(''));
		}else{
		    $titleData = array('title' => 'Student Dashboard','page_slug' => ''); 
			$this->load->view('students/header',$titleData);
			$id =  $this->session->student_id;
			$student = $this->Common_model->getRecordById('student','student_id',$id);
			
			$data = array('studentData' => $student);
			//$this->getNotification();
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->load->view('students/dashboard',$data);
			$this->load->view('students/footer');
		}	
	}
	
	public function login(){

		$csrf = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('students/login',$csrf);
	}

	public function loginSub(){
		
		 if($this->session->has_userdata('studentdata')){
		 	redirect(base_url('dashboard'));
			 exit;
		  }

		$this->form_validation->set_rules('enrollment_no', 'Enrollment_no', 'required');
		$this->form_validation->set_rules('dob', 'dob', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
				$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
			);
				$this->load->view('students/login',$csrf);
		}
		else
		{ 

		
			$username = $_POST['enrollment_no'];
			$password = $_POST['dob'];
			
			$check_user = $this->Student_model->checkStudent($username,$password);
			//print_r($check_user); die;
			if($check_user){
				
				$data = array(
							'loged_in' 	  => true,
							'studentdata' => $check_user->enrollment_no,
							'dob' 	  	  => $check_user->dob,
							'student_id'  => $check_user->student_id,
							'admission_by' =>$check_user->admission_by
							//'Users_id'  => $check_user->user_id
						);
				
				$this->session->set_userdata($data);
		
			// $this->Student_model->checkStudentForm();
			redirect(base_url('dashboard'));
			}else{

			$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
		$this->session->set_flashdata('error','Enrollment no or Password are incorrect');
		
		$this->load->view('students/login',	$csrf );
		
		}
	}
}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}

	public function profile(){
	if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url('students/login'));
		}
		$student_id = $this->session->student_id;
		$data = array();
		$data['student'] = $this->Common_model->student_info($student_id);
		
		$this->load->view('students/header',array('title' => 'Student Form','page_slug' => 'profile'));	
		$this->load->view('template/form',$data);
		$this->load->view('students/footer');
	}
	

	private function getNotification(){
		$student_id = $this->session->student_id;
		if($student_id!=''){
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);
			$studentdata = array('student' => $student);
			//$this->load->view('users/header');
			$this->load->view('students/notification',$studentdata);
			//$this->load->view('users/footer');	
		}
	}

	public function exam_paper(){
		if(!$this->session->has_userdata('studentdata')){
			redirect(base_url('students/login'));
	   }
	   $data = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
	);
	    $student_id = $this->session->student_id;
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->db->select('paper_master.*');
    	$this->db->from('paper_master');
    	$this->db->join('new_exam_form', 'paper_master.id = new_exam_form.paper_id');
    	
    	$class_id = $data['student']['class_id'];

    	$where = array('paper_master.class_id' =>$data['student']['class_id'],
    		'student_id' => $student_id ,'paper_type'=>'theory'
    	);
    	$this->db->where($where); 
    	$data['papers'] = $this->db->get()->result();
		$whereClass = array('class_id' => $class_id,
					'exam_permission' => 'Y',
		);
		$timeTableData = $this->Common_model->getRecordByWhere('time_table',$whereClass);
		if((count($timeTableData)==0) || ($data['student']['new_exam_form']!='Y')){
			redirect(base_url());
		}
	    $this->load->view('students/header',array('title' => 'Exam Paper','page_slug' => 'exam_paper'));	
		$this->load->view('students/exam_paper',$data);
		$this->load->view('students/footer');
	}

	public function upload_anwser_sheet($paper_id){
		$paper_id = $this->Common_model->encrypt_decrypt($paper_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$data['paperData'] = $this->Common_model->getRecordById('paper_master','id',$paper_id);
		$student_id = $this->session->student_id;
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->load->view('students/header',array('title' => 'Upload Answer Sheet'));	
		$this->load->view('students/upload_answer_sheet',$data);
		$this->load->view('students/footer');
	}

	public function upload_assignment_sub(){
		if($_FILES['file']['name']!='')
		{
			$ext1=strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
			$fname=$_POST['student_id']."_".$_POST['paper_code'];
			$document_image = $fname.".".$ext1;
			$date = date('Y-m-d');
			if (!is_dir('assets/exam_answersheet/'.$date)) {
				mkdir('assets/exam_answersheet/'.$date, 0777, TRUE);
			}
			$upload_file = move_uploaded_file($_FILES['file']['tmp_name'],"assets/exam_answersheet/".$date.'/'.$document_image);

			if($upload_file){
				$data = array('student_id' =>$_POST['student_id'],
					'course_group_id' =>$_POST['course_group_id'],
					'class_id' =>$_POST['class_id'],
					'paper_code' =>$_POST['paper_code'],
					'center_id' =>$_POST['center_id'],
					'answer_sheet' =>$document_image ,
					'upload_date' =>date("Y-m-d") ,
					'exam_status' => 'R',
					'file_exist' => 'Y'
				);
				$where = array(
					'class_id' => $_POST['class_id'],
					'student_id' => $_POST['student_id'],
					'paper_code' =>$_POST['paper_code']
				);
				$ansSheetCount = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
				if($ansSheetCount>0){
					$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
				}else{
					$insert = $this->Common_model->insertAll('upload_exam_ans_sheet',$data);
				}
			}
		}
	}

	public function delete_exam_answersheet($anssheet_id)
	{
		$where = array('id' =>$anssheet_id);
		$ansdata =	$this->Common_model->getRecordById('upload_exam_ans_sheet','id',$anssheet_id);
		$data = array('answer_sheet' => '',
						'upload_date' => '',
						'file_exist' => 'N',
						'old_upload_date' => $ansdata->upload_date,
					);
		$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
		redirect(base_url('exam_paper'));
	}

    public function student_model_paper(){
		if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url('students/login'));
		}
		$this->load->view('students/header',array('title'=>'Model Paper', 'page_slug' => 'student_model_paper'));
		$student_id = $this->session->student_id;
		$this->db->select('*');
		$this->db->from('paper_master');
		$this->db->where('student_id',$student_id);
		$this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
		$data['students'] = $this->db->get()->result();
		$this->load->view('students/student_model_paper',$data);
		$this->load->view('students/footer');					
	}

	public function student_login($student_id){
		
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$results=   $this->Common_model->getRecordById('student','student_id',$student_id);;
		
		if($results){
			$data = array(
				'loged_in' 	  => true,
				'studentdata' => $results->enrollment_no,
				'dob' 	  	  => $results->dob,
				'student_id'  => $student_id,
				'admission_by' =>$results->admission_by
				//'Users_id'  => $check_user->user_id
			);
			
			$this->session->set_userdata($data);
		}
	
		redirect(base_url('dashboard'));
	}

	public function admission_form(){
	// 	if(!$this->session->has_userdata('studentdata')){
	// 		redirect(base_url('students/login'));
	//    }
	
	   if($this->session->admission_by!="web"){
			redirect(base_url('login'));
	   }
	   $titleData = array('title' => 'Student Admission Form','page_slug' => 'admission_form'); 
			$this->load->view('students/header',$titleData);
			$id =  $this->session->student_id;
			$student = $this->Common_model->getRecordById('student','student_id',$id);
			$data = array('student' => $student);
			//$this->getNotification();
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();

			//$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
			$student_id=$this->session->student_id;
			$titleData = array('title' => 'Admission Form'); 
			$state_list = $this->Common_model->get_record('state','*');
			$eligibility_list = $this->Common_model->get_record('course_group','DISTINCT (eligibility)');
			$district_list = $this->Common_model->get_record('distt','*');
			$course_group_list = $this->Common_model->get_record('course','*');

			$data = array(
				'state_list' => $state_list,
				'district_list' => $district_list,
				'course_group_list' => $course_group_list,
				'eligibility_list' => $eligibility_list,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'student_detail' => $this->db->get_where('student', array("student_id" => $student_id))->row(),
				'student_data'  => $this->db->get_where('student_data', array("student_id" => $student_id))->row()
			);


			$this->load->view('students/admissionForm',$data);
			$this->load->view('students/footer');

	}

	public function select_papers($student_id){
		if($this->session->admission_by!="web"){
			redirect(base_url('students/login'));
	   }
	   $student_id  =  $this->session->student_id;
	//	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData['title'] = 'Select Papers';
		$this->load->view('students/header',$titleData);
		$student = $this->Common_model->student_info($student_id);
		$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
		$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
		if($student['temp_exam_form'] == "Y"){
			$std_id = $this->Common_model->encrypt_decrypt($student_id);
			redirect(base_url('center/center/showPapers/'.$std_id.''));	
		}
		$this->db->order_by('id');
		if($student['university_mode'] != "PVT"){
		
		$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory" and cbcs_paper="'.$cbcs.'"');
		$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id join paper_master as m on m.id=p.paper_id where g.class_id='.$student['class_id'].' and cbcs_paper="'.$cbcs.'"  Order by g.id,p.sub_group_id,p.id')->result();
		//echo $this->Common_model->last_query();
		}else{
			$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory" and type="theory" and cbcs_paper="'.$cbcs.'"');
			 $this->db->select('p.*,g.group_name') ;
			 $this->db->from('group_paper as p');
			 $this->db->join('group as g','g.id = p.group_id');
			 $this->db->join('paper_master','paper_master.id = p.paper_id') ;
			$this->db->where(array('g.class_id'=>$student['class_id'],'paper_master.type'=>"theory", 'cbcs_paper'=> $cbcs));
			$groupPaper =$this->db->get()->result();	
		
		}
		
		$data['compulsoryPapers'] = $compulsoryPapers;
		$data['student'] = $student;
		$data['student_id'] = $student['student_id'];
			// // CONDITION FOR GROUP PAPER
		$this->db->select('class_group,select_group,group_type');
		$this->db->from('class_master');
		$this->db->join('student', 'class_master.id = student.class_id');
		$this->db->where(array('class_master.id' => $data['student']['class_id'],
			'student_id' => $student['student_id']
		));
		$class_group = $this->db->get()->result();

		$data['class_group'] = $class_group;

		$data['groupPaper'] = $groupPaper;


		if($class_group[0]->group_type=='Paper'){
			$this->load->view('Centers/select_papers',$data);
		}
		else{
			$this->load->view('Centers/select_group',$data);
		}
		$this->load->view('students/footer');

	}

	public function showPapers($student_id){
		
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$titleData = array('title' => 'Student Papers');
		$this->load->view('students/header',$titleData);

		$where = array(
			'student_id' => $student_id,
		);
		$student = $this->Common_model->student_info($student_id);
		$data['student'] = $student;
		$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
		$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
		$this->db->select('paper_master.*,new_exam_form.sub_group_id as sub_group');
		$this->db->from('paper_master');
		$this->db->order_by('new_exam_form.sub_group_id,paper_master.cbcs_paper,paper_order');
		$this->db->join('new_exam_form', 'paper_master.paper_code = new_exam_form.paper_code and  paper_master.class_id = new_exam_form.class_id');
		$where = array('paper_master.class_id' => $student['class_id'],
			'student_id' => $student_id,'cbcs_paper'=>$cbcs
		);
		$this->db->where($where);
		$data['papers'] = $this->db->get()->result();
		$data['name_csrf']= $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		
		// $this->Common_model->last_query();
		$this->load->view('Centers/showPapers',$data);
		$this->load->view('students/footer');
	
}
}