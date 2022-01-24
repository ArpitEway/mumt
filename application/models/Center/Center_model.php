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

	public function payment_complaint($student_id)
	{
		$details = html_escape($this->input->post('detail'));

		$student_detail = $this->Common_model->getSingleRow("student","*",array("student_id" => $student_id ));
		
		$data['details']   		= $details;
		$data['center_id'] 		= $student_detail->center_id;
		$data['enrollment_no'] 	= "-";
		$data['student_id'] 	= $student_id;
		$data['type']   		= 'admission';
		$data['date']   		=  date("Y-m-d");
		$data['status']   		= "Pending";

		$check = $this->Common_model->getSingleRow("payment_complaint","*",array("student_id" => $student_id, 'status !=' => 'Done' ));
		if($check){
			$response = array(
				'status' => true,
				'err_msg' => "A Complaint Already Under Process",
			);
		}else{			
			$this->db->insert('payment_complaint',$data);
			$response = array(
				'status' => true,
				'msg' => "Complained Succesfuly Registered",
			);
		}
		return json_encode($response);
	}
}

?>