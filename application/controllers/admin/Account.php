<?php
	include_once(APPPATH.'core/ADMIN_controller.php');
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Account extends CI_Controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('admin/admin_model');
			$this->load->model('Common_model');
			$this->load->model('admin/Account_model');
			if(!$this->session->account_type=='account'){
				redirect(base_url('admin/logout'));
			}
		}
		
		public function index(){
			
			if($this->session->has_userdata('username')){
			$admin_id = $this->session->admin_id;
			$where = 'admin_id='.$admin_id;
				$menu = array(
					"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
					"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
				);
				
				$this->load->view('header',array('title' => 'Account Section'));
				$this->load->view('admin/account_section/dashboard',$menu);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}
		
		public function dashboard(){
			
			if($this->session->has_userdata('username')){
				
				$this->load->view('admin/account_section/header');
				$this->load->view('admin/account_section/dashboard');
				$this->load->view('admin/account_section/footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}
		public function view_payment_list(){
			
			if($this->session->has_userdata('username')){
				
			$this->load->view('admin/account_section/header');
			$this->load->view('admin/account_section/view_payment_list');
			$this->load->view('admin/account_section/footer');
		}
			else
			{
				redirect(base_url('admin/login'));
			}
		}
		
		public function get_payment_list()
		{
			
			if ($this->input->method() == "post") 
			{
				$course_group_id = 0;
				$data = array();
				$dt   = array();
				
				$fees_head = $this->input->post("payment_list");
				
				if($fees_head != "all"){
					$dt['fees_head'] = $fees_head;
				}
				$dt['payment'] = "Y";
				$dts['accData'] = $this->Account_model->account_data($dt);
				
				$data =  $this->load->view('admin/account_section/get_payment_list',$dts,true);
				
				echo json_encode(array(
				"status" => true,
				"data" => $data
				));
			}
			
		}
		
		public function get_payment_list2()
		{
			
		   if($this->input->method() == "post") 
			{
				$fees_head = $this->input->post("payment_list");
				
				
				$dt['fees_head'] = $fees_head;
					
				$data = $row = array();
				// Fetch member's records
				$accData = $this->Account_model->account_data($dt);
				
				$i = 0;
				/* foreach($accData as $acc){
				$i++;
				
				$data[] = array($i, $acc->student_name,$acc->payment,$acc->payment_status,$acc->payment_date);
				
				} */
        
				$output = array(
					"draw" => 1,
					"recordsTotal" => $this->Account_model->countAll(),
					"recordsFiltered" => $this->Account_model->countFiltered($dt),
					"data" => $accData,
				);
        
				// Output to JSON format
				echo json_encode($output);
				
			}
			
		}
		
		
		
	}
