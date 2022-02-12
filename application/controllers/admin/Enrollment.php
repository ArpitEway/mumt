<?php
	include_once(APPPATH.'core/ADMIN_controller.php');
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Enrollment extends CI_Controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('admin/admin_model');
			$this->load->model('admin/Account_model');
			$this->load->model('Common_model');
			$this->load->model('Datatable_join_model');
			if($this->session->account_type!='Enrollment'){
				redirect(base_url('admin/logout')); 
			}
		}
		
		public function index(){
			
			if($this->session->has_userdata('adminData')){
				$admin_id = $this->session->admin_id;
				$where = 'admin_id='.$admin_id.' and status="Y"';
				$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
				);
				$this->load->view('header',array('title' => 'Enrollment Section'));
				$this->load->view('admin/enrollment/dashboard',$menu);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}

		public function dashboard(){
			
			redirect(base_url('admin/enrollment'));
		}

		public function student_report(){
			
			if($this->session->has_userdata('adminData')){
				$data = array();
				$data['title'] = "Student Verification";
				$this->load->view('header',$dt);
				$this->db->order_by('id', 'Desc');
				$data['sessions'] = $this->db->get_where('session', array())->result_array();
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('admin/enrollment/view_student_report',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}
		
		public function consolidate_report(){
			if($this->session->has_userdata('adminData'))
			{
				$dataTitle = array(); 
				$dataTitle['title'] = "Student Consolidate Report";
				$this->load->view('header',$dataTitle);
				$this->db->order_by('id', 'Desc');
				$data['sessions'] = $this->db->get_where('session', array())->result_array();
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('admin/enrollment/consolidate_report',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('enrollment/login'));
			}
		}

		
		public function get_student_consolidate_data()
		{ 
			if ($this->input->method() == "post") 
			{
				
				$course_group_id = 0;
				
				$data = array();
				$dt   = array();
				
				$course_group_id  = 	$this->input->post("course_group_id");
				$class_id  		  = 	$this->input->post("class_id");
				$approved 		  = 	$this->input->post("approved");
				$payment 		  = 	$this->input->post("payment");
				$enrolled 		  = 	$this->input->post("enrolled");
				$document_upload  = 	$this->input->post("document_upload");
				$filter  		  = 	$this->input->post("filter");
				$session 		  = 	$this->input->post("session");
			    $mode 		  	  = 	$this->input->post("mode");
				$center 	  	  = 	$this->input->post("center");
				$count_filter = $this->input->post("count_filter");

				if($mode != "all"){	 
					$dt['mode'] = $mode;
				}
				
				if($center != "all"){	 
					$dt['center_id'] = $center;
				}
				
				if($course_group_id != "all"){	 
					$dt['course_group_id'] = $course_group_id;
				}
				
				if($session != "all"){	 
					$dt['session'] = $session;
				}else{
					$dt['name!='] = '';
				}
				
				if($class_id != "All"){	 
					$dt['class_id'] = $class_id;
				}
				
				if($approved != "all"){
					$dt['approved'] = $approved;
				}
				
				if($payment != "all"){
					$dt['payment_status'] = $payment;
				}
				
				if($enrolled != "all"){
					$dt['enrolled'] = $enrolled;
				}
				
				if($document_upload != "all"){
					$dt['document_uploaded'] = $document_upload;
				}
				
				if($filter == "list"){
					$data['students'] = $this->Common_model->student_data_consolidate($dt);
				
				}
				
				if($filter == "count"){
					if($count_filter == "course_wise"){
						$data['count_filter'] = 'course_wise';
						$data['course_count'] = $this->Common_model->student_data_consolidate($dt,'course_group_id');
					}else{
						$data['count_filter'] = 'center_wise';
						$data['course_count'] = $this->Common_model->student_data_consolidate($dt,'center_id');
					}
				}
				
				
				$dt = $this->load->view('admin/student/getStudentConsolidate',$data,true);
				echo json_encode(array(
				"status" => true,
				"data" => $dt
				));
			}
		}
		
		
		public function student_doc_update($param){
			
			$response = $this->admin_model->student_doc_update($param);
			$this->session->set_flashdata('ajax_flash_message','Non approved');
			
			$student = $this->Common_model->getRecordById('student','student_id',$param);
			$remark_ids = $student->remark;
			$remark_ids = explode(",",$remark_ids);
			
			foreach($remark_ids as $remark_id ){
				$remark[] = $this->Common_model->getStudentRemarkNameById($remark_id)."<br>";
			}
			
			echo json_encode(array(
			"status" => 'true',
			"remark" => $remark,
			"remark_detail" => $student->remark_detail
			));
			//redirect(base_url().'admin/enrollment/student_report');
		}
		
		public function make_approved($param){
			$param = $this->Common_model->encrypt_decrypt($param,'decrypt');
			$response = $this->admin_model->student_approve($param);
			$json = json_decode($response);
			if($json->status == 'true'){
				$this->session->set_flashdata('ajax_flash_message','Approved');
				}else{
				$this->session->set_flashdata('ajax_error_message','Admission pending');
			}
		}
		
		
		public function get_student_data()
		{
			
			if ($this->input->method() == "post") 
			{
				$course_group_id = 0;
				$data = array();
				$dt   = array();

				$center 	  	  = 	$this->input->post("center");
				$course_group_id  = $this->input->post("course_group_id");
				$approved = $this->input->post("approved");
				$session = $this->input->post("session");
				

                  if($center != "all"){	 
					
					$dt['center_id'] = $center;
				}


				if($course_group_id != "all"){	
					$dt['course_group_id'] = $course_group_id;
				}
				
				if($approved != "all"){
					$dt['approved'] = $approved;
				}if($session != "All"){
					$dt['session'] = $session;
				}
				
				$dt['payment_status'] = "Y";
				$dt['document_uploaded'] = "Y";
				
				$data['students'] = $this->Common_model->student_data($dt);
				
				$dt =  $this->load->view('admin/student/getstudent',$data,true);
				echo json_encode(array(
				"status" => true,
				"data" => $dt
				));
			}
			
		}
		
		public function show_form($student_id){

			$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt'); 

			$student = $this->Common_model->student_info($student_id);
			$data = array(
				'student' => $student,
			);
			$this->load->view('header',array('title' => 'Admission Form'));
			$this->load->view('template/form',$data);
			$this->load->view('footer');
		}
		
		public function generate_enrollment(){
			if(isset($_POST['action']) && $_POST['action']=='view'){
				$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no in ("-","")','course_group_id','ASC');
				$session = $this->Common_model->getSessionForEnrollment();
				$enrollment_no = $this->Common_model->getCountByWhere('student','approved="Y" and enrollment_no not in ("-","") and session ="'.$session.'"');
				$enrollment_no+=1;
				$en_session = substr($session, -2);
				$data = array(
				'students' => $student,
				'enrollment_no' => $enrollment_no,
				'en_session' => $en_session,
				'action' => 'view',
				);
				}else if(isset($_POST['action']) && $_POST['action']=='generate'){
				
				$student_id = $this->input->post('student_id');
				$enrollment_no = $this->input->post('enrollment_no');
				
				$this->Common_model->genrateEnrollment($student_id,$enrollment_no);
				
				$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no not in ("-","")','course_group_id','ASC');
				$data = array(
				'students' => $student,
				'action' => 'generate',
				);
				}else{
				$data = array(
				'student' => '',
				'action' => '',
				);
			}
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->load->view('header',array('title' => 'Generate Enrollment'));
			$this->load->view('admin/enrollment/generate_enrollment',$data);
			$this->load->view('footer');
		}
		
		public function enrollment_permission(){
			if(!isset($_POST['action'])){
				$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no not in ("-","") and enrolled="N"','course_group_id','ASC');
				$data = array(
				'students' => $student,
				);
				$this->load->view('header',array('title' => 'Enrollment Permission'));
				$this->load->view('admin/enrollment/set_enrollment_permission',$data);
				$this->load->view('footer');
				}else if($_POST['action']=='setPermission'){
				$enrollment_nos = $this->input->post('enrollment_no');
				$this->iniEmail();
				$filename = base_url('assets/images/maskgroup/MaskGroup1.png');
				
				foreach($enrollment_nos as $enrollment_no){				
					$data = array('enrolled' => 'Y');
					$where = 'enrollment_no="'.$enrollment_no.'"';
					$this->Common_model->updateRecordByConditions('student',$where,$data);
					$student = $this->Common_model->getRecordById('student','enrollment_no',$enrollment_no);
					$studentdetail = $this->Common_model->getRecordById('student_data','student_id',$student->student_id);
					$this->email->from('info@mpsvv.in', 'MPSVV');
					//$this->email->to('akshay.eway@gmail.com');
					$this->email->bcc('mpsvvenrolstatus@gmail.com');
					$this->email->to($studentdetail->p_email);
					$this->email->subject('Enrollment Generated');
					$studentData = array(
					"student" => $student
					);
					$msgbody = $this->load->view('template/email/enrollment_info_2',$studentData,true);
					$this->email->message($msgbody);
					$mail = $this->email->send();
				}
				$this->session->set_flashdata('ajax_flash_message','permission updated');
				redirect(base_url().'admin/enrollment/enrollment_permission');		
			}
		}
		
		
		private function iniEmail(){
			
			$config['protocol'] = 'SMTP';
			$config['smtp_host'] = 'mpsvv.in';
			$config['smtp_user'] = 'info@mpsvv.in';
			$config['smtp_pass'] = '9A0xki1#';
			$config['smtp_port'] = 465;
			$config['smtp_crypto'] = 'ssl';
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;
			$config['mailtype'] = 'html';
			$this->email->initialize($config);	
		}
		
		public function print_form($student_id){
			$where =  array(
				'student_id' => $student_id,
				'payment' => 'Y'
			);
			$payment_details = $this->Common_model->get_record('online_payment_transaction','*',$where);
			$student = $this->Common_model->student_info($student_id);
			$data = array(
				'payment_details'=> $payment_details,
				'student' => $student,
			);
			$this->load->view('header',array('title' => 'Admission Form'));	
			$this->load->view('template/form',$data);
			$this->load->view('footer');
		}

		public function update_aadhar($param){
			if($this->session->has_userdata('adminData')){

			$data['student_id'] = $this->Common_model->encrypt_decrypt($param,'decrypt');
			$where['student.student_id'] = $data['student_id'];
			$data['student_detail'] = $this->Common_model->student_data($where);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();

			$this->load->view('header');
			$this->load->view('admin/student/update_aadhar',$data);
			$this->load->view('footer');
			}else{
				redirect(base_url('admin/login'));
			}
		}

		public function aadhar_update($param){
					
			$response = $this->admin_model->aadhar_update($param);

			$dt['student.student_id'] = $param;
			$student_detail = $this->Common_model->student_data($dt);

			$aadhar_no = wordwrap($student_detail[0]['adhar_no'], 4, ' ',true);

			echo json_encode(array(
			"status" => 'true',
			"res" => $aadhar_no
			
			));
		}

		public function checkDuplicateAdharNo()
		{
			$adhar_no = $this->input->post('adhar_no');
			$where = array('adhar_no'=>$adhar_no,'course_complete'=>'N');
			$count = $this->Common_model->getCountByWhere('student',$where);
			if($count>0){
				echo "Duplicate Adhar Card Number";
			}
		}

	public function center_request(){
		if($this->session->has_userdata('adminData'))
		{
			$where = array("status" => "Pending");
			$centers = $this->Common_model->get_record_group_by_where('request','center_id',$where);
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
			$this->load->view('header');
			$this->load->view('admin/enrollment/view_form_edit_request',$data);
			$this->load->view('footer');
		}else{
			redirect(base_url('admin/login'));
		}
	}

	public function getFormEditRequest()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
			$center_id  = $this->input->post("center_id");
			$wherecenter = 'center_id='.$center_id;
			$center_detail = $this->Common_model->get_record('request','*',$wherecenter);
			$data = array('center_details' => $center_detail ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());
			if($data['center_details']){
				$dt =  $this->load->view('admin/enrollment/FormEditRequestDetails',$data,true);
			}else{
				$dt = "Invalid Center Code";
			}
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		}
	}

	public function editForm($student_id = ""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin/'));
			exit;
		}

		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$titleData = array('title' => 'Admission Form'); 
		$state_list = $this->Common_model->get_record('state','*');
		$eligibility_list = $this->Common_model->get_record('course_group','DISTINCT (eligibility)');
		$district_list = $this->Common_model->get_record('distt','*');
		$course_group_list = $this->Common_model->get_record('course','*');
		
		$data = array(
			'state_list' => $state_list,
			'district_list' => $district_list,
			'course_group_list' => $course_group_list,
			'session' => 'July 2021',
			'eligibility_list' => $eligibility_list,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'student_detail' => $this->db->get_where('student', array("student_id" => $student_id))->row(),
			'student_data'  => $this->db->get_where('student_data', array("student_id" => $student_id))->row()
		
		);

		$this->load->view('header',$titleData);
		$this->load->view('admin/editForm',$data);
		$this->load->view('footer');
	}

	public function update_form_edit_request_status(){
		if ($this->input->method() == "post"){
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");
			if ($this->input->post("id")){
				$data = $this->Common_model->updateRecordByConditions("request",array("id" => $id),array("status" => $status ));

				$dt = $this->db->get_where("request",array("id" => $id ))->result_array();

				if($dt[0]['status'] == 'Done')
				{
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

	public function getCenterRequest()
		{
			$data = $row = array();
			$where = 'request.center_id='.$this->session->center_id;
			
			$column_order = array(null,'name','student.student_id','detail','date','status','request_remark');
			$column_search = array('name','student.student_id','detail','date','status','request_remark');

			$DataTableArray = array(
				'column_order' => $column_order,
				'column_search' => $column_search,
				'where' => $where,
				'table' =>  'request',
				'table2' => 'student',
				'joinOn' => 'request.student_id=student.student_id'
			);

			$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
			$i = $_POST['start'];
			foreach($tableData as $result){
				$i++;
				$date = $this->Common_model->viewDate($result->date);
				$data[] = array($i, $result->name, $result->student_id, $result->detail,$date,$result->status,$result->request_remark);
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Datatable_join_model->countAll('request',$where),
				"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
				"data" => $data,
			);
		
			// Output to JSON format
			echo json_encode($output);
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

		public function update_form_request_remark()
		{
			if ($this->input->method() == "post"){
				$id = $this->input->post('id');
				$remark = $this->input->post('remark');
				$status = ($remark=='Set') ? 'Pending' : 'Done';
				$remark = ($remark=='Set') ? '' : 'Invalid';
				if($this->input->post("id")){
					$data = $this->Common_model->updateRecordByConditions("request",array("id" => $id),array("status" => $status,'request_remark' => $remark ));
					$dt = $this->db->get_where("request",array("id" => $id ))->result_array();
					if($dt[0]['request_remark'] == 'Invalid'){
						$remark_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-danger remark_check" value="Invalid">';
						$sts_btn = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
					}else{
						$remark_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-success remark_check" value="Set">';
						$sts_btn = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
					}
					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"remark_btn" => $remark_btn,
						"sts_btn" => $sts_btn
					));
				}
			}
		}
	}
