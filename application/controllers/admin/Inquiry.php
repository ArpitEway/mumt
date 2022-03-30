<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Inquiry extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='Inquiry'){
				redirect(base_url('admin/logout')); 
		}
	}

	public function index(){
		$this->load->view('header',array('title' => 'Inquiry Section'));
		$this->load->view('admin/inquiry/dashboard');
		$this->load->view('footer');	
	}
    public function inquiry(){

        $data['inquiries'] = $this->Common_model->get_record('enquiry','*');
      
        $this->load->view('header',array('title' => 'Inquiry Section'));
		$this->load->view('admin/inquiry/inquiry_list',$data);
		$this->load->view('footer');
    }

	
}// class
