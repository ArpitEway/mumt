<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
	}

	public function index(){
		if($this->session->has_userdata('username')){
			$this->load->view('header');
			$this->load->view('admin/dashboard');
			$this->load->view('footer');
		}else{
			redirect(base_url('admin/login'));
		}
	}

	public function dashboard(){

		if(!$this->session->has_userdata('username')){
			redirect(base_url('admin'));
			exit;
		}else{

		$data = array();
			$this->load->view('header');
			$this->load->view('admin/dashboard');
			$this->load->view('footer');
		}
	}
	
	public function login(){
		if($this->session->has_userdata('username')){	
			 redirect(base_url('admin/'.$this->session->account_type));
			 exit;
		 }
		$this->load->view('admin/login');
	}

	public function loginSub(){
		if($this->session->has_userdata('username')){
			
			 redirect(base_url('admin/'.$this->session->account_type));
			 exit;
		 }

		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/login');
		}
		else
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$check_user = $this->admin_model->checkUser($username,$password);
			if($check_user){	
				$data = array('loged_in' => true,
							'username' => $check_user->name,
							'account_type' => $check_user->account_type,
							'admin_id' => $check_user->id
						);
				$this->session->set_userdata($data);
				redirect(base_url('admin/'.$check_user->account_type));
			}else{
				$data = array('error'=> "USERNAME AND PASSWORD ARE  INCORRECT");
				$this->load->view('admin/login',$data);
			}
		}
	}
	
	public function department($param1 = '', $param2 = '', $param3 = '')
	{
	    
    	if(!$this->session->has_userdata('username')){
			redirect(base_url('admin'));
			exit;
		}else{
		    
		    if($param1 == 'create'){
		        
    			$response = $this->admin_model->create_department();
    			$this->session->set_flashdata('ajax_flash_message','Department Successfully Added');
		        redirect(base_url().'admin/Admins/department');
		        
    		}
    		if($param1 == 'update'){
    		  
    		    $response = $this->admin_model->department_update($param2);
    			$this->session->set_flashdata('ajax_flash_message','Department Successfully Updated');
		        redirect(base_url().'admin/Admins/department');
    		}
    		
    		if($param1 == 'delete'){
    		    
    		    $response = $this->admin_model->department_delete($param2);
    			$this->session->set_flashdata('ajax_flash_message','Department Successfully Deleted');
		        redirect(base_url().'admin/Admins/department');
    		}
		    
			if(empty($param1) ){
        		$data = array();
    			$this->load->view('header');
    			$this->load->view('admin/department');
    			$this->load->view('footer');
    		}    

		    
		}
        		
	}
	public function course($param1 = '', $param2 = '', $param3 = '')
	{
	    
    	if(!$this->session->has_userdata('username')){
			redirect(base_url('admin'));
			exit;
		}else
		{
		    
		    if($param1 == 'create'){
		        
    			$response = $this->admin_model->create_course();
    			$this->session->set_flashdata('ajax_flash_message','Course Successfully Added');
		        redirect(base_url().'admin/Admins/course');
		        
    		}
    		if($param1 == 'update'){
    		  
    		    $response = $this->admin_model->course_update($param2);
    			$this->session->set_flashdata('ajax_flash_message','Course Successfully Updated');
		        redirect(base_url().'admin/Admins/course');
    		}
    		
    		if($param1 == 'delete'){
    		    
    		    $response = $this->admin_model->course_delete($param2);
    			$this->session->set_flashdata('ajax_flash_message','Course Successfully Deleted');
		        redirect(base_url().'admin/Admins/course');
    		}
		    
			if(empty($param1) ){
        		$data = array();
    			$this->load->view('header');
    			$this->load->view('admin/course');
    			$this->load->view('footer');
    		}    

		    
		}
        		
	}
public function classes($param1 = '', $param2 = '', $param3 = '')
{
	    
    if(!$this->session->has_userdata('username')){
			redirect(base_url('admin'));
			exit;
	}else
	{
		     
		    if($param1 == 'create'){
		        
    			$response = $this->admin_model->create_class();
    			$this->session->set_flashdata('ajax_flash_message','Class Successfully Added');
		        redirect(base_url().'admin/Admins/classes');
		        
    		}
    		if($param1 == 'update'){
    		  
    		    $response = $this->admin_model->class_update($param2);
    			$this->session->set_flashdata('ajax_flash_message','Class Successfully Updated');
		        redirect(base_url().'admin/Admins/classes');
    		}
    		
    		if($param1 == 'delete'){
    		    
    		    $response = $this->admin_model->class_delete($param2);
    			$this->session->set_flashdata('ajax_flash_message','Class Successfully Deleted');
		        redirect(base_url().'admin/Admins/classes');
    		}
		    
			if(empty($param1) ){
        		$data = array();
    			$this->load->view('header');
    			$this->load->view('admin/classes');
    			$this->load->view('footer');
    		}    

		    
		}
        		
	}
	
