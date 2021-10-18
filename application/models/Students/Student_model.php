<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Student_model extends CI_Model {
		
        public $table = 'student';
        public $id = 'id';
        public $order = 'DESC';
		
		public function checkStudent($username,$password)
        {
			
			$password = date("Y-m-d",strtotime($password));
			
			$chk = explode("-",$username);
			$chk_username = $chk[1];
			$student_info = $this->Common_model->student_info_by_where(array("enrollment_no" => $username));
			if(($chk_username < 21) && ($student_info['form_status']!='Y')){
				
				if(isset($student_info)){
					if($student_info['dob'] != ""){		
						$query = $this->db->get("student where enrollment_no = '".$username."' and dob = '".$password."' ");
					}
					else{
						$query = $this->db->get("student where enrollment_no = '".$username."' ");
					}
					}else{
					return false;
				}			
				}else{
				
				$query = $this->db->get("student where enrollment_no = '".$username."' and dob = '".$password."'");
				
			}
			
			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
				}else{
				return false;
			}
		}
		
		public function checkStudentForm(){
			if(!$this->session->has_userdata('studentdata')){
				redirect(base_url('students/login'));
			}
			$enrollment_no = $this->session->studentdata;
			$enrollSession = explode("-",$enrollment_no);
			$session = $enrollSession[1];
			$student = $this->Common_model->getRecordById('student','enrollment_no',$enrollment_no);
			if($session < 21){
			$where = 'student_id='.$student->student_id;
				$userCount = $this->Common_model->getCountByWhere('user_enquiry',$where);
				if($userCount<1){
				$this->session->set_flashdata('warning','Please Fill Form First');
					redirect(base_url('student/enquiry'));
					}else if($student->payment_status!='Y'){
					$this->session->set_flashdata('warning','Please Make Payment First');
					redirect(base_url('student/payment/admission/'.$student->student_id));
					}elseif($student->form_status!='Y'){
					redirect(base_url('student/admission/'.$student->student_id));
					}else{
					return true;
				}
			}else{
				return true;
			}
		}
	}
	
?>