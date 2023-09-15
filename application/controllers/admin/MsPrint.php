<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MsPrint extends CI_Controller {

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
		if($this->session->account_type!='MsPrint'){
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
		$this->load->view('header',array('title' => 'MS Print'));
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
				// $this->db->where_not_in('exam_year',array('February 2023','March 2023'));
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
		$data['old_result_data']  = $new_exam_form;
		$data['class_id']  = $new_exam_form[0]->class_id;
		$title = array('title' => 'Result');
		$data['exam_data'] = $this->Common_model->getRecordById('old_exam_data','id',$exam_data_id);
		
		$data['student'] = $this->Common_model->getRecordById('student','student_id', $data['exam_data']->student_id);
		// $course_id !=36 && $course_id !=37
		$class = $this->Common_model->getRecordByID('class_master','id', $data['exam_data']->class_id);

		$class_ids=array(101,104,107,110,116,119,125,128,131,134);
		$class_cbcs = array(193,197,201,203,205,211,213,221,223,225,227,275,279);
		if($data['exam_data']->university_mode == "REG" && in_array($data['class_id'] , $class_ids)){
			$this->load->model('Gradesheet_old_model');
			$dt =  $this->load->view('admin/msprint/student_marksheet_grade',$data);
		}elseif ($data['exam_data']->marks_pattern=='GRADE' && $data['exam_data']->university_mode == "REG" && in_array($data['class_id'] , $class_cbcs)) {
			$this->load->model('GradeSheet_old_model_pg');
			$dt =  $this->load->view('admin/msprint/student_marksheet_grade_pg',$data);
		}else if ($data['exam_data']->exam_status == "B") {
			$this->load->view('admin/msprint/old_backlog_student_marksheet',$data);
		}else if($class->internal=="Y" && $data['exam_data']->university_mode!="PVT" ){ 
			$this->load->view('admin/msprint/old_student_marksheet',$data);
		}else{
			$this->load->view('admin/msprint/old_student_marksheet_certificate',$data);
		}

		// $this->load->view('admin/generate_tr/header2',$title);
		// $this->load->view('admin/old_marksheet_top',$data);
		// $this->load->view('admin/marksheet_student',$data);
		// $this->load->view('admin/generate_tr/footer2');
	}
	public function center_wise_marksheet_dispatch(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Center Wise MarkSheet Dispatch '); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('DISTINCT(center_id)');
			$this->db->from($this->result_table);
			$this->db->where(array('exam_form'=>'Y'));
			$centers = $this->db->get()->result_array();
			$ids = array_column($centers, 'center_id');
			//print_r($ids);die;
			$this->db->select('*');
			$this->db->from('center');
			$this->db->where_in('id',$ids);
			$this->db->order_by('center_code', "asc");
			$data['centers'] = $this->db->get()->result();
			
			$this->load->view('admin/examController/center_wise_marksheet_dispatch',$data);
			$this->load->view('footer');
		}
	}

	public function get_center_wise_marksheet_dispatchlist(){
		$center = $this->input->post('center');
		$this->db->select('DISTINCT(center_id)');
		$this->db->from($this->result_table);
		$this->db->where(array('exam_form'=>'Y'));
		$centers = $this->db->get()->result_array();
		$ids = array_column($centers, 'center_id');
	
		$this->db->select('*');
		$this->db->from('center');
		if($center!="All")
			$this->db->where( array('id'=>$center));
		else
			$this->db->where_in('id',$ids);
		$this->db->order_by('center_code', "asc");
		$data['centers'] = $this->db->get()->result();
		$data['examTitle'] = "March 2023";
		
		echo $this->load->view('admin/examController/get_center_wise_marksheet_dispatchlist',$data, TRUE);
	}

	public function update_marksheet_date(){
		
		if ($this->input->method() == "post") 
		{ 
			if(isset($_POST['remark_date'])){ $remark = $this->input->post('remark_date');}else{ $remark = '';}
			$marksheet_date  = $this->input->post("marksheet_date");
			$record_id  = $this->input->post("record_id");
	      	$record_id = $this->Common_model->encrypt_decrypt($record_id,'decrypt');
			
			$updateData = array(
				'marksheet_date' => date('d/m/Y', strtotime($marksheet_date)) ,
				'remark_date' => $remark
			);
		
			$where = array(
				'id'=> $record_id
			);
			
			$response = $this->Common_model->updateRecordByConditions('old_exam_data',$where,$updateData);

			if($response){
			echo json_encode(array("status" => 'true'));
			}
		}
	}

	public function view_center_wise_complaint(){
			
		if($this->session->has_userdata('adminData')){
			$admin_id = $this->session->admin_id;
			$admin = $this->Common_model->getRecordById('admin_master','id',$admin_id);
			$where = "support_complaint.status = 'Pending' AND support_system.id IN (".$admin->support_ids.")";
				$this->db->select('count(*) as count,'.'center_id');
				$this->db->from('support_complaint');
				$this->db->join('support_system','support_system.name = support_complaint.type');
				$this->db->where($where);
				$this->db->group_by('center_id');
				$centers = $this->db->get()->result_array();

			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
			
			$titleData = array('title' => 'Complaints');
			$this->load->view('header',$titleData);
			$this->load->view('admin/view_center_wise_complaint',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url());
		}
	}

}