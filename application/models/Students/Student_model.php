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
			 	 
				} else{
			
				$query = $this->db->get("student where enrollment_no = '".$username."' and dob = '".$password."'");
				
			  }
			
			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
				}else{
				return false;
			}
		}	
	}
?>