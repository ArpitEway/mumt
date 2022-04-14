<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Teacher_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');
	}

	public function index(){
		if($this->session->has_userdata('teacherdata')){
			redirect(base_url('Teacher/dashboard'));
		}else{			
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('teacher/login',$csrf);
		}
	}

	public function dashboard(){

		if(!$this->session->has_userdata('teacherdata')){
			redirect(base_url('Teacher/login'));
		}else{
			$titleData = array('title' => 'Teacher Dashboard'); 
			$this->load->view('teacher/header',$titleData);
			$id =  $this->session->teacher_id;
			$center = $this->Common_model->getRecordById('teacher','id',$id);
			$data = array('teacher' => $center);
			$this->load->view('teacher/dashboard',$data);
			$this->load->view('teacher/footer');
		}
	}

	public function login(){
		if($this->session->has_userdata('teacherdata')){
			redirect(base_url('Teacher/dashboard'));
			exit;
		}
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('teacher/login',$csrf);
	}

	public function loginSub(){

		if($this->session->has_userdata('teacherdata')){
			redirect(base_url('dashboard'));
			exit;
		}

		$this->form_validation->set_rules('phone', 'Phone', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('teacher/login',$csrf);
		}else{

			$username = $_POST['phone'];
			$password = $_POST['password'];
			$check_user = $this->Teacher_model->checkTeacher($username,$password);

			if($check_user){

				$data = array(
					'loged_in' 	  => true,
					'teacherdata' => $check_user->phone,
					'password' 	  	  => $check_user->password,
					'teacher_id'  => $check_user->id

				);

				$this->session->set_userdata($data);
				redirect(base_url('Teacher/dashboard'));
			}else{
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);	
				$this->session->set_flashdata('error','Phone no or Password are incorrect');
				$this->load->view('teacher/login',	$csrf );
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('Teacher/login'));
	}

	public function change_password(){
		if(!$this->session->has_userdata('teacherdata')){
			redirect(base_url('login'));
		}else{
			$titleData = array('title' => 'Change Password'); 
			$this->load->view('teacher/header',$titleData);
			$id = $this->session->teacher_id;
			$teacher = $this->Common_model->getRecordById('teacher','id',$id);
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'teacher' => $teacher
			);
			$this->load->view('teacher/change_password',$data);
			$this->load->view('teacher/footer');
		}
	}


	public function change_password_sub($id){
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$resetdata =  $this->Common_model->getRecordById('teacher','id',$id);
		$old_password = $resetdata->password;
		if($this->input->post('password'))
		{
			if($old_password == $this->input->post('password'))
			{
				$new_password 	  = $this->input->post('new_password');
				$confirm_password =$this->input->post('passconf');
				if($this->input->post('new_password'))
				{
					if($new_password == $confirm_password)
					{
						$data = array("password" => $new_password );
						$this->db->where('id', $id);
						$this->db->update('teacher', $data);
						echo json_encode(array(
							"success" => 'Password Updated Successfully',
						));
					}
					else{
						echo json_encode(array(
							"error" => 'Password does not match',
						));
					}
				}else{
					echo json_encode(array(
						"error" => 'Please enter New Password',
					));
				}
			}else{
				echo json_encode(array(
					"error" => 'Current Password is wrong',
				));
			}
		}else{
			echo json_encode(array(
				"error" => 'Please enter current password',
			));
		}
	}

	public function account_transaction_details(){
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$whereStudent = array('id' => $this->session->teacher_id);
		$data['teacher'] = $this->Common_model->getRecordByWhere('teacher',$whereStudent);

		$this->load->view('teacher/header',array('title' => ' Bank Account Details'));
		$this->load->view('teacher/account_transtaction_details',$data);
		$this->load->view('teacher/footer');
	}

	public function account_transection_details_sub()
	{
		$bankname = $this->input->post('bankname');
		$accountno = $this->input->post('accountno');
		$accountholder = $this->input->post('accountholder');
		$ifsccode = $this->input->post('ifsccode');
		$teacher_id = $this->input->post('id');

		$config['upload_path'] = 'assets/documents';
		$config['allowed_types'] = 'jpg|jpeg|png|gif';  
		$config['encrypt_name']=TRUE;
		$this->load->library('upload');
		$this->upload->initialize($config);

		if($this->upload->do_upload('file')){
			$uploadData = $this->upload->data();
		}else{
			$returndata = array('error'=> $this->upload->display_errors());
			echo json_encode($returndata);	
			exit();
		}

		$teacherData = array(
			'bank_name' => $bankname,
			'account_no' => $accountno,
			'account_name' => $accountholder,
			'ifsc_code' =>$ifsccode,	
			'image' =>$uploadData['file_name']
		);
		$data = $this->Common_model->updateRecordByConditions('teacher',array('id'=>$teacher_id),$teacherData);

		if($data){
			$returndata = array('success'=> 'Form Has Been Submited');
			echo json_encode($returndata);
		}else{
			$returndata = array('error'=> 'An Error Occured');
			echo json_encode($returndata);		
		}
	}

	public function Teacher_answersheet_checked_count(){
		$title = array('title' => 'Teacher Answersheet Checked Count');
		$this->load->view('teacher/header',$title);
		$where = array('teacher_id'=>$this->session->teacher_id);
		$data['assigns'] = $this->Common_model->getRecordByWhere('assign_answersheet',$where);
		$this->load->view('teacher/teacher_answer_sheet_checked_count',$data); 
		$this->load->view('teacher/footer');
	}



	public function Teacher_paper_alloted_list(){
		$title = array('title' => 'Check Answer Sheet');
		$this->load->view('teacher/header',$title);	
		$where = array('teacher_id'=>$this->session->teacher_id);
		$assignAnsData = $this->Common_model->getRecordByWhere('assign_answersheet',$where);
		$data['assignAnsData'] = $assignAnsData;
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('teacher/teacher_paper_list_student_wise',$data); 
		$this->load->view('teacher/footer');
	}

	public function get_paper_details(){
     
		$paper_code =$this->input->post('paper_code');
		$where = array('teacher_id'=>$this->session->teacher_id,'paper_code'=> $paper_code);
		$assignAnsData = $this->Common_model->getRecordByWhere('assign_answersheet',$where);
	
		$where = 'paper_code = "'.$paper_code.'" and upload_exam_ans_sheet.center_id in ('.$assignAnsData[0]->center_id.') and answer_sheet!="" and file_exist="Y" and new_exam_form="Y" and teacher_id=""';

		$this->db->select('roll_no,enrollment_no,course_name,class_name,paper_code,upload_exam_ans_sheet.student_id,upload_exam_ans_sheet.id');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = upload_exam_ans_sheet.student_id');
		$answersheetData = $this->db->get()->result();
		
		$data = array(
			'answersheetData' => $answersheetData,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);

		if($data){
			$dt =  $this->load->view('teacher/view_student_details',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $dt
		));
	}



	public function student_details_uplode(){
		$upload_exam_ans_id = $this->input->post('upload_exam_ans_id');
		$where=array('upload_exam_ans_sheet.id'=>$upload_exam_ans_id);
		$this->db->select('*');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = upload_exam_ans_sheet.student_id');

		$details = $this->db->get()->result();
		$data = array(
			'details' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('teacher/view_model_data',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));	
	}


	public function question_paper_sub()
	{  
	  $teacher_id = $this->session->teacher_id;
		$id = $this->input->post('id');
		$marks1 = $this->input->post('marks1');
		$marks2 = $this->input->post('marks2');
		$marks3 = $this->input->post('marks3');
		$marks4 = $this->input->post('marks4');
		$marks5 = $this->input->post('marks5');
		$remark = $this->input->post('remark');
		$total_marks=$marks1+$marks2+$marks3+$marks4+$marks5;

		$where = array('id' => $id);
		$updateData = array('que_1' => $marks1,'que_2' => $marks2,'que_3' => $marks3,'que_4' => $marks4,'que_5' => $marks5, 'remark'=>$remark ,'total_marks'=> $total_marks,'teacher_id'=>$teacher_id);
		$result=	$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$updateData);
		if($result){
			echo json_encode(array(
				"success" => ' Updated Successfully',
			));
		}else{
			echo json_encode(array(
				"error" => ' error Occured',
			));
		}
	}


    public function check_answersheet_pdf($id){
		
		$id=$this->Common_model->encrypt_decrypt($id,'decrypt');
		
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$where= array('id'=>$id);
		$data['answer'] = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',$where);
		$update_open_answersheet = $this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,array('open_answersheet'=>'Y'));
		
		$this->load->view('teacher/view_answersheet_pdf',$data); 
	}

	 public function view_answersheet_pdf($id){
		
		$id=$this->Common_model->encrypt_decrypt($id,'decrypt');
		
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$where= array('id'=>$id);
		$data['answer'] = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',$where);
		$update_open_answersheet = $this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,array('open_answersheet'=>'Y'));
		
		$this->load->view('teacher/answersheet_pdf',$data); 
	}


	public function view_question_pdf($paper_code){
		$paper_code=$this->Common_model->encrypt_decrypt($paper_code,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);

		$where= array('paper_code'=>$paper_code);
		$data['question'] = $this->Common_model->getRecordByWhere('paper_master',$where);
		//$this->Common_model->last_query();
		$this->load->view('teacher/view_question_pdf',$data); 
	}

	public function student_marks_entry_update()
	{

		$upload_exam_ans_sheet_id = $this->input->post('upload_exam_ans_sheet_id');
		$json_data = $this->input->post('json_data');

		$where=array('uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
		$dataCount =	$this->Common_model->getCountByWhere('answer_sheet_json_data',$where);
		if($dataCount==""){
			$updateData = array('uplode_examsheet_id' => $upload_exam_ans_sheet_id,'json_data' => $json_data);

			$result=	$this->Common_model->insertAll('answer_sheet_json_data',$updateData);

			if($result){
				echo json_encode(array(
					"success" => ' Updated Successfully',
				));
			}else{
				echo json_encode(array(
					"error" => ' error Occured',
				));
			}
		}
		
		else{
			$where=array('uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
			$updateData1 = array('json_data' => $json_data);
			
			$result1=	$this->Common_model->updateRecordByConditions('answer_sheet_json_data',$where,$updateData1);


			if($result1){
				echo json_encode(array(
					"success" => ' Updated Successfully',
				));
			}else{
				echo json_encode(array(
					"error" => ' error Occured',
				));
			}
		}
//$this->Common_model->last_query();
	}




public function Plugin_initialized_entry_update()
{

	$upload_exam_ans_sheet_id = $this->input->post('upload_exam_ans_sheet_id');
	$initialize_json_data = $this->input->post('initialize_json_data');

	$where=array('uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
 $jsondataCount =	$this->Common_model->getCountByWhere('answer_sheet_json_data',$where);
	
	if(empty($jsondataCount)){
	$updateData = array('initialize_json' => $initialize_json_data,
	'uplode_examsheet_id'=>$upload_exam_ans_sheet_id);
	$result=	$this->Common_model->insertAll('answer_sheet_json_data',$updateData);
}
	echo json_encode(array(
		"success" => 'update Successfully ',
	));
	

}


}