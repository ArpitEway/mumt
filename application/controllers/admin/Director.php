<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Director extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Account_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='Director'){
			redirect(base_url('admin/logout')); 
		}
	}

	public function index(){

		if($this->session->has_userdata('adminData')){

       // redirect(base_url('admin/director/bulk_permission'));
       
			$admin_id = $this->session->admin_id;

			$where = 'admin_id='.$admin_id.' and status="Y"';

			$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
			);

			$this->load->view('header',array('title' => 'Director Section'));
			$this->load->view('admin/director/dashboard',$menu);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}
	public function dashboard(){

		if($this->session->has_userdata('adminData')){

			$admin_id = $this->session->admin_id;

			$where = 'admin_id='.$admin_id.' and status="Y"';

			$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
			);

			$this->load->view('header');
			$this->load->view('admin/director/dashboard',$menu);

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
			$data['sessions']  = $this->db->get_where('session', array())->result_array();
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->load->view('admin/director/consolidate_report',$data);
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

			$course_group_id  = 	$this->input->post("course_group_id");
			$class_id  		  = 	$this->input->post("class_id");
			$approved 		  = 	$this->input->post("approved");
			$payment 		  = 	$this->input->post("payment");
			$enrolled 		  = 	$this->input->post("enrolled");
			$document_upload  = 	$this->input->post("document_upload");
			$filter  		  = 	$this->input->post("filter");
			$session 		  = 	$this->input->post("session");
			$mode 		  	  = 	$this->input->post("mode");
			$admission_mode  = 	$this->input->post("admission_mode");
			$center 	  	  = 	$this->input->post("center");
       
			if($center != "all"){	 

				$dt['center_id'] = $center;
			}
			if($mode != "all"){	 

				$dt['mode'] = $mode;
			}

         if($admission_mode != "all"){	 

				$dt['student.university_mode'] = $admission_mode;
			}

			if($session != "all"){	 

				$dt['session'] = $session;
			}else{
				$dt['name!='] = '';
			}

			if($filter == "course"){

				$data['course_count'] = $this->Common_model->student_data_consolidate($dt,'course_group_id');
				
			}
			if($filter == "center"){

				$data['center_count'] = $this->Common_model->student_data_consolidate($dt,'center_id');

			}


			$dt = $this->load->view('admin/director/getStudentConsolidate',$data,true);

			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		}

	}
	


	public function enrollment_status($session=0)
	{
		$data['sessions'] = $this->db->get_where('session', array('enrollment_permission' => 'Y'))->result_array();
		//print_r($data['sessions']);die;
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
		//$session_july='July 2021';		// All Class

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

        //---paid/uploaded/ is = approved---
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

		// enrolled
		$where = array('enrolled'=>'N','enrollment_no !='=>'-','session'=>$session_july);
		$data['tot_not_enrolled'] = $this->Common_model->getCountByWhere('student',$where);

  		//echo '<pre>';
		// print_r($data);die;

		$this->load->view('header');
		$this->load->view('admin/enrollment/enrollment_status_count',$data);
		$this->load->view('footer');
	}

	

	public function bulk_permission(){

		if($this->session->has_userdata('adminData')){

			$admin_id = $this->session->admin_id;

			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);

			$this->load->view('header',array('title' => 'Bulk Permission For All Centers'));
			$this->load->view('admin/director/bulk_permission',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

	public function update_center_permission()
	{

		if ($this->input->method() == "post") 
		{

			$parameter1   	= $this->input->post("param_name");
			$permisssion    = $this->input->post("permission");

			$data = array($parameter1 => $permisssion);
			
			$res = $this->Common_model->updateRecordByConditions('center',$where,$data);
			
			//echo $this->db->last_query();


			$dt = $this->db->get_where("center",array($parameter1 => $permisssion))->row();

			if($dt->$parameter1 == 'Y')
			{
				$sts_btn = '<a class="btn btn-primary" onclick="update_permission(\''.$parameter1.'\',\'N\')">All Yes</a>';
			}

			else{
				
				$sts_btn = '<a class="btn btn-danger" onclick="update_permission(\''.$parameter1.'\',\'Y\')">All No</a>';
				
				
			}


			echo json_encode(array(
				"status" => $res,
				"msg" => "Permission has been updated successfully",
				"sts_btn"=>$sts_btn,
				"p1"=>$parameter1,
			));

		}
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
				
			$data['params'] = $param ;
			// All Class
			$this->db->select('COUNT(student_id) as student_count,center_id,center_code,
				center_name,center_id');
			$this->db->group_by('center_id');
			$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
			// echo $this->db->last_query();
			// echo'<pre>';
			// print_r($data);die;
			$this->load->view('header',$msg);
			$this->load->view('admin/enrollment/center_wise_list',$data); 
			$this->load->view('footer');

				
			
		}else{
		
			  return redirect(base_url().'director/enrollment_status');
		}

		
		//
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
        
		    
			$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
			$this->load->view('header',array('title' => 'Center Wise Student List'));
			$this->load->view('admin/enrollment/students_count_details',$data); 
			$this->load->view('footer');
		}else
		{
			redirect(base_url('admin/'.$this->session->account_type.'/enrollment_status'));
		}
	}


}