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
		redirect('http://162.144.38.91/~mmyvvdde/main/examcenter/index.php');
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
			$this->db->where('exam_date>=',"2023-07-31");	
			$this->db->where_not_in('course_group_id',array('75','76','77'));
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
		$this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->where('student.new_exam_form=','Y' );
		$this->db->where('paper_master.exam_date!=',"");
		if($exam_date!='All')	{
			$edate=date("Y-m-d", strtotime($exam_date));
			$this->db->where('paper_master.exam_date',$edate);
		}
			
		if($shift)	
			$this->db->where('paper_master.exam_shift',$shift);
		$this->db->where('student.exam_center_id', $exam_center );
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

	//Get search Student Attendance Sheet 
	public function get_search_student_attendance_sheet(){


		 $text_val =$this->input->post('text_val');
		 $radio_val = $this->input->post('radio_val');


		if($text_val !='')
		{
			if($text_val !='' && $radio_val == 'enrollment_no')
			{
				$where = array('enrollment_no'=>$text_val,'new_exam_form'=>'Y');

			}else if($text_val !='' && $radio_val == 'roll_no'){
				$where = array('roll_no'=>$text_val);
			}

			$data['exam_center_students'] = $this->Common_model->student_data($where);
			
			echo $this->load->view('examcenter/get_search_student_attendance_sheet',$data, TRUE);
			
		}		
	}

	 //Search Attendance sheet by student detail
	 public function search_backlog_attendance_sheet(){
		if(!$this->session->has_userdata('Examcenterdata')){
			redirect(base_url('Examcenter/dashboard'));
			exit;
		}else
		{
			$titleData = array('title' => 'Search Backlog Attendance Sheet of student'); 
			$this->load->view('examcenter/header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			

			$this->load->view('examcenter/search_backlog_attendance_sheet',$data);
			$this->load->view('examcenter/footer');
		}
	}
	//Get search Backlog Student Attendance Sheet 
	public function get_search_backlog_student_attendance_sheet(){
		$text_val =$this->input->post('text_val');
		$radio_val = $this->input->post('radio_val');
	   if($text_val !='')
	   {
		   if($text_val !='' && $radio_val == 'enrollment_no')
		   {
			   $where = array('backlog_student.enrollment_no'=>$text_val,'backlog_student.exam_form'=>'Y');

		   }else if($text_val !='' && $radio_val == 'roll_no'){
			   $where = array('backlog_student.roll_no'=>$text_val);
		   }
		  		 
				$this->db->select('backlog_student.*,student.name,student.f_h_name,student.course_name,student.photo');
				$this->db->from('backlog_student');
				$this->db->join('student', 'backlog_student.student_id = student.student_id ' );
				$this->db->order_by("roll_no", "asc");
				$this->db->where('backlog_student.exam_year','June 2023');
				$this->db->where($where);	
				$data['exam_center_students'] = $this->db->get()->result();
		   echo $this->load->view('examcenter/get_search_backlog_student_attendance_sheet',$data, TRUE);
		   
	   }		
   }
	public function regular_exam_center($method,$exam_center_id)
	{
		$exam_center_id = $this->Common_model->encrypt_decrypt($exam_center_id,'decrypt');
		$exam_center = $this->Common_model->getRecordById('exam_center','id',$exam_center_id);
		$data = array(
					'loged_in' 	  => true,
					'Examcenterdata' => $exam_center->examcentercode,
					'password' 	  	  => $exam_center->password,
					'exam_center_id'  => $exam_center->id
				);
				$this->session->set_userdata($data);
				redirect(base_url('Examcenter/'.$method));
	}

	public function search_exam_by_course(){
		$dt = array();
		$titleData = array('title' => 'Course Wise Exam Date ');
		$this->load->view('examcenter/header',$titleData);
		
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
		$this->load->view('examcenter/footer');
	}

}
