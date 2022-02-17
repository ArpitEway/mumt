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
			redirect(base_url('dashboard'));
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
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Center Dashboard'); 
			$this->load->view('Centers/header',$titleData);
			$id =  $this->session->center_id;
			$center = $this->Common_model->getRecordById('center','id',$id);
			$data = array('center' => $center);
			$this->load->view('Centers/dashboard',$data);
			$this->getNotification();
			$this->load->view('Centers/footer');
		}
	}

	public function instruction(){
		
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Course Fees Structure'); 
			$this->load->view('Centers/header',$titleData);
			$center_id =  $this->session->center_id;
			$centerdata = $this->Common_model->getRecordById('center','id',$center_id);
			$this->db->where('id in ('.$centerdata->allot_course_id.')');
			$course_group_list = $this->Common_model->get_record('course_group','*');
			$data = array('course_group' => $course_group_list);
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
		redirect('http://162.144.38.91/~mmyvvdde/main/center/index.php');
	}

	public function admission_form(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
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
		$state_id = $this->Common_model->getSinglefield('state','state_id',array('name' => $state));
		$nameAttr = $this->input->post('nameAttr');
		$districts = $this->Common_model->get_record('distt','*',"state_id='".$state_id."'");
		$data = array(
			'district_list' => $districts,
			'nameAttr' => $nameAttr
		);

		echo $this->load->view('template/getdistrict',$data,true);
	}

	public function show_form($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('login'));
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
		$where = array('center_id' => $this->session->center_id);
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'session_list' => $this->Common_model->get_record('session','*'),
			'courses' => $this->Common_model->get_record_by_order('student','DISTINCT(course_group_id),course_name','course_name desc',$where)
		);

		$titleData = array('title' => 'Students Report' );
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
		$i = $_POST['start'];
		foreach($tableData as $result){
			$btn = ($result->document_uploaded=='Y') ?
			'<a href="'.base_url('show_form/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>' : '';
			$i++;
			if($result->enrolled=='N'){
				$enrollment = '-';
			}else{
				$enrollment = $result->enrollment_no;
				}
			$data[] = array($result->student_id,$enrollment,$result->name, $result->f_h_name, $result->course_name,$result->class_name,$btn);
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
			$btn = '<a href="'.base_url('show_fees/'.$this->Common_model->encrypt_decrypt($result->id)).'" class="btn btn-primary btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>';			
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
			redirect(base_url());
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

			redirect(base_url());

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
			redirect(base_url());
			die();
		}
		
		$student_id = $this->input->post('student_id');
		if($this->center_model->checkcenterStudent($student_id)){
		$onlinePayTxnId = $this->input->post('id');
		$center_id  = $this->session->center_id;
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
			redirect(base_url('dashboard'));
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
		if($this->session->has_userdata('center_id')){
		$center_id =  $this->session->center_id;
		$centerdata = $this->Common_model->getRecordById('center','id',$center_id);
		$this->db->where('id in ('.$centerdata->allot_course_id.')');
		}
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
			redirect('https://center.mmyvvonline.com');
		}else{
			$this->session->set_flashdata('error','center Code Are Incorrect');
			redirect(base_url('center'));
		}		
	}

	public function payment_complaint($param = ""){
		
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{

			if(!$param)
			{ 
				
				$titleData = array('title' => 'Payment Complaint'); 
				$this->load->view('Centers/header',$titleData);
				$id =  $this->session->center_id;
				$center = $this->Common_model->getRecordById('center','id',$id);

				$center_id =  $this->session->center_id;

				$wherestudent = 'center_id='.$center_id;

				$center_detail = $this->Common_model->get_record('payment_complaint','*',$wherestudent);


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

	public function get_student_detail(){
		if ($this->input->method() == "post"){
			$course_group_id = 0;
			$data = array();
			$dt   = array();

			$center_id =  $this->session->center_id;
			$form_no  = $this->input->post("form_no");
			$wherestudent = 'student_id='.$form_no.' and center_id='.$center_id;
			$students = $this->Common_model->get_record('student','*',$wherestudent);
			$wherestudent = 'center_id='.$center_id;

				$center_detail = $this->Common_model->get_record('payment_complaint','*',$wherestudent);
				
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

			redirect(base_url());

		}else{

			$titleData = array('title' => 'Form Edit Request');

			$this->load->view('Centers/header',$titleData);

			$id =  $this->session->center_id;

			$request_detail = $this->Common_model->get_record('request','*',array());

			$data = array('request_detail' => $request_detail,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());

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
			
		);
		echo $this->load->view('template/getStudent',$data,true);
	}	

	public function create_form_edit_request(){

		$session_id = $this->input->post('session_id');
		$course_id  = $this->input->post('course_id');
		$student_id = $this->input->post('student');
		
		$check_record = $this->Common_model->get_record('request','*',array("center_id" => $id,'student_id' => $student_id));
		$id =  $this->session->center_id;

		if($check_record){
			echo json_encode(array("status" => 'true','data' => "error"));
		}else{
		$response = $this->admin_model->create_form_request();
		$request_detail = $this->Common_model->get_record('request','*',array());
		$data = array('request_detail' => $request_detail,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());

		$dt =  $this->load->view('admin/center/getRequestList',$data,true);
		echo json_encode(array("status" => 'true','data' => $dt));
	
	}	
}

	public function getPaymentComplaint()
	{
		$data = $row = array();
		$where = 'payment_complaint.center_id='.$this->session->center_id.' and type="admission" ';
		
		$column_order = array(null,'name','student.student_id','course_name','class_name','details','date','status','payment_complaint.remark');
		$column_search = array('name','student.student_id','course_name','class_name','details','date','payment_complaint.status','payment_complaint.remark');

		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'select' => 'student.name, student.student_id, student.course_name, student.class_name, payment_complaint.date, payment_complaint.details, payment_complaint.remark,payment_complaint.status',
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
			$status = ($result->status=="Pending") ? 'Pending' : 'Done';
			$data[] = array($i, $result->name, $result->student_id, $result->course_name,$result->class_name,$result->details,$date,$status,$result->remark);
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

	public function getFormEditRequest()
	{
		$data = $row = array();
		$where = 'request.center_id='.$this->session->center_id;
		
		$column_order = array(null,'name','student.student_id','detail','date','status','request_remark');
		$column_search = array('name','student.student_id','detail','date','status','request_remark');

		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'select' => 'request.request_remark,request.student_id, request.date, request.detail, name, request.status',
			'where' => $where,
			'table' =>  'request',
			'table2' => 'student',
			'joinOn' => 'request.student_id=student.student_id'
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$i++;
			$status = ($result->status=='Pending') ? 'Pending' : 'Done';
			$date = $this->Common_model->viewDate($result->date);
			$data[] = array($i, $result->name, $result->student_id, $result->detail,$date,$status,$result->request_remark);
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




	public function not_approve_student_list(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$titleData = array('title' => 'Unapproved Student List' );
		$this->load->view('Centers/header',$titleData);

		$center_id =  $this->session->center_id;
	
		$where = array(
			'approved' =>'N',
			'center_id' => $center_id,
		);
		$data['students'] = $this->Common_model->getRecordByWhere('student',$where);
		$this->load->view('Centers/not_approve_student_list',$data);
		$this->load->view('Centers/footer');		
	}

	public function remaining_documents($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		if($student_id!=''){
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);
			$remark = $student->remark;
			if($remark!=''){
				$where = ' id in ( '.$remark.' ) ';
				$document = $this->Common_model->getRecordByWhere('document_category',$where);
			}else{
				$document=array();
			}
			$titleData = array('title' => 'Unapproved Document List');

			$data = array(
				'student' => $student,
				'documentData' => $document,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('Centers/header',$titleData);
			$this->load->view('Centers/remaining_documents',$data);
			$this->load->view('Centers/footer');
		}
	}


	public function exam_form_students($exam_form1 = 'notSubmitted'){
    
		$titleData = array('title' => 'Exam Form Student List' );
		$this->load->view('Centers/header',$titleData);
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);


		$center_id =  $this->session->center_id;
	
		    if($exam_form1=='submitted'){
			$where = array(
				'new_exam_form' =>'Y',
				'center_id' => $center_id,
			);	
			}else if($exam_form1 =="notSubmitted"){
				$where = array(
					'new_exam_form' =>'N',
					'center_id' => $center_id,
				);
			}else if($exam_form1=="skipped"){
				$where = array(
					'new_exam_form' =>'S',
					'center_id' => $center_id,
				);
			}
			$data['exam_form_button'] = $exam_form1 ;
			$data['documents'] = $this->Common_model->getRecordByWhere('student',$where);

		$this->load->view('Centers/exam_form_students',$data);
		$this->load->view('Centers/footer');		
	}


	public function change_new_exam_form_status(){
		$id    	= 0;
		$id    	= $this->input->post("id");
		$status = $this->input->post("check_skipped");

		if ($this->input->post("id")) 
		{
			$status = ($status=='skipped') ? 'S' : 'N';
			$data = $this->Common_model->updateRecordByConditions("student",array("student_id" => $id ),array("new_exam_form" => $status ));

			$dt = $this->db->get_where("student",array("student_id" => $id ))->result_array();

			if($dt[0]['new_exam_form'] == 'N')
			{
				$sts_btn = '<input type ="button" name="" data-id='.$id.' class="btn btn-danger check_skipped" value="skipped">';
			}else{
				$sts_btn = '<input type ="button" name="update_enroll_stats" data-id='.$id.' class="btn btn-success check_skipped" value="Unskipped">';
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

    public function showPapers($student_id){
    	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
    	$titleData = array('title' => 'Student Papers'); 
    	$this->load->view('Centers/header',$titleData);
    	
    	$where = array(
    		'student_id' => $student_id,
    	);
    	$student = $this->Common_model->student_info($student_id);
    	$data['student'] = $student;
    	$this->db->select('*');
    	$this->db->from('paper_master');
    	$this->db->join('new_exam_form', 'paper_master.id = new_exam_form.paper_id');
    	$where = array('paper_master.class_id' => $student['class_id'],
    		'student_id' => $student_id
    	);
    	$this->db->where($where); 
    	$data['papers'] = $this->db->get()->result();
    	// $this->Common_model->last_query();
    	$this->load->view('Centers/showPapers',$data);
    	$this->load->view('Centers/footer');
    }

	public function paper_missing_list(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$titleData = array('title' => 'Paper Missing List' );
		$this->load->view('Centers/header',$titleData);
		$center_id =  $this->session->center_id;
		$where = array(
			'temp_exam_form' =>'N',
			'center_id' => $center_id,
		);
		$data['students'] = $this->Common_model->getRecordByWhere('student',$where);
		$this->load->view('Centers/paper_missing_list',$data);
		$this->load->view('Centers/footer');		
	}

	public function select_papers($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData['title'] = 'Select Papers';
		$this->load->view('Centers/header',$titleData);
		$student = $this->Common_model->student_info($student_id);
		
		$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory"');
		$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id where class_id='.$student['class_id'].' Order by g.id')->result();

		$data['compulsoryPapers'] = $compulsoryPapers;
		$data['student'] = $student ;

		$data['student_id'] = $student['student_id'];

			// // CONDITION FOR GROUP PAPER
		$this->db->select('class_group,select_group,group_type');
		$this->db->from('class_master');
		$this->db->join('student', 'class_master.id = student.class_id');
		$this->db->where(array('class_master.id' => $data['student']['class_id'],
			'student_id' => $student['student_id']
		));
		$class_group = $this->db->get()->result();

		$data['class_group'] = $class_group ; 

		$data['groupPaper'] = $groupPaper;

		
		if($class_group[0]->group_type=='Paper'){
			
			$this->load->view('centers/select_papers',$data);
		}else{
			
			$this->load->view('centers/select_group',$data);
		}
		$this->load->view('Centers/footer');

	}

	public function submit_papers(){
		$student_id = $_POST['student_id'];
		$paper_id1 = $_POST['paper_id'];
		$paper_id2 = $_POST['compulsary_paper_id'];
		$paper_id= array_merge($paper_id1,$paper_id2);
		$paper_id = implode(",",$paper_id);
		$paper_data = 	$this->Common_model->get_record('paper_master','*','id in ('.$paper_id.')');
		
		foreach($paper_data as $paper){
			$data['course_group_id']=$paper['course_group_id'];
			$data['class_id']=$paper['class_id'];
			$data['paper_code']=$paper['paper_code'];
			$data['paper_type']=$paper['type'];
			$data['book_code']=$paper['book_code'];
			$data['paper_id']=$paper['id'];
			$data['student_id']=$student_id;
			$insert = $this->Common_model->insertAll('new_exam_form',$data);
		}
		

		if($insert){
			$data = array('temp_exam_form'=>'Y');
			$where = array('student_id'=>$student_id);
			$this->Common_model->updateRecordByConditions('student',$where,$data);
			echo json_encode(array("status" => 'true','student_id' => $student_id));
		}else{
			echo json_encode(array("status" => 'false','student_id' => $student_id));
		}
	}


	public function submit_group(){
		$group_id = implode(',',$_POST['group_id']);
		$paper_id = 	$this->Common_model->get_record('group_paper','group_concat(paper_id) as paper_id ','group_id in ( '.$group_id.' ) ');
		$paper_id1 = $paper_id[0]['paper_id'] ;
		$paper_id2 = $_POST['compulsary_paper_id'] ;
		$paper_id2 = implode(",",$paper_id2);
		$paper_id = $paper_id1.",".$paper_id2 ;
		$paper_data = 	$this->Common_model->get_record('paper_master','*','id in ('.$paper_id.')');
		$student_id=$_POST['student_id'];
		

		foreach($paper_data as $paper){
			$data['course_group_id']=$paper['course_group_id'];
			$data['class_id']=$paper['class_id'];
			$data['paper_code']=$paper['paper_code'];
			$data['paper_type']=$paper['type'];
			$data['book_code']=$paper['book_code'];
			$data['paper_id']=$paper['id'];
			$data['student_id']=$student_id;
			$insert = $this->Common_model->insertAll('new_exam_form',$data);
		}

		if($insert){
			$data = array('temp_exam_form'=>'Y');
			$where = array('student_id'=>$student_id);
			$this->Common_model->updateRecordByConditions('student',$where,$data);
			echo json_encode(array("status" => 'true','student_id' => $student_id));
		}else{
			echo json_encode(array("status" => 'false','student_id' => $student_id));
		}
	}
}