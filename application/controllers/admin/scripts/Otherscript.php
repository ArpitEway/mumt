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
		$where="`id` in (1356,1357)";
		$papers = $this->Common_model->get_record('paper_master','*',$where);

		foreach ($papers as $paper) {
			$where = ' class_id = "'.$paper['class_id'].'" and temp_exam_form="Y" and exam_pattern="GRADE"';
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
				echo	$studentData['student_id'] = $student['student_id'];
				echo "<br>";
				$this->Common_model->insertAll('new_exam_form',$studentData);
				echo $this->db->last_query().'<br>';
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
		 $marks = array('27','26','25','27','26','25','24','24'); 
		$cls_id=132;
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and int_marks_sub='N' and roll_no!=0 and university_mode='REG' limit 100";
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
		// $marks = array('43','42','41','40'); 
		// $marks = array('85','84','83','82'); 
		// $marks = array('68','67','66','65');
		 $marks = array('60','59','58','57');
		$cls_id=126;
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and p_marks_sub='N' and roll_no!=0 and university_mode='REG' order by roll_no limit 100";
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
		$cls_id=126;
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and p_marks_sub='N' and  university_mode='REG' and  roll_no!=0 order by roll_no  limit 100";
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
            $where = array('test_id'=>'0','paper_code'=> $row->paper_code);
            $update =$this->Common_model->updateRecordByConditions('paper_master',$where,$data);
			 echo  $this->db->last_query(); //die;
			$i++;
		// $this->Common_model->updateRecordByConditions('new_exam_form',$where,$data);
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
	// $this->db->where_in('class_id',array(134,107,116,119,110,125,128,131));
	$this->db->where_in('class_id',array(110,119,125,128,131));
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
    $this->db->where_in('class_id',array(101,104,107,110,116,119,125,128,131,134));
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
	
	$sql = "SELECT o.*,s.roll_number FROM `old_exam_data` as o join student as s on s.student_id=o.student_id WHERE `exam_year`='Aug 2022' and s.old_class_id=o.class_id and s.roll_number!=o.roll_no  limit 500";
	$students = $this->db->query($sql)->result_array();
	
	foreach($students as $student){
		$where = array('student_id'=>$student['student_id'],'exam_year'=>'Aug 2022','id'=>$student['id']);
		$data = array ('roll_no'=>$student['roll_number']);
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
	
		$this->db->select('paper_code,sub_group_id,class_id,group_id,student_id');
		$this->db->from('new_exam_form');
		$this->db->where_in('class_id',array(101,104,107,110,116,119,125,128,131,134));
		// $this->db->limit(100);
		$papers = $this->db->get()->result();
		
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
		 $studentsql = "SELECT count(*) as total FROM `new_exam_form` WHERE `class_id`=104 AND paper_code= '".$paper['paper_code']."' AND theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and student_id in (SELECT `student_id` FROM `student` WHERE `class_id`=104 and exam_form='Y' and `university_mode`='PVT'); ";
	
		$exam_papers = $this->db->query($studentsql)->result_array();
		
		//print_r($exam_papers);
		echo "<b><a href='get_new_marks/".$paper['id']."'>". $exam_papers[0]['total']."</a></b></td></tr>";
		$s_no++;
		}
		echo "</table>";
	}
	public function get_new_marks($paperID)
	{
		$sql = "SELECT * FROM `paper_master` WHERE `class_id`=104 AND type='theory' AND id='".$paperID."' ";
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
		 $studentsql = "SELECT e.*,s.name,s.enrollment_no,s.roll_number FROM `new_exam_form_backup_ba_pvt` as e join student as s on s.student_id=e.student_id  WHERE e.`class_id`=104 AND e.paper_id= '".$paperID."' AND e.theory_marks not in('','ABS','00') and theory_marks<='".$th_marks."' and s.`class_id`=104 and s.exam_form='Y' and s.`university_mode`='PVT' and update_marks_status='N' limit 1";
	
		$student_papers = $this->db->query($studentsql)->result_array();
		echo "<table>";
		$i=1;
		foreach ($student_papers as $stud) {
			$a=(int)$stud['theory_marks']/3;
			$new_marks=$stud['theory_marks']+$a;
			$new_marks=round($new_marks,0);
			echo "<tr><td>".$i."</td><td>".$stud['student_id']."</td><td>".$stud['name']."</td><td>".$stud['enrollment_no']."</td><td>".$stud['theory_marks']."</td><td><b>".$new_marks."</b></td></tr>";
			$updateSQL="update `new_exam_form` set theory_marks='".$new_marks."' WHERE `class_id`=104 AND paper_id= '".$paperID."' AND student_id='".$stud['student_id']."' AND id='".$stud['id']."'";
			$st = $this->db->query($updateSQL)->result_array();
			$updateStatus="update new_exam_form_backup_ba_pvt set update_marks_status='Y' where id='".$stud['id']."'";
			$pap = $this->db->query($updateStatus)->result_array();

			$i++;
		}
		echo "</table>";
		
		$s_no++;
		}
		

	}
}

?>
