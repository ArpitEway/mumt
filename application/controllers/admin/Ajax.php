<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Account_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='master'){
			redirect(base_url('admin/logout'));
		}
	}

	public function getStudentData()
	{
		echo $text_val =$this->input->post('text_val');
        echo $radio_val = $this->input->post('radio_val');

        if($text_val != '')
        {
        	$st_data = 
        }
	}

}//class