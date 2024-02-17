<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Center extends CI_Controller {
 public $master = array();
 public $exam_table;
 public $exam_form;
 public $roll_no ;
 public $exam_form_table;
	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Center/center_model');
		$this->load->model('Datatable_join_model');
		$this->master = $this->Common_model->getSingleRow('master');
		 $this->exam_table = $this->master->student_exam_table;
		 $this->exam_form = $this->master->exam_form_col;
		 $this->exam_form_result = $this->master->exam_form_col_result;
		 $this->roll_no = $this->master->roll_number_col;
		 $this->result_table = $this->master->student_result_table;
		 $this->exam_form_table = $this->master->exam_form_table;
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

	public function course_wise_paper($course_id,$mode,$sem_mode){
    	
    	$titleData = array('title' => 'Class Wise Papers');
    	$this->load->view('Centers/header',$titleData);
		if($sem_mode=="Month")$sem_mode="Semester";
    	$Data['classData'] = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=> $course_id,'mode'=>$sem_mode));
		// $this->Common_model->last_query();
		$Data['mode'] = $mode;
    	$this->load->view('Centers/course_wise_paper',$Data);
    	$this->load->view('Centers/footer');
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
		$center_ids = array( 10,11,12,13,21,22,23,24,25,26,27,28,29 );
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
		$center_data = $this->Common_model->getRecordByWhere('center',array('id'=>$center_id));
		$center_session_permission = $center_data[0]->old_session_permission;
		$center_ids_dep = array(21,22,23,24,25,26,27,28,29);
		$whereSession = array();
		if (in_array($center_id, $center_ids_dep)){
			$passing_exam_year = '2023';
			$whereSession['admission_permission_dep'] =  'Y';
			if($center_session_permission =='N'){
				$this->db->order_by("id", "desc");
				$this->db->limit(1);
			}
			
		}else{
			// $passing_exam_year = '2021';
			$passing_exam_year = '2023';
			if($center_session_permission!='Y')
			{
				$whereSession['admission_permission_ic'] =  'Y';
			}
			
			
		}
		if($mode=='regular' ){
			$where = array('admission_permission'=>'Y' ,'id'=>$center_id);
			$head = '(Regular)';
		}elseif($mode=='private' ){
			$where = array('admission_permission_private'=>'Y','id'=>$center_id);
			$head = '(Private)';
			if($center_session_permission!='Y')
			{
				$whereSession['pvt_admission_permission_ic'] =  'Y';
			}		
		}else{
			redirect(base_url('dashboard'));
		}
		
		$sessions = $this->Common_model->get_record('session','*',$whereSession);
		
		$check = $this->Common_model->getRecordByWhere("center",$where);
		
		$center_id =  $this->session->center_id;
		$center_ids_dep = array(10,11,12,21,22,23,24,25,26,27,28,29,13);

		// if(($mode=='regular' && $check[0]->admission_permission!='Y' && !in_array($center_id, $center_ids_dep)) || ($mode=='private' && $check[0]->admission_permission_private!='Y')){
		// 	redirect(base_url('dashboard'));
		// }
		if(($mode=='regular' && $check[0]->admission_permission!='Y' ) || ($mode=='private' && $check[0]->admission_permission_private!='Y')){
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
			'passing_exam_years' =>$passing_exam_year,

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
		if($course!=36 && $course!=37  ){
			if($student_mode=="private"){
				$this->db->where('course_group.private_mode=class_master.mode');
			 }else{
				$this->db->where('class_master.mode=course_group.mode');
			 }
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
			// 'center_id' => $center_id,
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
		
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		$center_ids_dep = array( 21,22,23,24,25,26,27,28,29);
		foreach($tableData as $result){
			// $btn = ($result->document_uploaded=='Y' || in_array($this->session->center_id, $center_ids_dep)) ?
			// '<a href="'.base_url('show_form/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>' : '';
			$btn = '<a href="'.base_url('show_form/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm" target="_blank" title="Form"><i class="fa fa-eye text-white"></i></a>';
			 $btn1 = ($result->temp_exam_form=='Y')?
				'<a target="_blank"  class="btn btn-info btn-sm" href="'.base_url('center/Center/showPapers/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" title="Papers"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>':'';
			$i++;

			if($result->enrolled=='N'){
				$enrollment = '-';
			}else{
				$enrollment = $result->enrollment_no;
			}
	
			$data[] = array($i,$result->student_id,$enrollment,$result->name, $result->f_h_name, $result->course_name,$result->class_name,$btn,$btn1);
		}
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsTotal = $this->Datatable_join_model->countAll('student',$where);
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
		);

// Output to JSON format
		echo json_encode($output);
	}

	

	public function student_list($param1 = '')
	{
		$course_type = $this->uri->segment(3); 
		$late_admission_fees_pvt = $this->Common_model->getRecordByWhere('master');
		$csrf = array( 
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'course_type' => $course_type,
			'late_privte_admission_fees' => $late_admission_fees_pvt[0]->p_late_fee_status
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
		
		$centerData = $this->Common_model->getRecordById('center','id',$this->session->center_id);
		
		$where = 'online_payment_transaction.payment!="Y"';
		
		if($param1=='Admission'){
			

			$permission_session= $this->Common_model->getRecordByWhere('session',array('unpaid_permission'=>'Y' ));
			$where .= " and online_payment_transaction.fees_head='Admission Fees'  and  student.payment_status='N'  and ( "; //and student.class_name not like '%SEM%'
			foreach($permission_session as $key=>$row){
			
				if($row->semester_permission=='N' && $row->annual_permission=='Y' )
				$where.=" (student.class_name not like '%SEM%' and student.session='".$row->session."') or ";
				else if($row->annual_permission=='N' && $row->semester_permission=='Y')
				$where.="  (student.class_name not like '%YEAR%' and student.session='".$row->session."') or ";
				else if($row->annual_permission=='Y' && $row->semester_permission=='Y')
				$where.="   session='".$row->session."'";
				
			}
			
			
			$where .= "  ) "; 
				//stop admission of class
				 $master = $this->Common_model->getSingleRow('master');
				//  echo $centerData->temp_admission_payment ;die;
				 if(!empty($master->remove_class_from_center) && $centerData->temp_admission_payment =='N')
				 $where.=" and `student`.`class_id` NOT IN ($master->remove_class_from_center)";
			// $where.=" or (student.student_id in (715231, 715241, 716487, 717657, 717662, 722810) and online_payment_transaction.payment='N' )";
			
			// $where .= " and online_payment_transaction.fees_head='Admission Fees'  and  `student.payment_status`='N' and ( (student.class_name not like '%SEM%' and student.session='July 2021') or session!='July 2021')";
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
		
		 if ($this->session->center_id!=13) {
			$this->db->where('online_payment_transaction.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('online_payment_transaction.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		
		$i = $_POST['start'];
		
		if ($this->session->center_id!=13) {
			$this->db->where('online_payment_transaction.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('online_payment_transaction.center_id',array( 21,22,23,24,25,26,27,28));
		}
		
		$counttableData = $this->Datatable_join_model->joincountAll($_POST,$DataTableArray);
				  
		foreach($tableData as $result){
			$center_ids_dep = array( 10,11,12,13,20,21,22,23,24,25,26,27,28,29,1975,2098,2115);
			if(in_array($this->session->center_id, $center_ids_dep)){
				$modal ='<a href="#"  data-student_name = "'.$result->name.'"  data-student_id="'.$this->Common_model->encrypt_decrypt($result->student_id).'" class="btn btn-primary btn-sm font-weight-bold pay1" data-toggle="modal" data-target="#kt_datepicker_modal" "  data-amount= "'.$result->amount.'">Receive</a>';
			}else{
			 $modal = '<a href="#" data-student_id="'.$this->Common_model->encrypt_decrypt($result->student_id).'" data-id="'.$this->Common_model->encrypt_decrypt($result->id).'" class="btn btn-info btn-sm pay" >Pay</a>';
			}
			
			$i++;
			$data[] = array($i,$result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$result->amount,$modal);
		}

		if ($this->session->center_id!=13) {
			$this->db->where('online_payment_transaction.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('online_payment_transaction.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $counttableData,//$this->Datatable_join_model->countAll('online_payment_transaction',$where),
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}


	public function getPaidFeesList(){
		$data = $row = array();
		$where = 'online_payment_transaction.payment="Y"';

		$column_order = array('student.university_mode,student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','txnId',null);
		$column_search = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','fees_head','amount','txnId');
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
		if ($this->session->center_id!=13) {
			$this->db->where('online_payment_transaction.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('online_payment_transaction.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$btn = '<a href="'.base_url('show_fees/'.$this->Common_model->encrypt_decrypt($result->id)).'" class="btn btn-primary btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>';
			$i++;
			$university_mode = ($result->university_mode=='REG') ? 'Regular' : 'Private';
			$data[] = array($i,$university_mode, $result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$result->fees_head,$result->amount,$result->txnId,$btn);
		}
		if ($this->session->center_id!=13) {
			$this->db->where('online_payment_transaction.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('online_payment_transaction.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsTotal = $this->Datatable_join_model->countAll('online_payment_transaction',$where);
		if ($this->session->center_id!=13) {
			$this->db->where('online_payment_transaction.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('online_payment_transaction.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
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
		$this->db->group_start();
		$this->db->where('course_group_id in ('.$centerdata->allot_course_group_id.')');
		$this->db->group_end();
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
		$this->db->group_start();
		$this->db->where('eligibility',$eligibility);
		$this->db->where('course.session',$session);
		if($mode=='REG' || $mode=='regular'){
			$where['admission_permission_regular'] = 'Y';
			$this->db->where('admission_permission_regular','Y');
		  }else{
			 $where['admission_permission_private'] = 'Y';
			 $this->db->where('admission_permission_private','Y');
		  }
		  $this->db->group_end();
		  if($center_id == 11 || $center_id == 13 || $center_id == 2115 || $center_id == 1707 ){
			$this->db->or_group_start();
		  $this->db->or_where_in('course_group.id',array(33,45));
		  $this->db->where(array('eligibility' => $eligibility ,'course.session'=>$session));
		  $this->db->group_end();
		 
		  }
		$query = $this->db->get();
		$course_group_list= $query->result_array();
		
		$data = array('course_group_list'=>$course_group_list);
		echo $this->load->view('template/getcourse',$data,true);
	}

	public function checkDuplicateAdharNo()
	{
		$adhar_no = $this->input->post('adhar_no');
		$where = array('adhar_no'=>$adhar_no,'course_complete'=>'N','new_admission_permission'=>'N');
		$count = $this->Common_model->getCountByWhere('student',$where);
		if($count>0){
			echo "Duplicate Adhar Card Number";
		}
	}
	public function checkDuplicateMobileNo()
	{
		$p_mobile_no = $this->input->post('p_mobile_no');
		$count = $this->db->query("select * from student_data as d join student as s on s.student_id=d.student_id where s.course_complete='N' and s.new_admission_permission='N' and d.p_mobile_no = '".$p_mobile_no."' limit 1")->num_rows();
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
		$where = $course_type_where." session='".$session."'";
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
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
		$where='';
		$course_group_id = $this->input->post('course_group_id');
		$session_course_type=$this->input->post('session_course_type');
		$mode=$this->input->post('mode');
		if(@$session_course_type){
			$where.="session = '".$session_course_type ."' and ";
		}
		$where .= "course_group_id = ".$course_group_id." and enrolled = 'N' and university_mode='".$mode."'";
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$student_list = $this->Common_model->get_record('student','student_id as id,name',$where);
		$data = array('student_list' => $student_list,);
		echo $this->load->view('template/getStudent',$data,true);
	}

	public function create_form_edit_request(){
		$session_id = $this->input->post('session_id');
		$course_group_id  = $this->input->post('course_group_id');
		$student_id = $this->input->post('student');
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$check_record = $this->Common_model->get_record('request','*',array('student_id' => $student_id,'status'=>'Pending'));
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
		$where = 'type="admission" ';

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
		if ($this->session->center_id!=13) {
			$this->db->where('payment_complaint.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('payment_complaint.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$i++;
			$date = $this->Common_model->viewDate($result->date);
			$status = ($result->status=="Pending") ? 'Pending' : 'Done';
			$data[] = array($i, $result->name, $result->student_id, $result->course_name,$result->class_name,$result->details,$date,$status,$result->remark);
		}
		if ($this->session->center_id!=13) {
			$this->db->where('payment_complaint.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('payment_complaint.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsTotal = $this->Datatable_join_model->countAll('payment_complaint',$where);
		if ($this->session->center_id!=13) {
			$this->db->where('payment_complaint.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('payment_complaint.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
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
			$course_type_where .=" student.university_mode='".$course_type."'  ";	
		$this->db->order_by("id", "DESC");
		$data = $row = array();
		$column_order = array(null,'name','student.student_id','detail','date','status','request_remark');
		$column_search = array('name','student.student_id','detail','date','status','request_remark');
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'select' => 'request.request_remark,request.student_id, request.date, request.detail, name, request.status',
			'where' => $course_type_where,
			'table' =>  'request',
			'table2' => 'student',
			'joinOn' => 'request.student_id=student.student_id'
		);
		if ($this->session->center_id!=13) {
			$this->db->where('request.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('request.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$i++;
			$status = ($result->status=='Pending') ? 'Pending' : 'Done';
			$date = $this->Common_model->viewDate($result->date);
			$data[] = array($i, $result->name, $result->student_id, $result->detail,$date,$status,$result->request_remark);
		}
		if ($this->session->center_id!=13) {
			$this->db->where('request.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('request.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsTotal = $this->Datatable_join_model->countAll('request',$where);
		if ($this->session->center_id!=13) {
			$this->db->where('request.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('request.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
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

		// $where = array(
		// 	'approved' =>'N',
		// 	'center_id' => $center_id,
		// 	'university_mode' =>$course_type,
		// );
		// $data['students'] = $this->Common_model->getRecordByWhere('student',$where);
		// echo $this->db->last_query(); //die;
		$where=" AND ( ";
		$permission_session= $this->Common_model->getRecordByWhere('session',array('document_permission'=>'Y' )); 
		foreach($permission_session as $key=>$row){
			
			if($row->semester_permission=='N' && $row->annual_permission=='Y' )
			$where.=" (student.class_name not like '%SEM%' and student.session='".$row->session."') or ";
			else if($row->annual_permission=='N' && $row->semester_permission=='Y')
			$where.="  (student.class_name not like '%YEAR%' and student.session='".$row->session."') or ";
			else if($row->annual_permission=='Y' && $row->semester_permission=='Y')
			$where.="   session='".$row->session."'";
			
		}
		
		
		$where .= " ) "; 
		
		if ($this->session->center_id!=13) {
			$sql="SELECT * FROM `student`  WHERE approved = 'N' AND center_id = '".$center_id."' AND university_mode='".$course_type."'  ".$where; 
		}else{
			$sql="SELECT * FROM `student`  WHERE approved = 'N' AND center_id in (21,22,23,24,25,26,27,28) AND university_mode='".$course_type."'  ".$where; 
		}
		
		$query = $this->db->query($sql);
		
		$data['students'] =  $query->result();
		
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
				// $document=array();
				$doc = array();
				$document_required = $this->Common_model->getRecordByWhere('admission_document',array('student_id'=>$student_id));
				foreach($document_required as $doc_req){
					array_push($doc,$doc_req->document_category_id);
				}
				$this->db->where_in('id',$doc);
				$document = $this->Common_model->getRecordByWhere('document_category');
				
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
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);

      $classpermission = $this->Common_model->get_record('class_master','id',array('exam_form_permission'=>'Y'));
  		$class_ids = array_column($classpermission, 'id');
		$center_id =  $this->session->center_id;
		$centerData = $this->Common_model->getRecordById('center','id',$this->session->center_id);
		$master = $this->Common_model->getSingleRow('master');
		//$center_permission = $this->Common_model->get_record('center','exam_form_permission,temp_exam_form',array('id'=>$center_id));
		//if($center_permission[0]['temp_exam_form']=='N'){
		if($centerData->temp_exam_form=='N'){	
			$this->db->where_in('class_id',$class_ids);
		}
		//stop admission of class
		
		
		
		if($exam_form1=='submitted' ){
			if ($this->session->center_id!=13) {
				$this->db->where('center_id',$this->session->center_id);
			}else{
				$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
			}
			$where = array('new_exam_form' =>'Y');
			$data['documents'] = $this->Common_model->getRecordByWhere('student',$where);
		}else if($exam_form1 =="notSubmitted"){
			if($centerData->exam_form_permission!='Y'){
				$data['documents'] ="";
			}else{
				$where = array(
					'new_exam_form' =>'N',
				);
				if ($this->session->center_id!=13) {
					$this->db->where('center_id',$this->session->center_id);
				}else{
					$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
				}
				
		
				if(!empty($master->remove_class_from_center) && $centerData->temp_exam_form =='N'){
					$remove_classes=explode(',',$master->remove_class_from_center);
					$this->db->where_not_in('student.class_id', $remove_classes );
				
				}
			//	$this->db->where_in('class_id',array( 256,258,268,270));
				$data['documents'] = $this->Common_model->getRecordByWhere('student',$where);
				
			}
		}else if($exam_form1=="skipped"){
			$where = array(
				'new_exam_form' =>'S',
				
			);
			if ($this->session->center_id!=13) {
				$this->db->where('center_id',$this->session->center_id);
			}else{
				$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
			}
			$data['documents'] = $this->Common_model->getRecordByWhere('student',$where);
		}
		$data['exam_form_button'] = $exam_form1;
		
		//$data['documents'] = $this->Common_model->getRecordByWhere('student',$where);
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
			$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
			$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
			$this->db->select('paper_master.*,new_exam_form.sub_group_id as sub_group');
			$this->db->from('paper_master');
			$this->db->order_by('new_exam_form.sub_group_id,paper_master.cbcs_paper,paper_order');
			$this->db->join('new_exam_form', 'paper_master.paper_code = new_exam_form.paper_code and  paper_master.class_id = new_exam_form.class_id');
			$where = array('paper_master.class_id' => $student['class_id'],
				'student_id' => $student_id,'cbcs_paper'=>$cbcs
			);
			$this->db->where($where);
			$data['papers'] = $this->db->get()->result();
            $data['name_csrf']= $this->security->get_csrf_token_name();
            $data['hash_csrf'] = $this->security->get_csrf_hash();
            
			// $this->Common_model->last_query();
			$this->load->view('Centers/showPapers',$data);
			$this->load->view('Centers/footer');
		
    }

    public function paper_missing_list($mode=''){
    	if(!$this->session->has_userdata('centerdata')){
    		redirect(base_url());
    	}


    	$center_id =  $this->session->center_id;
    	if($mode == "regular"){
    		$titleData = array('title' => 'Paper Missing List (Regular)' );
    		$where = array(
    			'temp_exam_form' =>'N',
    			'university_mode'=>'REG',
				'class_name!='=>'II Year',
    		);
    	}else{
    		$titleData = array('title' => 'Paper Missing List (Private)' );
    		$where = array(
    			'temp_exam_form' =>'N',
    			'university_mode'=>'PVT',
				'class_name!='=>'II Year',
    		);
    	}
    	$this->load->view('Centers/header',$titleData);
    	if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
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
		$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
		$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
		if($student['temp_exam_form'] == "Y"){
			$std_id = $this->Common_model->encrypt_decrypt($student_id);
			redirect(base_url('center/center/showPapers/'.$std_id.''));	
		}
		$this->db->order_by('id');
		if($student['university_mode'] != "PVT"){
		
		$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory" and cbcs_paper="'.$cbcs.'"');
		$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id join paper_master as m on m.id=p.paper_id where g.class_id='.$student['class_id'].' and cbcs_paper="'.$cbcs.'"  Order by g.id,p.sub_group_id,p.id')->result();
		//echo $this->Common_model->last_query();
		}else{
			$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory" and type="theory" and cbcs_paper="'.$cbcs.'"');
			 $this->db->select('p.*,g.group_name') ;
			 $this->db->from('group_paper as p');
			 $this->db->join('group as g','g.id = p.group_id');
			 $this->db->join('paper_master','paper_master.id = p.paper_id') ;
			$this->db->where(array('g.class_id'=>$student['class_id'],'paper_master.type'=>"theory", 'cbcs_paper'=> $cbcs));
			$groupPaper =$this->db->get()->result();	
		
		}
		
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
		}
		else{
			$this->load->view('Centers/select_group',$data);
		}
		$this->load->view('Centers/footer');

	}

	public function submit_papers(){
		$student_id=$this->Common_model->encrypt_decrypt($_POST['student_id'],'decrypt');
		$mode = $this->Common_model->getRecordById('student','student_id',$student_id);
		$paper_id1 = $_POST['paper_id'];
		$paper_id2 = $_POST['compulsary_paper_id'];
		$paper_id= array_merge($paper_id1,$paper_id2);
		$paper_id = implode(",",$paper_id);
		$classData = $this->Common_model->getRecordById('class_master','id', $mode->class_id);
		$cbcs = ($classData->cbcs == 'Y')?'Y':'N';
		
		if($mode->university_mode == "PVT"){
			
		$paper_data = 	$this->Common_model->get_record('paper_master','*','id in ('.$paper_id.') and type="theory" and cbcs_paper="'.$cbcs.'"');
		
		}else{
			$paper_data = 	$this->Common_model->get_record('paper_master','*','id in ('.$paper_id.') and cbcs_paper="'.$cbcs.'"');	
		}
		foreach($paper_data as $paper){
			$data['course_group_id']=$paper['course_group_id'];
			$data['class_id']=$paper['class_id'];
			$data['paper_code']=$paper['paper_code'];
			$data['paper_type']=$paper['type'];
			$data['book_code']=$paper['book_code'];
			$data['paper_id']=$paper['id'];
			$data['paper_order']=$paper['paper_no'];
			$data['sub_group_id']=$paper['sub_group_id'];
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
		
		$paper_code = $_POST['compulsary_paper_code'];
		$class_id = $_POST['class_id'];
		$student_id=$this->Common_model->encrypt_decrypt($_POST['student_id'],'decrypt');
		$mode = $this->Common_model->getRecordById('student','student_id',$student_id);
		$classData = $this->Common_model->getRecordById('class_master','id', $mode->class_id);
		//$cbcs = ($classData->cbcs == 'Y')?'Y':'N';
		$cbcs = ($classData->cbcs == 'Y' && $mode->exam_pattern=="GRADE")?'Y':'N';
		$i = 1;
		$this->db->where_in('paper_code',$paper_code);
		$this->db->where('class_id',$class_id);
		$paper_data = $this->Common_model->get_record('paper_master','*',array('type' => "theory",'cbcs_paper'=>$cbcs));
		
		foreach($paper_data as $paper){
			$data['course_group_id']=$paper['course_group_id'];
			$data['class_id']=$paper['class_id'];
			$data['paper_code']=$paper['paper_code'];
			$data['paper_type']=$paper['type'];
			$data['book_code']=$paper['book_code'];
			$data['paper_id']=$paper['id'];
			$data['student_id']=$student_id;
			$data['paper_order']=$i;
			$data['sub_group_id']=$paper['sub_group_id'];
			$insert = $this->Common_model->insertAll('new_exam_form',$data);
			$i++;
		}

		if(isset($_POST['group_id'])){
			$group_id = $_POST['group_id'];
			$this->db->select('group_paper.paper_code,group_paper.sub_group_id');
			$this->db->from('group_paper');
			if($mode->university_mode == "PVT"){
			$this->db->join('paper_master','paper_master.id = group_paper.paper_id');
			$this->db->where(array('paper_master.type'=>"theory" ));
			}
			// else{
			// 	$this->db->where(array('cbcs_paper'=>$cbcs ));
			// }
			$this->db->where_in('group_id',$group_id);
			
			$groupPaperData = $this->db->get()->result_array();

			$groupPaperCodes = array_column($groupPaperData, 'paper_code');
			$this->db->where_in('paper_code',$groupPaperCodes);
			$this->db->where('class_id',$class_id);
			$this->db->where('cbcs_paper',$cbcs );
			$this->db->order_by('paper_no');
			$papers = $this->Common_model->get_record('paper_master','*');
			foreach($papers as $paper){
				$data['course_group_id']=$paper['course_group_id'];
				$data['class_id']=$paper['class_id'];
				$data['paper_code']=$paper['paper_code'];
				$data['paper_type']=$paper['type'];
				$data['book_code']=$paper['book_code'];
				$data['paper_id']=$paper['id'];
				$data['student_id']=$student_id;
				$data['paper_order']=$i;
				$debug =  array_search($paper['paper_code'], $groupPaperCodes);
				$data['sub_group_id'] = $groupPaperData[$debug]['sub_group_id'];
				$insert = $this->Common_model->insertAll('new_exam_form',$data);
				$i++;
			}
		}

		if($insert){
			$data = array('temp_exam_form'=>'Y');
			if (isset($_POST['group_id'])) {
				$data['group_id'] = implode(',', $_POST['group_id']);
			}
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
		$titleData = array('title' => 'Admit Card List 2024' );
		$this->load->view('Centers/header',$titleData);
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		//'center_id'=>$this->session->center_id , 
		$where = array('admit_card_permission' =>'Y',"student.roll_no!="=>0);
        // $this->db->where_not_in('student_id', array(374292,374779,379155,379652,380605,380673,381026,382024,385894,685803,686581,686621,687158,687165,687390,687395,687622,722149));
        $this->db->select('DISTINCT(student.class_id) as
			class_id,course_name,student.class_name,class_id');
		$this->db->from('student');
		$this->db->Where($where);
		$this->db->join('class_master', 'class_master.id = student.class_id');
		$this->db->order_by("student.course_name,student.class_id");
		$data['students'] = $this->db->get()->result();
		// echo $this->db->last_query();
		 //  $this->Common_model->last_query();
		$this->load->view('Centers/class_wise_admit_card',$data);
		$this->load->view('Centers/footer');
	}

	


	public function admit_card_student_list($class_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$class_id=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$titleData = array('title' => 'Admit Card Student List 2024' );
		$this->load->view('Centers/header',$titleData);
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		//$center_id =  $this->session->center_id;
		$where = array(
			'class_id' =>$class_id,
			//'center_id' => $center_id,
			'roll_no!=' => 0,
			'new_exam_form' => 'Y'
		);
        // $this->db->where_not_in('student_id', array(374292,374779,379155,379652,380605,380673,381026,382024,385894,685803,686581,686621,687158,687165,687390,687395,687622,722149));
        $data['students'] = $this->Common_model->getRecordByWhere('student',$where);
		$this->load->view('Centers/class_wise_admit_card_list',$data);
		$this->load->view('Centers/footer');
	}

	public function admit_card_backlog_student_list(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$titleData = array('title' => 'Admit Card Backlog Student List 2024' );
		$this->load->view('Centers/header',$titleData);
		//$center_id =  $this->session->center_id;
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$where = array(	
			//'center_id' => $center_id,
			'roll_no!=' => 0,
			'exam_form' => 'Y',
			'exam_year' => 'Dec 2023'
		);
		$this->db->order_by('course_group_id,class_id');
        // $this->db->where_not_in('student_id', array(188428,686377,375381));
		$data['students'] = $this->Common_model->getRecordByWhere('backlog_student',$where);
		$this->load->view('Centers/class_wise_backlog_admit_card_list',$data);
		$this->load->view('Centers/footer');
	}

	public function admit_card($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$en_student_id = $student_id;
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$titleData = array('title' => 'Admit Card 2024' );
		$this->load->view('Centers/header',$titleData);
		//$center_id =  $this->session->center_id;
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$where = array(
			'student_id' => $student_id,
			'roll_no !=' => 0,
			//'center_id' => $center_id,
			'new_exam_form' => 'Y',
		);

		$this->db->select('*');
		$this->db->from('student');
		
		$this->db->where($where);
		$data['student'] = $this->db->get()->result();
		if ($data['student'][0]->temp_exam_form=='N') {
			redirect(base_url('select_papers/'.$en_student_id));
		}
		$wherePaper = array('student_id' => $student_id,'paper_master.type'=>'theory','paper_master.class_id'=>$data['student'][0]->class_id,'paper_master.course_group_id'=>$data['student'][0]->course_group_id,'paper_master.exam_date!=' =>'0000-00-00');
		$this->db->select('*');
		$this->db->from('paper_master');
		$this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
		
		$this->db->where($wherePaper);
		$this->db->order_by("exam_date", "asc");
		$this->db->order_by("exam_shift", "desc");
		$data['papers'] = $this->db->get()->result();
		//echo $this->db->last_query(); die;
		$this->load->view('template/admit_card',$data);
		$this->load->view('Centers/footer');
	}

	public function backlog_admit_card($backlog_student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		// $en_student_id = $student_id;
		$backlog_student_id=$this->Common_model->encrypt_decrypt($backlog_student_id,'decrypt');
		$titleData = array('title' => 'Backlog Admit Card 2024' );
		$this->load->view('Centers/header',$titleData);
		//$center_id =  $this->session->center_id;
		if ($this->session->center_id!=13) {
			$this->db->where('backlog_student.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('backlog_student.center_id',array( 21,22,23,24,25,26,27,28));
		}

		$where = array(
			'backlog_student.id' => $backlog_student_id,
			'backlog_student.roll_no !=' => 0,
			//'backlog_student.center_id' => $center_id,
			'backlog_student.exam_form' => 'Y',
			'backlog_student.exam_year'=>'Dec 2023'
		);
        // $this->db->where_not_in('backlog_student.student_id', array(188428,686377,375381));
		$this->db->select('backlog_student.*,student.name,student.f_h_name,student.session,student.photo');
		$this->db->from('backlog_student');
		$this->db->join('student','student.student_id = backlog_student.student_id');
		
		$this->db->where($where);
		$data['student'] = $this->db->get()->result();
		// print_r($data['student'] );
		
		$wherePaper = array('backlog_student_id' => $backlog_student_id,'paper_master.type'=>'theory','paper_master.class_id'=>$data['student'][0]->class_id,'paper_master.course_group_id'=>$data['student'][0]->course_group_id,'status'=>'B','backlog_student_id'=>$data['student'][0]->id,'paper_master.exam_date!=' =>'0000-00-00');
		$this->db->select('*');
		$this->db->from('paper_master');
		$this->db->join('backlog_exam_form', 'backlog_exam_form.paper_code = paper_master.paper_code and backlog_exam_form.class_id = paper_master.class_id');
		
		$this->db->where($wherePaper);
		$this->db->order_by("exam_date", "asc");
		$this->db->order_by("exam_shift", "desc");
		$data['papers'] = $this->db->get()->result();
		// echo $this->db->last_query(); die;
		$this->load->view('template/backlog_admit_card',$data);
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
        if($student_data[0]->demo == 'Y'){
            $total_fees = $fees[0]->exam_fees;
        }else{
            $total_fees =$fees[0]->program_fees+$fees[0]->exam_fees;
        }
		$data['student_id']=$student_data[0]->student_id;
		$data['center_id']=$student_data[0]->center_id;
		$data['exam_session'] = "Dec 2023";
		$data['course_group_id']=$student_data[0]->course_group_id;
		$data['class_id']=$student_data[0]->class_id;
		$data['amount'] = $total_fees;
		$data['fees_head']='Exam Fees';
		$data['student_name']=$student_data[0]->name;
		$data['payment']='Y';
		$data['payment_status']='Paid By University';
		$data['payment_date']= $this->input->post('payment_date');
        $data['receipt_number']= $this->input->post('receipt_number');
		$data['admission_type']= 'Regular';
		$data['payment_time']=date("h:i:s");
		$insert = $this->Common_model->insertAll('online_payment_transaction',$data);
		$student_data = array('new_exam_form' => 'Y');
		$update = $this->Common_model->updateRecordByConditions('student','student_id='.$student_id,$student_data);
       
		if($update){
            echo json_encode(array(
                "status" => 'success',
                "data" => 'exam_form_students'
            ));
			// redirect(base_url('exam_form_students'));
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
		// if($this->session->center_id!=12 && $this->session->center_id!=28){
	 	 	 redirect(base_url());
		// }
	 	if(!$this->session->has_userdata('centerdata')){
	 		redirect(base_url());
	 	}
	 	$data = array(
	 		'name_csrf' => $this->security->get_csrf_token_name(),
	 		'hash_csrf' => $this->security->get_csrf_hash(),
	 	);

	 	$titleData = array('title' => 'Regular Internal Marks Submission' );
	 	$this->load->view('Centers/header',$titleData);
	 	$center_id =  $this->session->center_id;
	 	$where = array('university_mode' => 'REG','center_id' => $center_id, $this->exam_form => 'Y','internal'=>"Y",'old_result_show ' => 'N');
	 	// ,"demo"=>'N' ,'class_id'=>264
	 	$this->db->order_by("int_marks_sub,".$this->exam_table.".course_group_id,".$this->exam_table.".old_class_id", "asc");
	 	$this->db->select('*');
	 	$this->db->from($this->exam_table);
		 $this->db->join('class_master', ''.$this->exam_table.'.old_class_id = class_master.id');
	 	$this->db->Where($where);
		
	 	$data['students'] = $this->db->get()->result();//echo $this->db->last_query(); die;
	 	$this->load->view('Centers/student_marks_no_list',$data);
	 	$this->load->view('Centers/footer');
	}

	public function load_student_assignment(){
	 	$student_id = $this->input->post('student_id');
		 $class_id = $this->input->post('old_class_id');
		$classData	= $this->Common_model->getRecordById('class_master','id',$class_id);
	 	//$where=array('student.student_id'=>$student_id,'paper_type'=>'theory');
	 	$this->db->select('*');
	 	$this->db->from('new_exam_form');
	 	//$this->db->Where($where );
		$this->db->where(''.$this->exam_table.'.student_id',$student_id);
		$this->db->where('new_exam_form.class_id',$class_id);
		if($classData->practical_internal_marks=="N")
			// $this->db->where('paper_type','theory');
		$this->db->where_in('paper_type',array('Sessional','theory'));
	 	$this->db->join($this->exam_table, ''.$this->exam_table.'.student_id = new_exam_form.student_id');
		$this->db->order_by('new_exam_form.sub_group_id,paper_order');
	 	$details = $this->db->get()->result();
		//  echo $classData->practical_internal_marks.
		//echo $this->db->last_query(); die;
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
	 	$Marksentry1 = $this->Common_model->updateRecordByConditions($this->exam_table,$where1,$Data);
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
        //$where = array('center_id'=>$center_id);
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}

		$data = array('name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$this->db->select('distinct('.$this->result_table.'.course_group_id) as course_group_id , course_group.course_name');
		$this->db->from($this->result_table);
		
		$this->db->join('course_group', ''.$this->result_table.'.course_group_id = course_group.id');
		$this->db->join('class_master', 'class_master.course_group_id = course_group.id');
		//$this->db->where('class_master.id', 'student.class_id');
		
		$this->db->where('class_master.result_permission', 'Y');
	//	$this->db->where('center_id', $center_id);
		$this->db->where('old_result_show','Y');
		$this->db->where($this->exam_form_result,'Y');
		//$this->db->where('`student.class_id` in (154,181,193,199,201,209,221,223,225,197,203,211,213)');
		$data['courses'] = $this->db->get()->result();
		// echo $this->db->last_query(); die;
		$this->load->view('Centers/header', array('title' => 'Result'));
		$this->load->view('Centers/result',$data);
		$this->load->view('Centers/footer');		
	}

	public function backlog_result()
	{
		
		$center_id =  $this->session->center_id;
        //$where = array('center_id'=>$center_id);
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$data = array('name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$this->db->select('distinct(backlog_student.course_group_id) as course_group_id , course_group.course_name');
		$this->db->from('backlog_student');
		
		$this->db->join('course_group', 'backlog_student.course_group_id = course_group.id');
		$this->db->join('class_master', 'class_master.course_group_id = course_group.id');
		//$this->db->where('class_master.id', 'student.class_id');
		
		// $this->db->where('class_master.backlog_result_permission', 'Y');
        $this->db->where('class_master.backlog_exam_form_permission', 'Y');
		//$this->db->where('center_id', $center_id);
		$this->db->where('result_show','Y');
		$this->db->where('exam_form','Y');
		$this->db->where('exam_year','June 2023');
		//$this->db->where('`student.class_id` in (154,181,193,199,201,209,221,223,225,197,203,211,213)');
		$data['courses'] = $this->db->get()->result();
		//  echo $this->db->last_query(); die;
		$this->load->view('Centers/header', array('title' => 'Backlog Result'));
		$this->load->view('Centers/backlog_result',$data);
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

	public function AllClassByCourseForResult($backlog ='')
	{
    $course = $this->input->post('course_group_id');
	$this->db->select('*');
	$this->db->from('class_master');
	// $this->db->where('exam_form_permission','Y');
	if($backlog == ''){
		$this->db->where('class_master.result_permission', 'Y');
	}else{
		$this->db->where('class_master.backlog_result_permission', 'Y');
	}
	
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
		// $where1 ='';
	
		$where = array($this->exam_form_result=>'Y','old_result_show'=>'Y');
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}

		if($_POST['course_group_id']!='All' and $_POST['course_group_id']!=''){
			$where[''.$this->result_table.'.course_group_id'] = $this->input->post('course_group_id');
			
		}
		// else{
		// 	$where1 = " `center_id` = ".$this->session->center_id."
		// 	AND `exam_form` = 'Y'
		// 	AND `result_permission` = 'Y'and ((student.course_group_id!=12 ) or (student.course_group_id=12 and university_mode='PVT' ) ) and ((student.course_group_id!=13 ) or (student.course_group_id=13 and university_mode='PVT' ) ) ";
		// }
		if($_POST['class_id']!='All' and $_POST['class_id']!=''){
			$where['class_id'] = $this->input->post('class_id');
		
		}
		// if($this->input->post('course_group_id') == 12 || $this->input->post('course_group_id') == 13){
		// 	$where['university_mode'] = 'PVT';
		// }
		
		$where['result_permission'] = 'Y';
		// Fetch member's records
		
		$column_order = array(''.$this->result_table.'.student_id','enrollment_no','name','f_h_name','course_name',''.$this->result_table.'.class_name','provisional_remark',null);
		$column_search = array(''.$this->result_table.'.student_id','enrollment_no','course_name',''.$this->result_table.'.class_name','name','f_h_name');
	
		$DataTableArray = array(
			'select' => ''.$this->result_table.'.*',
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => $this->result_table,
			'table2' => 'class_master',
			'joinOn' => ''.$this->result_table.'.old_class_id=class_master.id'
		);
		
		
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		
	
		$i = $_POST['start'];
		foreach($tableData as $result){
			   
			if($result->provisional_remark=="N" || $result->provisional_remark==""){
				// if(($result->old_class_id == '104' || $result->old_class_id == '107' || $result->old_class_id == '101' || $result->old_class_id == '134' || $result->old_class_id == '116'|| $result->old_class_id == '110' || $result->old_class_id == '119' || $result->old_class_id == '131') && $result->university_mode == 'REG')
				$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
				$class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
				if((in_array($result->old_class_id , $class_ids)) && $result->university_mode=='REG' && $result->exam_pattern=='GRADE')	
				{
					$btn =	'<a href="'.base_url('center/Center/grade_marksheet/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm dt-center" target="_blank" ><i class="fa fa-eye text-white"></i></a>' ;
				}else if((in_array($result->old_class_id, $class_cbcs)) && $result->university_mode=='REG' && $result->exam_pattern=='GRADE'){
					$btn =	'<a href="'.base_url('center/Center/grade_marksheet_pg/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm dt-center" target="_blank" ><i class="fa fa-eye text-white"></i></a>' ;
				}else{
					$btn =	'<a href="'.base_url('center/Center/marksheet/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" class="btn btn-info btn-sm dt-center" target="_blank" ><i class="fa fa-eye text-white"></i></a>' ;
				}
			
			}else{
				$this->db->select('provisional_remarks');
				$this->db->from('provisional_remark_details');
	             $this->db->where('document_category_id',$result->provisional_remark);
				 $remark = $this->db->get()->row();
				 if($remark->provisional_remarks == "Enrollment List"){
					$btn = "Documents are not recieved at university";
				 }else{
			    $btn = $remark->provisional_remarks." are not recieved at university";
				 }
			}
			$i++;
			if($result->enrolled=='N'){
				$enrollment = '-';
			}else{
				$enrollment = $result->enrollment_no;
				}
			$class_name =  $this->Common_model->getClassNameByClassId($result->old_class_id); 
			$data[] = array($i,$result->student_id,$enrollment,$result->name, $result->f_h_name, $result->course_name,$class_name,$btn);
		}
		
		/********************************************/
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsTotal =$this->Datatable_join_model->joincountAll($_POST,$DataTableArray);

		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);
		/*******************************************/		

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" =>$recordsFiltered,
			"data" => $data,
		);

// Output to JSON format
		echo json_encode($output);
	}

	public function getStudentListForBacklogMarksheet(){
		
		$data = $row = array();
		// $where1 ='';
	
		$where = array('exam_form'=>'Y','result_show'=>'Y','exam_year'=>'June 2023');
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}

		if($_POST['course_group_id']!='All' and $_POST['course_group_id']!=''){
			$where['backlog_student.course_group_id'] = $this->input->post('course_group_id');
			
		}
		// else{
		// 	$where1 = " `center_id` = ".$this->session->center_id."
		// 	AND `exam_form` = 'Y'
		// 	AND `result_permission` = 'Y'and ((student.course_group_id!=12 ) or (student.course_group_id=12 and university_mode='PVT' ) ) and ((student.course_group_id!=13 ) or (student.course_group_id=13 and university_mode='PVT' ) ) ";
		// }
		if($_POST['class_id']!='All' and $_POST['class_id']!=''){
			$where['class_id'] = $this->input->post('class_id');
		
		}
		// if($this->input->post('course_group_id') == 12 || $this->input->post('course_group_id') == 13){
		// 	$where['university_mode'] = 'PVT';
		// }
		// $where['class_master.result_permission'] = 'Y';
		// Fetch member's records
		
		$column_order = array('backlog_student.student_id','backlog_student.enrollment_no','backlog_student.course_group_id','backlog_student.class_id','class_master.class_name',null);
		$column_search = array('backlog_student.student_id','backlog_student.enrollment_no','backlog_student.course_group_id','backlog_student.class_id','class_master.class_name');
	
		$DataTableArray = array(
			'select' => 'backlog_student.*',
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => 'backlog_student',
			'table2' => 'class_master',
			'joinOn' => 'backlog_student.class_id=class_master.id'
		);
		// if($where1 != ''){
		// $this->db->where($where1);
		// }
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		//  $this->Common_model->last_query();
		//die;
		$i = $_POST['start'];
		foreach($tableData as $result){
			$student = $this->Common_model->getRecordById('student','student_id',$result->student_id);
			   
			// if($result->provisional_remark=="N" || $result->provisional_remark==""){
				// if(($result->old_class_id == '104' || $result->old_class_id == '107' || $result->old_class_id == '101' || $result->old_class_id == '134' || $result->old_class_id == '116'|| $result->old_class_id == '110' || $result->old_class_id == '119' || $result->old_class_id == '131') && $result->university_mode == 'REG')
				$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
				if((in_array($result->class_id , $class_ids)) && $result->mode=='REG')	
				{
					$btn =	'<a href="'.base_url('center/Center/backlog_grade_marksheet/'.$this->Common_model->encrypt_decrypt($result->id)).'" class="btn btn-info btn-sm dt-center" target="_blank" ><i class="fa fa-eye text-white"></i></a>' ;
				}else{
					$btn =	'<a href="'.base_url('center/Center/backlog_marksheet/'.$this->Common_model->encrypt_decrypt($result->id)).'" class="btn btn-info btn-sm dt-center" target="_blank" ><i class="fa fa-eye text-white"></i></a>' ;
				}
			
			// }
			$i++;
			if($result->enrolled=='N'){
				$enrollment = '-';
			}else{
				$enrollment = $result->enrollment_no;
				}
			$class_name =  $this->Common_model->getClassNameByClassId($result->class_id); 
			$data[] = array($result->student_id,$enrollment,$student->name, $student->f_h_name, $student->course_name,$this->Common_model->getClassNameByClassId($result->class_id),$btn);
		}
		/********************************************/
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsTotal =$this->Datatable_join_model->joincountAll($_POST,$DataTableArray);

		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);
		/*******************************************/		

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" =>$recordsTotal ,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
		);

// Output to JSON format
		echo json_encode($output);
	}


	public function marksheet($student_id="")
	{
		$provisional=array("","N");
		$this->db->where_in('provisional_remark', $provisional);
		
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$student = $this->Common_model->getRecordByWhere($this->result_table,array($this->exam_form_result=>'Y','old_result_show'=>'Y','student_id'=>$student_id));
		// ($student->course_group_id == 12 && $student->university_mode == 'REG') || ($student->course_group_id == 13 && $student->university_mode == 'REG')
		if ((count($student)==0)  ) {
			redirect(base_url());
		}
		$data['student']=$student[0];
		$classData = $this->Common_model->getRecordById('class_master','id',$data['student']->old_class_id);
		$data['practical_internal_marks']=$classData->practical_internal_marks;
		$this->db->select('*');
		$this->db->from($this->exam_form_table);
		$this->db->where(''.$this->exam_form_table.'.student_id',$data['student']->student_id);
		$this->db->where(''.$this->exam_form_table.'.class_id',$data['student']->old_class_id);
		$this->db->order_by(''.$this->exam_form_table.'.paper_order',''.$this->exam_form_table.'.paper_id');
		$new_exam_form = $this->db->get()->result();
		$data['new_exam_form']  = $new_exam_form;
		$data['classData']  = $classData;
		$data['exam_session']  = 'July 2023';
		$title = array('title' => 'Result - '.$data['student']->enrollment_no);
		$this->load->view('admin/generate_tr/header2',$title);	
		//$this->load->view('Centers/marksheet',$data);
		$this->load->view('Centers/marksheet_top',$data);
		//if ($student[0]->course_group_id==36 || $student[0]->course_group_id==37 || $student[0]->course_group_id==33) {
		if($classData->internal=='N'){
			$this->load->view('Centers/marksheet_without_int',$data);
		}else{
			if($student[0]->old_class_id=='168'){
				$this->load->view('Centers/marksheet_mom',$data);
			}else{
				$this->load->view('Centers/marksheet_bottom',$data);
			}
			
		$this->load->view('admin/generate_tr/footer2');
	}
}

public function backlog_marksheet($student_id="")
	{
		// $provisional=array("","N");
		// $this->db->where_in('provisional_remark', $provisional);
		
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		// $student = $this->Common_model->getRecordByWhere('backlog_student',array('exam_form'=>'Y','result_show'=>'Y','student_id'=>$student_id));
		// ($student->course_group_id == 12 && $student->university_mode == 'REG') || ($student->course_group_id == 13 && $student->university_mode == 'REG')
		$this->db->select('backlog_student.*,student.name,student.f_h_name,student.course_name,student.photo,student.session');
				$this->db->from('backlog_student');
				$this->db->join('student','backlog_student.student_id=student.student_id');
				$this->db->where(array('backlog_student.exam_form'=>'Y','backlog_student.result_show'=>'Y','backlog_student.id'=>$student_id,'exam_year'=>'June 2023'));
				$student = $this->db->get()->result();
		if ((count($student)==0)  ) {
			redirect(base_url());
		}
		// $data['students'] =$this->Common_model->getRecordById('student','student_id',$student[0]->student_id);
		
		$data['student']=$student[0];
        $data['student_info']= $this->Common_model->getRecordById('student','student_id',$data['student']->student_id);
		$classData = $this->Common_model->getRecordById('class_master','id',$data['student']->class_id);
		$data['practical_internal_marks']=$classData->practical_internal_marks;
		$this->db->select('*');
		$this->db->from('backlog_exam_form');
		$this->db->where('backlog_exam_form.student_id',$data['student']->student_id);
		$this->db->where('backlog_exam_form.class_id',$data['student']->class_id);
		$this->db->where('backlog_exam_form.backlog_student_id',$data['student']->id);
		$this->db->order_by('backlog_exam_form.paper_order','backlog_exam_form.paper_order');
		$backlog_exam_form = $this->db->get()->result();
		$data['backlog_exam_form']  = $backlog_exam_form;
		$data['classData']  = $classData;
		$data['exam_session']  = 'July 2023';
		$title = array('title' => 'Result - '.$data['student']->enrollment_no);
		$this->load->view('admin/generate_tr/header2',$title);	
		//$this->load->view('Centers/marksheet',$data);
		$this->load->view('Centers/marksheet_top_backlog',$data);
		//if ($student[0]->course_group_id==36 || $student[0]->course_group_id==37 || $student[0]->course_group_id==33) {
		if($classData->internal=='N'){
			$this->load->view('Centers/marksheet_without_int_backlog',$data);
		}else{
			if($student[0]->class_id=='168'){
				$this->load->view('Centers/marksheet_mom_backlog',$data);
			}else{
				$this->load->view('Centers/marksheet_bottom_backlog',$data);
			}
			
		$this->load->view('admin/generate_tr/footer2');
	}
}

public function marksheet_admin($student_id="")
	{
		$provisional=array("","N");
		$this->db->where_in('provisional_remark', $provisional);
		
		$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$student = $this->Common_model->getRecordByWhere($this->exam_table,array($this->exam_form=>'Y','student_id'=>$student_id));
		// ($student->course_group_id == 12 && $student->university_mode == 'REG') || ($student->course_group_id == 13 && $student->university_mode == 'REG')
		if ((count($student)==0)  ) {
			redirect(base_url());
		}
		$data['student']=$student[0];
		$classData = $this->Common_model->getRecordById('class_master','id',$data['student']->class_id);
		$data['practical_internal_marks']=$classData->practical_internal_marks;
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->where('new_exam_form.student_id',$data['student']->student_id);
		$this->db->where('new_exam_form.class_id',$data['student']->class_id);
		$this->db->order_by('new_exam_form.sub_group_id','new_exam_form.paper_order','new_exam_form.paper_id');
		$new_exam_form = $this->db->get()->result();
		$data['new_exam_form']  = $new_exam_form;
		$data['classData']  = $classData;
		$data['exam_session']  = 'July 2023';
		$title = array('title' => 'Result - '.$data['student']->enrollment_no);
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
		$class_cbcs = array(193,197,201,203,205,211,213,221,223,225,227,275,279);
		$this->load->view('admin/generate_tr/header2',$title);	
		//$this->load->view('Centers/marksheet',$data);
		if((in_array($student[0]->class_id , $class_ids)) && $student[0]->university_mode=='REG'){
			$this->load->model('Gradesheet_model');
			$this->load->view('Centers/grade_marksheet_admin',$data);	
		}else{
		$this->load->view('Centers/marksheet_top_admin',$data);
		//if ($student[0]->course_group_id==36 || $student[0]->course_group_id==37 || $student[0]->course_group_id==33) {
		if($classData->internal=='N'){
			$this->load->view('Centers/marksheet_without_int',$data);
		}else{
			if($student[0]->class_id=='168'){
				$this->load->view('Centers/marksheet_mom',$data);
			}else{
				$this->load->view('Centers/marksheet_bottom',$data);
			}
		}
			
		$this->load->view('admin/generate_tr/footer2');
	}
}


 public function grade_marksheet($student_id=""){
	
	 $student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$student = $this->Common_model->getRecordByWhere($this->result_table,array($this->exam_form_result=>'Y','old_result_show'=>'Y','student_id'=>$student_id));
		// print_r($student);die;
		if (count($student)==0) {
			redirect(base_url());
		}
		$data['student']=$student[0];
		$classData = $this->Common_model->getRecordById('class_master','id',$data['student']->old_class_id);
		$data['practical_internal_marks']=$classData->practical_internal_marks;
		$this->db->select('*');
		$this->db->from($this->exam_form_table.' as new_exam_form');
		$this->db->where('new_exam_form.student_id',$data['student']->student_id);
		$this->db->where('new_exam_form.class_id',$data['student']->old_class_id);
		$this->db->order_by('new_exam_form.paper_order','new_exam_form.paper_id');
		$new_exam_form = $this->db->get()->result();
		$data['new_exam_form']  = $new_exam_form;
		$data['classData']  = $classData;
		$data['exam_session']  = 'July 2023';
		$this->load->model('Gradesheet_model');
		// $title = array('title' => 'Result - '.$data['student']->enrollment_no);
		$title ="";
		$this->load->view('admin/generate_tr/header2');
		//$this->load->view('Centers/header',$title);
		$this->load->view('Centers/grade_marksheet',$data);
		//$this->load->view('Centers/footer');
		$this->load->view('admin/generate_tr/footer2');

 }

 public function grade_marksheet_pg($student_id=""){
	
	$student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
	   $student = $this->Common_model->getRecordByWhere($this->result_table,array($this->exam_form_result=>'Y','old_result_show'=>'Y','student_id'=>$student_id));
	   // print_r($student);die;
	   if (count($student)==0) {
		   redirect(base_url());
	   }
	   $data['student']=$student[0];
	   $classData = $this->Common_model->getRecordById('class_master','id',$data['student']->old_class_id);
	   $data['practical_internal_marks']=$classData->practical_internal_marks;
	   $this->db->select('*');
	   $this->db->from($this->exam_form_table.' as new_exam_form');
	   $this->db->where('new_exam_form.student_id',$data['student']->student_id);
	   $this->db->where('new_exam_form.class_id',$data['student']->old_class_id);
	   $this->db->order_by('new_exam_form.paper_order','new_exam_form.paper_id');
	   $new_exam_form = $this->db->get()->result();
	   $data['new_exam_form']  = $new_exam_form;
	   $data['classData']  = $classData;
	   $data['exam_session']  = 'July 2023';
	   $this->load->model('Gradesheet_model_pg');
	   // $title = array('title' => 'Result - '.$data['student']->enrollment_no);
	   $title ="";
	   $this->load->view('admin/generate_tr/header2');
	   //$this->load->view('Centers/header',$title);
	   $this->load->view('Centers/grade_marksheet_pg',$data);
	   //$this->load->view('Centers/footer');
	   $this->load->view('admin/generate_tr/footer2');

}


public function backlog_grade_marksheet($student_id=""){
	
    $student_id=$this->Common_model->encrypt_decrypt($student_id,'decrypt');
       $student = $this->Common_model->getRecordByWhere('backlog_student',array('exam_form'=>'Y','result_show'=>'Y','id'=>$student_id));
       // print_r($student);die;
       if (count($student)==0) {
           redirect(base_url());
       }
       $data['student']=$student[0];
       $data['student_info'] = $this->Common_model->getRecordById('student','student_id',$data['student']->student_id);
       $classData = $this->Common_model->getRecordById('class_master','id',$data['student']->class_id);
       $data['practical_internal_marks']=$classData->practical_internal_marks;
       $data['classData']  = $classData;
       $data['exam_session']  = 'July 2023';
       $this->load->model('Gradesheet_backlog_model');
       // $title = array('title' => 'Result - '.$data['student']->enrollment_no);
       $title ="";
       $this->load->view('admin/generate_tr/header2');
       //$this->load->view('Centers/header',$title);
       $this->load->view('Centers/backlog_grade_marksheet',$data);
       //$this->load->view('Centers/footer');
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
		// if($this->session->center_id!=12 && $this->session->center_id!=28){
		  redirect(base_url());
		// }
		//  $master = $this->Common_model->getSingleRow('master');
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData = array('title' => 'Regular Practical Marks Submission' );
		$this->load->view('Centers/header',$titleData);
		$center_id =  $this->session->center_id;
		$where = array('university_mode' => 'REG','center_id' => $center_id,'old_result_show' => 'N',$this->exam_form => 'Y');
		// ,'demo'=>'N','class_id'=>264
		$this->db->order_by("p_marks_sub,".$this->exam_table.".course_group_id,".$this->exam_table.".old_class_id", "asc");
		$this->db->select('*');
		$this->db->from($this->exam_table);
		$this->db->join('class_master', ''.$this->exam_table.'.old_class_id = class_master.id');
		$this->db->Where($where);
		$this->db->Where('(project="Y" or practical = "Y")');
		
		$data['students'] = $this->db->get()->result();
		// $this->Common_model->last_query();
		$this->load->view('Centers/practical_marks_no_list',$data);
		$this->load->view('Centers/footer');
	}

	public function load_student_practical_assignment (){
		$student_id = $this->input->post('student_id');
		$where=array(''.$this->exam_table.'.student_id'=>$student_id);
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		$this->db->where_not_in('paper_type',array('Sessional','theory'));
		$this->db->join($this->exam_table, ''.$this->exam_table.'.student_id = new_exam_form.student_id and '.$this->exam_table.'.old_class_id = new_exam_form.class_id');
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
		$Marksentry1 = $this->Common_model->updateRecordByConditions($this->exam_table,$where1,$Data);
		$sts_btn = '<button  class="btn btn-info btn-sm font-weight-bold view"  data-toggle="modal" data-target="#kt_datepicker_modal"  data-id = '.$_POST['student_id'].'"
		onclick="view_mark('.$_POST['student_id'].','.$_POST['old_class_id'].'")">view</button>';
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
			$class_id = $this->input->post('old_class_id');
			$classData	= $this->Common_model->getRecordById('class_master','id',$class_id); 
		 	$where=array(''.$this->exam_table.'.student_id'=>$student_id,'paper_master.sub_group_id !='=>1);
		 	$this->db->select('*');
		 	$this->db->from('new_exam_form');
		 	$this->db->Where($where );
			 if($classData->internal == "N"){
				$this->db->where('paper_master.type !=','theory'); }
		 	$this->db->join($this->exam_table, ''.$this->exam_table.'.student_id = new_exam_form.student_id and '.$this->exam_table.'.old_class_id = new_exam_form.class_id');
			$this->db->join('paper_master',''.$this->exam_table.'.old_class_id= paper_master.class_id and paper_master.paper_code = new_exam_form.paper_code');
			$this->db->order_by('new_exam_form.sub_group_id,paper_order,paper_no');
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
	public function edit_student_marks(){
		$student_id = $this->input->post('student_id');
	    $class_id = $this->input->post('old_class_id');
	   $classData	= $this->Common_model->getRecordById('class_master','id',$class_id); 
		$where=array(''.$this->exam_table.'.student_id'=>$student_id,'new_exam_form.class_id'=>$class_id);
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		$this->db->join($this->exam_table, ''.$this->exam_table.'.student_id = new_exam_form.student_id');
	//    $this->db->join('paper_master','student.class_id= paper_master.class_id and paper_master.paper_code = new_exam_form.paper_code');
	   $this->db->order_by('new_exam_form.sub_group_id,paper_order');
		$details = $this->db->get()->result();
		// $this->Common_model->last_query();
		$data = array(
		   'classData' =>$classData,
			'detail' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('Centers/student_marks_edit_data',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));
}
public function practical_assignment_marks_edit(){
	$student_id = $this->input->post('student_id');
   $class_id = $this->input->post('old_class_id');
   $classData	= $this->Common_model->getRecordById('class_master','id',$class_id); 
	$where=array(''.$this->exam_table.'.student_id'=>$student_id,'paper_master.sub_group_id !='=>1);
	$this->db->select('*');
	$this->db->from('new_exam_form');
	$this->db->Where($where );
	$this->db->where_not_in('paper_type',array('Sessional','theory'));
	$this->db->join($this->exam_table, ''.$this->exam_table.'.student_id = new_exam_form.student_id and '.$this->exam_table.'.old_class_id = new_exam_form.class_id');
    $this->db->join('paper_master','student.old_class_id= paper_master.class_id and paper_master.paper_code = new_exam_form.paper_code');
   $this->db->order_by('new_exam_form.sub_group_id,paper_order,paper_no');
	$details = $this->db->get()->result();
	$data = array(
	   'classData' =>$classData,
		'detail' => $details,
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash(),
	);
	if($data){
		$model =  $this->load->view('Centers/practical_marks_edit_data',$data,true);
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
			$transaction_number  = $this->input->post("transaction_number");
			$receipt_number  = $this->input->post("receipt_number");
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
				'txnId' =>$transaction_number,
				'receipt_number'=>$receipt_number,
				'image' => $file_name,
				'payment_status' => "Paid By University",
				'payment' => 'Y'
			);
		
			$where = array(
				'fees_head'=>'Admission Fees',
				'student_id'=> $student_id
			);
			$update = $this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$updateData);
			$response = $this->Common_model->updateRecordByConditions('student',array('student_id'=> $student_id),array('payment_status'=>'Y','document_uploaded'=>'Y','approved'=>'Y'));

			if($response){
			echo json_encode(array("status" => 'true'));
			}
		}
	}

	public function update_unpaid_student_exam_form(){
			
		if ($this->input->method() == "post") 
		{  
		    $exam_session='Dec 2023';
			 $date = $this->input->post('payment_date');
			 $date = str_replace('/', '-', $date);
			 $payment_date = date('Y-m-d', strtotime($date));
			$student_id  = $this->input->post("student_id");
	      	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
			$remark  = $this->input->post("remark");
			$payment_mode  = $this->input->post("payment_mode");
			$amount  = $this->input->post("amount");
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);
			$class_id = $student->class_id;
			$course_group_id = $student->course_group_id;
			$mode = ($student->university_mode == 'REG')?'Regular':'Private';
			$student_name = $student->name;
			$center_id = $student->center_id;
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
			$paymentData = array(
				'fees_head'=>'Exam Fees',
				'student_id'=> $student_id,
				'course_group_id'=>$course_group_id,
				'admission_type'=>$mode,
				'class_id'=>$class_id,
				'exam_session'=>$exam_session,
				'center_id'=>$center_id,
				'student_name'=>$student_name,
				'payment_date' => $payment_date,
				'remark' => $remark,
				'payment_mode' => $payment_mode,
				'amount' => $amount,
				'image' => $file_name,
				'payment_status' => "Paid By University",
				'payment' => 'Y'
			);
		
			
			$update = $this->Common_model->insertAll('online_payment_transaction',$paymentData);
			// $this->Common_model->last_query();
			$response = $this->Common_model->updateRecordByConditions('student',array('student_id'=> $student_id),array('new_exam_form'=>'Y'));

			if($response){
			echo json_encode(array("status" => 'true'));
			}
		}
	}
	
	public function search_exam_by_course(){
		// redirect(base_url('dashboard'));
		$dt = array();
		$titleData = array('title' => 'Course Wise Exam Date ');
		$this->load->view('Centers/header',$titleData);
		
		$dt['name_csrf'] = $this->security->get_csrf_token_name();
		$dt['hash_csrf'] = $this->security->get_csrf_hash();
	
		$this->db->select('course_group.*');
		$this->db->from('course_group');
		$this->db->join('paper_master', 'paper_master.course_group_id = course_group.id');
		$this->db->where('paper_master.exam_date!=','');
		//$this->db->where_not_in('paper_master.course_group_id',array(75,76));
		$this->db->where('paper_master.exam_date!=','0000-00-00');  
		$this->db->where('paper_master.type','theory'); 
	   // $this->db->where_not_in('class_id',array(163,175));
		$this->db->group_by('paper_master.course_group_id');
		$this->db->order_by('course_group.course_name', 'Asc');
		$dt['courses']= $this->db->get()->result_array();
		$this->load->view('Centers/search_exam_by_course',$dt);
		$this->load->view('Centers/footer');
	}
	//For Both private & regular 
	public function getClassByCourseForBoth(){
		$course = $this->input->post('course');
	
		$this->db->select('class_master.id,class_master.class_name');
                $this->db->from('class_master');
                $this->db->join('paper_master', 'paper_master.class_id = class_master.id');
                $this->db->where('paper_master.exam_date!=','');
				$this->db->where('paper_master.exam_date!=','0000-00-00'); 
                $this->db->where('paper_master.type','theory'); 
				$this->db->where('class_master.course_group_id',$course); 
			 
                $this->db->group_by('class_master.class_name');
				$this->db->order_by('class_master.class_name', 'Asc');
                $class_list= $this->db->get()->result_array();
			
		$data = array(
			'class_list' => $class_list,
			//'all' => 'All',
		);	
		echo $this->load->view('template/getclass',$data,true);
	}

	public function getClassByCourseForBothOld(){
		$course = $this->input->post('course');
	
		$this->db->select('class_master.id,class_master.class_name');
                $this->db->from('class_master');
                $this->db->join('paper_master', 'paper_master.class_id = class_master.id');
                $this->db->where('paper_master.old_exam_date!=','');
				$this->db->where('paper_master.old_exam_date!=','0000-00-00'); 
                $this->db->where('paper_master.type','theory'); 
				$this->db->where('class_master.course_group_id',$course); 
			 
                $this->db->group_by('class_master.class_name');
				$this->db->order_by('class_master.class_name', 'Asc');
                $class_list= $this->db->get()->result_array();
			
		$data = array(
			'class_list' => $class_list,
			//'all' => 'All',
		);	
		echo $this->load->view('template/getclass',$data,true);
	}
	//Time Table
	public function getExamTimeTable(){
		$course = $this->input->post('course');
		$class_id = $this->input->post('class_id');
		$data['class'] = $this->Common_model->get_record('class_master','*',array("course_group_id"=>$course,"id"=>$class_id));
		$this->db->order_by('exam_date', 'Asc');
		$this->db->order_by('exam_shift', 'Desc');
		$this->db->order_by('paper_no', 'Asc');
		$cbcs = $data['class'][0]['cbcs'];
		$data['paper_list'] = $this->Common_model->get_record('paper_master','*',array("course_group_id"=>$course,"class_id"=>$class_id,"type"=>'theory','paper_master.exam_date!='=>'','paper_master.exam_date!='=>'0000-00-00','cbcs_paper'=>$cbcs));
	
	//	echo $this->db->last_query();																				  
		echo $this->load->view('Centers/time_table',$data,true);
	}

	public function getExamTimeTableOld(){
		$course = $this->input->post('course');
		$class_id = $this->input->post('class_id');
		$data['class'] = $this->Common_model->get_record('class_master','*',array("course_group_id"=>$course,"id"=>$class_id));
		$this->db->order_by('old_exam_date', 'Asc');
		$this->db->order_by('old_exam_shift', 'Desc');
		$this->db->order_by('paper_no', 'Asc');
		$cbcs = $data['class'][0]['cbcs'];
		$data['paper_list'] = $this->Common_model->get_record('paper_master','*',array("course_group_id"=>$course,"class_id"=>$class_id,"type"=>'theory','paper_master.old_exam_date!='=>'','paper_master.old_exam_date!='=>'0000-00-00','cbcs_paper'=>$cbcs));
	
	//	echo $this->db->last_query();																				  
		echo $this->load->view('Centers/time_table_old',$data,true);
	}

	public function backlog_exam_form_students($exam_form1 = 'notSubmitted'){
		// redirect(base_url('dashboard'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$master = $this->Common_model->getSingleRow('master');
      $classpermission = $this->Common_model->get_record('class_master','id',array('backlog_exam_form_permission'=>'Y'));
  		$class_ids = array_column($classpermission, 'id');
		$center_id =  $this->session->center_id;
		$center_permission = $this->Common_model->get_record('center','exam_form_permission,temp_exam_form,temp_admission_payment',array('id'=>$center_id));
		if($exam_form1=='submitted'){
			$where = array('exam_form' =>'Y','center_id' => $center_id);
		}else if($exam_form1 =="notSubmitted"){
				$where = array(
					'exam_form' =>'N',
					'center_id' => $center_id,
				);
			
		}else if($exam_form1=="skipped"){
			$where = array(
				'exam_form' =>'S',
				'center_id' => $center_id,
			);
		}
		$data['exam_form_button'] = $exam_form1;
		if($center_permission[0]['temp_exam_form']=='N'){
			$this->db->where_in('class_id',$class_ids);
		}
		$this->db->where('exam_year' ,'Dec 2023');
		//Start
		
		if(!empty($master->remove_class_from_center) && $center_permission[0]['temp_exam_form'] =='N'){
			$remove_classes=explode(',',$master->remove_class_from_center);
			$this->db->where_not_in('backlog_student.class_id', $remove_classes );
		
		}
		//END
		$data['documents'] = $this->Common_model->getRecordByWhere('backlog_student',$where);
		if($center_permission[0]['exam_form_permission']!='Y' && $exam_form1 =="notSubmitted"){
			$data['documents'] ="";
		} 
		// $this->Common_model->last_query();
		$this->load->view('Centers/header');
		$this->load->view('Centers/backlog_exam_form_students',$data);
		$this->load->view('Centers/footer');
	}


    public function backlog_showPapers($student_id,$class_id){

    	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
    	$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
    	$titleData = array('title' => 'Backlog Student Papers');
    	$this->load->view('Centers/header',$titleData);
    	$student = $this->Common_model->student_info($student_id);
    	$data['student'] = $student;
    	$this->db->select('*');
    	$this->db->from('backlog_student');
    	$this->db->join('backlog_exam_form', 'backlog_exam_form.student_id = backlog_student.student_id and backlog_exam_form.backlog_student_id = backlog_student.id');
    	$this->db->where('backlog_student.student_id',$student_id); 
    	$this->db->where('backlog_student.class_id',$class_id);
		$this->db->where('backlog_exam_form.class_id',$class_id);
		$this->db->where('backlog_student.exam_year','Dec 2023');
    	$this->db->where('status','B');
		$this->db->order_by('paper_order', 'asc');
    	$data['papers'] = $this->db->get()->result();
        $data['name_csrf'] =  $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
    	$this->load->view('Centers/backlog_showPapers',$data);
    	$this->load->view('Centers/footer');
    }


  	public function change_backlog_new_exam_form_status(){
		$id    	= 0;
		$id    	= $this->input->post("id");
		$class_id = $this->input->post('class_id');
		$status = $this->input->post("check_skipped");

		if ($this->input->post("id"))
		{
			$status = ($status=='skipped') ? 'S' : 'N';
			$data = $this->Common_model->updateRecordByConditions("backlog_student",array("student_id" => $id ,"class_id"=>$class_id,'exam_year'=>'June 2023'),array("exam_form" => $status ));

			$dt = $this->db->get_where("backlog_student",array("student_id" => $id,"class_id"=>$class_id,'exam_year'=>'June 2023' ))->result_array();

			if($dt[0]['exam_form'] == 'N')
			{
				$sts_btn = '<input type ="button" name="" data-id='.$id.' data-class = '.$class_id.' class="btn btn-danger check_skipped" value="skipped">';
			}else{
				$sts_btn = '<input type ="button" name="update_enroll_stats" data-id='.$id.' data-class = '.$class_id.'  class="btn btn-success check_skipped" value="Unskipped">';
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

	public function application_form(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
			die();
		}
		$center_id =  $this->session->center_id;
		$titleData = array('title' => 'Search Student For Application');
		$this->load->view('Centers/header',$titleData);
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'center_id'=>$center_id,
		);
		$this->load->view('Centers/application_form',$data);
		$this->load->view('Centers/footer');
	}
	public function getStudentData()
	{
	
		$text_val =$this->input->post('text_val');
		$center_id =$this->input->post('center_id');
		
		if($text_val !='')
		{
			$where = array('enrollment_no'=>$text_val,'center_id'=>$center_id);
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'center_id'=>$center_id
			);
			$data['students'] = $this->Common_model->student_data($where);
			// print_r($data['students']);die;
			if($data['students']){
				$dt =  $this->load->view('Centers/getStudentForm',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));

			}else{
				echo json_encode(array(
					"status" => true,
					"data" => "Student Not Found"
				));

			}


			
		}
	}

	public function application_submit(){
		
		$apply = $this->input->post("apply_for");
		$std_id = $this->input->post("student_id");
		$enroll = $this->input->post("enrollment");
		$class_id = $this->input->post("class_id");
		if($apply == "DUPLICATE-MARKSHEET"){
			if($_FILES['policecomplaint']['name']==""){
				$this->session->set_flashdata('warning','Police Complaint is required !');
					redirect(base_url().'application_form');
			}
			if($_FILES['affidavit']['name']==""){
				$this->session->set_flashdata('warning','Affidavit is required !');
					redirect(base_url().'application_form');
			}
		}
		
		if($_FILES['adhar']['name']==""){
			$this->session->set_flashdata('warning','Adhar Card is required !');
				redirect(base_url().'application_form');
		}
		$student_id = $this->Common_model->encrypt_decrypt($std_id,'encrypt');
		// if($apply != "DUPLICATE-MARKSHEET"){
			$where = 'enrollment="'.$enroll.'" and apply_for="'.$apply.'"';
			$txnData = $this->Common_model->get_record('application_form','*',$where);
			$amount = $this->Common_model->getRecordById('application_field','field',$apply);
			if($txnData[0]['payment'] == "Y"){
				$this->session->set_flashdata('warning','Application already submitted');
				redirect(base_url().'application_form');
			}
			if(count($txnData)>0){

				if($txnData[0]['payment'] == "N"){
					redirect('center/payment/application/'.$student_id.'/'.$apply.'');

				}

				}else{
					$adhar_image=$marksheet_image="";
					$session=$this->input->post("session");
					if (!is_dir('assets/center_degree/'.$session)) {
						mkdir('assets/center_degree/'.$session, 0777, TRUE);
					}
					if($_FILES['adhar']['name']!=""){
						
						$ext1=strtolower(pathinfo($_FILES['adhar']['name'],PATHINFO_EXTENSION));
						$adhar_image=$std_id."_adhar.".$ext1;
						$upload_file = move_uploaded_file($_FILES['adhar']['tmp_name'],"assets/center_degree/".$session."/".$adhar_image);
					}
					if($_FILES['marksheet']['name']!=""){
						
						$ext1=strtolower(pathinfo($_FILES['marksheet']['name'],PATHINFO_EXTENSION));
						$marksheet_image=$std_id."_marksheet.".$ext1;
						$upload_file = move_uploaded_file($_FILES['marksheet']['tmp_name'],"assets/center_degree/".$session."/".$marksheet_image);
					}
					$data = array(
						"student_uid"=>$std_id,
						"center_id"=>$this->input->post("center_id"),
						"apply_for"=>$apply,
						"class"=>$class_id,
						"name_eng"=>$this->input->post("name_eng"),
						"name_hindi"=>$this->input->post("name_hindi"),
						"fname_eng"=>$this->input->post("fname_eng"),
						"fname_hindi"=>$this->input->post("fname_hindi"),
						"roll_no"=>$this->input->post("roll_no"),
						"enrollment"=>$this->input->post("enrollment"),
						"session"=>$this->input->post("session"),
						"course"=>$this->input->post("course"),
						"amount"=>$amount->amount,
						"phone"=>$this->input->post("phone"),
						"address"=>$this->input->post("address"),
						"adhar"=>$adhar_image,
						"marksheet"=>$marksheet_image,

					);

					$this->Common_model->insertAll('application_form',$data);
					$this->session->set_flashdata('success','Application Successfully Submit');
					redirect('center/payment/application/'.$student_id.'/'.$apply.'');

			}


		// }
		
	}

	public function application_list(){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
			die();
		}

		
		$csrf = array( 
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'course_type' => $course_type 
		);
		 
		
		$titleData = array('title' => 'Application List');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/application_list',$csrf);
		$this->load->view('Centers/footer');
	}

	public function getApplicationList(){
		
		$data = $row = array();
		$where = 'application_form.center_id='.$this->session->center_id.'';

		$column_order = array('student.university_mode','application_form.student_uid','application_form_enrollment', 'application_form.name_eng', 'application_form.fname_eng', 'course_name','class_name','application_form.apply_for',null);
		$column_search = array('student.student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name','application_form.apply_for',);
		$course_type=$this->input->post('course_type');
		//AND student.university_mode="'.$course_type.'"
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where.' and application_form.center_id=student.center_id',
			'table' => 'student',
			'table2' => 'application_form',
			'joinOn' => 'student.enrollment_no=application_form.enrollment'
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		$x=1;
		foreach($tableData as $result){
			$where1 = 'center_id='.$this->session->center_id.' and student_id='.$result->student_id.' and fees_head="'.$result->apply_for.'"';
			$txn = $this->Common_model->get_record('online_payment_transaction','*',$where1);
			if($result->payment == "N"){
				$btn = '<a href="'.base_url('center/payment/application/'.$this->Common_model->encrypt_decrypt($result->student_id).'/'.$result->apply_for).'" class="btn btn-info btn-sm" target="_blank" >pay</a>';
			}else{
				$btn = '<a href="'.base_url('show_fees/'.$this->Common_model->encrypt_decrypt($txn[0]['id'])).'" class="btn btn-primary btn-sm" target="_blank" ><i class="fa fa-eye text-white"></i></a>';
			}

            if($result->payment == "N"){
				$btn_download = '';
			}else{
				$btn_download = '<a href="'.base_url('assets/student_application/'.$result->session.'/'.$result->document).'" class="btn btn-primary btn-sm"  download> Download</a>';
			}
			
			$i++;
			$university_mode = ($result->university_mode=='REG') ? 'Regular' : 'Private';
			$data[] = array($x++, $result->student_uid,$result->enrollment_no, $result->name, $result->f_h_name, $result->course_name,$university_mode,$result->apply_for,$result->amount,$btn,$btn_download);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('application_form',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}
	public function admission_mode_edit_request($course_type="REG")
	{
		//redirect(base_url());
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{
			if($course_type=="REG"||$course_type=="reg")	
			$titleData = array('title' => 'Regular to Private Admission Mode Change Request'); 
				
			else
			redirect(base_url());
			//$titleData = array('title' => 'Private to Regular Admission Mode Change Request');	
			$this->load->view('Centers/header',$titleData);
			$id =  $this->session->center_id;
			$request_detail = $this->Common_model->get_record('request','*',array());
			$data = array('request_detail' => $request_detail,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'course_type' =>  $course_type,
			);
			$this->load->view('Centers/admission_mode_edit_request',$data);
			$this->load->view('Centers/footer');
		}
	}
	public function create_admission_mode_edit_request(){
		$session_id = $this->input->post('session_id');
		$course_group_id  = $this->input->post('course_group_id');
		$student_id = $this->input->post('student');
		$mode = $this->input->post('mode');
		if ($this->session->center_id!=13) {
			$this->db->where('center_id',$this->session->center_id);
		}else{
			$this->db->where_in('center_id',array( 21,22,23,24,25,26,27,28));
		}
		$check_record = $this->Common_model->get_record('request_mode_change','*',array('student_id' => $student_id));
		//print_r($this->db->last_query());    
		
		if($check_record){
			echo json_encode(array("status" => 'true','data' => "error"));
		}else{
			$response = $this->admin_model->create_admission_mode_request();
			$request_detail = $this->Common_model->get_record('request_mode_change','*',array());
			$data = array('request_detail' => $request_detail,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());
			$dt =  $this->load->view('admin/center/getRequestList',$data,true);
			echo json_encode(array("status" => 'true','data' => $dt));
		}
	}

	public function getModeEditRequest()
	{
		$course_type=$this->input->post('course_type');
		$course_type_where="";
		if(!empty($course_type))
			$course_type_where .=" student.university_mode='".$course_type."'  ";	
		$data = $row = array();
		$column_order = array(null,'name','student.student_id','detail','date','status','remark');
		$column_search = array('name','student.student_id','detail','date','status','remark');
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'select' => 'request_mode_change.remark,request_mode_change.from_mode,request_mode_change.to_mode,request_mode_change.student_id, request_mode_change.date, request_mode_change.detail, name, request_mode_change.status',
			'where' => $course_type_where,
			'table' =>  'request_mode_change',
			'table2' => 'student',
			'joinOn' => 'request_mode_change.student_id=student.student_id'
		);
		if ($this->session->center_id!=13) {
			$this->db->where('request_mode_change.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('request_mode_change.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$i++;
			$status = ($result->status=='Pending') ? 'Pending' : 'Done';
			$date = $this->Common_model->viewDate($result->date);
			$data[] = array($i, $result->name, $result->student_id,$result->from_mode,$result->to_mode, $result->detail,$date,$status,$result->remark);
		}
		if ($this->session->center_id!=13) {
			$this->db->where('request_mode_change.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('request_mode_change.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsTotal = $this->Datatable_join_model->countAll('request_mode_change',$where);
		
		if ($this->session->center_id!=13) {
			$this->db->where('request_mode_change.center_id',$this->session->center_id);
		}else{
			$this->db->where_in('request_mode_change.center_id',array( 21,22,23,24,25,26,27,28));
		}
		$recordsFiltered = $this->Datatable_join_model->countFiltered($_POST,$DataTableArray);

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}

	public function photo_missing_list(){
    	if(!$this->session->has_userdata('centerdata')){
    		redirect(base_url());
    	}


    	$center_id =  $this->session->center_id;
    	
		$titleData = array('title' => 'Photo Missing List' );
    	$this->load->view('Centers/header',$titleData);
    
		$this->db->where('center_id',$this->session->center_id);
		
    	$data['students'] = $this->Common_model->getRecordByWhere('student',$where);
    	$this->load->view('Centers/photo_missing_list',$data);
    	$this->load->view('Centers/footer');
    }	
	public function update_student_photo($student_id){
		
		$session=$this->input->post("session");
		$path = './assets/student_image/'.$session;
		$ext=$this->input->post("ext");
		
		$file='photo';
	
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['file_name'] =  $student_id;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload($file))
		{
			return $error = array('error' => $this->upload->display_errors());
		}else{
			
		
		$PhotoData = array('photo' => $student_id.'.'.$ext);
		$where = array('student_id'=>$student_id);
		$this->Common_model->updateRecordByConditions('student',$where,$PhotoData);
		$this->session->set_flashdata('ajax_flash_message','Photo uploaded !');
		echo json_encode(array(		"status" => 'true',		));
		
		}
		 
		
	}

	public function support_system_complaint($param = ""){

		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{
			if(!$param){
				$titleData = array('title' => 'Support Complaint');
				$this->load->view('Centers/header',$titleData);
				$id =  $this->session->center_id;
				$center = $this->Common_model->getRecordById('center','id',$id);
				$center_id =  $this->session->center_id;
				$wherestudent = 'center_id='.$center_id;
				$center_detail = $this->Common_model->get_record('support_complaint','*',$wherestudent);
				$data = array('center' => $center,'center_details' => $center_detail,'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash());
				$this->load->view('Centers/support_system_complaint',$data);
				$this->load->view('Centers/footer');
			}
			
		}
	}

	public function get_student_support_system(){
		
		// if ($this->input->method() == "post"){
		// 	//$course_group_id = 0;
			$data = array();
			// $dt   = array();
			$center_id =  $this->session->center_id;
			$form_no  = $this->input->post("form_no");
			$wherestudent = 'student_id='.$form_no.' and center_id='.$center_id;
			$students = $this->Common_model->get_record('student','*',$wherestudent);
			// $wherestudent = 'center_id='.$center_id;
			// $center_detail = $this->Common_model->get_record('support_complaint','*',$wherestudent);

			$data = array('students' => $students ,
				// 'center_details' => $center_detail,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash());

			if($data['students']){
				$dt =  $this->load->view('Centers/get_student_support_system_wise',$data,true);
			}else{
				$dt = "Invalid Form no";
			}
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		// }
	}

	public function student_support_system_sub($param = ""){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url());
		}else{
			if(!$param){
				$titleData = array('title' => 'Support Complaint');
				$this->load->view('Centers/header',$titleData);
				$id =  $this->session->center_id;
				$center = $this->Common_model->getRecordById('center','id',$id);
				$data = array('center' => $center,'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash());
				$this->load->view('Centers/get_student_support_system_wise',$data);
				$this->load->view('Centers/footer');
			}else{
				// print_r($this->input->post());die;
				$file_name ='';
				if(isset($_FILES['photo']) && $_FILES['photo']['tmp_name']!=''){
					// print_r($_FILES['photo']);die;
					$filename = $param.'-'.date('Ymdhis');
					$this->upload->initialize($this->Common_model->set_upload_options('./assets/complaintImages/',$filename));
					if(!$this->upload->do_upload('photo')){
						$error = $this->upload->display_errors();
						$msg = array('error'=>$error);
						echo json_encode($msg);
						exit();
						
					}else{
						$uploadData = $this->upload->data();
						$file_name = $uploadData['file_name'];
					}
				}
				$details = html_escape($this->input->post('detail'));
				$department = html_escape($this->input->post('complaint_department'));
				$complaint_type = html_escape($this->input->post('complaint_type'));
				$student_detail = $this->Common_model->getSingleRow("student","*",array("student_id" => $param));
				$data['details']   		= $details;
				$data['type'] 		    = $complaint_type;
				$data['department']		= $department;
				$data['center_id'] 		= $student_detail->center_id;
				$data['enrollment_no'] 	= $student_detail->enrollment_no;
				$data['student_id'] 	= $param;
				$data['attachment'] 	= $file_name;
				$data['date']   		=  date("Y-m-d");
				$data['status']   		= "Pending";
	    //   $check = $this->Common_model->getSingleRow("support_complaint","*",array("student_id" => $param, 'status !=' => 'Done' ));
		$check = $this->Common_model->getSingleRow("support_complaint","*",array("student_id" => $param, 'status !=' => 'Done','type ='=>$complaint_type ));

          if($check){
					$response = array(
						'status' => true,
						'err_msg' => "A Complaint Already Under Process",
					);
				}else{			
					$this->db->insert('support_complaint',$data);
					$response = array(
						'status' => true,
						'msg' => "Complained Succesfuly Registered",
					);
				}
               echo json_encode($response);	
			}
		}
	}


	public function getsupportComplaint()
	{
		$data = $row = array();
		$where = 'support_complaint.center_id='.$this->session->center_id;
		$column_order = array(null,'name','student.student_id','course_name','class_name','details','date','status','support_complaint.remark');
		$column_search = array('name','student.student_id','course_name','class_name','details','date','support_complaint.status','support_complaint.remark','support_complaint.reply_text','support_complaint.department');
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			// 'select' => 'student.name, student.student_id, student.course_name, student.class_name, support_complaint.date, support_complaint.details, support_complaint.remark,support_complaint.status',
			'select' => 'student.name, student.student_id, student.course_name, student.class_name, support_complaint.date, support_complaint.details, support_complaint.remark,support_complaint.status,support_complaint.type,support_complaint.id,support_complaint.attachment,support_complaint.reply_text,support_complaint.department',
			'where' => $where,
			'table' => 'support_complaint',
			'table2' => 'student',
			'joinOn' => 'support_complaint.student_id=student.student_id'
		);
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$remark = ($result->remark == 'N')?'':$result->remark;
			$i++;
			$date = $this->Common_model->viewDate($result->date);
			$status = ($result->status=="Pending") ? 'Pending' : 'Done';
			if($result->attachment != ''){
			$attachment = '<a target="_blank"  href="'.base_url().'assets/complaintImages/'.$result->attachment.'">'.'<i class="fa fa-eye">'.'</i>'.'</a>';
			}else{
				$attachment = '';	
			}
			$department = $this->Common_model->getRecordById('department_complaint','id',$result->department);
			// $data[] = array($i, $result->name, $result->student_id, $result->course_name,$result->class_name,$result->details,$date,$status,$result->remark);
			$data[] = array($i, $result->name, $result->student_id, $result->course_name,$result->class_name,$department->name,$result->type,$result->details,$date,$status,$remark,$result->reply_text,$attachment);

		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('support_complaint',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function paid_by_university_backlog($backlog_student_id){

		$backlog_student_id = $this->Common_model->encrypt_decrypt($backlog_student_id,'decrypt');
		$student_data = $this->Common_model->getRecordByWhere('backlog_student',array('id'=>$backlog_student_id));
		$where = array(
			'course_group_id' => $student_data[0]->course_group_id,
			'backlog_student_id' => $backlog_student_id,
			'status' => 'B'
		);
		$student = $this->Common_model->getRecordById('student','student_id',$student_data[0]->student_id);
		$fees = $this->Common_model->getCountByWhere('backlog_exam_form',$where);
		$data['student_id']=$student_data[0]->student_id;
		$data['center_id']=$student_data[0]->center_id;
		$data['exam_session'] = "Dec 2023";
		$data['course_group_id']=$student_data[0]->course_group_id;
		$data['class_id']=$student_data[0]->class_id;
		$data['amount'] = $fees*100;
		$data['fees_head']='Backlog Exam Fees';
		$data['student_name']=$student->name;
		$data['payment']='Y';
		$data['payment_status']='Paid By University';
		$data['payment_date']= $this->input->post('payment_date');
        $data['receipt_number'] = $this->input->post('receipt_number');
		$data['admission_type']= 'Regular';
		$data['payment_time']=date("h:i:s");
		$insert = $this->Common_model->insertAll('online_payment_transaction',$data);
		$student_data = array('exam_form' => 'Y');
		$update = $this->Common_model->updateRecordByConditions('backlog_student','id='.$backlog_student_id,$student_data);
		if($update){
			//redirect(base_url('backlog_exam_form_students'));
            $response = array(
                'status' => true,
                'msg' => "Complained Succesfuly Registered",
                'url'=> "backlog_exam_form_students"
            );
            echo json_encode($response);
		}
	}
	
	public function applyApplicationForm()
	{
	
		$text_val =$this->input->post('apply'); 
		$center_id =$this->input->post('center_id');
		$student_enroll =$this->input->post('student_enroll');
		
		if($text_val !='')
		{
			$where = array('enrollment_no'=>$student_enroll,'center_id'=>$center_id);
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'center_id'=>$center_id,
				'enrollment_no'=>$student_enroll
			);
			$data['students'] = $this->Common_model->student_data($where);
			//echo $this->db->last_query(); die;
			// print_r($data['students']);die;
			if($data['students']){
				$data['apply']=$text_val;
				$data['center_id']=$center_id;
				$dt =  $this->load->view('Centers/getApplicationForm',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));

			}else{
				echo json_encode(array(
					"status" => true,
					"data" => "Student Not Found"
				));

			}


			
		}
	}


	public function complaint_reply_list(){
		
		$titleData = array('title' => 'Support Complaint Reply');
		$this->db->select('sp.*,std.class_name,std.course_name,std.name');
		$this->db->from('support_complaint as sp');
		$this->db->join('student as std','std.student_id=sp.student_id');
		$this->db->where('sp.center_id',$this->session->center_id);
		$data['complaints'] = $this->db->get()->result();
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/complaint_reply_list',$data);
		$this->load->view('Centers/footer');
	}

	public function getComplaintType(){
		$department_id = $this->input->post('department');
		// $state_id = $this->Common_model->getSinglefield('state','state_id',array('name' => $state));
		$nameAttr = $this->input->post('nameAttr');
		$department = $this->Common_model->getRecordById('department_complaint',"id",$department_id);
		$ids = explode(',',$department->support_ids);
		$this->db->where_in('id',$ids);
		$this->db->where('status !=','N');
		$complaints = $this->Common_model->getRecordByWhere('support_system');
		$data = array(
			'complaints' => $complaints,
			'nameAttr' => $nameAttr
		);
		echo $this->load->view('admin/complaint_department/getcomplaint',$data,true);
	}
}//class
