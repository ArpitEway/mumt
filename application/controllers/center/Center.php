<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Center/center_model');
		$this->load->model('Datatable_join_model');
	}

	public function index(){
		if($this->session->has_userdata('centerdata')){
			redirect(base_url('center/instruction'));
		}else{
			$this->load->view('Centers/header');
			$this->load->view('Centers/disclaimer');
			$this->load->view('Centers/footer');
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
			$titleData = array('title' => 'Center Instruction'); 
			$this->load->view('Centers/header',$titleData);
			$id =  $this->session->center_id;
			$center = $this->Common_model->getRecordById('center','id',$id);


			$course_group = $this->db->get_where('course_group', array())->result_array();

			//$course = $this->db->get_where('course', array())->result_array();

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
		$this->load->view('Centers/login');
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
			$this->load->view('Centers/login');
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
				$this->load->view('Centers/login',$data);
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
			'session' => 2021,
			'eligibility_list' => $eligibility_list,
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
		$data = array();
		$data['student'] = $this->Common_model->student_info($student_id);
		if($data['student']['approved']!='Y'){
			redirect(base_url('center/admission/'.$student_id));
		}
		$this->load->view('Centers/header',array('title' => 'Enrollment Form'));	
		$this->load->view('template/form',$data);
		$this->load->view('Centers/footer');
	}



	public function getAdmissionClassByCourse(){
		$course = $this->input->post('course');
		// and admission_permission!='Y'
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
//$this->load->view('Centers/header');
			$this->load->view('Centers/notification',$centerdata);
//$this->load->view('Centers/footer');	
		}
	}

	public function student_list()
	{
		$titleData = array('title' => 'Students List', );
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/student_details');
		$this->load->view('Centers/footer');
	}

	public function getStudentList(){
		$data = $row = array();
		$where = array(
			'center_id' => $this->session->center_id,
		);
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

			$btn = '<a href="'.base_url('center/show_form/'.$result->student_id).'" class="btn btn-primary btn-sm" target="_blank" ><i class="fa fa-eye"></i></a>';
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
		if($param1=='paid'){
		$titleData = array('title' => 'Paid Student List');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/all_paid_student');
		}elseif($param1=='unpaid'){
		$titleData = array('title' => 'Unpaid Student List');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/all_unpaid_student');
		}
		$this->load->view('Centers/footer');
	}

	public function getFeesList($param1 = ''){
		$data = $row = array();
		$where = 'online_payment_transaction.center_id='.$this->session->center_id;
		if($param1=='paid'){
			$where .= ' and online_payment_transaction.payment="Y" ';
		}elseif($param1=='unpaid'){
			$where .= ' and online_payment_transaction.payment!="Y" ';
		}
		
		$column_order = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','payment_date',null);
		$column_search = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','payment_date');

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
			if($result->payment_status!='success'){
			$btn = '<a href="#" data-student_id="'.$result->student_id.'" data-id="'.$result->id.'" class="btn btn-info btn-sm pay" >Pay</a>';
			$payment_date = '';
			}else{
			$btn = '<a href="'.base_url('center/show_fees/'.$result->id).'" class="btn btn-primary btn-sm" target="_blank" ><i class="fa fa-eye"></i></a>';
			$payment_date = $this->Common_model->viewDate($result->payment_date);
			}

			$i++;
			$data[] = array($result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$result->fees_head,$result->amount,$payment_date,$btn);
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

			//$id =  $this->session->center_id;

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
}
