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
		}
		else
		{ 

		
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
	

public function change_password_sub($id)
	{

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


$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$whereStudent = array('id' => $this->session->teacher_id);
		$data['teacher'] = $this->Common_model->getRecordByWhere('teacher',$whereStudent);
	

	     $this->load->view('teacher/header',array('title' => ' Account Details Update'));
				
	$this->load->view('teacher/account_transtaction_details',$data);
			$this->load->view('teacher/footer');
		

	}





public function account_transection_details_sub()
	{


$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);

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

		if($this->upload->do_upload('file'))
		{
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

		$title = array('title' => 'Teacher_answersheet_checked_count');
		$this->load->view('teacher/header',$title);	
		
		$where = array('teacher_id'=>$this->session->teacher_id
	);
		$data['assigns'] = $this->Common_model->getRecordByWhere('assign_answersheet',$where);

		
		$this->load->view('teacher/teacher_answer_sheet_checked_count',$data); 
		$this->load->view('teacher/footer');
		}



		
		}









	

