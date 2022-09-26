<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class CampusIncharge extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='CampusIncharge'){
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
		$this->load->view('header',array('title' => 'Campus Incharge Section'));
		$this->load->view('admin/campusincharge/dashboard',$menu);
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
		$this->load->view('admin/campusincharge/consolidate_report',$dt);
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
			else{
				$dt = 'center_id in (20,21,22,23,24,25,26,27,28) ';

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

			if($filter == "list"){

				$data['students'] = $this->Common_model->student_data_consolidate($dt);
			}

			if($filter == "count"){				
				$data['course_count'] = $this->Common_model->student_data_consolidate($dt,$_POST['count_filter']);
			}

			$dt = $this->load->view('admin/student/getStudentConsolidate',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));

		}
	}


	public function search_student(){

		$this->load->view('header',array('title' => 'Search Students'));

		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
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
				$where = array('name'=>$text_val

			);

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

			$data = array();
			$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt'); 
			$data['student'] = $this->Common_model->student_info($student_id);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->load->view('header',array('title' => 'Admission Form'));	
			$this->load->view('template/form',$data);
			$this->load->view('footer');
		}


     public function show_paper($student_id){

    	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
    	$titleData = array('title' => 'Student Papers');
    	$this->load->view('header',$titleData);
    	$where = array(
    		'student_id' => $student_id,
    	);
    	$student = $this->Common_model->student_info($student_id);
    	$data['student'] = $student;
    	
    	$this->db->select('paper_master.*,new_exam_form.sub_group_id');
    	$this->db->from('paper_master');
    	$this->db->order_by('new_exam_form.sub_group_id,paper_order');
    	$this->db->join('new_exam_form', 'paper_master.paper_code = new_exam_form.paper_code and  paper_master.class_id = new_exam_form.class_id');
    	$where = array('paper_master.class_id' => $student['class_id'],
    		'student_id' => $student_id
    	);
    	$this->db->where($where);
    	$data['papers'] = $this->db->get()->result();
    	$this->load->view('admin/student/show_paper',$data);
    	$this->load->view('footer');
    }


}
