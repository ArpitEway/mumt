<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Exam_center_model extends CI_Model {
		
        public $table = ' exam_center';
        public $id = 'id';
        public $order = 'DESC';
		
		public function checkcenter($username,$password)
        {
			
			
			$query = $this->db->get("exam_center where examcentercode = '".$username."' and password = '".$password."'");

			if($query->num_rows()>0){
				$result = $query->result();
				return $result[0];
				}else{
				return false;
			}
		}	
	}
?>