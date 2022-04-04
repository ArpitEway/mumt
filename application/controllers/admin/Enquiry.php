<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Enquiry extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='Enquiry'){
				redirect(base_url('admin/logout')); 
		}
	}

	public function index(){
		$admin_id = $this->session->admin_id;
		$where = 'admin_id='.$admin_id.' and status="Y"';
		$menu = array(
			"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
			"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
		);
		$this->load->view('header',array('title' => 'Enquiry Section'));
		$this->load->view('admin/enquiry/dashboard',$menu);
		$this->load->view('footer');	
	}

    public function view_enquiry(){
        $data['inquiries'] = $this->Common_model->get_record('enquiry','*');
        $this->load->view('header',array('title' => 'Enquiry Section'));
		$this->load->view('admin/enquiry/enquiry_list',$data);
		$this->load->view('footer');
    }

    public function department($param1 = '', $param2 = '', $param3 = '')
    {
    	if(!$this->session->has_userdata('adminData')){
    		redirect(base_url());
    		exit;
    	}else{
    		if($param1 == 'create'){
    			if($_FILES['photos']['name']==""){
    				$department_image= "";
    			}else{
    				$ext1=strtolower(pathinfo($_FILES['photos']['name'],PATHINFO_EXTENSION));
    				$department_image=date('Y-m-d-H-i-s')."_".$_FILES['photos']['name'];
    				$upload_file = move_uploaded_file($_FILES['photos']['tmp_name'],"assets/department/".$department_image);
    			}

    			$data = array('department_name' =>$_POST['department_name'],
    				'image' =>$department_image,);
    			$insert = $this->Common_model->insertAll('department',$data);
    			if($insert){
    				redirect(base_url().'admin/enquiry/department');
    			}
    		}

    		if($param1 == 'update'){
    			if($_FILES['photos']['name']!=""){
    				$ext1=strtolower(pathinfo($_FILES['photos']['name'],PATHINFO_EXTENSION));
    				$department_image=date('Y-m-d-H-i-s')."_".$_FILES['photos']['name'];
    				$upload_file = move_uploaded_file($_FILES['photos']['tmp_name'],"assets/department/".$department_image);	
    			}else{
    				$department_image=$_POST['old_image'];
    			}

    			$data = array('department_name' =>$_POST['department_name'],
    				'image' =>$department_image,
    			);

    			$update = $this->Common_model->updateRecordByConditions('department',array("id"=>$param2),$data);
    			if($update){
    				redirect(base_url().'admin/enquiry/department');
    			}
    		}

    		if($param1 == 'delete'){
    			$response = $this->Common_model->deleteByWhere('department',array("id"=>$param2));
    			$this->session->set_flashdata('ajax_flash_message','Department Successfully Deleted');
    			redirect(base_url().'admin/enquiry/department');
    		}

    		if(empty($param1)){
    			$data = array();
    			$title['title'] = "Department";
    			$data = array(
    				'name_csrf' => $this->security->get_csrf_token_name(),
    				'hash_csrf' => $this->security->get_csrf_hash()
    			);
    			$data['departments'] = $this->Common_model->get_record("department","*");
    			$this->load->view('header',$title);
    			$this->load->view('admin/enquiry/department',$data);
    			$this->load->view('footer');
    		}
    	}
    }

	public function program($param1 = '', $param2 = '', $param3 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			if($param1 == 'create'){
				$data = array('department_id' =>$_POST['department_id'],
					'program_name' =>$_POST['program_name'],
					'status'=>'Y' );
				$insert = $this->Common_model->insertAll('program',$data);
				if($insert){
					redirect(base_url().'admin/enquiry/program');
				}
			}
			if($param1 == 'update'){
				$data = array('department_id' =>$_POST['department_id'],
					'program_name' =>$_POST['program_name'],);
				$update = $this->Common_model->updateRecordByConditions('program',array("id"=>$param2),$data);
				if($update){
					redirect(base_url().'admin/enquiry/program');
				}
			}
			if($param1 == 'delete'){
				$response = $this->Common_model->deleteByWhere('program',array("id"=>$param2));
				$this->session->set_flashdata('ajax_flash_message','Department Successfully Deleted');
				redirect(base_url().'admin/enquiry/program');
			}
			if(empty($param1)){
				$data = array();
				$title['title'] = "Program";
				$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$data['programs'] = $this->Common_model->get_record("program","*");
				$this->load->view('header',$title);
				$this->load->view('admin/enquiry/program',$data);
				$this->load->view('footer');
			}    
		}
	}
}// class
