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
				$result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id' =>$student->student_id));
			
				$data = array(
					'student' => $student,
					'studentContactData'=>$studentContactData,
					'documentDetails' => $documentDetails,
					'result' => $result,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				if($data){
					$dt =  $this->load->view('admin/verification/view_student_documents',$data,true);
					$status = true;
				}else{
					$dt = "This Student Not Found !";
					$status = false;
				}
				echo json_encode(array(
					"status" => $status,
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
		$data['old_result_data']  = $new_exam_form;
		$data['class_id']  = $new_exam_form[0]->class_id;
		$data['exam_data_id']=$exam_data_id ;
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136);
		// $title = array('title' => 'Result');
		$data['exam_data'] = $this->Common_model->getRecordById('old_exam_data','id',$exam_data_id);
		// $course_id !=36 && $course_id !=37
		//$class = $this->Common_model->getRecordByID('class_master','id', $data['exam_data']->class_id);
		$data['class'] = $this->Common_model->getRecordByID('class_master','id', $data['exam_data']->class_id);
		$this->load->view('admin/generate_tr/header2',$title);
		$this->load->view('admin/old_marksheet_top',$data);
		
		// if((in_array($new_exam_form[0]->class_id , $class_ids)) && $data['exam_data']->university_mode=='REG'){
		// 	$this->load->model('Gradesheet_old_model');
		// 	$this->load->view('admin/grade_marksheet',$data);
		// }else if($data['exam_data']->university_mode !="PVT" || $class->internal !='N'){
			
		// 	$this->load->view('admin/marksheet_student',$data);
		// }else{
			
		// 	$this->load->view('admin/marksheet_student_pvt',$data);
		// }
	
		if((in_array($new_exam_form[0]->class_id , $class_ids)) && $data['exam_data']->marks_pattern=='GRADE'){
            $this->load->model('Gradesheet_model');
			$this->load->model('Gradesheet_old_model');
			$this->load->view('admin/grade_marksheet',$data);
		}else if($data['class']->cbcs=='Y' && $data['exam_data']->university_mode=='REG' && $data['exam_data']->marks_pattern=='GRADE'){
				$this->load->model('GradeSheet_old_model_pg');
				$this->load->view('admin/grade_marksheet_pg',$data);
		}else if($data['exam_data']->university_mode !="PVT"  && $data['class']->internal !='N'){
			$this->load->view('admin/marksheet_student',$data);
		}else{
			
			$this->load->view('admin/marksheet_student_pvt',$data);
		}
		$this->load->view('admin/generate_tr/footer2');
	}
	
}
