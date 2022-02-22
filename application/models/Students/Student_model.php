<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Student_model extends CI_Model {
		
        public $table = 'student';
        public $id = 'id';
        public $order = 'DESC';
		
		public function checkStudent($username,$password)
        {
			
			$password = date("Y-m-d",strtotime($password));
			$student_info = $this->Common_model->student_info_by_where(array("enrollment_no" => $username));
			$query = $this->db->get("student where enrollment_no = '".$username."' and dob = '".$password."'");

			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
				}else{
				return false;
			}
		}	
	}
?>