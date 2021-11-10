<?php
	include_once(APPPATH.'core/ADMIN_controller.php');
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Account extends CI_Controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('admin/admin_model');
			$this->load->model('Common_model');
			$this->load->model('admin/Account_model');
			if(!$this->session->account_type=='Account'){
				redirect(base_url('admin/logout'));
			}
		}
		
		public function index(){
			if($this->session->has_userdata('adminData')){
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
			
			if($this->session->has_userdata('adminData')){
				
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
			
			if($this->session->has_userdata('adminData')){
				
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
 
		public function update_payment_complaint(){
			
			if($this->session->has_userdata('adminData')){
				$where = array("status" => "Pending");
				$centers = $this->Common_model->get_record('payment_complaint','*',$where);

				$data = array('name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
					'centers' =>$centers
				);
				
				$this->load->view('header');
				$this->load->view('admin/account_section/update_payment_complaint',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}

	public function get_center_detail()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
				$center_id  = $this->input->post("center_id");
			
				$wherecenter = 'center_id='.$center_id;
				$center_detail = $this->Common_model->get_record('payment_complaint','*',$wherecenter);
				
				$data = array('center_details' => $center_detail ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());

				if($data['center_details']){

					$dt =  $this->load->view('admin/account_section/getStudentDetail',$data,true);

				}else{

					$dt = "Invalid Center Code";
				}


				echo json_encode(array(
				"status" => true,
				"data" => $dt
				));
		}
			
	}


public function update_request_status()
{
	if ($this->input->method() == "post") 
	{
            $id    	= 0;
            $id    	= $this->input->post("id");
			$status = $this->input->post("status");

			
            if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("payment_complaint",array("id" => $id ),array("status" => $status ));
			
				$dt = $this->db->get_where("payment_complaint",array("id" => $id ))->result_array();

				if($dt[0]['status'] == 'Done')
				{

				$sts_btn = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
				
				}

				else{

				$sts_btn = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
				
			}



				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $sts_btn
				));
			}
	}
}

public function update_request_remark()
{
	if ($this->input->method() == "post") 
	{
        $id    	= 0;
        $id    	= $this->input->post("id");
		$remark = "invalid";
		$status = "Done";

			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("payment_complaint",array("id" => $id ),array("remark" => $remark,"status" => $status));
				
				$dt = $this->db->get_where("payment_complaint",array("id" => $id ))->result_array();

				if($dt[0]['remark'] != 'invalid' || $dt[0]['remark'] == '')
				{

					
				$sts_btn = '<input type="button" name="update_req_remark" data-id='.$id.' class="btn btn-success req_check" value="Set">';
					
				
				}

				else{

				$sts_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-danger req_check" value="Invalid">';
				
				$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';

				}
			

				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $sts_btn,
					"data2" => $sts_btn2
				));
			}		
	}
}
		
		
		
}
