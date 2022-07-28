<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
	}

	public function index(){
		if($this->session->has_userdata('adminData')){
			$admin_id = $this->session->admin_id;
			$where = 'admin_id='.$admin_id.' and status="Y"';
			$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
			);
			$this->load->view('header',array('title' => 'Admin Section'));
			$this->load->view('admin/dashboard',$menu);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('login'));
		}
	}

	public function dashboard(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$admin_id = $this->session->admin_id;
			$where = 'admin_id='.$admin_id.' and status="Y"';
			$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
			);
			$data = array();
			$this->load->view('header');
			$this->load->view('admin/dashboard',$menu);
			$this->load->view('footer');
		}
	}
	
	public function add_menu_heading($param1 = '', $param2 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$dt = array();
			$dt['title'] = "Add Menu Heading";
			$data['admins'] = $this->db->get_where('admin_master', array())->result_array();
			if($param1 == 'create'){
				$response = $this->admin_model->create_menu_heading();
				echo json_encode(array("status" => 'true','data' => $response));
			}
			if($param1 == 'update'){
				$response = $this->admin_model->update_menu_heading($param2);
				echo json_encode(array("status" => 'true'));
			}
			if($param1 == 'delete'){
				$response = $this->admin_model->menu_heading_delete($param2);
				echo json_encode(array("status" => 'true'));
			}
			if(empty($param1) ){
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$data['title']='Add Admin Menu Heading';
				$this->load->view('header');
				$this->load->view('admin/menu/add_menu_heading',$data);
				$this->load->view('footer');
			}
		}
	}

	public function add_student_menu_heading($param1 = '', $param2 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$data = array();
			$dt   = array();
			$dt['title'] = "Add Student Menu Heading";
			if($param1 == 'create'){
				$response = $this->admin_model->create_student_menu_heading();
				echo json_encode(array("status" => 'true'));
			}
			if($param1 == 'update'){
				$response = $this->admin_model->update_student_menu_heading($param2);
				echo json_encode(array("status" => 'true'));
			}
			if($param1 == 'delete'){
				$response = $this->admin_model->student_menu_heading_delete($param2);
				echo json_encode(array("status" => 'true'));
			}
			if(empty($param1))
			{
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$data['headings'] = $this->Common_model->student_menu_heading_data();
				$this->load->view('header');
				$this->load->view('admin/student_menu/add_menu_heading',$data);
				$this->load->view('footer');
			}
		}
	}

	public function add_menu($param1 = '', $param2 = '')
	{

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$data = array();
			$dt = array();
			$dt['title'] = "Add Menu";
			$data['admins'] = $this->db->get_where('admin_master', array())->result_array();
			$data['headings'] = $this->db->get_where('menu_heading', array())->result_array();

			if($param1 == 'create'){
				$response = $this->admin_model->create_menu();
				echo json_encode(array("status" => 'true'));
			}

			if($param1 == 'update'){
				$response = $this->admin_model->update_menu($param2);
				echo json_encode(array("status" => 'true'));
			}

			if($param1 == 'delete'){
				$response = $this->admin_model->menu_delete($param2);
				echo json_encode(array("status" => 'true'));
			}

			if(empty($param1))
			{
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('header');
				$this->load->view('admin/menu/add_menu',$data);
				$this->load->view('footer');
			}	
		}
	}

	public function student_add_menu($param1 = '', $param2 = '')
	{

		if(!$this->session->has_userdata('adminData')){

			redirect(base_url());
			exit;

		}else
		{

			$data = array();
			$dt = array();
			$dt['title'] = "Add Menu";


			$data['headings'] = $this->db->get_where('student_menu_heading', array())->result_array();

			if($param1 == 'create'){

				$response = $this->admin_model->create_student_menu();
				echo json_encode(array("status" => 'true'));
			}

			if($param1 == 'update'){

				$response = $this->admin_model->update_student_menu($param2);
				echo json_encode(array("status" => 'true'));
			}

			if($param1 == 'delete'){

				$response = $this->admin_model->student_menu_delete($param2);
				echo json_encode(array("status" => 'true')); 
			}

			if(empty($param1))
			{
				$data = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$data['menus'] = $this->Common_model->student_menu_data();
				$this->load->view('header');
				$this->load->view('admin/student_menu/add_menu',$data);
				$this->load->view('footer');
			}

		}
	}

	public function login(){
		if($this->session->has_userdata('adminData')){	
			redirect(base_url($this->session->account_type));
			exit;
		}
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->load->view('admin/login',$csrf);
	}

	public function loginSub(){
		if($this->session->has_userdata('adminData')){
			redirect(base_url($this->session->account_type));
			exit;
		}

		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('username', 'username', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('admin/login',$csrf);
		}
		else
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$check_user = $this->admin_model->checkUser($username,$password);
			if($check_user){	
				$data = array('loged_in' => true,
					'adminData' => $check_user->name,
					'account_type' => $check_user->account_type,
					'admin_id' => $check_user->id
				);

				$this->session->set_userdata($data);
				redirect(base_url($check_user->account_type));
			}else{
				$data = array('error'=> "USERNAME AND PASSWORD ARE  INCORRECT",
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('admin/login',$data);
			}
		}
	}

	public function session($param1 = '', $param2 = '', $param3 = '')
	{

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{

			if($param1 == 'create'){
				$this->db->select('session');	
				$this->db->order_by("id", "desc");
				$this->db->limit(1);
				$this->db->from('session'); 
				$query = $this->db->get();
				$value = $query->result(); 
				$old_session = $value[0]->session ;
				if($old_session==$_POST['session']){
					$this->session->set_flashdata('ajax_flash_message','Session Already Created');
					redirect(base_url().'session');
				}else{
					$this->db->select('*');	
					$this->db->from('course'); 
					$this->db->where('session',$old_session);
					$query = $this->db->get();
					$courses = $query->result(); 	
					$response = $this->admin_model->create_session();
					if ($response){
						foreach($courses as $course){
							$Data['course_group_id'] = $course->course_group_id;
							$Data['course_name'] = $course->course_name;
							$Data['course_code'] = $course->course_code;
							$Data['min_duration'] = $course->min_duration;	  
							$Data['max_duration'] = $course->max_duration;
							$Data['form_fees'] = $course->form_fees;
							$Data['admission_fees'] = $course->admission_fees;
							$Data['program_fees'] = $course->program_fees;
							$Data['exam_fees'] = $course->exam_fees;
							$Data['practical_exam_fees'] = $course->practical_exam_fees;
							$Data['p_form_fees'] = $course->p_form_fees;
							$Data['p_admission_fees'] = $course->p_admission_fees;
							$Data['p_program_fees'] = $course->p_program_fees;
							$Data['p_exam_fees'] = $course->p_exam_fees;
							$Data['session'] = $_POST['session'];
							$this->db->insert('course',$Data);
						}
					}
					$this->session->set_flashdata('ajax_flash_message','Session Successfully Added');
					redirect(base_url().'session');
				}
			}
			// if($param1 == 'update'){

			// 	$response = $this->admin_model->session_update($param2);
			// 	$this->session->set_flashdata('ajax_flash_message','Session Successfully Updated');
			// 	redirect(base_url().'session');
			// }

			// if($param1 == 'delete'){

			// 	$response = $this->admin_model->session_delete($param2);
			// 	$this->session->set_flashdata('ajax_flash_message','Session Successfully Deleted');
			// 	redirect(base_url().'session');
			// }

			if(empty($param1) ){
				$data = array();
				$data['title'] = "Session";
				$csrf = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
				$this->load->view('header',$data);
				$this->load->view('admin/session',$csrf);
				$this->load->view('footer');
			}    
		}
	}


	public function course($param1 = '', $param2 = '', $param3 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			if($param1 == 'create'){

				$response = $this->admin_model->create_course();
				$this->session->set_flashdata('ajax_flash_message','Course Successfully Added');
				redirect(base_url().'course');

			}
			if($param1 == 'update'){

				$response = $this->admin_model->course_update($param2);
				$this->session->set_flashdata('ajax_flash_message','Course Successfully Updated');
				redirect(base_url().'course');
			}

			if($param1 == 'delete'){

				$response = $this->admin_model->course_delete($param2);
				$this->session->set_flashdata('ajax_flash_message','Course Successfully Deleted');
				redirect(base_url().'course');
			}

			if(empty($param1) ){
				$data = array();
				$data['title'] = "Course";
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('header',$data);
				$this->load->view('admin/course',$csrf);
				$this->load->view('footer');
			}    


		}

	}

	public function classes($param1 = '', $param2 = '', $param3 = '')
	{

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{

			if($param1 == 'create'){
				
				$response = $this->admin_model->create_class();
				$this->session->set_flashdata('ajax_flash_message','Class Successfully Added');
				redirect(base_url().'classes');

			}
			if($param1 == 'update'){
				$response = $this->admin_model->class_update($param2);
				$this->session->set_flashdata('ajax_flash_message','Class Successfully Updated');
				redirect(base_url().'classes');
				
			}

			if($param1 == 'delete'){

				$response = $this->admin_model->class_delete($param2);
				$this->session->set_flashdata('ajax_flash_message','Class Successfully Deleted');
				redirect(base_url().'classes');
				
			}

			if(empty($param1) ){

				$data = array();
				$data['title'] = "Classes";
				$this->load->view('header',$data);
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('admin/classes',$csrf);
				$this->load->view('footer');
			}    


		}

	}
	public function get_papers_by_class_course()
	{

		if ($this->input->method() == "post") 
		{
			$class_id    = 0;
            		//$count = 0;
			$class_id    = $this->input->post("class_id");
			$course_group_id    = $this->input->post("course_group_id");
			
			
			$where = array();
			if($course_group_id!='All'){
				$where = array('course_group_id' => $course_group_id);
			}
			if($class_id!='All'){
				$where = array('class_id' => $class_id);
			}

			$papers = $this->db->get_where("paper_master",$where)->result_array();

			$htmlData = array(
				'papers' => $papers,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);

			$data = $this->load->view('admin/paper/paper',$htmlData,true);
			$status = true;
			$msg    = "";
		}
		echo json_encode(array(
			"status" => $status,
			"msg" => $msg,
			"data" => $data
		));
	}
	
	public function paper($param1 = '', $param2 = '', $param3 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			if($param1 == 'create'){
				$response = $this->admin_model->create_paper();
				$this->session->set_flashdata('ajax_flash_message','Paper Successfully Added');
				redirect(base_url().'paper');
			}

			if($param1 == 'update'){
				$response = $this->admin_model->update_paper($param2);
				$this->session->set_flashdata('ajax_flash_message','Paper Successfully Updated');
				redirect(base_url().'paper');
			}

			if($param1 == 'delete'){
				$response = $this->admin_model->paper_delete($param2);
				$this->session->set_flashdata('ajax_flash_message','Paper Successfully Deleted');
				redirect(base_url().'paper');
			}

			if(empty($param1) ){
				$data = array();
				$data['title'] = "Paper";
				$this->load->view('header',$data);
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('admin/paper',$csrf);
				$this->load->view('footer');
			}
		}
	}

	public function paper_test($param1 = '', $param2 = '', $param3 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			if($param1 == 'create'){
				$response = $this->admin_model->create_paper();
				$this->session->set_flashdata('ajax_flash_message','Paper Successfully Added');
				redirect(base_url().'Paper');
			}

			if($param1 == 'update'){
				$response = $this->admin_model->paper_update($param2);
				$this->session->set_flashdata('ajax_flash_message','Paper Successfully Updated');
				redirect(base_url().'Paper');
			}

			if($param1 == 'delete'){
				$response = $this->admin_model->paper_delete($param2);
				$this->session->set_flashdata('ajax_flash_message','Paper Successfully Deleted');
				redirect(base_url().'Paper');
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

	public function get_heading_list_by_admin()
	{
		if ($this->input->method() == "post") 
		{
			$id    = 0;
			$count = 0;
			$id = $this->input->post("id");
			
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->getAllRow("menu_heading", "id, heading", array("admin_id" => $id
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
				<th>Course</th>
				<th>Class</th>
				<th>Paper</th>
				<th>Type</th>
				<th>CE</th>
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
					<td>'.$course_name.'</td>
					<td>'.$class_name.'</td>
					<td>'.$paper["paper_name"].'</td>
					<td>'.$paper["type"].'</td>
					<td>'.$paper["ce"].'</td>
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

		public function account_register($param1 = '', $param2 = '', $param3 = '')
		{

			if(!$this->session->has_userdata('adminData')){
				redirect(base_url());
				exit;
			}else
			{

				if($param1 == 'create'){

					$response = $this->admin_model->create_account();
					$this->session->set_flashdata('ajax_flash_message','Account Successfully Added');
					redirect(base_url().'account_register');

				}
				if($param1 == 'update'){

					$response = $this->admin_model->account_update($param2);
					$this->session->set_flashdata('ajax_flash_message','Account Successfully Updated');
					redirect(base_url().'account_register');
				}

				if($param1 == 'delete'){

					$response = $this->admin_model->account_delete($param2);
					$this->session->set_flashdata('ajax_flash_message','Account Successfully Deleted');
					redirect(base_url().'account_register');
				}

				if(empty($param1) ){
					$data = array();
					$this->load->view('header');
					$csrf = array(
						'name_csrf' => $this->security->get_csrf_token_name(),
						'hash_csrf' => $this->security->get_csrf_hash()
					);
					$this->load->view('admin/register_account',$csrf);
					$this->load->view('footer');
				}    


			}

		}

		public function update_user_status()
		{

			if ($this->input->method() == "post") 
			{
				$id    = 0;

				$id    = $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("admin_master",array("id" => $id ),array("status" => $status ));

					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $data
					));
				}
			}
		}
		public function update_heading_status()
		{

			if ($this->input->method() == "post") 
			{
				$id    = 0;
				$id    = $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("menu_heading",array("id" => $id ),array("status" => $status ));

					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $data
					));
				}
			}
		}


		public function update_student_heading_status()
		{
			if ($this->input->method() == "post") 
			{
				$id    = 0;
				$id    = $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("student_menu_heading",array("id" => $id ),array("status" => $status ));

					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $data
					));
				}
			}
		}


		public function update_menu_status()
		{
			if ($this->input->method() == "post") 
			{
				$id    = 0;
				$id    = $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("menu",array("id" => $id ),array("status" => $status ));

					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $data
					));
				}
			}
		}
		public function update_student_menu_status()
		{
			if ($this->input->method() == "post") 
			{
				$id    = 0;
				$id    = $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("student_menu",array("id" => $id ),array("status" => $status ));

					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $data
					));
				}
			}
		}

		public function get_heading_data()
		{

			if ($this->input->method() == "post") 
			{
				$admin_id = 0;
				$data = array();
				$dt   = array();

				$admin_id  = $this->input->post("admin_id");

				if($admin_id != "all"){

					$dt['admin_id'] = $admin_id;

				}

				$data['headings'] = $this->Common_model->heading_data($dt);
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$dt =  $this->load->view('admin/menu/getHeadingList',$data,true);
				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}

		}

		public function get_student_heading_data()
		{

			if ($this->input->method() == "post") 
			{
				$admin_id = 0;
				$data = array();
				$dt   = array();

				$data['headings'] = $this->Common_model->student_menu_heading_data();

				$dt =  $this->load->view('admin/student_menu/getHeadingList',$data,true);

				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}
			
		}
		
		public function get_menu_data()
		{
			if ($this->input->method() == "post") 
			{
				$heading_id = 0;
				$data = array();
				$dt   = array();
				
				$heading_id  = $this->input->post("heading_id");
				
				if($heading_id != "all"){
					
					$dt['heading_id'] = $heading_id;
				}
				
				$data['menus'] = $this->Common_model->menu_data($dt);
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$dt =  $this->load->view('admin/menu/getMenuList',$data,true);
				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}
			
		}
		public function get_student_menu_data()
		{
			
			if ($this->input->method() == "post") 
			{
				$heading_id = 0;
				$heading_id  = $this->input->post("heading_id");
				$data = array();
				$dt   = array();
				if($heading_id != "all"){
					
					$dt['heading_id'] = $heading_id;
				}
				$data['menus'] = $this->Common_model->student_menu_data($dt);
				
				$dt =  $this->load->view('admin/student_menu/getMenuList',$data,true);
				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}
			
		}

		public function update_menu_heading_order()
		{

			$allDataa=$_POST['allData'];

			$i = 1;
			foreach ($allDataa as $key => $value) {
				$data = array(
					'heading_order' => $i
				);
				
				$where = 'id='.$value;				
				$this->Common_model->updateRecordByConditions('menu_heading',$where,$data); 
				$i++;


				$this->session->set_flashdata('success','Order Updated.');
				echo "Order Updated";	
			}

		}

		public function update_student_menu_heading_order()
		{
			$allDataa=$_POST['allData'];

			$i = 1;
			foreach ($allDataa as $key => $value) {
				$data = array(
					'heading_order' => $i
				);
				
				$where = 'id='.$value;				
				$this->Common_model->updateRecordByConditions('student_menu_heading',$where,$data); 
				$i++;


				$this->session->set_flashdata('success','Order Updated.');
				echo "Order Updated";	
			}
			
		}

		public function update_menu_order()
		{

			$allDataa=$_POST['allData'];

			$i = 1;
			foreach ($allDataa as $key => $value) {
				$data = array(
					'menu_order' => $i
				);
				
				$where = 'id='.$value;				
				$this->Common_model->updateRecordByConditions('menu',$where,$data); 
				$i++;


				$this->session->set_flashdata('success','Order Updated.');
				echo "Order Updated";	
			}
		}

		public function update_student_menu_order()
		{

			$allDataa=$_POST['allData'];

			$i = 1;
			foreach ($allDataa as $key => $value) {
				$data = array(
					'menu_order' => $i
				);
				
				$where = 'id='.$value;				
				$this->Common_model->updateRecordByConditions('student_menu',$where,$data); 
				$i++;


				$this->session->set_flashdata('success','Order Updated.');
				echo "Order Updated";	
			}
			
		}
		
		public function logout()
		{
			$this->session->sess_destroy();
			redirect(base_url());
		}
		
		public function centers($param1 = '', $param2 = '', $param3 = '')
		{
			if(!$this->session->has_userdata('adminData')){
				redirect(base_url());
				exit;
			}else{

				$data['center_code'] = html_escape($this->input->post('center_code'));

				$data['center_name'] = html_escape($this->input->post('center_name'));

				$data['address'] = html_escape($this->input->post('address'));

				$data['pin_code'] = html_escape($this->input->post('pin_code'));

				$data['state_id'] = html_escape($this->input->post('state'));

				$data['distt_id'] = html_escape($this->input->post('district'));

				$data['city'] = html_escape($this->input->post('city'));

				$data['contactpersonname'] = html_escape($this->input->post('contact_person'));

				$data['email'] = html_escape($this->input->post('email'));

				$data['password'] = html_escape($this->input->post('password'));

				$data['mobile_no_1'] = html_escape($this->input->post('mobile_no'));

				$data['mobile_no_2'] = html_escape($this->input->post('mobile_no_2'));

				$data['status'] = html_escape($this->input->post('status'));

				if($param1 == 'create'){
					$response = $this->admin_model->create_center($data);
					echo json_encode(array("status" => 'true'));
				}
				if($param1 == 'update'){
					$response = $this->admin_model->center_update($param2,$data);
					echo json_encode(array("status" => 'true'));
				}
				if($param1 == 'delete'){
					$response = $this->admin_model->center_delete($param2);
					$this->session->set_flashdata('ajax_flash_message','Center Deleted Successfully ');

					redirect(base_url().'centers');
				}

				if(empty($param1) ){

					$data = array();
					$data['title'] = "Center";
					$data['center_code'] = $this->admin_model->getcenterCode();
					$this->load->view('header',$data);
					$data['center'] = $this->Common_model->get_record('center','');

					$data['name_csrf'] = $this->security->get_csrf_token_name();
					$data['hash_csrf'] = $this->security->get_csrf_hash();
					$this->load->view('admin/center',$data);
					$this->load->view('footer');
				}
			}
		}

		public function get_dist_by_state()
		{
			if ($this->input->method() == "post") {
				$id    = 0;
				$count = 0;
				$state = $this->input->post("state");
				
				if ($this->input->post("state")) {
					$data = $this->db->get_where('distt', array("state_id" => $state))->result_array();
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
		
		public function allot_course($param1 = '',$param2 = '')
		{
			if(!$this->session->has_userdata('adminData')){
				redirect(base_url());
				exit;
				
			}else{
				if($param1 == 'allot'){
					$response = $this->admin_model->allot_course($param2);
					echo json_encode(array("status" => 'true'));
				}
				
				if(!empty($param1) && $param1 != 'allot' ){
					$data = array();
					$data['courses'] = $this->db->get_where("course_group", array())->result_array();
					$data['center_id'] = $param1;
					$data['name_csrf'] = $this->security->get_csrf_token_name();
					$data['hash_csrf'] = $this->security->get_csrf_hash();
					$this->load->view('header');
					$this->load->view('admin/allot_course_to_centers',$data);
					$this->load->view('footer');
				}

			}
		}
		
		public function get_paper_code()
		{
			if ($this->input->method() == "post") 
			{
				$id    = 0;
				$count = 0;
				$class_id = $this->input->post("id");
				$course_group_id = $this->input->post("course_group_id");
				if($class_id) 
				{
					$data = $this->Common_model->getPaperCode($course_group_id,$class_id);
				}
				echo json_encode(array(
					"status" => "true",
					"data" => $data
				));
			}
		}


		public function login_section()
		{
			if(!$this->session->has_userdata('adminData'))
			{
				redirect(base_url());

				exit;
			}else{

				$data = array();
				$data['users'] = $this->db->get_where("admin_master", array('status' => 'Y'))->result_array();
				$this->load->view('header');
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('admin/login_section',$data);
				$this->load->view('footer');
			}
		}

		public function check_login()
		{
			if ($this->input->method() == "post") 
			{
				$adminData = $this->input->post('username'); 

				$check_user = $this->admin_model->checkUserByUsername($adminData);
				if($check_user){	
					$data = array('loged_in' => true,
						'adminData' => $check_user->name,
						'account_type' => $check_user->account_type,
						'admin_id' => $check_user->id
					);
					$this->session->set_userdata($data);
				}else{
					$data = array('error'=> "username INCORRECT");
					$data['name_csrf'] = $this->security->get_csrf_token_name();
					$data['hash_csrf'] = $this->security->get_csrf_hash();
					$this->load->view('admin/login',$data);
				}

				echo json_encode(array(
					"status" => "true",
					"data" => $data
				));
			}
		}


		public function remote_login($u_Id)
		{
			$userId  = $this->Common_model->encrypt_decrypt($u_Id,'decrypt');
			$check_user = $this->admin_model->checkUserByUserID($userId);
			if($check_user){
				$data = array('loged_in' => true,
					'adminData' => $check_user->name,
					'account_type' => $check_user->account_type,
					'admin_id' => $check_user->id
				);
				$this->session->set_userdata($data);
				redirect(base_url('admin/'.$check_user->account_type));
			}else{
				$data = array('error'=> "username INCORRECT");
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('admin/login',$data);
			}
		}

		public function getClassByCourse(){
			$course = $this->input->post('course_group_id');
			$class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$course."'");
			$data = array(
				'class_list' => $class_list,
				'all' => 'All',
			);	
			echo $this->load->view('template/getclass',$data,true);
		}

		public function getClassByCourseInAdmission(){
			$course = $this->input->post('course_group_id');
			
			$student_mode = $this->input->post('mode');
			$this->db->select('class_master.*');
			$this->db->from('class_master');
			$this->db->join('course_group', 'class_master.course_group_id = course_group.id');
			if($student_mode=="private" || $student_mode=="PVT"){
				$this->db->where('course_group.private_mode=class_master.mode');
			}else{
				$this->db->where('class_master.mode=course_group.mode');
			}
			$this->db->where('class_master.admission_permission','Y');
			$this->db->where('course_group_id',$course);
			$class_list = $this->db->get()->result_array();
			$data = array(
				'class_list' => $class_list,
			);
			
			echo $this->load->view('template/getclass',$data,true);
		}

		public function add_center_menu_heading($param1 = '', $param2 = '')
		{
			if(!$this->session->has_userdata('adminData')){
				redirect(base_url());
				exit;
			}else
			{
				$data = array();
				if($param1 == 'create'){

					$response = $this->admin_model->create_center_menu_heading();
					echo json_encode(array("status" => 'true')); 

				}
				
				if($param1 == 'update'){
					
					$response = $this->admin_model->update_center_menu_heading($param2);
					echo json_encode(array("status" => 'true'));
					
				}
				if($param1 == 'delete'){
					$response = $this->admin_model->delete_center_menu_heading($param2);
					echo json_encode(array("status" => 'true'));
					
				}
				
				if(empty($param1) ){
					$dataTitle['title'] = "Add center Menu Heading";	
					$data['headings'] = $this->Common_model->center_menu_heading_data();
					$this->load->view('header',$dataTitle);
					$data['name_csrf'] = $this->security->get_csrf_token_name();
					$data['hash_csrf'] = $this->security->get_csrf_hash();
					$this->load->view('admin/center/center_menu_heading_view',$data);
					$this->load->view('footer');
				}
				
			}
		}


		public function get_center_heading_data()
		{

			if ($this->input->method() == "post") 
			{
				$admin_id = 0;
				$data = array();
				$data['headings'] = $this->Common_model->center_menu_heading_data();
				$viewData =  $this->load->view('admin/center/headingListView',$data,true);

				echo json_encode(array(
					"status" => true,
					"data" => $viewData
				));
			}
			
		}

		public function update_center_heading_status()
		{
			if ($this->input->method() == "post") 
			{
				$id    = 0;
				$id    = $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("center_menu_heading",array("id" => $id ),array("status" => $status ));

					$status = true;
					$msg    = "";
					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $data
					));
				}
			}
		}


		public function update_session_unpaid_permission_status()
		{

			if ($this->input->method() == "post") 
			{
				$id    	= 0;
				$id    	= $this->input->post("id");
				$status = $this->input->post("status");
				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("session",array("id" => $id ),array("unpaid_permission" => $status ));

					$dt = $this->db->get_where("session",array("id" => $id ))->result_array();

					if($dt[0]['unpaid_permission'] == 'Y')
					{
						$sts_btn = '<input type="button" name="update_stats" data-id='.$id.' class="btn btn-success unpaid_permission_check" value="Yes">';
					}else{
						$sts_btn = '<input type="button" name="update_stats" data-id='.$id.' class="btn btn-danger unpaid_permission_check" value="No">';
					}
					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $sts_btn
					));
				}
			}
		}

		public function update_doc_permission_status()
		{

			if ($this->input->method() == "post") 
			{
				$id    	= 0;
				$id    	= $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("session",array("id" => $id ),array("document_permission" => $status ));

					$dt = $this->db->get_where("session",array("id" => $id ))->result_array();

					if($dt[0]['document_permission'] == 'Y')
					{
						$sts_btn = '<input type ="button" name="update_doc_stats" data-id='.$id.' class="btn btn-success doc_permission_check" value="Yes">';
					}else{
						$sts_btn = '<input type ="button" name="update_doc_stats" data-id='.$id.' class="btn btn-danger doc_permission_check" value="No">';
					}
					$status = true;
					$msg    = "";

					echo json_encode(array(
						"status" => $status,
						"msg" => $msg,
						"data" => $sts_btn
					));
				}
			}
		}
		public function consolidate_report(){
			$dt = array();
			$dt['title'] = "Student Consolidate Report";
			$this->load->view('header',$dt);
			$this->db->order_by('id', 'Desc');
			$dt['name_csrf'] = $this->security->get_csrf_token_name();
			$dt['hash_csrf'] = $this->security->get_csrf_hash();
			$dt['sessions'] = $this->db->get_where('session', array())->result_array();
			$this->load->view('admin/consolidate_report',$dt);
			$this->load->view('footer');
		}

		public function get_student_consolidate_data()
		{   

			if ($this->input->method() == "post") 
			{
				$course_group_id = 0;
				$data = array();
				$dt   = array();
				$course_group_id  = $this->input->post("course_group_id");
				$class_id  		  = $this->input->post("class_id");
				$approved 		  = $this->input->post("approved");
				$new_exam_form    = $this->input->post("new_exam_form");

				$payment 		  = $this->input->post("payment");
				$enrolled 		  = $this->input->post("enrolled");
				$document_upload  = $this->input->post("document_upload");
				$filter  		  = $this->input->post("filter");
				$session 		  = $this->input->post("session");
				$mode 		  	  = $this->input->post("mode");
				$center_id	  	  = $this->input->post("center_id");
				$university_mode	  	  = $this->input->post("university_mode");

				if($mode != "all"){	 
					
					$dt['mode'] = $mode;
				}
				if($university_mode!="all"){
					$dt['student.university_mode'] = $university_mode ;
				}
				if($session != "All") {	 

					$dt['session'] = $session;
				}else  {
					$dt['name!='] = '';
				}


				if($class_id !=  "All" && $class_id !=  "" ){	 

					$dt['class_id'] = $class_id;
				}

				if($approved != "all"){

					$dt['approved'] = $approved;
				}


				if($new_exam_form != "all"){

					$dt['new_exam_form'] = $new_exam_form;
				}
				if($course_group_id != "all"){

					$dt['course_group_id'] = $course_group_id;
				}

				if($center_id != "all"){

					$dt['center_id'] = $center_id;
				}



				if($payment != "all"){

					$dt['payment_status'] = $payment;
				}
				if($enrolled != "all"){

					$dt['enrolled'] = $enrolled;
				}
				if($document_upload != "all"){

					$dt['document_uploaded'] = $document_upload;
			//print_r($document_upload);
				}

			//print($dt);
			//die;
				if($filter == "list"){

					$data['students'] = $this->Common_model->student_data_consolidate($dt);


				}
				if($filter == "count"){				
					$data['course_count'] = $this->Common_model->student_data_consolidate($dt,$_POST['count_filter']);
				}

			//$this->Common_model->last_query();

				$dt = $this->load->view('admin/student/getStudentConsolidate',$data,true);

				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));



			}
		}

		public function show_form($student_id){
			$data = array();
			$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt'); 
			$data['student'] = $this->Common_model->student_info($student_id);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->load->view('header',array('title' => 'Admission Form'));	
			$this->load->view('template/form',$data);
			$this->load->view('footer');
		}

		public function search_student(){

			$this->load->view('header',array('title' => 'Search Students'));

			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);

			$this->load->view('admin/search_student',$data);
			$this->load->view('footer');
		}


		public function course_detail(){
			if(!$this->session->has_userdata('adminData')){
				redirect(base_url());
				exit;
			}else{
				$admin_id = $this->session->admin_id;
				$course_group = $this->db->get_where('course_group', array())->result_array();
				$data = array('course_group' => $course_group,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('header');
				$this->load->view('admin/course_detail',$data);
				$this->load->view('footer');
			}
		}

		public function updateDdeStudent()
	{	//array('student_id'=> 372663)
	$dde_students = $this->Common_model->get_record('dde_student','*');

	foreach ($dde_students as $dde_student) {

		$studentdata = $this->Common_model->get_record('dde_student_data','*','student_id='.$dde_student['student_id']);

		$courseDetail = $this->Common_model->getRecordById('dde_course','old_id',$dde_student['course_group_id']);

		$dde_student['course_group_id'] = $courseDetail->new_id;
		$dde_student['course_name'] = $courseDetail->new_name;
		$classData = $this->Common_model->getRecordByWhere('class_master','course_group_id='.$courseDetail->new_id.' and  admission_permission="Y" ');

		$studentCount = $this->Common_model->getCountByWhere('student',array('student_id' => $dde_student['student_id']));

		if($studentCount>0){
			continue;
		}

		$dde_student['class_id'] = $classData[0]->id;
		unset($dde_student['enrollment_no']);
		unset($dde_student['approved']);
		unset($dde_student['approved_by']);
		unset($dde_student['new_exam_form']);
		unset($dde_student['temp_exam_form']);
		unset($dde_student['enrolled']);
		$dde_student['class_name'] = $classData[0]->class_name;
		$dde_student['medium'] = $studentdata[0]['medium'];
		$dde_student['university_mode'] = 'REG';
		$dde_student['session'] = 'July 2021';
		unset($dde_student['admit_card']);
		unset($dde_student['cls_id']);
		unset($dde_student['form_no']);
		unset($dde_student['contact']);
		unset($dde_student['signature']);
		unset($dde_student['regi_date']);
		unset($dde_student['forwarded']);
		unset($dde_student['forward_date']);
		unset($dde_student['marksheet_out']);
		unset($dde_student['marksheet_remark']);
		unset($dde_student['admission_in']);
		unset($dde_student['addmission_remark']);
		unset($dde_student['exam_center_id']);
		unset($dde_student['exam_center_code']);
		unset($dde_student['ex']);
		unset($dde_student['delete_request']);
		unset($dde_student['through_bpp']);
		unset($dde_student['old_student_id']);
		unset($dde_student['status']);
		unset($dde_student['pattern']);
		unset($dde_student['prev_pattern']);
		unset($dde_student['new_exam_permission']);
		unset($dde_student['admission_print']);
		unset($dde_student['new_exam_center_id']);
		unset($dde_student['new_exam_center_code']);
		unset($dde_student['permission']);
		unset($dde_student['problem']);
		unset($dde_student['problem_description']);
		unset($dde_student['book_issued']);

		$this->Common_model->insertAll('student',$dde_student);

		$compareArray = $this->Common_model->get_record('student_data','*','student_id=0');
		$studentdata = $studentdata[0];
		$updateData = array_diff_key($studentdata,$compareArray[0]);
		$updateData = array_diff($studentdata,$updateData);
		$updateData['handicapped'] = $studentdata['p_handicapped'];
		$updateData['eligibility'] = $studentdata['ten_sub'];
		$updateData['board'] = $studentdata['ten_board'];
		$updateData['total_marks'] = $studentdata['ten_tmarks'];
		$updateData['marks'] = $studentdata['ten_marks'];
		$updateData['passing_year'] = $studentdata['ten_year'];
		$updateData['percentage'] = $studentdata['ten_per'];
		unset($updateData['id']);
		$this->Common_model->insertAll('student_data',$updateData);

		$txnData = $this->Common_model->get_record('dde_online_payment_transaction','*','student_id='.$dde_student['student_id']);
		$compareArray = $this->Common_model->get_record('online_payment_transaction','*','student_id=1');
		$txnData = $txnData[0];
		$updateData = array_diff_key($txnData,$compareArray[0]);
		$updateData = array_diff($txnData,$updateData);
		$updateData['course_group_id'] = $courseDetail->new_id;
		$updateData['class_id'] = $classData[0]->id;
		$updateData['txnId'] = $txnData['txnid'];
		$updateData['fees_head'] = 'Admission Fees';
		$updateData['admission_type'] = 'Regular';
		unset($updateData['id']);
		$this->Common_model->insertAll('online_payment_transaction',$updateData);

		if($dde_student['document_uploaded']=='Y'){
			$where = array('student_id' => $dde_student['student_id']);
			$admissionDoc = $this->Common_model->get_record('dde_admission_document','*',$where);
			$course = $this->Common_model->getRecordById('course_group','id',$courseDetail->new_id);

			$document_id = $course->document_id;

			foreach ($admissionDoc as $docData) {
				$where = array('category' => $document_id,
					'document' => $docData['document_name'],
				);

				$data = $this->Common_model->get_record('document_category','*',$where);

				$uploadDocData = array(
					'student_id' => $docData['student_id'],
					'course_group_id' => $courseDetail->new_id,
					'document_name' => $docData['document_name'],
					'document_image' => $docData['document_image'],
					'date_time' => $docData['date_time'],
					'status' => $docData['status'],
					'document_category_id' => $data[0]['id'],
				);

				$docId = $this->Common_model->insertAll('admission_document',$uploadDocData);

				$org_image=FCPATH."/assets/reg_doc_image/".$docData['document_image'];
				$ext = pathinfo($org_image, PATHINFO_EXTENSION);
				$imgName = $docId.'.'.$ext;
				$destination=FCPATH."/assets/documents/".$imgName;

				if( rename( $org_image , $destination )){
					echo '<br>moved!'.$destination;
				} else {
					echo '<br>failed'.$docData['student_id'];
				}
				$this->Common_model->updateRecordByConditions('admission_document',array('id'=>$docId),array('document_image' => $imgName));
			}
		}
	}
}

public function getStudentData()
{
	if(!$this->session->has_userdata('adminData')){
		redirect(base_url());
		exit;
	}

	$text_val =$this->input->post('text_val');
	$radio_val = $this->input->post('radio_val');


	if($text_val !='')
	{
		if($text_val !='' && $radio_val == 'enrollment_no')
		{
			$where = array('enrollment_no'=>$text_val);

		}else if($text_val !='' && $radio_val == 'student_id')
		{
			$where = array('student.student_id'=>$text_val);

		}else if($text_val !='' && $radio_val == 'roll_no')
		{
			$where = array('name'=>$text_val

		);

		}else if($text_val !='' && $radio_val == 'student_name')
		{
			$where = array();
			$this->db->like('name', $text_val);

		}else if($text_val !='' && $radio_val == 'adhar_no')
		{
			$where =  array('adhar_no' => $text_val);
		}

		$data['students'] = $this->Common_model->student_data($where);


		$dt =  $this->load->view('admin/student/getStudentConsolidate',$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $dt
		));
	}
	}//fun

	public function update_center_status()
	{
		if ($this->input->method() == "post") 
		{
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");

			
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("center",array("id" => $id ),array("status" => $status ));

				$dt = $this->db->get_where("center",array("id" => $id ))->result_array();

				if($dt[0]['status'] == 'Y')
				{

					$sts_btn = '<input type="button" name="update_center_stats" data-id='.$id.' class="btn btn-success center_status_check" value="Yes">';

				}

				else{

					$sts_btn = '<input type="button" name="update_center_stats" data-id='.$id.' class="btn btn-danger center_status_check" value="No">';

				}

				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $sts_btn
				));
			}
		}
	}

	public function center_login_section($param1 = '', $param2 = '', $param3 = '')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			if(empty($param1) ){

				$data = array();
				$data['title'] = "Center login";
				$data['center_code'] = $this->admin_model->getcenterCode();
				$this->load->view('header',$data);
				$data['center'] = $this->Common_model->get_record('center','');

				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('admin/center_login',$data);
				$this->load->view('footer');
			}
		}
	}

	public function getCenterLogin()
	{
		$data = $row = array();
		$where = "status=   'Y' ";
		$column_order = array(null,'center_code','center_name','contactpersonname','mobile_no_1');
		$column_search = array('center_code','center_name','contactpersonname','mobile_no_1');
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => 'center'
		);
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$center_code = $this->Common_model->encrypt_decrypt($result->center_code,'encrypt');
			//$btn ='<a class="btn btn-primary"  href="'.'https://center.mmyvvonline.com/center/loginAs/'.$center_code.'" target="_blank" >Log As</a>' ;
			$centerURL=str_replace("admin","center",base_url());
			$btn ='<a class="btn btn-primary"  href="'.$centerURL.'/center/loginAs/'.$center_code.'" target="_blank" >Log As</a>' ;
            $status = $result->old_session_permission;	
				if($status == 'Y')
				{
				$permission_btn = '<input type="button" name="update_permission" data-id='.$result->id.' class="btn btn-success permission_checks" value="Yes">';
				}else{
				$permission_btn = '<input type="button" name="update_permission" data-id='.$result->id.' class="btn btn-danger permission_checks" value="No">';
				}
			$i++;
			$data[] = array($i,$result->id, $result->center_code, $result->center_name, $result->contactpersonname,$result->mobile_no_1,$btn,$permission_btn);
	     	}
		  $output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('center',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);
		// Output to JSON format
		echo json_encode($output);	
	}

	public function editForm($student_id = ""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}

		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$titleData = array('title' => 'Admission Form'); 
		$state_list = $this->Common_model->get_record('state','*');
		$eligibility_list = $this->Common_model->get_record('course_group','DISTINCT (eligibility)');
		$district_list = $this->Common_model->get_record('distt','*');
		$course_group_list = $this->Common_model->get_record('course','*');

		$data = array(
			'state_list' => $state_list,
			'district_list' => $district_list,
			'course_group_list' => $course_group_list,
			'eligibility_list' => $eligibility_list,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'student_detail' => $this->db->get_where('student', array("student_id" => $student_id))->row(),
			'student_data'  => $this->db->get_where('student_data', array("student_id" => $student_id))->row()
		);


		$this->load->view('header',$titleData);
		$this->load->view('admin/editForm',$data);
		$this->load->view('footer');
	}

       //-------- add center menus

	function add_center_menus($param='' ,$id='')
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		else{

			if($param == 'create')
			{
				$response = $this->admin_model->create_center_menu();
				echo json_encode(array("status" => 'true'));
			}
			if($param == 'update')
			{
				$response = $this->admin_model->update_center_menu($id);
				echo json_encode(array('status'=>true));
			}
			if($param == 'delete')
			{
				$response = $this->admin_model->delete_center_menu($id);
				echo json_encode(array('status'=>true));
			}
			if(empty($param))
			{
				$this->load->view('header',array('title'=>'Center Menu'));
				$this->load->view('admin/center/add_center_menu_view');
				$this->load->view('footer');
			}

		}

	}

	function get_recent_center_menu()
	{
          //
		if($this->input->method()== 'post')
		{
			$heading_id = 0;
			$heading_id  = $this->input->post("heading_id");
			$data = array();
			$dt   = array();
			if($heading_id != "all"){

				$dt['heading_id'] = $heading_id;
			}

			$data['new_menus'] = $this->Common_model->center_menus_added($dt);

			$dt = $this->load->view('admin/center/menuListView',$data,true);

			echo json_encode(array('status'=>true,
				'data'=>$dt));
		}
          //if($heading_id)
	}

	public function update_center_menu_status()
	{
		if ($this->input->method() == "post") 
		{
			$id    = 0;
			$id    = $this->input->post("id");
			$status = $this->input->post("status");

			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("center_menu",array("id" => $id ),array("status" => $status ));

				echo $this->db->last_query();
				echo json_encode(array(
					"status" => true,
					"msg" => '',
					"data" => $data
				));
			}
		}
	}

	public function updatePassingYear()
	{
		$stduent_ids = $this->Common_model->get_record('pass_year_student_data','*');
		foreach ($stduent_ids as $student) {
			$where = array('student_id' => $student['student_id']);
			$data = array('passing_year' => $student['ten_year']);
				// echo "<pre>";
				// print_r($data);
			$this->Common_model->updateRecordByConditions('student_data',$where,$data);
			echo $this->db->last_query().'<br>';
		}
	}

	public function updateAadharStudent()
	{
		$stduent_ids = $this->Common_model->get_record('aadhar_student','*');
		foreach ($stduent_ids as $student) {
			$where = array('student_id' => $student['student_id']);
			$data = array('adhar_no' => $student['adhar_no'], 'enrollment_no' => $student['enrollment_no'], 'enrolled' => 'Y', 'approved' => 'Y', 'document_uploaded' => 'Y', 'temp_exam_form' => 'Y', 'payment_status' => 'Y');
				// echo "<pre>";
				// print_r($data);
			$this->Common_model->updateRecordByConditions('student',$where,$data);
			echo $this->db->last_query().'<br>';
		}
	}


	public function check_payment_transection(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$this->load->view('header',array('title' => 'Search Transaction Details'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);

			$this->load->view('admin/check_payment_transection',$data);
			$this->load->view('footer');
		}	
	}

	public function get_payment_details(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{

			$text_val =$this->input->post('text_val');
			$radio_val = $this->input->post('radio_val');

			if($text_val !=''){

				if($text_val !='' && $radio_val == 'enrollment_no'){
					$student = $this->Common_model->getRecordById('student','enrollment_no',$text_val);
				}else if($text_val !='' && $radio_val == 'student_id'){
					$student = $this->Common_model->getRecordById('student','student_id',$text_val);
				}

				$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student->student_id ));
				$data = array(
					'student' => $student,
					'paymentDetails' => $paymentDetails,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				if($data){
					$dt =  $this->load->view('admin/account_section/view_student_transaction',$data,true);
					$status = true;
				}else{
					$dt = "This student Does Not Have Any Pending payment Complaint";
					$status = false;
				}
				echo json_encode(array(
					"status" => $status,
					"data" => $dt
				));
			}
		}
	}

	public function updatePaymentTransaction()
	{
		$id = $this->input->post('id');
		$dateTime = $this->input->post('dateTime');
		$student_id = $this->input->post('student_id');
		$txnid = $this->input->post('txnid');
		$dateTime = explode(' ',$dateTime);
		$updateData = array('txnId' => $txnid,'payment_date' => $dateTime[0],'payment_time' => $dateTime[1],'payment' => 'Y', 'payment_status' => 'success');
		$where = array('id' => $id);
		$this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$updateData);
		$txnDetails = $this->Common_model->getRecordById('online_payment_transaction','id',$id);
		if($txnDetails->fees_head=='Exam Fees'){
			$updateData = array('new_exam_form'=> 'Y'); 
		}elseif ($txnDetails->fees_head=='Admission Fees') {
			$updateData = array('payment_status'=> 'Y'); 
		}
		$whereStudent = array('student_id'=> $student_id);
		$result = $this->Common_model->updateRecordByConditions('student',$whereStudent,$updateData);
		if($result){
			$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student_id));
			$data = array('paymentDetails' => $paymentDetails);
			$htmlData = $this->load->view('admin/account_section/view_transaction_details',$data,true); 
			$return = array('success' => 'Transaction Details Updated','data' =>$htmlData);
		}else{
			$return = array('error' => 'An error occurred');
		}
		echo json_encode($return);
		die;
	}

	public function update_enroll_permission_status()
	{
		if ($this->input->method() == "post") 
		{
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");

			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("session",array("id" => $id ),array("enrollment_permission" => $status ));

				$dt = $this->db->get_where("session",array("id" => $id ))->result_array();

				if($dt[0]['enrollment_permission'] == 'Y')
				{
					$sts_btn = '<input type ="button" name="update_enroll_stats" data-id='.$id.' class="btn btn-success enroll_permission_check" value="Yes">';
				}else{
					$sts_btn = '<input type ="button" name="update_enroll_stats" data-id='.$id.' class="btn btn-danger enroll_permission_check" value="No">';
				}
				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $sts_btn
				));
			}
		}
	}


	public function update_exam_form_permission_status()
	{

		if ($this->input->method() == "post") 
		{
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");

			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("session",array("id" => $id ),array("exam_form_permission" => $status ));

				$dt = $this->db->get_where("session",array("id" => $id ))->result_array();

				if($dt[0]['exam_form_permission'] == 'Y')
				{
					$sts_btn = '<input type ="button" name="update_exam_form_stats" data-id='.$id.' class="btn btn-success exam_form_permission_check" value="Yes">';
				}else{
					$sts_btn = '<input type ="button" name="update_exam_form_stats" data-id='.$id.' class="btn btn-danger exam_form_permission_check" value="No">';
				}
				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $sts_btn
				));
			}
		}
	}

	public function exam_form_status(){

		$this->load->view('header',array('title' => 'Exam Wise Student Status(DEC-2021)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);

		$where = array('new_exam_form !=' =>'D');
		$data['permitted_student'] = $this->Common_model->getCountByWhere('student',$where);

		$where = array('new_exam_form' =>'Y');
		$data['filled_student'] = $this->Common_model->getCountByWhere('student',$where);

		$where = array('new_exam_form ' =>'');
		$data['skipped_student'] = $this->Common_model->getCountByWhere('student',$where);

		$where = array('new_exam_form' =>'N');
		$data['not_filled_student'] = $this->Common_model->getCountByWhere('student',$where);


		$this->load->view('admin/exam_wise_student_status',$data);
		$this->load->view('footer');

	}

	public function class_wise_exam_from_status(){

		$this->load->view('header',array('title' => 'Class Wise Exam Form Status(DEC-2021)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('new_exam_form !=' =>'D' );
		$data['counts']=$this->Common_model->new_exam_form_permission_status($where);
		$this->load->view('admin/class_wise_exam_from_status',$data);
		$this->load->view('footer');
	}

	//Result upload report view
	public function class_wise_result_upload_status(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$admin_id = $this->session->admin_id;
			$course_group = $this->db->get_where('course_group', array('exam_form_permission' => 'Y'))->result_array();
			$data = array('course_group' => $course_group,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('header');
			$this->load->view('admin/class_wise_result_upload_status',$data);
			$this->load->view('footer');
		}
	}

	public function class_wise_result_upload_status_report($course_group_id,$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$data=array();	
			$course_group = $this->Common_model->get_record('course_group','*',array('id'=>$course_group_id));
			$data['course_group']=$course_group[0]['course_name'];
			
			$total_paper_count=0;$absent=0;
			if(!$class_id){
				$class_master = $this->db->get_where('class_master', array('course_group_id' => $course_group_id))->result_array();
                
              
				foreach($class_master as $class){
            
					$classArr['class_name']=$class["class_name"];
					
					
					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id');
					$this->db->where('student.new_exam_form','Y');
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.paper_type',"theory");
					$count = $this->db->get()->result();
					
					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id');
					$this->db->where('student.new_exam_form','Y');
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.paper_type',"theory");
					$this->db->where('new_exam_form.theory_marks',"ABS");
					$abs = $this->db->get()->result();
					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id');
					$this->db->where('student.new_exam_form','Y');
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.theory_marks !=', "");
					$this->db->where('new_exam_form.paper_type',"theory");
					$uploaded = $this->db->get()->result();
					
					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id');
					$this->db->where('student.new_exam_form','Y');
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.paper_type',"theory");
					$this->db->where('new_exam_form.int_marks !=', "N");
					$internal = $this->db->get()->result();
					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id');
					$this->db->where('student.new_exam_form','Y');
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.paper_type!=',"theory");
					
					$practicalTotal = $this->db->get()->result();
					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id');
					$this->db->where('student.new_exam_form','Y');
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.paper_type!=',"theory");
					$this->db->where('new_exam_form.p_marks !=', "");
					$this->db->where('new_exam_form.p_marks !=', "N");
					$practical = $this->db->get()->result();
					$classArr['class_id'] = $class['id'];
					$classArr['total_paper_count'] = $count[0]->num;
					$classArr['absent'] = $abs[0]->num;
					$classArr['uploaded'] = $uploaded[0]->num;
					$classArr['internal'] = $internal[0]->num;
					$classArr['practicalTotal'] = $practicalTotal[0]->num;
					$classArr['practical'] = $practical[0]->num;
					$data['class'][]=$classArr;
					$data['course_group_id']=$course_group_id;
					
				}	 
					
			}
			else{
				$class = $this->Common_model->get_record('class_master','*',array('id'=>$class_id));
				
				
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.paper_type',"theory");
				$count = $this->db->get()->result();
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.paper_type',"theory");
				$this->db->where('new_exam_form.theory_marks',"ABS");
				$abs = $this->db->get()->result();
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.paper_type',"theory");
				$this->db->where('new_exam_form.theory_marks !=', "");
				$uploaded = $this->db->get()->result();
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.int_marks !=', "N");
				$this->db->where('new_exam_form.paper_type',"theory");
				$internal = $this->db->get()->result();
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.paper_type!=',"theory");
				
				$practicalTotal = $this->db->get()->result();
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.paper_type!=',"theory");
				$this->db->where('new_exam_form.p_marks !=', "");
				$this->db->where('new_exam_form.p_marks !=', "N");
				$practical = $this->db->get()->result();
				$data['course_group_id']=$course_group_id;
				$data['class_id']=$class_id;
				$data['class_name']=$class[0]['class_name'];
				$data['total_paper_count'] = $count[0]->num;
				$data['absent'] = $abs[0]->num;
				$data['uploaded'] = $uploaded[0]->num;
				$data['internal'] = $internal[0]->num;
				$data['practicalTotal'] = $practicalTotal[0]->num;
				$data['practical'] = $practical[0]->num;
				
			}
			
			$this->load->view('header',array('title' => 'Result Upload Status'));
			$this->load->view('admin/class_wise_result_upload_status_report',$data);
			$this->load->view('footer');
		}
	}

	public function class_wise_remaining_report($remaining,$course_group_id,$class_id){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$data=array();	
			$course_group = $this->Common_model->get_record('course_group','*',array('id'=>$course_group_id));
			$data['course_group']=$course_group[0]['course_name'];
			$class = $this->Common_model->get_record('class_master','*',array('id'=>$class_id));
				
			if($remaining=="theory"){
				$this->db->select('*');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.theory_marks','');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.paper_type',"theory");
				$data['students'] = $this->db->get()->result();

			}
			if($remaining=="internal"){
				$this->db->select('*');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.int_marks', "N");
				$this->db->where('new_exam_form.paper_type',"theory");
				$data['students'] = $this->db->get()->result();
			}	
			if($remaining=="practical"){
				$this->db->select('*');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class_id);
				$this->db->where('new_exam_form.paper_type!=',"theory");
				$this->db->where('new_exam_form.p_marks', "N");
				$data['students'] = $this->db->get()->result();
			}

			$this->load->view('header',array('title' => ' '.ucfirst($remaining).' Marks not submitted of the following Students'));
			$this->load->view('admin/class_wise_remaining_report_table',$data);
			$this->load->view('footer');
		}
	}
	
	public function center_wise_remains_count(){

		$title = array('title' => 'Center Wise Student Remaining Form List');
		$this->load->view('header',$title);	
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('new_exam_form' =>'N');
		$this->db->select('COUNT(*) as student_count,center_code,
			center_name');
		$this->db->group_by('center_id');
		$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
		$this->load->view('admin/center_wise_student_form_count_list',$data); 
		$this->load->view('footer');
	}

	public function final_exam_date_wise_permission()
	{
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$this->load->view('header',array('title' => 'Final Exam Date Wise Permission (DEC-2021)'));
		
		$this->db->select('GROUP_CONCAT(class_id) as class_id ,course_name,class_name,exam_start_date,exam_end_date,id,exam_permission');
		$this->db->from("time_table");

		$this->db->group_by("class_id,exam_start_date"); 
		$this->db->order_by("exam_start_date ","asc");
		$data['category'] = $this->db->get()->result_array();


		$this->load->view('admin/Final Class Wise Exam Permission',$data);
		$this->load->view('footer');

	}

/*
public function update_exam_datewise_permission(){
	$status =  $this->input->post('exam_permission');

		// if(isset($_POST['exam_start_date'])){


		//}
	$id =  $this->input->post('exam_start_date');

	$where = array('exam_start_date'=>$id);


	if($status!=''){
		$st = ($status == 'Y') ? 'N' : 'Y';
		$data=array(
			'exam_permission'=>$st);
	}

	$res=$this->Common_model->updateRecordByConditions('time_table',$where,$data);
	if($status == 'Y'){
		echo json_encode(array('success'=>true));
	}else if($status == 'N'){
		echo json_encode(array('error'=>false));
	}
} */


	public function check_student_exam_records(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$this->load->view('header',array('title' =>'Search Student Answersheet (Dec 2021)'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('admin/check_student_exam_records',$data);
			$this->load->view('footer');
		}

	}


	public function get_student_exam_details(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$text_val =$this->input->post('text_val');
			$radio_val = $this->input->post('radio_val');
			if($text_val !=''){
				if($text_val !='' && $radio_val == 'roll_no'){
					$student = $this->Common_model->getRecordById('student','roll_no',$text_val);
				}
				else if($text_val !='' && $radio_val == 'enrollment_no'){
					$student = $this->Common_model->getRecordById('student','enrollment_no',$text_val);
				}else if($text_val !='' && $radio_val == 'student_id'){
					$student = $this->Common_model->getRecordById('student','student_id',$text_val);
				}  
				$papers = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id' =>$student->student_id, 'paper_type' => 'theory'));
				$data = array(
					'paper' => $papers,
					'student' => $student,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				$dt =  $this->load->view('admin/view_student_examination_view_records',$data,true);
				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}
		}
	}


	public function paper_for_open_book(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$this->load->view('header',array('title' => 'Paper for open book'));
			$this->db->select('*');
			$this->db->from('class_master');
			$this->db->join('paper_master', 'class_master.id = paper_master.class_id');
			$this->db->where('class_master.exam_form_permission', 'Y');
			$data['classes'] = $this->db->get()->result();
			$this->load->view('admin/paper_for_open_book',$data);
			$this->load->view('footer');
		}
	}

	public function delete_answersheet($id)
	{
		$view = $this->Common_model->get_record('upload_exam_ans_sheet','*',array('id'=>$id));
		if(file_exists(FCPATH.'/assets/exam_answersheet/'.$view[0]['upload_date'].'/'.$view[0]['answer_sheet'].'.pdf')){
			$studentdata=unlink( FCPATH . '/assets/exam_answersheet/'.$view[0]['upload_date'].'/'.$view[0]['answer_sheet'].'.pdf' );
		}
		if($studentdata){
			$where = array('id' => $id);
			$data = array(
				'answer_sheet' => '',
				'file_exist'=>'N'
			);
			$response= $this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$data);
			redirect(base_url('admin/admins/check_student_exam_records'));
		}
	}

	public function answersheet_uplaod_status(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{	
			$this->load->view('header',array('title' => 'Answersheet Upload Status'));
			$this->db->select('count(*) as num');
			$this->db->from('new_exam_form');
			$this->db->join('student', 'new_exam_form.student_id = student.student_id');
			$this->db->where('student.new_exam_form','Y');
			$this->db->where('new_exam_form.paper_type','theory');
			$count = $this->db->get()->result();
			$data['total_paper_count'] = $count[0]->num;
			$data['uploaded'] = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array('exam_status'=> 'R','answer_sheet!=' => ''));
			$data['checked'] = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array('teacher_id!='=> ''));
			$this->load->view('admin/answersheet_uplaod_status',$data);
			$this->load->view('footer');
		}
	}

	public function result_uplaoding_status(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{	
			$this->load->view('header',array('title' => 'Main Exam Result Upload Status'));
			
			#total
			$this->db->select('count(*) as num');
			$this->db->from('new_exam_form');
			$this->db->join('student', 'new_exam_form.student_id = student.student_id');
			$this->db->where('student.new_exam_form','Y');
			$this->db->where('new_exam_form.paper_type','theory');
			$count = $this->db->get()->result();
			
			#Absent
			$this->db->select('count(*) as num');
			$this->db->from('new_exam_form');
			$this->db->join('student', 'new_exam_form.student_id = student.student_id');
			$this->db->where('student.new_exam_form','Y');
			$this->db->where('new_exam_form.paper_type','theory');
			$this->db->where('new_exam_form.theory_marks','ABS');
			$abs = $this->db->get()->result();
			
			#uploaded
			$this->db->select('count(*) as num');
			$this->db->from('new_exam_form');
			$this->db->join('student', 'new_exam_form.student_id = student.student_id');
			$this->db->where('student.new_exam_form','Y');
			$this->db->where('new_exam_form.paper_type','theory');
			$this->db->where(array('new_exam_form.theory_marks !='=> ''));
			$uploaded = $this->db->get()->result();
			$data['total_paper_count'] = $count[0]->num;
			$data['uploaded'] = $uploaded[0]->num;
			$data['absent'] = $abs[0]->num;
			$this->load->view('admin/result_uplaoding_status',$data);
			$this->load->view('footer');
		}
	}

	public function add_new_txn(){		
		$txnid = $this->input->post('txnId');
		$Fess_head = $this->input->post('fees_head');
		$dateTime = $this->input->post('dateTime');
		$student_id = $this->input->post('student_id');
		$where = array('student_id'=>$student_id);
		$student_details =  $this->Common_model->getRecordByWhere('student',$where);
		$course_details =  $this->Common_model->getRecordByWhere('course',array('course_group_id'=>$student_details[0]->course_group_id,'session'=>$student_details[0]->session));
		$center_id = $student_details[0]->center_id;
		$course_group_id = $student_details[0]->course_group_id;
		$remark = '';
		// $session = $student_details[0]->session;
		$session = 'June 2022';
		$class_id = $student_details[0]->class_id;
		$name = $student_details[0]->name;

		if($Fess_head!=''){
			$exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->exam_fees+$course_details[0]->program_fees : $course_details[0]->form_fees+$course_details[0]->admission_fees;
		}

		$dateTime = explode(' ',$dateTime);
		$updateData = array('txnId' => $txnid,'fees_head'=>$Fess_head,'payment_date' => $dateTime[0],'payment_time' => $dateTime[1],'payment' => 'Y', 'payment_status' => 'success','student_id'=>$student_id
			,'center_id'=>$center_id,'course_group_id'=>$course_group_id,'class_id'=>$class_id,'remark'=>$remark,'student_name'=>$name,'exam_session'=>$session,
			'admission_type'=>'Regular','amount'=>
			$exam_fees
		);

		$transaction = $this->Common_model->insertAll('online_payment_transaction',$updateData);
		$where1 = array('student_id' => $student_id);
		if($Fess_head=='Admission Fees'){	
			$result = $this->Common_model->updateRecordByConditions('student',$where1,array('payment_status'=> 'Y'));
		}elseif($Fess_head=='Exam Fees'){
			$result = $this->Common_model->updateRecordByConditions('student',$where1,array('new_exam_form'=> 'Y'));
		}
		if($result){
			$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student_id));
			$data = array('paymentDetails' => $paymentDetails);
			$htmlData = $this->load->view('admin/account_section/view_transaction_details',$data,true); 
			$return = array('success' => 'Transaction Details Updated','data' =>$htmlData);
		}else{
			$return = array('error' => 'An error occurred');
		}
		echo json_encode($return);
		die;
	}

	public function regular_consolidate_report(){
		$dataTitle = array(); 
		$dataTitle['title'] = "Regular Student Report";
		$this->load->view('header',$dataTitle);
		$this->db->order_by('id', 'Desc');
		$data['sessions']  = $this->db->get_where('session', array())->result_array();
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('admin/regular_consolidate_report',$data);
		$this->load->view('footer');
	}


	public function get_student_consolidate_data_regular()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
			$course_group_id  = 	$this->input->post("course_group_id");
			$class_id  		  = 	$this->input->post("class_id");
			$approved 		  = 	$this->input->post("approved");
			$payment 		  = 	$this->input->post("payment");
			$enrolled 		  = 	$this->input->post("enrolled");
			$document_upload  = 	$this->input->post("document_upload");
			$filter  		  = 	$this->input->post("filter");
			$session 		  = 	$this->input->post("session");
			$mode 		  	  = 	$this->input->post("mode");
			$center 	  	  = 	$this->input->post("center");

			if($center != "all"){
				$dt['center_id'] = $center;
			}
			if($mode != "all"){	 
				$dt['mode'] = $mode;
			}
			if($session != "all"){	 
				$dt['session'] = $session;
			}else{
				$dt['name!='] = '';
			}
			if($filter == "course"){
				$data['course_count'] = $this->Common_model->student_data_consolidate($dt,'course_group_id');
			}
			if($filter == "center"){
				$data['center_count'] = $this->Common_model->student_data_consolidate($dt,'center_id');
			}
			$dt = $this->load->view('admin/getStudentConsolidateRegular',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		}
	}

	public function view_complaint(){
		$where = array("status" => "P","type" => "Exam Form");
		$centers = $this->Common_model->get_record_group_by_where('center_complaint','center_id',$where );
		$data = array('name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'centers' =>$centers
		);
		$this->load->view('header');
		$this->load->view('admin/view_complaint_status',$data);
		$this->load->view('footer');
	}

	public function get_complaints_status()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
			$center_id  = $this->input->post("center_id");
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$wherecenter = 'center_id='.$center_id.' and status="P" and type="Exam Form"';
			$complaints = $this->Common_model->get_record('center_complaint','*',$wherecenter);
				//	$this->Common_model->last_query();
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);

			if($data['complaints']){
				$dt =  $this->load->view('admin/getComplaints',$data,true);
				$status = true;
			}else{
				$dt = "This Center Does Not Have Any Pending payment Complaint";
				$status = false;
			}
			echo json_encode(array(
				"status" => $status,
				"data" => $dt
			));
		}
	}


	public function update_complaint_status()
	{
		if ($this->input->method() == "post") 
		{
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("center_complaint",array("id" => $id ),array("status" => $status ));
				$dt = $this->db->get_where("center_complaint",array("id" => $id ))->result_array();
				if($dt[0]['status'] == 'D'){
					$sts_btn = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
				}else{
					$sts_btn = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
				}
				$status = true;
				$msg    = "";
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $sts_btn
				));
			}
		}
	}

	public function update_complaint_remark()
	{
		if ($this->input->method() == "post") 
		{
			$id    	= $this->input->post("id");
			$remark = $this->input->post("remark");
			$status = ($remark=='Set') ? 'P' : "D";
			$remark = ($remark=='Set') ? '' : 'Invalid';
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("center_complaint",array("id" => $id ),array("remark" => $remark,"status" => $status));
				$dt = $this->db->get_where("center_complaint",array("id" => $id ))->result_array();

				if($dt[0]['remark'] == 'Invalid'){
					$sts_btn = '<input type="button" name="update_req_remark" data-id='.$id.' class="btn btn-danger remark_check" value="Invalid">';
					$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
				}else{
					$sts_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-success remark_check" value="Set">';
					$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
				}
				$status = true;
				$msg    = "";
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"remarkBtn" => $sts_btn,
					"statusBtn" => $sts_btn2
				));
			}	
		}
	}

	public function complaint_form_sub(){
		$id = $this->input->post('complain');
		$redy = $this->input->post('redy');
		$where = array('id' => $id);
		$studentData = array('type' => $redy);
		$update =  $this->Common_model->updateRecordByConditions('center_complaint',$where,$studentData);
		$dt = $this->Common_model->getRecordById('center_complaint','id',$id);	
		$dt1 = $dt->type;
		if($update){
			echo json_encode(array(
				'data'  => $dt1,
				"success" => ' Updated Successfully',
			));
		}else{
			echo json_encode(array(
				"error" => ' error Occured',
			));
		}
	}
	

	public function student_notification_list($course_id="",$class_id=""){
		$course_id1=$this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id1=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id1 ,'class_id' => $class_id1,'new_exam_form'=>'Y' ,'roll_no!='=>'0','result_show'=>'N'  ));
		$this->load->view('header',array('title' => 'Student Notification List'));
		$this->load->view('admin/student_notification_list',$data);
		$this->load->view('footer');
	}

	public function marksheet_variable(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$admin_id = $this->session->admin_id;
			$data = array();
			$course_detail = $this->Common_model->getRecordByOrder('marksheet_variables',"notification_no,result_date","ASC");
			$data = array('course_detail' => $course_detail ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$this->load->view('header');
			$this->load->view('admin/marksheet_variable',$data);
			$this->load->view('footer');
		}
	}

	public function update_marksheet_variable(){
		$classes    			=	 $_POST['class_id'];
		$notifi_no 				=	 $_POST['notifi_no'];
		$result_date			=	 $_POST['result_date'];
		$backlog_notifi_no 		=	 $_POST['backlog_notifi_no'];
		$backlog_result_date	=	 $_POST['backlog_result_date'];
		$exam_session           =	 $_POST['exam_session'];
		$bar_code_no            =	 $_POST['bar_code_no'];
		foreach($classes as $key => $item){
			$where = array('class_id' => $item);
			$marksheetData = array('bar_code_no' => $bar_code_no,
				'exam_session' => $exam_session,
				'notification_no' => $notifi_no[$key],
				'backlog_notification_no' => $backlog_notifi_no[$key],
				'result_date' => $result_date[$key],
				'backlog_result_date' => $backlog_result_date[$key]);
			$update =  $this->Common_model->updateRecordByConditions('marksheet_variables',$where,$marksheetData);		
		}

		if($update){
			echo json_encode(array(
				"success" => 'Updated Successfully',
			));
		}else{
			echo json_encode(array(
				"error" => ' error Occured',
			));
		}
	}

	public function generate_tr($course_group_id="",$class_id="",$startlimit=1,$pagenumber=1){
		$this->db->order_by('center_id','ASC');
		$this->db->order_by('roll_no','ASC');
		$start=0;
		$start=($startlimit-1)*1000;
		$this->db->limit(1000,$start);
		
		$data['students'] = $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_group_id ,'class_id' => $class_id ,'new_exam_form'=>'Y','roll_no!='=>'0' ));
		$data['class_id'] = $class_id;
		$data['pagenumber']=$pagenumber-1;
		$data['course_group_id'] = $course_group_id;
		$title = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$this->load->view('admin/generate_tr/header2',array('title' =>$title));
		$this->load->view('admin/generate_tr',$data);
		$this->load->view('admin/generate_tr/footer2');
	}

	public function UpdateStudentDataMarks()
	{
		$students = $this->Common_model->get_record('student_data','*');

		foreach ($students as $student) {
			$data = array();
			$where = array('student_id' =>$student['student_id'] );
			echo "<br><br> student_id ".$student['student_id'];
			if($student['total_marks']<$student['marks']){
				echo "<br> Total Marks".	$data['total_marks'] = $student['marks'];
				echo "<br> Marks".	$data['marks'] = $student['total_marks'];
				$this->Common_model->updateRecordByConditions('student_data',$where,$data);
			}
		}
	}

	public function tr_class_list(){
		$where = "id in (select distinct(course_group_id) from student where new_exam_form = 'Y' or exam_form = 'Y' )";
		$data['courses'] = $this->Common_model->get_record('course_group','*',$where);
		$this->load->view('header',array('title' => 'Class List'));
		$this->load->view('admin/tr_class_list',$data);
		$this->load->view('footer');
	}

	public function student_result_permission($course_id="",$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		$data['not_permited_students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'class_id' => $class_id , 'result_show'=>'N' , 'new_exam_form'=>'Y' ));

		$data['permited_students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'class_id' => $class_id , 'result_show'=>'Y' , 'new_exam_form'=>'Y' ));
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('header',array('title' => ''));
		$this->load->view('admin/student_result_permission',$data);
		$this->load->view('footer');
	}

	public function update_student_result_permission(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		if($_POST['not_permitted']){
			$student_ids = (implode(',',$_POST['not_permitted']));
			$data = array('result_show' => 'Y');
			$where = 'student_id in ('.$student_ids.')';
			$update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('result_show' => 'N');
			$where ='student_id in ('.$student_ids.')';
			$update = 	$this->Common_model->updateRecordByConditions('student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/student_result_permission/'.$_POST['course_group_id'].'/'.$_POST['class_id']);
		}
	}

	//withheld_student_list where  remove student to show result 
	public function remove_student_result_permission(){
		
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		if($_POST['not_permitted']){
			$student_ids = (implode(',',$_POST['not_permitted']));
			$data = array('result_show' => 'Y');
			$where = 'student_id in ('.$student_ids.')';
			$update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('result_show' => 'N');
			$where ='student_id in ('.$student_ids.')';
			$update = 	$this->Common_model->updateRecordByConditions('student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/withheld_student_list/'.$_POST['course_group_id'].'/'.$_POST['class_id']);
		}
	}

	public function withheld_student_list($course_id="",$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		$this->db->select('count(*) as cnt ,student.name,student.roll_no,student.course_name, student.class_name , student.center_code,student.course_group_id,student.class_id,student.student_id');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id');
		$this->db->where('student.new_exam_form','Y'); 
		$this->db->where('student.result_show','Y'); 
		$this->db->where('new_exam_form.paper_type','theory'); 
		$this->db->where('new_exam_form.theory_marks',''); 
		$this->db->where('student.course_group_id',$course_id); 
		$this->db->where('student.class_id',$class_id); 
		$this->db->group_by('new_exam_form.student_id');
		
		$data['students'] = $this->db->get()->result();
		
		//$this->db->last_query(); die;
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();

		$this->load->view('header',array('title' => 'List of students'));
		$this->load->view('admin/withheld_student_list',$data);
		$this->load->view('footer');
	}

	public function student_marksheet($course_id="",$class_id="")
	{
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'class_id' => $class_id,'new_exam_form'=>'Y','roll_no!='=>'0' ));

		$this->load->view('admin/generate_tr/header',array('title' => ''));
		$this->load->view('admin/student_marksheet',$data);
		$this->load->view('admin/generate_tr/footer');
	}

	public function update_fees_in_program()
	{
		$programs = $this->Common_model->get_record('program','id, course_group_id','course_group_id!=0' );

		foreach ($programs as $program) {
			$courseData = $this->Common_model->getRecordById('course','course_group_id',$program['course_group_id']);
			$updateData['admission_fees'] = $courseData->form_fees+$courseData->admission_fees;
			$updateData['program_fees'] = $courseData->program_fees;
			$updateData['exam_fees'] = $courseData->exam_fees;
			$courseData = $this->Common_model->getRecordByWhere('course_group',array('id' => $program['course_group_id']));
			$updateData['min_duration'] = $courseData[0]->duration;
			$updateData['eligibility'] = $courseData[0]->eligibility_detail;
			$updateData['mode'] = $courseData[0]->mode;
			$where = array('id' => $program['id']);
			$this->Common_model->updateRecordByConditions('program',$where,$updateData);
			echo $this->db->last_query().'<br>';
		}
	}

	public function updateRegStudent(){
		$dde_students = $this->Common_model->get_record('dde_student','*');
		foreach ($dde_students as $dde_student) {
			$studentdata = $this->Common_model->get_record('dde_student_data','*','student_id='.$dde_student['student_id']);
			$courseDetail = $this->Common_model->getRecordById('dde_course_reg','old_id',$dde_student['course_group_id']);
			$dde_student['course_group_id'] = $courseDetail->new_id;
			$dde_student['course_name'] = $courseDetail->new_name;
			$classData = $this->Common_model->getRecordByWhere('class_master','course_group_id='.$courseDetail->new_id.' and  admission_permission="Y" ');
			$studentCount = $this->Common_model->getCountByWhere('student',array('student_id' => $dde_student['student_id']));

			if($studentCount>0){
				continue;
			}

			$dde_student['class_id'] = $classData[0]->id;
			unset($dde_student['enrollment_no']);
			unset($dde_student['approved']);
			unset($dde_student['approved_by']);
			unset($dde_student['new_exam_form']);
			unset($dde_student['temp_exam_form']);
			unset($dde_student['enrolled']);
			$dde_student['class_name'] = $classData[0]->class_name;
			$dde_student['medium'] = $studentdata[0]['medium'];
			$dde_student['university_mode'] = 'REG';
			$dde_student['session'] = 'July 2021';
			unset($dde_student['admit_card']);
			unset($dde_student['cls_id']);
			unset($dde_student['form_no']);
			unset($dde_student['contact']);
			unset($dde_student['signature']);
			unset($dde_student['regi_date']);
			unset($dde_student['forwarded']);
			unset($dde_student['forward_date']);
			unset($dde_student['marksheet_out']);
			unset($dde_student['marksheet_remark']);
			unset($dde_student['admission_in']);
			unset($dde_student['addmission_remark']);
			unset($dde_student['exam_center_id']);
			unset($dde_student['exam_center_code']);
			unset($dde_student['ex']);
			unset($dde_student['delete_request']);
			unset($dde_student['through_bpp']);
			unset($dde_student['old_student_id']);
			unset($dde_student['status']);
			unset($dde_student['pattern']);
			unset($dde_student['prev_pattern']);
			unset($dde_student['new_exam_permission']);
			unset($dde_student['admission_print']);
			unset($dde_student['new_exam_center_id']);
			unset($dde_student['new_exam_center_code']);
			unset($dde_student['permission']);
			unset($dde_student['problem']);
			unset($dde_student['problem_description']);
			unset($dde_student['book_issued']);
			
			$this->Common_model->insertAll('student',$dde_student);
			echo $this->db->last_query().'<br>';
			$compareArray = $this->Common_model->get_record('student_data','*','student_id=0');
			$studentdata = $studentdata[0];
			$updateData = array_diff_key($studentdata,$compareArray[0]);
			$updateData = array_diff($studentdata,$updateData);
			$updateData['handicapped'] = $studentdata['p_handicapped'];
			$updateData['eligibility'] = $studentdata['ten_sub'];
			$updateData['board'] = $studentdata['ten_board'];
			$updateData['total_marks'] = $studentdata['ten_tmarks'];
			$updateData['marks'] = $studentdata['ten_marks'];
			$updateData['passing_year'] = $studentdata['ten_year'];
			$updateData['percentage'] = $studentdata['ten_per'];
			unset($updateData['id']);
			$this->Common_model->insertAll('student_data',$updateData);
			echo $this->db->last_query().'<br>';
			$new_exam_form = $this->Common_model->get_record('dde_new_exam_form','*',array('student_id' => $dde_student['student_id']));

			foreach ($new_exam_form as $papers) {
				unset($papers['id']);
				$papers['course_group_id'] = $courseDetail->new_id;
				$papers['class_id'] = $classData[0]->id;
				$papers['paper_id'] = $this->Common_model->getSinglefield('paper_master','id',array('paper_code' => $papers['paper_code']));
				$this->Common_model->insertAll('new_exam_form',$papers);
				echo $this->db->last_query().'<br>';
			}
			echo '<br>';
		}
	}

	public function internal_marks_list(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
		}
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData = array('title' => 'Internal  Marks Submission' );
		$this->load->view('header',$titleData);
		$this->db->order_by("int_marks_sub,student.course_group_id,student.class_id", "asc");
		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('new_exam_form', 'student.student_id = new_exam_form.student_id');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->Where('new_exam_form','Y');
		$this->db->Where('paper_type','theory');
		//$this->db->where('`student.class_id` in (154,181,193,199,201,209,221,223,225,195,197,203,211,213,227,158,166,167,172,205)');
        	//$this->db->Where('result_show','Y');
		$this->db->where_in('new_exam_form.int_marks',array('ABS','N'));
		$data['students'] = $this->db->get()->result();//echo $this->db->last_query(); die;
		$this->load->view('admin/student_int_marks_no_list',$data);
		$this->load->view('footer');
	}

	public function student_int_assignment_marks(){ 
		$student_id = $this->input->post('student_id');
		$class_id = $this->input->post('class_id');
		
		$classData	= $this->Common_model->getRecordById('class_master','id',$class_id);
		
		$this->db->select('*');
		$this->db->from('new_exam_form');
		
		$this->db->where('student.student_id',$student_id);
		if($classData->practical_internal_marks=="N")
			$this->db->where('paper_type','theory');
		
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		
		$details = $this->db->get()->result();
	
		$data = array(
			'details' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('admin/student_int_assignment_marks',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));
	}

	public function internal_assignment_marks_sub()
	{
		$data=array();
		$post = $this->input->post();
		$data['paper_id'] = $this->input->post('paper_id');
		$data['marks'] = $this->input->post('marks');
		foreach ($data['paper_id'] as $key => $value){
			$studentData = array(
				'int_marks' => $data['marks'][$key],
			);
			$where =  array(
				'paper_id' =>$value,
				'student_id'  =>$_POST['student_id']
			);
			$Marksentry = $this->Common_model->updateRecordByConditions('new_exam_form',$where,$studentData);
		}
		$where1 =  array(
			'student_id'  =>$_POST['student_id']
		);
		$Data = array(
			'int_marks_sub' => 'Y',
		);
		$Marksentry1 = $this->Common_model->updateRecordByConditions('student',$where1,$Data);
		if($Marksentry1)
		{
			$returndata = array('success'=> 'Form Has Been Submited');
			echo json_encode($returndata);
		}else{
			$returndata = array('error'=> 'An Error Occured');
			echo json_encode($returndata);
		}
	}

	public function view_student_marks(){
		$student_id = $this->input->post('student_id');
		$class_id = $this->input->post('class_id');
		$classData	= $this->Common_model->getRecordById('class_master','id',$class_id);
		$where=array('student.student_id'=>$student_id,);
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$details = $this->db->get()->result();
		$data = array(
			'classData' =>$classData,
			'detail' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('admin/view_student_marks',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));
	}

    public function practical_marks_list(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
		}
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData = array('title' => 'Practical  Marks Submission' );
		$this->load->view('header',$titleData);
		$this->db->order_by("p_marks_sub,student.course_group_id,student.class_id", "asc");
		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('new_exam_form', 'student.student_id = new_exam_form.student_id');
		$this->db->join('class_master', 'student.class_id = class_master.id');
		$this->db->group_by('new_exam_form.student_id');
		$where = array('paper_type!='=>'theory','new_exam_form'=>'Y');
		$this->db->Where($where);
		$this->db->where_in('new_exam_form.p_marks',array('ABS','N'));
	    	//$this->db->where('`student.class_id` in (154,181,193,199,201,209,221,223,225,195,197,203,211,213,227,158,166,167,172,205)');
		$this->db->Where('(project="Y" or practical = "Y")');
		$data['students'] = $this->db->get()->result();
		 // $this->Common_model->last_query();
		$this->load->view('admin/student_practical_marks_no_list',$data);
		$this->load->view('footer');
	}


	public function student_practical_assignment_marks (){
		$student_id = $this->input->post('student_id');
		$where=array('student.student_id'=>$student_id,
			'paper_type!='=>'theory', );
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->join('paper_master', 'paper_master.id = new_exam_form.paper_id');
		$details = $this->db->get()->result();
		$data = array(
			'details' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('admin/student_practical_assignment_marks',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));
	}

	public function practical_assignment_marks_sub()
	{
		$data=array();
		$post = $this->input->post();
		$data['paper_id'] = $this->input->post('paper_id');
		$data['marks'] = $this->input->post('marks');
		foreach ($data['paper_id'] as $key => $value){
			$studentData = array('p_marks' => $data['marks'][$key]);
			$where =  array('paper_id' =>$value,'student_id'  =>$_POST['student_id']);
			$this->Common_model->updateRecordByConditions('new_exam_form',$where,$studentData);
		}
		$where1 =  array('student_id'  => $_POST['student_id'] );
		$Data = array('p_marks_sub' => 'Y');
		$Marksentry1 = $this->Common_model->updateRecordByConditions('student',$where1,$Data);
		$sts_btn = '<button  class="btn btn-info btn-sm font-weight-bold view"  data-toggle="modal" data-target="#kt_datepicker_modal"  data-id = '.$_POST['student_id'].'"
		onclick="view_mark('.$_POST['student_id'].'")">view</button>';
		if($Marksentry1){
			$dt =  "Marks Submited";
		}else{
			$dt = "Error";
		}
		echo json_encode(array(
			"data" => $sts_btn,
			'msg'=>  $dt,
		));
	}

	public function generate_tr_bed($course_group_id="",$class_id=""){
		$this->db->order_by('roll_number','ASC');
		$data['students'] = $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_group_id ,'class_id' => $class_id ,'exam_form'=>'Y','roll_number!='=>'0', 'result_show' => 'N' ));
		$data['class_id'] = $class_id;
		$data['course_group_id'] = $course_group_id;
		$title = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$this->load->view('admin/generate_tr/header2',array('title' =>$title));
		$this->load->view('admin/generate_tr/bed_tr',$data);
		$this->load->view('admin/generate_tr/footer2');
	}

	public function student_notification_list_bed($course_id="",$class_id=""){
		$course_id=$this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$this->db->order_by('roll_number','ASC');
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id, 'class_id' => $class_id, 'exam_form'=>'Y','roll_number!='=>'0','result_show'=>'N' ));
		$this->load->view('admin/generate_tr/header2',array('title' => 'Student Notification List'));
		$this->load->view('admin/student_notification_list_bed',$data);
		$this->load->view('admin/generate_tr/footer2');
	}

	// public function updatePaperpgd()
	// {
	// 	$where = 'id in (140, 139)';
	// 	$papers = $this->Common_model->get_record('paper_master','*',$where);
	// 	foreach ($papers as $paper) {
	// 		$paper_data = array(
	// 			'course_group_id' => $paper['course_group_id'],
	// 			'class_id' => $paper['class_id'],
	// 			'paper_id' => $paper['id'],
	// 			'paper_code' => $paper['paper_code'],
	// 			'paper_type' => $paper['type'],
	// 			'paper_order' => $paper['paper_no'],
	// 		);
	// 		$students = $this->Common_model->get_record('student','*','class_id = 172');
	// 		foreach ($students as $student) {
	// 			$paper_data['student_id'] = $student['student_id'];

	// 			$this->Common_model->insertAll('new_exam_form',$paper_data);
	// 		}
	// 	}
	// }

	public function remaining_student_marks(){
		$this->db->select('*');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->join('student', 'upload_exam_ans_sheet.student_id = student.student_id');
		$this->db->order_by('upload_exam_ans_sheet.course_group_id,upload_exam_ans_sheet.class_id','asc');
		$this->db->where('upload_exam_ans_sheet.remark_status','');
		$this->db->where('upload_exam_ans_sheet.total_marks',0);
		$this->db->where('upload_exam_ans_sheet.teacher_id!=','');
		$this->db->group_by('upload_exam_ans_sheet.student_id');
		$data['students'] = $this->db->get()->result();
		$this->load->view('header',array('title' => 'Student Remaining Marks List'));
		$this->load->view('admin/remaining_student_marks',$data);
		$this->load->view('footer');
		
	}

	public function remaining_failed_student_marks(){
		$this->db->select('*');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->join('student', 'upload_exam_ans_sheet.student_id = student.student_id');
		$this->db->join('new_exam_form', 'upload_exam_ans_sheet.student_id = new_exam_form.student_id and new_exam_form.paper_code = upload_exam_ans_sheet.paper_code');
		$this->db->order_by('upload_exam_ans_sheet.course_group_id,upload_exam_ans_sheet.class_id','asc');
		 // $this->db->where('upload_exam_ans_sheet.remark_status','');
		$this->db->where('upload_exam_ans_sheet.total_marks!=',0);
		$this->db->where('upload_exam_ans_sheet.teacher_id!=','');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('new_exam_form.theory_marks','');
		$this->db->group_by('upload_exam_ans_sheet.student_id');
		$data['students'] = $this->db->get()->result();
		$this->load->view('header',array('title' => 'Student Remaining Marks List'));
		$this->load->view('admin/remaining_failed_student_marks',$data);
		$this->load->view('footer');
	}


    public function search_student_for_mode(){
		$this->load->view('header',array('title' => 'Search Students'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);	
		$this->load->view('admin/search_student_for_mode',$data );
		$this->load->view('footer');
	}

	public function change_student_mode(){
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);	
		$student_id =$_POST['student_id'];
		$data['student']= $this->Common_model->getRecordByWhere('student',array("student_id"=>$student_id));
		$data['student_data']= $this->Common_model->getRecordByWhere('student_data',array("student_id"=>$student_id));
		$html_comment = $this->load->view('admin/student_change_mode' ,$data,true);
		echo json_encode(array(
			"status" => true,
			"data" => $html_comment
		));
	}

	public function update_student_mode(){
		$student_id = $_POST['student_id'];
		$student= $this->Common_model->getRecordByWhere('student',array("student_id"=>$student_id));
		$course = $this->Common_model->getRecordByWhere('course_group',array("id"=>$student[0]->course_group_id));

		if ($student[0]->university_mode=='REG') {
			if ($course[0]->admission_permission_pvt!='Y') {
				$result = array("status" => false, "message"=> "COURSE NOT HAVE PERMISSION");
				echo json_encode($result);
				die();
			}
			
			$updatedata=array('university_mode' => 'PVT');

			if($course[0]->private_mode!=$course[0]->mode){
				$classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$student[0]->course_group_id,'mode'=>$course[0]->private_mode));
				$mode = 'PVT';	
				$updatedata['class_name'] = $classes[0]->class_name;
				$updatedata['class_id']   = $classes[0]->id;
				$updatedata['temp_exam_form']   = 'N';
				$updateOnlineTxn = array('admission_type' => 'Private','class_id'=>$classes[0]->id);
				$deletewhere = array('student_id'=> $student_id, 'class_id' => $student[0]->class_id);
				$this->Common_model->deleteByWhere('new_exam_form',$deletewhere);
			}else{
				$updateOnlineTxn = array('admission_type' => 'Private','class_id'=>$classes[0]->id);
			}

		}elseif($student[0]->university_mode=='PVT'){
			if ($course[0]->admission_permission!='Y') {
				$result = array("status" => false, "message"=> "COURSE NOT HAVE PERMISSION");
				echo json_encode($result);
				die();
			}
			$updatedata=array('university_mode' => 'REG');
			
			if($course[0]->private_mode!=$course[0]->mode){
				$classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$student[0]->course_group_id,'mode'=>$course[0]->mode));
				$mode = 'REG';
				$updatedata['class_name'] = $classes[0]->class_name;
				$updatedata['class_id']   = $classes[0]->id;
				$updatedata['temp_exam_form']   = 'N';
				$updateOnlineTxn = array('admission_type' => 'Regular','class_id'=>$classes[0]->id);
				$deletewhere = array('student_id'=> $student_id, 'class_id' => $student[0]->class_id);
				$this->Common_model->deleteByWhere('new_exam_form',$deletewhere);
			}else{
				$updateOnlineTxn = array('admission_type' => 'Regular');
			}
		}
		$this->Common_model->updateRecordByConditions('online_payment_transaction',array('student_id'=>$student_id),$updateOnlineTxn);
		$this->Common_model->updateRecordByConditions('student',array('student_id'=>$student_id),$updatedata);
		$result = array("status" => true, "mode"=> $mode);
		echo json_encode($result);
	}

	public function updatePvtDdeStudent()
	{
		$dde_students = $this->Common_model->get_record('dde_student','*');

		foreach ($dde_students as $dde_student) {
			$studentdata = $this->Common_model->get_record('dde_student_data','*','student_id='.$dde_student['student_id']);
			$courseDetail = $this->Common_model->getRecordById('dde_course','old_id',$dde_student['course_group_id']);
			$dde_student['course_group_id'] = $courseDetail->new_id;
			$dde_student['course_name'] = $courseDetail->new_name;
			$courseData = $this->Common_model->get_record('course_group','*',array('id' => $dde_student['course_group_id']));

			$this->db->where(array('mode' => $courseData[0]['private_mode']));
			$classData = $this->Common_model->getRecordByWhere('class_master','course_group_id='.$courseDetail->new_id.' and  admission_permission="Y"');
			$studentCount = $this->Common_model->getCountByWhere('student',array('student_id' => $dde_student['student_id']));
			if($studentCount>0){
				echo "alreay exist->". $dde_student['student_id'].'<br>';
				continue;
			}

			$dde_student['class_id'] = $classData[0]->id;
			unset($dde_student['enrollment_no']);
			unset($dde_student['approved']);
			unset($dde_student['approved_by']);
			unset($dde_student['new_exam_form']);
			unset($dde_student['temp_exam_form']);
			unset($dde_student['enrolled']);
			$dde_student['class_name'] = $classData[0]->class_name;
			$dde_student['medium'] = $studentdata[0]['medium'];
			$dde_student['university_mode'] = 'PVT';
			$dde_student['session'] = 'July 2021';
			unset($dde_student['admit_card']);
			unset($dde_student['cls_id']);
			unset($dde_student['form_no']);
			unset($dde_student['contact']);
			unset($dde_student['signature']);
			unset($dde_student['regi_date']);
			unset($dde_student['forwarded']);
			unset($dde_student['forward_date']);
			unset($dde_student['marksheet_out']);
			unset($dde_student['marksheet_remark']);
			unset($dde_student['admission_in']);
			unset($dde_student['addmission_remark']);
			unset($dde_student['exam_center_id']);
			unset($dde_student['exam_center_code']);
			unset($dde_student['ex']);
			unset($dde_student['delete_request']);
			unset($dde_student['through_bpp']);
			unset($dde_student['old_student_id']);
			unset($dde_student['status']);
			unset($dde_student['pattern']);
			unset($dde_student['prev_pattern']);
			unset($dde_student['new_exam_permission']);
			unset($dde_student['admission_print']);
			unset($dde_student['new_exam_center_id']);
			unset($dde_student['new_exam_center_code']);
			unset($dde_student['permission']);
			unset($dde_student['problem']);
			unset($dde_student['problem_description']);
			unset($dde_student['book_issued']);

			$this->Common_model->insertAll('student',$dde_student);
			echo $this->db->last_query().'<br>';
			$compareArray = $this->Common_model->get_record('student_data','*','student_id=0');
			$studentdata = $studentdata[0];
			$updateData = array_diff_key($studentdata,$compareArray[0]);
			$updateData = array_diff($studentdata,$updateData);
			$updateData['handicapped'] = $studentdata['p_handicapped'];
			$updateData['eligibility'] = $studentdata['ten_sub'];
			$updateData['board'] = $studentdata['ten_board'];
			$updateData['total_marks'] = $studentdata['ten_tmarks'];
			$updateData['marks'] = $studentdata['ten_marks'];
			$updateData['passing_year'] = $studentdata['ten_year'];
			$updateData['percentage'] = $studentdata['ten_per'];
			unset($updateData['id']);
			$this->Common_model->insertAll('student_data',$updateData);
			echo $this->db->last_query().'<br>';
			$txnData = $this->Common_model->get_record('dde_online_payment_transaction','*','student_id='.$dde_student['student_id']);
			$compareArray = $this->Common_model->get_record('online_payment_transaction','*','student_id=1');
			$txnData = $txnData[0];
			$updateData = array_diff_key($txnData,$compareArray[0]);
			$updateData = array_diff($txnData,$updateData);
			$updateData['course_group_id'] = $courseDetail->new_id;
			$updateData['class_id'] = $classData[0]->id;
			$updateData['txnId'] = $txnData['txnid'];
			$updateData['fees_head'] = 'Admission Fees';
			$updateData['admission_type'] = 'Private';
			unset($updateData['id']);
			$this->Common_model->insertAll('online_payment_transaction',$updateData);
			echo $this->db->last_query().'<br>';
			if($dde_student['document_uploaded']=='Y'){
				$where = array('student_id' => $dde_student['student_id']);
				$admissionDoc = $this->Common_model->get_record('dde_admission_document','*',$where);
				$course = $this->Common_model->getRecordById('course_group','id',$courseDetail->new_id);

				$document_id = $course->document_id;
				foreach ($admissionDoc as $docData) {
					$where = array('category' => $document_id,
						'document' => $docData['document_name'],
					);
					$data = $this->Common_model->get_record('document_category','*',$where);
					$uploadDocData = array(
						'student_id' => $docData['student_id'],
						'course_group_id' => $courseDetail->new_id,
						'document_name' => $docData['document_name'],
						'document_image' => $docData['document_image'],
						'date_time' => $docData['date_time'],
						'status' => $docData['status'],
						'document_category_id' => $data[0]['id'],
					);
					$docId = $this->Common_model->insertAll('admission_document',$uploadDocData);
					echo $this->db->last_query().'<br>';
					$org_image=FCPATH."/assets/reg_doc_image/".$docData['document_image'];
					$ext = pathinfo($org_image, PATHINFO_EXTENSION);
					$imgName = $docId.'.'.$ext;
					$destination=FCPATH."/assets/documents/".$imgName;

					if( rename( $org_image , $destination )){
						echo '<br>moved!'.$destination;
					}else{
						echo '<br>failed'.$docData['student_id'];
					}
					$this->Common_model->updateRecordByConditions('admission_document',array('id'=>$docId),array('document_image' => $imgName));
					echo $this->db->last_query().'<br>';
				}
			}
		}
	}

	public function remaining_student_average_marks($param = 1,$param1 = 0){

		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);	
		$this->db->select('*,count(total_marks) as num');
		$this->db->from('upload_exam_ans_sheet');
		$this->db->join('new_exam_form', 'upload_exam_ans_sheet.student_id  = new_exam_form.student_id and upload_exam_ans_sheet.paper_code = new_exam_form.paper_code');
		$this->db->order_by('upload_exam_ans_sheet.course_group_id,upload_exam_ans_sheet.class_id','asc');
		$this->db->where('upload_exam_ans_sheet.remark_status','');
		if ($param1==0) {
			$this->db->where('upload_exam_ans_sheet.total_marks',0);
		}
		$this->db->where('new_exam_form.theory_marks','');
		$this->db->where('new_exam_form.paper_type','theory');
		$this->db->where('upload_exam_ans_sheet.teacher_id!=','');
		$this->db->group_by('upload_exam_ans_sheet.student_id');
		$this->db->having(" num = $param ");
		$data['students'] = $this->db->get()->result();
		$this->load->view('header',array('title' => 'Student Remaining Marks List'));
		$this->load->view('admin/remaining_student_average_marks',$data);
		$this->load->view('footer');
		
	}

	public function view_student_paper_marks_details(){
		$student_id = $this->input->post('student_id');
		$where=array('new_exam_form.student_id'=>$student_id,'paper_type'=>'theory');
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$details = $this->db->get()->result();
		$data = array(
			'detail' => $details,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		if($data){
			$model =  $this->load->view('admin/view_student_paper_marks_details',$data,true);
			$status = true;
		}
		echo json_encode(array(
			"status" => $status,
			"data" => $model
		));
	}

	public function update_mark_for_all_question()
	{     

		$data=array();
		$post = $this->input->post();
		$data['paper_code'] = $this->input->post('paper_code');
		$data['paper_marks'] = $this->input->post('marks');

		$total_marks_count = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet','student_id='.$_POST['student_id'].' and total_marks=0 and  class_id='.$_POST['class_id'].'');

		foreach ($data['paper_code'] as $key => $value){

			if($total_marks_count[0]->paper_code==$value){
				$paper_no= $data['paper_marks'][$key];
				$que_all= round($paper_no/5);
				
				$marks_5 = $paper_no - ($que_all*4);
				
				$studentData = array('total_marks' => $data['paper_marks'][$key]
					,'que_1'=>$marks_5,'que_2'=>$que_all,'que_3'=>$que_all,'que_4'=>$que_all,'que_5'=>$que_all
				);
				$where =  array('paper_code' =>$value,'student_id'  =>$_POST['student_id']
			);
				$update =	$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet',$where,$studentData);
			}
		}

		if($update){
			echo json_encode(array('status'=>true));
		}
	}

	public function allot_papers()
	{
		$groups = $_POST['group'];
		$sub_group = $_POST['sub_group'];
		$papers = $_POST['papers'];
		$i = 0;
		foreach ($groups as $group) {
			$paper = $this->Common_model->getRecordById('paper_master','id',$papers[$i]);
			$group_paper_data = array(
				'group_id' => $group,
				'paper_name' => $paper->paper_name,
				'paper_code' => $paper->paper_code,
				'paper_id' => $paper->id,
				'sub_group_id' => $sub_group[$i]
			);
				$this->Common_model->insertAll('group_paper',$group_paper_data);
				$paper_data = array('sub_group_id' => $paper->sub_group_id.','.$sub_group[$i]);
				$where = array('id' => $papers[$i]);
				$this->Common_model->updateRecordByConditions('paper_master',$where,$paper_data);
			$i++;
		}

		redirect(base_url('admin/admins/classes'));
	}

	public function check_student_for_session_change_report(){
		$dt = array();
		$dt['title'] = "Search Student For Session change";
		$this->load->view('header',$dt);
		$this->db->order_by('id', 'Desc');
		$dt['name_csrf'] = $this->security->get_csrf_token_name();
		$dt['hash_csrf'] = $this->security->get_csrf_hash();
		$dt['sessions'] = $this->db->get_where('session', array())->result_array();
		$this->load->view('admin/show_course_student_for_session_change',$dt);
		$this->load->view('footer');
	}

	public function get_student_for_session_change_report()
		{   

			if ($this->input->method() == "post") 
			{
				$course_group_id = 0;
				$data = array();
				$dt   = array();
				$count_filter='course_group_id';
				$course_group_id  = $this->input->post("course_group_id");
				$class_id  		  = $this->input->post("class_id");
				$approved 		  = $this->input->post("approved");
				$new_exam_form    = $this->input->post("new_exam_form");

				$data['payment']= $payment 		  = $this->input->post("payment");
				$enrolled 		  = $this->input->post("enrolled");
				$data['document_upload']=$document_upload  = $this->input->post("document_upload");
				$filter  		  = $this->input->post("filter");
				$data['session']  =$session 		  = $this->input->post("session");
				$mode 		  	  = $this->input->post("mode");
				$center_id	  	  = $this->input->post("center_id");
				$university_mode	  	  = $this->input->post("university_mode");

				if($mode != "all"){	 
					
					$dt['mode'] = $mode;
				}
				if($university_mode!="all"){
					$dt['student.university_mode'] = $university_mode ;
				}
				if($session != "All") {	 
					$sessionRow = $this->db->get_where('session', array('id'=>$session))->result_array();
					$dt['session'] = $sessionRow[0]['session'] ;
				}else  {
					$dt['name!='] = '';
				}


				if($class_id !=  "All" && $class_id !=  "" ){	 

					$dt['class_id'] = $class_id;
				}

				if($approved != "all"){

					$dt['approved'] = $approved;
				}


				if($new_exam_form != "all"){

					$dt['new_exam_form'] = $new_exam_form;
				}
				if($course_group_id != "all"){

					$dt['course_group_id'] = $course_group_id;
				}

				if($center_id != "all"){

					$dt['center_id'] = $center_id;
				}



				if($payment != "all"){

					$dt['payment_status'] = $payment;
				}
				if($enrolled != "all"){

					$dt['enrolled'] = $enrolled;
				}
				if($document_upload != "all"){

					$dt['document_uploaded'] = $document_upload;
			
				}

			
				if($filter == "list"){

					$data['students'] = $this->Common_model->student_data_consolidate($dt);


				}
				if($filter == "count"){				
					$data['course_count'] = $this->Common_model->student_data_consolidate($dt,$count_filter);
				}
			
		

				$dt = $this->load->view('admin/getStudentForSessionChangeReport',$data,true);

				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));



			}
		}

		public function get_student_list_for_session_change_report()
		{   
			$dat = array();
			$dat['title'] = " Student For Session change";
			$this->load->view('header',$dat);
		
				$course_group_id = 0;
				$count_filter='course_group_id';
				$data = array();
				$dt   = array();
			 	$course_group_id  = $this->uri->segment(5);
				$class_id  		  = '';
				$approved 		  = '';
				$new_exam_form    = 'all';

			 	$payment 		  = $this->uri->segment(3);
				$enrolled 		  = 'all';
				$document_upload  = $this->uri->segment(4);
				$filter  		  = "list";
				$session 		  = $this->uri->segment(2);
				$mode 		  	  = 'all';
				$center_id	  	  = 'all';
				$university_mode	  	  = 'all';

				if($mode != "all"){	 
					
					$dt['mode'] = $mode;
				}
				if($university_mode!="all"){
					$dt['student.university_mode'] = $university_mode ;
				}
				if($session != "All") {	 
					$sessionRow = $this->db->get_where('session', array('id'=>$session))->result_array();
					$dt['session'] = $sessionRow[0]['session'] ;
				}else  {
					$dt['name!='] = '';
				}


				if($class_id !=  "All" && $class_id !=  "" ){	 

					$dt['class_id'] = $class_id;
				}

				if($approved != "all"){

					$dt['approved'] = $approved;
				}


				if($new_exam_form != "all"){

					$dt['new_exam_form'] = $new_exam_form;
				}
				if($course_group_id != "all"){

					$dt['course_group_id'] = $course_group_id;
				}

				if($center_id != "all"){

					$dt['center_id'] = $center_id;
				}



				if($payment != "all"){

					$dt['payment_status'] = $payment;
				}
				if($enrolled != "all"){

					$dt['enrolled'] = $enrolled;
				}
				if($document_upload != "all"){

					$dt['document_uploaded'] = $document_upload;
			
				}

			
				if($filter == "list"){

					$data['students'] = $this->Common_model->student_data_consolidate($dt);


				}
				if($filter == "count"){				
					$data['course_count'] = $this->Common_model->student_data_consolidate($dt,$count_filter);
				}
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->db->order_by('id', 'Desc');
				
				$data['sessions'] = $this->db->get_where('session', array())->result_array();
				$data['listSession'] = $session;
				$this->load->view('admin/show_student_for_session_change',$data);
				$this->load->view('footer');


			}
	
			public function update_student_session()
			{ 
				if(!$this->session->has_userdata('adminData')){
					redirect(base_url());
					exit;
				}else{
					if ($this->input->method() == "post") 
					{
						$session    = $this->input->post("session");
						$students    = $this->input->post("students");
						$studentArr=explode(',',$students);
						$sessionRow = $this->db->get_where('session', array('id'=>$session))->result_array();
						$sessionValue = $sessionRow[0]['session'] ;
						
						foreach($studentArr as $k=>$val){
							$studentRow = $this->db->get_where('student', array('student_id'=>$val))->result_array();
							$path = 'assets/student_image/'.$sessionValue.'/'.$studentRow[0]['photo'];
							$prev_path = 'assets/student_image/'.$studentRow[0]['session'].'/'.$studentRow[0]['photo'];
							$upload = rename($prev_path,$path); 
							
							$data = $this->Common_model->updateRecordByConditions("student",array("student_id" => $val ),array("session" => $sessionValue ));
						}
					}
					redirect(base_url('check_student_for_session_change_report'));
				}	
			}
			
}// class
