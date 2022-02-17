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
		    $titleData = array('title' => 'Student Dashboard'); 
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
		
			$this->Student_model->checkStudentForm();
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
		$this->load->view('students/header',array('title' => 'Admission Form'));	
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
}