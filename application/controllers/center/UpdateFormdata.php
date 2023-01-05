<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class updateFormdata extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Center/center_model');
		if(!$this->session->has_userdata('adminData')){
			exit();
		}
	}

	public function index(){
	   
	   $mode = $this->Common_model->getRecordByWhere('student',array("student_id"=>$_POST['student_id'] ));	
	   // code for delete papers 
		if($_POST['old_course_group_id']!=$_POST['course_group_id']){
			$delete  =  $this->Common_model->deleteByWhere('new_exam_form' ,array('student_id'=>$_POST['student_id']));
			$class_master =   $this->Common_model->getRecordByWhere('class_master' ,array("id"=>$_POST['class_id']));
			if($class_master[0]->class_group=='N'){
				if($mode[0]->university_mode=='PVT') 
					$paperWhere=array('class_id'=>$_POST['class_id'],'type'=>'theory');
				else			
					$paperWhere=array('class_id'=>$_POST['class_id']);
				$papers = $this->Common_model->getRecordByWhere('paper_master',$paperWhere);
				
					if(count($papers)>0){
						foreach($papers as $paper){
							$insert_paper = array(
								'student_id'=>$_POST['student_id'],
								'course_group_id' =>$_POST['course_group_id'],
								'class_id' =>$_POST['class_id'],
								'paper_id' =>$paper->id,
								'paper_code' =>$paper->paper_code,
								'paper_type'=>$paper->type,
								'book_code'=>$paper->book_code,
								'paper_order'=>$paper->paper_no,
								'sub_group_id'=>$paper->sub_group_id
							);
							$insert = $this->Common_model->insertAll('new_exam_form',$insert_paper);
						}
						$data['temp_exam_form'] = 'Y';
					}else{
						$data['temp_exam_form'] = 'N';
					}
			}else{
				$data['temp_exam_form'] = 'N';
			}
		}
	
		$course_group_id = html_escape($this->input->post('course_group_id'));
		$class_id = html_escape($this->input->post('class_id'));
		$session = html_escape($this->input->post('session'));

		$data['session'] = $session;
		$data['course_group_id'] = $course_group_id;
		$data['course_name'] = $this->Common_model->getCourseNameByCourseId($course_group_id);
		$data['class_name'] = $this->Common_model->getClassNameByClassId($class_id);
		$data['class_id'] = $class_id;
		$data['medium'] = html_escape($this->input->post('medium'));
		$data['category'] = html_escape($this->input->post('category'));
		$data['gender'] = html_escape($this->input->post('gender'));
		$data['name'] = html_escape(strtoupper($this->input->post('name')));
		$data['f_h_name'] = html_escape(strtoupper($this->input->post('f_h_name')));
		$data['mother_name'] = html_escape(strtoupper($this->input->post('mother_name')));
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

		$studentData['board'] = html_escape($this->input->post('board'));
		$studentData['nationality'] = html_escape($this->input->post('nationality'));
		$studentData['minority'] = html_escape($this->input->post('minority'));
		
		// transaction start from here 
		// https://codeigniter.com/userguide3/database/transactions.html
		
		$this->db->trans_start();
        $student_id = html_escape($this->input->post('student_id'));
		$course_permission= $this->Common_model->getRecordByWhere('course',array("session"=>$session,'course_group_id'=>$course_group_id ));
		$session_permission= $this->Common_model->getRecordByWhere('session',array("session"=>$session));	
		
		if ($session!=$mode[0]->session) {
			if(($mode[0]->university_mode=='REG' && $course_permission[0]->admission_permission_regular=='Y') ||  ($mode[0]->university_mode=='PVT' &&  $course_permission[0]->admission_permission_private=='Y'))
			{
				$path = 'assets/student_image/'.$session.'/'.$mode[0]->photo;
				$prev_path = 'assets/student_image/'.$mode[0]->session.'/'.$mode[0]->photo;
				$upload = rename($prev_path,$path);
			}
			else {
				return false;
			}
		}
		if (isset($_FILES['photo'])) {
			$path = 'assets/student_image/'.$session;
			$upload = $this->do_upload('photo',$path,$student_id);
			if (isset($upload['file_name'])) {
				$PhotoData = array('photo' => $upload['file_name']);
				$where = array('student_id'=>$student_id);
				$this->Common_model->updateRecordByConditions('student',$where,$PhotoData);
			}
		}
		$studentData['student_id'] = $student_id;
		$this->db->where('student_id', $student_id);
		$this->db->update('student', $data);
		
        $this->db->where('student_id', $student_id);
		$this->db->update('student_data', $studentData);
		
		$OnlinePayTxnData = array('course_group_id' => $course_group_id,'class_id' => $class_id);

        $this->db->where('student_id', $student_id);
		$this->db->update('online_payment_transaction', $OnlinePayTxnData);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}else{ 
			$this->db->trans_complete();
		}

		$student_id = $this->Common_model->encrypt_decrypt($student_id);
		$userType = $this->session->userdata['account_type'];
		$result = array('student_id'=>$student_id ,'userType'=>$userType);
		echo json_encode($result);
	}

	private function set_upload_options($path,$name)
	{   
			//upload an image options
		$config = array();
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|pdf';
		$config['max_size']      = '0';
		$config['overwrite']     = TRUE;
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
		if (! $this->upload->do_upload($file)){
			 return $this->upload->display_errors();
		}else{
			return   $this->upload->data();
		}
	}


}