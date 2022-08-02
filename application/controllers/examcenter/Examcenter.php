<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Examcenter extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Exam_center_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');
	}

	public function index(){
		if($this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
		}else{			
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
	    	$this->load->view('examcenter/login',$csrf);
		}
	}

	public function dashboard(){

		if(!$this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/login'));
		}else{
			$titleData = array('title' => 'Exam center Dashboard'); 
			$this->load->view('examcenter/header',$titleData);
			$id =  $this->session->exam_center_id;
			$exam_center = $this->Common_model->getRecordById('exam_center','id',$id);
			$data = array('exam_center' => $exam_center);
			$this->load->view('examcenter/dashboard',$data);
			$this->load->view('examcenter/footer');
		}
	}

	public function login(){
		if($this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
			exit;
		}
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('examcenter/login',$csrf);
	}

	public function loginSub(){

		if($this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
			exit;
		}
		$this->form_validation->set_rules('exam_center', 'Exam Center', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('examcenter/login',$csrf);
		}else{
			$username = $_POST['exam_center'];
			$password = $_POST['password'];
			$check_user = $this->Exam_center_model->checkcenter($username,$password);
			if($check_user){
				$data = array(
					'loged_in' 	  => true,
					'Examcenterdata' => $check_user->examcentercode,
					'password' 	  	  => $check_user->password,
					'exam_center_id'  => $check_user->id
				);
				$this->session->set_userdata($data);
				redirect(base_url('Examcenter/dashboard'));
			}else{
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
				$this->session->set_flashdata('error','Exam Center Code or Password are incorrect');
				$this->load->view('examcenter/login',$csrf );
			}
		    }
	        }


	public function logout()
	 {
		$this->session->sess_destroy();
		redirect(base_url('Examcenter/login'));
	 }

}
