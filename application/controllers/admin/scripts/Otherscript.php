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
		$where = " paper_code in ('2RBED6')";
		$papers = $this->Common_model->get_record('paper_master','*',$where);

		foreach ($papers as $paper) {
			$where = ' class_id = "'.$paper['class_id'].'" and temp_exam_form="Y"';
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
				$studentData['student_id'] = $student['student_id'];
				//$this->Common_model->insertAll('new_exam_form',$studentData);
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
		// $marks = array('18','17','16','18','17','16','15','15');
		$marks = array('09','08','07','09','08','07','06','06');
		// $marks = array('27','26','25','27','26','25','24','24'); 
		$cls_id=202;
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and int_marks_sub='N' and roll_no!=0 limit 100";
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type='Theory' and int_marks in ('N') and class_id='".$cls_id."' ORDER by rand()";
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
		//$marks = array('85','84','83','82'); 
		$cls_id=182;
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and p_marks_sub='N' and roll_no!=0 order by roll_no limit 100";
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
		$marks = array('85','84','83','82'); 
		$cls_id=182;
		$sql = "select * from student where class_id='".$cls_id."' and new_exam_form='Y' and p_marks_sub='N' and roll_no!=0 order by roll_no limit 100";
		$rs = $this->db->query($sql)->result_array();
		$s_no=1;
		foreach ($rs as $student) {
			$new_exam_sql = "select * from new_exam_form where student_id='".$student['student_id']."' and paper_type='Project' and p_marks in ('N') and class_id='".$cls_id."' ORDER by rand()";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$i=0;
			foreach ($new_exam_rs as $new_exam_data) {
				echo $s_no++.' student_id =>'.$student['student_id'].' paper_code =>'.$new_exam_data['paper_code'].' Marks=>'.$marks[$i].'<br>';

				$update_marks = "update new_exam_form set p_marks='".$marks[$i]."' where id=".$new_exam_data['id'];
				$i++;
				$this->db->query($update_marks);
				shuffle($marks);
			}
			$update_student = "update student set p_marks_sub='Y' where student_id='".$student['student_id']."' and class_id='".$cls_id."'";

			$this->db->query($update_student);
		}
	}
	public function update_oldexam_marks($cls_id =0)
	{

		$cls_id=154;
		$sql = "SELECT * FROM `old_exam_data` WHERE `class_id`=154  AND total_marks=400 limit 250";
		$rs = $this->db->query($sql)->result_array();
		$i=0;
		foreach ($rs as $student) {
			$new_exam_sql = "SELECT SUM(p_marks) as tot FROM `old_result_data` WHERE `exam_data_id`='".$student['id']."' and `type`!='Theory'";
			$new_exam_rs =	$this->db->query($new_exam_sql)->result_array();
			$total_marks=500;
			$obtain_marks=$student['obtain_marks']+$new_exam_rs[0]['tot'];
			echo "<br> $i= <br>";
			$percentage=round(($obtain_marks/$total_marks)*100,2);
				// $percentage=($obtain_marks/$total_marks)*100;
			echo $update_marks = "update old_exam_data set total_marks='".$total_marks."',obtain_marks='".$obtain_marks."',percentage='".$percentage."' where id=".$student['id'];
			$i++;
			$this->db->query($update_marks);
		}
	}

	public function remaining_failed_student_marks($class_id=0){
		$class_id=107;
		$university_mode='REG';
		$this->db->select('count(*) as num,student.*');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.class_id');
		$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
		$this->db->order_by('new_exam_form.course_group_id,new_exam_form.class_id','asc');
		$this->db->where('new_exam_form.paper_type','theory');
		// $this->db->where('new_exam_form.theory_marks',);
		$this->db->where('exam_form','Y');
		if ($university_mode='REG') {
			$this->db->where('new_exam_form.theory_marks < paper_master.min_theory_marks');
		}else{
			$this->db->where('new_exam_form.theory_marks < paper_master.private_min_theory_marks');
		}
		$this->db->where('new_exam_form.class_id',$class_id);
		$this->db->where('roll_no!=','0');
		$this->db->where('paper_type','Theory');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->having('num',1);
		$data['students'] = $this->db->get()->result();
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
}

?>
