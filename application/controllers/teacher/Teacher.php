<?php


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
			redirect(base_url('teacher/dashboard'));
		}else{			
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('teacher/login',$csrf);
		}
	}
		
	public function dashboard(){
	
		if(!$this->session->has_userdata('teacherdata')){
			redirect(base_url('login'));
		}else{
			$titleData = array('title' => 'Teacher Dashboard'); 
			$this->load->view('teacher/header',$titleData);
			$id =  $this->session->teacher_id;
			$center = $this->Common_model->getRecordById('teacher','id',$id);
			$data = array('teacher' => $center);
			$this->load->view('teacher/dashboard',$data);
			$this->load->view('teacher/footer');
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
		$this->load->view('teacher/login',$csrf);
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
				$this->load->view('teacher/login',$csrf);
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
		
			
			redirect(base_url('Teacher/dashboard'));
			}else{

			$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
		$this->session->set_flashdata('error','Phone no or Password are incorrect');
		
		$this->load->view('teacher/login',	$csrf );
		
		}
	}
}



	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('teacher/Teacher/login'));
	}

	
public function change_password(){
		if(!$this->session->has_userdata('teacherdata')){

			redirect(base_url());

		}else{

			$titleData = array('title' => 'Change Password'); 

			$this->load->view('teacher/header',$titleData);

			$teacher_data = $this->session->get_userdata($data);
	        
			$id = $teacher_data['teacher_id'];

			$teacher = $this->Common_model->getRecordById('teacher','id',$id);
			
$data = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	

			$data = array('teacher' => $teacher);
			$this->load->view('teacher/change_password',$data);
			$this->load->view('teacher/footer');
		}
	}
	


public function password_change($id)
	{
		 print_r($_POST);
		 die;

       $data = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
		$data = $this->Common_model->getRecordById('teacher','id',$id);

		$old_password = $data->password;

		$password 	  = $this->input->post('password');
		$new_password  = $this->input->post('new_password');

		$confirm_password = $this->input->post('passconf');
		
		if($old_password == $password)
		{
			$where=array('id'=> $id);
			$data = array("password" =>$new_password );


			$this->Common_model->updateRecordByConditions('teacher',$where,$data);
			echo json_encode(array(
				"success" => 'Password Updated Successfully',
			));
			
						
				
			}else{
				echo json_encode(array(
					"error" => 'Current Password is wrong',
				));
			}
		
		$this->Common_model->last_query();
	}

	

}