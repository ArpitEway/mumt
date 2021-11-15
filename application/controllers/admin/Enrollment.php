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
			if($this->session->account_type!='enrollment'){
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
			
			if($this->session->has_userdata('adminData')){
				$this->load->view('header');
				$this->load->view('admin/enrollment/dashboard');
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
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
				if($class_id != ""){	 
					
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
					
					$data['course_count'] = $this->Common_model->student_data_consolidate($dt,'course_group_id');
					
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
			
			$remark_ids = $this->Common_model->getStudentRemarkID($param);
			$remark_ids = explode(",",$remark_ids);
			foreach($remark_ids as $remark_id ){
				$remark[] = $this->Common_model->getStudentRemarkNameById($remark_id)."<br>";
			}
			
			echo json_encode(array(
			"status" => 'true',
			"remark" => $remark
			
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
				
				$course_group_id  = $this->input->post("course_group_id");
				$class_id  = $this->input->post("class_id");
				
				$approved = $this->input->post("approved");
				
				$session = $this->input->post("session");
				
				if($course_group_id != "all"){	
					$dt['course_group_id'] = $course_group_id;
				}
				if($class_id != ""){	
					$dt['class_id'] = $class_id;
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
		
		public function unpaid_student_list(){
			if($this->session->has_userdata('adminData'))
			{
				$dt = array();
				$data = array();
				$dt['payment_status'] = 'N';
				$data['students'] = $this->Common_model->all_student_info_by_where($dt);
				$dt['title'] = "Unpaid student";
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('header',$dt);
				$this->load->view('admin/enrollment/unpaid_student_list',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('enrollment/login'));
			}
		}
		
		public function update_unpaid_student(){
			
			if ($this->input->method() == "post") 
			{
				$payment_date  = $this->input->post("payment_date");
				
				$student_id  = $this->input->post("student_id");
				$remark  = $this->input->post("remark");
				$payment_mode  = $this->input->post("payment_mode");
				$amount  = $this->input->post("amount");
				$file_name = '';
				if(isset($_FILES['images']) && $_FILES['images']['tmp_name']!=''){
				$filename = $student_id.'-'.date('Ymdhis');
				$this->upload->initialize($this->Common_model->set_upload_options('./assets/transactionImgaes/',$filename));
				if(!$this->upload->do_upload('images')){
					$error = $this->upload->display_errors();
					$msg = array('error'=>$error);
					echo json_encode($msg);
					exit();
					
				}else{
				$uploadData = $this->upload->data();
				$file_name = $uploadData['file_name'];
				}
				}
				$updateData = array(
					'student_id' => $student_id,
					'payment_date' => $payment_date,
					'remark' => $remark,
					'payment_mode' => $payment_mode,
					'amount' => $amount,
					'image' => $file_name
				);
				$response = $this->admin_model->unpaid_student_update($updateData);
				if($response){
				echo json_encode(array("status" => 'true'));
				}
			}
		}
		
		public function view_payment_list(){
			
			if($this->session->has_userdata('adminData')){
				$titleData = array('title' => 'Paid Student');
				$this->load->view('header',$titleData);
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('admin/enrollment/view_payment_list');
				$this->load->view('footer');
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
				$dt['payment'] = 'Y' ;
				$dts['accData'] = $this->Account_model->account_data($dt);
				$data =  $this->load->view('admin/enrollment/get_payment_list',$dts,true);
				echo json_encode(array(
				"status" => true,
				"data" => $data
				));
			}
		}
		
		public function status(){
			$this->load->view('header',array('title'=>'status'));
			$this->load->view('admin/enrollment/status');
			$this->load->view('footer');
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

public function getOtherCourse($student_id)
{
	$student = $this->Common_model->getRecordById('student','student_id',$student_id);
	$user_id = $student->user_id;
	$where = 'user_id='.$user_id.' and student_id!='.$student_id;
	$other_student = $this->Common_model->getRecordByWhere('student',$where);
	$data = array('other_student' => $other_student);
	$data['name_csrf'] = $this->security->get_csrf_token_name();
	$data['hash_csrf'] = $this->security->get_csrf_hash();
	$this->load->view('admin/student/other_course',$data);
}

public function update_aadhar($param){
			
	if($this->session->has_userdata('adminData')){

	$data['student_id'] = $this->Common_model->encrypt_decrypt($param,'decrypt'); 


	$dt['student.student_id'] = $data['student_id'];
	$data['student_detail'] = $this->Common_model->student_data($dt);

	$this->load->view('header');
	$this->load->view('admin/student/update_aadhar',$data);
	$this->load->view('footer');

	}
	else
	{
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

	public function check_enrollment_status()
	{
		$session_july='July 2021';		// All Class

		$where = array('session'=>$session_july);
		$data['total_student'] = $this->Common_model->getCountByWhere('student',$where);

       //---paid------
		$where = array('payment_status'=>'Y','session'=>$session_july);
		$data['tot_paid'] = $this->Common_model->getCountByWhere('student',$where);

       // --- not paid------
		$where = array('payment_status'=>'N','session'=>$session_july);
		$data['tot_unpaid'] = $this->Common_model->getCountByWhere('student',$where);

       //---paid and uploaded--------
		$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july);
		$data['uploaded'] = $this->Common_model->getCountByWhere('student',$where);
        
        //---not uploaded--------
		$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july);
		$data['not_uploaded'] = $this->Common_model->getCountByWhere('student',$where);
        
        //---paid/uploaded/ is = approved---
		$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july);
		$data['non_approved'] = $this->Common_model->getCountByWhere('student',$where);

        // paid + uploaded but approved = '' not verified----
		$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july);
		$data['not_verified'] = $this->Common_model->getCountByWhere('student',$where);

         // paid + uploaded + approved = Y  verified----
		$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july);
		$data['approved'] = $this->Common_model->getCountByWhere('student',$where);

		// enrollement genrated
		$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july);
		$data['en_generated'] = $this->Common_model->getCountByWhere('student',$where);

		// not enrollement genrated
		$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july);
		$data['not_en_generated'] = $this->Common_model->getCountByWhere('student',$where);

		// enrolled
		$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july);
		$data['tot_enrolled'] = $this->Common_model->getCountByWhere('student',$where);

		// enrolled
		$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july);
		$data['tot_not_enrolled'] = $this->Common_model->getCountByWhere('student',$where);

  //        echo '<pre>';
		// print_r($data);die;
        $this->load->view('header');
	    $this->load->view('admin/enrollment/enrollment_status_count',$data);
	    $this->load->view('footer');

	    
		
		
	}

}//class
