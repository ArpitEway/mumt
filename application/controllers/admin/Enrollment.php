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
				//START
				$master = $this->Common_model->getSingleRow('master');
				if(!empty($master->remove_class_from_center) ){
					
					$sql="SELECT GROUP_CONCAT(DISTINCT(`course_group_id`)) as id FROM `class_master` where `id`  IN ($master->remove_class_from_center) and admission_permission = 'Y'";
					$query = $this->db->query($sql);
        			$group_ids = $query->result_array();
					$grouparr=explode(',',$group_ids[0]['id']);
					$this->db->where_not_in('id',$grouparr );
					
				 }
				 //END
				$data['courses'] = $this->Common_model->get_record('course_group','id,course_name');
				
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
				$master = $this->Common_model->getSingleRow('master');
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
				//$dt['payment_status'] = "Y";
				$dt['document_uploaded'] = "Y";
				// $dt['university_mode'] = "REG";	
				$this->db->where('new_admission_permission', 'N');
				//START
				
				 if(!empty($master->remove_class_from_center) ){
					$remove_classes=explode(',',$master->remove_class_from_center);
					$this->db->where_not_in('student.class_id', $remove_classes );
				 }
				
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
				 if($exam_form_permission[0]->exam_form_permission=='Y' && $session[0]->exam_form_permission=="Y" && ( ( $student[0]->session=='Jan 2023' && $student[0]->class_name=="I Year") || ( $student[0]->session=='July 2023' &&  $student[0]->class_name=="I SEM") ) )
				 {
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
			if($mode!=""){ 	$where = array('session'=>$session_july,'university_mode'=>$mode ,'new_admission_permission'=>'N'); 	}
			$data['total_student'] = $this->Common_model->getCountByWhere('student',$where);
			
	       //---paid------
			$where = array('payment_status'=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['tot_paid'] = $this->Common_model->getCountByWhere('student',$where);

	       // --- not paid------
			$where = array('payment_status'=>'N','session'=>$session_july);
			if($mode!=""){ 	$where = array('payment_status'=>'N','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['tot_unpaid'] = $this->Common_model->getCountByWhere('student',$where);

	       //---paid and uploaded--------
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['uploaded'] = $this->Common_model->getCountByWhere('student',$where);

	        //---not uploaded--------
			$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july);
			if($mode!=""){ 	$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['not_uploaded'] = $this->Common_model->getCountByWhere('student',$where);

	        //---paid/uploaded/ non approved---
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['non_approved'] = $this->Common_model->getCountByWhere('student',$where);

	        // paid + uploaded but approved = '' not verified----
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['not_verified'] = $this->Common_model->getCountByWhere('student',$where);

	         // paid + uploaded + approved = Y  verified----
			$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['approved'] = $this->Common_model->getCountByWhere('student',$where);

			// enrollement genrated
			$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['en_generated'] = $this->Common_model->getCountByWhere('student',$where);

			// not enrollement genrated
			$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('enrollment_no '=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['not_en_generated'] = $this->Common_model->getCountByWhere('student',$where);

			// enrolled
			$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
			$data['tot_enrolled'] = $this->Common_model->getCountByWhere('student',$where);

			// not enrolled
			$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'new_admission_permission'=>'N');
			if($mode!=""){ 	$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
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
					$where = array('payment_status'=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Paid)');
				}
				if($param =='not_paid'){
					// --- not paid------
					$where = array('payment_status'=>'N','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('payment_status'=>'N','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Unpaid)');
				}

				if($param =='uploaded')
				{
					//---paid and uploaded--------
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Documents Uploaded)');
				}
				if($param =='not_uploaded')
				{
//---not uploaded--------
					$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Documents Not Uploaded)');
				}
				if($param =='approved')
				{

					// paid + uploaded + approved = Y  verified----
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Approved)');
				}
				if($param =='not_verified')
				{
					 // paid + uploaded but approved = '' not verified----
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Not Verified)');
				}
				if($param =='non_approved')
				{
					  //---paid/uploaded/ non approved---
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Non-Approved)');
				}
				if($param =='generated')
				{
					// enrollement genrated
					$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Generated)');
				}
				if($param =='not_generated')
				{
					// not enrollement genrated
					$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('enrollment_no '=>'-','approved='=>'Y','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Not Generated)');
				}
				if($param =='enrolled')
				{
                  // enrolled
					$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Enrolled)');
				}
				if($param =='not_enrolled')
				{
					// not enrolled
					$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
					$msg = array('title' => 'Center Wise Student List(Not Enrolled)');
				}

				if($param == 'all')
				{

					$where = array('session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
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
				$where = array('payment_status'=>'Y','session'=>$session_july ,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('payment_status'=>'Y','session'=>$session_july ,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Paid)');
			}
			if($params_value =='not_paid'){
					// --- not paid------
				$where = array('payment_status'=>'N','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('payment_status'=>'N','session'=>$session_july ,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Unpaid)');
			}

			if($params_value =='uploaded')
			{
					//---paid and uploaded--------
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Documents Uploaded)');
			}
			if($params_value =='not_uploaded')
			{
//---not uploaded--------
				$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Documents Not Uploaded)');
			}
			if($params_value =='approved')
			{

					// paid + uploaded + approved = Y  verified----
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Approved)');
			}
			if($params_value =='not_verified')
			{
					 // paid + uploaded but approved = '' not verified----
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Not Verified)');
			}
			if($params_value =='non_approved')
			{
					  //---paid/uploaded/ non approved---
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Non-Approved)');
			}
			if($params_value =='generated')
			{
					// enrollement genrated
				$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Generated)');
			}
			if($params_value =='not_generated')
			{
					// not enrollement genrated
				$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('enrollment_no '=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Not Generated)');
			}
			if($params_value =='enrolled')
			{
                  // enrolled
				$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Enrolled)');
			}
			if($params_value =='not_enrolled')
			{
					// not enrolled
				$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id,'new_admission_permission'=>'N');
				if($mode!=""){ 	$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
				$msg = array('title' => 'Center Wise Student List(Not Enrolled)');
			}
			if($params_value == 'all')
				{

					$where = array('session'=>$session_july,'new_admission_permission'=>'N');
					if($mode!=""){ 	$where = array('session'=>$session_july,'university_mode'=>$mode,'new_admission_permission'=>'N'); 	}
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
		$data['approved_by'] = "";
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
		$data ['approved_by'] = $this->session->admin_id;

		$student = $this->Common_model->getRecordById('student','student_id',$param);
		$wherearray=array('student_id'=>$param, 'fees_head' => 'Admission Fees','course_group_id' => $student->course_group_id,'class_id' => $student->class_id);
		$admissionEntry = $this->Common_model->get_record('online_payment_transaction','*',$wherearray);
		
		if(($student->admission_by=='web' )  && (!@($admissionEntry))){
			$amount = $this->Common_model->getRecordByWhere('course',array('course_group_id'=> $student->course_group_id));
	
            $mode = 'regular';
            $late_fees=0;
            $remark="From Web";
            if($mode=='regular'){
                $amount = $amount[0]->admission_fees;
                $admission_type = 'regular';
            }


            $OnlinePayTxnData = array('student_id' => $param,'center_id' => $student->center_id ,'fees_head' => 'Admission Fees','amount' => $amount,'payment_status'=>'pending','course_group_id' =>$student->course_group_id,'class_id' => $student->class_id,'student_name' => $student->name,'admission_type'=>$admission_type,'remark'=>$remark);
            $OnlinePayTxn = $this->Common_model->insertAll('online_payment_transaction',$OnlinePayTxnData);
		}
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
		$update_aug_22 =$this->Common_model->updateRecordByConditions('student_result_aug_22',$where,$data);
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
		$this->db->select('DISTINCT YEAR(`tc_date`) as year');
		$this->db->from('student');
		$this->db->where('tc_date IS NOT NULL', NULL, FALSE);	
		$this->db->order_by("year", "desc");
		$query = $this->db->get();
		$data['years']= $query->result_array();
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

	public function search_student_for_mode(){
		$this->load->view('header',array('title' => 'Search Students'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);	
		$this->load->view('admin/search_student_for_mode',$data );
		$this->load->view('footer');
	}//fun

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

	public function search_student_session_change(){
		$this->load->view('header',array('title' => 'Search Students to Update Session'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);	
		$this->load->view('admin/enrollment/search_student_session_change',$data );
		$this->load->view('footer');
	}

	public function get_student_session_update(){
		
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);	
		$student_id =$_POST['student_id'];
		$data['eligibility_list'] = $this->Common_model->get_record('course_group','DISTINCT (eligibility)');
		$data['course_group_list'] = $this->Common_model->get_record('course','*');
		$data['student']= $this->Common_model->getRecordByWhere('student',array("student_id"=>$student_id));
		$data['student_data']= $this->Common_model->getRecordByWhere('student_data',array("student_id"=>$student_id));
		$html_comment = $this->load->view('admin/enrollment/get_student_session_update' ,$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $html_comment
		));
	}
	public function update_student_session(){
		$student_id = $_POST['student_id'];
		$course_group_id = $_POST['course_group_id_admission'];
		
		///////////////////////////

		$mode = $this->Common_model->getRecordByWhere('student',array("student_id"=>$_POST['student_id'] ));	
		// code for delete papers 
		if($_POST['old_course_group_id']!=$_POST['course_group_id']){
			$delete  =  $this->Common_model->deleteByWhere('new_exam_form' ,array('student_id'=>$_POST['student_id']));
			$class_master =   $this->Common_model->getRecordByWhere('class_master' ,array("id"=>$_POST['class_id']));
			$cbcs = ($class_master[0]->cbcs == 'Y')?'Y':'N';
			if($class_master[0]->class_group=='N'){
				if($mode[0]->university_mode=='PVT') 
					$paperWhere=array('class_id'=>$_POST['class_id'],'type'=>'theory','cbcs_paper'=>$cbcs);
				else			
					$paperWhere=array('class_id'=>$_POST['class_id'],'cbcs_paper'=>$cbcs);
				$papers = $this->Common_model->getRecordByWhere('paper_master',$paperWhere);
				
					if(count($papers)>0){
						foreach($papers as $paper){
							$insert_paper = array(
								'student_id'=>$_POST['student_id'],
								'course_group_id' =>$_POST['course_group_id'],
								'class_id' =>$_POST['class_id'],
								'paper_id' =>$paper->id,
								'paper_code' =>$paper->paper_code,
								'paper_type'=>$paper->type,
								'book_code'=>$paper->book_code,
								'paper_order'=>$paper->paper_no,
								'sub_group_id'=>$paper->sub_group_id
							);
							$insert = $this->Common_model->insertAll('new_exam_form',$insert_paper);
						}
						$data['temp_exam_form'] = 'Y';
					}else{
						$data['temp_exam_form'] = 'N';
					}
			}else{
				$data['temp_exam_form'] = 'N';
			}
		}

		$course_group_id = html_escape($this->input->post('course_group_id'));
		$class_id = html_escape($this->input->post('class_id'));
		$session = html_escape($this->input->post('session'));

		$data['session'] = $session;
		$data['course_group_id'] = $course_group_id;
		$data['course_name'] = $this->Common_model->getCourseNameByCourseId($course_group_id);
		$data['class_name'] = $this->Common_model->getClassNameByClassId($class_id);
		$data['class_id'] = $class_id;
		

		$studentData['eligibility'] = html_escape($this->input->post('eligibility'));
	
		// transaction start from here 
		// https://codeigniter.com/userguide3/database/transactions.html
		
		$this->db->trans_start();
		$student_id = html_escape($this->input->post('student_id'));
		$course_permission= $this->Common_model->getRecordByWhere('course',array("session"=>$session,'course_group_id'=>$course_group_id ));
		$session_permission= $this->Common_model->getRecordByWhere('session',array("session"=>$session));	
		
		if ($session!=$mode[0]->session) {
			if(($mode[0]->university_mode=='REG' && $course_permission[0]->admission_permission_regular=='Y') ||  ($mode[0]->university_mode=='PVT' &&  $course_permission[0]->admission_permission_private=='Y'))
			{
				$path = 'assets/student_image/'.$session.'/'.$mode[0]->photo;
				$prev_path = 'assets/student_image/'.$mode[0]->session.'/'.$mode[0]->photo;
				$upload = rename($prev_path,$path);
			}
			else {
				return false;
			}
		}
	
		$studentData['student_id'] = $student_id;
		$this->db->where('student_id', $student_id);
		$this->db->update('student', $data);
		
		$this->db->where('student_id', $student_id);
		$this->db->update('student_data', $studentData);
	
		
		$OnlinePayTxnData = array('course_group_id' => $course_group_id,'class_id' => $class_id);

		$this->db->where('student_id', $student_id);
		$this->db->update('online_payment_transaction', $OnlinePayTxnData);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{ 
			$this->db->trans_complete();
		}

		
		///////////////////////////
		
		
		$result = array("status" => true, "student_id"=> $student_id);
		echo json_encode($result);
	}

	public function student_old_result(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$this->load->view('header',array('title' =>'Search Student Old Result'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('admin/msprint/student_result',$data);
			$this->load->view('footer');
		}

	}
	public function get_student_result(){
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
				$result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id' =>$student->student_id));
				//,"exam_year"=>"Feb 2022"
				$data = array(
					'result' => $result,
					'student' => $student,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				$dt =  $this->load->view('admin/msprint/view_student_result',$data,true);
				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}
		}
	}
	
	public function marksheet($exam_data_id="")
	{
		$this->load->library('numbertowordconvertsconver');
		$exam_data_id =  $this->Common_model->encrypt_decrypt($exam_data_id,'decrypt');
		$this->db->select('*');
		$this->db->from('old_result_data');
		$this->db->where('old_result_data.exam_data_id',$exam_data_id);
		$this->db->order_by('p_order','ASC');
		$new_exam_form = $this->db->get()->result();
		$course_id = $new_exam_form[0]->course_group_id;
		$data['old_result_data']  = $new_exam_form;
		$data['class_id']  = $new_exam_form[0]->class_id;
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
		
		// $title = array('title' => 'Result');
		$data['exam_data'] = $this->Common_model->getRecordById('old_exam_data','id',$exam_data_id);
		
		// $course_id !=36 && $course_id !=37
		$data['class'] = $this->Common_model->getRecordByID('class_master','id', $data['exam_data']->class_id);
		
		$this->load->view('admin/generate_tr/header2',$title);
		$this->load->view('admin/old_marksheet_top',$data);
		
		if((in_array($new_exam_form[0]->class_id , $class_ids)) && $data['exam_data']->university_mode=='REG' && $data['exam_data']->marks_pattern=='GRADE'){
			$this->load->model('Gradesheet_old_model');
			$this->load->view('admin/grade_marksheet',$data);
		}else if($data['class']->cbcs=='Y' && $data['exam_data']->university_mode=='REG' && $data['exam_data']->marks_pattern=='GRADE')
		{
				$this->load->model('Gradesheet_model_pg');
				$this->load->view('admin/grade_marksheet_pg',$data);
		}else if($data['exam_data']->university_mode !="PVT"  && $data['class']->internal !='N'){
			
			$this->load->view('admin/marksheet_student',$data);
		}else{
			
			$this->load->view('admin/marksheet_student_pvt',$data);
		}
		
		$this->load->view('admin/generate_tr/footer2');
	}
}
