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
		
		$classes = $this->Common_model->get_record('class_master','GROUP_CONCAT(id) as class_id',array('class_group' => 'N','exam_form_permission' => 'Y'));

		$class_ids = $classes[0]['class_id'];
		
		$this->db->select('count(class_id) as num,course_name,class_name,class_id');
		$this->db->where('class_id in ('.$class_ids.') and payment_status="Y" and temp_exam_form="N"');
		$this->db->group_by('class_id');
		$this->db->order_by('course_group_id');
		$studentClasses = $this->db->get('student')->result();
		// $this->Common_model->last_query();
		$data = array(
			'studentClasses' => $studentClasses,
		);
		$this->load->view('admin/script/upload_exam_paper',$data);
		$this->load->view('admin/script/footer');
	}

	public function upload_exam_paper_sub($class_id)
	{
		$where = array('class_id' => $class_id,
					'payment_status' => 'Y',
					'temp_exam_form' => "N",
		);
		$papers = $this->Common_model->get_record('paper_master','*','class_id='.$class_id);
		$students = $this->Common_model->get_record('student','*',$where);
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
		$this->db->where('class_id in ('.$class_ids.') and new_exam_form="D" and enrolled = "Y"');
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
					'temp_exam_form' => "Y",
				);
		
		$students = $this->Common_model->get_record('student','*',$where);

		foreach ($students as $student) {
			$where = array('student_id'=>$student['student_id']);
			$this->Common_model->updateRecordByConditions('student',$where,array('new_exam_form' => "N"));
		}
	}

	
	
	// public function	update_exam_center_id_in_student() {
		
	// 	$exam_centers = $this->Common_model->get_record('exam_center','*');
	// 	foreach($exam_centers as $exam_center){
	// 		$where = 'institute_id in ('.$exam_center['allot_institute_id'].' ) and new_exam_form!="D" and exam_center_id = ""';
	// 		$data = array('exam_center_id'=> $exam_center['id']);
	// 		$students = $this->Common_model->updateRecordByConditions('student',$where,$data);
	//          echo $this->db->last_query().'<br>';
	// 	}
	// }


	
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
}

?>
