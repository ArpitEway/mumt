<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ViceChancellor extends CI_Controller {

	public $master = array();
	public $exam_table;
	public $exam_form;
	public $roll_no ;
	public $exam_form_table;


	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		$this->master = $this->Common_model->getSingleRow('master');
		 $this->exam_table = $this->master->student_exam_table;
		 $this->exam_form = $this->master->exam_form_col;
		 $this->roll_no = $this->master->roll_number_col;
		 $this->result_table = $this->master->student_result_table;
		 $this->exam_form_table = $this->master->exam_form_table;
		 $this->old_result_table = $this->master->old_student_result_table;
		 $this->old_exam_form_table = $this->master->old_exam_form_table;
		if($this->session->account_type!='ViceChancellor'){
			redirect(base_url('admin/logout')); 
		}
	}

	public function index()
	{
		$admin_id = $this->session->admin_id;
		$where = 'admin_id='.$admin_id.' and status="Y"';
		$menu = array(
			"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
			"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
		);
		$this->load->view('header',array('title' => 'Vice Chancellor'));
		$this->load->view('admin/Enquiry/dashboard',$menu);
		$this->load->view('footer');
	}

	public function student_result(){
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
				 $this->db->where_not_in('exam_year',array('January 2024'));
				$result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id' =>$student->student_id));
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
		if($course_id==12){
			$this->db->select('*');
			$this->db->from('old_result_data');
			$this->db->where('old_result_data.exam_data_id',$exam_data_id);
			$this->db->join('group', 'old_result_data.group_id = group.id');
			$this->db->order_by('sub_group_id,p_order','ASC');
			$new_exam_form = $this->db->get()->result();
		}
		$data['old_result_data']  = $new_exam_form;
		$data['class_id']  = $new_exam_form[0]->class_id;
		$title = array('title' => 'Result');
		$data['exam_data'] = $this->Common_model->getRecordById('old_exam_data','id',$exam_data_id);
		$data['exam_data_id']=$exam_data_id;
		$data['student'] = $this->Common_model->getRecordById('student','student_id', $data['exam_data']->student_id);
		// $course_id !=36 && $course_id !=37
		$class = $this->Common_model->getRecordByID('class_master','id', $data['exam_data']->class_id);

		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
		if ($data['exam_data']->exam_status == "B") { //Backlog
			if($data['exam_data']->marks_pattern == "GRADE" && in_array($data['class_id'] , $class_ids)){
				$this->load->model('Gradesheet_old_model');
				  $this->load->view('admin/msprint/backlog_student_marksheet_grade',$data);// student_marksheet_grade
			}
			elseif($class->internal=="Y"  && $data['exam_data']->university_mode!="PVT" ){
				$this->load->view('admin/msprint/old_backlog_student_marksheet',$data);
			}
			else{
				$this->load->view('admin/msprint/old_backlog_student_marksheet_certificate',$data);
			}
		}
		else{ // Main 
			if($data['exam_data']->marks_pattern == "GRADE" && in_array($data['class_id'] , $class_ids)){
				$this->load->model('Gradesheet_old_model');
				  $this->load->view('admin/msprint/student_marksheet_grade',$data);
			}elseif ($data['exam_data']->marks_pattern=='GRADE' && $data['exam_data']->university_mode == "REG" &&  $class->cbcs=='Y' && $data['exam_data']->marks_pattern=='GRADE') {
				$this->load->model('GradeSheet_old_model_pg');
				 $this->load->view('admin/msprint/student_marksheet_grade_pg',$data);
			}else if($class->internal=="Y" && $data['exam_data']->university_mode!="PVT" ){ 
				$this->load->view('admin/msprint/old_student_marksheet',$data);
			}else{
				$this->load->view('admin/msprint/old_student_marksheet_certificate',$data);
			}
		}

		
	}
	

	

	
	
	public function search_student_marksheet(){
		
		$segment = $this->uri->segment(2);
		
		$this->load->view('header',array('title' => 'Search Students Result'));

		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'segment' => $segment
		);

		$this->load->view('admin/msprint/search_student_marksheet',$data);
		$this->load->view('footer');
	}

   

    

	public function getStudentMarksheetData()
	{
		// if(!$this->session->has_userdata('adminData')){
		// 	redirect(base_url());
		// 	exit;
		// }

		$text_val =$this->input->post('text_val');
		$radio_val = $this->input->post('radio_val');


		if($text_val !='')
		{
			if($text_val !='' && $radio_val == 'enrollment_no')
			{
				$where = array('exam_form'=>'Y','enrollment_no'=>$text_val,'old_result_show'=>'Y');
				//,'result_show'=>'Y'

			}else if($text_val !='' && $radio_val == 'roll_no')
			{
				$where = array('exam_form'=>'Y','roll_number'=>$text_val ,'old_result_show'=>'Y');
			//,'result_show'=>'Y'
			}

			
				$student = $this->Common_model->getRecordByWhere($this->result_table,$where);
				//print_r($student); die;
				$msg="";
				if (count($student)==0) {
					
					echo json_encode(array(
						"status" => false,
						"data" => "<p style='text-align: center;'><b>No data found!</b></p>"
					));
					
				}else{
			
					if($student[0]->old_result_show =="N"){
						
							$msg="<p style='text-align: center;' id='result_msg'><b>Student result not declared!</b></p>"; 
					}
						$data['student']=$student[0];
						$data['exam_session']  = 'July 2023';
						/**********************/
						if($data['student']->provisional_remark!="N" && $data['student']->provisional_remark!="")
						{
							$this->db->select('provisional_remarks');
							$this->db->from('provisional_remark_details');
							$this->db->where('document_category_id',$data['student']->provisional_remark);
							$remark = $this->db->get()->row();
							$provisional_remark_details ="<p style='text-align: center;' id='pro_remark'><b>".$remark->provisional_remarks." are not recieved at university</b></p>";
						}
						/************************/
						
						$classData = $this->Common_model->getRecordById('class_master','id',$data['student']->old_class_id);
						$data['practical_internal_marks']=$classData->practical_internal_marks;
						$this->db->select('*');
						$this->db->from($this->exam_form_table);
						$this->db->where(''.$this->exam_form_table.'.student_id',$data['student']->student_id);
						$this->db->where(''.$this->exam_form_table.'.class_id',$data['student']->old_class_id);
						$new_exam_form = $this->db->get()->result();
						$data['classData']  = $classData;
						$data['new_exam_form']  = $new_exam_form;
						// if(($data['student']->old_class_id == '104' || $data['student']->old_class_id == '107' || $data['student']->old_class_id == '101' || $data['student']->old_class_id == '134' || $data['student']->old_class_id == '116' || $data['student']->old_class_id == '110'|| $data['student']->old_class_id == '119' || $data['student']->old_class_id == '131') && $data['student']->university_mode == 'REG')
						
						$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
						$class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
						if((in_array($data['student']->class_id, $class_ids))  && $data['student']->exam_pattern=='GRADE')	//&& $data['student']->university_mode=='REG'
						{
							$this->load->model('Gradesheet_model');
							$dt = $provisional_remark_details.$msg.$this->load->view('Centers/grade_marksheet',$data,true);
						}else if((in_array($data['student']->class_id, $class_cbcs)) && $data['student']->university_mode=='REG' && $data['student']->exam_pattern=='GRADE'){
							$this->load->model('Gradesheet_model_pg');
							$dt = $provisional_remark_details.$msg.$this->load->view('Centers/grade_marksheet_pg',$data,true);
						}else{
							
							$title = array('title' => 'Result - '.$data['student']->enrollment_no);
							
							$marksheet_top =  $this->load->view('Centers/marksheet_top',$data,true);
							// if ($student[0]->course_group_id==36 || $student[0]->course_group_id==37) {
								
							// 	$marksheet_bottom=  $this->load->view('Centers/marksheet_without_int',$data,true);
							// }else{
								
							// 	$marksheet_bottom=  $this->load->view('Centers/marksheet_bottom',$data,true);
							// }
							if($classData->internal=='N'){
								$marksheet_bottom = $this->load->view('Centers/marksheet_without_int',$data,true);
							}else{
								if($student[0]->class_id=='168'){
									$marksheet_bottom  = $this->load->view('Centers/marksheet_mom',$data,true);
								}else{
									$marksheet_bottom = $this->load->view('Centers/marksheet_bottom',$data,true);
								}
							// $dt =  $marksheet_top.$marksheet_bottom;
							}
						
						
							$dt =$provisional_remark_details. $msg. $marksheet_top.$marksheet_bottom;
						
						}
						echo json_encode(array(
							"status" => true,
							"data" => $dt
						));
					 }
		
	  }
	}//fun




	

	
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

	public function show_form($student_id){

		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt'); 

		$student = $this->Common_model->student_info($student_id);
		$data = array(
			'student' => $student,
		);
		$this->load->view('header',array('title' => 'Admission Form'));
		$this->load->view('template/form',$data);
		$this->load->view('footer');
	}//fun
	
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
			$course_type 		  = $this->input->post("course_type");

			$payment 		  = $this->input->post("payment");
			$enrolled 		  = $this->input->post("enrolled");
			$document_upload  = $this->input->post("document_upload");
			$filter  		  = $this->input->post("filter");
			$session 		  = $this->input->post("session");
			$mode 		  	  = $this->input->post("mode");
			$center_id	  	  = $this->input->post("center_id");
			$university_mode	  	  = $this->input->post("university_mode");
			$center_type	  = $this->input->post("center_type");

			if($mode != "all"){	 
				
				$dt['mode'] = $mode;
			}
			if($university_mode!="all"){
				$dt['student.university_mode'] = $university_mode ;
			}
			if($course_type != "All"){
				if ($course_type=='UGPG') {
					$this->db->where_in('course_type',array('UG','PG'));
				}else{
					$dt['course_type'] = $course_type;
				}
			}
			if($session != "All") {	 

				$dt['session'] = $session;
			}else  {
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
	
			}
			// if($center_type != "all"){

			// 	$dt['center_type'] = $center_type;
	
			// }


	
			if($filter == "list"){

				$data['students'] = $this->Common_model->student_data_consolidate($dt,"",$center_type);
				
			}
			if($filter == "count"){				
				$data['course_count'] = $this->Common_model->student_data_consolidate($dt,$_POST['count_filter'],$center_type);
			
			}

		//$this->Common_model->last_query();

			$dt = $this->load->view('admin/student/getStudentConsolidate',$data,true);

			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));



		}
	}//fun

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

	}//fun
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

		//Time Table
		public function time_table(){
			$dt = array();
			$titleData = array('title' => 'Course Wise Exam Date ');

			$this->load->view('header',$titleData );
			$dt['name_csrf'] = $this->security->get_csrf_token_name();
			$dt['hash_csrf'] = $this->security->get_csrf_hash();

			$this->db->select('course_group.*');
			$this->db->from('course_group');
			$this->db->join('paper_master', 'paper_master.course_group_id = course_group.id');
			$this->db->where('paper_master.exam_date!=','');
			$this->db->where('paper_master.exam_date!=','0000-00-00');  
			$this->db->where('paper_master.type','theory'); 
		
			$this->db->group_by('paper_master.course_group_id');
			$this->db->order_by('course_group.course_name', 'Asc');
			$dt['courses']= $this->db->get()->result_array();
			$this->load->view('Centers/search_exam_by_course',$dt);
			$this->load->view('footer');

			
		}//fun

		public function exam_form_status(){

			$this->load->view('header',array('title' => 'Exam Form Status(June 2024)'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
	
			$where = array('new_exam_form !=' =>'D');
			$data['permitted_student'] = $this->Common_model->getCountByWhere('student',$where);
	
			$where = array('new_exam_form' =>'Y');
			$data['filled_student'] = $this->Common_model->getCountByWhere('student',$where);
	
			$where = array('new_exam_form ' =>'S');
			$data['skipped_student'] = $this->Common_model->getCountByWhere('student',$where);
	
			$where = array('new_exam_form' =>'N');
			$data['not_filled_student'] = $this->Common_model->getCountByWhere('student',$where);
	
			//backlog
	
			$where = array('exam_form !=' =>'D','exam_year' =>'June 2024');
			$data['permitted_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);
	
			$where = array('exam_form' =>'Y','exam_year' =>'June 2024');
			$data['filled_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);
	
			$where = array('exam_form ' =>'S','exam_year' =>'June 2024');
			$data['skipped_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);
	
			$where = array('exam_form' =>'N','exam_year' =>'June 2024');
			$data['not_filled_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);
	
	
	
			$this->load->view('admin/exam_wise_student_status',$data);
			$this->load->view('footer');
	
		}//fun
	
}