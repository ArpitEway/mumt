<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_controller {

	function __construct(){ 
		parent::__construct();
			$this->load->model('Common_model');
		}

	public function index()
	{
		$this->load->view('students/header');
		$this->load->view('disclaimer');
		$this->load->view('footer');
		//redirect(base_url('user'));
	}
	public function notice_board()
	{
		$this->load->view('webHeader');
		$this->load->view('noticeBoard');
		$this->load->view('footer');
	}
	
}