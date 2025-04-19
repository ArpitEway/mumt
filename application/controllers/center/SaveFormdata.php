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
		if ($this->session->center_id!=13) {
			$data['center_id'] = $this->session->center_id;
			$data['center_code'] = $this->session->centerdata;
			$data['center_name'] = $this->Common_model->getSinglefield('center','center_name','id='.$this->session->center_id);
		}else{
			$this->db->like('allot_course_group_id',$course_group_id);
			$this->db->where_in('id',array(21, 22, 23, 24, 25, 26, 27, 28));
			$this->db->from('center');
			$centerData = $this->db->get()->row();
			$data['center_id'] = $centerData->id;
			$data['center_code'] = $centerData->center_code;
			$data['center_name'] = $centerData->center_name;
		}
		if($this->input->post('mode')=="regular"){
           $mode = "REG";
		}else{
			$mode="PVT";
		};
		$data['university_mode'] =$mode ;
		$data['class_id'] = $class_id;
		//Center Admission in University
		if($this->session->center_id==100){
			$data['for_center'] =html_escape($this->input->post('forCenter'));
		}
		$data['medium'] = html_escape($this->input->post('medium'));
		$data['category'] = html_escape($this->input->post('category'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['name'] = html_escape(strtoupper($this->input->post('name')));
	
		$data['f_h_name'] = html_escape(strtoupper($this->input->post('f_h_name')));
		$data['mother_name'] = html_escape(strtoupper($this->input->post('mother_name')));
		$data['dob'] = html_escape(date("Y-m-d", strtotime($this->input->post('dob'))));
		$data['adhar_no'] = html_escape($this->input->post('adhar_no'));
		$data['regular_exam_form_permission'] = 'Y';

		$studentData['eligibility'] = html_escape($this->input->post('eligibility'));
		$studentData['p_mobile_no'] = html_escape($this->input->post('p_mobile_no'));
		$studentData['religion'] = html_escape($this->input->post('religion'));
		$studentData['p_email'] = html_escape($this->input->post('p_email'));

		$studentData['handicapped'] = html_escape($this->input->post('handicapped'));
		$studentData['marital_status'] = html_escape($this->input->post('marital_status'));
		$studentData['p_address'] = html_escape($this->input->post('p_address'));
		$studentData['p_city'] = html_escape($this->input->post('p_city'));
		$p_state_id = html_escape($this->input->post('p_state'));
		$studentData['p_state'] = $p_state_id;
		$p_district_id = html_escape($this->input->post('p_district'));
		$studentData['p_district'] = $p_district_id;
		$studentData['p_pin_code'] = html_escape($this->input->post('p_pin_code'));
		$studentData['c_address'] = html_escape($this->input->post('c_address'));
		$studentData['c_city'] = html_escape($this->input->post('c_city'));
		$c_state_id = html_escape($this->input->post('c_state'));
		$c_district_id = html_escape($this->input->post('c_district'));
		$studentData['c_state'] = $c_state_id;
		$studentData['c_district'] = $c_district_id;
		$studentData['c_pin_code'] = html_escape($this->input->post('c_pin_code'));

		$studentData['marks'] = html_escape($this->input->post('marks'));
		$studentData['total_marks'] = html_escape($this->input->post('total_marks'));

		$studentData['passing_year'] = html_escape($this->input->post('passing_year'));

		$studentData['board'] = html_escape($this->input->post('board'));
		$studentData['nationality'] = html_escape($this->input->post('nationality'));
		$studentData['minority'] = html_escape($this->input->post('minority'));
		
		$class_ids=array(101,104,107,110,116,119,125,128,131,134);
		$class = $this->Common_model->getRecordByWhere('class_master',array('id' =>$class_id));
		if(($class[0]->cbcs == 'Y' || in_array($class_id, $class_ids)))
		{
			$data['exam_pattern'] ="GRADE";
		}
		// transaction start from here 
	
		
		// Department center by default set status & approved

		// $center_ids_uni = array( 10,11,12,13);
		// if(in_array($this->session->center_id, $center_ids_uni)){
		// 	$data['payment_status']='Y';
		// 	$data['document_uploaded']='Y';
		// 	$data['approved']='Y';
			
		// }
		// $center_ids_dep = array( 21,22,23,24,25,26,27,28,29);
		
		// if(in_array($this->session->center_id, $center_ids_dep)){
		// 	$data['payment_status']='Y';
		// }

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
		$amount = $this->Common_model->getRecordByWhere('course',array('course_group_id'=> $course_group_id));
	
	    $mode = $this->input->post('mode');
		$late_fees=0;
		$remark="";
		if($mode=='regular'){
			$amount = $amount[0]->form_fees+$amount[0]->admission_fees;
			$admission_type = 'regular';
		}else{
			$center_ids_dep = array(10,11,12,13,20,21,22,23,24,25,26,27,28,29,1975,2098,2115);
			$late = $this->Common_model->getRecordByWhere('master',array('p_late_fee_status'=> 'Y'));
			if($late  && !in_array($this->session->center_id, $center_ids_dep)){
				$late_fees=$late[0]->p_late_fees;
				$remark="With Late Fees";
			}
			
			$amount = $amount[0]->p_form_fees+ $amount[0]->p_admission_fees+$late_fees;
			$admission_type = 'private';
		}
	    
		$OnlinePayTxnData = array('student_id' => $student_id,'center_id' => $data['center_id'] ,'fees_head' => 'Admission Fees','amount' => $amount,'payment_status'=>'pending','course_group_id' => $course_group_id,'class_id' => $class_id,'student_name' => $data['name'],'admission_type'=>$admission_type,'remark'=>$remark);

		// Department center by default set status & approved

	    // || in_array($this->session->center_id, $center_ids_dep)
		// if(in_array($this->session->center_id, $center_ids_uni))
		// {
		// 	$OnlinePayTxnData['payment_status']	= 'Paid By University';
		// 	$OnlinePayTxnData['payment'] =	'Y';
		// 	$OnlinePayTxnData['payment_date'] =	date('Y-m-d');
		// 	$OnlinePayTxnData['payment_time'] =	date('h:i:s');
			
		// }
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
		
			//	paper add code 
		
       
		// if($class[0]->exam_form_permission =='Y' && $class[0]->class_group=="N"){
			$cbcs = ($class[0]->cbcs == 'Y')?'Y':'N';
			if($class[0]->class_group=="N"){
			$this->db->order_by('id');
			if($data['university_mode']=='PVT') 
					$paperWhere=array('class_id'=>$class_id,'type'=>'theory','cbcs_paper'=>$cbcs);
			else			
					$paperWhere=array('class_id'=>$class_id,'cbcs_paper'=>$cbcs);
			$papers = $this->Common_model->getRecordByWhere('paper_master',$paperWhere);
	
	
		foreach($papers as $paper){
		
			$data = array(
				'student_id'=>$student_id ,
				'course_group_id'=>$paper->course_group_id,
				'class_id'=>$paper->class_id,
				'paper_id'=>$paper->id,
				'paper_code'=>$paper->paper_code,
				'paper_type'=>$paper->type,
				'book_code'=>$paper->book_code,
				'paper_order'=>$paper->paper_no,
				'sub_group_id'=>$paper->sub_group_id
			);
	       $this->Common_model->insertAll('new_exam_form',$data);

		 $this->Common_model->updateRecordByConditions('student',array('student_id'=>$student_id),array('temp_exam_form' => 'Y'));
		}
	
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
		}else{
			return   $this->upload->data();
		}
	}
}
