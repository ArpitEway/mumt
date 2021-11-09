<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class saveFormdata extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Center/center_model');
		if(!$this->session->has_userdata('centerdata')){
			exit();
		}
	}

	public function index(){
		$course_group_id = html_escape($this->input->post('course_group_id'));
		$class_id = html_escape($this->input->post('class_id'));
		$session = html_escape($this->input->post('session'));
		$data['session'] = $session;
		$data['course_group_id'] = $course_group_id;
		$data['course_name'] = $this->Common_model->getCourseNameByCourseId($course_group_id);
		$data['class_name'] = $this->Common_model->getClassNameByClassId($class_id);
		$data['center_id'] = $this->session->center_id;
		$data['center_code'] = $this->session->centerdata;
		$data['center_name'] = $this->Common_model->getSinglefield('center','center_name','id='.$this->session->center_id);
		$data['university_mode'] = 'REG';
		$data['class_id'] = $class_id;
		$data['medium'] = html_escape($this->input->post('medium'));
		$data['category'] = html_escape($this->input->post('category'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['name'] = html_escape($this->input->post('name'));
		$data['f_h_name'] = html_escape($this->input->post('f_h_name'));
		$data['mother_name'] = html_escape($this->input->post('mother_name'));
		$data['dob'] = html_escape(date("Y-m-d", strtotime($this->input->post('dob'))));
		$data['adhar_no'] = html_escape($this->input->post('adhar_no'));

		$studentData['eligibility'] = html_escape($this->input->post('eligibility'));
		$studentData['p_mobile_no'] = html_escape($this->input->post('p_mobile_no'));
		$studentData['religion'] = html_escape($this->input->post('religion'));
		$studentData['p_email'] = html_escape($this->input->post('p_email'));

		$studentData['handicapped'] = html_escape($this->input->post('handicapped'));
		$studentData['marital_status'] = html_escape($this->input->post('marital_status'));
		$studentData['p_address'] = html_escape($this->input->post('p_address'));
		$studentData['p_city'] = html_escape($this->input->post('p_city'));
		$studentData['p_state'] = html_escape($this->input->post('p_state'));
		$studentData['p_district'] = html_escape($this->input->post('p_district'));
		$studentData['p_pin_code'] = html_escape($this->input->post('p_pin_code'));
		$studentData['c_address'] = html_escape($this->input->post('c_address'));
		$studentData['c_city'] = html_escape($this->input->post('c_city'));
		$studentData['c_state'] = html_escape($this->input->post('c_state'));
		$studentData['c_district'] = html_escape($this->input->post('c_district'));
		$studentData['c_pin_code'] = html_escape($this->input->post('c_pin_code'));

		$studentData['marks'] = html_escape($this->input->post('marks'));
		$studentData['total_marks'] = html_escape($this->input->post('total_marks'));

		$studentData['passing_year'] = html_escape($this->input->post('passing_year'));

	    $studentData['percentage'] = $studentData['total_marks'] * 100/$studentData['marks'];

		$studentData['board'] = html_escape($this->input->post('board'));
		$studentData['nationality'] = html_escape($this->input->post('nationality'));
		$studentData['minority'] = html_escape($this->input->post('minority'));
		
		// transaction start from here 
		// https://codeigniter.com/userguide3/database/transactions.html
		$this->db->trans_start();

		$student_id = $this->Common_model->insertAll('student',$data);

		$path = './assets/student_image/'.$session;
		if(!file_exists($path)){
			mkdir($path);
		}

		$upload = $this->do_upload('photo',$path,$student_id);
		
		$PhotoData = array('photo' => $upload['file_name']);
		$where = array('student_id'=>$student_id);
		$this->Common_model->updateRecordByConditions('student',$where,$PhotoData);

		$studentData['student_id'] = $student_id;
		$this->Common_model->insertAll('student_data',$studentData);
		
		$OnlinePayTxnData = array('student_id' => $student_id,'center_id' => $this->session->center_id,'fees_head' => 'Admission Fees','amount' => 1500,'payment_status'=>'pending','course_group_id' => $course_group_id,'class_id' => $class_id,'student_name' => $data['name'],'admission_type'=>'regular');
		$OnlinePayTxn = $this->Common_model->insertAll('online_payment_transaction',$OnlinePayTxnData);

		// transaction Complete 
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{ 
			$this->db->trans_complete();
		}

		$student_id = $this->Common_model->encrypt_decrypt($student_id);
		$result = array('student_id'=>$student_id);
		echo json_encode($result);

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