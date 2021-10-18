<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ADMIN_Controller extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('common_model');
	}

	public function isAdminLogedIn()
	{
		if($this->session->has_userdata('username')){
			redirect(base_url('admin/dashboard'));
			exit;
		}else{
			redirect(base_url('admin/login'));
		}
	}

	public function loadPage($page,$data='')
	{
		if(!file_exists(APPPATH.'views/'.$page.'.php')){
			show_404();
		}
		$this->load->view('header');
		$this->load->view($page,$data);
		$this->load->view('footer');
	}
}