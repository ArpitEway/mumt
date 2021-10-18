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
			
			if($this->session->has_userdata('username')){
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
		
		public function user_enquiry(){
			
			if($this->session->has_userdata('username')){
				
				$data = array();
				$data['user_enquiry'] = $this->db->get_where('user_enquiry', array('status <'=> 3))->result_array();
				$dt = array();
				$dt['title'] = "User Enquiry";
				$this->load->view('header',$dt);
				$this->load->view('admin/enrollment/user_enquiry',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}
		
		public function dashboard(){
			
			if($this->session->has_userdata('username')){
				
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
			
			if($this->session->has_userdata('username')){
				$dt = array();
				$dt['title'] = "Student Verification";
				$this->load->view('header',$dt);
				$this->db->order_by('id', 'Desc');
				$dt['sessions'] = $this->db->get_where('session', array())->result_array();
				$this->load->view('admin/enrollment/view_student_report',$dt);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}
		
		
		public function consolidate_report(){
			if($this->session->has_userdata('username'))
			{
				$dt = array();
				$dt['title'] = "Student Consolidate Report";
				$this->load->view('header',$dt);
				$this->db->order_by('id', 'Desc');
				$dt['sessions'] = $this->db->get_where('session', array())->result_array();
				$this->load->view('admin/enrollment/consolidate_report',$dt);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('enrollment/login'));
			}
		}
		public function edit_non_verified_list(){
			if($this->session->has_userdata('username'))
			{
				$dt = array();
				$data = array();
				$dt['approved'] = "";
				$data['students'] = $this->Common_model->all_student_info_by_where($dt);
				$dt['title'] = "Edit Non Verified Studnets";
				$this->load->view('header',$dt);
				$this->load->view('admin/enrollment/edit_non_verified_list',$data);
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
				
				$course_group_id  = $this->input->post("course_group_id");
				$class_id  = $this->input->post("class_id");
				$approved 		  = $this->input->post("approved");
				$payment 		  = $this->input->post("payment");
				$enrolled 		  = $this->input->post("enrolled");
				$document_upload  = $this->input->post("document_upload");
				$filter  		  = $this->input->post("filter");
				$form_status  	  = $this->input->post("form_status");
				$program_fees  	  = $this->input->post("program_fees");
				$session 		  = $this->input->post("session");
				
				if($course_group_id != "all"){	 
					
					$dt['course_group_id'] = $course_group_id;
				}
				if($session != "All"){	 
					
					$dt['session'] = $session;
				}else{
					$dt['name!='] = '';
				}
				if($class_id != ""){	 
					
					$dt['class_id'] = $class_id;
				}
				if($program_fees != "all"){	
					
					$dt['program_fees'] = $program_fees;
				}
				
				if($form_status != "all"){
					
					$dt['form_status'] = $form_status;
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
			
			$response = $this->admin_model->student_approve($param);
			$json = json_decode($response);
			if($json->status == 'true'){
				$this->session->set_flashdata('ajax_flash_message','Approved');
				redirect(base_url().'admin/enrollment/student_report');
				}else{
				$this->session->set_flashdata('ajax_error_message','Admission pending');
				redirect(base_url().'admin/enrollment/student_report');
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
				$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no in ("-","") and program_fees="Y"','course_group_id','ASC');
				$session = $this->Common_model->getSessionForEnrollment();
				$enrollment_no = $this->Common_model->getCountByWhere('student','approved="Y" and enrollment_no not in ("-","") and session ="'.$session.'" and program_fees="Y"');
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
				
				$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no not in ("-","") and program_fees="Y"','course_group_id','ASC');
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
			$this->load->view('header',array('title' => 'Generate Enrollment'));
			$this->load->view('admin/enrollment/generate_enrollment',$data);
			$this->load->view('footer');
			
			
		}
		
		public function enrollment_permission(){
			if(!isset($_POST['action'])){
				$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no not in ("-","") and program_fees="Y" and enrolled="N"','course_group_id','ASC');
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
		
		/*
			1. js in edit_student js file 
			2. update code in  admin/updatestudentdata 
			3. copied from admission files
		*/
		
		public function edit_student($student_id){		
			$wherestudent = 'student_id='.$student_id;
			$student = $this->Common_model->get_record('student','*',$wherestudent);
			$userData = $this->Common_model->get_record('user_enquiry','*',$wherestudent);
			$courseData = $this->Common_model->getRecordById('course_group','id',$student[0]['course_group_id']);
			$titleData = array('title' => 'Admission Form'); 
			$category_list = $this->Common_model->getDistinct('course_group','category');
			$documentData = $this->Common_model->get_record('document_category','*','document_id='.$courseData->document_id);
			$state_list = $this->Common_model->get_record('state','*');
			$district_list = $this->Common_model->get_record('distt','*');
			$course_group_list = $this->Common_model->get_record('course_group','*','category="'.$student[0]['course_category'].'"');
			$class = $this->Common_model->get_record('class_master','*','course_group_id='.$student[0]['course_group_id'].' and admission_permission="Y"');
			$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$class[0]['id'].' and ce="compulsory"');
			$docLength = $this->Common_model->getCountByWhere('document_category','document_id ='.$courseData->document_id.' and status="Y"');
			$data = array(
			'course_group_list' => $course_group_list,
			'category_list' => $category_list,
			'documentData' => $documentData,
			'state_list' => $state_list,
			'district_list' => $district_list,
			'class' => $class[0],
			'compulsoryPapers' => $compulsoryPapers,
			'docLength' => $docLength,
			'courseData' => $courseData,
			'student' => $student[0],
			'userData' => $userData[0],
			);
			$studentdata = $this->Common_model->get_record('student_data','*',$wherestudent);
			$data['studentdata'] = $studentdata[0];
			$data['courseData'] = $courseData;
			$exam_papers= array();
			if($class[0]['class_group']=='Y'){
				$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id where class_id='.$class[0]['id'])->result();
				$data['groupPaper'] = $groupPaper;
			}
			if($student[0]['temp_exam_form']=='Y'){
				$exam_papers = $this->Common_model->get_record('new_exam_form','paper_id,paper_code',$wherestudent);
				$data['exam_papers'] = $exam_papers;
			}
			$count = $this->Common_model->getCountByWhere('student_data',$wherestudent);
			if($class[0]['class_group']=='Y'){
				$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id where class_id='.$class[0]['id'])->result();
				$data['groupPaper'] = $groupPaper; 
			}
			$this->load->view('header',$titleData);
			$this->load->view('admin/enrollment/edit_student',$data);
			$this->load->view('footer');
		}
		
		public function unpaid_student_list(){
			if($this->session->has_userdata('username'))
			{
				$dt = array();
				$data = array();
				$dt['payment_status'] = 'N';
				$data['students'] = $this->Common_model->all_student_info_by_where($dt);
				$dt['title'] = "Unpaid student";
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
			
			if($this->session->has_userdata('username')){
				$titleData = array('title' => 'Paid Student');
				$this->load->view('header',$titleData);
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
		
		function getUnpaidStudentProgramFee(){
			$data = $row = array();
			$where = 'approved="Y" and program_fees!="Y"';
			// Fetch member's records
			$column_order = array(null,'student.student_id','course_name','class_name','name','f_h_name','p_mobile_no','dob',null);
			$column_search = array('student.student_id','course_name','class_name','name','p_mobile_no','f_h_name','dob');
			
			$DataTableArray = array(
				'column_order' => $column_order,
				'column_search' => $column_search,
				'where' => $where,
				'table' => 'student',
				'table2' => 'student_data',
				'joinOn' => 'student.student_id=student_data.student_id'
			);
			
			$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
			
			$i = $_POST['start'];
			foreach($tableData as $result){

				

				$data_amount = $this->Common_model->getStudentProgramFeeByClass($result->course_group_id,$result->class_id,$result->gender);

				if($result->installment_permission == 'Y'){
					$data_amount = $data_amount/2;
				}

				$btn = '<a href="#" class="btn btn-primary btn-sm font-weight-bold student" data-toggle="modal" data-target="#kt_datepicker_modal" data-id="'.$result->student_id.'" data-name="'.$result->name.'" data-amount="'.$data_amount.'">Receive</a>';
				
				$sts = $result->installment_permission;
				
				if($sts == 'Y')
				{
				$installment_btn = '<input type="button" name="update_stats" data-id='.$result->student_id.' class="btn btn-success status_checks" value="Yes">';
				}else{
				$installment_btn = '<input type="button" name="update_stats" data-id='.$result->student_id.' class="btn btn-danger status_checks" value="No">';
				}
				
				$i++;
				
				$data[] = array('DT_RowId' => 'student_'.$result->student_id,$i, $result->student_id, $result->course_name,$result->class_name, $result->name, $result->f_h_name,$result->p_mobile_no , $this->Common_model->viewDate($result->dob),$btn,$installment_btn);
			}
			
			$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Datatable_join_model->countAll('student',$where),
            "recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
            "data" => $data,
			);
			
			// Output to JSON format
			echo json_encode($output);
		}
		
		function unpaid_program_fees_list(){
			// Load the member list view
			$this->load->view('header',array('title' => 'Unpaid Program Fees'));
			$this->load->view('admin/enrollment/unpaid_program_fee');
			$this->load->view('footer');
		}
		
		public function update_unpaid_program_fees(){
			
			if ($this->input->method() == "post") 
			{
				$payment_date  = $this->input->post("payment_date");
				$payment_mode  = $this->input->post("payment_mode");
				$amount  = $this->input->post("amount");
				$student_id  = $this->input->post("student_id");
				$remark  = $this->input->post("remark");
				$file_name = '';
				if(isset($_FILES['images']) && $_FILES['images']['tmp_name']!=''){
				$filename = $student_id.'-'.date('Ymdhis');
				$this->upload->initialize($this->Common_model->set_upload_options('./assets/transactionImgaes/',$filename));
				if(!$this->upload->do_upload('images')){
					$error = $this->upload->display_errors();
					$msg = array('error'=>$error);
					echo json_encode($msg);
					exit();
				}
				$uploadData = $this->upload->data();
				$file_name = $uploadData['file_name'];
				}
				$updateData = array(
					'student_id' => $student_id,
					'payment_date' => $payment_date,
					'remark' => $remark,
					'payment_mode' => $payment_mode,
					'amount' => $amount,
					'filename' => $file_name
				);
				$response = $this->admin_model->unpaid_program_fees($updateData);
				if($response){
					echo json_encode(array("status" => 'true'));
				}
			}
		}
		
public function update_student_installment_permission_status()
	{
	
	if ($this->input->method() == "post") 
	{
            $id    = 0;
            $id    = $this->input->post("id");
			$status = $this->input->post("status");
			
            if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("student",array("student_id" => $id ),array("installment_permission" => $status ));
			
				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $data
				));
			}
	}
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
	$this->load->view('admin/student/other_course',$data);
}

}