public function paper($param1 = '', $param2 = '', $param3 = '')
	{
	    
    	if(!$this->session->has_userdata('username')){
			redirect(base_url('admin'));
			exit;
		}else
		{
		     
		    if($param1 == 'create'){
		        
    		$response = $this->admin_model->create_paper();
    		$this->session->set_flashdata('ajax_flash_message','Paper Successfully Added');
		    redirect(base_url().'admin/Admins/Paper');
		        
    		}
    		if($param1 == 'update'){
    		  
    		$response = $this->admin_model->paper_update($param2);
    	    $this->session->set_flashdata('ajax_flash_message','Paper Successfully Updated');
		    redirect(base_url().'admin/Admins/Paper');
			
    		}
    		
    		if($param1 == 'delete'){
    		    
    		    $response = $this->admin_model->paper_delete($param2);
    			$this->session->set_flashdata('ajax_flash_message','Paper Successfully Deleted');
		        redirect(base_url().'admin/Admins/Paper');
    		}
		    
			if(empty($param1) ){
				
        		$data = array();
    			$this->load->view('header');
    			$this->load->view('admin/paper');
    			$this->load->view('footer');
    		}    

		    
		}
        		
}
public function paper_test($param1 = '', $param2 = '', $param3 = '')
	{
	    
    	if(!$this->session->has_userdata('username')){
			redirect(base_url('admin'));
			exit;
		}else
		{
		     
		    if($param1 == 'create'){
		        
    		$response = $this->admin_model->create_paper();
    		$this->session->set_flashdata('ajax_flash_message','Paper Successfully Added');
		    redirect(base_url().'admin/Admins/Paper');
		        
    		}
    		if($param1 == 'update'){
    		  
    		$response = $this->admin_model->paper_update($param2);
    	    $this->session->set_flashdata('ajax_flash_message','Paper Successfully Updated');
		    redirect(base_url().'admin/Admins/Paper');
			
    		}
    		
    		if($param1 == 'delete'){
    		    
    		    $response = $this->admin_model->paper_delete($param2);
    			$this->session->set_flashdata('ajax_flash_message','Paper Successfully Deleted');
		        redirect(base_url().'admin/Admins/Paper');
    		}
		    
			if(empty($param1) ){
				
        		$data = array();
    			$this->load->view('header');
    			$this->load->view('admin/paper_test');
    			$this->load->view('footer');
    		}    

		    
		}
        		
}
public function get_class_list_by_course()
{
    if ($this->input->method() == "post") {
            $id    = 0;
            $count = 0;
            $id    = $this->input->post("id");
            if ($this->input->post("id")) {
                $data = $this->Common_model->getAllRow("class_master", "id, class_name", array(
                    "course_group_id" => $id
                ),'id ASC');
                $count++;
            }
            if ($count > 0) {
                $status = true;
                $msg    = "";
            }
    }
    echo json_encode(array(
        "status" => $status,
        "msg" => $msg,
        "data" => $data
    ));
}

public function get_group_by_class()
{
    if ($this->input->method() == "post") {
            $id    = 0;
            $count = 0;
            $id    = $this->input->post("id");
            if ($this->input->post("id")) {
				
                $data = $this->Common_model->getAllRow("group", "id, group_name", array(
                    "class_id" => $id
                ),'id ASC');
                $count++;
            }
            if ($count > 0) {
                $status = true;
                $msg    = "";
            }else{
				$status = "";
			}
    }
    echo json_encode(array(
        "status" => $status,
        "msg" => $msg,
        "data" => $data
    ));
}

public function get_papers_by_class()
{
	
if ($this->input->method() == "post") {
            $class_id    = 0;
            //$count = 0;
            $class_id    = $this->input->post("class_id");
			
            if ($this->input->post("class_id")) {
				
				
			$data ='<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
					<th>Sno</th>
					<th>Paper</th>
					<th>Type</th>
					<th>CE</th> 					
					<th>Options</th>
				</tr>
			</thead>';
    		
    		
    		$i = 1;
			
            $papers = $this->db->get_where("paper_master", array("class_id" => $class_id))->result_array();
            
			foreach($papers as $paper){
				
			$courses = $this->db->get_where("course_group", array("id" => $paper["course_group_id"]))->row_array();
		 	$classes = $this->db->get_where("class_master", array("id" => $paper["class_id"]))->row_array();
			$course_name = $courses["course_name"];
			$class_name  = $classes["class_name"];
				
    		
			$data .= '<tbody>
					<tr>
						<td>'.$i.'</td>
						<td>'.$paper["paper_name"].'</td>
						<td>'.$paper["type"].'</td>
						<td>'.$paper["ce"].'</td>
						
                	    <td>
                			<div style="display: inline-flex;">
								<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal("http://103.195.186.84/~mpsvv/admin/modal/popup/admin/paper/edit/"'.$paper["id"].', "Update class")"> <i class="mdi mdi-pencil edit-icon"></i></a>   
								<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal("http://103.195.186.84/~mpsvv/admin/Admins/paper/delete/"'.$paper["id"].', showAllpaper )"><i class="mdi mdi-delete delete-icon"></i></a>
							</div>
                        </td>
					</tr>
				
			</tbody>';
			
			
			 $i++; } 
	$data .= '</table>';
			
				//$count++;
            }
			
			$status = true;
            $msg    = "";
            
    }
    echo json_encode(array(
        "status" => $status,
        "msg" => $msg,
        "data" => $data
    ));

}


	
public function logout()
{
	$this->session->sess_destroy();
	redirect(base_url());
}

}
