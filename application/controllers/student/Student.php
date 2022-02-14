<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
		//$this->load->model('users/User_model');
	}
	
	public function index(){
		if($this->session->has_userdata('studentdata')){
			 $this->Student_model->checkStudentForm();
			redirect(base_url('student/dashboard'));
		}else{
			$this->load->view('students/header');
			$this->load->view('students/disclaimer');
			$this->load->view('students/footer');
		}
	}
	
	public function dashboard(){
		if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url('student/'));
		}else{
		    $titleData = array('title' => 'Student Dashboard'); 
			$this->load->view('students/header',$titleData);
			$id =  $this->session->student_id;
			$student = $this->Common_model->getRecordById('student','student_id',$id);
			$data = array('student' => $student);
			$this->getNotification();
			$this->load->view('students/dashboard',$data);
			$this->load->view('students/footer');
		}
		
	}
	
	//public function view_payment_detail(){
	// // 	if(!$this->session->has_userdata('studentdata')){
	// // 		 redirect(base_url('student/'));
	// // 	}else{
	// // 		$this->Student_model->checkStudentForm();
	// // 		$titleData = array('title' => 'Student Payments'); 
	// // 		$this->load->view('students/header',$titleData);
	// // 		$id =  $this->session->student_id;
	// // 		$data['student_payments'] = $this->db->get_where('online_payment_transaction', array("student_id" => $id ))->result_array();
			
	// // 		$this->load->view('students/view_student_payment',$data);
			
	// // 		$this->load->view('students/footer');
			
	// // 	}
	// }
	
	public function login(){

		$csrf = array(
		'name_csrf' => $this->security->get_csrf_token_name(),
		'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('students/login',$csrf);
	}

	public function loginSub(){
		
		 if($this->session->has_userdata('studentdata')){
		 	$this->Student_model->checkStudentForm();
		 	redirect(base_url('student/dashboard'));
			 exit;
		  }

		$this->form_validation->set_rules('enrollment_no', 'Enrollment_no', 'required');
		$this->form_validation->set_rules('dob', 'dob', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
				$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
			);
				$this->load->view('students/login',$csrf);
		}
		else
		{ 

		
			$username = $_POST['enrollment_no'];
			$password = $_POST['dob'];
			
			$check_user = $this->Student_model->checkStudent($username,$password);
			
			if($check_user){
				
				$data = array(
							'loged_in' 	  => true,
							'studentdata' => $check_user->enrollment_no,
							'dob' 	  	  => $check_user->dob,
							'student_id'  => $check_user->student_id,
							//'Users_id'  => $check_user->user_id
						);
				
				$this->session->set_userdata($data);
		
			$this->Student_model->checkStudentForm();
			redirect(base_url('student/dashboard'));
			}else{

			$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
		$this->session->set_flashdata('error','Enrollment no or Password are incorrect');
		
		$this->load->view('students/login',	$csrf );
		
		}
	}
}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('student/login'));
	}
	
	public function admission($student_id){
		if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url('student/'));
			 exit;
		}
		$enrollment_no = $this->session->studentdata;
		$enrolldata = explode("-",$enrollment_no);
		$enrollmentCode = $enrolldata[0];
		$enrollSession = '20'.$enrolldata[1];
		$wherestudent = 'student_id='.$student_id;
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		$enrollSession = ($student[0]['session']=='') ? $enrollSession : $student[0]['session'];
		$titleData = array('title' => 'Admission Form'); 
		
		$state_list = $this->Common_model->get_record('state','*');
		$district_list = $this->Common_model->get_record('distt','*');
		//$whereCourse = 'enrollment_code="'.$enrollmentCode.'"';
		//$course_group_list = $this->Common_model->get_record('course','*',$whereCourse);
		$course_group_list = $this->Common_model->get_record('course','*');
		$data = array(
				'session' => $enrollSession,
				'state_list' => $state_list,
				'district_list' => $district_list,
				'course_group_list' => $course_group_list,
		);
		$courseData = $this->Common_model->getRecordById('course_group','id',$student[0]['course_group_id']);
		$docLength = $this->Common_model->getCountByWhere('document_category','category ='.$courseData->document_id.' and status="Y"');
		$data['docLength'] = $docLength;
			if($student[0]['course_group_id']!=''){
			$whereClass='course_group_id='.$student[0]['course_group_id'];
				$data['class_list'] = $this->Common_model->get_record('class_master','*',$whereClass);
			}
			
			if($student[0]['payment_status']!='Y'){
			$this->session->set_flashdata('warning','Please Make Payment First');
				redirect(base_url('student/payment/admission/'.$student_id));
			}
			$data['student'] = $student[0];
			$exam_papers= array();
			if($student[0]['temp_exam_form']=='Y'){
				$exam_papers = $this->Common_model->get_record('new_exam_form','paper_id,paper_code',$wherestudent);
				$data['exam_papers'] = $exam_papers;
			}
			
			$count = $this->Common_model->getCountByWhere('student_data',$wherestudent);
			if($count>0){
			$studentdata = $this->Common_model->get_record('student_data','*',$wherestudent);
				$data['studentdata'] = $studentdata[0];
			}
		
		$this->load->view('students/header',$titleData);
		$this->load->view('students/admission',$data);
		$this->load->view('students/footer');
	}
	
	public function isDuplicateEnrollment(){
		$enrollment_no = $this->input->post('enrollment_no');	
		$count = $this->Common_model->getCountByWhere('student','enrollment_no='.$enrollment_no);
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
	if(!$this->session->has_userdata('studentdata')){
			 redirect(base_url('students/login'));
		}
		$this->Student_model->checkStudentForm();
		$data = array();
		$data['student'] = $this->Common_model->student_info($student_id);
		if($data['student']['approved']!='Y'){
		redirect(base_url('student/admission/'.$student_id));
		}
		$this->load->view('students/header',array('title' => 'Admission Form'));	
		$this->load->view('template/form',$data);
		$this->load->view('students/footer');
	}
	
	public function enquirySubmit()
	{
		$student_id = $this->input->post('student_id');
		$studentData = array(
			'name' => $this->input->post('name'),
			'f_h_name' => $this->input->post('f_h_name'),
			'dob' => date("Y-m-d", strtotime($this->input->post('dob'))),
			'course_group_id' => $this->input->post('course'),
			'class_id' => $this->input->post('class_id'),
			'class_name' => $this->Common_model->getClassNameByClassId($this->input->post('class_id')),
		);
		$where = 'student_id='.$student_id;
		
		$this->Common_model->updateRecordByConditions('student',$where,$studentData);
		
		$submitdata = array(
			'name' => $this->input->post('name'),
			'f_h_name' => $this->input->post('f_h_name'),
			'email' => $this->input->post('email'),
			'dob' => date("Y-m-d", strtotime($this->input->post('dob'))),
			'mobile_no' => $this->input->post('mobile'),
			'course_group_id' => $this->input->post('course'),
			'status' => '1',
			'student_id' => $student_id,
		);
		
		$user_id = $this->Common_model->insertAll('user_enquiry',$submitdata);
		$this->Common_model->updateRecordByConditions('student','student_id='.$student_id,array('user_id' => $user_id));
		$data = array('loged_in' => true,
							'userdata' => $submitdata['mobile_no'],
							'dob' => $submitdata['dob'],
							'Users_id' => $user_id,
							'student_id' => $student_id
						);
		$this->session->set_userdata($data);
				
		$this->session->set_flashdata('success','Your Query Submited Successfully');
		
		redirect(base_url('student/payment/admission/'.$student_id));
	}
	
	public function enquiry(){
		if(!$this->session->has_userdata('studentdata')){
			$this->load->view('students/login'); 
		}else{
		$enrollment_no = $this->session->studentdata;
		//$enrolldata = explode("-",$enrollment_no);
		//$enrollmentCode = $enrolldata[0];
		//$whereCourse = 'enrollment_code="'.$enrollmentCode.'"';
		//$course_group_list = $this->Common_model->get_record('course','*',$whereCourse);
		$course_group_list = $this->Common_model->get_record('course_group','*','admission_permission="Y"');
		$data = array(
				'course_group_list' => $course_group_list,
		);
			$this->load->view('students/header',array('title' => 'Form'));	
			$this->load->view('students/enquiry',$data);
			$this->load->view('students/footer');
		}
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
		// and admission_permission!='Y'
		$class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$course."' ");
		$data = array(
			'class_list' => $class_list,
		);
		echo $this->load->view('template/getclass',$data,true);
	}

	private function getNotification(){
		$student_id = $this->session->student_id;
		if($student_id!=''){
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);
			$studentdata = array('student' => $student);
			//$this->load->view('users/header');
			$this->load->view('students/notification',$studentdata);
			//$this->load->view('users/footer');	
		}
	}


	public function form()
	{
		$student_id = $this->session->student_id;
		// $student_ids = $this->Common_model->getSinglefield('user_enquiry','student_id','id='.$user_id);
		// if($student_ids==''){
		// 	redirect(base_url('student/enquiry'));
		// }
		$students = $this->Common_model->getRecordByWhere('student',array('student_id'=>$student_id));
		$data['students'] = $students;
		$this->load->view('students/header',array('title' => 'Admission Details'));
		$this->load->view('students/admission_details',$data);
		$this->load->view('students/footer');
	}

	public function new_admission(){

		$user_id = $this->session->student_id;
		$students = $this->Common_model->get_record('student','course_group_id','student_id='.$student_id);
		$course_ids = implode(",",array_map(function($a) {return implode(",",$a);},$students));
  
		// $course_list = $this->Common_model->getRecordByWhere('course_group',"admission_permission = 'Y' and id not in (".$course_ids.")");
		$data = array(
				'course_list' => $course_ids,
			);
		$this->load->view('students/header');
		$this->load->view('students/new_admission',$data);
		$this->load->view('students/footer');
	}

	public function newAdmissionSub(){
		$user_id = $this->session->student_id;
		$userData = $this->Common_model->getRecordById('user_enquiry','id',$student_id);
		$course = $this->input->post('course_group_id');
		$courseData = $this->Common_model->getRecordById('course_group','id',$course);
		$class = $this->input->post('class_id');
		$student_id = $userData->student_id;
		
		$student = $this->Common_model->getRecordByWhere("student",'student_id in ('.$student_id.') and form_status="Y"');
		$student = $student[0];

		$data = array('course_group_id' => $course,
		'class_id' => $class,
		'class_name' => $this->Common_model->getClassNameByClassId($class),
		'course_name' => $this->Common_model->getCourseNameByCourseId($course),
		'name' => $student->name,
		'name_hindi' => $student->name_hindi,
		'f_h_name_hindi' => $student->f_h_name_hindi,
		'f_h_name' => $student->f_h_name,
		'mother_name' => $student->mother_name,
		'category' => $student->category,
		'mother_name_hindi' => $student->mother_name_hindi,
		'gender' => $student->gender,
		'dob' => $student->dob,
		'photo' => $student->photo,
		'course_category' => $courseData->category,
		'eligibility' => $courseData->eligibility,
		'enrollment_no' => $student->enrollment_no,
		'adhar_no' => $student->adhar_no,
		'sm_id' => $student->sm_id,
		'user_id' => $user_id,
		);
	
	$this->db->order_by('id', 'Desc');
	$session = $this->db->get_where('session', array())->row_array();

	$data['session'] = $session['session'];
	$new_student_id = $this->Common_model->insertAll('student',$data);
	$studentData = $this->Common_model->getRecordById('student_data','student_id',$student_id);
	$studentData->student_id = $new_student_id;
	unset($studentData->id);
	$new_studentdata_id = $this->Common_model->insertAll('student_data',$studentData);
	$OnlinePayTxnData = array('student_id' => $new_student_id,'fees_head' => 'admission','amount' => 272,'payment_status'=>'pending','course_group_id' => $course,'class_id' => $class,'student_name' => $student->name);
	if($student->document_uploaded=='Y'){
		$document_id = $courseData->document_id;
		$documents = $this->Common_model->getRecordByWhere('document_category',array('category'=>$document_id));
		$document_uploaded = 'Y';
		foreach ($documents as $document) {
			$whereDoc = 'student_id = '.$student->student_id.' and document_name like "%'.$document->document_name.'%"';
			$admissionDoc = $this->Common_model->get_record('admission_document','*',$whereDoc);
			if(count($admissionDoc)==0){
			
				if($document->status=='Y')
				$document_uploaded = 'N';

				continue;
			}
			
			unset($admissionDoc[0]['date_time']);
			unset($admissionDoc[0]['id']);
			$admissionDoc[0]['student_id'] = $new_student_id;
			$admissionDoc[0]['document_category_id'] = $document->id;
			$this->Common_model->insertAll('admission_document',$admissionDoc[0]);
		}
	}
	$OnlinePayTxn = $this->Common_model->insertAll('online_payment_transaction',$OnlinePayTxnData);

	$stduent_ids = $student_id.', '.$new_student_id;
	$this->Common_model->updateRecordByConditions('user_enquiry','id='.$user_id,array('student_id' => $stduent_ids));
	$this->Common_model->updateRecordByConditions('student','student_id='.$new_student_id,array('document_uploaded' => $document_uploaded));
	$this->session->set_flashdata('success','Form Submited Successfully');
	echo json_encode( array('success'=>true));
	die();
	}
}