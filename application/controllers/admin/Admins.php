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
		$this->load->library('numbertowordconvertsconver');
		$this->master = $this->Common_model->getSingleRow('master');
		 $this->exam_table = $this->master->student_exam_table;
		 $this->exam_form = $this->master->exam_form_col;
		 $this->roll_no = $this->master->roll_number_col;
		 $this->result_table = $this->master->student_result_table;
		 $this->old_result_table = $this->master->old_student_result_table;
		 $this->exam_form_table = $this->master->exam_form_table;
		 $this->old_exam_form_table = $this->master->old_exam_form_table;
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
				$new_session=$_POST['session'];
				$arr=explode(" ",$new_session);
				$new_month=$arr[0];
				$old_year=$arr[1]-1;
				$oldSession=$new_month." ".$old_year;
				$this->db->where('session', $oldSession);
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
					$this->db->where('session',$oldSession);
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
							if($new_month=="Jan"){
								$Data['admission_permission_regular'] =$course->admission_permission_regular;
								$Data['admission_permission_private'] =$course->admission_permission_private;
							}else{
								$Data['admission_permission_regular'] ="Y";
								if(in_array($course->course_group_id,array(12,13,21,22,23,24,25,39,40,41,52,53,54,55,57,59,61,62,65,66,71,73)))
									$Data['	admission_permission_private'] ="Y";
								else	
									$Data['	admission_permission_private'] ="N";
							}
							
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
			//$this->db->where_in('class_id',array('101','102','104','105','107','108','110','111','116','117','119','120','125','126','128','129','131','132','134','135','137','138','140','143','146','149','152','154','155','158','159','160','162','163','164','165','166','167','168','169','170','171','172','173','174','175','177','178','180','181','182','183','184','185','187','189','191','192','194','196','198','200','202','204','206','208','210','212','214','216','218','222','224','226','228','230','232','234','236','238','240','242','244','246','248','250','252','254','264','273','274','276','280','283','284','285','286','287','288','289','290','291','292','293','294','295','296','297','298','299'));
			$this->db->order_by("course_group_id,class_id,cbcs_paper,paper_no ","asc");
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

			if((!$this->session->has_userdata('adminData')) || ($this->session->userdata('account_type')!='Admins')){
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
				$pattern = $this->input->post("pattern");
				if($class_id) 
				{
					$data = $this->Common_model->getPaperCode($course_group_id,$class_id,$pattern);
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
			$this->db->order_by('id');
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
				$course_type 		  = $this->input->post("course_type");

				$payment 		  = $this->input->post("payment");
				$enrolled 		  = $this->input->post("enrolled");
				$document_upload  = $this->input->post("document_upload");
				$filter  		  = $this->input->post("filter");
				$session 		  = $this->input->post("session");
				$mode 		  	  = $this->input->post("mode");
				$center_id	  	  = $this->input->post("center_id");
				$university_mode	  	  = $this->input->post("university_mode");
				$center_type	  = $this->input->post("center_type");

				if($mode != "all"){	 
					
					$dt['mode'] = $mode;
				}
				if($university_mode!="all"){
					$dt['student.university_mode'] = $university_mode ;
				}
				if($course_type != "All"){
					if ($course_type=='UGPG') {
						$this->db->where_in('course_type',array('UG','PG'));
					}else{
						$dt['course_type'] = $course_type;
					}
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
				// if($center_type != "all"){

				// 	$dt['center_type'] = $center_type;
		
				// }


			//print($dt);
			//die;
				if($filter == "list"){

					$data['students'] = $this->Common_model->student_data_consolidate($dt,"",$center_type);
					
				}
				if($filter == "count"){				
					$data['course_count'] = $this->Common_model->student_data_consolidate($dt,$_POST['count_filter'],$center_type);
				
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
		$dde_student['session'] = 'July 2023';
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
			$where = array('roll_no'=>$text_val);

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
		$column_order = array(null,'id','center_code','center_name','contactpersonname','mobile_no_1',null,'exam_form_permission','old_session_permission');
		$column_search = array('id','center_code','center_name','contactpersonname','mobile_no_1','exam_form_permission','old_session_permission');
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
			$exam_form_permission = $result->exam_form_permission;	
			if($exam_form_permission == 'Y')
			{
			$exam_form_permission_btn = '<input type="button" name="update_exam_form_permission" data-id='.$result->id.' class="btn btn-success exam_form_permission_checks" value="Yes">';
			}else{
			$exam_form_permission_btn = '<input type="button" name="update_exam_form_permission" data-id='.$result->id.' class="btn btn-danger exam_form_permission_checks" value="No">';
			}	
			$temp_exam_form_permission = $result->temp_exam_form;	
			if($temp_exam_form_permission == 'Y')
			{
			$temp_exam_form_permission_btn = '<input type="button" name="update_temp_exam_form_permission" data-id='.$result->id.' class="btn btn-success temp_exam_form_permission_checks" value="Yes">';
			}else{
			$temp_exam_form_permission_btn = '<input type="button" name="update_temp_exam_form_permission" data-id='.$result->id.' class="btn btn-danger temp_exam_form_permission_checks" value="No">';
			}	
			$temp_admission_payment = $result->temp_admission_payment;	
			if($temp_admission_payment == 'Y')
			{
			$temp_admission_payment_btn = '<input type="button" name="update_temp_admission_payment" data-id='.$result->id.' class="btn btn-success temp_admission_payment_checks" value="Yes">';
			}else{
			$temp_admission_payment_btn = '<input type="button" name="update_temp_admission_payment" data-id='.$result->id.' class="btn btn-danger temp_admission_payment_checks" value="No">';
			}	
			$i++;
			$data[] = array($i,$result->id, $result->center_code, $result->center_name, $result->contactpersonname,$result->mobile_no_1,$btn,$temp_exam_form_permission_btn,$temp_admission_payment_btn,$exam_form_permission_btn,$permission_btn);
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
				//$mobile_number = $this->Common_model->getSinglefield('student_data','p_mobile_no',array('student_id' => $student->student_id));
				$studentContactData = $this->Common_model->getRecordById('student_data','student_id',$student->student_id);
				$paymentDetails = $this->Common_model->getRecordByWhere('online_payment_transaction',array('student_id' => $student->student_id ));
				$data = array(
					'student' => $student,
					'studentContactData'=>$studentContactData,
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

		$this->load->view('header',array('title' => 'Exam Form Status(June 2024)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);

		$where = array('new_exam_form !=' =>'D');
		$data['permitted_student'] = $this->Common_model->getCountByWhere('student',$where);

		$where = array('new_exam_form' =>'Y');
		$data['filled_student'] = $this->Common_model->getCountByWhere('student',$where);

		$where = array('new_exam_form ' =>'S');
		$data['skipped_student'] = $this->Common_model->getCountByWhere('student',$where);

		$where = array('new_exam_form' =>'N');
		$data['not_filled_student'] = $this->Common_model->getCountByWhere('student',$where);

		//backlog

		$where = array('exam_form !=' =>'D','exam_year' =>'June 2024');
		$data['permitted_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);

		$where = array('exam_form' =>'Y','exam_year' =>'June 2024');
		$data['filled_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);

		$where = array('exam_form ' =>'S','exam_year' =>'June 2024');
		$data['skipped_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);

		$where = array('exam_form' =>'N','exam_year' =>'June 2024');
		$data['not_filled_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);



		$this->load->view('admin/exam_wise_student_status',$data);
		$this->load->view('footer');

	}

	public function class_wise_exam_from_status(){

		$this->load->view('header',array('title' => 'Class Wise Exam Form Status(June 2024)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('new_exam_form !=' =>'D' );
		$data['counts']=$this->Common_model->new_exam_form_permission_status($where);
		$this->load->view('admin/class_wise_exam_from_status',$data);
		$this->load->view('footer');
	}

	public function class_wise_old_exam_from_status(){

		$this->load->view('header',array('title' => 'Class Wise Exam Form Status(Dec 2023)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('exam_form !=' =>'D' );
		$data['counts']=$this->Common_model->old_exam_form_permission_status($where);
		$this->load->view('admin/class_wise_old_exam_from_status',$data);
		$this->load->view('footer');
	}

	public function class_wise_old_backlog_exam_form_status(){

		$this->load->view('header',array('title' => 'Class Wise Backlog Exam Form Status(Dec 2023)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('exam_form !=' =>'D' , 'exam_year'=>'Dec 2023');
		$data['counts']=$this->Common_model->backlog_exam_form_permission_status($where);
		$this->load->view('admin/class_wise_backlog_old_exam_from_status',$data);
		$this->load->view('footer');
	}

	public function class_wise_backlog_exam_from_status(){

		$this->load->view('header',array('title' => 'Class Wise Backlog Exam Form Status(June 2024)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('exam_form !=' =>'D','exam_year'=>'June 2024');
		$data['counts']=$this->Common_model->backlog_exam_form_permission_status($where);
		$this->load->view('admin/class_wise_backlog_exam_from_status',$data);
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
			$course_groupids = array_column($course_group, 'id');
			$this->db->where_in('course_group_id',$course_groupids);
		   $this->db->order_by('course_name', "asc");
		   $course_group = $this->Common_model->get_record('student','DISTINCT(course_group_id) as  course_group_id,course_name' ,array($this->exam_form=>'Y'));
			$data = array('course_group' => $course_group,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('header');
			$this->load->view('admin/class_wise_result_upload_status',$data);
			$this->load->view('footer');
		}
	}

	public function class_wise_result_upload_status_report($courseType="ALL",$course_group_id,$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$data=array();	
			$course_group = $this->Common_model->get_record('course_group','*',array('id'=>$course_group_id));
			$data['course_group']=$course_group[0]['course_name'];
			
			$total_paper_count=0;$absent=0;
			if ($class_id!='') {
				$this->db->where('id',$class_id);
			}
			$class_master = $this->db->get_where('class_master', array('course_group_id' => $course_group_id))->result_array();

			foreach($class_master as $class){

				$classArr['class_name']=$class["class_name"];

				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				if($courseType!="ALL")
					$this->db->where('student.university_mode',$courseType);
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class['id']);
				$this->db->where('new_exam_form.paper_type',"theory");
				$count = $this->db->get()->result();

				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				if($courseType!="ALL")
					$this->db->where('student.university_mode',$courseType);
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class['id']);
				$this->db->where('new_exam_form.paper_type',"theory");
				$this->db->where('new_exam_form.theory_marks',"ABS");
				$abs = $this->db->get()->result();
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				if($courseType!="ALL")
					$this->db->where('student.university_mode',$courseType);
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class['id']);
				$this->db->where('new_exam_form.theory_marks !=', "");
				$this->db->where('new_exam_form.paper_type',"theory");
				$uploaded = $this->db->get()->result();

				// $this->db->select('count(*) as num');
				// $this->db->from('new_exam_form');
				// $this->db->join('student', 'new_exam_form.student_id = student.student_id');
				// $this->db->where('student.new_exam_form','Y');
				// if($courseType!="ALL")
				// 	$this->db->where('student.university_mode',$courseType);
				// $this->db->where('new_exam_form.course_group_id',$course_group_id);
				// $this->db->where('new_exam_form.class_id',$class['id']);
				// $this->db->where('new_exam_form.paper_type',"theory");
				// $this->db->where('new_exam_form.int_marks !=', "N");
				// $internal = $this->db->get()->result();
				$internalVar = 0;
					$internalcountVar =0;
				if($courseType=="PVT"){
					$internalVar = 0;
					$internalcountVar =0;
				}else{
					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id  and new_exam_form.class_id = student.class_id ');
					$this->db->where('student.new_exam_form','Y');
					if($courseType!="PVT")
						$this->db->where('student.university_mode',"REG");
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.paper_type',"theory");
					$this->db->join('paper_master', 'new_exam_form.class_id = paper_master.class_id  and new_exam_form.paper_code = paper_master.paper_code ');
					$this->db->where('paper_master.max_internal_marks!=',"0");
					$internalcount = $this->db->get()->result();

					$this->db->select('count(*) as num');
					$this->db->from('new_exam_form');
					$this->db->join('student', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id = student.class_id ');
					$this->db->where('student.new_exam_form','Y');
					if($courseType!="ALL")
						$this->db->where('student.university_mode',$courseType);
					$this->db->where('new_exam_form.course_group_id',$course_group_id);
					$this->db->where('new_exam_form.class_id',$class['id']);
					$this->db->where('new_exam_form.paper_type',"theory");
					$this->db->where('new_exam_form.int_marks !=', "N");
					$internal = $this->db->get()->result();
					$internalVar = $internal[0]->num;
					$internalcountVar =$internalcount[0]->num;;
				}

				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				if($courseType!="ALL")
					$this->db->where('student.university_mode',$courseType);
				$this->db->where('new_exam_form.course_group_id',$course_group_id);
				$this->db->where('new_exam_form.class_id',$class['id']);
				$this->db->where('new_exam_form.paper_type!=',"theory");

				$practicalTotal = $this->db->get()->result();
				$this->db->select('count(*) as num');
				$this->db->from('new_exam_form');
				$this->db->join('student', 'new_exam_form.student_id = student.student_id');
				$this->db->where('student.new_exam_form','Y');
				if($courseType!="ALL")
					$this->db->where('student.university_mode',$courseType);
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
				//$classArr['internal'] = $internal[0]->num;
				$classArr['internal'] = $internalVar;
				$classArr['internalcount'] = $internalcountVar;
				$classArr['practicalTotal'] = $practicalTotal[0]->num;
				$classArr['practical'] = $practical[0]->num;
				$data['class'][]=$classArr;
				$data['course_group_id']=$course_group_id;

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

	public function center_wise_remains_backlog_count(){

		$title = array('title' => 'Center Wise Backlog Student Remaining Form List');
		$this->load->view('header',$title);	
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('exam_form' =>'N','exam_year'=>'June 2024');
		$this->db->select('COUNT(*) as student_count,center_code,
			center_id');
		$this->db->group_by('center_id');
		$data['listing'] = $this->Common_model->getRecordByWhere('backlog_student',$where);
		$this->load->view('admin/center_wise_backlog_student_form_count_list',$data); 
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
				$papers = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id' =>$student->student_id, 'paper_type' => 'theory','class_id'=>$student->class_id));
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
        if($Fess_head == "Backlog Exam Fees"){
            $this->db->order_by("id",'desc');
            $student_details =  $this->Common_model->getRecordByWhere('backlog_student',$where);
        }else{
            $student_details =  $this->Common_model->getRecordByWhere('student',$where);
        }
		
		$course_details =  $this->Common_model->getRecordByWhere('course',array('course_group_id'=>$student_details[0]->course_group_id,'session'=>$student_details[0]->session));
		$center_id = $student_details[0]->center_id;
		$course_group_id = $student_details[0]->course_group_id;
		$remark = '';
		if($Fess_head == 'Admission Fees'){
	     $session = $student_details[0]->session;
		}else{
			$session = 'June 2024';
		}	
		$class_id = $student_details[0]->class_id;
		$name = $this->Common_model->getStudentNameById($student_details[0]->student_id);
        if($Fess_head == "Backlog Exam Fees"){
            $failCount = $this->Common_model->getCountByWhere('backlog_exam_form',array('student_id'=>$student_details[0]->student_id,'class_id'=>$student_details[0]->class_id,'paper_type'=>'Theory' ,'status'=>'B','backlog_student_id'=>$student_details[0]->id));
            // $this->Common_model->last_query();
            if( $failCount < 8){
                $exam_fees =$failCount * 100;
            }else{
                $exam_fees = 750; 
            }

        }elseif($student_details[0]->university_mode=='REG'){
            if($Fess_head!=''){
                if($student_details[0]->demo == 'Y'){
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->exam_fees : $course_details[0]->form_fees+$course_details[0]->admission_fees;
                }else{
                    
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->exam_fees+$course_details[0]->program_fees : $course_details[0]->form_fees+$course_details[0]->admission_fees;
                }
                
            } 
	    }else{
           if($Fess_head!=''){
                if($student_details[0]->demo == 'Y'){
                $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->p_exam_fees : $course_details[0]->p_form_fees+$course_details[0]->p_admission_fees;
                }else{
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->p_exam_fees+$course_details[0]->p_program_fees : $course_details[0]->p_form_fees+$course_details[0]->p_admission_fees;
                }
		    } 
	    }
		$dateTime = explode(' ',$dateTime);
        $admission_type = ($Fess_head == "Backlog Exam Fees")?$student_details[0]->mode:$student_details[0]->university_mode;
        $admission_mode = ($admission_type =="REG")?'Regular':'Private';
		$updateData = array('txnId' => $txnid,'fees_head'=>$Fess_head,'payment_date' => $dateTime[0],'payment_time' => $dateTime[1],'payment' => 'Y', 'payment_status' => 'success','student_id'=>$student_id
			,'center_id'=>$center_id,'course_group_id'=>$course_group_id,'class_id'=>$class_id,'remark'=>$remark,'student_name'=>$name,'exam_session'=>$session,
			'admission_type'=>$admission_mode,'amount'=>
			$exam_fees
		);

		$transaction = $this->Common_model->insertAll('online_payment_transaction',$updateData);
		$where1 = array('student_id' => $student_id);
		if($Fess_head=='Admission Fees'){	
			$result = $this->Common_model->updateRecordByConditions('student',$where1,array('payment_status'=> 'Y'));
		}elseif($Fess_head=='Exam Fees'){
			$result = $this->Common_model->updateRecordByConditions('student',$where1,array('new_exam_form'=> 'Y'));
		}elseif($Fess_head == "Backlog Exam Fees"){
            $this->db->where('id', $student_details[0]->id);
            $result = $this->Common_model->updateRecordByConditions('backlog_student',$where1,array('exam_form'=> 'Y'));
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
	

	public function student_notification_list($mode = "",$exam_pattern="M",$course_id="",$class_id="",$startlimit=0,$pagenumber=0){
		$this->load->model('Gradesheet_tr_model');
        if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}
		
		$course_id = $this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$this->db->order_by('roll_number','ASC');
		$data['mode']= $mode;
		//$this->db->where_in('student_id',array(379146,386106,684818,698913,698935,699440,699688,701898,701899,703694,703888,703975,704028,704055,704305,704409,704469,704783,705021,706439,706847,707041,707337,708030,708197,708273,708298,708485,708918,709142,709236,709265,709365,709953,710434,711824,711864,711971,712142,712149,712334,712568,712742,713081,713086,713178,713241,713315,713513,714111,714126,714131,714588,714715,715096,715361,715750,715807,715826,715833,716010,716336,716338,716340,716515,718064,718991,719124,719153,719358,719361,719601,719952,720778,720794,720900,721070,721977,722129,722265,722285,722615,722616,722642,722644,722711,723053,723529,723536,723716,723718,724366));
		//$this->db->limit(1000);
		//$this->db->where('roll_number','210410110');
		$start=0;
		if($startlimit==!0){
			$start=($startlimit-1)*10000;
			$this->db->limit(10000,$start);
			$pagetitle=$startlimit;
		}
		//$this->db->where_in('roll_number', array(210710017,210712137));
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y' ,'roll_number!='=>'0', 'university_mode'=>$mode,'exam_pattern'=>$pattern));//'result_show'=>'Y','old_result_show'=>'Y',
         // $this->Common_model->last_query();
		 $data['pagenumber']=$pagenumber;
		 $data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		// $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281);
		if((in_array($class_id, $class_cbcs)) && $pattern=="GRADE"){
			$this->load->model('Gradesheet_tr_model_pg');
			$this->load->view('admin/student_notification_list_pg',$data);
		}else{
			$this->load->view('admin/student_notification_list',$data);	
		}
		
	}

	public function marksheet_variable(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$admin_id = $this->session->admin_id;
			$data = array();
			$where = array('status'=>'Y');
			//$this->db->where($where);
			// $course_detail = $this->Common_model->getRecordByOrder('marksheet_variables',"notification_no,result_date","ASC");
           $this->db->order_by("notification_no,result_date", "asc");

			$course_detail = $this->Common_model->get_record('marksheet_variables','*',$where);
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

	public function generate_tr($mode="",$exam_pattern="M",$course_group_id="",$class_id="",$startlimit=0,$pagenumber=0){
		$start=0;
		if($startlimit==!0){
			$start=($startlimit-1)*1000;
			$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}
	
		if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}
		$where =array("course_group_id"=>$course_group_id ,'old_class_id' => $class_id ,'exam_form'=>'Y', 'roll_number!='=>'0' ,'university_mode'=> $mode ,'exam_pattern'=>$pattern);
		//,'examcentercode'=>'MDE165'
		//,'student_id'=>702823
		$this->db->order_by('center_id','ASC');
		$this->db->order_by('roll_number','ASC');

		//$this->db->where_in('roll_number',array(210412125,210413275,210417990,210412133,210420009,210420534));
		//$this->db->where_in('roll_number',array(210414167,210414296,210416271,210416325,210417759,210420469,210412133,210420009,210420534,210412125,210413275,210417990));
		//$this->db->where_in('roll_number',array(210710091,210710263,210710295,210710297,210710410,210710460,210710501,210710533,210710572,210710581,210710691,210710766,210711020,210711062,210711065,210711095,210711203,210711380,210711392,210711422,210711776,210711822,210711834,210711851,210712018,210712061,210712130,210712195,210712245,210712285,210712379,210712429,210712545,210712587));
		// $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',$where);
		// $this->db->where_in('roll_number',array(210410083,210410113,210410114,210410116,210410213,210410218,210410371,210410482,210410523,210410537,210410599,210410719,210410730,210410732,210410841,210410981,210411117,210411118,210411119,210411149,210411295,210411375,210411435,210411437,210411614,210411667,210411678,210411679,210411756,210411794,210411843,210412033,210412148,210412551,210412646,210413194,210413373,210413381,210413507,210413525,210413526,210413528,210413645,210413650,210413722,210413904,210413958,210414060,210414157,210414162,210414169,210414178,210414179,210414184,210414190,210414200,210414203,210414299,210414306,210414335,210414363,210414371,210414381,210414571,210414679,210414743,210414777,210414823,210414861,210415012,210415105,210415134,210415242,210415307,210415383,210415385,210415400,210415410,210415569,210415580,210415597,210415665,210415777,210415856,210416241,210416277,210416299,210416551,210416728,210416767,210416825,210416831,210416845,210416866,210416907,210416913,210416965,210416986,210416993,210417006,210417008,210417010,210417028,210417033,210417247,210417350,210417612,210417841,210417945,210417951,210417957,210417964,210417965,210417969,210418073,210418111,210418183,210418298,210418411,210418427,210418441,210418445,210418470,210418513,210418620,210418722,210418765,210418964,210419025,210419204,210419206,210419213,210419226,210419227,210419442,210419510,210420144,210420197,210420302,210420379,210420388,210420433,210420482,210420500,210420619,210420690,210420706,210420730,210420753,210420764,210420829,210420836,210420886,210420941,210420985,210421042,210421230,210421246,210421286,210421302,210421305,210421384,210421469,210421505,210421537,210421696,210421701,210421737,210421753,210421776,210421844,210422058,210422065,210422333,210422380,210422480,210422607,210422672,210422724,210423014,210423145,210423171,210423185,210423226,210423311,210423465,210423590,210423681,210423806,210423917,210423936,210423984,210424021,210424077,210424083,210424109,210424392,210424499,210424611,210424759,210424784,210424881,210425039,210425144,210425264,210425496,210425592,210425653,210425757,210425784,210426060,210426100,210426351,210426368,210414167,210414296,210416271,210416325,210417759,210420469,210412133,210420009,210420534,210412125,210413275,210417990,210410075,210410146,210410161,210410162,210410176,210410255,210410280,210410294,210410322,210410332,210410498,210410508,210410531,210410583,210410741,210410752,210410914,210410925,210411014,210411032,210411044,210411101,210411103,210411121,210411137,210411214,210411424,210411450,210411453,210411454,210411459,210411460,210411544,210411606,210411627,210411666,210411690,210411746,210412009,210412014,210412017,210412019,210412020,210412028,210412035,210412038,210412039,210412043,210412053,210412056,210412137,210412142,210412156,210412247,210412258,210412259,210412294,210412320,210412340,210412369,210412384,210412388,210412389,210412390,210412400,210412403,210412450,210412464,210412684,210412748,210412778,210412780,210412918,210413118,210413122,210413219,210413267,210413379,210413419,210413542,210413614,210413621,210413642,210413729,210413752,210413778,210413894,210413906,210413923,210413928,210414053,210414069,210414099,210414147,210414150,210414183,210414206,210414212,210414223,210414234,210414289,210414293,210414463,210414537,210414539,210414545,210414572,210414674,210414734,210414810,210414858,210414873,210414875,210414877,210414881,210414937,210414944,210415002,210415030,210415032,210415245,210415268,210415326,210415333,210415415,210415417,210415481,210415506,210415539,210415695,210415759,210415795,210415861,210415896,210415905,210416249,210416250,210416266,210416274,210416279,210416287,210416319,210416326,210416338,210416340,210416346,210416471,210416496,210416504,210416545,210416622,210416716,210416805,210416853,210416861,210416865,210416870,210416892,210416947,210416967,210416979,210416980,210417048,210417053,210417175,210417446,210417535,210417541,210417607,210417613,210417655,210417704,210417729,210417766,210417813,210417876,210417890,210417997,210418152,210418160,210418203,210418274,210418399,210418455,210418473,210418505,210418539,210418565,210418584,210418593,210418907,210419041,210419201,210419225,210419312,210419412,210419445,210419456,210419536,210419594,210419837,210419849,210419877,210419947,210419952,210419954,210419955,210419963,210420131,210420137,210420139,210420155,210420216,210420304,210420310,210420491,210420579,210420594,210420648,210420650,210420810,210420840,210420880,210420901,210420908,210421012,210421031,210421114,210421166,210421172,210421204,210421224,210421371,210421378,210421395,210421455,210421483,210421567,210421691,210421705,210421774,210421804,210421863,210421864,210421865,210421867,210421882,210421883,210421892,210422171,210422244,210422246,210422316,210422320,210422350,210422393,210422527,210422643,210422647,210422648,210422693,210422713,210422739,210422823,210422952,210422976,210422977,210423009,210423076,210423100,210423112,210423288,210423290,210423375,210423433,210423479,210423504,210423574,210423575,210423577,210423578,210423669,210423695,210423743,210423753,210423754,210423855,210423943,210423950,210423979,210423985,210424010,210424190,210424221,210424226,210424264,210424370,210424517,210424519,210424520,210424566,210424572,210424605,210424606,210424607,210424634,210424637,210424644,210424663,210424756,210424770,210424777,210424787,210424790,210424792,210424793,210424874,210424876,210424882,210424939,210425050,210425065,210425181,210425246,210425357,210425368,210425404,210425453,210425534,210425556,210425602,210425650,210425659,210425693,210425749,210425750,210425817,210425888,210425979,210426014,210426016,210426022,210426027,210426065,210426071,210426215,210426294,210426355,210426362,210426366,210426429,210426430,210426470,210426560));

		$data['students'] = $this->Common_model->getRecordByWhere('student',$where);
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		$data['pagenumber']=$pagenumber;
		$data['course_group_id'] = $course_group_id;
        $data['date_mode'] =$mode;
		$title = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] .= $title;//echo $this->db->last_query(); die;
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
		// $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302);
		if((in_array($class_id, $class_ids))  && $pattern!="MARKS")	//&& $mode=='REG'
		{
			$this->load->model('Gradesheet_tr_model');
			$this->load->view('admin/generate_gradesheet_tr',$data);
		}else if((in_array($class_id, $class_cbcs)) && $mode=='REG' && $pattern!="MARKS"){
			$this->load->model('Gradesheet_tr_model_pg');
			$this->load->view('admin/generate_gradesheet_tr_pg',$data);
		}
		else if ($class_id!=168) {
			$this->load->view('admin/generate_tr',$data);
		}else{
			$this->load->view('admin/generate_tr_mom',$data);
		}

	}

	public function backlog_generate_tr($mode="",$course_group_id="",$class_id="",$startlimit=0,$pagenumber=0){
		$start=0;
		if($startlimit==!0){
			$start=($startlimit-1)*1000;
			$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}
		
		$where =array("course_group_id"=>$course_group_id ,'class_id' => $class_id ,'exam_form'=>'Y', 'roll_no!='=>'0' ,'mode'=> $mode,'exam_year'=>'Dec 2023');
		//,'student_id'=>702823
		$this->db->order_by('center_id','ASC');
		$this->db->order_by('roll_no','ASC');
		
		// $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',$where);
		
		$data['students'] = $this->Common_model->getRecordByWhere('backlog_student',$where);
		// print_r($data['students']);die;
		//  $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		$data['pagenumber']=$pagenumber;
		$data['course_group_id'] = $course_group_id;
		$title = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] .= $title;//echo $this->db->last_query(); die;
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
		if((in_array($class_id, $class_ids)) && $mode=='REG')	
		{
			$this->load->model('Gradesheet_backlog_tr_model');
			$this->load->view('admin/generate_backlog_gradesheet_tr',$data);
		}else if((in_array($class_id, $class_cbcs)) && $mode=='REG'){
			$this->load->model('Gradesheet_backlog_tr_model_pg');
			$this->load->view('admin/generate_backlog_gradesheet_tr_pg',$data);
		}
		else if ($class_id!=168) {
			$this->load->view('admin/backlog_generate_tr',$data);
		}else{
			$this->load->view('admin/backlog_tr_mom',$data);
		}

	}

    public function backlog_generate_tr_bed($mode="",$course_group_id="",$class_id=""){
		// $this->db->order_by('center_id,roll_number','ASC');
		
		// $data['students'] = $this->Common_model->getRecordByWhere('student',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'class_id' => $class_id ,'new_exam_form'=>'Y','roll_no!='=>'0' ));

		// // $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'old_class_id' => $class_id ,'exam_form'=>'Y','roll_number!='=>'0' ));
		// //'result_show' => 'N' ,'student_id'=>'685381'
        $where =array("course_group_id"=>$course_group_id ,'class_id' => $class_id ,'exam_form'=>'Y', 'roll_no!='=>'0' ,'mode'=> $mode,'exam_year'=>'Dec 2023');
		//,'student_id'=>702823
		$this->db->order_by('center_id','ASC');
		$this->db->order_by('roll_no','ASC');
		
		// $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',$where);
		
		$data['students'] = $this->Common_model->getRecordByWhere('backlog_student',$where);
		$data['class_id'] = $class_id;
		$data['course_group_id'] = $course_group_id;
		$data['title'] = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		// $this->load->view('admin/generate_tr/header2',array('title' =>$title));

		// if($class_id == '110' || $class_id == '119' || $class_id == '131')
		$class_ids=array(110,119,125,128,131,111,126,129,132);
		if(in_array($class_id, $class_ids))		
		{
			$this->load->model('Gradesheet_backlog_tr_model');
			$this->load->view('admin/generate_tr/backlog_practical_internal_tr',$data);
		}else{
			$this->load->view('admin/generate_tr/backlog_bed_tr',$data);
		}
		
		// $this->load->view('admin/generate_tr/footer2');
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
		
		$where = "id in (select distinct(course_group_id) from student where exam_form = 'Y'  )  ";
        // and old_class_id in (102,105,108,111,117,120,126,129,132,135,194,198,202,204,206,212,214,222,224,226,228,276,280,303)
		
		// and old_result_show='Y'
		//187,134,135,159,178,137,138,140,143,146,149,169,170
		//and old_class_id in (155,182,299,218,230,232,234,236,238,240,242,244,246,216,248,250,252,254,172,154,181,196,200,208,210,162,165,173,174,177,180,300,258,256,268)
		// new_exam_form = 'Y' or student_result_aug_22
				
		$data['courses'] = $this->Common_model->get_record('course_group','*',$where);
		$this->load->view('header',array('title' => 'Class List'));
		$this->load->view('admin/tr_class_list',$data);
		$this->load->view('footer');
	}

	public function backlog_tr_class_list(){
		$where = "id in (select distinct(course_group_id) from backlog_student where exam_form = 'Y' and exam_year='Dec 2023'  )";
		// new_exam_form = 'Y' or student_result_aug_22
		// and class_id not in (154,155,171,182,204) 
		
		$data['courses'] = $this->Common_model->get_record('course_group','*',$where);
		$this->load->view('header',array('title' => 'Backlog Class List'));
		$this->load->view('admin/backlog_tr_class_list',$data);
		$this->load->view('footer');
	}

	public function student_result_permission($mode="",$course_id="",$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		$data['not_permited_students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id , 'old_result_show'=>'N' , 'exam_form'=>'Y' ,'university_mode'=>$mode ));
		// ,'examcentercode'=>'MDE165'
		$data['permited_students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id , 'old_result_show'=>'Y' , 'exam_form'=>'Y' ,'university_mode'=>$mode));
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$data['mode']=$mode;
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
			$data = array('old_result_show' => 'Y');
			$where = " student_id in (".$student_ids.")";//provisional_remark in ('','N') &&
			$update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('old_result_show' => 'N');
			$where ='student_id in ('.$student_ids.')';
			$update = 	$this->Common_model->updateRecordByConditions('student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/student_result_permission/'.$_POST['mode'].'/'.$_POST['course_group_id'].'/'.$_POST['class_id']);
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
			$data = array('old_result_show' => 'Y');
			$where = 'student_id in ('.$student_ids.')';
			$update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('old_result_show' => 'N');
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
		$this->db->select('count(*) as cnt ,student.name,student.roll_number,student.course_name, student.class_name , student.center_code,student.course_group_id,student.old_class_id,student.student_id');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'new_exam_form.student_id = student.student_id');
		$this->db->where('student.exam_form','Y'); 
		$this->db->where('student.old_result_show','Y'); 
		$this->db->where('new_exam_form.paper_type','theory'); 
		$this->db->where('new_exam_form.theory_marks',''); 
		$this->db->where('student.course_group_id',$course_id); 
		$this->db->where('student.old_class_id',$class_id); 
		$this->db->where('new_exam_form.class_id',$class_id); 
		$this->db->group_by('new_exam_form.student_id');
		
		$data['students'] = $this->db->get()->result();
		
		//$this->db->last_query(); die;
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();

		$this->load->view('header',array('title' => 'List of students'));
		$this->load->view('admin/withheld_student_list',$data);
		$this->load->view('footer');
	}

	public function student_marksheet($mode="",$course_id="",$class_id="",$startlimit=0)
	{
		$data = array('class_id' => $class_id,'course_group_id' =>$course_id );
				$start=0;
				
		// 'enrollment_no'=>'AG/21220737'
		// ,'enrollment_no'=>'AG/21200364'
		$title = "Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
		$class = $this->Common_model->getRecordByID('class_master','id',$class_id);

		if($startlimit!=0){
			$start=($startlimit-1)*1000;
			$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}	
		//$this->db->limit(1);
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] = $title;
		$data['university_mode'] = $mode;
 
		if($class->last_class == 'L'){
			$this->db->order_by('center_id,roll_number','ASC');
			// $this->db->where_in('student_id',array(188247,188249,188265,188272,188302,188306,188311,188322,188324,188338,188343,188346,188353,188361,188366,188368,188455,188495));
            // $this->db->where('student_id',702679);
			$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y','roll_number!='=>'0','course_complete'=>'Y','university_mode'=>$mode,'old_result_show'=>'Y' ,'exam_pattern'=>'MARKS'));
			
		}else{
			$this->db->order_by('center_id,roll_number','ASC');
            // $this->db->limit(1);
            //  $this->db->where_in('student_id', array(687872,689923,697856,698112,695552,688384,688896,723712,745473,734208,731648,733440,725504,720896));
           $data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y','roll_number!='=>'0','university_mode'=>$mode,'old_result_show'=>'Y','exam_pattern'=>'MARKS'));
		}
	 	if($class->internal=="Y" && $mode!="PVT"){
			$this->load->view('admin/student_marksheet',$data);
		}else{
			$this->load->view('admin/student_marksheet_certificate',$data);
		}
	}

	public function student_marksheet_grade($mode="",$course_id="",$class_id="",$startlimit=0)
	{
		$data = array('class_id' => $class_id,'course_group_id' =>$course_id );
				$start=0;
				
		// 'enrollment_no'=>'AG/21220737'
		// ,'enrollment_no'=>'AG/21200364'
		$title = "Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
		$class = $this->Common_model->getRecordByID('class_master','id',$class_id);

		if($startlimit!=0){
			$start=($startlimit-1)*1000;
			$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}	
	//	$this->db->where_in('roll_number',array(210414167,210414296,210416271,210416325,210417759,210420469,210412133,210420009,210420534,210412125,210413275,210417990));
		//$this->db->where_in('roll_number',array(210412125,210413275,210417990,210412133,210420009,210420534));
		//$this->db->limit(1);
		
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] = $title;
		$data['university_mode'] = $mode;
		// $this->load->model('Gradesheet_model');
		

		if($class->last_class == 'L'){
			$this->db->order_by('center_id,roll_number','ASC');
			$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y','roll_number!='=>'0','course_complete'=>'Y','university_mode'=>$mode,'old_result_show'=>'Y','exam_pattern'=>'GRADE' ));
		}else{
			$this->db->order_by('center_id,roll_number','ASC');
			// $this->db->limit(1);
			//  $this->db->where('student_id = "384259"');
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y','roll_number!='=>'0','university_mode'=>$mode,'old_result_show'=>'Y','exam_pattern'=>'GRADE'));
		}
	 	// if($class->internal=="Y" && $mode!="PVT"){
			$class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
			if(in_array($class_id , $class_cbcs))
			{
                $this->load->model('GradeSheet_old_model_pg');
                $this->load->model('Gradesheet_model_pg');
                $this->load->view('admin/student_marksheet_grade_pg',$data);
			}else{
				$this->load->model('Gradesheet_model');
				$this->load->view('admin/student_marksheet_grade1',$data);
			}
			

		// }else{
			// $this->load->view('admin/student_marksheet_certificate',$data);
		// }
	}

    public function backlog_student_marksheet_grade($mode="",$course_id="",$class_id="",$startlimit=0)
	{
		$data = array('class_id' => $class_id,'course_group_id' =>$course_id );
				$start=0;
				
		// 'enrollment_no'=>'AG/21220737'
		// ,'enrollment_no'=>'AG/21200364'
		$title = "Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
		$class = $this->Common_model->getRecordByID('class_master','id',$class_id);

		if($startlimit!=0){
			$start=($startlimit-1)*1000;
			$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}	
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] = $title;
		$data['university_mode'] = $mode;
		// $this->load->model('Gradesheet_model');
		

		if($class->last_class == 'L'){
			$this->db->order_by('bs.center_id,bs.roll_no','ASC');
			$this->db->select('bs.*, st.photo,st.session,st.course_name');
            $this->db->from('backlog_student as bs');
            $this->db->join('student as st','st.student_id=bs.student_id');
            $this->db->where(array("bs.course_group_id"=>$course_id ,'bs.class_id' => $class_id,'bs.exam_form'=>'Y','bs.roll_no!='=>'0','mode'=>$mode,'st.course_complete'=>'Y' ));
            $data['students']=  $this->db->get()->result();
            // $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id,'exam_form'=>'Y','roll_no!='=>'0','mode'=>$mode ));
		}else{
			$this->db->order_by('bs.center_id,bs.roll_no','ASC');
            $this->db->select('bs.*, st.photo,st.session,st.course_name,st.name,st.f_h_name');
            $this->db->from('backlog_student as bs');
            $this->db->join('student as st','st.student_id=bs.student_id');
            $this->db->where(array("bs.course_group_id"=>$course_id ,'bs.class_id' => $class_id,'bs.exam_form'=>'Y','bs.roll_no!='=>'0','mode'=>$mode,'bs.result_show'=>'Y' ));
            $data['students']=  $this->db->get()->result();
			// $this->db->limit(1);
			//  $this->db->where('student_id = "721275"');
		// $data['students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id,'exam_form'=>'Y','roll_no!='=>'0','mode'=>$mode));
		}
	 	// if($class->internal=="Y" && $mode!="PVT"){
			$class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
			if(in_array($class_id , $class_cbcs))
			{
                $this->load->model('GradeSheet_old_model_pg');
                $this->load->model('Gradesheet_model_pg');
                $this->load->view('admin/student_marksheet_grade_pg',$data);
			}else{
				$this->load->model('Gradesheet_backlog_model');
				$this->load->view('admin/backlog_student_marksheet_grade',$data);
			}
			

		// }else{
			// $this->load->view('admin/student_marksheet_certificate',$data);
		// }
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
		$this->db->order_by("int_marks_sub,student.course_group_id,student.old_class_id", "asc");
		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('new_exam_form', 'student.student_id = new_exam_form.student_id and student.old_class_id=new_exam_form.class_id');
		$this->db->join('class_master', 'student.old_class_id = class_master.id');
		$this->db->group_by('new_exam_form.student_id');
		$this->db->Where('exam_form','Y');
		$this->db->where('int_marks_sub','N');
		$this->db->Where('paper_type','theory');
		$this->db->where('university_mode','REG');
		$this->db->where('class_master.internal','Y');
		
        	//$this->db->Where('result_show','Y');
		$this->db->where_in('new_exam_form.int_marks',array('ABS','N'));
		//$this->db->where_in('student.old_class_id',array(195,197,199,201,203,205,209,211,213,215,217,221,223,227,229,231,237,241,245,247,249,251,253,275,279));
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$data['students'] = $this->db->get()->result();//echo $this->db->last_query(); die;
		$this->load->view('admin/student_int_marks_no_list',$data);
		$this->load->view('footer');
	}

	public function student_int_assignment_marks(){ 
		$student_id = $this->input->post('student_id');
		$class_id = $this->input->post('old_class_id');
		
		$classData	= $this->Common_model->getRecordById('class_master','id',$class_id);
		
		$this->db->select('*');
		$this->db->from('new_exam_form');
		
		$this->db->where('student.student_id',$student_id);
		$this->db->where('new_exam_form.class_id',$class_id);
		if($classData->practical_internal_marks=="N")
			// $this->db->where('paper_type','theory');
			$this->db->where_in('paper_type',array('Sessional','theory'));
		
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->order_by('new_exam_form.sub_group_id,paper_order');
		
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
		$class_id = $this->input->post('old_class_id');
		$classData	= $this->Common_model->getRecordById('class_master','id',$class_id);
		$where=array('student.student_id'=>$student_id,'paper_master.sub_group_id !='=>1);
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		if($classData->internal == "N"){
			$this->db->where('paper_master.type !=','theory'); }
		
		// $this->db->where("new_exam_form.class_id = ".$class_id."");
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->join('paper_master','student.old_class_id= paper_master.class_id and paper_master.paper_code = new_exam_form.paper_code');
		$this->db->order_by('new_exam_form.sub_group_id,paper_order,paper_no');
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
		$this->db->order_by("p_marks_sub,student.course_group_id,student.old_class_id", "asc");
		$this->db->select('*');
		$this->db->from('student');
		$this->db->join('new_exam_form', 'student.student_id = new_exam_form.student_id and student.old_class_id=new_exam_form.class_id');
		$this->db->join('class_master', 'student.old_class_id = class_master.id');
		$this->db->group_by('new_exam_form.student_id');
		$where = array('paper_type!='=>'theory','exam_form'=>'Y','p_marks_sub'=>'N');
		$this->db->Where($where);
		$this->db->where_in('new_exam_form.p_marks',array('ABS','N'));
		$this->db->where('university_mode','REG');
		//$this->db->where('student.class_id','168');
	    //$this->db->where('`student.old_class_id` in (101,104,107,110,116,119,125,128,131,134,154,155,159,162,164,165,168,169,170,171,172,173,174,175,178,180,181,182,196,200,206,208,224,226,228,256,262,273,274,299)');
	  //  $this->db->where_in('student.old_class_id',array(195,197,199,201,203,205,209,211,213,215,217,221,223,227,229,231,237,241,245,247,249,251,253,275,279));
		$this->db->Where('(project="Y" or practical = "Y")');
		$this->db->where_not_in('center_id',array(20,21,22,23,24,25,26,27,28,29));
		$data['students'] = $this->db->get()->result();
		 // $this->Common_model->last_query();
		$this->load->view('admin/student_practical_marks_no_list',$data);
		$this->load->view('footer');
	}


	public function student_practical_assignment_marks (){
		$student_id = $this->input->post('student_id');
		$class_id = $this->input->post('old_class_id');
 		$where=array('student.student_id'=>$student_id,
			'paper_type!='=>'theory', );
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->Where($where );
		$this->db->where("new_exam_form.class_id = ".$class_id."");
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->join('paper_master', 'paper_master.id = new_exam_form.paper_id');
		$this->db->where_not_in('paper_type',array('Sessional','theory'));
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

	public function generate_tr_bed($mode="",$exam_pattern="M",$course_group_id="",$class_id=""){
		
		if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}

		$this->db->order_by('center_id,roll_number','ASC');
		
		// $data['students'] = $this->Common_model->getRecordByWhere('student',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'class_id' => $class_id ,'new_exam_form'=>'Y','roll_no!='=>'0' ,'exam_pattern'=> $pattern));

		 $data['students'] = $this->Common_model->getRecordByWhere('student',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'old_class_id' => $class_id ,'exam_form'=>'Y','roll_number!='=>'0' ,'exam_pattern'=> $pattern));

		//'result_show' => 'N' ,'student_id'=>'685381'
		$data['class_id'] = $class_id;
		$data['course_group_id'] = $course_group_id;
        $data['date_mode'] = $mode;
		$data['title'] = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		// $this->load->view('admin/generate_tr/header2',array('title' =>$title));

		// if($class_id == '110' || $class_id == '119' || $class_id == '131')
		$class_ids=array(110,119,125,128,131,111,126,129,132);
		if(in_array($class_id, $class_ids))		
		{
			$this->load->model('Gradesheet_tr_model');
			$this->load->view('admin/generate_tr/practical_internal_tr',$data);
		}else{
			$this->load->view('admin/generate_tr/bed_tr',$data);
		}
		
		// $this->load->view('admin/generate_tr/footer2');
	}

	public function student_notification_list_bed($mode="",$exam_pattern="M",$course_id="",$class_id=""){
		// echo $mode;die;
		$this->load->model('Gradesheet_tr_model');
        if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}
		$course_id=$this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$this->db->order_by('roll_no','ASC');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id, 'class_id' => $class_id, 'new_exam_form'=>'Y','roll_no!='=>'0','university_mode'=>$mode ,'exam_pattern'=>$pattern));
		$data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$data['mode'] = $mode;
        // $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281);
        
        if((in_array($class_id, $class_cbcs)) && $pattern=="GRADE"){
			$this->load->model('Gradesheet_tr_model_pg');
			$this->load->view('admin/student_notification_list_pg',$data);
		}else{
		    $this->load->view('admin/student_notification_list_bed',$data);
        }
	}

    public function backlog_student_notification_list_bed($mode="",$course_id="",$class_id=""){
		// echo $mode;die;
		$this->load->model('Gradesheet_backlog_tr_model');
		$course_id=$this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$this->db->order_by('roll_no','ASC');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$data['students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id, 'class_id' => $class_id, 'exam_form'=>'Y','roll_no!='=>'0','mode'=>$mode,'exam_year'=>"Dec 2023" ));
		$data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$data['mode'] = $mode;
		$this->load->view('admin/backlog_student_notification_list_bed',$data);
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

		//$course = $this->Common_model->getRecordByWhere('course_group',array("id"=>$student[0]->course_group_id));
		
		$this->db->select('course.*,course_group.private_mode,course_group.mode');
		$this->db->from('course');
		$this->db->join('course_group', 'course.course_group_id = course_group.id');
		$where = array('session'=>$student[0]->session,'course.course_group_id'=>$student[0]->course_group_id);
		$this->db->where($where);	
		$course = $this->db->get()->result();
		
		if ($student[0]->university_mode=='REG') {
			if ($course[0]->admission_permission_private!='Y') {
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
			if ($course[0]->admission_permission_regular!='Y') {
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
			$dde_student['session'] = 'July 2022';
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
		$this->db->order_by('id', 'Asc');
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
				$count_filter='class_id';//array("course_group_id", "class_id"); //
				
				$data['approved']=$approved 		  = $this->input->post("approved");
				$data['payment']= $payment 		  = $this->input->post("payment");
				
				$data['document_upload']=$document_upload  = $this->input->post("document_upload");
				$filter  		  = $this->input->post("filter");
				$data['session']  =$session 		  = $this->input->post("session");

				if($session){
					$sessionRow = $this->db->get_where('session', array('id'=>$session))->result_array();
					$this->db->where('session', $sessionRow[0]['session'] );
					 
					
				}
				
				$this->db->select('count(*) as cnt,course_name,class_name,class_id,course_group_id');
				$this->db->from('student');
				
				if($payment && $payment!="all"){
					$this->db->where('payment_status',$payment);
				}
				if($document_upload && $document_upload!="all"){
					$this->db->where('document_uploaded ',$document_upload);
				}
				
					$this->db->where('approved',$approved);
					//$this->db->where('university_mode',"PVT");
					$this->db->group_by('class_id');
				
				
				$data['course_count'] = $this->db->get()->result_array();
				
				//echo $this->db->last_query();
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
	
			$count_filter='class_id';
			$data = array();
			$dt   = array();
			$course_group_id  = $this->uri->segment(5);
			$class_id  		  = $this->uri->segment(6);
			$approved 		  = $this->uri->segment(7);
			if(empty($approved)){$approved="";}
			$data['approved']=$approved;
			$payment 		  = $this->uri->segment(3);
			$document_upload  = $this->uri->segment(4);
			$filter  		  = "list";
			$session 		  = $this->uri->segment(2);
			
			if($session){
				$sessionRow = $this->db->get_where('session', array('id'=>$session))->result_array();
				$this->db->where('session', $sessionRow[0]['session']);

			}
			$this->db->select('*');
			$this->db->from('student');
			
			if($payment && $payment!="all"){
				$this->db->where('payment_status',$payment);
			}
			if($document_upload && $document_upload!="all"){
				$this->db->where('document_uploaded ',$document_upload);
			}
			
			if($course_group_id){
				$this->db->where('course_group_id',$course_group_id);
			}
			if($class_id){
				$this->db->where('class_id',$class_id);
			}
				$this->db->where('approved',$approved);
				//$this->db->group_by('class_id');
				//$this->db->where('university_mode',"PVT");
			
			$data['students'] =  $this->db->get()->result_array();
				
			
			//echo $this->db->last_query();
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
				$approved    = $this->input->post("approved");
				$students    = $this->input->post("students");
				$studentArr=explode(',',$students);
				$sessionRow = $this->db->get_where('session', array('id'=>$session))->result_array();
				$sessionValue = $sessionRow[0]['session'] ;
				
				foreach($studentArr as $k=>$val){
					$studentRow = $this->db->get_where('student', array('student_id'=>$val))->result_array();
					$path = 'assets/student_image/'.$sessionValue.'/'.$studentRow[0]['photo'];
					$prev_path = 'assets/student_image/'.$studentRow[0]['session'].'/'.$studentRow[0]['photo'];
					$upload = rename($prev_path,$path); 
					
					$data = $this->Common_model->updateRecordByConditions("student",array("student_id" => $val,'approved'=>$approved ),array("session" => $sessionValue ));
					
				}
			}
			redirect(base_url('check_student_for_session_change_report'));
		}	
	}
			
	public function paper_test_id_list()
	{	
		// $where = array('type' => 'theory');
		// $this->db->order_by('course_group_id,class_id,cbcs_paper,paper_no','ASC');
		// $papers = $this->Common_model->getRecordByWhere('paper_master',$where);
	
		
		$query = $this->db->query("SELECT p.* FROM `paper_master` as p join class_master as c on c.id=p.class_id WHERE cbcs_paper=cbcs and type='theory' and `class_id` in (195,197,199,201,203,205,209,211,213,221,223,225,227,275,279) and `exam_date`='0000-00-00' and type='Theory' and old_exam_date!='0000-00-00' order by p.course_group_id,class_id,cbcs_paper,paper_no asc");
		
			
		$papers =$query->result();
		
		$data = array('papers' => $papers);
		//echo $this->db->last_query().'<br>';
		$this->load->view('header');
		$this->load->view('admin/paper_test_id_list',$data);
		$this->load->view('footer');		
	}


   public function exam_center_login()
	{
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
				$data = array();
				$data['title'] = "Exam Center login";
				$this->load->view('header');
				$data['exam_centers'] = $this->Common_model->get_record('exam_center','*');
				$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
				$this->load->view('admin/exam_center_login',$data);
				$this->load->view('footer');
			
		}
	}

	public function exam_center_login_sub(){

		$exam_center_id = $this->input->post('id');
		$exam_center_data = $this->Common_model->getRecordByWhere('exam_center',array('id' => $exam_center_id));
		$exam_center_count = count($exam_center_data);
		if($exam_center_count>0){
			$data = array('loged_in' => true,
				'exam_center_id' => $exam_center_data[0]->id,
                'Examcenterdata' => $exam_center_data[0]->examcentercode,		
			);
			$this->session->set_userdata($data);
			$result = array("status" => "true");
		 }else{
			$result = array('error'=> "USERNAME INCORRECT");
		 }
		 echo json_encode($result);
	 }
	//Exam Center Wise Billing fetch
	public function exam_center_wise_billing(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Exam Center Wise Billing'); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('*');
			$this->db->from('exam_center');
			$this->db->order_by('examcentercode', "asc");
			$data['exam_centers'] = $this->db->get()->result();
			

			$this->load->view('admin/exam_center/exam_center_wise_billing',$data);
			$this->load->view('footer');
		}
	}	

	public function get_exam_center_wise_billing(){
		$exam_center = $this->input->post('exam_center');
		$this->db->select('*');
		$this->db->from('exam_center');
		if($exam_center!="All")
		$this->db->where('id',$exam_center);	
		$this->db->order_by('examcentercode', "asc");
		$data['exam_centers'] = $this->db->get()->result();
		$this->db->select('*');
			$this->db->from('paper_master');
			$this->db->where('exam_date!=',"");
			//$this->db->where_not_in('class_id',array("259","261","263"));
			$this->db->where('exam_date!=',"0000-00-00");	
			//$this->db->where('exam_date>=',"2023-07-31");	
			$this->db->group_by(array('exam_date','exam_shift'));
			$this->db->order_by('exam_date', "asc");
			$this->db->order_by('exam_shift', "desc");
			$data['examDate'] = $this->db->get()->result();
		//	echo $this->db->last_query(); die;
		echo $this->load->view('admin/exam_center/exam_center_wise_billing_show',$data, TRUE);
	}
	
	
	//Exam Center Wise Billing fetch
	public function exam_center_billing_report(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
		$data = array();
		$data['title'] = "Exam Center Billing January 2024";
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->db->order_by('examcentercode');
		$data['examCenters'] = $this->db->get_where('exam_center', array())->result_array();
		$this->load->view('header',$data);
		$this->load->view('admin/exam_center/exam_center_billing_report',$csrf);
		$this->load->view('footer');
		}
	}	
	
	public function show_paper($student_id){
    	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
    	$titleData = array('title' => 'Student Papers');
    	$this->load->view('header',$titleData);
    	$where = array(
    		'student_id' => $student_id,
    	);
    	$student = $this->Common_model->student_info($student_id);
    	$data['student'] = $student;
		$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
		$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
		
    	// print_r($data);
	 	// die;
    	$this->db->select('paper_master.*,new_exam_form.sub_group_id as sub_group');
    	$this->db->from('paper_master');
    	$this->db->order_by('new_exam_form.sub_group_id,paper_order,paper_no');
    	$this->db->join('new_exam_form', 'paper_master.paper_code = new_exam_form.paper_code and  paper_master.class_id = new_exam_form.class_id');
    	$where = array('paper_master.class_id' => $student['class_id'],
    		'student_id' => $student_id,'cbcs_paper'=>$cbcs
    	);
    	$this->db->where($where);
    	$data['papers'] = $this->db->get()->result();
    	//  $this->Common_model->last_query();

    	$this->load->view('admin/student/show_paper',$data);
    	$this->load->view('footer');
    }

	public function student_paper_delete()
	{
  		 $student_id = $this->input->post('student_id');
		  $classid = $this->input->post('classid'); 
		 $where=array("student_id"=>$student_id,"class_id"=> $classid);
		 $response = $this->Common_model->deleteByWhere('new_exam_form',$where);
		//$response = $this->Common_model->deleteById('new_exam_form','student_id',$student_id);
		echo json_encode(array("status" => 'true'));
		$where = array('student_id' => $student_id);
		$data = array('temp_exam_form' => 'N');
		
		$response= $this->Common_model->updateRecordByConditions('student',$where,$data );
		
		$this->session->set_flashdata('ajax_flash_message','Status Successfully Updated');
	}

	public function student_image_delete()
	{
  		 $student_id = $this->input->post('student_id');
		 $where=array("student_id"=>$student_id);
		 $studentData = $this->Common_model->get_record('student','*', $where);
	     $session=$studentData[0]['session'];
	     unlink('assets/student_image/'.$session.'/'.$studentData[0]['photo']);
		 echo json_encode(array("status" => 'true'));
		 $this->session->set_flashdata('ajax_flash_message','Image Deleted Successfully !');
	}

	public function regular_exam_controller($method,$admin_id)
	{
		$admin_id = $this->Common_model->encrypt_decrypt($admin_id,'decrypt');
		
		$check_user = $this->Common_model->getRecordById('admin_master','id',$admin_id);
				
				$data = array('loged_in' => true,
					'adminData' => $check_user->name,
					'account_type' => $check_user->account_type,
					'admin_id' => $check_user->id
				);
		$this->session->set_userdata($data);
		redirect(base_url('ExamController/'.$method));
	}

	public function select_paper($student_id){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$titleData['title'] = 'Select Papers';
		$this->load->view('header',$titleData);
		$student = $this->Common_model->student_info($student_id);
		$classData = $this->Common_model->getRecordById('class_master','id', $student['class_id']);
		//$cbcs = ($classData->cbcs == 'Y')?'Y':'N';
		$cbcs = ($classData->cbcs == 'Y' && $student['exam_pattern']=="GRADE")?'Y':'N';
		$this->db->order_by('id');
		$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$student['class_id'].' and ce="compulsory" and cbcs_paper="'.$cbcs.'"');
		if($student['university_mode'] == "REG"){
			$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id join paper_master as m on m.id=p.paper_id where g.class_id='.$student['class_id'].'  and cbcs_paper="'.$cbcs.'" Order by g.id,sub_group_id,p.id')->result();

		}else{
			$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id join paper_master as m on p.paper_id=m.id  where g.class_id='.$student['class_id'].'   and m.type="theory" Order by g.id,sub_group_id,p.id')->result();
		}
		
		$data['compulsoryPapers'] = $compulsoryPapers;
		$data['student'] = $student;
		$data['student_id'] = $student['student_id'];
			// // CONDITION FOR GROUP PAPER
		$this->db->select('class_group,select_group,group_type');
		$this->db->from('class_master');
		$this->db->join('student', 'class_master.id = student.class_id');
		$this->db->where(array('class_master.id' => $data['student']['class_id'],
			'student_id' => $student['student_id']
		));
		$class_group = $this->db->get()->result();

		$data['class_group'] = $class_group;

		$data['groupPaper'] = $groupPaper;


		if($class_group[0]->group_type=='Paper'){
			$this->load->view('admin/student/select_paper',$data);
		}else{
			$this->load->view('admin/student/select_group',$data);
		}
		$this->load->view('footer');

	}

	public function submit_papers(){
		$student_id=$this->Common_model->encrypt_decrypt($_POST['student_id'],'decrypt');
		$paper_id1 = $_POST['paper_id'];
		$paper_id2 = $_POST['compulsary_paper_id'];
		$paper_id= array_merge($paper_id1,$paper_id2);
		$paper_id = implode(",",$paper_id);
		$paper_data = 	$this->Common_model->get_record('paper_master','*','id in ('.$paper_id.')');

		foreach($paper_data as $paper){
			$data['course_group_id']=$paper['course_group_id'];
			$data['class_id']=$paper['class_id'];
			$data['paper_code']=$paper['paper_code'];
			$data['paper_type']=$paper['type'];
			$data['book_code']=$paper['book_code'];
			$data['paper_id']=$paper['id'];
			$data['sub_group_id']=$paper['sub_group_id'];
			$data['student_id']=$student_id;
			$insert = $this->Common_model->insertAll('new_exam_form',$data);
		}


		if($insert){
			$data = array('temp_exam_form'=>'Y');
			$where = array('student_id'=>$student_id);
			$this->Common_model->updateRecordByConditions('student',$where,$data);
			echo json_encode(array("status" => 'true','student_id' => $student_id));
		}else{
			echo json_encode(array("status" => 'false','student_id' => $student_id));
		}
	}


	public function submit_group($mode =""){
		
		$paper_code = $_POST['compulsary_paper_code'];
		$class_id = $_POST['class_id'];
		$student_id=$this->Common_model->encrypt_decrypt($_POST['student_id'],'decrypt');
		$studentData = $this->Common_model->getRecordById('student','student_id',$student_id);
		$classData = $this->Common_model->getRecordById('class_master','id', $studentData->class_id);
	
		$cbcs = ($classData->cbcs == 'Y' && $studentData->exam_pattern=="GRADE")?'Y':'N';
		$i = 1;
		$this->db->where_in('paper_code',$paper_code);
		$this->db->where('class_id',$class_id);
		$this->db->where('cbcs_paper',$cbcs);
		$this->db->order_by('paper_no');
		$paper_data = $this->Common_model->get_record('paper_master','*');
		
		foreach($paper_data as $paper){
			$data['course_group_id']=$paper['course_group_id'];
			$data['class_id']=$paper['class_id'];
			$data['paper_code']=$paper['paper_code'];
			$data['paper_type']=$paper['type'];
			$data['book_code']=$paper['book_code'];
			$data['paper_id']=$paper['id'];
			$data['student_id']=$student_id;
			$data['paper_order']=$i;
			$data['sub_group_id']=$paper['sub_group_id'];
			$insert = $this->Common_model->insertAll('new_exam_form',$data);
			$i++;
		}

		if(isset($_POST['group_id'])){
			$group_id = $_POST['group_id'];
			$this->db->select('paper_code,sub_group_id');
			$this->db->from('group_paper');
			$this->db->where_in('group_id',$group_id);
			$groupPaperData = $this->db->get()->result_array();

			$groupPaperCodes = array_column($groupPaperData, 'paper_code');
			$this->db->where_in('paper_code',$groupPaperCodes);
			$this->db->where('class_id',$class_id);
			if($mode == "PVT"){
				$this->db->where('type',"theory");
			}else{
				$this->db->where('cbcs_paper',$cbcs);
			}
			$this->db->order_by('paper_no');
			$papers = $this->Common_model->get_record('paper_master','*');
			foreach($papers as $paper){
				$data['course_group_id']=$paper['course_group_id'];
				$data['class_id']=$paper['class_id'];
				$data['paper_code']=$paper['paper_code'];
				$data['paper_type']=$paper['type'];
				$data['book_code']=$paper['book_code'];
				$data['paper_id']=$paper['id'];
				$data['student_id']=$student_id;
				$data['paper_order']=$i;
				$debug =  array_search($paper['paper_code'], $groupPaperCodes);
				$data['sub_group_id'] = $groupPaperData[$debug]['sub_group_id'];
				$insert = $this->Common_model->insertAll('new_exam_form',$data);
				$i++;
			}
		}

		if($insert){
			$data = array('temp_exam_form'=>'Y');
			if (isset($_POST['group_id'])) {
				$data['group_id'] = implode(',', $_POST['group_id']);
			}
			$where = array('student_id'=>$student_id);
			$this->Common_model->updateRecordByConditions('student',$where,$data);
			echo json_encode(array("status" => 'true','student_id' => $student_id));
		}else{
			echo json_encode(array("status" => 'false','student_id' => $student_id));
		}

	}
	public function center_wise_exam_form_report(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$data['centers'] = $this->Common_model->getRecordByWhere('center',array('status'=>'Y'));
			$data['name_csrf'] = $this->security->get_csrf_token_name();
				$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->load->view('header',array('title'=>"Center Wise Exam Form Status"));
			$this->load->view('admin/center_wise_exam_form_report',$data);
			$this->load->view('footer');
		}
	}

	public function get_center_wise_exam_form_report()
	{
		$data = $row = array();
		$where = "status=   'Y' ";
		$column_order = array(null,'center_code','center_name','city','Distirct','contactpersonname','mobile_no_1','Total','Fill','Remaining');
		$column_search = array('center_code','center_name','city','Distirct','contactpersonname','mobile_no_1','Total','Fill','Remaining');
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => 'center'
		);
		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$total_count = $this->Common_model->getcountbywhere('student',array('center_id'=>$result->id,'new_exam_form !='=>'D'));
			$fill_count = $this->Common_model->getcountbywhere('student',array('center_id'=>$result->id,'new_exam_form'=>'Y'));
			 $remaining = $total_count - $fill_count;
			$district= $this->Common_model->getDistrict($result->distt_id);
			$i++;
			$data[] = array($i, $result->center_code, $result->center_name,$result->city,$district ,$result->contactpersonname,$result->mobile_no_1,$total_count,$fill_count,$remaining);
	     	}
		  $output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('center',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);
		echo json_encode($output);	
	}

    public function exam_center_wise_exam_form_report(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			$data['exam_centers'] = $this->Common_model->getRecordByWhere('exam_center');	
			$this->load->view('header',array('title'=>"Exam Center Wise Exam Form Status"));
			$this->load->view('admin/exam_center_wise_exam_form_report',$data);
			$this->load->view('footer');
		}
	}

	public function exam_center_wise_student_list($center_id,$param='')
	{
	 $center_id = $this->Common_model->encrypt_decrypt($center_id,'decrypt');
	 $param = $this->Common_model->encrypt_decrypt($param,'decrypt');
		if($param=='fill')
		{
			$where = array('new_exam_form'=>'Y','exam_center_id'=>$center_id);		
		}
		else{
	     $this->db->group_start();
		 $this->db->where('new_exam_form','N');
		 $this->db->or_where('new_exam_form', 'S');
		 // $this->db->or_where('new_exam_form', 'D');
		 $this->db->group_end();
		 $where = array('exam_center_id'=>$center_id);
		}
		if($center_id!='')
		{
			$this->db->order_by('roll_no ','asc');
			$data['listing'] = $this->Common_model->getRecordByWhere('student',$where);
			$this->load->view('header',array('title' => 'Exam Center Wise Student List'));
			$this->load->view('admin/exam_center_wise_student_list',$data); 
			$this->load->view('footer');
		}
	}

	public function exam_center_wise_backlog_student_list($center_id,$param='')
	{
	 $center_id = $this->Common_model->encrypt_decrypt($center_id,'decrypt');
	 $param = $this->Common_model->encrypt_decrypt($param,'decrypt');
	
		if($param=='fill')
		{
			$where = array('exam_form'=>'Y','exam_center_id'=>$center_id,'exam_year'=>'Dec 2023');		
		}
		else{
	     $this->db->group_start();
		 $this->db->where('exam_form','N');
		 $this->db->or_where('exam_form', 'S');
		 // $this->db->or_where('exam_form', 'D');
		 $this->db->group_end();
		 $where = array('exam_center_id'=>$center_id);
		}
		if($center_id!='')
		{
			$this->db->order_by('roll_no ','asc');
			$data['listing'] = $this->Common_model->getRecordByWhere('backlog_student',$where);
			$this->load->view('header',array('title' => 'Exam Center Wise Backlog Student List'));
			$this->load->view('admin/exam_center_wise_backlog_student_list',$data); 
			$this->load->view('footer');
		}
	}


	public function change_password(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
		}else{
			$titleData = array('title' => 'Change Password');
			$this->load->view('header',$titleData);
            $admin_id = $this->session->admin_id;
			$admin_master_data = $this->Common_model->getRecordById('admin_master','id',$admin_id);
			$data = array('admin_data' => $admin_master_data,
                'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(), );
			$this->load->view('admin/enrollment/change_password',$data);
			$this->load->view('footer');
		}
	}


	public function change_password_sub($id)

	{ 
		$new_password 	  = $this->input->post('new_password');
		$confirm_password = $this->input->post('passconf');
		$new_password_change = md5($new_password);
		if($this->input->post('new_password') != "")
		{
			if($new_password == $confirm_password)
			{
				$data_update = array("password" =>  $new_password_change);
				$this->db->where('id', $id);
				$this->db->update('admin_master', $data_update);
				echo json_encode(array(
					"success" => 'Password Updated Successfully',
				));
			}
			else{
				echo json_encode(array(
					"error" => 'Password does not match',
				));
			}
		}else{
			echo json_encode(array(
				"error" => 'Please enter New Password',
			));
		}
	}

	public function annual_permission_status()
	{

		if ($this->input->method() == "post") 
		{
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");

			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("session",array("id" => $id ),array("annual_permission" => $status ));

				$dt = $this->db->get_where("session",array("id" => $id ))->result_array();

				if($dt[0]['annual_permission'] == 'Y')
				{
					$sts_btn = '<input type ="button" name="annual_permission_stats" data-id='.$id.' class="btn btn-success annual_permission_check" value="Yes">';
				}else{
					$sts_btn = '<input type ="button" name="annual_permission_stats" data-id='.$id.' class="btn btn-danger annual_permission_check" value="No">';
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

	public function semester_permission_status()
	{

		if ($this->input->method() == "post") 
		{
			$id    	= 0;
			$id    	= $this->input->post("id");
			$status = $this->input->post("status");

			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("session",array("id" => $id ),array("semester_permission" => $status ));

				$dt = $this->db->get_where("session",array("id" => $id ))->result_array();

				if($dt[0]['semester_permission'] == 'Y')
				{
					$sts_btn = '<input type ="button" name="semester_permission_stats" data-id='.$id.' class="btn btn-success semester_permission_check" value="Yes">';
				}else{
					$sts_btn = '<input type ="button" name="semester_permission_stats" data-id='.$id.' class="btn btn-danger semester_permission_check" value="No">';
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

	  public function application_field($param1 = '', $param2 = '', $param3 = ''){

		if($param1 == 'create'){

			$Data=array(
				'field'=>$this->input->post('field'),
				'amount'=>$this->input->post('amount')
			);
			$this->Common_model->insertAll('application_field',$Data);

			$this->session->set_flashdata('ajax_flash_message','Account Successfully Added');
			redirect(base_url().'application_field');

		}
		if($param1 == 'update'){

			// $response = $this->admin_model->account_update($param2);
			$where = "id= ".$param2."";
			$Data=array(
				'field'=>$this->input->post('field'),
				'amount'=>$this->input->post('amount')
			);
			$this->Common_model->updateRecordByConditions('application_field',$where,$Data); 
			$this->session->set_flashdata('ajax_flash_message','Account Successfully Updated');
			redirect(base_url().'application_field');
		}

		if($param1 == 'delete'){

			// $response = $this->admin_model->account_delete($param2);
			$this->Common_model->deleteById('application_field','id',$param2);
			$this->session->set_flashdata('ajax_flash_message','Account Successfully Deleted');
			redirect(base_url().'application_field');
		}

		if(empty($param1) ){
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);
			$titleData = array('title' => 'Application Field');
			$this->load->view('header',$titleData);
			$data['field']= $this->Common_model->getRecordByWhere('application_field');
			$this->load->view('admin/application_field',$data);
			$this->load->view('footer');
			
		}    
		
	  }

	  public function update_field_status()
		{

			if ($this->input->method() == "post") 
			{
				$id    = 0;

				$id    = $this->input->post("id");
				$status = $this->input->post("status");

				if ($this->input->post("id")) 
				{
					$data = $this->Common_model->updateRecordByConditions("application_field",array("id" => $id ),array("status" => $status ));

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

	public function view_mode_change_complaint(){
			
		if($this->session->has_userdata('adminData')){
			$where = array("status" => "Pending");
			$centers = $this->Common_model->get_record_group_by_where('request_mode_change','center_id',$where);

			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
			
			$this->load->view('header',array('title'=>"Center Mode Change Request"));
			$this->load->view('admin/view_mode_change_complaint',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}
	public function get_mode_change_complaints()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
			$center_id  = $this->input->post("center_id");
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$wherecenter = 'center_id='.$center_id.' and status="Pending"';
			$complaints = $this->Common_model->get_record('request_mode_change','*',$wherecenter);
			
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);

			if($data['complaints']){
				$dt =  $this->load->view('admin/getModeChnageComplaints',$data,true);
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
	public function update_mode_change_complaint_status()
	{
		if ($this->input->method() == "post") 
		{
            $id    	= 0;
            $id    	= $this->input->post("id");
			$status = $this->input->post("status");

			
            if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("request_mode_change",array("id" => $id ),array("status" => $status ));
			
				$dt = $this->db->get_where("request_mode_change",array("id" => $id ))->result_array();

				if($dt[0]['status'] == 'Done'){
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

	public function update_mode_change_complaint_remark()
	{
		if ($this->input->method() == "post") 
		{
	        $id    	= $this->input->post("id");
	        $remark = $this->input->post("remark");
			//$status = ($remark=='Invalid') ? 'Done' : "Pending";
			$status = ($remark=='') ? 'Pending' : "Done";
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("request_mode_change",array("id" => $id ),array("remark" => $remark,"status" => $status));
				
				$dt = $this->db->get_where("request_mode_change",array("id" => $id ))->result_array();
				
				if($dt[0]['remark'] == ''){
				
				//$sts_btn = '<input type="button" name="update_req_remark" data-id='.$id.' class="btn btn-success remark_check" value="Set">';
				
				$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
				}else{
				
			//	$sts_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-danger remark_check" value="Invalid">';
				
				$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
				}

				$status = true;
				$msg    = "";
				
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					//"remarkBtn" => $sts_btn,
					"statusBtn" => $sts_btn2
				));
			}	
		}
	}

	public function class_wise_remaining_theory_marks(){

		$this->load->view('header',array('title' => 'Class Wise Remaining Theory Marks'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);

			$this->load->view('admin/class_wise_remaining_theory_marks',$data);
			$this->load->view('footer');

	}

	public function class_wise_remaining_student_report(){
		
			$data=array();	
			$mode =$this->input->post('mode');
			$classes = $this->Common_model->getRecordByWhere('class_master',array('mode'=>$mode,'result_permission'=>'Y'));
			$set = array_column($classes,'id');
				$this->db->select('*');
				$this->db->from('new_exam_form as e' );
				$this->db->join('student', 'e.student_id = student.student_id and e.class_id = student.old_class_id');
				$this->db->where('student.exam_form','Y');
				// if($mode != 'All'){
				// 	$this->db->where_in('new_exam_form.class_id',$set);
				// }
				$this->db->where_in('e.class_id',$set);
				 $this->db->where('e.theory_marks','');
				$this->db->where('e.paper_type',"theory");
				$this->db->order_by('student.course_group_id','student.old_class_id','student.university_mode','student.roll_number');
				
			$data['students'] = $this->db->get()->result();
			
			$dt = $this->load->view('admin/class_wise_remaining_report_table_old',$data,true);
		
			echo json_encode(array(
                "status" => true,
                "data" => $dt
            ));
		
	}
	//Time Table
	public function time_table(){
		$dt = array();
		$titleData = array('title' => 'Course Wise Exam Date ');
	
		$this->load->view('header',$titleData );
		$dt['name_csrf'] = $this->security->get_csrf_token_name();
		$dt['hash_csrf'] = $this->security->get_csrf_hash();
	
		$this->db->select('course_group.*');
		$this->db->from('course_group');
		$this->db->join('paper_master', 'paper_master.course_group_id = course_group.id');
		$this->db->where('paper_master.exam_date!=','');
		$this->db->where('paper_master.exam_date!=','0000-00-00');  
		$this->db->where('paper_master.type','theory'); 
	   
		$this->db->group_by('paper_master.course_group_id');
		$this->db->order_by('course_group.course_name', 'Asc');
		$dt['courses']= $this->db->get()->result_array();
		$this->load->view('Centers/search_exam_by_course',$dt);
		$this->load->view('footer');

		
	}

	public function time_table_old(){
		$dt = array();
		$titleData = array('title' => 'Course Wise Old Exam Date ');
	
		$this->load->view('header',$titleData );
		$dt['name_csrf'] = $this->security->get_csrf_token_name();
		$dt['hash_csrf'] = $this->security->get_csrf_hash();
	
		$this->db->select('course_group.*');
		$this->db->from('course_group');
		$this->db->join('paper_master', 'paper_master.course_group_id = course_group.id');
		$this->db->where('paper_master.old_exam_date!=','');
		$this->db->where('paper_master.old_exam_date!=','0000-00-00');  
		$this->db->where('paper_master.type','theory'); 
	   
		$this->db->group_by('paper_master.course_group_id');
		$this->db->order_by('course_group.course_name', 'Asc');
		$dt['courses']= $this->db->get()->result_array();
		$this->load->view('Centers/search_exam_by_course_old',$dt);
		$this->load->view('footer');

		
	}

	public function support_system($param1 = '', $param2 = '', $param3 = '')
	{

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{

			if($param1 == 'create'){

				$response = $this->admin_model->create_support_system();
				$this->session->set_flashdata('ajax_flash_message','Support Successfully Added');
				redirect(base_url().'support_system');

			}
			if($param1 == 'update'){

				$response = $this->admin_model->update_support_system($param2);
				$this->session->set_flashdata('ajax_flash_message','Support Successfully Updated');
				redirect(base_url().'support_system');
			}

			if($param1 == 'delete'){

				$response = $this->admin_model->delete_support_system($param2);
				$this->session->set_flashdata('ajax_flash_message','Support Successfully Deleted');
				redirect(base_url().'support_system');
			}

			if(empty($param1) ){
				$data = array();
				$titleData = array('title' => 'Support System');
				$this->load->view('header',$titleData);
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('admin/support_system',$csrf);
				$this->load->view('footer');
			}    
		}
	}

	public function update_support_status()
	{
		if ($this->input->method() == "post") 
		{
			$id    = $this->input->post("id");
			$status = $this->input->post("status");
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("support_system",array("id" => $id ),array("status" => $status));
				$status = true;
				$msg    = "";
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $data));
				}
			}
	}

	public function complaint_department($param1 = '', $param2 = '', $param3 = '')
	{

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{

			if($param1 == 'create'){

				$response = $this->admin_model->create_complaint_department();
				$this->session->set_flashdata('ajax_flash_message','Create Department Successfully');
				redirect(base_url().'complaint_department');

			}
			if($param1 == 'update'){

				$response = $this->admin_model->update_complaint_department($param2);
				$this->session->set_flashdata('ajax_flash_message','Update Department Successfully');
				redirect(base_url().'complaint_department');
			}

			if($param1 == 'delete'){

				$response = $this->admin_model->delete_complaint_department($param2);
				$this->session->set_flashdata('ajax_flash_message','Department Successfully Deleted');
				redirect(base_url().'complaint_department');
			}

			if(empty($param1) ){
				$data = array();
				$titleData = array('title' => 'Complaint Department');
				$this->load->view('header',$titleData);
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('admin/complaint_department',$csrf);
				$this->load->view('footer');
			}    
		}
	}

	public function update_department_status()
	{
		if ($this->input->method() == "post") 
		{
			$id    = $this->input->post("id");
			$status = $this->input->post("status");
			if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("department_complaint",array("id" => $id ),array("status" => $status));
				$status = true;
				$msg    = "";
				echo json_encode(array(
					"status" => $status,
					"msg" => $msg,
					"data" => $data));
				}
			}
	}
				
	public function student_old_result(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
			exit;
		}else{
			$this->load->view('header',array('title' =>'Search Student Old Result'));
			$data = array(
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('admin/msprint/student_result',$data);
			$this->load->view('footer');
		}

	}

	public function get_student_result(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url('admin'));
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
				$result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id' =>$student->student_id));
				//,"exam_year"=>"Feb 2022"
				// $this->Common_model->last_query();
				$data = array(
					'result' => $result,
					'student' => $student,
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash(),
				);

				$dt =  $this->load->view('admin/msprint/view_student_result',$data,true);
				echo json_encode(array(
					"status" => true,
					"data" => $dt
				));
			}
		}
	}

	public function view_center_wise_complaint(){
			
		if($this->session->has_userdata('adminData')){
			$admin_id = $this->session->admin_id;
			$admin = $this->Common_model->getRecordById('admin_master','id',$admin_id);
			$where = "support_complaint.status = 'Pending' AND support_system.id IN (".$admin->support_ids.")";
				$this->db->select('count(*) as count,'.'center_id');
				$this->db->from('support_complaint');
				$this->db->join('support_system','support_system.name = support_complaint.type');
                $this->db->where('type','Exam Form Complaint');
				$this->db->where($where);
				$this->db->group_by('center_id');
				$centers = $this->db->get()->result_array();
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers
			);
			
			$titleData = array('title' => 'Complaints');
			$this->load->view('header',$titleData);
			$this->load->view('admin/view_center_wise_complaint',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

	public function get_center_wise_complaints()
{
	if ($this->input->method() == "post") 
	{
		$course_group_id = 0;
		$data = array();
		$dt   = array();
		$admin_id = $this->session->admin_id;
			$admin = $this->Common_model->getRecordById('admin_master','id',$admin_id);

			
		$center_id  = $this->input->post("center_id");
		$centerData = $this->Common_model->getRecordById('center','id',$center_id);
		// $wherecenter = 'center_id='.$center_id.' and status="Pending"';
		// $complaints = $this->Common_model->get_record('support_complaint','*',$wherecenter);
		$where = "center_id = ".$center_id." AND support_complaint.status = 'Pending' AND support_system.id IN (".$admin->support_ids.")";
			
				$this->db->select('support_complaint.*');
				$this->db->from('support_complaint');
                $this->db->where('type','Exam Form Complaint');
				$this->db->join('support_system','support_system.name = support_complaint.type');
				$this->db->where($where);
				
			$complaints = $this->db->get()->result_array();
			
			
		
		$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'centerData' => $centerData,
		);

		if($data['complaints']){
			$dt =  $this->load->view('admin/getCenterWiseComplaints',$data,true);
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

public function update_center_wise_complaint_status()
{
	if ($this->input->method() == "post") 
	{
		$id    	= 0;
		$id    	= $this->input->post("id");
		$status = $this->input->post("status");

		
		if ($this->input->post("id")) 
		{
			$data = $this->Common_model->updateRecordByConditions("support_complaint",array("id" => $id ),array("status" => $status));
			$dt = $this->db->get_where("support_complaint",array("id" => $id ))->result_array();

			if($dt[0]['status'] == 'Done'){
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

public function update_center_wise_complaint_remark()
{
	if ($this->input->method() == "post") 
	{
		$id    	= $this->input->post("id");
		$remark = $this->input->post("remark");
		$status = ($remark=='Invalid') ? 'Done' : "Pending";

		if ($this->input->post("id")) 
		{
			$data = $this->Common_model->updateRecordByConditions("support_complaint",array("id" => $id ),array("remark" => $remark,"status" => $status));

			$dt = $this->db->get_where("support_complaint",array("id" => $id ))->result_array();
			
			if($dt[0]['remark'] != 'Invalid'){
			
			$sts_btn = '<input type="button" name="update_req_remark" data-id='.$id.' class="btn btn-success remark_check" value="Set">';
			
			$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-danger req_check" value="Pending">';
			}else{
			
			$sts_btn = '<input type="button" name="req_remark" data-id='.$id.' class="btn btn-danger remark_check" value="Invalid">';
			
			$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$id.' class="btn btn-success req_check" value="Done">';
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
public function forward_complaint(){

	if ($this->input->method() == "post") 
	{
		$id    	= $this->input->post("id");
		$dept = $this->input->post("dept");
		$status = ($remark=='Invalid') ? 'Done' : "Pending";

		if ($this->input->post("id")) 
		{
			$data = $this->Common_model->updateRecordByConditions("support_complaint",array("id" => $id ),array("type" => $dept));
		}else{
			$data = "Something Went Wrong";
		}
		echo json_encode($data);
	}
}
	public function marksheet($exam_data_id="")
	{
		$this->load->library('numbertowordconvertsconver');
		$exam_data_id =  $this->Common_model->encrypt_decrypt($exam_data_id,'decrypt');
		$this->db->select('*');
		$this->db->from('old_result_data');
		$this->db->where('old_result_data.exam_data_id',$exam_data_id);
		$this->db->order_by('p_order','ASC');
		$new_exam_form = $this->db->get()->result();
		$course_id = $new_exam_form[0]->course_group_id;
		$data['old_result_data']  = $new_exam_form;
		$data['class_id']  = $new_exam_form[0]->class_id;
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
		//$class_cbcs = array(193,197,201,203,205,211,213,221,223,225,227,275,279);
		// $title = array('title' => 'Result');
		$data['exam_data'] = $this->Common_model->getRecordById('old_exam_data','id',$exam_data_id);
		$data['exam_data_id']=$exam_data_id;
		// $course_id !=36 && $course_id !=37
		$data['class'] = $this->Common_model->getRecordByID('class_master','id', $data['exam_data']->class_id);
		
		$this->load->view('admin/generate_tr/header2',$title);
		$this->load->view('admin/old_marksheet_top',$data);
		
		if((in_array($new_exam_form[0]->class_id , $class_ids)) && $data['exam_data']->marks_pattern=='GRADE'){
			$this->load->model('Gradesheet_old_model');
			$this->load->view('admin/grade_marksheet',$data);
		}else if($data['class']->cbcs=='Y' && $data['exam_data']->university_mode=='REG' && $data['exam_data']->marks_pattern=='GRADE'){
				$this->load->model('GradeSheet_old_model_pg');
				$this->load->view('admin/grade_marksheet_pg',$data);
		}else if($data['exam_data']->university_mode !="PVT"  && $data['class']->internal !='N'){
			
			$this->load->view('admin/marksheet_student',$data);
		}else{
			
			$this->load->view('admin/marksheet_student_pvt',$data);
		}
		
		$this->load->view('admin/generate_tr/footer2');
	}
	
	public function class_wise_remaining_theory_marks_count(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else{
			
			$admin_id = $this->session->admin_id;
		//	$this->db->where_not_in('id',array(236,238,240,244,246,216,248,250,254,232,234,252,300,258,256,268,218,230,242,155,182,154,181,180,174,196,162,200,210,172,299,273,165,289,110,116,149,170,187,140,143,146,290,284,294,296,292,192,184,159,138,194,198,202,204,206,212,214,222,276,280,224,226,228,164,101,102,111,117,120,129,132,168,183,283,288,287,291,293,298,105,135));
            // $this->db->where_in('id', array(102,105,108,111,117,120,126,129,132,135,194,198,202,204,206,212,214,222,224,226,228,276,280,303));
			$class_data = $this->db->get_where('class_master', array('result_permission' => 'Y'))->result_array();
			$class_dataids = array_column($class_data, 'id');
			$this->db->where_in('old_class_id',$class_dataids);
		
		  $courseData= $this->Common_model->get_record_group_by_where('student','course_group_id,old_class_id,class_name,course_name',array($this->exam_form=>'Y'));
			$data = array('course_group' => $courseData,
				'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
			);
			$this->load->view('header',array('title' => 'Class Wise Remaining Theory Marks'));
			$this->load->view('admin/class_wise_remaining_theory_marks_count',$data);
			$this->load->view('footer');
		}
	}

	public function bulk_permission(){

		if($this->session->has_userdata('adminData')){

			$admin_id = $this->session->admin_id;

			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash()
			);

			$this->load->view('header',array('title' => 'Bulk Permission For All Centers'));
			$this->load->view('admin/director/bulk_permission',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

	public function update_center_permission()
	{

		if ($this->input->method() == "post") 
		{

			$parameter1   	= $this->input->post("param_name");
			$permisssion    = $this->input->post("permission");

			$data = array($parameter1 => $permisssion);
			$where = array('status' => 'Y');
			$res = $this->Common_model->updateRecordByConditions('center',$where,$data);
			
			//echo $this->db->last_query();


			$dt = $this->db->get_where("center",array($parameter1 => $permisssion))->row();

			if($dt->$parameter1 == 'Y')
			{
				$sts_btn = '<a class="btn btn-primary" onclick="update_permission(\''.$parameter1.'\',\'N\')">All Yes</a>';
			}

			else{
				
				$sts_btn = '<a class="btn btn-danger" onclick="update_permission(\''.$parameter1.'\',\'Y\')">All No</a>';
				
				
			}


			echo json_encode(array(
				"status" => $res,
				"msg" => "Permission has been updated successfully",
				"sts_btn"=>$sts_btn,
				"p1"=>$parameter1,
			));

		}
	}

	public function update_late_fees()
	{

		if ($this->input->method() == "post") 
		{

			$parameter1   	= $this->input->post("param_name");
			$permisssion    = $this->input->post("permission");

			$data = array($parameter1 => $permisssion);
			
			$res = $this->Common_model->updateRecordByConditions('master',$where,$data);
			
			//echo $this->db->last_query();


			$late_exam_fees_privte = $this->Common_model->getRecordByWhere('master');

			if($late_exam_fees_privte[0]->p_late_fee_status == 'Y')
			{
				$sts_btn = '<a class="btn btn-primary" onclick="update_late_fees(`p_late_fee_status`,`N`)">All Yes</a>';
			}

			else{
				
				$sts_btn = '<a class="btn btn-danger" onclick="update_late_fees(`p_late_fee_status`,`Y`)">All No</a>';
				
				
			}


			echo json_encode(array(
				"status" => $res,
				"msg" => "Permission has been updated successfully",
				"sts_btn"=>$sts_btn,
				"p1"=>$parameter1,
			));

		}
	}
	public function backlog_student_result_permission($mode="",$course_id="",$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		$data['not_permited_students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id , 'result_show'=>'N' , 'exam_form'=>'Y' ,'mode'=>$mode,'exam_year'=>'Dec 2023'));

		$data['permited_students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id , 'result_show'=>'Y' , 'exam_form'=>'Y' ,'mode'=>$mode,'exam_year'=>'Dec 2023'));
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$data['mode']=$mode;
		$this->load->view('header',array('title' => ''));
		$this->load->view('admin/backlog_student_result_permission',$data);
		$this->load->view('footer');
	}

	public function update_backlog_student_result_permission(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		if($_POST['not_permitted']){
			$student_ids = (implode(',',$_POST['not_permitted']));
			$data = array('result_show' => 'Y');
			$where = " student_id in (".$student_ids.") and exam_year = 'Dec 2023' and class_id=".$_POST['class_id']."";//provisional_remark in ('','N') &&
			$update =$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('result_show' => 'N');
			$where ='student_id in ('.$student_ids.') and exam_year = "Dec 2023" and class_id='.$_POST["class_id"].'';
			$update = 	$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/backlog_student_result_permission/'.$_POST['mode'].'/'.$_POST['course_group_id'].'/'.$_POST['class_id']);
		}
	}

	public function backlog_student_notification_list($mode = "",$course_id="",$class_id=""){
		$this->load->model('Gradesheet_backlog_tr_model');
		$course_id = $this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$this->db->order_by('roll_no','ASC');
		$data['mode']= $mode;
		$data['students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id,'exam_form'=>'Y' ,'roll_no!='=>'0', 'mode'=>$mode,'exam_year'=>"Dec 2023"));//'result_show'=>'Y'
		$data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$this->load->view('admin/backlog_student_notification_list',$data);
	}

	public function withheld_backlog_student_list($course_id="",$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		$this->db->select('count(*) as cnt ,student.name,backlog_student.roll_no,student.course_name,backlog_student.class_id  , backlog_student.center_code,backlog_student.course_group_id,backlog_student.student_id');
		$this->db->from('backlog_exam_form');
		$this->db->join('backlog_student', 'backlog_exam_form.student_id = backlog_student.student_id');
		$this->db->join('student', 'student.student_id = backlog_student.student_id');
		$this->db->where('backlog_student.exam_form','Y'); 
		$this->db->where('backlog_student.exam_year','Dec 2023'); 
		$this->db->where('backlog_student.result_show','Y'); 
		$this->db->where('backlog_exam_form.paper_type','theory'); 
		$this->db->where('backlog_exam_form.theory_marks',''); 
		$this->db->where('backlog_student.course_group_id',$course_id); 
		$this->db->where('backlog_student.class_id',$class_id); 
		$this->db->where('backlog_exam_form.class_id',$class_id); 
		$this->db->group_by('backlog_exam_form.student_id');
		
		$data['students'] = $this->db->get()->result();
		
		// echo  $this->db->last_query(); die;
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();

		$this->load->view('header',array('title' => 'List of backlog students'));
		$this->load->view('admin/withheld_backlog_student_list',$data);
		$this->load->view('footer');
	}

	public function remove_backlog_student_result_permission(){
		
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		if($_POST['not_permitted']){
			$student_ids = (implode(',',$_POST['not_permitted']));
			$data = array('result_show' => 'Y');
			$where = 'student_id in ('.$student_ids.') and exam_year = "Dec 2023"';
			$update =$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('result_show' => 'N');
			$where ='student_id in ('.$student_ids.') and exam_year = "Dec 2023"';
			$update = 	$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/withheld_backlog_student_list/'.$_POST['course_group_id'].'/'.$_POST['class_id']);
		}
	}

	public function backlog_student_marksheet($mode="",$course_id="",$class_id="",$startlimit=0)
	{
		$data = array('class_id' => $class_id,'course_group_id' =>$course_id );
				$start=0;
			//,'enrollment_no'=>'AH/22101159'	
		// 'enrollment_no'=>'AG/21220737'
		// ,'enrollment_no'=>'AG/21200364'
		$title = "Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
		$class = $this->Common_model->getRecordByID('class_master','id',$class_id);

		if($startlimit!=0){
			$start=($startlimit-1)*1000;
			$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}	
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] = $title;
		$data['university_mode'] = $mode;

		if($class->last_class == 'L'){
			
			 $this->db->select('backlog_student.*,student.name,student.f_h_name,student.session,student.photo,student.course_name');
			 $this->db->from('backlog_student');
			$this->db->join('student','student.student_id = backlog_student.student_id');
			$this->db->where(array("backlog_student.course_group_id"=>$course_id ,'backlog_student.class_id' => $class_id,'backlog_student.exam_form'=>'Y','backlog_student.roll_no!='=>'0','student.course_complete'=>'Y','backlog_student.mode'=>$mode,'backlog_student.result_show'=>'Y','backlog_student.exam_year'=>'Dec 2023'));
			$this->db->order_by('backlog_student.center_id,backlog_student.roll_no','ASC');
			$data['students']=$this->db->get()->result();
			// $this->Common_model->last_query();
		 }else{
			$this->db->select('backlog_student.*,student.name,student.f_h_name,student.session,student.photo,student.course_name');
			$this->db->from('backlog_student');
			$this->db->join('student','student.student_id = backlog_student.student_id');
			$this->db->where(array("backlog_student.course_group_id"=>$course_id ,'backlog_student.class_id' => $class_id,'backlog_student.exam_form'=>'Y','backlog_student.roll_no!='=>'0','backlog_student.mode'=>$mode,'backlog_student.result_show'=>'Y' ,'backlog_student.exam_year'=>'Dec 2023'));
			//,'backlog_student.enrollment_no'=>'AH/22101159'
			$this->db->order_by('backlog_student.center_id,backlog_student.roll_no','ASC');
			$data['students']=$this->db->get()->result();
			// $this->Common_model->last_query();
		 }
	 	if($class->internal=="Y" && $mode!="PVT"){
			$this->load->view('admin/backlog_student_marksheet',$data);
		}else{
			$this->load->view('admin/backlog_student_marksheet_certificate',$data);
		}
	}

	public function complaint_reply(){
		if ($this->input->post("complaint_id")) 
		{
			
			$complaint_id = $this->input->post('complaint_id');
		 	$reply_text = $this->input->post('remark');
		 	
		
		 $this->Common_model->updateRecordByConditions("support_complaint",array("id" => $complaint_id ),array("remark" => 'Invalid',"status" => 'Done','reply_text'=>$reply_text));

			$dt = $this->db->get_where("support_complaint",array("id" => $complaint_id))->result_array();
			
			if($dt[0]['remark'] != 'Invalid'){
			
			$sts_btn = '<input type="button" name="update_req_remark" data-id='.$complaint_id.' class="btn btn-success remark_check" value="Set">';
			
			$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$complaint_id.' class="btn btn-danger req_check" value="Pending">';
			$sts_btn3 ='<button class="btn btn-primary" id="reply" disabled> Reply</button>';
			}else{
			
			$sts_btn = '<input type="button" name="req_remark" data-id='.$complaint_id.' class="btn btn-danger remark_check" value="Invalid">';
			
			$sts_btn2 = '<input type="button" name="update_req_stats" data-id='.$complaint_id.' class="btn btn-success req_check" value="Done">';
			$sts_btn3 ='<button class="btn btn-primary" id="reply" disabled> Reply</button>';
			}

			$status = true;
			$msg    = "";
			
			echo json_encode(array(
				"status" => $status,
				"msg" => $msg,
				"remarkBtn" => $sts_btn,
				"statusBtn" => $sts_btn2,
				'replyBtn'=>$sts_btn3,
			));
		}	
	}
		
	public function getFinalClassByCourse(){
		$course = $this->input->post('course_group_id');
		$this->db->order_by('id');
		$class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$course."' AND  last_class='L' ");
		$data = array(
			'class_list' => $class_list,
			
		);	
		echo $this->load->view('template/getclass',$data,true);
	}
	public function center_wise_current_student_count(){

		$title = array('title' => 'Center Wise Current Student Count List');
		$this->load->view('header',$title);	
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$sql="SELECT `id`,c.`center_code`,c.`center_name`,`address`,`city`,`contactpersonname`,c.`phoneno`,c.`mobile_no_1`,c.`mobile_no_2`,COUNT(*)
		as total FROM `center` as c JOIN student as s on s.center_id=c.id and s.enrolled='Y' and course_complete='N' and new_admission_permission='N' group by center_id  order by center_code ";
		$rs = $this->db->query($sql)->result_array();
		$data['listing'] =$rs;
		$this->load->view('admin/center_wise_current_student_count',$data); 
		$this->load->view('footer');
	}

	
	public function center_wise_list_for_course(){

		$title = array('title' => 'Center Wise List for Student Master I Sem');
		$this->load->view('header',$title);	
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		 $sql="SELECT `id`,c.`center_code`,c.`center_name`,`address`,`city`,`contactpersonname`,c.`phoneno`,c.`mobile_no_1`,c.`mobile_no_2`,COUNT(*)
		as total FROM `center` as c JOIN student as s on s.center_id=c.id  and course_complete='N' and new_admission_permission='N'  and s.class_id in (193,195,197,199,201,203,205,207,209,211,213,221,223,225,227,275,279,302) and (s.new_exam_form ='Y' OR session='July 2023') group by center_id  order by center_code ";
		$rs = $this->db->query($sql)->result_array(); 
		$data['listing'] =$rs;
		$this->load->view('admin/center_wise_list_for_course',$data); 
		$this->load->view('footer');
	}


    public function remove_student_result_permission_previous(){
		
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		if($_POST['not_permitted']){
			$student_ids = (implode(',',$_POST['not_permitted']));
			$data = array('old_result_show' => 'Y');
			$where = 'student_id in ('.$student_ids.')';
			$update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('old_result_show' => 'N');
			$where ='student_id in ('.$student_ids.')';
			$update = 	$this->Common_model->updateRecordByConditions('student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/student_previous_fail/REG/'. $this->Common_model->encrypt_decrypt($_POST['course_group_id']).'/'. $this->Common_model->encrypt_decrypt($_POST['class_id']));
		}
	}


    public function student_previous_fail($mode = "",$course_id="",$class_id=""){
		
		$course_id = $this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$this->db->order_by('roll_number','ASC');
		$data['mode']= $mode;
		
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y' ,'roll_number!='=>'0', 'university_mode'=>$mode,'old_result_show'=>'Y'));//'result_show'=>'Y'
        //  $this->Common_model->last_query();
		$data['title'] = "Remaining Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
        $data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();

		$this->load->view('header',$data);
        $this->load->view('admin/student_previous_fail', $data);
        $this->load->view('footer');
		
	}


}// class
