<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Examcenter extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Exam_center_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');
	}

	public function index(){
		if($this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
		}else{			
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
	    	$this->load->view('examcenter/login',$csrf);
		}
	}

	public function dashboard(){

		if(!$this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/login'));
		}else{
			$titleData = array('title' => 'Exam center Dashboard'); 
			$this->load->view('examcenter/header',$titleData);
			$id =  $this->session->exam_center_id;
			$exam_center = $this->Common_model->getRecordById('exam_center','id',$id);
			$data = array('exam_center' => $exam_center);
			$this->load->view('examcenter/dashboard',$data);
			$this->load->view('examcenter/footer');
		}
	}

	public function login(){
		if($this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
			exit;
		}
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('examcenter/login',$csrf);
	}

	public function loginSub(){

		if($this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
			exit;
		}
		$this->form_validation->set_rules('exam_center', 'Exam Center', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('examcenter/login',$csrf);
		}else{
			$username = $_POST['exam_center'];
			$password = $_POST['password'];
			$check_user = $this->Exam_center_model->checkcenter($username,$password);
			if($check_user){
				$data = array(
					'loged_in' 	  => true,
					'Examcenterdata' => $check_user->examcentercode,
					'password' 	  	  => $check_user->password,
					'exam_center_id'  => $check_user->id
				);
				$this->session->set_userdata($data);
				redirect(base_url('Examcenter/dashboard'));
			}else{
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
				$this->session->set_flashdata('error','Exam Center Code or Password are incorrect');
				$this->load->view('examcenter/login',$csrf );
			}
		    }
	        }


	public function logout()
	 {
		$this->session->sess_destroy();
		redirect(base_url('Examcenter/login'));
	 }

	 //Exam Center Wise Paper Count by Date & Shift
	public function exam_center_wise_paper_count_report(){
		if(!$this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
			exit;
		}else
		{
			$titleData = array('title' => 'Paper Count By Date'); 
			$this->load->view('examcenter/header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('*');
			$this->db->from('exam_center');
			$data['exam_centers'] = $this->db->get()->result();
			$this->db->select('*');
			$this->db->from('paper_master');
			$this->db->where('exam_date!=',"");
			$this->db->where('exam_date!=',"0000-00-00");	
			$this->db->group_by('exam_date');
			$this->db->order_by('exam_date', "asc");
			$data['examDate'] = $this->db->get()->result();

			$this->load->view('examcenter/exam_center_wise_paper_count_report',$data);
			$this->load->view('examcenter/footer');
		}
	}

	public function get_exam_center_wise_paper_count_report(){
		$data['exam_center']=$exam_center = $this->input->post('exam_center');
		$data['exam_date']=$exam_date = $this->input->post('exam_date');
		$data['shift']=$shift = $this->input->post('shift');
		$this->db->select('*');
		$this->db->from('exam_center');
		
		$this->db->where('id',$exam_center);	
		$data['exam_centers'] = $this->db->get()->result();


		$this->db->select('DISTINCT(paper_master.id),exam_date,exam_shift,exam_day,paper_master.paper_code,paper_master.paper_name,paper_master.course_group_id,paper_master.class_id');
		$this->db->from('paper_master');
		$this->db->join('new_exam_form_report', 'new_exam_form_report.paper_id = paper_master.id');
		$this->db->join('student_report', 'student_report.student_id = new_exam_form_report.student_id');
		$this->db->where('student_report.new_exam_form=','Y' );
		$this->db->where('paper_master.exam_date!=',"");
		if($exam_date!='All')	{
			$edate=date("Y-m-d", strtotime($exam_date));
			$this->db->where('paper_master.exam_date',$edate);
		}
			
		if($shift)	
			$this->db->where('paper_master.exam_shift',$shift);
		$this->db->where('student_report.exam_center_id', $exam_center );
		$this->db->group_by('paper_master.exam_date');

		
		$this->db->order_by('paper_master.exam_date');
		$data['papers'] = $this->db->get()->result();
	//	echo $this->db->last_query();die; 
	/*	$where="";
		if($exam_center!='All')
			$where.="AND `student_report`.`exam_center_id` = '".$exam_center."'";
		if($exam_date!='All')	{
			$edate=date("Y-m-d", strtotime($exam_date));
			$where.="AND paper_master.exam_date = '".$edate."'";
		}
		if($shift!='All')	
		$where.="AND paper_master.exam_shift = '".$shift."'";

		$where.="   GROUP BY `paper_master`.`exam_date`";

		 $sql="SELECT DISTINCT(paper_master.id), `exam_date`, `exam_shift`, `exam_day`, `paper_master`.`paper_code`, `paper_master`.`paper_name`, `paper_master`.`course_group_id`, `paper_master`.`class_id` FROM `paper_master` JOIN `student_report` ON `student_report`.`class_id` = `paper_master`.`class_id` WHERE `paper_master`.`type` = 'theory' AND `paper_master`.`exam_date` != '' AND paper_master.exam_date!='0000-00-00'  ".$where; 
		
		$query = $this->db->query($sql);
        $data['papers'] = $query->result();*/
		echo $this->load->view('examcenter/exam_center_paper_count_report_show',$data, TRUE);
	}

	 //Search Attendance sheet by student detail
	 public function search_attendance_sheet(){
		if(!$this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
			exit;
		}else
		{
			$titleData = array('title' => 'Search Attendance Sheet of student'); 
			$this->load->view('examcenter/header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			// $this->db->select('*');
			// $this->db->from('exam_center');
			// $data['exam_centers'] = $this->db->get()->result();
			// $this->db->select('*');
			// $this->db->from('paper_master');
			// $this->db->where('exam_date!=',"");
			// $this->db->where('exam_date!=',"0000-00-00");	
			// $this->db->group_by('exam_date');
			// $this->db->order_by('exam_date', "asc");
			// $data['examDate'] = $this->db->get()->result();

			$this->load->view('examcenter/search_attendance_sheet',$data);
			$this->load->view('examcenter/footer');
		}
	}

}
