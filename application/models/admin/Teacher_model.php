<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Student_model extends CI_Model {
		
        public $table = 'teacher';
        public $id = 'id';
        public $order = 'DESC';
		
		public function checkStudent($username,$password)
        {
			
			$password = date("Y-m-d",strtotime($password));
			$student_info = $this->Common_model->teacher_info_by_where(array("phone" => $username));
			$query = $this->db->get("teacher where phone = '".$username."' and password = '".$password."'");

			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
				}else{
				return false;
			}
		}	
	}
?>