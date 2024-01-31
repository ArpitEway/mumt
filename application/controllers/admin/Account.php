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
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$wherecenter = 'center_id='.$center_id.' and status="Pending"';
			$complaints = $this->Common_model->get_record('payment_complaint','*',$wherecenter);
			
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);

			if($data['complaints']){
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
		$studentContactData = $this->Common_model->getRecordById('student_data','student_id',$student_id);
		$data = array(
			'student' => $student,
			'paymentDetails' => $paymentDetails,
			'studentContactData'=>$studentContactData,
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
		$txnid = $this->input->post('txnid');
	
		$dateTime = $this->input->post('dateTime');
		$student_id = $this->input->post('student_id');
		$dateTime = explode(' ',$dateTime);
		$updateData = array('txnId' => $txnid,'payment_date' => $dateTime[0],'payment_time' => $dateTime[1],'payment' => 'Y', 'payment_status' => 'success');
		$where = array('id' => $id);
		$result = $this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$updateData);
		$whereStudent = array('student_id'=> $student_id);
		$txnDetails = $this->Common_model->getRecordById('online_payment_transaction','id',$id);
		if($txnDetails->fees_head=='Exam Fees'){
			$updateDataStd = array('new_exam_form'=> 'Y'); 
		}elseif ($txnDetails->fees_head=='Admission Fees') {
			$updateDataStd = array('payment_status'=> 'Y'); 
		}
		$result = $this->Common_model->updateRecordByConditions('student',$whereStudent,$updateDataStd);
		if($result){
			$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student_id));
			$data = array('paymentDetails' => $paymentDetails);
			$htmlData = $this->load->view('admin/account_section/view_transaction_details',$data,true); 
			$return = array('success' => 'Transaction Details Updated','data' =>$htmlData);
		}else{
			$return = array('error' => 'An error occurred');
		}
		echo json_encode($return);
		die;
	}


		public function check_payment_transection(){
			$this->load->view('header',array('title' => 'Search Transaction Details'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);

			$this->load->view('admin/check_payment_transection',$data);
			$this->load->view('footer');
		}


	public function get_payment_details(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{

			$text_val =$this->input->post('text_val');
			$radio_val = $this->input->post('radio_val');

			if($text_val !=''){

				if($text_val !='' && $radio_val == 'enrollment_no'){
					$student = $this->Common_model->getRecordById('student','enrollment_no',$text_val);
				}else if($text_val !='' && $radio_val == 'student_id'){
					$student = $this->Common_model->getRecordById('student','student_id',$text_val);
				}

				$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student->student_id ));
				$data = array(
					'student' => $student,
					'paymentDetails' => $paymentDetails,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				if($data){
					$dt =  $this->load->view('admin/account_section/view_student_transaction',$data,true);
					$status = true;
				}else{
					$dt = "This student Does Not Have Any Pending payment Complaint";
					$status = false;
				}
				echo json_encode(array(
					"status" => $status,
					"data" => $dt
				));
			}
		}
	}


	public function view_complaint(){
		$where = array("status" => "P");
		$this->db->like('type','payment');
		$centers = $this->Common_model->get_record_group_by_where('center_complaint','center_id',$where);

		$data = array('name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'centers' =>$centers
		);
		$this->load->view('header');
		$this->load->view('admin/view_complaint_status',$data);
		$this->load->view('footer');
		
	}

	public function get_complaints_status()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();

			$center_id  = $this->input->post("center_id");
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$this->db->like('type','payment');
			$wherecenter = 'center_id='.$center_id.' and status="P"';
			$complaints = $this->Common_model->get_record('center_complaint','*',$wherecenter);
			//	$this->Common_model->last_query();
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);

			if($data['complaints']){
				$dt =  $this->load->view('admin/getComplaints',$data,true);
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


	public function update_complaint_status()
	{
		if ($this->input->method() == "post") 
		{
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");
			
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("center_complaint",array("id" => $id ),array("status" => $status ));

				$dt = $this->db->get_where("center_complaint",array("id" => $id ))->result_array();

				if($dt[0]['status'] == 'D'){
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

	public function update_complaint_remark()
	{
		if ($this->input->method() == "post") 
		{
			$id    	= $this->input->post("id");
			$remark = $this->input->post("remark");
			$status = ($remark=='Set') ? 'P' : "D";
			$remark = ($remark=='Set') ? '' : 'Invalid';
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("center_complaint",array("id" => $id ),array("remark" => $remark,"status" => $status));
				
				$dt = $this->db->get_where("center_complaint",array("id" => $id ))->result_array();
				
				if($dt[0]['remark'] == 'Invalid'){
					$sts_btn = '<input type="button" name="update_req_remark" data-id='.$id.' class="btn btn-danger remark_check" value="Invalid">';
					$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
				}else{

					$sts_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-success remark_check" value="Set">';

					$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
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

	public function complaint_form_sub(){
		$id = $this->input->post('complain');
		$redy = $this->input->post('redy');

		$where = array(
			'id' => $id	);
		$studentData = array(
			'type' => $redy	);

		$update =  $this->Common_model->updateRecordByConditions('center_complaint',$where,$studentData);
		if($update){
			echo json_encode(array(
				"success" => ' Updated Successfully',
			));
		}else{
			echo json_encode(array(
				"error" => ' error Occured',
			));
		}
	}

	public function add_new_txn(){		
		$txnid = $this->input->post('txnId');
		$Fess_head = $this->input->post('fees_head');
		$dateTime = $this->input->post('dateTime');
		$student_id = $this->input->post('student_id');
		$where = array('student_id'=>$student_id);
		$student_details =  $this->Common_model->getRecordByWhere('student',$where);
		$course_details =  $this->Common_model->getRecordByWhere('course',array('course_group_id'=>$student_details[0]->course_group_id,'session'=>$student_details[0]->session));
		$center_id = $student_details[0]->center_id;
		$course_group_id = $student_details[0]->course_group_id;
		$remark = '';
		if($Fess_head == 'Admission Fees'){
	     $session = $student_details[0]->session;
		}else{
			$session = 'Dec 2023';
		}
		$class_id = $student_details[0]->class_id;
		$name = $student_details[0]->name;

        if($student_details[0]->university_mode=='REG'){
            if($Fess_head!=''){
                if($student_details[0]->demo == 'Y'){
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->exam_fees : $course_details[0]->form_fees+$course_details[0]->admission_fees;
                }else{
                    
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->exam_fees+$course_details[0]->program_fees : $course_details[0]->form_fees+$course_details[0]->admission_fees;
                }
                
            } 
	    }else{
           if($Fess_head!=''){
                if($student_details[0]->demo == 'Y'){
                $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->p_exam_fees : $course_details[0]->p_form_fees+$course_details[0]->p_admission_fees;
                }else{
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->p_exam_fees+$course_details[0]->p_program_fees : $course_details[0]->p_form_fees+$course_details[0]->p_admission_fees;
                }
		    } 
	    }
	    
		$dateTime = explode(' ',$dateTime);
		$updateData = array('txnId' => $txnid,'fees_head'=>$Fess_head,'payment_date' => $dateTime[0],'payment_time' => $dateTime[1],'payment' => 'Y', 'payment_status' => 'success','student_id'=>$student_id
			,'center_id'=>$center_id,'course_group_id'=>$course_group_id,'class_id'=>$class_id,'remark'=>$remark,'student_name'=>$name,'exam_session'=>$session,
			'admission_type'=>'Regular','amount'=>
			$exam_fees
		);
        if($student_details[0]->new_exam_form!='D'){
			$transaction = $this->Common_model->insertAll('online_payment_transaction',$updateData);
		}
		$where1 = array('student_id' => $student_id);
		if($Fess_head=='Admission Fees'){	
		
			$result = $this->Common_model->updateRecordByConditions('student',$where1,array('payment_status'=> 'Y'));
		}elseif($Fess_head=='Exam Fees' && $student_details[0]->new_exam_form!='D'){
			
			$result = $this->Common_model->updateRecordByConditions('student',$where1,array('new_exam_form'=> 'Y'));
		}
		if($result){
		
			$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student_id));
			$data = array('paymentDetails' => $paymentDetails);
			$htmlData = $this->load->view('admin/account_section/view_transaction_details',$data,true); 
			$return = array('success' => 'Transaction Details Updated','data' =>$htmlData);
		}else{
			$return = array('error' => 'An error occurred');
		}
		echo json_encode($return);
		
	}

	public function unpaid_student(){
			
		if($this->session->has_userdata('adminData')){
			$where = array("status" => "Pending");
			//$centers = $this->Common_model->get_record_group_by_where('payment_complaint','center_id',$where);
			$centers = $this->Common_model->getRecordByWhere('center',array('payment_gateway_permission'=>'N'));
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
		//	print_r($centers);
			$this->load->view('header',array('title' => 'Centerwise Unpaid Student'));
			$this->load->view('admin/account_section/unpaid_student',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

	public function get_unpaid_student()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
			$center_id  = $this->input->post("center_id");
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$wherecenter = 'center_id='.$center_id.' and status="Pending"';
			

			$this->db->select('s.student_id as student_id,s.name as name,s.f_h_name as fathername,s.course_name as course_name,s.class_name as class_name,p.amount as amount');
			$this->db->from('`student` as s');
			$this->db->join('online_payment_transaction as p', 'p.student_id=s.student_id');
			$this->db->where('p.payment','N');
			$this->db->where('s.payment_status','N');
			$this->db->where('p.center_id',$center_id); 
			
			$complaints = $this->db->get()->result();
			
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);

			
				$dt =  $this->load->view('admin/account_section/getUnpaidStudent',$data,true);
				$status = true;
			
			echo json_encode(array(
			"status" => $status,
			"data" => $dt
			));
		}
	}

	public function unpaid_student_exam_form(){
			
		if($this->session->has_userdata('adminData')){
			$where = array("status" => "Pending");
			//$centers = $this->Common_model->get_record_group_by_where('payment_complaint','center_id',$where);
			$centers = $this->Common_model->getRecordByWhere('center',array('payment_gateway_permission'=>'N'));
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
		//	print_r($centers);
			$this->load->view('header',array('title' => 'Centerwise Exam Fees Unpaid Student'));
			$this->load->view('admin/account_section/unpaid_student_exam_form',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

	public function get_unpaid_student_exam_form()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
			$center_id  = $this->input->post("center_id");
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			
			$complaints = $this->Common_model->getRecordByWhere('student',array('new_exam_form'=>'N','center_id'=>$center_id));
			// $this->Common_model->last_query();
			
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);

			
				$dt =  $this->load->view('admin/account_section/getUnpaidStudentExamForm',$data,true);
				$status = true;
			
			echo json_encode(array(
			"status" => $status,
			"data" => $dt
			));
		}
	}

	public function search_unpaid_student(){
			
		if($this->session->has_userdata('adminData')){
			$where = array("status" => "Pending");
			
			
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				
			);
	
			$this->load->view('header',array('title' => 'Search Unpaid Student'));
			$this->load->view('admin/account_section/search_unpaid_student',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

	public function get_search_unpaid_student()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
			$student_id  = $this->input->post("form_number");
			$studentData = $this->Common_model->getRecordById('student','student_id',$student_id);
			$centerData = $this->Common_model->getRecordById('center','id',$studentData->center_id);
			
			$this->db->select('s.student_id as student_id,s.name as name,s.f_h_name as fathername,s.course_name as course_name,s.class_name as class_name,p.amount as amount');
			$this->db->from('`student` as s');
			$this->db->join('online_payment_transaction as p', 'p.student_id=s.student_id');
			$this->db->where('p.payment','N');
			$this->db->where('s.payment_status','N');
			$this->db->where('p.student_id',$student_id); 
			
			$complaints = $this->db->get()->result();
			
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);
			$dt =  $this->load->view('admin/account_section/getUnpaidStudent',$data,true);
			$status = true;
			
			echo json_encode(array(
			"status" => $status,
			"data" => $dt
			));
		}
	}
	public function view_center_wise_complaint(){
			
		if($this->session->has_userdata('adminData')){
			$admin_id = $this->session->admin_id;
			$admin = $this->Common_model->getRecordById('admin_master','id',$admin_id);
			$where = "support_complaint.status = 'Pending' AND support_system.id IN (".$admin->support_ids.")";
				$this->db->select('count(*) as count,'.'center_id');
				$this->db->from('support_complaint');
				$this->db->join('support_system','support_system.name = support_complaint.type');
				$this->db->where($where);
				$this->db->group_by('center_id');
				$centers = $this->db->get()->result_array();
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
			
			$titleData = array('title' => 'Complaints');
			$this->load->view('header',$titleData);
			$this->load->view('admin/view_center_wise_complaint',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url());
		}
	}

}