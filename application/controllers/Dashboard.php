<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_controller {

	function __construct(){ 
		parent::__construct();
			$this->load->model('Common_model');
		}

	public function index()
	{
		if($_SERVER['HOSTNAME']=='center.mmyvvonline.com'){

		}elseif($_SERVER['HOSTNAME']=='admin.mmyvvonline.com'){

		}else{
		redirect('http://162.144.38.91/~mmyvvdde/main/center/index.php');
		}
	}
	public function notice_board()
	{
		$this->load->view('webHeader');
		$this->load->view('noticeBoard');
		$this->load->view('footer');
	}
	
}