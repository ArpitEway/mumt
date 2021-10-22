<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class center_model extends CI_Model {

	public $table = 'center';
	public $id = 'id';
	public $order = 'DESC';

	public function checkUser($centercode,$password)
	{
		$query = $this->db->get($this->table." where center_code='".$centercode."' and password='".$password."'");
		if($query->num_rows()>0){
			$result = $query->result();
			return $result[0];
		}else{
			return false;
		}
	}

	public function checkLink($centercode)
	{
		$query = $this->db->get($this->table." where center_code='".$centercode."'");
		if($query->num_rows()>0){
			$result = $query->result();
			return $result[0];
		}else{
			return false;
		}
	}

	public function checkcenterStudent($student_id)
	{
		$where = array(
		'center_code' =>	$this->session->centerdata,
		'center_id' => $this->session->center_id
	);
		$count = $this->Common_model->getCountByWhere('student',$where);
		return ($count>0) ? true : false;
	}

	public function getRemainingBalance($center_id)
	{
		return $this->Common_model->getSinglefield($this->table,'balance','id='.$center_id);
	}
}

?>