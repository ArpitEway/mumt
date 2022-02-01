<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class ExamController extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
	}

	public function consolidate_report(){
		$dt = array();
		$dt['title'] = "Student Consolidate Report";
		$this->load->view('header',$dt);
		$this->db->order_by('id', 'Desc');
		$dt['name_csrf'] = $this->security->get_csrf_token_name();
		$dt['hash_csrf'] = $this->security->get_csrf_hash();
		$dt['sessions'] = $this->db->get_where('session', array())->result_array();
		$this->load->view('admin/consolidate_report',$dt);
		$this->load->view('footer');
	}

    public function search_student(){
		
		$segment = $this->uri->segment(2);
		
		$this->load->view('header',array('title' => 'Search Students'));

		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'segment' => $segment
		);

		$this->load->view('admin/search_student',$data);
		$this->load->view('footer');
	}
  
}// class
