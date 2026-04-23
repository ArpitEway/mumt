<?php
	include_once(APPPATH.'core/ADMIN_controller.php');
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Verification extends CI_Controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('admin/admin_model');
			$this->load->model('admin/Account_model');
			$this->load->model('Common_model');
			$this->load->model('Datatable_join_model');
			$this->master = $this->Common_model->getSingleRow('master');
			$this->exam_table = $this->master->student_exam_table;
			$this->exam_form = $this->master->exam_form_col;
			$this->exam_form_result = $this->master->exam_form_col_result;
			$this->roll_no = $this->master->roll_number_col;
			$this->result_table = $this->master->student_result_table;
			$this->old_result_table = $this->master->old_student_result_table;
			$this->exam_form_table = $this->master->exam_form_table;
			$this->old_exam_form_table = $this->master->old_exam_form_table;
			if($this->session->account_type!='Verification'){
				redirect(base_url('admin/logout')); 
			}
		}
		
		public function index(){
			
			if($this->session->has_userdata('adminData')){
				$admin_id = $this->session->admin_id;
				$where = 'admin_id='.$admin_id.' and status="Y"';
				$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
				);
				$this->load->view('header',array('title' => 'Verification Section'));
				$this->load->view('admin/verification/dashboard',$menu);
				$this->load->view('footer');
			}
			else
			{
				redirect(base_url('admin/login'));
			}
		}

		public function dashboard(){
			
			redirect(base_url('admin/verification'));
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

	public function degree_verification(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$this->load->view('header',array('title' => 'Search Degree Details'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);

			$this->load->view('admin/degree_verification',$data);
			$this->load->view('footer');
		}	
	}

	public function get_degree_details(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
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
				//$mobile_number = $this->Common_model->getSinglefield('student_data','p_mobile_no',array('student_id' => $student->student_id));
				$studentContactData = $this->Common_model->getRecordById('student_data','student_id',$student->student_id);
				$this->db->order_by('id');
				$documentDetails = $this->Common_model->getRecordByWhere('admission_document',array('student_id' => $student->student_id ));
			
				$data = array(
					'student' => $student,
					'studentContactData'=>$studentContactData,
					'documentDetails' => $documentDetails,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				if($documentDetails){
					$dt =  $this->load->view('admin/verification/view_student_documents',$data,true);
					$status = true;
				}else{
					$dt = "This student Does Not Have Any Documents Uploaded !";
					$status = false;
				}
				echo json_encode(array(
					"status" => $status,
					"data" => $dt
				));
			}
		}
	}

	
}
