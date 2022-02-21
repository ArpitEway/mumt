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
			$data = array('student' => $student);
			//$this->getNotification();
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
			
			if($check_user){
				
				$data = array(
							'loged_in' 	  => true,
							'studentdata' => $check_user->enrollment_no,
							'dob' 	  	  => $check_user->dob,
							'student_id'  => $check_user->student_id,
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
		if($data['student']['approved']!='Y'){
		redirect(base_url('admission/'.$student_id));
		}
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
    		'student_id' => $student_id
    	);
    	$this->db->where($where); 
    	$data['papers'] = $this->db->get()->result();
		$whereClass = array('class_id' => $class_id,
					'exam_permission' => 'Y',
		);
		$timeTableData = $this->Common_model->getRecordByWhere('time_table',$whereClass);
		if(count($timeTableData)==0){
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
					'course_id' =>$_POST['course_id'],
					'class_id' =>$_POST['class_id'],
					'paper_code' =>$_POST['paper_code'],
					'center_id' =>$_POST['center_id'],
					'answer_sheet' =>$document_image ,
					'upload_date' =>date("Y-m-d") ,
					'exam_status' => 'R'
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
}