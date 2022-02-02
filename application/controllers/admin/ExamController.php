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
			$this->load->view('header',array('title' => 'ExamController Section'));
			$this->load->view('admin/examController/dashboard');
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
	
	 $this->load->view('header',array('title' => 'Sessions'));
	 $this->load->view('admin/examController/generate_enrollment',$data);
	 $this->load->view('footer');
	}
   

	 public function enrollment_permission(){
		if(!isset($_POST['action'])){
		
			$student = $this->Common_model->getRecordByWhereByOrder('student','approved="Y" and enrollment_no not in ("-")  and enrolled="N"','enrollment_no','ASC',100);
		
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
