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
	}

	public function index(){
			
			if($this->session->has_userdata('adminData')){
				$admin_id = $this->session->admin_id;
				$where = 'admin_id='.$admin_id.' and status="Y"';
				$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
				);
				$this->load->view('header',array('title' => 'Enrollment Section'));
				$this->load->view('admin/dashboard',$menu);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
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
     		'session' => $_POST['session'],
     		'approved' =='Y'
     	);
     	$students = $this->db->get_where('student',$where)->result_array();

     	$last_number = str_pad($count,3,'0',STR_PAD_LEFT);
     	$year = $_POST['session'];
     	$lastTwoNumbers = (int) substr($year, -2);

     	$month =  strtok($_POST['session'], " "); 

     	$enrolment_code = $this->db->get_where('session', array('session'=>$_POST['session']))->result_array();

     	foreach($students as $student){

     		if($month=='Jan'){
     			$enrollment = $enrolment_code[0]['enrollment_code'].$lastTwoNumbers.'1'.str_pad($last_number,3,"0",STR_PAD_LEFT );

     		}else{
     			$enrollment = $enrolment_code[0]['enrollment_code'].$lastTwoNumbers.'2'.str_pad($last_number,3,"0",STR_PAD_LEFT );

     		}
     		$last_number++ ;

     		$whereUpdate = array('student_id' => $student['student_id']);
     		$updateData = array('enrollment_no' =>$enrollment);
     		$updateEnrollment = $this->Common_model->updateRecordByConditions('student',$whereUpdate,$updateData);
     	}

     	if($updateEnrollment){
     		echo json_encode(array("status" => 'true'));
     		redirect(base_url('admin/examController/session'));
     	}

     }

	 public function enrollment_permission(){
		if(!isset($_POST['action'])){
		
			$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no not in ("-")  and enrolled="N"','enrollment_no','ASC');
		
			$data = array(
			'students' => $student,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			);
		
			$this->load->view('header',array('title' => 'Enrollment Permission'));
			$this->load->view('admin/enrollment/set_enrollment_permission',$data);
			$this->load->view('footer');
			}else if($_POST['action']=='setPermission'){
	
			
			$enrollment_nos = $this->input->post('enrollment_no');
			
			foreach($enrollment_nos as $enrollment_no){				
				$data = array('enrolled' => 'Y');
				$where = 'enrollment_no="'.$enrollment_no.'" ';
				$this->Common_model->updateRecordByConditions('student',$where,$data);
			
			}
			$this->session->set_flashdata('ajax_flash_message','permission updated');
			redirect(base_url().'admin/ExamController/enrollment_permission');		
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
}// class
