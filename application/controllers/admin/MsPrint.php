<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MsPrint extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='MsPrint'){
				redirect(base_url('admin/logout')); 
		}
	}

	public function index()
	{
		$admin_id = $this->session->admin_id;
		$where = 'admin_id='.$admin_id.' and status="Y"';
		$menu = array(
			"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
			"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
		);
		$this->load->view('header',array('title' => 'MS Print'));
		$this->load->view('admin/Enquiry/dashboard',$menu);
		$this->load->view('footer');
	}
}