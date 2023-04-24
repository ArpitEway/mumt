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
				$this->db->order_by('id', 'ASC');
				$data['sessions'] = $this->db->get_where('session', array('enrollment_permission'=>'Y'))->result_array();
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
				$course_group_id  = $this->input->post("course_group_id");
				$class_id  		  = $this->input->post("class_id");
				$approved 		  = $this->input->post("approved");
				$new_exam_form    = $this->input->post("new_exam_form");
			
				$payment 		  = $this->input->post("payment");
				$enrolled 		  = $this->input->post("enrolled");
				$document_upload  = $this->input->post("document_upload");
				$filter  		  = $this->input->post("filter");
		     	$session 		  = $this->input->post("session");
				$mode 		  	  = $this->input->post("mode");
				$center_id	  	  = $this->input->post("center_id");
		      	$university_mode	  	  = $this->input->post("university_mode");
				
				if($mode != "all"){	 
						
					$dt['mode'] = $mode;
				}
				if($university_mode!="all"){
					$dt['student.university_mode'] = $university_mode ;
				}
				if($session != "All" && $session != ""  ) {	 
					
					$dt['session'] = $session;
				}
				else  {
					$dt['name!='] = '';
				}
	
				
				if($class_id !=  "All" && $class_id !=  "" ){	 
	
					$dt['class_id'] = $class_id;
					}
				
				if($approved != "all"){
	
					$dt['approved'] = $approved;
				}
			   
				
				if($new_exam_form != "all"){
				   
					$dt['new_exam_form'] = $new_exam_form;
				}
				if($course_group_id != "all"){
				   
					$dt['course_group_id'] = $course_group_id;
				}
				
				if($center_id != "all"){
				   
					$dt['center_id'] = $center_id;
				}
			
	
	
				if($payment != "all"){
	
					$dt['payment_status'] = $payment;
				}
				if($enrolled != "all"){
	
					$dt['enrolled'] = $enrolled;
				}
				if($document_upload != "all"){
	
					$dt['document_uploaded'] = $document_upload;
				//print_r($document_upload);
				}
				
				//print($dt);
				//die;
				if($filter == "list"){
	
					$data['students'] = $this->Common_model->student_data_consolidate($dt);
					
	
				}
				if($filter == "count"){				
					$data['course_count'] = $this->Common_model->student_data_consolidate($dt,$_POST['count_filter']);
				}
				
				// $this->Common_model->last_query();
						
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

				$center 	    = 	$this->input->post("center");
				$course_group_id  = $this->input->post("course_group_id");
				$approved = $this->input->post("approved");
				$session = $this->input->post("session");
				$university_mode = $this->input->post("university_mode");

                if($center != "all"){	 
					
					$dt['center_id'] = $center;
				}

				if($university_mode != "all"){	
					$dt['university_mode'] = $university_mode;
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
				// $dt['university_mode'] = "REG";	
				$this->db->where('new_admission_permission', 'N');
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

			$document_id = $this->Common_model->encrypt_decrypt($param,'decrypt');
			$data['doc_details'] = $this->Common_model->getRecordById('admission_document','id',$document_id);
			$data['student_id'] = $data['doc_details']->student_id;
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
			$where = array('adhar_no'=>$adhar_no,'course_complete'=>'N'
				,'new_admission_permission'=>'N'  
			);
			$count = $this->Common_model->getCountByWhere('student',$where);
			if($count>0){
				echo "Duplicate Adhar Card Number";
			}
		}

	public function center_request(){
		if($this->session->has_userdata('adminData'))
		{
			$where = array('status' => 'Pending');
			$centers = $this->Common_model->get_record_group_by_where('request','center_id',$where);
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
			$this->load->view('header',array('title'=>"Edit Request"));
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
			//$course_group_id = 0;
			$data = array();
			$dt   = array();
			$center_id  = $this->input->post("center_id");
			$wherecenter = array('status' => 'Pending',
				'center_id'=>$center_id,
			);
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$center_detail = $this->Common_model->get_record('request','*',$wherecenter);
			$data = array('center_details' => $center_detail ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),'centerData' => $centerData,);
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


	public function generate_enrollment(){
		$where = array('enrollment_permission' => 'Y');
	$session = $this->db->get_where('session',$where)->result_array();
     
	$data['session'] = $session;
	$data['name_csrf'] = $this->security->get_csrf_token_name();
	$data['hash_csrf'] = $this->security->get_csrf_hash();
	
	 $this->load->view('header',array('title' => 'Generate Enrollment'));
	 $this->load->view('admin/enrollment/generate_enrollment',$data);
	 $this->load->view('footer');
	}

	

	public function  center_wise_enrollment_permission(){
  
		$data = array();
		$data['title'] = "Center Wise Enrollment Permission";
		$this->load->view('header',$data);
		$where = array("approved" => "Y" , "enrollment_no!="=> '-' , 'enrolled'=> 'N');
		$data['centers'] = $this->Common_model->get_record_group_by_where('student','center_id, center_name, center_code ',$where);

		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('admin/enrollment/center_wise_enrollment_permission',$data);
		$this->load->view('footer');
	}


	public function enrollment_permission($centerCode = ""){
		$centerCode = $this->Common_model->encrypt_decrypt($centerCode,'decrypt');
		if(!isset($_POST['action'])){
			
			$where = array(
				'center_code' => $centerCode,
				'enrolled' => 'N',
				'approved' => 'Y',
				"enrollment_no!="=> '-',
			);

			$student = $this->Common_model->getRecordByWhere('student',$where);

			$data = array(
				'students' => $student,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);

			$this->load->view('header',array('title' => 'Set Enrollment Permission'));
			$this->load->view('admin/enrollment/set_enrollment_permission',$data);
			$this->load->view('footer');
		}else if($_POST['action']=='setPermission'){
			$enrollment_nos = $this->input->post('enrollment_no');
			
			foreach($enrollment_nos as $en){
				$student = $this->Common_model->getRecordByWhere('student',array('enrollment_no'=>$en));

				$exam_form_permission = $this->Common_model->getRecordByWhere('class_master',array('id'=>$student[0]->class_id));

				$session = $this->Common_model->getRecordByWhere('session',array('session'=>$student[0]->session));

				$data = array('enrolled' => 'Y');
				/*****  exam form permission *****/
				 if($exam_form_permission[0]->exam_form_permission=='Y' && $session[0]->exam_form_permission && ( ( $student[0]->session=='Jan 2022' && $student[0]->class_name=="I Year") || ( $student[0]->session=='July 2022' &&  $student[0]->class_name=="I SEM") ) ){
				 	$data['new_exam_form'] ='N';
				 } 
				$where = 'student_id="'.$student[0]->student_id.'" ';
				$this->Common_model->updateRecordByConditions('student',$where,$data);
				//Move Documents as per session
				$where = array('student_id' => $student[0]->student_id);
				$admissionDoc = $this->Common_model->get_record('admission_document','*',$where);
				foreach($admissionDoc as $row){
					$source=FCPATH."/assets/documents/".$row['document_image'];
				
					$destination=FCPATH."/assets/enrolled_documents/".$student[0]->session."/".$row['document_image'];
				
					$dirname = FCPATH."/assets/enrolled_documents/".$student[0]->session;

					if(!is_dir($dirname)){
						mkdir( $dirname, 0777);
						
					
					} 

					if( rename( $source , $destination )){
					
						$data  = array('move'=>'Y' );
						$where = array('student_id'=>$row['student_id'],'id'=> $row['id']);
						
						$update =$this->Common_model->updateRecordByConditions('admission_document',$where,$data);
					} 
				}	
			}
		
			$this->session->set_flashdata('ajax_flash_message','permission updated');
			$centerCode = $this->Common_model->encrypt_decrypt($centerCode,'encrypt');
			redirect(base_url().'admin/enrollment/enrollment_permission/'.$centerCode);
		}
	}

	public function enrollment_status($session=0,$mode="")
	{
			$data['sessions'] = $this->db->get_where('session', array('enrollment_permission' => 'Y'))->result_array();
			$data['mode']=$mode;
			if($session==0)
			{
				$LastSessionElement = $data['sessions'];
				$session=$LastSessionElement[0]['id'];
				
			}
			
			$data['sessionsSelect'] =$session;
			$record=$this->db->get_where('session', array("id"=>$session,'enrollment_permission' => 'Y'))->result_array();	
			//array('session'=>$record[0]['session'])
			//$session_july='July 2021';		// All Class
			$session_july=$record[0]['session'];
			$where = array('session'=>$session_july,);
			if($mode!=""){ 	$where = array('session'=>$session_july,'university_mode'=>$mode); 	}
			$data['total_student'] = $this->Common_model->getCountByWhere('student',$where);
			
	       //---paid------
			$where = array('payment_status'=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['tot_paid'] = $this->Common_model->getCountByWhere('student',$where);

	       // --- not paid------
			$where = array('payment_status'=>'N','session'=>$session_july);
			if($mode!=""){ 	$where = array('payment_status'=>'N','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['tot_unpaid'] = $this->Common_model->getCountByWhere('student',$where);

	       //---paid and uploaded--------
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['uploaded'] = $this->Common_model->getCountByWhere('student',$where);

	        //---not uploaded--------
			$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['not_uploaded'] = $this->Common_model->getCountByWhere('student',$where);

	        //---paid/uploaded/ non approved---
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july);
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['non_approved'] = $this->Common_model->getCountByWhere('student',$where);

	        // paid + uploaded but approved = '' not verified----
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july);
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['not_verified'] = $this->Common_model->getCountByWhere('student',$where);

	         // paid + uploaded + approved = Y  verified----
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['approved'] = $this->Common_model->getCountByWhere('student',$where);

			// enrollement genrated
			$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['en_generated'] = $this->Common_model->getCountByWhere('student',$where);

			// not enrollement genrated
			$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('enrollment_no '=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['not_en_generated'] = $this->Common_model->getCountByWhere('student',$where);

			// enrolled
			$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july);
			if($mode!=""){ 	$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['tot_enrolled'] = $this->Common_model->getCountByWhere('student',$where);

			// not enrolled
			$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july);
			if($mode!=""){ 	$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode); 	}
			$data['tot_not_enrolled'] = $this->Common_model->getCountByWhere('student',$where);

			

			$this->load->view('header');
			$this->load->view('admin/enrollment/enrollment_status_count',$data);
			$this->load->view('footer');

		}



		public function center_wise_list($param)
		{
			
			if($param!='')
			{
			
				//$session_july='July 2021';
			
				 $session_id = $this->uri->segment(5);
				 $mode = $this->uri->segment(6);
				 $data['mode']=$mode;
				 $record=$this->db->get_where('session', array("id"=>$session_id))->result_array();
				 $session_july=$record[0]['session'];
				 $data['sessionsSelect'] =$session_id;
				if($param =='paid')
				{
                   //---paid------
					$where = array('payment_status'=>'Y','session'=>$session_july);
					if($mode!=""){ 	$where = array('payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Paid)');
				}
				if($param =='not_paid'){
					// --- not paid------
					$where = array('payment_status'=>'N','session'=>$session_july);
					if($mode!=""){ 	$where = array('payment_status'=>'N','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Unpaid)');
				}

				if($param =='uploaded')
				{
					//---paid and uploaded--------
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july);
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Documents Uploaded)');
				}
				if($param =='not_uploaded')
				{
//---not uploaded--------
					$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july);
					if($mode!=""){ 	$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Documents Not Uploaded)');
				}
				if($param =='approved')
				{

					// paid + uploaded + approved = Y  verified----
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july);
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Approved)');
				}
				if($param =='not_verified')
				{
					 // paid + uploaded but approved = '' not verified----
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july);
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Not Verified)');
				}
				if($param =='non_approved')
				{
					  //---paid/uploaded/ non approved---
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july);
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Non-Approved)');
				}
				if($param =='generated')
				{
					// enrollement genrated
					$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july);
					if($mode!=""){ 	$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Generated)');
				}
				if($param =='not_generated')
				{
					// not enrollement genrated
					$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july);
					if($mode!=""){ 	$where = array('enrollment_no '=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Not Generated)');
				}
				if($param =='enrolled')
				{
                  // enrolled
					$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july);
					if($mode!=""){ 	$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Enrolled)');
				}
				if($param =='not_enrolled')
				{
					// not enrolled
					$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july);
					if($mode!=""){ 	$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List(Not Enrolled)');
				}

				if($param == 'all')
				{

					$where = array('session'=>$session_july);
					if($mode!=""){ 	$where = array('session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List');
				}

                // All Class
				$this->db->select('COUNT(student_id) as student_count,center_id,center_code,
					center_name,center_id');
				$this->db->group_by('center_id');
				
				$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
				//echo $this->db->last_query();
				$data['params'] = $param ;
				$this->load->view('header',$msg);
				$this->load->view('admin/enrollment/center_wise_list',$data); 
				$this->load->view('footer');
			}else{
				return redirect(base_url().$this->session->account_type.'/enrollment_status');
			}
		}


		public function students_count_list()
		{
			//$session_july='July 2021';
			$center_id = $this->uri->segment(4);
			$params_value = $this->uri->segment(5);
			$session_id = $this->uri->segment(6);
			$mode = $this->uri->segment(7);
			$data['mode']=$mode;
			$record=$this->db->get_where('session', array("id"=>$session_id))->result_array();
			$session_july=$record[0]['session'];
			$data['sessionsSelect'] =$session_id;

			if($params_value =='paid')
			{
                   //---paid------
				$where = array('payment_status'=>'Y','session'=>$session_july ,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('payment_status'=>'Y','session'=>$session_july ,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Paid)');
			}
			if($params_value =='not_paid'){
					// --- not paid------
				$where = array('payment_status'=>'N','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('payment_status'=>'N','session'=>$session_july ,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Unpaid)');
			}

			if($params_value =='uploaded')
			{
					//---paid and uploaded--------
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Documents Uploaded)');
			}
			if($params_value =='not_uploaded')
			{
//---not uploaded--------
				$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Documents Not Uploaded)');
			}
			if($params_value =='approved')
			{

					// paid + uploaded + approved = Y  verified----
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Approved)');
			}
			if($params_value =='not_verified')
			{
					 // paid + uploaded but approved = '' not verified----
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Not Verified)');
			}
			if($params_value =='non_approved')
			{
					  //---paid/uploaded/ non approved---
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Non-Approved)');
			}
			if($params_value =='generated')
			{
					// enrollement genrated
				$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Generated)');
			}
			if($params_value =='not_generated')
			{
					// not enrollement genrated
				$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('enrollment_no '=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Not Generated)');
			}
			if($params_value =='enrolled')
			{
                  // enrolled
				$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Enrolled)');
			}
			if($params_value =='not_enrolled')
			{
					// not enrolled
				$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id);
				if($mode!=""){ 	$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode); 	}
				$msg = array('title' => 'Center Wise Student List(Not Enrolled)');
			}
			if($params_value == 'all')
				{

					$where = array('session'=>$session_july);
					if($mode!=""){ 	$where = array('session'=>$session_july,'university_mode'=>$mode); 	}
					$msg = array('title' => 'Center Wise Student List');
				}
			
			if($center_id!='')
			{

           	      $this->db->where('center_id',$center_id);
				$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
		
				$this->load->view('header',array('title' => 'Center Wise Student List'));
				$this->load->view('admin/enrollment/students_count_details',$data); 
				$this->load->view('footer');
			}else
			{
				redirect(base_url('admin/'.$this->session->account_type.'/enrollment_status'));
			}
		}


	public function view_complaint(){
		$where = array("status" => "P",'type'=>'Enrollment');
		$centers = $this->Common_model->get_record_group_by_where('center_complaint','center_id',$where);
		$data = array('name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'centers' =>$centers
		);

		$this->load->view('header');
		$this->load->view('admin/view_complaint_status',$data);
		$this->load->view('footer');
	}

	public function get_complaints_status(){
		if ($this->input->method() == "post"){
			$course_group_id = 0;
			$data = array();
			$dt   = array();

			$center_id  = $this->input->post("center_id");
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$wherecenter = 'center_id='.$center_id.' and status="P" and type="Enrollment"';
			$complaints = $this->Common_model->get_record('center_complaint','*',$wherecenter);
			
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
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");

			if ($this->input->post("id")){

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

	public function update_complaint_remark(){
		if ($this->input->method() == "post"){
			$id    	= $this->input->post("id");
			$remark = $this->input->post("remark");
			$status = ($remark=='Set') ? 'P' : "D";
			$remark = ($remark=='Set') ? '' : 'Invalid';

			if ($this->input->post("id")){
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
		$where = array('id' => $id);
		$studentData = array('type' => $redy);

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

	public function make_Non_Verified(){
		$student_id = html_escape($this->input->post('student_id'));
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data['provisional_remark'] = "N";
		$data['approved'] = "";
		$this->db->where('student_id', $student_id);
		$this->db->update('student', $data);	
		$this->session->set_flashdata('ajax_flash_message','Non Verfied');
		echo json_encode(array(
		"status" => 'true',
		));
	}

	public function provisional_remark_update($param){
		$remark = html_escape($this->input->post('remark'));
		$data['provisional_remark'] = implode(",",$remark);
		$data['approved'] = 'Y';
		$this->db->where('student_id', $param);
		$this->db->update('student', $data);	
		$this->session->set_flashdata('ajax_flash_message','approved');
		echo json_encode(array(
		"status" => 'true',
		));
	}

	public function provisional_remark_list(){

		if($this->input->method() == "post") 
			{
				$session    = $this->input->post("session");
			}
			else{
				$session='All';
			}
		$this->load->view('header',array('title' => 'Provisional Students'));
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();	 
		$where = array('','N');
		$this->db->where_not_in('provisional_remark', $where);	
		if($session!="All"){
			$this->db->where('session',$session);
		}
		
		$this->db->order_by('session,center_id', 'ASC');
		$data['student_list'] = $this->db->get('student')->result();
		$this->db->where_not_in('provisional_remark', $where);
		$data['sessions'] = $this->Common_model->get_record('student','DISTINCT (session)');
		$data['sessionsSelect'] =$session;
		$this->load->view('admin/enrollment/provisional_remark_list',$data);
		$this->load->view('footer');
	}


	public function update_provisional_status()
	{ 
		$class_ids = $this->input->post('class_ids');	
		$student_id = $this->input->post('student_ids');
		$exam_form = $this->input->post('new_exam_form');
		$class_permission= $this->Common_model->getRecordByWhere('class_master',array('id'=>$class_ids));	
		$where = array('student_id'=>$student_id);
		/*if($class_permission[0]->result_permission=='Y' && $exam_form=='Y'){     
		$data = array('provisional_remark' =>'N','old_result_show' =>'Y');//old_result_show
	     	}
	     	else{
	     	$data = array('provisional_remark' =>'N');
	     	}*/
		$data = array('provisional_remark' =>'N');	 
		$update =  $this->Common_model->updateRecordByConditions('student',$where,$data);
		if($update){
			$result = array("status" => "true");
		}
		else{
			$result = array('error'=> "Not Remark Updated");
		}
		echo json_encode($result);
	}

	public function change_password(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Change Password');
			$this->load->view('header',$titleData);
            $admin_id = $this->session->admin_id;
			$admin_master_data = $this->Common_model->getRecordById('admin_master','id',$admin_id);
			$data = array('admin_data' => $admin_master_data,
                'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(), );
			$this->load->view('admin/enrollment/change_password',$data);
			$this->load->view('footer');
		}
	}

	public function change_password_sub($id)

	{ 
		$new_password 	  = $this->input->post('new_password');
		$confirm_password = $this->input->post('passconf');
		$new_password_change = md5($new_password);

		if($this->input->post('new_password') != "")
		{
			if($new_password == $confirm_password)
			{
				$data_update = array("password" =>  $new_password_change);
				$this->db->where('id', $id);
				$this->db->update('admin_master', $data_update);
				echo json_encode(array(
					"success" => 'Password Updated Successfully',
				));
			}
			else{
				echo json_encode(array(
					"error" => 'Password does not match',
				));
			}
		}else{
			echo json_encode(array(
				"error" => 'Please enter New Password',
			));
		}
	}



public function getStudentData()
{
	if(!$this->session->has_userdata('adminData')){
		redirect(base_url());
		exit;
	}

	$text_val =$this->input->post('text_val');
	$radio_val = $this->input->post('radio_val');


	if($text_val !='')
	{
		if($text_val !='' && $radio_val == 'enrollment_no')
		{
			$where = array('enrollment_no'=>$text_val);

		}else if($text_val !='' && $radio_val == 'student_id')
		{
			$where = array('student.student_id'=>$text_val);

		}else if($text_val !='' && $radio_val == 'roll_no')
		{
			$where = array('roll_no'=>$text_val);

		}else if($text_val !='' && $radio_val == 'student_name')
		{
			$where = array();
			$this->db->like('name', $text_val);

		}else if($text_val !='' && $radio_val == 'adhar_no')
		{
			$where =  array('adhar_no' => $text_val);
		}

		$data['students'] = $this->Common_model->student_data($where);


		$dt =  $this->load->view('admin/student/getStudentConsolidate',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}
	}//fun

	public function genrate_tc($student_id){
		
		$tc_date=$this->input->post("tc_date");
		$tc_remark=$this->input->post("tc_remark");
		if((!empty($tc_date)) && (!empty($tc_remark))){
			$tcData = array('tc_date' => $tc_date,'delete_remark' => $tc_remark,'new_admission_permission'=>'Y');
			$where = array('student_id'=>$student_id);
			$this->Common_model->updateRecordByConditions('student',$where,$tcData);
			$this->session->set_flashdata('ajax_flash_message','TC Generated !');
			
			echo json_encode(array("status" => 'true',		));
		}
		 else{
			echo json_encode(array("status" => 'false',		));
		 }		
	}


	public function tc_student_list(){
		$segment = $this->uri->segment(2);
		$this->load->view('header',array('title' => 'TC Student List'));	
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'segment' => $segment
		);
		$this->load->view('admin/tc_student_list',$data);
		$this->load->view('footer');
	}


	
	public function getTCStudentData()
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}

	 	 $text_val =$this->input->post('text_val'); 
		if($text_val !='')
		{
			$where=array();
			
			if($text_val=='All'){
				
				$this->db->where('tc_date IS NOT NULL', NULL, FALSE);	
			}
			else{
				$this->db->like('tc_date',$text_val);
			}
			
				
			$data['students'] = $this->Common_model->student_data($where);


			$dt =  $this->load->view('admin/student/getTCStudents.php',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		}
	}//fun	
}
