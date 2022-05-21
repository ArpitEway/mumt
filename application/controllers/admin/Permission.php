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
		$order = 'id asc';
        $data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$data['classes'] = $this->Common_model->getRecordByOrder($table ,'class_name',$order);
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

	public function course_wise_permission()
	{
		$this->load->view('header',array("title"=>"Course Wise Permission"));	
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->db->order_by("course_name", "asc");
		$data['course']= $this->Common_model->getRecordByWhere("course_group");
		$this->load->view('admin/permission/course_wise_permission',$data);
		$this->load->view('footer');	
	}

	public function update_course_wise_permission(){
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
	}
}// class
