<?php
ob_start();
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
			$titleData = array('title' => 'Regular Course Fees Structure');
			$this->load->view('Centers/header',$titleData);
			$center_id =  $this->session->center_id;
			$centerdata = $this->Common_model->getRecordById('center','id',$center_id);
			$this->db->where('id in ('.$centerdata->allot_course_group_id.')');
			$course_group_list = $this->Common_model->get_record('course_group','*',array('status !=' => 'D'));
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
		}else{
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
		$center_ids = array( 10,11,12,13,21,22,23,24,25,26,27,28 );
		if(in_array($this->session->center_id, $center_ids)){
			$this->session->sess_destroy();
			redirect(base_url());
		}else{
			$this->session->sess_destroy();
			redirect('http://162.144.38.91/~mmyvvdde/main/center/index.php');
		}
	}

	public function admission_form($mode=''){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
			exit;
		}
		$center_id =  $this->session->center_id;
		$center_ids_dep = array(21,22,23,24,25,26,27,28);
		$whereSession = array();
		if (in_array($center_id, $center_ids_dep)){
			$whereSession['admission_permission_dep'] =  'Y';
		}else{
			$whereSession['admission_permission_ic'] =  'Y';
		}
		
		if($mode=='regular'){
			$where = array('admission_permission'=>'Y' ,'id'=>$center_id);
			$head = '(Regular)';
		}else{
			$where = array('admission_permission_private'=>'Y','id'=>$center_id);
			$head = '(Private)';
			$whereSession['pvt_admission_permission_ic'] =  'Y';
		}
		$sessions = $this->Common_model->get_record('session','*',$whereSession);
		$check = $this->Common_model->getRecordByWhere("center",$where);
		if(($mode=='regular' && $check[0]->admission_permission!='Y') || ($mode=='private' && $check[0]->admission_permission_private!='Y')){
			redirect(base_url('dashboard'));
		}

		$titleData = array('title' => 'Admission Form '.$head);
		$state_list = $this->Common_model->get_record('state','*');
		$eligibility_list = $this->Common_model->get_record('course_group','DISTINCT (eligibility)');
		$district_list = $this->Common_model->get_record('distt','*');
		$course_group_list = $this->Common_model->get_record('course','*');
		$data = array(
			'mode'=>$mode,
			'state_list' => $state_list,
			'district_list' => $district_list,
			'course_group_list' => $course_group_list,
			'eligibility_list' => $eligibility_list,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'sessions' => $sessions,
			'center_ids_dep' =>$center_ids_dep,
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
		$student_mode = $this->input->post('mode');
		$this->db->select('class_master.*');
		$this->db->from('class_master');
		$this->db->join('course_group', 'class_master.course_group_id = course_group.id');
		if($student_mode=="private"){
			$this->db->where('course_group.private_mode=class_master.mode');
		 }else{
			$this->db->where('class_master.mode=course_group.mode');
		 }
		$this->db->where('class_master.admission_permission','Y');
		$this->db->where('course_group_id',$course);
		$class_list = $this->db->get()->result_array();
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
		$course_type = $this->uri->segment(2);  
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'session_list' => $this->Common_model->get_record('session','*'),
			'courses' => $this->Common_model->get_record_by_order('student','DISTINCT(course_group_id),course_name','course_name desc',$where),
			'course_type' => $course_type 
		);

		$titleData = array('title' => 'Students Report');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/student_details',$csrf);
		$this->load->view('Centers/footer');
	}

	public function getStudentList(){
		$data = $row = array();

		$where = array(
			'center_id' => $this->session->center_id,
			'new_admission_permission'=>'N'
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
		 if($_POST['university_mode']!='All'){
			$where['university_mode'] = $this->input->post('university_mode');
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
		$course_type = $this->uri->segment(3); 
		$csrf = array( 
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'course_type' => $course_type 
		);
		 
		if($param1=='paid'){
		$titleData = array('title' => 'Paid Student List');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/all_paid_student',$csrf);
		}elseif($param1=='unpaid'){
			if($course_type=="PVT")	
				$titleData = array('title' => 'Private Unpaid Student List');
			else
				$titleData = array('title' => 'Regular Unpaid Student List');
		//$titleData = array('title' => 'Unpaid Student List');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/all_unpaid_student',$csrf);
		}
		$this->load->view('Centers/footer');
	}

	public function getUnpaidFeesList($param1 = ''){
		$course_type=$this->input->post('course_type');
		$data = $row = array();
		$where = 'online_payment_transaction.center_id='.$this->session->center_id.' and online_payment_transaction.payment!="Y"';
		
		if($param1=='Admission'){
			$where .= " and online_payment_transaction.fees_head='Admission Fees'  and  `student.payment_status`='N' and ( (student.class_name not like '%SEM%' and student.session='July 2021') or session!='July 2021')";
		}elseif($param1=='Exam'){
			$where .= ' and online_payment_transaction.fees_head="Exam Fees"';
		}

		$column_order = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','amount',null);
		$column_search = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','amount');

		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where.' and online_payment_transaction.center_id=student.center_id AND student.university_mode="'.$course_type.'"',
			
			'table' => 'student',
			'table2' => 'online_payment_transaction',
			'joinOn' => 'student.student_id=online_payment_transaction.student_id'
		);
		
		 
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		
		$i = $_POST['start'];
		
	
		 $counttableData = $this->Datatable_join_model->joincountAll($_POST,$DataTableArray);
				  
		foreach($tableData as $result){
			$center_ids_dep = array( 21,22,23,24,25,26,27,28);
			if(in_array($this->session->center_id, $center_ids_dep)){
				$modal ='<a href="#"  data-student_name = "'.$result->name.'"  data-student_id="'.$this->Common_model->encrypt_decrypt($result->student_id).'" class="btn btn-primary btn-sm font-weight-bold pay1" data-toggle="modal" data-target="#kt_datepicker_modal" "  data-amount= "'.$result->amount.'">Receive</a>';
			}else{
			 $modal = '<a href="#" data-student_id="'.$this->Common_model->encrypt_decrypt($result->student_id).'" data-id="'.$this->Common_model->encrypt_decrypt($result->id).'" class="btn btn-info btn-sm pay" >Pay</a>';
			}
			
			$i++;
			$data[] = array($result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$result->amount,$modal);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $counttableData,//$this->Datatable_join_model->countAll('online_payment_transaction',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}


	public function getPaidFeesList(){
		$data = $row = array();
		$where = 'online_payment_transaction.center_id='.$this->session->center_id.' and online_payment_transaction.payment="Y"';

		$column_order = array('university_mode,student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','txnId',null);
		$column_search = array('university_mode,student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','txnId');
		$course_type=$this->input->post('course_type');
		//AND student.university_mode="'.$course_type.'"
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
			$university_mode = ($result->university_mode=='REG') ? 'Regular' : 'Private';
			$data[] = array($university_mode, $result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$result->fees_head,$result->amount,$result->txnId,$btn);
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
			$data = array('center' => $center,
                'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(), );
			$this->load->view('Centers/change_password',$data);
			$this->load->view('Centers/footer');
		}
	}


	public function change_password_sub($id)
	{
		// $id = $this->Common_model->encrypt_decrypt($id,'decrypt');
		// $where = array("id" => $id);

		// $data = $this->Common_model->getRecordById('center','id',$id);

		// $old_password = $data->password;
			
				$new_password 	  = $this->input->post('new_password');
				$confirm_password = $this->input->post('passconf');

				if($this->input->post('new_password') != "")
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
		$session = $this->input->post('session');
		$mode = $this->input->post('mode');
		$myString =$eligibility;
		 
		if($this->session->has_userdata('center_id')){
		$center_id =  $this->session->center_id;
		$centerdata = $this->Common_model->getRecordById('center','id',$center_id);
		$this->db->where('course_group_id in ('.$centerdata->allot_course_group_id.')');
		}
		 $where['eligibility'] = $eligibility;
		/* if($mode=='regular'){
		   $where['admission_permission'] = 'Y';
		 }else{
			$where['admission_permission_pvt'] = 'Y';
		 }
		$course_group_list = $this->Common_model->get_record('course_group','*',$where);
		*/
		
		$this->db->select('course_group.id,course.course_name');
		$this->db->from('course');
		$this->db->join('course_group', 'course_group.id = course.course_group_id'); 
		$this->db->where('eligibility',$eligibility);
		$this->db->where('course.session',$session);
		if($mode=='regular'){
			$where['admission_permission_regular'] = 'Y';
			$this->db->where('admission_permission_regular','Y');
		  }else{
			 $where['admission_permission_private'] = 'Y';
			 $this->db->where('admission_permission_private','Y');
		  }
		
		$query = $this->db->get();
		$course_group_list= $query->result_array();
		
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

	public function admission_instruction($mode='')
	{
		$data['mode']=$mode ;
		$this->load->view('Centers/header',array('title'=>'Admission Instruction'));
		$this->load->view('Centers/admission_instruction',$data);
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
			
			//redirect('https://center.mmyvvonline.com');
			redirect($this->index);
		}else{
			$this->session->set_flashdata('error','center Code Are Incorrect');
			redirect(base_url('center'));
		}
	}

	public function payment_complaint($param = ""){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{
			if(!$param){
				$titleData = array('title' => 'Payment Complaint');
				$this->load->view('Centers/header',$titleData);
				$id =  $this->session->center_id;
				$center = $this->Common_model->getRecordById('center','id',$id);
				$center_id =  $this->session->center_id;
				$wherestudent = 'center_id='.$center_id;
				$center_detail = $this->Common_model->get_record('payment_complaint','*',$wherestudent);
				$data = array('center' => $center,'center_details' => $center_detail,'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash());
				$this->load->view('Centers/payment_complaint',$data);
				$this->load->view('Centers/footer');
			}else{
				$response = $this->center_model->payment_complaint($param);
				echo $response;
			}
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
			$data = array('students' => $students ,
				'center_details' => $center_detail,
				'name_csrf' => $this->security->get_csrf_token_name(),
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
		$course_type= $this->input->post('course_type');
		$course_type_where="";
		if(!empty($course_type)){
			
				$course_type_where="student.university_mode='".$course_type."' AND ";	
			
		}
		$where = $course_type_where." session='".$session."' and center_id=".$this->session->center_id;
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


	public function form_edit_request($course_type="REG")
	{
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{
			if($course_type=="PVT")	 
				$titleData = array('title' => 'Private Form Edit Request');
			else
				$titleData = array('title' => 'Regular Form Edit Request');	
			$this->load->view('Centers/header',$titleData);
			$id =  $this->session->center_id;
			$request_detail = $this->Common_model->get_record('request','*',array());
			$data = array('request_detail' => $request_detail,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'course_type' =>  $course_type,
			);
			$this->load->view('Centers/form_edit_request',$data);
			$this->load->view('Centers/footer');
		}
	}

	public function getStudent_By_Course(){
		$course_group_id = $this->input->post('course_group_id');
		$where = "course_group_id = ".$course_group_id." and enrolled = 'N' and center_id=".$this->session->center_id;
		$student_list = $this->Common_model->get_record('student','student_id as id,name',$where);
		$data = array('student_list' => $student_list,);
		echo $this->load->view('template/getStudent',$data,true);
	}

	public function create_form_edit_request(){
		$session_id = $this->input->post('session_id');
		$course_group_id  = $this->input->post('course_group_id');
		$student_id = $this->input->post('student');
		$check_record = $this->Common_model->get_record('request','*',array("center_id" => $id,'student_id' => $student_id));
		$id =  $this->session->center_id;
		if($check_record){
			echo json_encode(array("status" => 'true','data' => "error"));
		}else{
			$response = $this->admin_model->create_form_request();
			$request_detail = $this->Common_model->get_record('request','*',array());
			$data = array('request_detail' => $request_detail,
				'name_csrf' => $this->security->get_csrf_token_name(),
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
		$course_type=$this->input->post('course_type');
		$course_type_where="";
		if(!empty($course_type))
			$course_type_where .=" AND student.university_mode='".$course_type."'  ";	
		$data = $row = array();
		$where = 'request.center_id='.$this->session->center_id;
		$column_order = array(null,'name','student.student_id','detail','date','status','request_remark');
		$column_search = array('name','student.student_id','detail','date','status','request_remark');
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'select' => 'request.request_remark,request.student_id, request.date, request.detail, name, request.status',
			'where' => $where.$course_type_where,
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
		$course_type = $this->uri->segment(2); 
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		if($course_type=="PVT")	
			$titleData = array('title' => 'Unapproved Private Student List' );
		else
			$titleData = array('title' => 'Unapproved Regular Student List' );	
		$this->load->view('Centers/header',$titleData);

		$center_id =  $this->session->center_id;

		$where = array(
			'approved' =>'N',
			'center_id' => $center_id,
			'university_mode' =>$course_type,
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
		redirect(base_url());
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);

		$center_id =  $this->session->center_id;
		if($exam_form1=='submitted'){
			$where = array('new_exam_form' =>'Y','center_id' => $center_id);
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
		$data['exam_form_button'] = $exam_form1;
		$data['documents'] = $this->Common_model->getRecordByWhere('student',$where);
		$this->load->view('Centers/header');
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
		$data['student'] = $student;

		$data['student_id'] = $student['student_id'];

			// // CONDITION FOR GROUP PAPER
		$this->db->select('class_group,select_group,group_type');
		$this->db->from('class_master');
		$this->db->join('student', 'class_master.id = student.class_id');
		$this->db->where(array('class_master.id' => $data['student']['class_id'],
			'student_id' => $student['student_id']
		));
		$class_group = $this->db->get()->result();

		$data['class_group'] = $class_group;

		$data['groupPaper'] = $groupPaper;


		if($class_group[0]->group_type=='Paper'){

			$this->load->view('Centers/select_papers',$data);
		}else{

			$this->load->view('Centers/select_group',$data);
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
		$paper_id = $_POST['compulsary_paper_id'];
		$paper_id = implode(",",$paper_id);

		if(isset($_POST['group_id'])){
			$group_id = implode(',',$_POST['group_id']);
			$group_paper_ids = 	$this->Common_model->get_record('group_paper','group_concat(paper_id) as paper_id ','group_id in ( '.$group_id.' ) ');
			$group_paper_id = $group_paper_ids[0]['paper_id'];
			$paper_id = $paper_id.",".$group_paper_id;
		}
		$paper_data = 	$this->Common_model->get_record('paper_master','*','id in ('.$paper_id.')');
		$student_id=$this->Common_model->encrypt_decrypt($_POST['student_id'],'decrypt');
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

	public function admit_card_list(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$titleData = array('title' => 'Admit Card List DEC 2021' );
		$this->load->view('Centers/header',$titleData);
		$where = array('center_id'=>$this->session->center_id , 'admit_card_permission' =>'Y',"student.roll_no!="=>0);
		$this->db->select('DISTINCT(student.class_id) as
			class_id,course_name,student.class_name,class_id');
		$this->db->from('student');
		$this->db->Where($where);
		$this->db->join('class_master', 'class_master.id = student.class_id');
		$data['students'] = $this->db->get()->result();
		 //  $this->Common_model->last_query();
		$this->load->view('Centers/class_wise_admit_card',$data);
		$this->load->view('Centers/footer');
	}



	public function admit_card_student_list($class_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$class_id=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$titleData = array('title' => 'Admit Card Student List DEC 2021' );
		$this->load->view('Centers/header',$titleData);
		$center_id =  $this->session->center_id;
		$where = array(
			'class_id' =>$class_id,
			'center_id' => $center_id,
		);
		$data['students'] = $this->Common_model->getRecordByWhere('student',$where);
		$this->load->view('Centers/class_wise_admit_card_list',$data);
		$this->load->view('Centers/footer');
	}

	public function admit_card($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$titleData = array('title' => 'Admit Card Dec 2021' );
		$this->load->view('Centers/header',$titleData);
		$center_id =  $this->session->center_id;
		$where = array(
			'student_id' => $student_id,
			'roll_no !=' => 0,
			'center_id' => $center_id,
		);

		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('center', 'center.id = student.center_id');
		$this->db->where($where);
		$data['student'] = $this->db->get()->result();

		$wherePaper = array('student_id' => $student_id);
		$this->db->select('*');
		$this->db->from('paper_master');
		$this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
		$this->db->join('time_table', 'paper_master.class_id = time_table.class_id');
		$this->db->where($wherePaper);
		$data['papers'] = $this->db->get()->result();

		$this->load->view('template/admit_card',$data);
		$this->load->view('Centers/footer');
	}

	public function student_roll_no_list(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$titleData = array('title' => 'Student Roll No List DEC 2021' );
		$this->load->view('Centers/header',$titleData);
		$center_id =  $this->session->center_id;
		$where = array('center_id' => $center_id, 'roll_no !=' => 0);
		$data['students'] = $this->Common_model->getRecordByWhereByOrder('student',$where,'roll_no','ASC');
		$this->load->view('Centers/student_roll_no_list',$data);
		$this->load->view('Centers/footer');
	}

	public function paid_by_university($student_id){

		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$student_data = $this->Common_model->getRecordByWhere('student',array('student_id'=>$student_id));

		$where = array('session' =>$student_data[0]->session,
			'course_group_id' => $student_data[0]->course_group_id,
		);

		$fees = $this->Common_model->getRecordByWhere('course',$where);
		$data['student_id']=$student_data[0]->student_id;
		$data['center_id']=$student_data[0]->center_id;
		$data['course_group_id']=$student_data[0]->course_group_id;
		$data['class_id']=$student_data[0]->class_id;
		$data['amount']=$fees[0]->program_fees+$fees[0]->exam_fees;
		$data['fees_head']='Exam Fees';
		$data['student_name']=$student_data[0]->name;
		$data['payment']='Y';
		$data['payment_status']='Paid By University';
		$data['payment_date']= date("Y-m-d");
		$data['admission_type']= 'Regular';
		$data['payment_time']=date("h:i:s");
		$insert = $this->Common_model->insertAll('online_payment_transaction',$data);
		$student_data = array('new_exam_form' => 'Y');
		$update = $this->Common_model->updateRecordByConditions('student','student_id='.$student_id,$student_data);
		if($update){
			redirect(base_url('exam_form_students'));
		}
	}


	public function remaining_exam_answersheet_admin(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
	  $data = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);
		$center_id =  $this->session->center_id;
		$titleData = array('title' => 'Remaining Exam Answersheet');
		$this->load->view('Centers/header',$titleData);
		$this->db->select('count(*) as cnt ,student.class_id,new_exam_form.course_group_id , center_code , center_name ,roll_no,enrollment_no , name , course_name , class_name ,student.student_id');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id');
		$this->db->where('student.new_exam_form','Y');
		$this->db->where('student.center_id',$center_id);
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->group_by('new_exam_form.student_id');
		$data['students'] = $this->db->get()->result();
		$this->load->view('Centers/remaining_exam_answersheet',$data);
		$this->load->view('Centers/footer');
	}

	public function activity($param1="",$param2=""){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$center_id =  $this->session->center_id;
		if($param1 == 'create'){
        	$data = array(
				"activity_name"=>$_POST['activity_name'],
				'description'=>$_POST['description'],
				'date'=>$_POST['date'],
				'center_id'=>$center_id
			);
			$last_id = $this->Common_model->insertAll('activity',$data);
			if($_FILES['file']['name']!="")
			{
			$files = array_filter($_FILES['file']['name']); //Use something similar before processing files.
			// Count the number of uploaded files in array
			$total_count = count($_FILES['file']['name']);
			// Loop through every file
			for( $i=0; $i < $total_count; $i++ ) {
				//The temp file path is obtained
				$tmpFilePath = $_FILES['file']['tmp_name'][$i];
				//A file path needs to be present
				if ($tmpFilePath != ""){
					//Setup our new file path
					$newFilePath = "./assets/activity/" .date('Y-m-d-H-i-s')."_".  $_FILES['file']['name'][$i];
					//File is uploaded to temp dir
					if(move_uploaded_file($tmpFilePath, $newFilePath)) {
						$data = array(
							"activity_id"=>$last_id,
							"activity_file"=>date('Y-m-d-H-i-s')."_".  $_FILES['file']['name'][$i],
						);
						$insert = $this->Common_model->insertAll("activity_file",$data);
					}
				}
			}
		}
		redirect(base_url().'activity');
	}

	if($param1 == 'update'){

		if($_FILES['file']['name']!="")
		{
			 $files = array_filter($_FILES['file']['name']); //Use something similar before processing files.
			 // Count the number of uploaded files in array
			 $total_count = count($_FILES['file']['name']);
			 // Loop through every file
			 for( $i=0; $i < $total_count; $i++ ) {
				 //The temp file path is obtained
			 	$tmpFilePath = $_FILES['file']['tmp_name'][$i];
				 //A file path needs to be present
			 	if ($tmpFilePath != ""){
					 //Setup our new file path
			 		$newFilePath = "./assets/activity/" .date('Y-m-d-H-i-s')."_".  $_FILES['file']['name'][$i];
					 //File is uploaded to temp dir
			 		if(move_uploaded_file($tmpFilePath, $newFilePath)) {
			 			$data = array(
			 				"activity_id"=>$_POST['activity_id'],
			 				"activity_file"=>date('Y-m-d-H-i-s')."_".  $_FILES['file']['name'][$i],
			 			);
			 			$insert = $this->Common_model->insertAll("activity_file",$data);
			 		}
			 	}
			 }
			}

			$data = array(
				'date' =>$_POST['date'],
				'activity_name' =>$_POST['activity_name'],
				'description' =>$_POST['description'],
			);
			$update = $this->Common_model->updateRecordByConditions('activity',array("id"=>$_POST['activity_id']),$data);
			if($update){
				redirect(base_url().'activity');
			}
		}

		if($param1 == 'delete'){
			$response = $this->Common_model->deleteByWhere('activity',array("id"=>$param2));
			$this->session->set_flashdata('ajax_flash_message','Activity Successfully Deleted');
			redirect(base_url().'activity');
		}

		if(empty($param1)){
			$data = array('title' => "Activity");
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('Centers/header',$data);
			$this->load->view('Centers/activity',$csrf);
			$this->load->view('Centers/footer');

		}
	 }
	public function internal_marks_list(){
	 		redirect(base_url());
	 	if(!$this->session->has_userdata('centerdata')){
	 		redirect(base_url());
	 	}
	 	$data = array(
	 		'name_csrf' => $this->security->get_csrf_token_name(),
	 		'hash_csrf' => $this->security->get_csrf_hash(),
	 	);

	 	$titleData = array('title' => 'Internal  Marks Submission' );
	 	$this->load->view('Centers/header',$titleData);
	 	$center_id =  $this->session->center_id;
	 	$where = array('center_id' => $center_id,'new_exam_form' => 'Y','result_show ' => 'N');
	 	$this->db->order_by("int_marks_sub,student.course_group_id,student.class_id", "asc");
	 	$this->db->select('*');
	 	$this->db->from('student');
	 	$this->db->Where($where);
	 	//$this->db->where('`student.class_id` in (154 , 158 , 181 , 193 , 195 , 197 , 199 , 201 , 203 , 205 , 207 , 209 , 211 , 213 , 221 , 223 , 225 , 227 )');
	 	$data['students'] = $this->db->get()->result();//echo $this->db->last_query(); die;
	 	$this->load->view('Centers/student_marks_no_list',$data);
	 	$this->load->view('Centers/footer');
	}

	public function load_student_assignment(){
	 	$student_id = $this->input->post('student_id');
		 $class_id = $this->input->post('class_id');
		$classData	= $this->Common_model->getRecordById('class_master','id',$class_id);
	 	//$where=array('student.student_id'=>$student_id,'paper_type'=>'theory');
	 	$this->db->select('*');
	 	$this->db->from('new_exam_form');
	 	//$this->db->Where($where );
		$this->db->where('student.student_id',$student_id);
		if($classData->practical_internal_marks=="N")
			$this->db->where('paper_type','theory');
	 	$this->db->join('student', 'student.student_id = new_exam_form.student_id');
	 	$details = $this->db->get()->result();
		 	//echo $classData->practical_internal_marks.$this->db->last_query(); die;
	 	$data = array(
	 		'details' => $details,
	 		'name_csrf' => $this->security->get_csrf_token_name(),
	 		'hash_csrf' => $this->security->get_csrf_hash(),
	 	);
	 	if($data){
	 		$model =  $this->load->view('Centers/view_student_model_data',$data,true);
	 		$status = true;
	 	}
	 	echo json_encode(array(
	 		"status" => $status,
	 		"data" => $model
	 	));
	}

	public function assignment_marks_sub()
	{
	 	$data=array();
	 	$post = $this->input->post();
	 	$data['paper_id'] = $this->input->post('paper_id');
	 	$data['marks'] = $this->input->post('marks');
	 	foreach ($data['paper_id'] as $key => $value){
	 		$studentData = array(
	 			'int_marks' => $data['marks'][$key],
	 		);
	 		$where =  array(
	 			'paper_id' =>$value,
	 			'student_id'  =>$_POST['student_id']
	 		);
	 		$Marksentry = $this->Common_model->updateRecordByConditions('new_exam_form',$where,$studentData);
	 	}
	 	$where1 =  array(
	 		'student_id'  =>$_POST['student_id']
	 	);
	 	$Data = array(
	 		'int_marks_sub' => 'Y',
	 	);
	 	$Marksentry1 = $this->Common_model->updateRecordByConditions('student',$where1,$Data);
	 	if($Marksentry1)
	 	{
	 		$returndata = array('success'=> 'Form Has Been Submited');
	 		echo json_encode($returndata);
	 	}else{
	 		$returndata = array('error'=> 'An Error Occured');
	 		echo json_encode($returndata);
	 	}
	 }

	public function show_activity_file(){
		$activity_file= $this->Common_model->getRecordByWhere("activity_file",array("activity_id"=>$_POST['activity_id']));
		$name_csrf  = $this->security->get_csrf_token_name();
		$hash_csrf =  $this->security->get_csrf_hash();
		$output = "<div class='row'>";
		foreach($activity_file as $files){
			$activity_img="".site_url()."assets/activity/".$files->activity_file;
			$output .= '
			<div class="col-md-2">
			<input type="hidden" class="csrfname" name="'.$name_csrf.'" value="'.$hash_csrf.'">
			<img src="'.$activity_img.'" class="img-thumbnail" name"old_image" style="height:125px;" />
			<button type="button" class="btn btn-link remove_image"  onclick="confirmation('.$files->id.')"   id="'.$files->id.'">Remove</button>
			</div>';
		}
		$output .= '</div>';
		echo json_encode(array("status" => true, "data" => $output));
	}

	public function delete_activity_file()
	{
		$delete_img = $this->Common_model->deleteByWhere("activity_file",array('id'=>$_POST['id']));
		echo json_encode(array("status" => true,));
	}

	public function result()
	{
		$center_id =  $this->session->center_id;
        $where = array('center_id'=>$center_id);
		$data = array('name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$this->db->select('distinct(student.course_group_id) as course_group_id , course_group.course_name');
		$this->db->from('student');
		
		$this->db->join('course_group', 'student.course_group_id = course_group.id');
		$this->db->join('class_master', 'class_master.course_group_id = course_group.id');
		//$this->db->where('class_master.id', 'student.class_id');
		
		$this->db->where('class_master.result_permission', 'Y');
		$this->db->where('center_id', $center_id);
		$this->db->where('result_show','Y');
		//$this->db->where('`student.class_id` in (154,181,193,199,201,209,221,223,225,197,203,211,213)');
		$data['courses'] = $this->db->get()->result();
		//echo $this->db->last_query(); die;
		$this->load->view('Centers/header', array('title' => 'Result'));
		$this->load->view('Centers/result',$data);
		$this->load->view('Centers/footer');		
	}

	public function AllClassByCourse()
	{
    $course = $this->input->post('course_group_id');
	$this->db->select('*');
	$this->db->from('class_master');
	$this->db->where('exam_form_permission','Y');
	//$this->db->where('class_master.result_permission', 'Y');
	$this->db->where('course_group_id',$course);
	$class_list = $this->db->get()->result_array();	
	//echo $this->db->last_query(); 	
		$data = array(
			'class_list' => $class_list,
			'all'=> true
		);
		echo $this->load->view('template/getclass',$data,true);
	}

	public function AllClassByCourseForResult()
	{
    $course = $this->input->post('course_group_id');
	$this->db->select('*');
	$this->db->from('class_master');
	$this->db->where('exam_form_permission','Y');
	$this->db->where('class_master.result_permission', 'Y');
	$this->db->where('course_group_id',$course);
	$class_list = $this->db->get()->result_array();	
	//echo $this->db->last_query(); 	
		$data = array(
			'class_list' => $class_list,
			'all'=> true
		);
		echo $this->load->view('template/getclass',$data,true);
	}

	public function getStudentListForMarksheet(){
		$data = $row = array();
	
		$where = array('center_id' => $this->session->center_id,
			 'result_show'=>'Y');
	

		if($_POST['course_group_id']!='All' and $_POST['course_group_id']!=''){
			$where['student.course_group_id'] = $this->input->post('course_group_id');
			
		}
		if($_POST['class_id']!='All' and $_POST['class_id']!=''){
			$where['class_id'] = $this->input->post('class_id');
		
		}
		$where['result_permission'] = 'Y';
		// Fetch member's records
		
		$column_order = array('student.student_id','enrollment_no','name','f_h_name','course_name','class_name',null);
		$column_search = array('student.student_id','enrollment_no','course_name','class_name','name','f_h_name');
	
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => 'student',
			'table2' => 'class_master',
			'joinOn' => 'student.class_id=class_master.id'
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		
		$i = $_POST['start'];
		foreach($tableData as $result){
			$btn =	'<a href="'.base_url('center/Center/marksheet/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>' ;
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
			"recordsTotal" => $this->Datatable_join_model->joincountAll($_POST,$DataTableArray),
			//"recordsTotal" => $this->Datatable_join_model->countAll('student',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);

// Output to JSON format
		echo json_encode($output);
	}


	public function marksheet($student_id="")
	{
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$student = $this->Common_model->getRecordByWhere("student",array('new_exam_form'=>'Y','result_show'=>'Y','student_id'=>$student_id));
		if (count($student)==0) {
			redirect(base_url());
		}
		$data['student']=$student[0];
		$classData = $this->Common_model->getRecordById('class_master','id',$data['student']->class_id);
		$data['practical_internal_marks']=$classData->practical_internal_marks;
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->where('new_exam_form.student_id',$data['student']->student_id); 
		$new_exam_form = $this->db->get()->result();
		$data['new_exam_form']  = $new_exam_form;
		$title = array('title' => 'Result - '.$data['student']->enrollment_no);
		$this->load->view('admin/generate_tr/header2',$title);	
		//$this->load->view('Centers/marksheet',$data);
		$this->load->view('Centers/marksheet_top',$data);
		$this->load->view('Centers/marksheet_bottom',$data);
		$this->load->view('admin/generate_tr/footer2');
	}

	public function exam_paper($student_id=''){
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->db->select('paper_master.*');
		$this->db->from('paper_master');
		$this->db->join('new_exam_form', 'paper_master.id = new_exam_form.paper_id');
		$class_id = $data['student']['class_id'];
		$where = array('paper_master.class_id' =>$data['student']['class_id'],
			'student_id' => $student_id,'paper_type'=>'theory'
		);
		$this->db->where($where);
		$data['papers'] = $this->db->get()->result();
		$whereClass = array('class_id' => $class_id,
			'exam_permission' => 'Y',
		);
		$timeTableData = $this->Common_model->getRecordByWhere('time_table',$whereClass);
		if((count($timeTableData)==0) || ($data['student']['new_exam_form']!='Y')){
			redirect(base_url());
		}
		$this->load->view('Centers/header',array('title' => 'Student Answer Sheet Status','page_slug' => 'exam_paper'));
		$this->load->view('students/exam_paper',$data);
		$this->load->view('Centers/footer');
	}



	public function upload_anwser_sheet($paper_id,$student_id=''){
		$paper_id = $this->Common_model->encrypt_decrypt($paper_id,'decrypt');
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$data['paperData'] = $this->Common_model->getRecordById('paper_master','id',$paper_id);
		$data['student'] = $this->Common_model->student_info($student_id);
		$this->load->view('Centers/header',array('title' => 'Upload Answer Sheet'));
		$this->load->view('students/upload_answer_sheet',$data);
		$this->load->view('Centers/footer');
	}


	public function upload_assignment_sub(){
		if($_FILES['file']['name']!='')
		{
			$ext1=strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
			$fname=$_POST['student_id']."_".$_POST['paper_code'];
			$document_image = $fname.".".$ext1;
			$date = date('Y-m-d');
			if (!is_dir('assets/exam_answersheet/'.$date)) {
				mkdir('assets/exam_answersheet/'.$date, 0777, TRUE);
			}
			$upload_file = move_uploaded_file($_FILES['file']['tmp_name'],"assets/exam_answersheet/".$date.'/'.$document_image);
			if($upload_file){
				$data = array('student_id' =>$_POST['student_id'],
					'course_group_id' =>$_POST['course_group_id'],
					'class_id' =>$_POST['class_id'],
					'paper_code' =>$_POST['paper_code'],
					'center_id' =>$_POST['center_id'],
					'answer_sheet' =>$document_image ,
					'upload_date' =>date("Y-m-d") ,
					'exam_status' => 'R',
					'file_exist' => 'Y'
				);
				$where = array(
					'class_id' => $_POST['class_id'],
					'student_id' => $_POST['student_id'],
					'paper_code' =>$_POST['paper_code']
				);
				$ansSheetCount = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
				if($ansSheetCount>0){
					$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
				}else{
					$insert = $this->Common_model->insertAll('upload_exam_ans_sheet',$data);
				}
			}
		}
		$student_id = $this->Common_model->encrypt_decrypt($_POST['student_id']);
		echo $student_id;
	}

	public function practical_marks_list(){
		redirect(base_url());
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData = array('title' => 'Practical  Marks Submission' );
		$this->load->view('Centers/header',$titleData);
		$center_id =  $this->session->center_id;
		$where = array('center_id' => $center_id,'new_exam_form' => 'Y','result_show' => 'N');
		$this->db->order_by("p_marks_sub,student.course_group_id,student.class_id", "asc");
		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('class_master', 'student.class_id = class_master.id');
		$this->db->Where($where);
		$this->db->Where('(project="Y" or practical = "Y")');
		$data['students'] = $this->db->get()->result();
		$this->load->view('Centers/practical_marks_no_list',$data);
		$this->load->view('Centers/footer');
	}

	public function load_student_practical_assignment (){
		$student_id = $this->input->post('student_id');
		$where=array('student.student_id'=>$student_id,
			'paper_type!='=>'theory', );
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->join('paper_master', 'paper_master.id = new_exam_form.paper_id');
		$details = $this->db->get()->result();
		$data = array(
			'details' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('Centers/view_student_practical_data',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));
	}

	public function practical_assignment_marks_sub()
	{
		$data=array();
		$post = $this->input->post();
		$data['paper_id'] = $this->input->post('paper_id');
		$data['marks'] = $this->input->post('marks');
		foreach ($data['paper_id'] as $key => $value){
			$studentData = array('p_marks' => $data['marks'][$key]);
			$where =  array(
				'paper_id' =>$value,
				'student_id'  =>$_POST['student_id']
			);
			$this->Common_model->updateRecordByConditions('new_exam_form',$where,$studentData);
		}
		$where1 =  array('student_id'  => $_POST['student_id'] );
		$Data = array('p_marks_sub' => 'Y');
		$Marksentry1 = $this->Common_model->updateRecordByConditions('student',$where1,$Data);
		$sts_btn = '<button  class="btn btn-info btn-sm font-weight-bold view"  data-toggle="modal" data-target="#kt_datepicker_modal"  data-id = '.$_POST['student_id'].'"
		onclick="view_mark('.$_POST['student_id'].'")">view</button>';
		if($Marksentry1){
			$dt =  "Marks Submited";
		}else{
			$dt = "Error";
		}
		echo json_encode(array(
			"data" => $sts_btn,
			'msg'=>  $dt,
		));
	}

	public function view_student_marks(){
		 	$student_id = $this->input->post('student_id');
			$class_id = $this->input->post('class_id');
			$classData	= $this->Common_model->getRecordById('class_master','id',$class_id); 
		 	$where=array('student.student_id'=>$student_id,);
		 	$this->db->select('*');
		 	$this->db->from('new_exam_form');
		 	$this->db->Where($where );
		 	$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		 	$details = $this->db->get()->result();
		 	$data = array(
				'classData' =>$classData,
		 		'detail' => $details,
		 		'name_csrf' => $this->security->get_csrf_token_name(),
		 		'hash_csrf' => $this->security->get_csrf_hash(),
		 	);
		 	if($data){
		 		$model =  $this->load->view('Centers/student_marks_no_data',$data,true);
		 		$status = true;
		 	}
		 	echo json_encode(array(
		 		"status" => $status,
		 		"data" => $model
		 	));
	}

	public function instruction_private(){

		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Private Course Fees Structure');
			$this->load->view('Centers/header',$titleData);
			$center_id =  $this->session->center_id;
			$centerdata = $this->Common_model->getRecordById('center','id',$center_id);
			$this->db->where('id in ('.$centerdata->allot_course_group_id.')');
			$course_group_list = $this->Common_model->get_record('course_group','*',array('status !=' => 'D' ,'admission_permission_pvt'=>'Y'));
			$data = array('course_group' => $course_group_list);
			$this->load->view('Centers/instruction_private',$data);
			$this->load->view('Centers/footer');
		}
	}


	public function update_unpaid_student(){
			
		if ($this->input->method() == "post") 
		{  

		    
			$payment_date  = $this->input->post("payment_date");
			$student_id  = $this->input->post("student_id");
	      	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
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
				'payment_date' => $payment_date,
				'remark' => $remark,
				'payment_mode' => $payment_mode,
				'amount' => $amount,
				'image' => $file_name,
				'payment_status' => "Paid By University",
				'payment' => 'Y'
			);
		
			$where = array(
				'fees_head'=>'Admission Fees',
				'student_id'=> $student_id
			);
			$update = $this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$updateData);
			$response = $this->Common_model->updateRecordByConditions('student',array('student_id'=> $student_id),array('payment_status'=>'Y'));

			if($response){
			echo json_encode(array("status" => 'true'));
			}
		}
	}
	
}
