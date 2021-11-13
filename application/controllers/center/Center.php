<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Center/center_model');
		$this->load->model('Datatable_join_model');
	}

	public function index(){
		if($this->session->has_userdata('centerdata')){
			redirect(base_url('center/instruction'));
		}else{			
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('Centers/login',$csrf);
		}
	}

	public function dashboard(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/'));
		}else{
			$titleData = array('title' => 'Center Dashboard'); 
			$this->load->view('Centers/header',$titleData);
			$id =  $this->session->center_id;
			$center = $this->Common_model->getRecordById('center','id',$id);
			$data = array('center' => $center);
			$this->getNotification();
			$this->load->view('Centers/dashboard',$data);
			$this->load->view('Centers/footer');
		}
	}

	public function instruction(){
		
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/'));
		}else{
			$titleData = array('title' => 'Course Fees Structure'); 
			$this->load->view('Centers/header',$titleData);
			$id =  $this->session->center_id;
			$center = $this->Common_model->getRecordById('center','id',$id);
			
			$course_group = $this->db->get_where('course_group', array())->result_array();
			$data = array('course_group' => $course_group);
			$this->getNotification();
			$this->load->view('Centers/instruction',$data);
			$this->load->view('Centers/footer');
		}
	}


	public function login(){
		if($this->session->has_userdata('center_code')){
			redirect(base_url('center'));
			exit;
		}
		$csrf = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('Centers/login',$csrf);
	}

	public function loginSub(){
		if($this->session->has_userdata('center_code')){
			redirect(base_url('center'));
			exit;
		}
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('centercode', 'center Code', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('error','center Code And Password Are Required');
				$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
			);
		$this->load->view('Centers/login',$csrf);
		}
		else
		{
			$centercode = $_POST['centercode'];
			$password = $_POST['password'];
			$check_user = $this->center_model->checkUser($centercode,$password);
			if($check_user){	
				$data = array('loged_in' => true,
					'centerdata' => $check_user->center_code,
					'center_id' => $check_user->id,
					'account_type' => 'center'
				);
				$this->session->set_userdata($data);
				redirect(base_url('center'));
			}else{

				$this->session->set_flashdata('error','center Code And Password Are Incorrect');
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('Centers/login',$csrf);
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('center/login'));
	}

	public function admission_form(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/'));
			exit;
		}
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
			'hash_csrf' => $this->security->get_csrf_hash()
		);

		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/admission_form',$data);
		$this->load->view('Centers/footer');
	}

	public function isDuplicateEnrollment(){
		$enrollment_no = $this->input->post('enrollment_no');	
		$count = $this->Common_model->getCountByWhere('center','enrollment_no='.$enrollment_no);
		echo $count;
		die;
	}

	public function getDistrictByState(){
		$state = $this->input->post('state');
		$nameAttr = $this->input->post('nameAttr');
		$districts = $this->Common_model->get_record('distt','*',"state_id='".$state."'");
		$data = array(
			'district_list' => $districts,
			'nameAttr' => $nameAttr
		);

		echo $this->load->view('template/getdistrict',$data,true);
	}

	public function show_form($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/login'));
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data = array();
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->load->view('Centers/header',array('title' => 'Admission Form'));
		$this->load->view('template/form',$data);
		$this->load->view('Centers/footer');
	}



	public function getAdmissionClassByCourse(){
		$course = $this->input->post('course');
		$class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$course."' and  admission_permission='Y'");
		$data = array(
			'class_list' => $class_list,
		);

		echo $this->load->view('template/getclass',$data,true);
	}

	public function getClassByCourse(){
		$course = $this->input->post('course');
		$class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$course."'  and admission_permission='Y'");
		$data = array(
			'class_list' => $class_list,
		);
		echo $this->load->view('template/getclass',$data,true);
	}

	private function getNotification(){
		$center_id = $this->session->center_id;
		if($center_id!=''){
			$center = $this->Common_model->getRecordById('center','id',$center_id);
			$centerdata = array('center' => $center);
			$this->load->view('Centers/notification',$centerdata);
		}
	}

	public function all_student()
	{
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'session_list' => $this->Common_model->get_record('session','*'),
		);
		$titleData = array('title' => 'Students Report', );
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/student_details',$csrf);
		$this->load->view('Centers/footer');
	}

	public function getStudentList(){
		$data = $row = array();

		$where = array(
			'center_id' => $this->session->center_id,
		);

		if($_POST['session']!='All'){
			$where['session'] = $this->input->post('session');
		}
		if($_POST['course_group_id']!='All' and $_POST['course_group_id']!=''){
			$where['course_group_id'] = $this->input->post('course_group_id');
		}
		if($_POST['class_id']!='All' and $_POST['class_id']!=''){
			$where['class_id'] = $this->input->post('class_id');
		}
		if($_POST['approved']!='All'){
			$where['approved'] = $this->input->post('approved');
		}
		if($_POST['enrolled']!='All'){
			$where['enrolled'] = $this->input->post('enrolled');
		}
		if($_POST['document']!='All'){
			$where['document_uploaded'] = $this->input->post('document');
		}

		// Fetch member's records
		
		$column_order = array('student.student_id','enrollment_no','name','f_h_name','course_name','class_name',null);
		$column_search = array('student.student_id','enrollment_no','course_name','class_name','name','f_h_name');

		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => 'student',
			'table2' => 'student_data',
			'joinOn' => 'student.student_id=student_data.student_id'
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		// echo $this->db->last_query();
		// die;
		$i = $_POST['start'];
		foreach($tableData as $result){
			$btn = ($result->document_uploaded=='Y') ?
			'<a href="'.base_url('center/show_form/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>' : '';
			$i++;
			$data[] = array($result->student_id,$result->enrollment_no, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$btn);
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

	public function student_list($param1 = '')
	{
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);

		if($param1=='paid'){
		$titleData = array('title' => 'Paid Student List');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/all_paid_student',$csrf);
		}elseif($param1=='unpaid'){
		$titleData = array('title' => 'Unpaid Student List');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/all_unpaid_student',$csrf);
		}
		$this->load->view('Centers/footer');
	}

	public function getUnpaidFeesList($param1 = ''){
		$data = $row = array();
		$where = 'online_payment_transaction.center_id='.$this->session->center_id.' and online_payment_transaction.payment!="Y"';
		if($param1=='Admission'){
			$where .= ' and online_payment_transaction.fees_head="Admission Fees"';
		}elseif($param1=='Exam'){
			$where .= ' and online_payment_transaction.fees_head="Exam Fees"';
		}
		
		$column_order = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','amount',null);
		$column_search = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','amount');

		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where.' and online_payment_transaction.center_id=student.center_id',
			'table' => 'student',
			'table2' => 'online_payment_transaction',
			'joinOn' => 'student.student_id=online_payment_transaction.student_id'
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$btn = '<a href="#" data-student_id="'.$this->Common_model->encrypt_decrypt($result->student_id).'" data-id="'.$this->Common_model->encrypt_decrypt($result->id).'" class="btn btn-info btn-sm pay" >Pay</a>';
			$i++;
			$data[] = array($result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$result->amount,$btn);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('online_payment_transaction',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}


	public function getPaidFeesList(){
		$data = $row = array();
		$where = 'online_payment_transaction.center_id='.$this->session->center_id.' and online_payment_transaction.payment="Y"';
		
		$column_order = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','txnId',null);
		$column_search = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','txnId');

		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where.' and online_payment_transaction.center_id=student.center_id',
			'table' => 'student',
			'table2' => 'online_payment_transaction',
			'joinOn' => 'student.student_id=online_payment_transaction.student_id'
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$btn = '<a href="'.base_url('center/show_fees/'.$this->Common_model->encrypt_decrypt($result->id)).'" class="btn btn-primary btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>';			
			$i++;
			$data[] = array($result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$result->fees_head,$result->amount,$result->txnId,$btn);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('online_payment_transaction',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}

	public function profile(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/'));
		}else{
			$titleData = array('title' => 'center Profile'); 
			$this->load->view('Centers/header',$titleData);
			$center_data = $this->session->get_userdata($data);
			$id = $center_data['center_id'];
			$center = $this->Common_model->getRecordById('center','id',$id);
			$data = array('center' => $center);
			$this->getNotification();
			$this->load->view('Centers/profile',$data);
			$this->load->view('Centers/footer');
		}
	}
	public function change_password(){
		if(!$this->session->has_userdata('centerdata')){

			redirect(base_url('center/'));

		}else{

			$titleData = array('title' => 'Change Password'); 

			$this->load->view('Centers/header',$titleData);

			$center_data = $this->session->get_userdata($data);

			$id = $center_data['center_id'];

			$center = $this->Common_model->getRecordById('center','id',$id);
			$data = array('center' => $center);
			$this->getNotification();
			$this->load->view('Centers/change_password',$data);
			$this->load->view('Centers/footer');
		}
	}

	public function password_change($id)
	{
		$id = $this->Common_model->encrypt_decrypt($id,'decrypt');
		$where = array("id" => $id);

		$data = $this->Common_model->getRecordById('center','id',$id);

		$old_password = $data->password;

		if($this->input->post('password') != "")
		{
			if($old_password == $this->input->post('password'))
			{
				$new_password 	  = $this->input->post('new_password');
				$confirm_password = $this->input->post('new_password1');

				if($this->input->post('new_password1') != "")
				{

						if($new_password == $confirm_password)
						{
							
							$data_update = array("password" => $this->input->post("new_password"));
							$this->db->where('id', $id);
							$this->db->update('center', $data_update);

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
			}else{
				echo json_encode(array(
					"error" => 'Current Password is wrong',
				));
			}
		}else{
			echo json_encode(array(
				"error" => 'Please enter current password',
			));
		}
	}

	public function payStudentFees()
	{
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/'));
			die();
		}
		
		$student_id = $this->input->post('student_id');
		if($this->center_model->checkcenterStudent($student_id)){
		$onlinePayTxnId = $this->input->post('id');
		$center_id = $this->session->center_id;
		$centerdata = $this->Common_model->getRecordById('center','id',$center_id);
		$updateData = array(
			'payment' => 'Y',
			'payment_status' => 'Paid By center',
			'payment_date' => date('Y-m-d'),
			'payment_time' => date('H:i:s'),
		);
		if($centerdata->balance<200){
			$result = array('error' => 'Insufficient Balance',
				'balance' => $centerdata->balance,
			);
			echo json_encode($result);
			exit();
		}
		$balance = $centerdata->balance-200;

		$this->Common_model->updateRecordByConditions('center','id='.$center_id,array('balance'=>$balance));
		$this->Common_model->updateRecordByConditions('online_payment_transaction','id='.$onlinePayTxnId,$updateData);
		$this->Common_model->updateRecordByConditions('student','student_id='.$student_id,array('payment_status'=>"Y"));
		$result = array('success' => 'Fees Paid Successfully',
			'balance' => $balance,
		);
		echo json_encode($result);
	}
	}

	public function show_fees($onlinePayTxnId)
	{
		$onlinePayTxnId = $this->Common_model->encrypt_decrypt($onlinePayTxnId,'decrypt');
		$where = 'id='.$onlinePayTxnId;
		$transaction = $this->Common_model->get_record('online_payment_transaction','*',$where);
		if($transaction[0]['center_id']!=$this->session->center_id){
			$this->session->set_flashdata('error','Details Not Found');
			redirect(base_url('center/dashboard'));
		}
		$wherestudent = 'student_id='.$transaction[0]['student_id'];
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		$data = array(
		'student' => $student[0],
		'transaction' => $transaction[0],
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);

		$titleData = array('title'=>'Payment Details');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/payment_detail',$data);
		$this->load->view('Centers/footer');
	}

	public function getCourseByEligibility()
	{
		$eligibility = $this->input->post('eligibility');
		$course_group_list = $this->Common_model->get_record('course_group','*',array('eligibility'=>$eligibility,
			'admission_permission' => 'Y'
		));
		$data = array('course_group_list'=>$course_group_list);
		echo $this->load->view('template/getcourse',$data,true);
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
	public function checkDuplicateMobileNo()
	{	
		$p_mobile_no = $this->input->post('p_mobile_no');
		$count = $this->db->query("select * from student_data as d join student as s on s.student_id=d.student_id where s.course_complete='N' and d.p_mobile_no = '".$p_mobile_no."' limit 1")->num_rows();

		if($count>0){
			echo "Duplicate Mobile No";
		}
	}

	public function admission_instruction()
	{
		$this->load->view('Centers/header',array('title'=>'Admission Instruction'));
		$this->load->view('Centers/admission_instruction');
		$this->load->view('Centers/footer');
	}

	public function loginAs($centercode){
		$centercode = $this->Common_model->encrypt_decrypt($centercode,'decrypt');
		$check_user = $this->center_model->checkLink($centercode);
		if($check_user){	
			$data = array(
				'loged_in' => true,
				'centerdata' => $check_user->center_code,
				'center_id' => $check_user->id,
				'account_type' => 'center'
			);
			$this->session->set_userdata($data);
			redirect(base_url('center'));
		}else{
			$this->session->set_flashdata('error','center Code Are Incorrect');
			redirect(base_url('center'));
		}		
	}

	public function payment_complaint($param = ""){
		
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/'));
		}else{

			if(!$param)
			{ 
				
				$titleData = array('title' => 'Payment Complaint'); 
				$this->load->view('Centers/header',$titleData);
				$id =  $this->session->center_id;
				$center = $this->Common_model->getRecordById('center','id',$id);

				$center_id =  $this->session->center_id;

				$wherestudent = 'center_id='.$center_id;

				$center_detail = $this->Common_model->get_record('payment_complaint','*',$wherecenter);


				$data = array('center' => $center,'center_details' => $center_detail,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());
				$this->getNotification();
				$this->load->view('Centers/payment_complaint',$data);
				$this->load->view('Centers/footer');

			}else{
				
				$response = $this->center_model->payment_complaint($param);
			
				echo $response;
			}
		
			//redirect(base_url().'admin/enrollment/student_report');
		}
					
	}

	public function get_student_detail()
	{

		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
				$form_no  = $this->input->post("form_no");
			
				$wherestudent = 'student_id='.$form_no;

				$students = $this->Common_model->get_record('student','*',$wherestudent);

				$center_id =  $this->session->center_id;

				$wherestudent = 'center_id='.$center_id;

				$center_detail = $this->Common_model->get_record('payment_complaint','*',$wherecenter);
				
				$data = array('students' => $students ,'center_details' => $center_detail,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());

				if($data['students']){

					$dt =  $this->load->view('Centers/getStudentDetail',$data,true);

				}else{

					$dt = "Invalid Form no";
				}
				
				echo json_encode(array(
				"status" => true,
				"data" => $dt
				));
		}
			
	}
	
	public function getCourseBySession(){
		$session = $this->input->post('session');
		$where = "session='".$session."' and center_id=".$this->session->center_id;
		$course_group_list = $this->Common_model->get_record('student','distinct(course_group_id) as id,course_name',$where);
		$data = array(
			'course_group_list' => $course_group_list,
			'all'=> true,
		);
		echo $this->load->view('template/getcourse',$data,true);
	}		
	
	public function getAllClassByCourse(){
		$course = $this->input->post('course_group_id');
		$class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$course."'");
		$data = array(
			'class_list' => $class_list,
			'all'=> true
		);
		echo $this->load->view('template/getclass',$data,true);
	}


	public function form_edit_request()
	{
		if(!$this->session->has_userdata('centerdata')){

			redirect(base_url('center/'));

		}else{

			$titleData = array('title' => 'Form Edit Request');

			$this->load->view('Centers/header',$titleData);

			$id =  $this->session->center_id;

			$request_detail = $this->Common_model->get_record('request','*',array());

			$data = array('request_detail' => $request_detail,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());

			$this->getNotification();

			$this->load->view('Centers/form_edit_request',$data);
			$this->load->view('Centers/footer');

		}
	}

	public function getStudent_By_Course(){

		$course_id = $this->input->post('course_id');

		$where = "course_group_id = ".$course_id." and enrolled = 'N' and center_id=".$this->session->center_id;
		$student_list = $this->Common_model->get_record('student','student_id as id,name',$where);
		
		$data = array(
			'student_list' => $student_list,
			'all'=> true,
		);
		echo $this->load->view('template/getStudent',$data,true);
	}	

	public function get_request_detail(){

		$session_id = $this->input->post('session_id');
		$course_id  = $this->input->post('course_id');
		$student_id = $this->input->post('student');
		

		$response = $this->admin_model->create_form_request();

		$id =  $this->session->center_id;

		$request_detail = $this->Common_model->get_record('request','*',array());

		$data = array('request_detail' => $request_detail,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());

		$dt =  $this->load->view('admin/center/getRequestList',$data,true);


		echo json_encode(array("status" => 'true','data' => $dt));
	
	}	

	public function getPaymentComplaint()
	{
		$data = $row = array();
		$where = 'payment_complaint.center_id='.$this->session->center_id.' and type="admission" ';
		
		$column_order = array(null,'name','student.student_id','course_name','class_name','details','date','status','payment_remark');
		$column_search = array('name','student.student_id','course_name','class_name','details','date','status','payment_remark');

		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => 'payment_complaint',
			'table2' => 'student',
			'joinOn' => 'payment_complaint.student_id=student.student_id'
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$i++;
			$date = $this->Common_model->viewDate($result->date);
			$data[] = array($i, $result->name, $result->student_id, $result->course_name,$result->class_name,$result->details,$date,$result->status,$result->payment_remark);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('payment_complaint',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}
}
