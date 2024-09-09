<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Otherscript extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
		$this->load->model('users/User_model');
		if(!$this->session->has_userdata('loged_in')){
			exit;
		}
	}
	
	// Update Exam fields in Paper Master from paper_paper_master_sub table
	public function move_student_document(){
		echo "Move Student Document  ";
		$this->db->select('*');
       	$this->db->from('student');
        $this->db->join('admission_document', 'admission_document.student_id = student.student_id');   
        $this->db->where('approved ="Y" and document_uploaded ="Y" and enrolled = "Y" and move="N"');   
        $start=0;
		$this->db->limit(1000,$start);
		$rows=$this->db->get()->result();
		$i=1;
		foreach($rows as $row){

		 	echo "<br> ".$i." ".$row->student_id ." ". $row->firstname ." ". $row->session;
		
	
			$source=FCPATH."/assets/documents/".$row->document_image;
		
			$destination=FCPATH."/assets/enrolled_documents/".$row->session."/".$row->document_image;
		
			$dirname = FCPATH."/assets/enrolled_documents/".$row->session;

			if(!is_dir($dirname)){
				mkdir( $dirname, 0777);
				echo "The directory $dirname was successfully created.";
			
			} 

			if( rename( $source , $destination )){
				echo '<br>moved!'.$destination;
				$data  = array('move'=>'Y' );
				$where = array('student_id'=>$row->student_id,'id'=> $row->id);
				
				$update =$this->Common_model->updateRecordByConditions('admission_document',$where,$data);
			} 

		 	$i++;
		}
	}
	
	public function update_extra_papers_in_new_exam()
	{
		//$where = " paper_code in ('2RMAEDU5', '2RMAGEO6', '2RMAGEO7', '2RMAGEO8', '2RMAPSY5', '2RMSCC7', '2RMSCC8', '2RMSCM7', '2RMSW7')";
		// $where = " paper_code in ('2RBED6')";
		//$where = " paper_code in ('1RMLIS10','1RMLIS11')";
		//$where="`paper_code` in ('1RCMSCCH7','1RCMSCCH8','1RCMSCCH9','1RCMSCBT7','1RCMSCBT8','1RCMSCC7','1RCMSCM7','3RMSCC6','3RMSCC7')";
		$where="`id` in (382,392,292)";
		//in (947,1799,985,1800,1054,1801)";
		// $where="`id` = 292";
		$papers = $this->Common_model->get_record('paper_master','*',$where);

		foreach ($papers as $paper) {
			$where = ' class_id = "'.$paper['class_id'].'" and temp_exam_form="Y" and  exam_pattern="GRADE"  and university_mode="PVT" ';
			
			//student_id not in (703394,703395,683403,685044,685047,686312,689208,702853,723869,724934)
			$students = $this->Common_model->get_record('student','*',$where);
			$studentData = array(
				'course_group_id' => $paper['course_group_id'],
				'class_id' => $paper['class_id'],
				'paper_id' => $paper['id'],
				'paper_code' => $paper['paper_code'],
				'paper_type' => $paper['type'],
				'paper_order' => $paper['paper_no'],
			);
			foreach ($students as $student) {
				$where = ' class_id = "'.$paper['class_id'].'" and student_id=  "'.$student['student_id'].'" and  paper_id="'.$paper['id'].'"  ';
				$examData = $this->Common_model->get_record('new_exam_form','*',$where);
				if(!$examData){
					echo	$studentData['student_id'] = $student['student_id'];
				    echo "<br>";
				    $this->Common_model->insertAll('new_exam_form',$studentData);
				    echo $this->db->last_query().'<br>';
				}
				
			}
		}	
	}
	 
	public function move_practical_marks(){
		
		$this->db->select('exam_form.*');
		$this->db->from('exam_form');
		
		$this->db->join('new_exam_form', 'exam_form.student_id=new_exam_form.student_id and exam_form.p_marks != new_exam_form.p_marks and exam_form.class_id = new_exam_form.class_id and exam_form.paper_code = new_exam_form.paper_code');
		$this->db->join('student', 'exam_form.student_id=student.student_id');
		$where = array('exam_form.paper_type !='=>'theory','new_exam_form.p_marks !='=>'N','student.new_exam_form'=>'Y','student.p_marks_sub'=>'Y','demo'=>'N');
		$this->db->where($where);
		$data = $this->db->get()->result();
		
        	foreach($data as $dt){
			$pmark  = array('p_marks'=>$dt->p_marks);
			$where = array('id'=> $dt->id);
			//$update =$this->Common_model->updateRecordByConditions('new_exam_form',$where,$pmark);
		}
		
	}

	public function student_photo(){
	
		$this->db->select('student_id,name,photo,session');
		$this->db->from('student');
		
		$start=0;
		$this->db->limit(30,$start);
		$result = $this->db->get()->result();
		
		foreach ($result as $row){
			$dir = './assets/student_image/'.$row->session;
		
			$files = glob( './assets/student_image/'.$row->session.'/'.$row->photo.'');

			foreach($files as $key => $value){
				if($value){
					$std .=  $row->student_id.'/';
				}

			
			} 
		}

		$de = explode('/',$std);
			
		$this->db->select('student_id');
		$this->db->from('student');
		$this->db->where_not_in('student_id',$de);
	
		$this->db->limit(10);
		$rs = $this->db->get()->result();
		// echo $this->db->last_query().'<br>';
		foreach ($rs as  $val) {
			echo $val->student_id.",";
		}
				
			
	}
	public function update_old_result_show(){
		
		$result = $this->Common_model->getRecordByWhere("student",array("exam_form"=>'Y'));
		
		foreach($result as $row){
			
			    $data  = array('old_result_show'=>$row->result_show);
				$where = array('exam_form'=>'Y','student_id'=>$row->student_id);
				
				// $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}
	}
	

	public function update_int_marks($cls_id =0)
	{
		//$marks = array('18','17','16','18','17','16','15','15');
		// $marks = array('09','08','07','09','08','07','06','06');
		  $marks = array('27','26','25','27','26','25','24','24','23'); 
		$cls_id=215;
		
		$sql = "select * from student where class_id='".$cls_id."' and exam_form='Y' and int_marks_sub='N' and roll_number!=0 and university_mode='REG' limit 100";
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type='Theory' and int_marks in ('N') and class_id='".$cls_id."' and paper_code not in ('1RBA1','1RBA2','1RBA3','1RBA4','1RMOM1') and sub_group_id!='1' ORDER by rand()";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$i=0;
			foreach ($new_exam_rs as $new_exam_data) {
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data['paper_code'].' Marks=>'.$marks[$i].'<br>';

				$update_marks = "update new_exam_form set int_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				$i++;
				$this->db->query($update_marks);
			}
			$update_student = "update student set int_marks_sub='Y' where student_id='".$student['student_id']."' and class_id='".$cls_id."'";

			$this->db->query($update_student);
		}
		
	}

	public function update_practical_marks($cls_id =0)
	{
		$marks = array('43','42','41','40'); 
		// $marks = array('85','84','83','82'); 
		// $marks = array('68','67','66','65');
		 // $marks = array('60','59','58','57');
		$cls_id=182;
		$sql = "select * from student where class_id='".$cls_id."' and exam_form='Y' and p_marks_sub='N' and roll_number!=0 and university_mode='REG' order by roll_number limit 100";
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type='Practical' and p_marks in ('N') and class_id='".$cls_id."' ORDER by rand()";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$i=0;
			foreach ($new_exam_rs as $new_exam_data) {
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data['paper_code'].' Marks=>'.$marks[$i].'<br>';

				$update_marks = "update new_exam_form set p_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				$i++;
				$this->db->query($update_marks);
				shuffle($marks);
			}
			  // $update_student = "update student set p_marks_sub='Y' where student_id='".$student['student_id']."' and class_id='".$cls_id."'";

			 // $this->db->query($update_student);
		}
	}

	public function update_project_marks($cls_id =0)
	{
		//$marks = array('166','168','170','165'); 
		$marks = array('85','84','83','82'); 
		$cls_id=215;
		$sql = "select * from student where class_id='".$cls_id."' and exam_form='Y' and p_marks_sub='N' and  university_mode='REG' and exam_pattern= 'GRADE' and  roll_number!=0 order by roll_number  limit 100";
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type='Project' and p_marks in ('N') and class_id='".$cls_id."' ORDER by rand()";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$i=0;
			foreach ($new_exam_rs as $new_exam_data) {
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data['paper_code'].' Marks=>'.$marks[$i].'<br>';

				$update_marks = "update new_exam_form set p_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				//$update_marks_exam_form = "update exam_form set p_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				$i++;
				$this->db->query($update_marks);
				//$this->db->query($update_marks_exam_form);
				shuffle($marks);
			}
			$update_student = "update student set p_marks_sub='Y' where student_id='".$student['student_id']."' and class_id='".$cls_id."'";

			$this->db->query($update_student);
		
		}
	}
	public function update_oldexam_marks($cls_id =0)
	{

		$cls_id=227;
		$old_totoal_marks=600;
		$paper_master_total_marks=700;

		$sql = "SELECT * FROM `old_exam_data` WHERE `class_id`='".$cls_id."'  AND total_marks='".$old_totoal_marks."' AND exam_year='March 2023'  limit 250";
		$rs = $this->db->query($sql)->result_array();
		$i=0;
		foreach ($rs as $student) {
			$new_exam_sql = "SELECT SUM(p_marks) + SUM(int_marks)  as tot FROM `old_result_data` WHERE `exam_data_id`='".$student['id']."' and `type`!='Theory'";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$obtain_marks=$student['obtain_marks']+$new_exam_rs[0]['tot'];
			echo "<br> $i=".$student['enrollment_no']."<br>";
			$percentage=round(($obtain_marks/$paper_master_total_marks)*100,2);
				// $percentage=($obtain_marks/$total_marks)*100;
			echo $update_marks = "update old_exam_data set total_marks='".$paper_master_total_marks."',obtain_marks='".$obtain_marks."',percentage='".$percentage."' where id=".$student['id'];
			$i++;

			// $this->db->query($update_marks);
		}
	}

	public function remaining_failed_student_marks($class_id=0){
		$class_id=104;
		$university_mode='PVT';
		$this->db->select('count(*) as num,student.*');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.class_id');
		$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
		$this->db->order_by('new_exam_form.course_group_id,new_exam_form.class_id','asc');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('university_mode',$university_mode);
		$this->db->where('new_exam_form.theory_marks!=','');
		$this->db->where('exam_form','Y');
		if ($university_mode=='REG') {
			$this->db->where('new_exam_form.theory_marks < paper_master.min_theory_marks');
		}else{
			$this->db->where('new_exam_form.theory_marks < paper_master.private_min_theory_marks');
		}
		$this->db->where('new_exam_form.class_id',$class_id);
		// //$this->db->where_in('roll_number',array());
		$this->db->where('paper_type','theory');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->having('num',1);
		$data['students'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		
		$this->load->view('header',array('title' => 'Student Failed Remaining Marks List'));
		$this->load->view('admin/remaining_student_average_marks_offline',$data);
		$this->load->view('footer');
	}

	public function update_student_remaining_marks($student_id,$nefId,$marks)
	{
		// $this->Common_model->updateRecordByConditions('new_exam_form',array('id'=>$nefId,'student_id'=>$student_id),array('theory_marks'=>$marks));
		echo "<title>MMYVVONLINE</title>";
		echo $this->db->last_query();
	}

	public function update_exam_fields_of_paper_master_table(){
		echo "Update Exam Data in Paper Master";
		$this->db->select('*');
       		$this->db->from('paper_testid_relation');
		$rows=$this->db->get()->result();
		$i=1;
		foreach($rows as $row){

			echo "<br> ".$i." ". $row->paper_code ." ". $row->test_id;
			$data  = array('test_id'=>$row->test_id);
            $where = array('test_id'=>'0','paper_code'=> $row->paper_code,'id'=> $row->paper_id);
            $update =$this->Common_model->updateRecordByConditions('paper_master',$where,$data);
			 echo  $this->db->last_query(); //die;
			$i++;
		
		// echo $this->db->last_query().'<br>';
		}
	}
	public function remaining_failed_student_list(){
		$university_mode='REG';
		$this->db->select('count(*) as num,student.*');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.old_class_id');
		$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('university_mode',$university_mode);
		$this->db->where('exam_form','Y');
		if ($university_mode=='REG') {
			$this->db->where('new_exam_form.theory_marks < paper_master.min_theory_marks');
		}else{
			$this->db->where('new_exam_form.theory_marks < paper_master.private_min_theory_marks');
		}
		// $this->db->where('new_exam_form.class_id',$class_id);
		$this->db->where_in('old_class_id',array(162,165,180,183,170,173,134,116,101,169,110,125,128,131));
		$this->db->where('roll_number !=','');
		$this->db->where('theory_marks','');
		$this->db->where('paper_type','theory');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->order_by('student.course_group_id,student.old_class_id,student.roll_number','asc');
		$data['students'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		
		$this->load->view('header',array('title' => 'Student Failed Remaining Marks List'));
		$this->load->view('admin/remaining_student_average_marks_offline',$data);
		$this->load->view('footer');
	}

	public function remaining_withheld_student_list(){
		$class_id=104;
		$university_mode='PVT';
		if ($university_mode=='REG') {
		$this->db->select('count(*) as num,student.*,new_exam_form.paper_code,paper_master.min_theory_marks as min_marks, paper_master.max_theory_marks as max_marks');
		}else{
			$this->db->select('count(*) as num,student.*,new_exam_form.paper_code,paper_master.private_min_theory_marks as min_marks,private_max_theory_marks as max_marks');
		}
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.class_id');
		$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
		$this->db->join('student_av_check', 'student_av_check.enrollment_no_check = student.enrollment_no and student_av_check.paper_code_check=new_exam_form.paper_code');
		$this->db->order_by('new_exam_form.course_group_id,new_exam_form.class_id','asc');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('university_mode',$university_mode);
		$this->db->where('new_exam_form.theory_marks','');
		$this->db->where('exam_form','Y');
		if ($university_mode=='REG') {
			$this->db->where('new_exam_form.theory_marks < paper_master.min_theory_marks');
		}else{
			$this->db->where('new_exam_form.theory_marks < paper_master.private_min_theory_marks');
		}
		$this->db->where('new_exam_form.class_id',$class_id);
		$this->db->where('roll_number !=','');
		$this->db->where('paper_type','theory');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->having('num<',3);
		$data['students'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		$this->load->view('header',array('title' => 'Student Failed Remaining Marks List'));
		$this->load->view('admin/remaining_withheld_student_list',$data);
		$this->load->view('footer');
	}
	public function update_withheld_student_remaining_marks($student_id,$paper,$marks)
	{
		// $this->Common_model->updateRecordByConditions('new_exam_form',array('paper_code'=>$paper,'student_id'=>$student_id,'theory_marks'=>''),array('theory_marks'=>$marks));
		echo "<title>MMYVVONLINE</title>";
		echo $this->db->last_query();
	}

	public function update_group_id_in_new_exam_form(){
		// $this->db->limit(10);
		//$students = $this->Common_model->getRecordByWhere('student',array('group_id !='=>''));
		$sql="SELECT DISTINCT(s.student_id),s.group_id,s.class_id FROM `new_exam_form` as e join student as s on s.student_id=e.student_id and s.class_id=e.class_id WHERE e.group_id='' and s.group_id!='' order by s.class_id";
		$students = $this->db->query($sql)->result_array();

		foreach($students as $student){
		$where = array(
			'student_id'=>$student['student_id'],
			'class_id'=>$student['class_id'],
		);
		$data = array(
			'group_id'=>$student['group_id']
		);

		$this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
		echo $this->db->last_query().'<br>';//die;
	}
}

public function update_sub_group_id_in_new_exam_form(){
	// $this->db->limit(10);
	 $this->db->where_in('class_id',array(134,107,116,119,110,125,128,131));
	//$this->db->where_in('class_id',array(110,119,125,128,131));
	// if($class_id == 101 || $class_id == 104){
	// 	$this->db->where(array('sub_group_id'=>1));
	// }
	$this->db->limit(100);
	$papers = $this->Common_model->getRecordByWhere('paper_master');
	// echo '<pre>';
	// print_r($papers);die;
	foreach($papers as $paper){
	$where = array(
		'paper_code'=>$paper->paper_code,
		'class_id'=>$paper->class_id,
		'sub_group_id'=>0,
	);
	$data = array(
		'sub_group_id'=>$paper->sub_group_id
	);
	

	$this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
	echo $this->db->last_query().'<br>';
}
}

public function update_sub_group_id_in_new_exam_form_sub(){
	// $this->db->limit(10);
	$this->db->where_in('class_id',array(101,104));
	$this->db->where(array('sub_group_id'=>1));
	
	$papers = $this->Common_model->getRecordByWhere('paper_master');
	echo '<pre>';
	print_r($papers);die;
	foreach($papers as $paper){
	$where = array(
		'paper_code'=>$paper->paper_code,
		'class_id'=>$paper->class_id,
		'sub_group_id'=>0,
	);
	$data = array(
		'sub_group_id'=>$paper->sub_group_id
	);
	

	// $this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
	echo $this->db->last_query().'<br>';
}
}

public function update_sub_group_id_in_backlog_exam_form(){
    $this->db->limit(300);
    $this->db->where_in('class_id',array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135));
    $students = $this->Common_model->getRecordByWhere('backlog_student',array('exam_year'=>'June 2023',
    'exam_form'=>'Y','mode'=>'REG'));
    // $this->Common_model->last_query();
    echo count($students);
    foreach($students as $student){
     $papers =   $this->Common_model->getRecordByWhere('old_result_data',array('student_id'=>$student->student_id,'class_id'=>$student->class_id));
    //  $this->Common_model->last_query();
    //  echo $papers[0]->student_id;die;
    //  echo '<pre>';
    //  print_r($papers);die;
     foreach ($papers as $paper){
        $where = array(
             	'paper_code'=>$paper->paper_code,
             	'class_id'=>$paper->class_id,
                'student_id'=>$paper->student_id,
            	'sub_group_id'=>0,
             );
            $data = array(
            	'sub_group_id'=>$paper->sub_group_id
            );
        $this->Common_model->updateRecordByConditions('backlog_exam_form',$where,$data);
	        echo $this->db->last_query().'<br>';
     }
    }

}

public function update_group_id_in_backlog_exam_form(){
    $this->db->limit(300);
    $this->db->where_in('class_id',array(101,104));
    $students = $this->Common_model->getRecordByWhere('backlog_student',array('exam_year'=>'June 2023',
    'exam_form'=>'Y','mode'=>'REG'));
    // $this->Common_model->last_query();
    echo count($students);
    foreach($students as $student){
     $papers =   $this->Common_model->getRecordByWhere('old_result_data',array('student_id'=>$student->student_id,'class_id'=>$student->class_id));
    //  $this->Common_model->last_query();
    //  echo $papers[0]->student_id;die;
    //  echo '<pre>';
    //  print_r($papers);die;
     foreach ($papers as $paper){
        $where = array(
             	'paper_code'=>$paper->paper_code,
             	'class_id'=>$paper->class_id,
                'student_id'=>$paper->student_id,
            	'group_id'=>'',
             );
            $data = array(
            	'group_id'=>$paper->group_id
            );
        $this->Common_model->updateRecordByConditions('backlog_exam_form',$where,$data);
	        echo $this->db->last_query().'<br>';
     }
    }

}
// public function update_sub_group_id_in_new_exam_form_group_paper(){
// 	// $this->db->limit(10);
// 	// $this->db->where_in('class_id',array(101,104));
// 	$this->db->where_not_in('sub_group_id',array(0,1));
// 	$papers = $this->Common_model->getRecordByWhere('group_paper');
// 	echo '<pre>';
// 	print_r($papers);die;
// 	foreach($papers as $paper){
// 	$where = array(
// 		'paper_code'=>$paper->paper_code,
// 		'group_id'=>$paper->group_id,
// 		'sub_group_id'=>0,
// 	);
// 	$data = array(
// 		'sub_group_id'=>$paper->sub_group_id
// 	);
	

// 	// $this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
// 	echo $this->db->last_query().'<br>';
// }
// }

public function update_roll_no_old_data(){
	
	$sql = "SELECT o.*,s.roll_no FROM `old_exam_data` as o join student as s on s.student_id=o.student_id WHERE `exam_year`='June 2024' and s.class_id=o.class_id and s.roll_no!=o.roll_no  limit 500";
	$students = $this->db->query($sql)->result_array();
	
	foreach($students as $student){
		$where = array('student_id'=>$student['student_id'],'exam_year'=>'June 2024','id'=>$student['id'],'roll_no'=>'0');
		$data = array ('roll_no'=>$student['roll_no']);
		$this->Common_model->updateRecordByConditions('old_exam_data',$where,$data);
		echo $this->db->last_query().'<br>';
	}

}
	public function center_wise_student_count_list(){
		$data['courses'] = $this->Common_model->getRecordByWhere('course_group');
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('header',array('title' => 'Center Wise Student Count List'));
		$this->load->view('admin/center_wise_student_count_list',$data);
		$this->load->view('footer');
	}

	public function get_student_count_data(){
		$data['course_group_id'] = $this->input->post('course_group_id');
		$this->db->select('count(*) as num,center_id,center_code,examcentercode,exam_center_id');
		$this->db->from('student');
		$this->db->where('course_group_id',$this->input->post('course_group_id'));
		$this->db->where('session','July 2022');
		$this->db->group_by('center_id');
		$data['students'] = $this->db->get()->result();
		$dt = $this->load->view('admin/getStudentCountData',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}

	public function update_sub_group_id(){
	
		$this->db->select('e.paper_code,e.sub_group_id,e.class_id,e.group_id,e.student_id');
		$this->db->from('new_exam_form as e');
		$this->db->join('old_result_data as r', 'r.student_id = e.student_id AND r.paper_code=e.paper_code');
		$this->db->where_in('e.class_id',array(104,107,134,105));
		$this->db->where('r.sub_group_id',0);
		//$this->db->where_in('class_id',array(101,104,107,110,116,119,125,128,131,134));
		 $this->db->limit(25000);
		$papers = $this->db->get()->result();
		//echo $this->db->last_query();
		foreach($papers as $paper){
			
			$where = array('paper_code'=>$paper->paper_code,'class_id'=>$paper->class_id,'student_id'=>$paper->student_id,'sub_group_id'=>0);
			$data = array ('sub_group_id'=>$paper->sub_group_id,'group_id'=>$paper->group_id);
			$this->Common_model->updateRecordByConditions('old_result_data',$where,$data);
			echo $this->db->last_query();
		}
	}

	public function add_extra_papers_in_old_result_data()
	{
		
		//$class_id=253;
		 $class_id=269;
		$num_of_papers=4;
		
		$sql="SELECT count(*)as num,e.* FROM `old_result_data` as e join `old_exam_data` as s on s.id=e.`exam_data_id` WHERE s.`class_id`=".$class_id." AND exam_year='May 2023' and exam_status='R' group by s.student_id HAVING num='".$num_of_papers."'";
		$sql_result = $this->db->query($sql);
        $students = $sql_result->result_array();
		foreach ($students as $student) {
			 	$query="SELECT * FROM `new_exam_form` WHERE `student_id`='".$student['student_id']."' and class_id='".$class_id."' and paper_type!='Theory'";
			$query_result = $this->db->query($query);
			$student_practical_records = $query_result->result_array();
			foreach ($student_practical_records as $row) {
				$where="`id` = '".$row['paper_id']."'"  ;
				$papers = $this->Common_model->get_record('paper_master','*',$where);
				//print_r($papers);
				$ResultData = array(
					'exam_data_id' =>  $student['exam_data_id'] ,
					'student_id' =>  $row['student_id'] ,
					'course_group_id' => $row['course_group_id'] ,
					'class_id' => $row['class_id'] ,
					'paper_code'=> $row['paper_code'] ,
					'type'=> $row['paper_type'] ,
					'max_theory_marks'=> $papers[0]['max_theory_marks'],
					'max_int_marks'=> $papers[0]['max_internal_marks'],
					'min_theory_marks'=> $papers[0]['min_theory_marks'],
					'min_int_marks'=> $papers[0]['min_internal_marks'],
					'theory_marks'=> $row['theory_marks'],
					'p_marks'=> $row['p_marks'],
					'int_marks'=> $row['int_marks'],
					'paper_name'=>  $papers[0]['paper_name'],
					'result' => 'PASS',
					'group_id' => $row['group_id'],
					'sub_group_id' => $row['sub_group_id'],
					'p_order'=> $row['paper_order'] 
				);
				
				echo "<pre>";
				
				//print_r($ResultData);

			   $insert = $this->Common_model->insertAll('old_result_data',$ResultData);
				echo $this->db->last_query().'<br>';
				
			}
			
			
		}	
		
	}
	public function update_int_marks_new_exam_form_to_old_result_data()
	{
		
		$sql = "SELECT * FROM `old_result_data` WHERE `int_marks`='-' and type!='theory' order by student_id limit 1000";
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			
			$new_exam_sql = "SELECT * FROM `new_exam_form` WHERE `student_id`='".$student['student_id']."' and class_id= '".$student['class_id']."' and paper_type!='Theory' and paper_code='".$student['paper_code']."' ";
			$new_exam_data =	$this->db->query($new_exam_sql)->result_array();
			//print_r($new_exam_data);
			$i=0;
			
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data[0]['paper_code'].' Marks=>'.$new_exam_data[0]['int_marks'].'<br>';

			echo	$update_marks = "update old_result_data set int_marks='".$new_exam_data[0]['int_marks']."' where id=".$student['id']." and int_marks='-'";
			echo '<br>';
				$i++;
				$this->db->query($update_marks);
		
		
		}
	}

	public function grade_pattern_papers_list(){
		
		$this->db->select('pm.*,cm.class_name');
		$this->db->from('paper_master as pm');
		$this->db->join('class_master as cm','cm.id = pm.class_id');
		$this->db->where('cbcs','Y');
		$this->db->where('cbcs_paper','Y');
		$this->db->order_by('cm.id');
		$papers_pg = $this->db->get()->result();
	
		$this->db->select('pm.*,cm.class_name');
		$this->db->from('paper_master as pm');
		$this->db->join('class_master as cm','cm.id = pm.class_id');
		$this->db->where_in('cm.course_group_id',array(11,12,13,14,16,17,18,19,20,21));
		$this->db->where_in('cm.class_name',array('I Year','II Year'));
		$papers_ug = $this->db->get()->result();
 		$data['papers'] = array_merge($papers_pg,$papers_ug);
		$this->load->view('header',array('title' => 'Grade Pattern Papers'));
		$this->load->view('admin/grade_pattern_papers',$data);
		$this->load->view('footer');
	
	}

	public function check_div_form_to_old_result_data()
	{
		echo "<h3>Check Div of final and Avg % of all Sem</h3>";
		$sql = "SELECT o.* FROM `old_exam_data`as o join student as s on s.student_id= o.student_id and s.class_id=o.class_id and s.course_complete='Y' WHERE exam_year='March 2023' and o.class_id in (155,182)   ";//order by o.student_id limit 1000";
		//and o.enrollment_no='AH/22100348'
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			$result= "<br> ".$s_no." ".$student['class_id']." ".$student['student_id']."  ".$student['enrollment_no'];
			$percent=$student['percentage'];
			if($percent>=60){
				$div = "First";
			  }elseif($percent<60 && $percent>=40){
				$div  = "Second";
			  }else{
				$div = "Third";
			  }
			  $result.= " Division  ". $div;
			$query = "SELECT * FROM `old_exam_data` WHERE student_id ='".$student['student_id']."' and exam_result!='FAIL'";
			$rset = $this->db->query($query)->result_array();
			$per_total=0;
			foreach ($rset as $all_class_result) {
				//echo "<br>".	$all_class_result["percentage"];
				$per_total+=$all_class_result["percentage"];
			}
		
			//echo "<br>".$per_total/2;
			$per_total=$per_total/2;
			$percentage = round($per_total,2);
			if($percentage>=60){
			  $division = "First";
			}elseif($percentage<60 && $percentage>=40){
			  $division  = "Second";
			}else{
			  $division = "Third";
			}
			$result.= " Correct  ". $division;
			if($division!=$div){
			echo $result;
			$s_no++;
			}
		}
	}
	public function get_below_fifty_per_marks()
	{
		echo "<h3>Get BA I Sem PVT Student LIst of below 50% Marks in Subjects</h3>";
		 $sql = "SELECT * FROM `paper_master` WHERE `class_id`=104 AND type='theory' AND id in(367,368,371,381) ORDER BY `paper_master`.`paper_no` ASC  ";
	
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo "<table>";
		foreach ($rs as $paper) {
		echo	$result= "<tr><td> ".$s_no."</td><td> ".$paper['id']." </td><td>".$paper['paper_code']." </td><td>".$paper['paper_name']." </td><td> ".$paper['private_max_theory_marks']."</td><td>";
		if($paper['sub_group_id']==1){
			$th_marks=25;
		}
		else{
			$th_marks=50;
		}
		 $studentsql = "SELECT count(*) as total FROM `new_exam_form_backup_ba_pvt` WHERE `class_id`=104 AND paper_code= '".$paper['paper_code']."' AND theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and update_marks_status='N' AND student_id in (SELECT `student_id` FROM `student` WHERE `class_id`=104 and exam_form='Y' and `university_mode`='PVT'); ";
	
		$exam_papers = $this->db->query($studentsql)->result_array();
		
		//print_r($exam_papers);
		echo "<b><a href='get_new_marks/".$paper['id']."'>". $exam_papers[0]['total']."</a></b></td></tr>";
		$s_no++;
		}
		echo "</table>";
	}
	public function get_new_marks($paperID)
	{
		$sql = "SELECT * FROM `paper_master` WHERE `class_id`=104 AND type='theory' AND id ='".$paperID."'";
	//30 Jan 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo "<table>";
		foreach ($rs as $paper) {
		echo	$result= "<tr><td> ".$s_no."</td><td> ".$paper['id']." </td><td>".$paper['paper_code']." </td><td>".$paper['paper_name']." </td><td> ".$paper['private_max_theory_marks']."</td><td></tr></table>";
		if($paper['sub_group_id']==1){
			$th_marks=25;
		}
		else{
			$th_marks=50;
		}
		 // $studentsql = "SELECT e.*,s.name,s.enrollment_no,s.roll_number FROM `new_exam_form` as e join student as s on s.student_id=e.student_id  WHERE e.`class_id`=104 AND e.paper_id= '".$paperID."' AND e.theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and s.`class_id`=104 and s.exam_form='Y' and s.`university_mode`='PVT' ";
		 $studentsql = "SELECT e.*,s.name,s.enrollment_no,s.roll_number FROM `new_exam_form_backup_ba_pvt` as e join student as s on s.student_id=e.student_id  WHERE e.`class_id`=104 AND e.paper_id= '".$paperID."' AND e.theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and s.`class_id`=104 and s.exam_form='Y' and s.`university_mode`='PVT' and update_marks_status='N' limit 1000";
	
		$student_papers = $this->db->query($studentsql)->result_array();
		echo "<table>";
		$i=1;
		foreach ($student_papers as $stud) {
			$a=(int)$stud['theory_marks']/3;
			$new_marks=$stud['theory_marks']+$a;
			$new_marks=round($new_marks,0);
			echo "<tr><td>".$i."</td><td>".$stud['student_id']."</td><td>".$stud['name']."</td><td>".$stud['enrollment_no']."</td><td>".$stud['theory_marks']."</td><td><b>".$new_marks."</b></td></tr>";
			/*$updateSQL="update `new_exam_form` set theory_marks='".$new_marks."' WHERE `class_id`=104 AND paper_id= '".$paperID."' AND student_id='".$stud['student_id']."' AND id='".$stud['id']."'";
			 $this->db->query($updateSQL);
			$updateStatus="update new_exam_form_backup_ba_pvt set update_marks_status='Y' where id='".$stud['id']."'";
			 $this->db->query($updateStatus);
			*/
			$i++;
		}
		echo "</table>";
		
		$s_no++;
		}
		

	}
	public function get_single_subject_fail_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code,p.private_max_theory_marks FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and p.private_min_theory_marks>e.theory_marks and e.theory_marks not in ('','00','ABS') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code,e.theory_marks FROM `new_exam_form` as e  join student as s on s.student_id =e.student_id WHERE   e.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'  and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' and e.theory_marks  in ('','00','ABS') having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				
				$avgsql="SELECT sum(e.theory_marks) as obtain , sum(p.private_max_theory_marks) as obtainfrom FROM `paper_master` as p  join `new_exam_form` as e on p.id=e.paper_id WHERE p.paper_code=e.paper_code and e.class_id=104 and p.class_id=104 and  `student_id`='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."'";
				$avg = $this->db->query($avgsql)->result_array();
				$average=($avg[0]['obtain']*100)/$avg[0]['obtainfrom'];
				$average=number_format((float)$average, 2, '.', '');
				$avg_marks = round($student['private_max_theory_marks']*$average/100);
				echo " , ".$avg[0]['obtain']." , ".$avg[0]['obtainfrom']." , ".$average." , ".$student['private_max_theory_marks']." , ".$avg_marks;
				$updateSql="Update new_exam_form set theory_marks='".$avg_marks."' WHERE paper_code='".$student['paper_code']."' AND student_id='".$student['student_id']."' ";
				//Set AVG Marks in remaining paper
				//$this->db->query($updateSql);
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " total_fail_count ".$total_fail_count;
	
		

	}
	public function get_single_subject_zero_marks_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code,p.private_max_theory_marks FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and e.theory_marks='00' and e.theory_marks not in ('','ABS') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'   and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' AND (p.private_min_theory_marks>e.theory_marks OR e.theory_marks  in ('','ABS')) having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				$avgsql="SELECT sum(e.theory_marks) as obtain , sum(p.private_max_theory_marks) as obtainfrom FROM `paper_master` as p  join `new_exam_form` as e on p.id=e.paper_id WHERE p.paper_code=e.paper_code and e.class_id=104 and p.class_id=104 and  `student_id`='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."'";
				$avg = $this->db->query($avgsql)->result_array();
				$average=($avg[0]['obtain']*100)/$avg[0]['obtainfrom'];
				$average=number_format((float)$average, 2, '.', '');
				$avg_marks = round($student['private_max_theory_marks']*$average/100);
				echo " , ".$avg[0]['obtain']." , ".$avg[0]['obtainfrom']." , ".$average." , ".$student['private_max_theory_marks']." , ".$avg_marks;
				$updateSql="Update new_exam_form set theory_marks='".$avg_marks."' WHERE paper_code='".$student['paper_code']."' AND student_id='".$student['student_id']."' ";
				//Set AVG Marks in remaining paper
			//	$this->db->query($updateSql);
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " total_zero_count ".$total_fail_count;
	
		

	}
	public function get_single_subject_blank_marks_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code,p.private_max_theory_marks FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and e.theory_marks='' and e.theory_marks not in ('00','ABS') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'   and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' AND (p.private_min_theory_marks>e.theory_marks OR e.theory_marks  in ('00','ABS')) having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				 $avgsql="SELECT sum(e.theory_marks) as obtain , sum(p.private_max_theory_marks) as obtainfrom FROM `paper_master` as p  join `new_exam_form` as e on p.id=e.paper_id WHERE p.paper_code=e.paper_code and e.class_id=104 and p.class_id=104 and  `student_id`='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."'";
				$avg = $this->db->query($avgsql)->result_array();
				$average=($avg[0]['obtain']*100)/$avg[0]['obtainfrom'];
				$average=number_format((float)$average, 2, '.', '');
				$avg_marks = round($student['private_max_theory_marks']*$average/100);
				echo " , ".$avg[0]['obtain']." , ".$avg[0]['obtainfrom']." , ".$average." , ".$student['private_max_theory_marks']." , ".$avg_marks;
				 $updateSql="Update new_exam_form set theory_marks='".$avg_marks."' WHERE paper_code='".$student['paper_code']."' AND student_id='".$student['student_id']."' ";
				//Set AVG Marks in remaining paper
				//$this->db->query($updateSql);
				
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " total_zero_count ".$total_fail_count;
	
		

	}
	public function get_single_subject_abs_student()
	{
		 $sql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT' and e.theory_marks='ABS' and e.theory_marks not in ('','00') group by s.student_id having num=1 order by s.student_id";
		//1 Feb 24
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		echo count($rs);
	
		$total_fail_count=1;
		foreach ($rs as $student) {
			 $failsql = "SELECT count(*) as num ,s.student_id,e.paper_code FROM `paper_master` as p join `new_exam_form` as e on p.id=e.paper_id join student as s on s.student_id =e.student_id WHERE  p.paper_code=e.paper_code and  e.class_id=104 and p.class_id=104 and  s.class_id=104 and s.exam_form='Y' and s.university_mode='PVT'   and s.student_id='".$student['student_id']."' and e.paper_code!='".$student['paper_code']."' AND (p.private_min_theory_marks>e.theory_marks OR e.theory_marks  in ('','00')) having num=0 ";
			$failrs = $this->db->query($failsql)->result_array();
			if($failrs){
				echo "<br> ";
				echo $total_fail_count++;
				echo " , ".$student['student_id']." , ".$student['paper_code'];
				
				//echo "<pre>";
			   // print_r($failrs);
			}
			
		}
		$total_fail_count--;
		echo "<br> ";
		echo " Total ABS count ".$total_fail_count;
	
		

	}

    public function check_old_percentage(){
        // $this->db->limit(1);
        // $this->db->where('id',37339);
        $old_datas = $this->Common_model->getRecordByWhere('old_exam_data', array('exam_year'=>'July 2023'));
       $student = [];
        foreach($old_datas as $data){
           
            $old_result_datas = $this->Common_model->getRecordByWhere('old_result_data', array('exam_data_id'=>$data->id));
            $class_data = $this->Common_model->getRecordById('class_master', 'id', $data->class_id);
            $total_marks= 0;
            $total_obtained_marks=0;
            foreach($old_result_datas as $old){
                if($old->type == 'theory'){
                    if($class_data->internal == 'Y' && $data->university_mode !='PVT'){
                        
                        if($old->theory_marks == 'ABS' || $old->int_marks == 'ABS'){
                            if($old->theory_marks == 'ABS' && $old->int_marks == 'ABS'){
                                $total_obtained_marks +=0;
                            }elseif($old->int_marks == 'ABS'){
                                $total_obtained_marks += $old->theory_marks; 
                            }elseif($old->theory_marks == 'ABS'){
                                $total_obtained_marks +=$old->int_marks;
                            }
                            
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;
                        }else{
                            $total_obtained_marks += $old->theory_marks + $old->int_marks;
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;
                        }
                       
                    }else{
                        if($old->theory_marks == 'ABS'){
                            $total_obtained_marks += 0;
                            $total_marks += $old->max_theory_marks;
                        }else{
                            $total_obtained_marks += $old->theory_marks;
                            $total_marks += $old->max_theory_marks;
                        }
                       
                    }
                }else if($old->type == 'Sessional'){
                    $total_obtained_marks += $old->int_marks;
                    $total_marks += $old->max_int_marks;
                }else{
                    if($class_data->practical_internal_marks == 'Y' && $data->university_mode !='PVT'){
                        if($class_data->id == 206 && $data->marks_pattern == 'MARKS'){
                            $total_obtained_marks += $old->p_marks;
                            $total_marks += $old->max_theory_marks;
                        }
                        elseif($old->p_marks == 'ABS' || $old->int_marks == 'ABS'){
                            if($old->p_marks == 'ABS' && $old->int_marks == 'ABS'){
                                $total_obtained_marks += 0;
                            }elseif($old->int_marks == 'ABS'){
                                $total_obtained_marks += $old->theory_marks; 
                            }elseif($old->p_marks == 'ABS'){
                                $total_obtained_marks +=$old->int_marks;
                            }
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;;
                        }else{
                            $total_obtained_marks += $old->p_marks + $old->int_marks;
                            $total_marks += $old->max_theory_marks + $old->max_int_marks;
                        }
                       
                    }else{
                        if($old->p_marks == 'ABS'){
                            $total_obtained_marks += 0;
                            $total_marks += $old->max_theory_marks;
                        }else{
                            $total_obtained_marks += $old->p_marks;
                            $total_marks += $old->max_theory_marks;
                        }
                       
                    }
                }
            }
           
            if($total_obtained_marks == 0 || $total_marks ==0){
                $percentage = 0;
            }else{
                $percentage = round(($total_obtained_marks/$total_marks) *100,2);
            }
           
           
            if($total_marks != $data->total_marks || $total_obtained_marks != $data->obtain_marks || $percentage != $data->percentage){
                echo 'Total :'.$total_marks.'Obtain :'.$total_obtained_marks.'Percentage :'.$percentage .'<br>';
                // echo $data->percentage .'actual'.$percentage ;
                $student[] =  $data->id;
                echo 'Exam Id :'. $data->id.'<br>';
            }
        }
       
         echo 'Count :' .count($student);
    }

	public function roll_number_correction(){

		//$sql = "SELECT o.id,o.student_id,o.class_id ,s.roll_number FROM `old_exam_data` as o join student as s on o.student_id=s.student_id WHERE o.class_id=s.old_class_id and o.`roll_no`!=s.roll_number and exam_year='July 2023' and exam_status='R'";
		echo $sql="SELECT * FROM `old_exam_data` as o JOIN student as s on o.student_id=s.student_id WHERE o.marks_pattern!=s.exam_pattern";
		//7 March 24
		$this->db->limit(1);
		$rs = $this->db->query($sql)->result_array();
		echo "<br>";
		echo count($rs);
		echo "<br>";
		$i=1;
		foreach ($rs as $student) {
			echo "<br>".$i."    ";
			echo $student['id']." ".$student['student_id']." ".$student['class_id']." ".$student['roll_number'] ." OLD ".$student['marks_pattern']." STUD ".$student['exam_pattern'];

			//$data  = array('roll_no'=>$student['roll_number'] );
			$data  = array('marks_pattern'=>$student['exam_pattern'] );
			$where = array('student_id'=>$student['student_id'],'id'=> $student['id']);
			//$update =$this->Common_model->updateRecordByConditions('old_exam_data',$where,$data);
			$i++;
			
			//break;
		}
	}

	public function update_pvt_grade_marks(){

		$class_id=107;
		 $sql="SELECT id,student_id,paper_code,theory_marks,(theory_marks+2) as new_marks FROM `new_exam_form` WHERE class_id='".$class_id."' and theory_marks in (30,31) and sub_group_id!=1  and `student_id` in (SELECT student_id FROM `student` WHERE  `roll_number` in (210710091,210710263,210710295,210710297,210710410,210710460,210710501,210710533,210710572,210710581,210710691,210710766,210711020,210711062,210711065,210711095,210711203,210711380,210711392,210711422,210711776,210711822,210711834,210711851,210712018,210712061,210712130,210712195,210712245,210712285,210712379,210712429,210712545,210712587) and exam_form='Y' )  ";
		//210410083,210410113,210410114,210410116,210410213,210410218,210410371,210410482,210410523,210410537,210410599,210410719,210410730,210410732,210410841,210410981,210411117,210411118,210411119,210411149,210411295,210411375,210411435,210411437,210411614,210411667,210411678,210411679,210411756,210411794,210411843,210412033,210412148,210412551,210412646,210413194,210413373,210413381,210413507,210413525,210413526,210413528,210413645,210413650,210413722,210413904,210413958,210414060,210414157,210414162,210414169,210414178,210414179,210414184,210414190,210414200,210414203,210414299,210414306,210414335,210414363,210414371,210414381,210414571,210414679,210414743,210414777,210414823,210414861,210415012,210415105,210415134,210415242,210415307,210415383,210415385,210415400,210415410,210415569,210415580,210415597,210415665,210415777,210415856,210416241,210416277,210416299,210416551,210416728,210416767,210416825,210416831,210416845,210416866,210416907,210416913,210416965,210416986,210416993,210417006,210417008,210417010,210417028,210417033,210417247,210417350,210417612,210417841,210417945,210417951,210417957,210417964,210417965,210417969,210418073,210418111,210418183,210418298,210418411,210418427,210418441,210418445,210418470,210418513,210418620,210418722,210418765,210418964,210419025,210419204,210419206,210419213,210419226,210419227,210419442,210419510,210420144,210420197,210420302,210420379,210420388,210420433,210420482,210420500,210420619,210420690,210420706,210420730,210420753,210420764,210420829,210420836,210420886,210420941,210420985,210421042,210421230,210421246,210421286,210421302,210421305,210421384,210421469,210421505,210421537,210421696,210421701,210421737,210421753,210421776,210421844,210422058,210422065,210422333,210422380,210422480,210422607,210422672,210422724,210423014,210423145,210423171,210423185,210423226,210423311,210423465,210423590,210423681,210423806,210423917,210423936,210423984,210424021,210424077,210424083,210424109,210424392,210424499,210424611,210424759,210424784,210424881,210425039,210425144,210425264,210425496,210425592,210425653,210425757,210425784,210426060,210426100,210426351,210426368
		//$this->db->limit(1);
		$rs = $this->db->query($sql)->result_array();
		echo "<br>";
		echo count($rs);
		echo "<br>";
		$i=1;
		foreach ($rs as $student) {
			echo "<br>".$i."    ";
			echo $student['id']." ".$student['student_id']." ".$student['paper_code']." ";
			echo "<br>";
			//$data  = array('roll_no'=>$student['roll_number'] );
				$sql1="update new_exam_form set theory_marks= ".$student['new_marks']." where id='".$student['id']."'";
				$sql2="update old_result_data set theory_marks= ".$student['new_marks']." where student_id='".$student['student_id']."' AND paper_code='".$student['paper_code']."' ";
				$old_datas = $this->Common_model->getRecordByWhere('old_exam_data', array('exam_year'=>'July 2023','class_id'=>$class_id,'student_id'=>$student['student_id']));
			
		
				 $obtain_marks= $old_datas[0]->obtain_marks+2;
				 $percentage=round(($obtain_marks/$old_datas[0]->total_marks)*100,2);
				
			 $sql3="update old_exam_data set obtain_marks='".$obtain_marks."',percentage='".$percentage."' where student_id='".$student['student_id']."' AND  class_id='".$class_id."'AND exam_year='July 2023'" ;
			$i++;

			 $this->db->query($sql1);
			 $this->db->query($sql2);
			 $this->db->query($sql3);
			
		//	break;
		}
	}
	public function dg_locker_data(){
		//and enrollment_no in ('AG/21204765','AG/21204615')
		$this->load->view('header',array('title' => 'Student Data For DIGI LOCKER'));
		//$this->db->where_in('enrollment_no',array('AG/21204765','AG/21204615'));
		$sql="SELECT * FROM `old_exam_data` WHERE class_id in (131,125,119,116,110,101,134,107,104) and exam_year in ('August 2022','Aug 2022') and center_id in (21,22,23,24,25,26,27,28)   and university_mode='REG'";
		$rs = $this->db->query($sql)->result_array();
		$i=1;
		$this->load->model('Gradesheet_old_model');
		$data['rs']=$rs;
		
		$this->load->view('admin/digi_locker',$data);
		$this->load->view('footer');
	}
	
	public function update_AGPA_CGPA_class_list(){
		
		$this->db->select('count(class_id) as total, class_id');
		$this->db->from('old_exam_data');
		$this->db->where('exam_year ="January 2024" and marks_pattern ="GRADE"  and exam_status = "R" and agpa_sgpa=""');  
		$this->db->group_by('class_id');
		$class_list = $this->db->get()->result();
	
		
 		$data['class_list'] = $class_list;
	
		$this->load->view('header',array('title' => 'Grade Pattern Class List'));
		$this->load->view('admin/update_AGPA_CGPA_class_list',$data);
		$this->load->view('footer');
	
	}
	public function update_AGPA_CGPA($class_id,$cbcs){
		$this->db->select('*');
		$this->db->from('old_exam_data');
		$this->db->where('exam_year ="January 2024" and exam_status="R" and marks_pattern ="GRADE" and class_id="'.$class_id.'" and agpa_sgpa=""');  
		// $this->db->where('student_id',380243);
        // $this->db->where_in('student_id',array(718293,718689,721416));
		$this->db->limit(1000);
		$student_list = $this->db->get()->result();
        if($cbcs == "Y"){
			$this->load->model('GradeSheet_old_model_pg');
		}
		else{
			$this->load->model('Gradesheet_old_model');
		}
		
		foreach($student_list as $student){
		echo "<br>". $student->student_id;
		if($cbcs == 'Y'){

			$gradeData = $this->GradeSheet_old_model_pg->view_old_results($student->student_id,$student->course_group_id,$class_id,$student->university_mode);
		}else{
			$gradeData = $this->Gradesheet_old_model->view_old_results($student->student_id,$student->course_group_id,$class_id,$student->university_mode,  $student->id,$student->exam_status);
		}
			
			//print_r($gradeData);
			
			echo "<br> AGPA ".$gradeData['agpa'].' ';
			echo $agpa=number_format((float)$gradeData['agpa'], 2, '.', '');
		    echo 	$update_marks = "update old_exam_data set agpa_sgpa='".$agpa."' where id=".$student->id;
			
			$this->db->query($update_marks);

            // echo $this->db->last_query();
			//die;
		}
	
	}

	public function remove_old_class_paper(){
		

		$sql="SELECT e.id,e.student_id FROM `student_report` as s JOIN new_exam_form_report as e on s.student_id=e.student_id WHERE s.class_id!=e.class_id limit 50000";
		$students = $this->db->query($sql)->result_array();
		
		foreach($students as $student)
        {
			$where=array("student_id"=>$student['student_id'],"id"=> $student['id']);
		    $response = $this->Common_model->deleteByWhere('new_exam_form_report',$where);
			echo $this->db->last_query();echo "<br>";

		}
	}	

    public function update_old_exam_data_result_date_main(){
        $exam_year = 'January 2024';
        $this->db->select('DISTINCT(class_id),university_mode');
        $this->db->from('old_exam_data');
        $this->db->limit(20);
        $this->db->order_by('class_id','university_mode');
        $this->db->where(array('exam_year'=>$exam_year,'exam_status'=>'R','marksheet_date'=>""));
        $classes = $this->db->get()->result();
        
        foreach($classes as $class){
            $marksheet_varible = $this->Common_model->getRecordById('marksheet_variables','class_id', $class->class_id);
           $date = ($class->university_mode == 'REG')?$marksheet_varible->result_date:$marksheet_varible->pvt_result_date;
           $date = str_replace('/', '-', $date);
           $data = array('marksheet_date'=>date('Y-m-d',strtotime($date)));
            $update =$this->Common_model->updateRecordByConditions('old_exam_data',array('exam_year'=>$exam_year,'exam_status'=>'R', 'class_id'=>$class->class_id,'university_mode'=>$class->university_mode,'marksheet_date'=>""),$data);
            echo $this->db->last_query() . '<br>';
        } 
    }

    public function update_old_exam_data_result_date_backlog(){
        $exam_year = 'January 2024';
        $this->db->select('DISTINCT(class_id),university_mode');
        $this->db->from('old_exam_data');
        $this->db->order_by('class_id','university_mode');
        $this->db->limit(20);
        $this->db->where(array('exam_year'=>$exam_year,'exam_status'=>'B','marksheet_date'=>""));
        $classes = $this->db->get()->result();
      
        foreach($classes as $class){
            $marksheet_varible = $this->Common_model->getRecordById('marksheet_variables','class_id', $class->class_id);
            $date = ($class->university_mode == 'REG')?$marksheet_varible->backlog_result_date:$marksheet_varible->backlog_pvt_result_date;
            $date = str_replace('/', '-', $date);
            $data = array('marksheet_date'=>date('Y-m-d',strtotime($date)));
            $update =$this->Common_model->updateRecordByConditions('old_exam_data',array('exam_year'=>$exam_year,'exam_status'=>'B', 'class_id'=>$class->class_id,'university_mode'=>$class->university_mode,'marksheet_date'=>""),$data);
            echo $this->db->last_query() . '<br>';
        } 
    }
		
}




?>
