<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class updateEnrollmentForm extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('users/User_model');
		if(!$this->session->has_userdata('centerdata')){
			exit();
		}
	}

	public function index($step){
		if($step==1){
			$course_group_id = html_escape($this->input->post('course_group_id'));
			$class_id = html_escape($this->input->post('class_id'));
			$data = array( 
				'session' => html_escape($this->input->post('session')),
				'course_group_id' => $course_group_id,
				'course_name' => $this->Common_model->getCourseNameByCourseId($course_group_id),
				'class_name' => $this->Common_model->getClassNameByClassId($class_id),
				'class_id' => $class_id,
				'eligibility' => html_escape($this->input->post('eligibility')),
				'center_code' => $this->session->centerdata,
				'center_id' => $this->session->center_id,
			);

			$student_id = $this->input->post('student_id');
			if($student_id==''){
				 $student_id = $this->Common_model->insertAll('student',$data);
			}else{
			$this->Common_model->updateRecordByConditions('student',$whereStudent,$data);
			}
			echo $student_id;

		}else if($step==2){

			$student_id = $this->input->post('student_id');
			if(!$this->input->post('photo')){
				$path = './assets/student_image/';
				$upload = $this->do_upload('photo',$path,$student_id);
				$data['photo'] = $upload['file_name'];
			}

			$data['category'] = html_escape($this->input->post('category'));
			$data['gender'] = html_escape($this->input->post('gender'));
			$studentData['freedom_fighter'] = html_escape($this->input->post('freedom_fighter'));
			$studentData['national_award'] = html_escape($this->input->post('national_award'));
			$studentData['NCC_NSS'] = html_escape($this->input->post('NCC_NSS'));
			$studentData['p_handicapped'] = html_escape($this->input->post('p_handicapped'));
			$data['name_hindi'] = html_escape($this->input->post('name_hindi'));
			$data['name'] = html_escape($this->input->post('name'));
			$data['f_h_name_hindi'] = html_escape($this->input->post('f_h_name_hindi'));
			$data['f_h_name'] = html_escape($this->input->post('f_h_name'));
			$studentData['f_h_occupation'] = html_escape($this->input->post('f_h_occupation'));
			$data['mother_name_hindi'] = html_escape($this->input->post('mother_name_hindi'));
			$data['mother_name'] = html_escape($this->input->post('mother_name'));
			$studentData['mother_occupation'] = html_escape($this->input->post('mother_occupation'));
			$studentData['p_mobile_no'] = html_escape($this->input->post('p_mobile_no'));
			$studentData['f_h_mobile_no'] = html_escape($this->input->post('f_h_mobile_no'));
			$studentData['p_email'] = html_escape($this->input->post('p_email'));
			$data['dob'] = html_escape(date("Y-m-d", strtotime($this->input->post('dob'))));
			$data['adhar_no'] = html_escape($this->input->post('adhar_no'));
			$data['sm_id'] = html_escape($this->input->post('sm_id'));

			$where = 'student_id='.$this->input->post('student_id');

			$this->Common_model->updateRecordByConditions('student',$where,$data);

			$count = $this->Common_model->getCountByWhere('student_data',$where);
			if($count>0){
				$this->Common_model->updateRecordByConditions('student_data',$where,$studentData);	
			}else{
				$studentData['student_id'] = $student_id;
				$this->Common_model->insertAll('student_data',$studentData);
			}

		}else if($step==3){
			$studentData['p_address'] = html_escape($this->input->post('p_address'));
			$studentData['p_city'] = html_escape($this->input->post('p_city'));
			$studentData['p_state'] = html_escape($this->input->post('p_state'));
			$studentData['p_district'] = html_escape($this->input->post('p_district'));
			$studentData['p_pin_code'] = html_escape($this->input->post('p_pin_code'));
			$where = 'student_id='.$this->input->post('student_id');

			$this->Common_model->updateRecordByConditions('student_data',$where,$studentData);
		}else 
		// if($step==6){
		// 	$studentData['ten_marks'] = html_escape($this->input->post('ten_marks'));
		// 	$studentData['ten_total_marks'] = html_escape($this->input->post('ten_total_marks'));
		// 	$studentData['ten_year'] = html_escape($this->input->post('ten_year'));
		// 	$studentData['ten_subjects'] = html_escape($this->input->post('ten_subjects'));
		// 	$studentData['ten_board'] = html_escape($this->input->post('ten_board'));

		// 	$studentData['twowelth_marks'] = html_escape($this->input->post('twowelth_marks'));
		// 	$studentData['twowelth_total_marks'] = html_escape($this->input->post('twowelth_total_marks'));
		// 	$studentData['twowelth_year'] = html_escape($this->input->post('twowelth_year'));
		// 	$studentData['twowelth_subject'] = html_escape($this->input->post('twowelth_subject'));
		// 	$studentData['twowelth_board'] = html_escape($this->input->post('twowelth_board'));

		// 	$studentData['graduation_marks'] = html_escape($this->input->post('graduation_marks'));
		// 	$studentData['graduation_university'] = html_escape($this->input->post('graduation_university'));
		// 	$studentData['graduation_year'] = html_escape($this->input->post('graduation_year'));
		// 	$studentData['graduation_subject'] = html_escape($this->input->post('graduation_subject'));
		// 	$studentData['graduation_total_marks'] = html_escape($this->input->post('graduation_total_marks'));

		// 	$studentData['pg_marks'] = html_escape($this->input->post('pg_marks'));
		// 	$studentData['pg_university'] = html_escape($this->input->post('pg_university'));
		// 	$studentData['pg_year'] = html_escape($this->input->post('pg_year'));
		// 	$studentData['pg_subject'] = html_escape($this->input->post('pg_subject'));
		// 	$studentData['pg_total_marks'] = html_escape($this->input->post('pg_total_marks'));

		// 	$student_id = $this->input->post("student_id");
		// 	$where = 'student_id='.$student_id;
		// 	$this->Common_model->updateRecordByConditions('student_data',$where,$studentData);
		// 	echo true;
		// }else
		if ($step==4){
			$student_id = $this->input->post("student_id");
			$studentData = $this->Common_model->getRecordById('student','student_id',$student_id);
			$updatetData = array('form_status' => 'Y','approved' => "Y");
			$where = 'student_id='.$student_id;
			$this->session->set_flashdata('success','Your Admission Form Submited');
			$center_id = $this->session->center_id;
			$wherecenter = 'id = "'.$center_id.'"';
			$OnlinePayTxnData = array('student_id' => $student_id,'center_id' => $center_id,'fees_head' => 'Enrollment Fees','amount' => 200,'course_group_id' => $studentData->course_group_id,'class_id' => $studentData->class_id,'student_name' => $studentData->name,'payment_status'=>'pending' );

			$OnlinePayTxn = $this->Common_model->insertAll('online_payment_transaction',$OnlinePayTxnData);

			$this->Common_model->updateRecordByConditions('student',$where,$updatetData);
		}

	}

	private function set_upload_options($path,$name)
	{   
//upload an image options
		$config = array();
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|pdf';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;
		$config['file_name'] =  $name;

		return $config;
	}


	public function do_upload($file,$path,$name)
	{
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['file_name'] =  $name;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload($file))
		{
			return $error = array('error' => $this->upload->display_errors());
		}
		else
		{
			return   $this->upload->data();
		}
	}
}
