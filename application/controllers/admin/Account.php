<?php
	include_once(APPPATH.'core/ADMIN_controller.php');
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Account extends CI_Controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('admin/admin_model');
			$this->load->model('Common_model');
			$this->load->model('admin/Account_model');
			if($this->session->account_type!='Account'){
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

		public function view_payment_complaint(){
			
			if($this->session->has_userdata('adminData')){
				$where = array("status" => "Pending");
				$centers = $this->Common_model->get_record_group_by_where('payment_complaint','center_id',$where);

				$data = array('name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
					'centers' =>$centers
				);
				
				$this->load->view('header');
				$this->load->view('admin/account_section/view_payment_complaint',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}

	public function get_payment_complaints()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
			$center_id  = $this->input->post("center_id");
		
			$wherecenter = 'center_id='.$center_id.' and status="Pending"';
			$center_detail = $this->Common_model->get_record('payment_complaint','*',$wherecenter);
			
			$data = array('center_details' => $center_detail ,'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash());

			if($data['center_details']){
				$dt =  $this->load->view('admin/account_section/getPaymentComplaints',$data,true);
				$status = true;
			}else{
				$dt = "This Center Does Not Have Any Pending payment Complaint";
				$status = false;
			}
			echo json_encode(array(
			"status" => $status,
			"data" => $dt
			));
		}
	}


	public function update_payment_complaint_status()
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

				if($dt[0]['status'] == 'Done'){
				$sts_btn = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
				}else{
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

	public function update_payment_complaint_remark()
	{
		if ($this->input->method() == "post") 
		{
	        $id    	= $this->input->post("id");
	        $remark = $this->input->post("remark");
			$status = ($remark=='Invalid') ? 'Done' : "Pending";

			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("payment_complaint",array("id" => $id ),array("remark" => $remark,"status" => $status));
				
				$dt = $this->db->get_where("payment_complaint",array("id" => $id ))->result_array();
				
				if($dt[0]['remark'] != 'Invalid'){
				
				$sts_btn = '<input type="button" name="update_req_remark" data-id='.$id.' class="btn btn-success remark_check" value="Set">';
				
				$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
				}else{
				
				$sts_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-danger remark_check" value="Invalid">';
				
				$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
				}

				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"remarkBtn" => $sts_btn,
					"statusBtn" => $sts_btn2
				));
			}	
		}
	}

	public function view_student_transaction($student_id)
	{
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$student = $this->Common_model->getRecordById('student','student_id',$student_id);
		$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student_id));
		$data = array(
			'student' => $student,
			'paymentDetails' => $paymentDetails,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$this->load->view('header',array('title' => 'View Student Transaction'));
		$this->load->view('admin/account_section/view_student_transaction',$data);
		$this->load->view('footer');
	}

	public function updatePaymentTransaction()
	{
		$id = $this->input->post('id');
		$txnid = $this->input->post('TxnId');
		$dateTime = $this->input->post('dateTime');
		$dateTime = explode(' ',$dateTime);
		$updateData = array('txnId' => $txnid,'payment_date' => $dateTime[0],'payment_time' => $dateTime[1],'payment' => 'Y', 'payment_status' => 'captured');
		$where = array('id' => $id);
		$result = $this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$updateData);
		if($result){
			$return = array('success' => 'Transaction Details Updated');
		}else{
			$return = array('error' => 'An error occurred');
		}
		echo json_encode($return);
		die;
	}
}