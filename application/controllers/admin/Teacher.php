<?php
// include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Teacher_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');

		
	}

public function index(){
		if($this->session->has_userdata('teacherdata')){
			redirect(base_url('admin/teacher/dashboard'));
		}else{			
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('admin/teacher/login',$csrf);
		}
	}
		
	public function dashboard(){

		if(!$this->session->has_userdata('teacherdata')){
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Teacher Dashboard'); 
			$this->load->view('admin/teacher/header',$titleData);
			$id =  $this->session->teacher_id;
			$center = $this->Common_model->getRecordById('teacher','id',$id);
			$data = array('teacher' => $center);
			$this->load->view('admin/teacher/dashboard',$data);
			$this->load->view('admin/teacher/footer');
		}
	}


	
	
public function login(){
		if($this->session->has_userdata('teacherdata')){
			redirect(base_url('teacher'));
			exit;
		}
		$csrf = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('admin/teacher/login',$csrf);
	}



public function loginSub(){
		
		 if($this->session->has_userdata('teacherdata')){
		 	redirect(base_url('dashboard'));
			 exit;
		  }

		$this->form_validation->set_rules('phone', 'Phone', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
				$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
			);
				$this->load->view('admin/teacher/login',$csrf);
		}
		else
		{ 

		
			$username = $_POST['phone'];
			$password = $_POST['password'];
			
			$check_user = $this->Teacher_model->checkTeacher($username,$password);
			
			if($check_user){
				
				$data = array(
							'loged_in' 	  => true,
							'teacherdata' => $check_user->phone,
							'password' 	  	  => $check_user->password,
							'teacher_id'  => $check_user->id,
							
						);
				
				$this->session->set_userdata($data);
		
			
			redirect(base_url('admin/teacher/dashboard'));
			}else{

			$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
		$this->session->set_flashdata('error','Phone no or Password are incorrect');
		
		$this->load->view('admin/teacher/login',	$csrf );
		
		}
	}
}



	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}

	

	



	

}