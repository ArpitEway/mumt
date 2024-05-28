<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
	}

	public function class_wise_permission()
	{   
       
		$this->load->view('header',array("title"=>"Class Wise Permission"));	
		$table ="class_master";
		$where = "";
		$where = ' class_name="II Sem"  or class_name="IV Sem"';
		//admission_permission="Y" or or class_name="II Year" or class_name="III Sem"
		// or class_name="II Sem" or class_name="III Sem"
		 // $order = 'id ASC';
        $data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
         $this->db->order_by("id", "asc");
	    $data['classes'] = $this->Common_model->getRecordByWhere($table ,$where);
		// $data['classes'] = $this->Common_model->getRecordByOrder($table ,'class_name',$order);
		$this->load->view('admin/permission/class_wise_permission',$data);
		$this->load->view('footer');	
	}
    


    public function update_exam_form_permission(){
		$status =  $this->input->post('exam_form_permission');
      
		if(isset($_POST['class_id'])){
			$class_id =  $this->input->post('class_id');
          
			$where = array('id'=>$class_id);
		}
        
        if($status!=''){
		$st = ($status == 'Y') ? 'N' : 'Y';
		$data=array(
			'exam_form_permission'=>$st,);
	        }
        
		$res=$this->Common_model->updateRecordByConditions('class_master',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}
   
	public function update_backlog_exam_form_permission(){
		$status =  $this->input->post('backlog_exam_form_permission');
      
		if(isset($_POST['class_id'])){
			$class_id =  $this->input->post('class_id');
          
			$where = array('id'=>$class_id);
		}
        
        if($status!=''){
		$st = ($status == 'Y') ? 'N' : 'Y';
		$data=array(
			'backlog_exam_form_permission'=>$st,);
	        }
        
		$res=$this->Common_model->updateRecordByConditions('class_master',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}
   
	public function update_admit_card_permission(){
		$status =  $this->input->post('admit_card_permission');
     
		if(isset($_POST['class_id'])){
			$class_id =  $this->input->post('class_id');
			$where = array('id'=>$class_id);
		}

        if($status!=''){
        	$st = ($status == 'Y') ? 'N' : 'Y';
        	$data=array('admit_card_permission'=>$st,);
        }
        
		$res=$this->Common_model->updateRecordByConditions('class_master',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	public function course_wise_permission($session=0)
	{
		$this->load->view('header',array("title"=>"Course Wise Permission"));	
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$data['sessions'] = $this->db->get_where('session', array())->result_array();
		if($session==1)
			{
				$LastSessionElement = end($data['sessions']);
				 $session=$LastSessionElement['id'];
			
			}
		$record=$this->db->get_where('session', array("id"=>$session))->result_array();
		$this->db->order_by("course_name", "asc");
		$data['course']= $this->Common_model->getRecordByWhere("course",array('session'=>$record[0]['session']));	
		$data['sessionsSelect'] =$session;
		
		$this->load->view('admin/permission/course_wise_permission',$data);
		$this->load->view('footer');	
	}
	

	public function update_course_wise_permission(){
		$status =  $this->input->post('admission_permission');
		$statusPvt =  $this->input->post('admission_permission_pvt');
		if(isset($_POST['course_id']))
		{
			$course__id =  $this->input->post('course_id');
			$where = array('id'=>$course__id);
		}

		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('admission_permission_regular'=>$st,);
		}else{  
			$st1 = ($statusPvt == 'Y') ? 'N' : 'Y';
			$data=array('admission_permission_private'=>$st1,);
		}
		$res=$this->Common_model->updateRecordByConditions('course',$where,$data);
		
		
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
		if($statusPvt == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($statusPvt == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	/*public function update_course_wise_permission(){
		$status =  $this->input->post('admission_permission');
		$statusPvt =  $this->input->post('admission_permission_pvt');
		if(isset($_POST['course_group_id']))
		{
			$course_group_id =  $this->input->post('course_group_id');
			$where = array('id'=>$course_group_id);
		}

		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('admission_permission'=>$st,);
		}else{  
			$st1 = ($statusPvt == 'Y') ? 'N' : 'Y';
			$data=array('admission_permission_pvt'=>$st1,);
		}
		$res=$this->Common_model->updateRecordByConditions('course_group',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
		if($statusPvt == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($statusPvt == 'N'){
			echo json_encode(array('error'=>false));
		}
	}*/

	public function update_result_permission(){
		$status =  $this->input->post('result_permission');
     
		if(isset($_POST['class_id'])){
			$class_id =  $this->input->post('class_id');
			$where = array('id'=>$class_id);
		}

        if($status!=''){
        	$st = ($status == 'Y') ? 'N' : 'Y';
        	$data=array('result_permission'=>$st);
        }
        
		$res=$this->Common_model->updateRecordByConditions('class_master',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	public function update_backlog_result_permission(){
		$status =  $this->input->post('backlog_result_permission');
     
		if(isset($_POST['class_id'])){
			$class_id =  $this->input->post('class_id');
			$where = array('id'=>$class_id);
		}

        if($status!=''){
        	$st = ($status == 'Y') ? 'N' : 'Y';
        	$data=array('backlog_result_permission'=>$st);
        }
        
		$res=$this->Common_model->updateRecordByConditions('class_master',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	public function center_wise_permission()

	{   
		$this->load->view('header',array("title"=>"Center Wise Permission"));	
        $data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$data['centers'] = $this->Common_model->getRecordByWhere("center" , array());
		$this->load->view('admin/permission/center_wise_permission',$data);
		$this->load->view('footer');	
	}


	public function update_admission_permission_regular()

	{
		$status =  $this->input->post('admission_permission');
		if(isset($_POST['center_id']))
		{
			$center_id =  $this->input->post('center_id');
			$where = array('id'=>$center_id);
		} 
		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('admission_permission'=>$st,);
		}
		$res=$this->Common_model->updateRecordByConditions('center',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	public function update_admission_permission_private()

	{
		$status =  $this->input->post('admission_permission_private');
		if(isset($_POST['center_id']))
		{
			$center_id =  $this->input->post('center_id');
			$where = array('id'=>$center_id);
		}
		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('admission_permission_private'=>$st,);
		}
		$res=$this->Common_model->updateRecordByConditions('center',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	public function update_center_exam_form_permission()

	{
		$status =  $this->input->post('exam_form_permission');
		if(isset($_POST['center_id']))
		{
			$center_id =  $this->input->post('center_id');
			$where = array('id'=>$center_id);
		} 
		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('exam_form_permission'=>$st,);
		}
		$res=$this->Common_model->updateRecordByConditions('center',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	public function update_center_admit_card_permission()

	{

		$status =  $this->input->post('admit_card_permission');	
		if(isset($_POST['center_id']))
		{
			$center_id =  $this->input->post('center_id');
			$where = array('id'=>$center_id);
		} 
		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('admit_card_permission'=>$st,);
		}
		$res=$this->Common_model->updateRecordByConditions('center',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

	public function update_center_result_permission()
	{

		$status =  $this->input->post('result_permission');
		if(isset($_POST['center_id']))
		{
			$center_id =  $this->input->post('center_id');
			$where = array('id'=>$center_id);
		} 
		if($status!=''){
			$st = ($status == 'Y') ? 'N' : 'Y';
			$data=array('result_permission'=>$st,);
		}
		$res=$this->Common_model->updateRecordByConditions('center',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}

    public function update_center_old_session_permission()
	{	
            $id    = $this->input->post("id");
			$status = $this->input->post("status");	
			$data = $this->Common_model->updateRecordByConditions("center",array("id" => $id ),array("old_session_permission" => $status ));
				 $status = true;
				echo json_encode(array(
					 "status" => $status,
					"data" => $data
				));	 
	}
	public function update_exam_form_permission_notSubmitted()
	{	
            $id    = $this->input->post("id");
			$exam_form_permission = $this->input->post("exam_form_permission");	
			$data = $this->Common_model->updateRecordByConditions("center",array("id" => $id ),array("exam_form_permission" => $exam_form_permission ));
				 $status = true;
				echo json_encode(array(
					 "status" => $status,
					"data" => $data
				));	 
	}
	public function update_temp_exam_form_permission()
	{	
            $id    = $this->input->post("id");
			$temp_exam_form_permission = $this->input->post("temp_exam_form_permission");	
			$data = $this->Common_model->updateRecordByConditions("center",array("id" => $id ),array("temp_exam_form" => $temp_exam_form_permission ));
				 $status = true;
				echo json_encode(array(
					 "status" => $status,
					"data" => $data
				));	 
	}
	public function update_temp_admission_payment()
	{	
            $id    = $this->input->post("id");
			$temp_admission_payment = $this->input->post("temp_admission_payment");	
			$data = $this->Common_model->updateRecordByConditions("center",array("id" => $id ),array("temp_admission_payment" => $temp_admission_payment ));
				 $status = true;
				echo json_encode(array(
					 "status" => $status,
					"data" => $data
				));	 
	}
	public function update_final_result_permission(){
		$status =  $this->input->post('final_result_permission');
     
		if(isset($_POST['class_id'])){
			$class_id =  $this->input->post('class_id');
			$where = array('id'=>$class_id);
		}

        if($status!=''){
        	$st = ($status == 'Y') ? 'N' : 'Y';
        	$data=array('final_result_permission'=>$st);
        }
        
		$res=$this->Common_model->updateRecordByConditions('class_master',$where,$data);
		if($status == 'Y'){
			echo json_encode(array('success'=>true));
		}else if($status == 'N'){
			echo json_encode(array('error'=>false));
		}
	}
}// class
