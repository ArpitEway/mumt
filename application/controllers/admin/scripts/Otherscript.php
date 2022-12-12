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
	
}

?>
