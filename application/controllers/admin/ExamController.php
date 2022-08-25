<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class ExamController extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='ExamController'){
				redirect(base_url('admin/logout')); 
		}
	}

	public function index(){
		$admin_id = $this->session->admin_id;
		$where = 'admin_id='.$admin_id.' and status="Y"';
		$menu = array(
			"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
			"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
		);
		$this->load->view('header',array('title' => 'Exam Controller Section'));
		$this->load->view('admin/enrollment/dashboard',$menu);
		$this->load->view('footer');	
	}

	public function consolidate_report(){
      
		$dt = array();
		$dt['title'] = "Student Consolidate Report";
		$this->load->view('header',$dt);
		$this->db->order_by('id', 'Desc');
		$dt['name_csrf'] = $this->security->get_csrf_token_name();
		$dt['hash_csrf'] = $this->security->get_csrf_hash();
		$dt['sessions'] = $this->db->get_where('session', array())->result_array();
		$this->load->view('admin/consolidate_report',$dt);
		$this->load->view('footer');
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

     public function generate_enrollment(){
		$where = array(
			'enrollment_permission' => 'Y',
			);
	$session = $this->db->get_where('session',$where)->result_array();

	$data['session'] = $session;
	$data['name_csrf'] = $this->security->get_csrf_token_name();
	$data['hash_csrf'] = $this->security->get_csrf_hash();
	
	 $this->load->view('header',array('title' => 'Generate Enrollment'));
	 $this->load->view('admin/examController/generate_enrollment',$data);
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
			$this->load->view('admin/examController/set_enrollment_permission',$data);
			$this->load->view('footer');
		}else if($_POST['action']=='setPermission'){
			$enrollment_nos = $this->input->post('enrollment_no');
			
			foreach($enrollment_nos as $en){
				$student = $this->Common_model->getRecordByWhere('student',array('enrollment_no'=>$en));

				$exam_form_permission = $this->Common_model->getRecordByWhere('class_master',array('id'=>$student[0]->class_id));

				$session = $this->Common_model->getRecordByWhere('session',array('session'=>$student[0]->session));

				$data = array('enrolled' => 'Y');
				/*****  exam form permission *****/
				if($exam_form_permission[0]->exam_form_permission=='Y' && $session[0]->exam_form_permission){
					$data['new_exam_form'] ='N';
				}
				$where = 'student_id="'.$student[0]->student_id.'" ';
				$this->Common_model->updateRecordByConditions('student',$where,$data);
			}

			$this->session->set_flashdata('ajax_flash_message','permission updated');
			$centerCode = $this->Common_model->encrypt_decrypt($centerCode,'encrypt');
			redirect(base_url().'admin/ExamController/enrollment_permission/'.$centerCode);		
		}
	}


	public function show_form($student_id){
		$data = array();
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt'); 
		$data['student'] = $this->Common_model->student_info($student_id);
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('header',array('title' => 'Admission Form'));	
		$this->load->view('template/form',$data);
		$this->load->view('footer');
	}

	public function enrollment_status()
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

	        //---paid/uploaded/ non approved---
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

			// not enrolled
			$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july);
			$data['tot_not_enrolled'] = $this->Common_model->getCountByWhere('student',$where);

			$this->load->view('header');
			$this->load->view('admin/enrollment/enrollment_status_count',$data);
			$this->load->view('footer');

		}

		public function center_wise_list($param)
		{
			
			if($param!='')
			{
				$session_july='July 2021';
				if($param =='paid')
				{
                   //---paid------
					$where = array('payment_status'=>'Y','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Paid)');
				}
				if($param =='not_paid'){
					// --- not paid------
					$where = array('payment_status'=>'N','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Unpaid)');
				}

				if($param =='uploaded')
				{
					//---paid and uploaded--------
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Documents Uploaded)');
				}
				if($param =='not_uploaded')
				{
//---not uploaded--------
					$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Documents Not Uploaded)');
				}
				if($param =='approved')
				{

					// paid + uploaded + approved = Y  verified----
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Approved)');
				}
				if($param =='not_verified')
				{
					 // paid + uploaded but approved = '' not verified----
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Not Verified)');
				}
				if($param =='non_approved')
				{
					  //---paid/uploaded/ non approved---
					$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Non-Approved)');
				}
				if($param =='generated')
				{
					// enrollement genrated
					$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Generated)');
				}
				if($param =='not_generated')
				{
					// not enrollement genrated
					$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Not Generated)');
				}
				if($param =='enrolled')
				{
                  // enrolled
					$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Enrolled)');
				}
				if($param =='not_enrolled')
				{
					// not enrolled
					$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List(Not Enrolled)');
				}

				if($param == 'all')
				{

					$where = array('session'=>$session_july);
					$msg = array('title' => 'Center Wise Student List');
				}

                // All Class
				$this->db->select('COUNT(student_id) as student_count,center_id,center_code,
					center_name,center_id');
				$this->db->group_by('center_id');
				$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
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
			$session_july='July 2021';
			$center_id = $this->uri->segment(4);
			$params_value = $this->uri->segment(5);

			if($params_value =='paid')
			{
                   //---paid------
				$where = array('payment_status'=>'Y','session'=>$session_july ,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Paid)');
			}
			if($params_value =='not_paid'){
					// --- not paid------
				$where = array('payment_status'=>'N','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Unpaid)');
			}

			if($params_value =='uploaded')
			{
					//---paid and uploaded--------
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Documents Uploaded)');
			}
			if($params_value =='not_uploaded')
			{
//---not uploaded--------
				$where = array('document_uploaded'=>'N','payment_status'=>'Y','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Documents Not Uploaded)');
			}
			if($params_value =='approved')
			{

					// paid + uploaded + approved = Y  verified----
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Approved)');
			}
			if($params_value =='not_verified')
			{
					 // paid + uploaded but approved = '' not verified----
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Not Verified)');
			}
			if($params_value =='non_approved')
			{
					  //---paid/uploaded/ non approved---
				$where = array('document_uploaded'=>'Y','payment_status'=>'Y','approved='=>'N','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Non-Approved)');
			}
			if($params_value =='generated')
			{
					// enrollement genrated
				$where = array('enrollment_no !='=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Generated)');
			}
			if($params_value =='not_generated')
			{
					// not enrollement genrated
				$where = array('enrollment_no'=>'-','approved='=>'Y','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Not Generated)');
			}
			if($params_value =='enrolled')
			{
                  // enrolled
				$where = array('enrolled'=>'Y','approved='=>'Y','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Enrolled)');
			}
			if($params_value =='not_enrolled')
			{
					// not enrolled
				$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july,'center_id'=>$center_id);
				$msg = array('title' => 'Center Wise Student List(Not Enrolled)');
			}
			
			if($center_id!='')
			{

           	// $this->db->where('center_id',$center_id);
				$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
				$this->load->view('header',array('title' => 'Center Wise Student List'));
				$this->load->view('admin/enrollment/students_count_details',$data); 
				$this->load->view('footer');
			}else
			{
				redirect(base_url('admin/'.$this->session->account_type.'/enrollment_status'));
			}
		}


	public function  center_wise_enrollment_permission(){
		$data = array();
		$data['title'] = "Center Wise Enrollment Permission";
		$this->load->view('header',$data);
		$where = array("approved" => "Y" , "enrollment_no!="=> '-' , 'enrolled'=> 'N');
		$data['centers'] = $this->Common_model->get_record_group_by_where('student','center_id, center_name, center_code ',$where);

		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('admin/examController/center_wise_enrollment_permission',$data);
		$this->load->view('footer');
	}

	public function course_detail(){
			$titleData = array('title' => 'Course Details'); 
			$this->load->view('header',$titleData);
			$id =  $this->session->center_id;
			$center = $this->Common_model->getRecordById('center','id',$id);
			$course_group = $this->db->get_where('course_group', array())->result_array();
			$data = array('course_group' => $course_group);
			$this->load->view('Centers/instruction',$data);
			$this->load->view('footer');
	}   



	public function teachers($param1 = '', $param2 = '', $param3 = ''){
		
		if($param1 == 'create'){
			$data['name'] = html_escape($this->input->post('name'));
			$data['address'] = html_escape($this->input->post('address'));
			$data['email'] = html_escape($this->input->post('email'));
			$data['phone'] = html_escape($this->input->post('phone'));
			$data['subject'] = html_escape($this->input->post('subject'));
			$data['clg_name'] = html_escape($this->input->post('clg_name'));	
			$insert = $this->Common_model->insertAll('teacher',$data);
			redirect(base_url().'ExamController/teachers');
		}
		if($param1 == 'update'){
			$data['name'] = html_escape($this->input->post('name'));
			$data['address'] = html_escape($this->input->post('address'));
			$data['email'] = html_escape($this->input->post('email'));
			$data['phone'] = html_escape($this->input->post('phone'));
			$data['subject'] = html_escape($this->input->post('subject'));
			$data['clg_name'] = html_escape($this->input->post('clg_name'));	
			$update = $this->Common_model->updateRecordByConditions('teacher', array('id'=>$param2) ,$data);
			redirect(base_url().'ExamController/teachers');
		}
		
		if($param1 == 'delete'){
			$delete = $this->Common_model->deleteByWhere('teacher',array('id'=>$param2));
			redirect(base_url().'ExamController/teachers');
		}

		if(empty($param1) ){
		$titleData = array('title' => 'Teachers'); 
		$this->load->view('header',$titleData);	
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$data['teachers'] = $this->Common_model->get_record('teacher','*');
		    $this->load->view('admin/examController/teachers',$data);
			$this->load->view('footer');
		}
	}
 
	public function update_teacher_status(){
		$status =  $this->input->post('status');
		if(isset($_POST['id'])){
			$id =  $this->input->post('id');
			$where = array('id'=>$id);
		}
		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('status'=>$st,);
		}
		$res=$this->Common_model->updateRecordByConditions('teacher',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}
  
	public function assign_answersheet(){
		$titleData = array('title' => 'Assign Answersheet'); 
		$this->load->view('header',$titleData);
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();	
		$data['courses'] = $this->Common_model->get_record('student','DISTINCT (course_group_id) , course_name ');
		$this->load->view('admin/examController/assign_answersheet',$data);
		$this->load->view('footer');
   } 

   public function generate_counter_folio(){
		$titleData = array('title' => 'Assign Answersheet'); 
		$this->load->view('header',$titleData);
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();	
		$data['courses'] = $this->Common_model->get_record('student','DISTINCT (course_group_id) , course_name ');
		$this->load->view('admin/examController/search_teacher_assign_answersheet',$data);
		$this->load->view('footer');
	} 

	public function search_assign_teacher(){
		if($_POST['action1']=='submit'){
			$this->db->select('GROUP_CONCAT(center_id) as center_id');
			$this->db->from("assign_answersheet");
			$this->db->where('paper_code',$_POST['paper_code']); 
			$query = $this->db->get()->result_array();
			$center_id = explode(',',$query[0]['center_id']);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
				 // it show student count for perticular center and pertical paper .	
			$this->db->select('DISTINCT(upload_exam_ans_sheet.teacher_id),teacher.name');
			$this->db->from('upload_exam_ans_sheet');
			$this->db->join('teacher', 'upload_exam_ans_sheet.teacher_id = teacher.id');
			$this->db->where('upload_exam_ans_sheet.class_id',$_POST['class_id']);
			$this->db->where('upload_exam_ans_sheet.paper_code',$_POST['paper_code']); 
			$this->db->where('upload_exam_ans_sheet.teacher_id!=',''); 
			
			$this->db->group_by('center_id');
			
			$data['teachers'] = $this->db->get()->result();//echo $this->db->last_query();die;
			//$data['teacher_id'] = $_POST['teacher_id'];
			$data['class_id'] = $_POST['class_id'];
			$data['paper_code'] = $_POST['paper_code'];
			$data['course_group_id'] = $_POST['course_group_id'];

			$dt = $this->load->view('admin/examController/get_assign_teacher',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		}
		
	}
	public function show_counter_folio(){
		if($_POST['action']=='assign_answersheet'){
			$data_insert['teacher_id'] =  implode(',',$_POST['teacher_id']);
			//echo "<pre>";
				//print_r($_POST['teacher_id']);
			$dataArray= array();	
			foreach($_POST['teacher_id'] as $teacher_id){
				$this->db->select('DISTINCT(upload_exam_ans_sheet.teacher_id),teacher.name,student.enrollment_no,student.roll_no,upload_exam_ans_sheet.total_marks');
				$this->db->from('upload_exam_ans_sheet');
				$this->db->join('teacher', 'upload_exam_ans_sheet.teacher_id = "'.$teacher_id.'"');
				$this->db->join('student', 'upload_exam_ans_sheet.student_id = student.student_id');
				$this->db->where('upload_exam_ans_sheet.class_id',$_POST['class_id']);
				$this->db->where('upload_exam_ans_sheet.paper_code',$_POST['paper_code']); 
				$this->db->group_by('upload_exam_ans_sheet.center_id');
				$dataArray['data'][$teacher_id] = $this->db->get()->result();
				$dataArray['teachername'][$teacher_id] = $this->Common_model->getSinglefield('teacher','name',array('id'=>$teacher_id));
				
			}	
			$dataArray['exam_date'] = $this->Common_model->getSinglefield('time_table','exam_start_date',array('class_id'=>$_POST['class_id']));
				/* echo $this->db->last_query();die; */
			$data['class_id'] = $_POST['class_id'];
			$data['paper_code'] = $_POST['paper_code'];
			$data['course_group_id'] = $_POST['course_group_id'];
			$dataArray["teacher_id"]=$_POST['teacher_id'];
			$dataArray['examname']= $this->Common_model->getCourseNameByCourseId($_POST['course_group_id']);
			$dataArray['class_name']= $this->Common_model->getClassNameByClassId($_POST['class_id']);
			
			$dataArray['paper']= $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$_POST['class_id'] , 'paper_code'=>$_POST['paper_code']));
			//print_r($dataArray['paper']); echo $this->db->last_query(); die;
			$this->load->view('admin/generate_tr/header2',array('title' =>'Folio'));
			$this->load->view('admin/examController/show_teacher_counter_folio',$dataArray); 
				
		
		}	
	}
   public function getPaperByClassId(){
	// $_POST['class_id'];
	   $data= $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$_POST['class_id'], 'type' => 'theory'));
	   echo json_encode(array('data'=>$data));
   }

   public function assign_answersheet_sub(){
	   	if($_POST['action1']=='submit'){
	   		$this->db->select('GROUP_CONCAT(center_id) as center_id');
	   		$this->db->from("assign_answersheet");
	   		$this->db->where('paper_code',$_POST['paper_code']); 
	   		$query = $this->db->get()->result_array();
	   		$center_id = explode(',',$query[0]['center_id']);
	   		$data['name_csrf'] = $this->security->get_csrf_token_name();
	   		$data['hash_csrf'] = $this->security->get_csrf_hash();
					// it show student count for perticular center and pertical paper .	
	   		$this->db->select(' count(*) as cnt ,center_code,center_id');
	   		$this->db->from('new_exam_form');
	   		$this->db->join('student', 'new_exam_form.student_id = student.student_id');
	   		$this->db->where('new_exam_form.class_id',$_POST['class_id']);
	   		$this->db->where('new_exam_form.paper_code',$_POST['paper_code']); 
	   		$this->db->where('student.new_exam_form','Y'); 
	   		$this->db->where_not_in('student.center_id', $center_id );
	   		$this->db->group_by('center_id');

	   		$data['centers'] = $this->db->get()->result();
	   		$data['teacher_id'] = $_POST['teacher_id'];
	   		$data['class_id'] = $_POST['class_id'];
	   		$data['paper_code'] = $_POST['paper_code'];
	   		$data['course_group_id'] = $_POST['course_group_id'];

	   		$dt = $this->load->view('admin/examController/get_center_code_by_class',$data,true);
	   		echo json_encode(array(
	   			"status" => true,
	   			"data" => $dt
	   		));
	   	}
	   	if($_POST['action']=='assign_answersheet'){

	   		$where = array(
	   			'teacher_id' => $_POST['teacher_id'],
	   			'course_group_id'=> $_POST['course_group_id'],
	   			'class_id'=> $_POST['class_id'],
	   			'paper_code'=> $_POST['paper_code'],
	   		);
	   		$data= $this->Common_model->getRecordByWhere('assign_answersheet',$where);

	   		if($data==null){
	   			$data_insert['teacher_id'] = $_POST['teacher_id'];
	   			$data_insert['class_id'] = $_POST['class_id'];
	   			$data_insert['course_group_id'] = $_POST['course_group_id'];
	   			$data_insert['paper_code'] = $_POST['paper_code'];
	   			$data_insert['center_id'] =  implode(',',$_POST['center_id']);
	   			$data_insert['date'] = date("Y-m-d");
	   			$data_insert['exam_session'] = 'Dec 2021';
	   			$insert = $this->Common_model->insertAll('assign_answersheet',$data_insert);
	   			
	   			if($insert){
	   				echo json_encode(array(
	   					"status" => true,
	   				));	
	   			}

	   		}else{
	   			if($data[0]->center_id!=''){
	   				$center_id = implode(',',$_POST['center_id']) ;
	   				$new_Center_id =$data[0]->center_id .",".$center_id;
	   			}else{
	   				$new_Center_id = $center_id;
	   			}

	   			$data=array(
	   				'center_id' =>$new_Center_id,
	   			);
	   			$update = 	$this->Common_model->updateRecordByConditions('assign_answersheet',$where,$data);
	   			if($update){
	   				echo json_encode(array(
	   					"status" => true,
	   				));	
	   			}
	   		}
	   	}
   	}
   
    public function  view_assign_answersheet(){
		$titleData = array('title' => 'All Assign Answersheet'); 
		$this->load->view('header',$titleData);
		$this->db->select('*');
		$this->db->from('assign_answersheet');
		$this->db->join('teacher', 'teacher.id = assign_answersheet.teacher_id');
		$data['teachers'] = $this->db->get()->result();
		$this->load->view('admin/examController/view_assign_answersheet',$data);
		$this->load->view('footer');
	}

		
	public function  teacher_alloted_exam_center($teacher_id ="",$class_id = "" , $course_group_id="" ,$paper_code=""){
		$teacher_id = $this->Common_model->encrypt_decrypt($teacher_id,'decrypt');
		$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$course_group_id = $this->Common_model->encrypt_decrypt($course_group_id,'decrypt');
		$paper_code = $this->Common_model->encrypt_decrypt($paper_code,'decrypt');
		$assign_answersheet_data= $this->Common_model->getRecordByWhere('assign_answersheet',array('teacher_id'=>$teacher_id , 'class_id'=>$class_id ,'paper_code'=>$paper_code , 'course_group_id'=>$course_group_id ));
		$center_ids = $assign_answersheet_data[0]->center_id;
		$data['course_name']= $this->Common_model->getCourseNameByCourseId($course_group_id);
		$data['class']= $this->Common_model->getClassNameByClassId($class_id);
		$data['teacher_name'] = $this->Common_model->getSinglefield('teacher','name',array('id'=>$teacher_id));
		$data['paper_name']= $this->Common_model->getSinglefield('paper_master','paper_name',array('class_id'=>$class_id , 'paper_code'=>$assign_answersheet_data[0]->paper_code));
		if($center_ids != ''){
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();

		//  query for paper count 
			$this->db->select(' count(*) as cnt , center_code , center_id');
			$this->db->from('new_exam_form');
			$this->db->join('student', 'new_exam_form.student_id = student.student_id');
			$this->db->where('new_exam_form.class_id',$assign_answersheet_data[0]->class_id);
			$this->db->where('new_exam_form.paper_code',$assign_answersheet_data[0]->paper_code);
			$this->db->where('student.center_id in ('.$center_ids.')');
			$this->db->where('student.new_exam_form','Y'); 
			$this->db->group_by('center_code');
			$data['paper_count']= $this->db->get()->result();
			$data['paper_code']= $assign_answersheet_data[0]->paper_code;
			$data['class_id']= $assign_answersheet_data[0]->class_id;
			$data['assign_answer_sheet_id']= $assign_answersheet_data[0]->id;
		}

		$titleData = array('title' => 'Alloted Center List');
		$this->load->view('header',$titleData);
		$this->load->view('admin/examController/teacher_alloted_exam_center',$data);
		$this->load->view('footer');
	}

	public function remove_centers_from_assign_answersheet(){
			// code for remove centers from assign_answersheet 

			
		$assign_answersheet_data= $this->Common_model->getRecordByWhere('assign_answersheet',array('id'=>$_POST['assign_answersheet_id']));
		    
		$alloted_center_id = explode(',',$assign_answersheet_data[0]->center_id);
	
		$remove_center_id_array = $_POST['center_id'];
	
		$new_alloted_center_id=array_diff($alloted_center_id,$remove_center_id_array);
		
	
		if(count($new_alloted_center_id)===0){
          $new_alloted_center_id = 0 ;
		  
		}else{
			$new_alloted_center_id = implode(',',$new_alloted_center_id);
		}
		$removed_center_id = implode(',',$remove_center_id_array);

		$data=array(
			'center_id' =>$new_alloted_center_id ,
			'removed_exam_center_id' =>$removed_center_id,
		);
		$update =$this->Common_model->updateRecordByConditions('assign_answersheet',array('id' =>$_POST['assign_answersheet_id']),$data);
		if($update){
			echo json_encode(array(
				"status" => true,
			));	
		}
	}
  
	public function course_wise_answersheet_status(){
		$titleData = array('title' => 'Course Wise Answersheet Status'); 
		$this->load->view('header',$titleData);
		$data['courses']= $this->Common_model->get_record('upload_exam_ans_sheet','DISTINCT (course_group_id)',array('course_group_id !=' => '0'));
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('admin/examController/course_wise_answersheet_status',$data);
		$this->load->view('footer');
	}

	public function load_course_wise_answersheet_status(){

		if($_POST['course_group_id']=='all'){
			$where  = array('type'=>'theory');
		}else{
			$where = array('course_group_id'=>$_POST['course_group_id'],'type'=>'theory');
		}
		$data['papers']= $this->Common_model->getRecordByWhere('paper_master',$where);
		$dt = $this->load->view('admin/examController/load_course_wise_answersheet_status',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}

	 public function teacher_wise_answersheet_status(){
		$titleData = array('title' => 'Teacher Wise Answersheet Status'); 
		$this->load->view('header',$titleData);
		$this->db->select('DISTINCT(teacher_id)');
		$this->db->from('assign_answersheet');
		$where_clause = $this->db->get_compiled_select();
		#Create main query
		$this->db->select('*');
		$this->db->from('teacher');
		 $this->db->where("`id` IN ($where_clause)", NULL, FALSE);
		$data['teachers'] = $this->db->get()->result();
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('admin/examController/teacher_wise_answersheet_status',$data);
		$this->load->view('footer');
	 } 
   

	public function load_teacher_wise_answersheet_status(){
		$data['teachers']= $this->Common_model->getRecordByWhere('assign_answersheet',array('teacher_id'=>$_POST['teacher_id']));
		$dt = $this->load->view('admin/examController/load_teacher_wise_answersheet_status',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}

	public function remaining_exam_answersheet(){
		$titleData = array('title' => 'Remaining answersheet  Status'); 
		$this->load->view('header',$titleData);
		$this->db->select('count(*) as cnt ,student.class_id,new_exam_form.course_group_id, center_code, center_name, roll_no, enrollment_no, name, course_name, class_name, student.student_id');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id');
		$this->db->where('student.new_exam_form','Y'); 
		$this->db->where('new_exam_form.paper_type','theory'); 
		$this->db->group_by('new_exam_form.student_id');
		$data['students'] = $this->db->get()->result();
		$this->load->view('admin/examController/remaining_exam_answersheet',$data);
		$this->load->view('footer');
	}

	public function checked_answersheet_status(){
		$titleData = array('title' => 'Checked Answersheet Status'); 
		$this->db->select('*');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->where('teacher_id!=',"");
		$this->db->group_by('teacher_id');
		$data['teachers']= $this->db->get()->result();
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('header',$titleData);
		$this->load->view('admin/examController/checked_answersheet_status',$data);
		$this->load->view('footer');
	}

	public function load_checked_answersheet_status(){
		$this->db->group_by('paper_code','ASC');
		$data['teachers']= $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array('teacher_id'=>$_POST['teacher_id']));	
		$dt = $this->load->view('admin/examController/load_checked_answersheet_status',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}

	public function answersheet_remark_status(){
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$where = array('total_marks'=>0, 'teacher_id!='=>'');
		$data['courses'] = $this->Common_model->get_record('upload_exam_ans_sheet','DISTINCT (course_group_id),class_id',$where);
		$this->load->view('header','Answersheet remark status ');
		$this->load->view('admin/examController/answersheet_remark_status',$data);
		$this->load->view('footer');
	}

	public function get_class_list_by_course()
	{
		if ($this->input->method() == "post") {
			$id    = 0;
			$count = 0;
			$id    = $this->input->post("id");
			if ($this->input->post("id")) {
				$data = $this->Common_model->getAllRow("class_master", "id, class_name", array(
					"course_group_id" => $id,
				),'id ASC');
				$count++;
			}
			if ($count > 0) {
				$status = true;
				$msg    = "";
			}
		}
		echo json_encode(array(
			"status" => $status,
			"msg" => $msg,
			"data" => $data
		));
	}

	public function get_student_for_remark(){
		$this->db->select('*');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->join('student', 'upload_exam_ans_sheet.student_id = student.student_id');
		if($_POST['course_group_id']!='all'){
			$this->db->where('upload_exam_ans_sheet.course_group_id', $_POST['course_group_id']);
			$this->db->where('upload_exam_ans_sheet.class_id', $_POST['class_id']);
		}
		$this->db->order_by('upload_exam_ans_sheet.course_group_id,upload_exam_ans_sheet.class_id');
		$this->db->where('upload_exam_ans_sheet.remark_status','');
		$this->db->where('upload_exam_ans_sheet.total_marks',0);
		$this->db->where('upload_exam_ans_sheet.teacher_id!=','');
		$this->db->group_by('upload_exam_ans_sheet.student_id');
		$data['students'] = $this->db->get()->result();
		$dt = $this->load->view('admin/examController/get_student_for_remark',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}

	public function update_remark_status()
	{
		$where = array(
			'paper_code'=>$_POST['paper_code'],
			'student_id'=>$_POST['student_id'],
			'class_id'=>$_POST['class_id']
		);
		$data = array('remark_status' =>$_POST['remark_status']);
		$update =  $this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
		if($update){
			echo json_encode(array('status'=>true));
		}
	}

	public function view_answersheet_pdf($id){
		$id=$this->Common_model->encrypt_decrypt($id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$where= array('id'=>$id);
		$data['answer'] = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',$where);
		$this->load->view('teacher/answersheet_pdf',$data); 
	}


	public function Teacher_paper_alloted_list(){
		$title = array('title' => 'Check Answer Sheet');
		$this->load->view('header',$title);	
		$where = ' answer_sheet!="" and file_exist="Y" and teacher_id="" and total_marks=0';
		$this->db->group_by('paper_code');
		$assignAnsData = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',$where);
		$data['assignAnsData'] = $assignAnsData;
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('admin/examController/teacher_paper_list_student_wise',$data); 
		$this->load->view('footer');
	}

    public function get_paper_details(){
     
		$paper_code =$this->input->post('paper_code');
		$where = array('paper_code'=> $paper_code);
		$assignAnsData = $this->Common_model->getRecordByWhere('assign_answersheet',$where);
	// 'teacher_id'=>$this->session->teacher_id,
		$where = 'paper_code = "'.$paper_code.'"  and answer_sheet!="" and file_exist="Y" and new_exam_form="Y" and teacher_id="" and total_marks=0';
		// and upload_exam_ans_sheet.center_id in ('.$assignAnsData[0]->center_id.')

		$this->db->select('roll_no,enrollment_no,course_name,class_name,paper_code,upload_exam_ans_sheet.student_id,upload_exam_ans_sheet.id');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = upload_exam_ans_sheet.student_id');
		$answersheetData = $this->db->get()->result();
		
		$data = array(
			'answersheetData' => $answersheetData,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);

		if($data){
			$dt =  $this->load->view('admin/examController/view_student_details',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $dt
		));
	}

	public function student_details_uplode(){
		$upload_exam_ans_id = $this->input->post('upload_exam_ans_id');
		$where=array('upload_exam_ans_sheet.id'=>$upload_exam_ans_id);
		$this->db->select('*');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = upload_exam_ans_sheet.student_id');

		$details = $this->db->get()->result();
		$data = array(
			'details' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('admin/examController/view_model_data',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));	
	}


	public function question_paper_sub()
	{  
		$id = $this->input->post('id');
		$marks1 = $this->input->post('marks1');
		$marks2 = $this->input->post('marks2');
		$marks3 = $this->input->post('marks3');
		$marks4 = $this->input->post('marks4');
		$marks5 = $this->input->post('marks5');
		$remark = $this->input->post('remark');
		$total_marks=$marks1+$marks2+$marks3+$marks4+$marks5;

		$where = array('id' => $id);
		$updateData = array('que_1' => $marks1,'que_2' => $marks2,'que_3' => $marks3,'que_4' => $marks4,'que_5' => $marks5, 'remark'=>$remark ,'total_marks'=> $total_marks);
		$result=	$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$updateData);
		if($result){
			echo json_encode(array(
				"success" => ' Updated Successfully',
			));
		}else{
			echo json_encode(array(
				"error" => ' error Occured',
			));
		}
	}

    public function view_question_pdf($paper_code){
		$paper_code=$this->Common_model->encrypt_decrypt($paper_code,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);

		$where= array('paper_code'=>$paper_code);
		$data['question'] = $this->Common_model->getRecordByWhere('paper_master',$where);
		$this->load->view('teacher/view_question_pdf',$data); 
	}


    public function check_answersheet_pdf($id){
		
		$id=$this->Common_model->encrypt_decrypt($id,'decrypt');
		
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$where= array('id'=>$id);
		$data['answer'] = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',$where);
		$update_open_answersheet = $this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,array('open_answersheet'=>'Y'));
		
		$this->load->view('admin/examController/view_answersheet_pdf',$data); 
	}


	public function student_marks_entry_update()
	{

		$upload_exam_ans_sheet_id = $this->input->post('upload_exam_ans_sheet_id');
		$json_data = $this->input->post('json_data');

		$where=array('uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
		$dataCount =	$this->Common_model->getCountByWhere('answer_sheet_json_data',$where);
		if($dataCount==""){
			$updateData = array('uplode_examsheet_id' => $upload_exam_ans_sheet_id,'json_data' => $json_data);

			$result=	$this->Common_model->insertAll('answer_sheet_json_data',$updateData);

			if($result){
				echo json_encode(array(
					"success" => ' Updated Successfully',
				));
			}else{
				echo json_encode(array(
					"error" => ' error Occured',
				));
			}
		}
		
		else{
			$where=array('uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
			$updateData1 = array('json_data' => $json_data);
			
			$result1=	$this->Common_model->updateRecordByConditions('answer_sheet_json_data',$where,$updateData1);


			if($result1){
				echo json_encode(array(
					"success" => ' Updated Successfully',
				));
			}else{
				echo json_encode(array(
					"error" => ' error Occured',
				));
			}
		}
	}


	public function Plugin_initialized_entry_update()
	{

		$upload_exam_ans_sheet_id = $this->input->post('upload_exam_ans_sheet_id');
		$initialize_json_data = $this->input->post('initialize_json_data');

		$where=array('uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
		$jsondataCount =	$this->Common_model->getCountByWhere('answer_sheet_json_data',$where);

		if(empty($jsondataCount)){
			$updateData = array('initialize_json' => $initialize_json_data,
				'uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
			$result=	$this->Common_model->insertAll('answer_sheet_json_data',$updateData);
		}
		echo json_encode(array(
			"success" => 'update Successfully ',
		));
	}

	public function check_student_exam_records(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$this->load->view('header',array('title' =>'Search Student Answersheet (Dec 2021)'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('admin/check_student_exam_records',$data);
			$this->load->view('footer');
		}

	}

	public function get_student_exam_details(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$text_val =$this->input->post('text_val');
			$radio_val = $this->input->post('radio_val');
			if($text_val !=''){
				if($text_val !='' && $radio_val == 'roll_no'){
					$student = $this->Common_model->getRecordById('student','roll_number',$text_val);
				}
				else if($text_val !='' && $radio_val == 'enrollment_no'){
					$student = $this->Common_model->getRecordById('student','enrollment_no',$text_val);
				}else if($text_val !='' && $radio_val == 'student_id'){
					$student = $this->Common_model->getRecordById('student','student_id',$text_val);
				}  
				
				$papers = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id' =>$student->student_id,'class_id' =>$student->old_class_id,'paper_type' => 'theory'));
				$data = array(
					'paper' => $papers,
					'student' => $student,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				$dt =  $this->load->view('admin/view_student_examination_view_records',$data,true);
				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}
		}
	}

	public function exam_center($param1 = '', $param2 = '', $param3 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			if($param1 == 'create'){
				if ($this->input->method() == "post") 
					{
						$arr['examcentercode']    = $this->input->post("examcentercode");
						$arr['schoolcollegename']    = $this->input->post("schoolcollegename");
						$arr['examcenteraddress']    = $this->input->post("examcenteraddress");
						$arr['city']    = $this->input->post("city");
						$arr['district']    = $this->input->post("district");
						$arr['pincode']    = $this->input->post("pin_code");
						$arr['superintendent']    = $this->input->post("superintendent");
						$arr['phonenumber']    = $this->input->post("phonenumber");
						$arr['bankaccountnumber']    = $this->input->post("bankaccountnumber");
						$arr['bankname']    = $this->input->post("bankname");
						$arr['bankbranch']    = $this->input->post("bankbranch");
						$arr['bankisfc']    = $this->input->post("bankisfc");
						$arr['csname']    = $this->input->post("csname");
						$arr['csnumber_1']    = $this->input->post("csnumber_1");
						$arr['csnumber_2']    = $this->input->post("csnumber_2");
					}
				
				//$response = $this->admin_model->create_exam_center();
				$response=$this->Common_model->insertAll('exam_center',$arr);
				$this->session->set_flashdata('ajax_flash_message','Exam Center Successfully Added');
				redirect(base_url().'ExamController/exam_center');

			}
			if($param1 == 'update'){ 
				if ($this->input->method() == "post") 
					{
						$arr['examcentercode']    = $this->input->post("examcentercode");
						$arr['schoolcollegename']    = $this->input->post("schoolcollegename");
						$arr['examcenteraddress']    = $this->input->post("examcenteraddress");
						$arr['city']    = $this->input->post("city");
						$arr['district']    = $this->input->post("district");
						$arr['pincode']    = $this->input->post("pin_code");
						$arr['superintendent']    = $this->input->post("superintendent");
						$arr['phonenumber']    = $this->input->post("phonenumber");
						$arr['bankaccountnumber']    = $this->input->post("bankaccountnumber");
						$arr['bankname']    = $this->input->post("bankname");
						$arr['bankbranch']    = $this->input->post("bankbranch");
						$arr['bankisfc']    = $this->input->post("bankisfc");
						$arr['csname']    = $this->input->post("csname");
						$arr['csnumber_1']    = $this->input->post("csnumber_1");
						$arr['csnumber_2']    = $this->input->post("csnumber_2");
						$response=$this->Common_model->updateRecordByConditions('exam_center',array('id'=>$param2),$arr);
					}
				//$response = $this->admin_model->course_update($param2);
				$this->session->set_flashdata('ajax_flash_message','Exam Center Successfully Updated');
				redirect(base_url().'ExamController/exam_center');
			}

			if($param1 == 'delete'){
				 $id    = $param2;
				 $response=$this->Common_model->deleteById('exam_center','id',$id);
				//$response = $this->admin_model->exam_center_delete($param2);
				$this->session->set_flashdata('ajax_flash_message','Course Successfully Deleted');
				redirect(base_url().'ExamController/exam_center');
			}

			if(empty($param1) ){
				$data = array();
				$data['title'] = "Exam Center";
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('header',$data);
				$this->load->view('admin/examController/exam_center',$csrf);
				$this->load->view('footer');
			}    


		}

	}

	public function allot_exam_center(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Allot Center'); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$data['exam_center'] = $this->Common_model->get_record('exam_center','id, examcentercode,schoolcollegename ');
			$where = "id not in (select distinct(center_id) from allot_exam_center )";
			$this->db->order_by('id');
			$data['centers'] = $this->Common_model->get_record('center','*',$where);
			$this->load->view('admin/exam_center/allot_exam_center',$data);
			$this->load->view('footer');
		}
	}
	public function allot_exam_center_sub(){
		if(($_POST['action1']=='allot_exam_center') && (!empty($_POST['exam_center'])))
		{
			$exam_center=$this->input->post('exam_center');	
			$exam_centers = $this->db->get_where('exam_center', array('id' => $exam_center))->result_array();
			foreach($_POST['center_id'] as $center_id){
				
				$arr['examcentercode']= $exam_centers[0]['examcentercode'];
				$arr['center_id']=$center_id;
				$arr['exam_center_id']=$exam_center;
				$response=$this->Common_model->insertAll('allot_exam_center',$arr);
				//echo $this->db->last_query();
			}
				
				
		}		
		redirect(base_url().'admin/ExamController/allot_exam_center');	
	}

	public function allotted_exam_center($param1 = '',$param2 = ''){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			if($param1 == 'delete'){
				$id    = $param2;
				$response=$this->Common_model->deleteById('allot_exam_center','id',$id);
			   //$response = $this->admin_model->exam_center_delete($param2);
			   $this->session->set_flashdata('ajax_flash_message','Course Successfully Deleted');
			   redirect(base_url().'ExamController/allotted_exam_center');
		   }
		   if(empty($param1) ){
			$titleData = array('title' => 'List of Allotted Center'); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('allot_exam_center.id,center.center_code,center.center_name,exam_center.examcentercode,exam_center.schoolcollegename,exam_center.city,exam_center.examcenteraddress');
			$this->db->from('allot_exam_center');
			$this->db->join('exam_center', 'allot_exam_center.exam_center_id  = exam_center.id');
			$this->db->join('center', 'allot_exam_center.center_id  = center.id');
			$this->db->order_by('center.id');
			$data['exam_center_allotted'] = $this->db->get()->result();
			
			$this->load->view('admin/exam_center/allotted_exam_center',$data);
			$this->load->view('footer');
		   }
		}	
	}
	//Single Paper
	public function testid_wise_student_count(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Test Id wise Student Count'); 
			$this->load->view('header',$titleData);
			$this->db->select('*,COUNT(id) as tot');
			$this->db->from('paper_master');
			$this->db->where('type','Theory');
			$this->db->where('test_id!=','');
			$this->db->where('exam_date!=','0000-00-00');
			$this->db->where('exam_date!=','');
			$this->db->group_by('test_id ');
			$this->db->having(' tot=1');
			$this->db->order_by("test_id", "asc");
			$data['list'] = $this->db->get()->result();
			$data['multiple']=false;
			$this->load->view('admin/exam_center/testid_wise_student',$data);
			$this->load->view('footer');
		}
	}
	//Multiple Paper
	public function testid_wise_student_count_multiple(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Test Id wise Student Count (Multiple Test ID)'); 
			$this->load->view('header',$titleData);
			$this->db->select('*,COUNT(id) as tot');
			$this->db->from('paper_master');
			$this->db->where('type','Theory');
			$this->db->where('test_id!=','');
			$this->db->where('exam_date!=','0000-00-00');
			$this->db->where('exam_date!=','');
			$this->db->group_by('test_id ');
			$this->db->having(' tot>1');
			$this->db->order_by("test_id", "asc");
			$data['list'] = $this->db->get()->result();//echo $this->db->last_query();
			$data['multiple']=true;
			$this->load->view('admin/exam_center/testid_wise_student',$data);
			$this->load->view('footer');
		}
	}
	//Envelope cover Single Test ID 
	public function envelope_cover_page_single_testid(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Envelope Cover Page Single Testid'); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('*,COUNT(id) as tot');
			$this->db->from('paper_master');
			$this->db->where('type','Theory');
			$this->db->where('test_id!=','');
			$this->db->where('exam_date!=','0000-00-00');
			$this->db->where('exam_date!=','');
			$this->db->group_by('test_id ');
			$this->db->having(' tot=1');
			$this->db->order_by("test_id", "asc");
			$data['list'] = $this->db->get()->result();
			$data['multiple']=0;
			$this->load->view('admin/exam_center/envelope_cover_page',$data);
			$this->load->view('footer');
		}
	}	
	
	public function getEnvelope(){
		$test_id = $this->input->post('test_id');
		$multiple = $this->input->post('multiple');
		$data['examSession'] = 'June 2022';
		$this->db->select('*');
		$this->db->from('exam_center');
		//$this->db->where('examcentercode','MDE034');
		$this->db->order_by("exam_center.examcentercode", "asc");
		$data['elist'] = $this->db->get()->result();//echo $this->db->last_query(); die;
		if($multiple){
			
			$data['paperData'] =$paperData = $this->Common_model->get_record('paper_master','*',"test_id='".$test_id."'");
			echo $this->load->view('admin/exam_center/envelope_cover_page_multiple',$data, TRUE);
		}
		else{
			$data['paperData'] =$classData = $this->Common_model->get_record('paper_master','*',"test_id='".$test_id."'");
		
			
			//echo $this->db->last_query(); die;
			$this->db->select('*');
			$this->db->from('class_master');
			$this->db->where('class_master.id',$classData[0]['class_id']);
			$data['classMaster'] = $this->db->get()->result();
			
			echo $this->load->view('admin/exam_center/envelope_cover_page_single',$data, TRUE);
		}
			
	}

	//Envelope cover Multiple Test ID 
	public function envelope_cover_page_multiple_testid(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('*,COUNT(id) as tot');
			$this->db->from('paper_master');
			$this->db->where('type','Theory');
			$this->db->where('test_id!=','');
			$this->db->where('exam_date!=','0000-00-00');
			$this->db->where('exam_date!=','');
			$this->db->group_by('test_id ');
			$this->db->having(' tot>1');
			$this->db->order_by("test_id", "asc");
			$data['list'] = $this->db->get()->result();
			$data['multiple']=true;
			$titleData = array('title' => 'Envelope Cover Page Mutiple Testid'); 
			$this->load->view('header',$titleData);
			$this->load->view('admin/exam_center/envelope_cover_page',$data);
			$this->load->view('footer');
		}
	}	

	//Exam Center Wise Answer Sheet Count
	public function exam_center_wise_answer_sheet_count(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Exam Center Wise Answer Sheet Count'); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('*');
			$this->db->from('exam_center');
			$this->db->order_by('examcentercode', "asc");
			$data['exam_centers'] = $this->db->get()->result();

			$this->load->view('admin/exam_center/exam_center_wise_answer_sheet',$data);
			$this->load->view('footer');
		}
	}	

	public function get_exam_center_wise_answer_sheet_count(){
		$exam_center = $this->input->post('exam_center');
		$this->db->select('*');
		$this->db->from('exam_center');
		if($exam_center!="All")
		$this->db->where('id',$exam_center);	
		$data['exam_centers'] = $this->db->get()->result();
		echo $this->load->view('admin/exam_center/exam_center_wise_answer_sheet_count_show',$data, TRUE);
	}

	//Exam Center Wise Paper Count by Date & Shift
	public function exam_center_wise_paper_count(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Paper Count By Date'); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('*');
			$this->db->from('exam_center');
			$this->db->order_by('examcentercode', "asc");
			$data['exam_centers'] = $this->db->get()->result();
			$this->db->select('*');
			$this->db->from('paper_master');
			$this->db->where('exam_date!=',"");
			$this->db->where('exam_date!=',"0000-00-00");	
			$this->db->group_by('exam_date');
			$this->db->order_by('exam_date', "asc");
			$data['examDate'] = $this->db->get()->result();

			$this->load->view('admin/exam_center/exam_center_wise_paper',$data);
			$this->load->view('footer');
		}
	}
	
	public function get_exam_center_wise_paper_count(){
		$data['exam_center']=$exam_center = $this->input->post('exam_center');
		$data['exam_date']=$exam_date = $this->input->post('exam_date');
		$data['shift']=$shift = $this->input->post('shift');
		$this->db->select('*');
		$this->db->from('exam_center');
		
		$this->db->where('id',$exam_center);	
		$data['exam_centers'] = $this->db->get()->result();

/*
		$this->db->select('DISTINCT(paper_master.id),exam_date,exam_shift,exam_day,paper_master.paper_code,paper_master.paper_name,paper_master.course_group_id,paper_master.class_id');
		$this->db->from('paper_master');
		$this->db->join('new_exam_form_report', 'new_exam_form_report.paper_id = paper_master.id');
		$this->db->join('student_report', 'student_report.student_id = new_exam_form_report.student_id');
		$this->db->where('student_report.new_exam_form!=','D' );
		$this->db->where('paper_master.exam_date!=',"");
		if($exam_date)	{
			$edate=date("Y-m-d", strtotime($exam_date));
			$this->db->where('paper_master.exam_date',$edate);
		}
			
		if($shift)	
			$this->db->where('paper_master.exam_shift',$shift);
		$this->db->where('student_report.exam_center_id', $exam_center );
		$this->db->group_by('paper_master.exam_date');

		
		//$this->db->order_by('paper_master.exam_date');
		$data['papers'] = $this->db->get()->result();
		echo $this->db->last_query();die; */

		$where="";
		if($exam_center!='All')
			$where.="AND `student`.`exam_center_id` = '".$exam_center."'";
		if($exam_date!='All')	{
			$edate=date("Y-m-d", strtotime($exam_date));
			$where.="AND paper_master.exam_date = '".$edate."'";
		}
		if($shift!='All')
		$where.="AND paper_master.exam_shift = '".$shift."'";

		$where.="   GROUP BY `paper_master`.`exam_date`";

		 $sql="SELECT DISTINCT(paper_master.id), `exam_date`, `exam_shift`, `exam_day`, `paper_master`.`paper_code`, `paper_master`.`paper_name`, `paper_master`.`course_group_id`, `paper_master`.`class_id` FROM `paper_master` JOIN `student` ON `student`.`class_id` = `paper_master`.`class_id` WHERE `paper_master`.`type` = 'theory' AND `paper_master`.`exam_date` != '' AND paper_master.exam_date!='0000-00-00'  ".$where; 
		
		$query = $this->db->query($sql);
        $data['papers'] = $query->result();
		echo $this->load->view('admin/exam_center/exam_center_paper_count_show',$data, TRUE);
	}

	public function paper()
	{

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}	
		$this->load->view('header',array('title'=>'Paper'));
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('admin/examController/paper',$csrf);
		$this->load->view('footer');

	}

	public function get_papers_by_class_course()
	{

		if ($this->input->method() == "post") 
		{
			$class_id    = 0;
			$class_id    = $this->input->post("class_id");
			$course_group_id    = $this->input->post("course_group_id");
			$where = array();
			if($course_group_id!='All'){
				$where = array('course_group_id' => $course_group_id);
			}
			if($class_id!='All'){
				$where = array('class_id' => $class_id);
			}
			$papers = $this->db->get_where("paper_master",$where)->result_array();
			$htmlData = array(
				'papers' => $papers,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$data = $this->load->view('admin/examController/paper_details',$htmlData,true);
			$status = true;
			$msg    = "";
		   }
		echo json_encode(array(
			"status" => $status,
			"msg" => $msg,
			"data" => $data
		));
	}

	public function course_details_private(){

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Private Course Details');
			$this->load->view('header',$titleData);
			$course_group_list = $this->Common_model->get_record('course_group','*',array('status !=' => 'D' ,'admission_permission_pvt'=>'Y'));
			$data = array('course_group' => $course_group_list);
			$this->load->view('Centers/instruction_private',$data);
			$this->load->view('footer');
		}
	  }


	public function course_details_regular(){

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Regular Course Details');
			$this->load->view('header',$titleData);
			$course_group_list = $this->Common_model->get_record('course_group','*',array('status !=' => 'D','admission_permission'=>'Y'));
			$data = array('course_group' => $course_group_list);
			$this->load->view('Centers/instruction',$data);
			$this->load->view('footer');
		}
	 }

	 //Exam Center Wise Student Attendance Sheet 
	public function exam_center_wise_student_attendance_sheet(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Exam Center Wise Student Attendance Sheet '); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('*');
			$this->db->from('exam_center');
			$this->db->order_by('examcentercode', "asc");
			$data['exam_centers'] = $this->db->get()->result();

			$this->load->view('admin/exam_center/exam_center_wise_student_attendance_sheet',$data);
			$this->load->view('footer');
		}
	}	

	//Get Exam Center Wise Student Attendance Sheet 
	public function get_exam_center_wise_student_attendance_sheet(){
		$exam_center = $this->input->post('exam_center');
		$this->db->select('*');
		$this->db->from('student');
		$this->db->order_by("roll_no", "asc");
		// if($exam_center!="All")
		$where = array('exam_center_id'=>$exam_center, 'roll_no!=' => 0 ,'notification_no'=>2);
		$this->db->where($where);	
		$data['exam_center_students'] = $this->db->get()->result();
		echo $this->load->view('admin/exam_center/get_exam_center_wise_student_attendance_sheet',$data, TRUE);
	}

		//Date Wise Paper Count by All & Unique category
	public function date_wise_paper_calculation(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Paper Count By Date'); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			
			$this->db->select('*');
			$this->db->from('paper_master');
			$this->db->where('exam_date!=',"");
			$this->db->where('exam_date!=',"0000-00-00");	
			$this->db->group_by('exam_date');
			$this->db->order_by('exam_date', "asc");
			$data['examDate'] = $this->db->get()->result();

			$this->load->view('admin/exam_center/date_wise_paper_calculation',$data);
			$this->load->view('footer');
		}
	}

	public function get_date_wise_paper_calculation(){
		$data['category']=$exam_center = $this->input->post('category');
		$data['exam_date']=$exam_date = $this->input->post('exam_date');
		$data['shift']=$shift = $this->input->post('shift');
		$this->db->select('*');
		$this->db->from('exam_center');
		
		$this->db->where('id',$exam_center);	
		$data['exam_centers'] = $this->db->get()->result();

/*
		$this->db->select('DISTINCT(paper_master.id),exam_date,exam_shift,exam_day,paper_master.paper_code,paper_master.paper_name,paper_master.course_group_id,paper_master.class_id');
		$this->db->from('paper_master');
		$this->db->join('new_exam_form_report', 'new_exam_form_report.paper_id = paper_master.id');
		$this->db->join('student_report', 'student_report.student_id = new_exam_form_report.student_id');
		$this->db->where('student_report.new_exam_form!=','D' );
		$this->db->where('paper_master.exam_date!=',"");
		if($exam_date)	{
			$edate=date("Y-m-d", strtotime($exam_date));
			$this->db->where('paper_master.exam_date',$edate);
		}
			
		if($shift)	
			$this->db->where('paper_master.exam_shift',$shift);
		$this->db->where('student_report.exam_center_id', $exam_center );
		$this->db->group_by('paper_master.exam_date');

		
		//$this->db->order_by('paper_master.exam_date');
		$data['papers'] = $this->db->get()->result();
		echo $this->db->last_query();die; */

		$where="";
		//if($exam_center!='All')
		//	$where.="AND `student`.`exam_center_id` = '".$exam_center."'";
		if($exam_date!='All')	{
			$edate=date("Y-m-d", strtotime($exam_date));
			$where.="AND paper_master.exam_date = '".$edate."'";
		}
		if($shift!='All')
		$where.="AND paper_master.exam_shift = '".$shift."'";

		$where.="   GROUP BY `paper_master`.`exam_date`";

		 $sql="SELECT DISTINCT(paper_master.id), `exam_date`, `exam_shift`, `exam_day`, `paper_master`.`paper_code`, `paper_master`.`paper_name`, `paper_master`.`course_group_id`, `paper_master`.`class_id` FROM `paper_master` JOIN `student` ON `student`.`class_id` = `paper_master`.`class_id` WHERE `paper_master`.`type` = 'theory' AND `paper_master`.`exam_date` != '' AND paper_master.exam_date!='0000-00-00'  ".$where; 
		
		$query = $this->db->query($sql);
        $data['papers'] = $query->result();
		//echo $this->db->last_query(); die;
		echo $this->load->view('admin/exam_center/get_date_wise_paper_calculation',$data, TRUE);
	}

	public function regular_exam_controller($method,$admin_id)
	{
		$admin_id = $this->Common_model->encrypt_decrypt($admin_id,'decrypt');
		
		$check_user = $this->Common_model->getRecordById('admin_master','id',$admin_id);
			if($check_user){	
				$data = array('loged_in' => true,
					'adminData' => $check_user->name,
					'account_type' => $check_user->account_type,
					'admin_id' => $check_user->id
				);
		$this->session->set_userdata($data);
		redirect(base_url('examController/'.$method));
	}
}// class
