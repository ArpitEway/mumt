<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_controller {

	function __construct(){ 
		parent::__construct();
			$this->load->model('Common_model');
		}

	public function index()
	{
		redirect(base_url('center'));
	}
	public function notice_board()
	{
		$this->load->view('webHeader');
		$this->load->view('noticeBoard');
		$this->load->view('footer');
	}
	
}