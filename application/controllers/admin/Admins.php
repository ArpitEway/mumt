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
				$paper=$this->Common_model->getRecordByWhere('paper_master',array('id'=>$param2 ));
				$this->session->set_flashdata('ajax_flash_message','Paper Successfully Updated');
				if($this->session->account_type=="ExamController")
					$url=base_url().'/ExamController/paper/'.$paper[0]->course_group_id;
				else
					// $url=base_url().'/paper/'.$paper[0]->course_group_id;
				$url=base_url().'/paper';
				redirect($url);
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

		public function alloted_course($param1 = '',$param2 = '')
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
					$this->load->view('admin/alloted_course_to_centers',$data);
					$this->load->view('footer');
				}

			}
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


				if($class_id !=  "ALL"){	 

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
		$column_order = array(null,'id','center_code','center_name','city','contactpersonname','mobile_no_1',null,'exam_form_permission','old_session_permission');
		$column_search = array('id','center_code','center_name','city','contactpersonname','mobile_no_1','exam_form_permission','old_session_permission');
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
			$payment_by_center_permission = $result->payment_gateway_permission;	
			if($payment_by_center_permission == 'Y')
			{
			$center_payment_permission_btn = '<input type="button" name="update_payment_by_center_permission" data-id='.$result->id.' class="btn btn-success center_payment_permission_checks" value="Yes">';
			}else{
			$center_payment_permission_btn = '<input type="button" name="update_payment_by_center_permission" data-id='.$result->id.' class="btn btn-danger center_payment_permission_checks" value="No">';
			}
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
			
			$center_name=$result->center_name."<br>(Balance -<b>Rs.".$result->balance."</b>)";
			$data[] = array($i,$result->id, $result->center_code, $center_name, $result->city, $result->contactpersonname,$result->mobile_no_1,$btn,$center_payment_permission_btn,$temp_exam_form_permission_btn,$temp_admission_payment_btn,$exam_form_permission_btn,$permission_btn);
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
				$this->db->order_by('id');
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
		}elseif($txnDetails->fees_head=='Backlog Exam Fees'){
            $updateData = array('exam_form'=> 'Y'); 
        }

        if($txnDetails->fees_head=='Backlog Exam Fees'){
            $whereStudent = array('student_id'=> $student_id ,'exam_year'=>'June 2025');
		    $result = $this->Common_model->updateRecordByConditions('backlog_student',$whereStudent,$updateData);
        }else{
            $whereStudent = array('student_id'=> $student_id);
		    $result = $this->Common_model->updateRecordByConditions('student',$whereStudent,$updateData);
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

		$this->load->view('header',array('title' => 'Exam Form Status(June 2025)'));
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

		$where = array('exam_form !=' =>'D','exam_year' =>'June 2025');
		$data['permitted_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);

		$where = array('exam_form' =>'Y','exam_year' =>'June 2025');
		$data['filled_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);

		$where = array('exam_form ' =>'S','exam_year' =>'June 2025');
		$data['skipped_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);

		$where = array('exam_form' =>'N','exam_year' =>'June 2025');
		$data['not_filled_backlog_student'] = $this->Common_model->getCountByWhere('backlog_student',$where);



		$this->load->view('admin/exam_wise_student_status',$data);
		$this->load->view('footer');

	}

	public function class_wise_exam_from_status(){

		$this->load->view('header',array('title' => 'Class Wise Exam Form Status(June 2025)'));
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

		$this->load->view('header',array('title' => 'Class Wise Exam Form Status(Dec 2024)'));
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

		$this->load->view('header',array('title' => 'Class Wise Backlog Exam Form Status(Dec 2024)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		$where = array('exam_form !=' =>'D' , 'exam_year'=>'Dec 2024');
		$data['counts']=$this->Common_model->backlog_exam_form_permission_status($where);
		$this->load->view('admin/class_wise_backlog_old_exam_from_status',$data);
		$this->load->view('footer');
	}

	public function class_wise_backlog_exam_from_status(){

		$this->load->view('header',array('title' => 'Class Wise Backlog Exam Form Status(June 2025)'));
		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
		);
		//,'mode'=>'PVT'
		$where = array('exam_form !=' =>'D','exam_year'=>'June 2025');
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
		$master =  $this->Common_model->getRecordByWhere('master')[0];
		
        if($Fess_head == "Backlog Exam Fees"){
            $this->db->order_by("id",'desc');
			$where = array('student_id'=>$student_id,'exam_year'=>'June 2025');
            $student_details =  $this->Common_model->getRecordByWhere('backlog_student',$where);
        }else{
			$where = array('student_id'=>$student_id);
            $student_details =  $this->Common_model->getRecordByWhere('student',$where);
        }
		
		$course_details =  $this->Common_model->getRecordByWhere('course',array('course_group_id'=>$student_details[0]->course_group_id,'session'=>$student_details[0]->session));
		$center_id = $student_details[0]->center_id;
		$course_group_id = $student_details[0]->course_group_id;
		$remark = '';
		if($Fess_head == 'Admission Fees'){
	     $session = $student_details[0]->session;
		}else{
			$session = 'June 2025';
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
				if($Fess_head == 'Late Exam Fees' && $master->late_exam_fee_status == 'Y'){
					$exam_fees = ($student_details[0]->demo == 'Y')?$course_details[0]->exam_fees + $master->p_late_fees:$course_details[0]->exam_fees + $master->p_late_fees+$course_details[0]->program_fees;
					$remark = ($student_details[0]->demo == 'Y')?'Late Demo Exam Fees':'Late Exam Fees';
				}else if($student_details[0]->demo == 'Y'){
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->exam_fees : $course_details[0]->form_fees+$course_details[0]->admission_fees;
                }else{
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->exam_fees+$course_details[0]->program_fees : $course_details[0]->form_fees+$course_details[0]->admission_fees;
                }
                
            } 
	    }else{
           if($Fess_head!=''){
				if($Fess_head == 'Late Exam Fees' && $master->late_exam_fee_status == 'Y'){
					$exam_fees = ($student_details[0]->demo == 'Y')?$course_details[0]->p_exam_fees + $master->p_late_fees:$course_details[0]->p_exam_fees + $master->p_late_fees+$course_details[0]->p_program_fees;
					$remark = ($student_details[0]->demo == 'Y')?'Late Demo Exam Fees':'Late Exam Fees';
				}else if($student_details[0]->demo == 'Y'){
                $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->p_exam_fees : $course_details[0]->p_form_fees+$course_details[0]->p_admission_fees;
                }else{
                    $exam_fees = ($Fess_head== 'Exam Fees') ? $course_details[0]->p_exam_fees+$course_details[0]->p_program_fees : $course_details[0]->p_form_fees+$course_details[0]->p_admission_fees;
                }
		    } 
	    }
		$Fess_head = ($Fess_head == 'Late Exam Fees')?'Exam Fees':$Fess_head;
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
	

	public function student_notification_list($mode = "",$exam_pattern="M",$course_id="",$class_id="",$startlimit=0,$pagenumber=0,$department=""){
		$this->load->model('Gradesheet_tr_model');
        $this->load->model('Gradesheet_model');
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
		if($startlimit != 0){
			$start=($startlimit-1)*6000;
			$this->db->limit(6000,$start);
			$pagetitle=$startlimit;
		}
        $class_ids=array(103,106,109,112,118,121,127,130,133,136);
        $dept_ids = array(10,11,12,13,20,21,22,23,24,25,26,27,28,29,30);
        // echo $department.'ss';die;
        if($department !=""){
            $this->db->where_in('center_id', $dept_ids);
        }elseif(in_array($class_id, $class_ids)&& $pattern=="GRADE"){
        	 $this->db->where_not_in('center_id', $dept_ids);
		}
		//$this->db->where_in('roll_number', array(210710017,210712137));
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y' ,'roll_number!='=>'0', 'university_mode'=>$mode,'exam_pattern'=>$pattern));//'result_show'=>'Y','old_result_show'=>'Y',
         // $this->Common_model->last_query();
		 $data['pagenumber']=$pagenumber;
		 $data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		// $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,243,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210,244);
       

		if((in_array($class_id, $class_cbcs)) && $pattern=="GRADE"){
			$this->load->model('Gradesheet_tr_model_pg');
            $this->load->model('GradeSheet_old_model_pg');
           $this->load->view('admin/student_notification_list_pg',$data);
			
		}else{
            if($department !=""){
                $this->load->view('admin/student_notification_list_dept',$data);
            }else{
			$this->load->view('admin/student_notification_list',$data);	
            }
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
		//, 'student_id'=>705250
		$this->db->order_by('center_id','ASC');
		$this->db->order_by('roll_number','ASC');

		// $this->db->where_in('student_id',array(754811));
		// $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',$where);
		// $this->db->where('student_id',723380);
		$data['students'] = $this->Common_model->getRecordByWhere('student',$where);
		// $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		$data['pagenumber']=$pagenumber;
		$data['course_group_id'] = $course_group_id;
        $data['date_mode'] =$mode;
		$title = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] .= $title;//echo $this->db->last_query(); die;
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136);
		// $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210,243,267,244,325);
		if((in_array($class_id, $class_ids))  && $pattern!="MARKS")	//&& $mode=='REG'
		{
			$this->load->model('Gradesheet_tr_model');
            $this->load->model('Gradesheet_model');
			$this->load->view('admin/generate_gradesheet_tr',$data);
		}else if((in_array($class_id, $class_cbcs)) && $mode=='REG' && $pattern!="MARKS"){
			$this->load->model('Gradesheet_tr_model_pg');
            $this->load->model('GradeSheet_old_model_pg');
			$this->load->view('admin/generate_gradesheet_tr_pg',$data);
		}
		else if ($class_id!=168 && $class_id!=256 && $class_id!=257) {
			
			$this->load->view('admin/generate_tr',$data);
		}
		else if( $class_id==256 || $class_id==257 ) {
			
			$this->load->view('admin/generate_tr_sessional',$data);
		}
		else{
			$this->load->view('admin/generate_tr_mom',$data);
		}

	}

	public function backlog_generate_tr($mode="",$exam_pattern="M",$course_group_id="",$class_id="",$startlimit=0,$pagenumber=0){
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
		
		$where =array("bs.course_group_id"=>$course_group_id ,'bs.class_id' => $class_id ,'bs.exam_form'=>'Y', 'bs.roll_no!='=>'0' ,'bs.mode'=> $mode,'bs.exam_year'=>'June 2025', 's.exam_pattern'=>$pattern);
		//,'bs.student_id'=>734487
		$this->db->order_by('bs.center_id','ASC');
		$this->db->order_by('bs.roll_no','ASC');
		
		// $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',$where);	
        $this->db->select('bs.*');
        $this->db->from('backlog_student as bs');
        $this->db->join('student as s', 's.student_id=bs.student_id');
        $this->db->where($where);
        $data['students'] = $this->db->get()->result();
        // $this->Common_model->getRecordByWhere('backlog_student',$where);
		// print_r($data['students']);die;
		//  $this->Common_model->last_query();
		$data['class_id'] = $class_id;
		$data['pagenumber']=$pagenumber;
		$data['course_group_id'] = $course_group_id;
		$title = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] .= $title;//echo $this->db->last_query(); die;
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210);
		if((in_array($class_id, $class_ids)) && $pattern!="MARKS")	
		{
			$this->load->model('Gradesheet_backlog_tr_model');
			$this->load->view('admin/generate_backlog_gradesheet_tr',$data);
		}else if((in_array($class_id, $class_cbcs)) && $mode=='REG' && $pattern!="MARKS"){
			$this->load->model('Gradesheet_backlog_tr_model_pg');
			$this->load->model('GradeSheet_old_model_pg');
			$this->load->view('admin/generate_backlog_gradesheet_tr_pg',$data);
		}
		else if ($class_id!=168) {
			$this->load->view('admin/backlog_generate_tr',$data);
		}else{
			$this->load->view('admin/backlog_tr_mom',$data);
		}

	}

    public function backlog_generate_tr_bed($mode="",$exam_pattern="M",$course_group_id="",$class_id=""){
		// $this->db->order_by('center_id,roll_number','ASC');
		
		// $data['students'] = $this->Common_model->getRecordByWhere('student',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'class_id' => $class_id ,'new_exam_form'=>'Y','roll_no!='=>'0' ));

		// // $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'old_class_id' => $class_id ,'exam_form'=>'Y','roll_number!='=>'0' ));
		// //'result_show' => 'N' ,'student_id'=>'685381'
        if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}
        $where =array("bs.course_group_id"=>$course_group_id ,'bs.class_id' => $class_id ,'bs.exam_form'=>'Y', 'bs.roll_no!='=>'0' ,'bs.mode'=> $mode,'bs.exam_year'=>'June 2025','exam_pattern'=>$pattern);
		//,'student_id'=>702823
		$this->db->order_by('bs.center_id','ASC');
		$this->db->order_by('bs.roll_no','ASC');
		
		// $data['students'] = $this->Common_model->getRecordByWhere('student_result_aug_22',$where);
		
		// $data['students'] = $this->Common_model->getRecordByWhere('backlog_student',$where);
        $this->db->select('bs.*');
        $this->db->from('backlog_student as bs');
        $this->db->join('student as s', 's.student_id=bs.student_id');
        $this->db->where($where);
        $data['students'] = $this->db->get()->result();
		$data['class_id'] = $class_id;
		$data['course_group_id'] = $course_group_id;
		$data['title'] = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		// $this->load->view('admin/generate_tr/header2',array('title' =>$title));

		// if($class_id == '110' || $class_id == '119' || $class_id == '131')
		$class_ids=array(110,119,125,128,131,111,126,129,132,112,121,127,130,133);
		if(in_array($class_id, $class_ids) && $pattern !="MARKS")		
		{
			$this->load->model('Gradesheet_backlog_tr_model');
			$this->load->model('Gradesheet_model');
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

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		
		// $where = "id in (select distinct(course_group_id) from student where exam_form = 'Y' and old_class_id in (154,155,181,182,193,217,195,197,231,199,233,201,235,203,237,205,239,241,209,243,211,245,213,215,302,304,275,277,279,281,221,247,223,249,225,251,227,253) )  ";
		
		// $where = "id in (select distinct(course_group_id) from student where exam_form = 'Y' )";

        $where = "id in (select distinct(course_group_id) from student where exam_form = 'Y'  )";

		// and class_id in (298,112,136,256,258,260,317)
        // and class_id in (103,194,196,198,200,202,204,206,210,212,214,303,276,280,222,224,226,228,285,291,121,296,284,311)
        // and class_id in (155,168,182,283,287,289,293,310,295,297,264)
        // 155,182,264,168,289,287,310,293,283,297,295
        // 218,232,234,236,238,240,242,244,246,216,305,278,282,248,250,252,254
		// 193,217,197,231,203,237,211,275,277,279,281,221,247,223,249,225,251,263 
		// 245,199,233,201,235,241,227,253,154,181
		// 155,182,205,239,213,302,304
    
		$data['courses'] = $this->Common_model->get_record('course_group','*',$where);
		$this->load->view('header',array('title' => 'Class List'));
		$this->load->view('admin/tr_class_list',$data);
		$this->load->view('footer');
	}

	public function backlog_tr_class_list(){
		$where = "id in (select distinct(course_group_id) from backlog_student where exam_form = 'Y' and exam_year='June 2025' and class_id in (121,198,240,276,280,282,224,264,273,300,301,206))";
		// new_exam_form = 'Y' or student_result_aug_22
		// and class_id in (101,104,105,108,110,119,125,126,128,131,137,154,155,168,175,177,181,182,191,198,204,206,208,246,214,276,224,250,226,252,228,262,273) 
		// 
		
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
			$data = array('old_result_show' => 'Y','result_show' => 'Y');
			$where = " student_id in (".$student_ids.")";//provisional_remark in ('','N') &&
			$update =$this->Common_model->updateRecordByConditions('student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('old_result_show' => 'N','result_show' => 'N');
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

	public function student_marksheet_grade($mode="",$course_id="",$class_id="",$startlimit=0,$department="")
	{
		$data = array('class_id' => $class_id,'course_group_id' =>$course_id );
				$start=0;
				
		// 'enrollment_no'=>'AG/21220737'
		// ,'enrollment_no'=>'AG/21200364'
		$title = "Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
		$class = $this->Common_model->getRecordByID('class_master','id',$class_id);
        $dept_ids = array(10,13,20,21,22,23,24,25,26,27,28,29,30);//11,12
        $class_ids=array(103,106,109,112,118,121,127,130,133,136);
		if($startlimit!=0){
			$start=($startlimit-1)*1000;
			$this->db->limit(1000,$start);
			$pagetitle=$startlimit;
		}	
	//	$this->db->where_in('roll_number',array(210414167,210414296,210416271,210416325,210417759,210420469,210412133,210420009,210420534,210412125,210413275,210417990));
		//$this->db->where_in('roll_number',array(210412125,210413275,210417990,210412133,210420009,210420534));
		// $this->db->limit(1);
		
		$title .= ($startlimit!=0) ? ' Part - '.$pagetitle : '';
		$data['title'] = $title;
		$data['university_mode'] = $mode;
		// $this->load->model('Gradesheet_model');
		

		if($class->last_class == 'L' && $department ==""){
          
            // if((in_array($class_id, $class_ids))){
            //     $this->db->where_not_in('center_id',$dept_ids);
            // }
			$this->db->where_in('center_id',array(11,12));
			$this->db->order_by('center_id,roll_number','ASC'); 
			$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y','roll_number!='=>'0','course_complete'=>'Y','university_mode'=>$mode,'old_result_show'=>'Y','exam_pattern'=>'GRADE' ));
			
		}else{
			$this->db->order_by('center_id,roll_number','ASC');
			// $this->db->limit(1);
          if((in_array($class_id, $class_ids))){
              $this->db->where_in('center_id',$dept_ids);
          }
		  
		//   $this->db->where_in('student_id',array(337712,374302,377327,380891,394291,685337,686217,688744,688749,688753,689058,689220,689438,689877,690471,690929,691044,691654,691737,691745,692014,693191,693569,693783,694640,694810,695073,695142,695157,695192,695361,696622,696629,697270,697973,699159,699404,699496,699598,699786,699903,700030,700759,700869,702218,702279,702344,702459,702916,703004,703066,703113,703227,703322,703459,703514,703542,703560,703639,703730,703833,703873,703900,703909,703952,704084,704187,704230,704281,704291,704454,704520,704570,704803,704826,704840,704956,704989,705147,705181,705378,705597,705862,705915,706014,706126,706308,706309,706392,706398,706449,706507,706576,706579,706771,706824,706929,706950,706975,707382,707433,707454,707493,707729,707767,707785,707806,707846,707858,707946,708014,708191,708216,708229,708231,708236,708250,708326,708343,708344,708410,708519,708528,708533,708537,708545,708602,708613,708637,708661,708673,708945,709065,709170,709300,709327,709442,709622,709671,709841,709877,709961,709964,710016,710438,710456,710466,710580,710593,710610,710736,710743,710836,710844,710974,711102,711162,711256,711389,711408,711487,711538,711632,711668,711714,711746,711757,711765,711775,711885,711940,712053,712179,712235,712257,712291,712292,712294,712383,712422,712564,712578,712580,712582,712584,712739,712767,712770,712826,712851,712896,712977,713039,713138,713156,713295,713374,713441,713579,713652,713668,713887,713908,714056,714107,714127,714199,714207,714298,714410,714411,714461,714718,714922,714934,715113,715140,715176,715290,715345,715347,715407,715439,715563,715628,715643,715707,715771,715773,715858,715874,715888,716057,716066,716151,716156,716198,716229,716253,716316,716343,716381,716448,716467,716726,716727,716888,716996,717085,717092,717096,717123,717266,717283,717320,717364,717556,717655,717737,717841,717890,717915,718076,718192,718198,718314,718362,718398,718681,718836,718957,718980,719028,719139,719159,719288,719472,719533,719535,719562,719664,719734,719740,719769,719845,719991,720050,720140,720157,720213,720221,720362,720489,720636,720759,720854,720857,720968,720999,721015,721134,721335,721340,721373,721409,721427,721467,721494,721565,721663,721698,721711,721731,721760,721768,721849,721853,721918,722015,722112,722180,722221,722230,722288,722295,722452,722525,722554,722650,722718,722855,722960,723043,723153,723330,723370,723462,723500,723523,723607,723639,723650,723675,723704,723737,723769,723772,723849,723959,724021,724139,724140,724184,724193,724238,724264,724391,724430,724458,724545,724566,724571,724628,724659,724705,725088,725102,725147,725174,725182,725199,725200,725245,725260,725265,725426,725427,725428,725435,725545,725546,725577,725712,725924,726013,726132,726298,726304,726363,726429,726476,726968,726993,727072,727131,727288,727297,727301,727327,727362,727437,727489,727570,727644,727655,727705,727707,727814,727893,727915,727930,727996,728130,728145,728256,728270,728353,728419,728445,728700,728722,728746,728795,728801,728859,728993,729074,729083,729099,729104,729110,729125,729142,729194,729254,729272,729298,729314,729319,729326,729334,729376,729666,729801,729804,729875,729916,730097,730106,730112,730126,730152,730193,730203,730363,730412,730449,730467,730484,730490,730521,730534,730537,730630,730678,730699,730709,730716,730738,731044,731106,731128,731143,731189,731223,731247,731270,731289,731337,731360,731392,731399,731472,731480,731550,731589,731595,731649,731741,731801,731881,731885,731887,731929,731971,732003,732033,732046,732104,732132,732189,732227,732288,732290,732305,732314,732335,732356,732410,732418,732433,732436,732513,732553,732584,732628,732648,732663,732669,732746,732803,732836,732995,733003,733040,733051,733141,733146,733186,733228,733232,733254,733279,733305,733326,733384,733408,733425,733434,733447,733483,733514,733557,733633,733665,733684,733710,733772,733801,733878,733947,733991,734100,734108,734157,734162,734195,734208,734268,734281,734353,734361,734409,734486,734497,734606,734738,734797,734799,734844,734845,734874,735020,735049,735072,735102,735228,735233,735239,735363,735455,735483,735496,735526,735573,735642,735655,735720,735759,735850,735859,735888,735891,735916,735959,735984,735985,736019,736020,736034,736059,736088,736175,736177,736203,736221,736258,736292,736314,736323,736405,736440,736450,736477,736489,736503,736613,736678,736746,736829,736854,736877,736904,736907,736910,736917,736935,736945,736951,737019,737020,737024,737026,737038,737075,737107,737108,737210,737263,737423,737488,737499,737500,737540,737626,737641,737654,737660,737680,737693,737714,737741,737763,737816,737832,737840,737846,737895,737941,737967,738073,738107,738178,738186,738224,738255,738263,738274,738310,738311,738372,738381,738405,738475,738525,738549,738582,738585,738652,738654,738658,738708,738726,738743,738750,738792,738820,739008,739017,739048,739049,739141,739208,739211,739213,739246,739253,739317,739355,739373,739395,739397,739423,739428,739458,739579,739606,739665,739669,739818,739826,739926,740019,740044,740054,740070,740116,740127,740143,740275,740360,740387,740390,740416,740452,740455,740456,740460,740512,740520,740524,740527,740563,740591,740663,740676,740747,740820,740863,740887,740967,741006,741016,741064,741098,741131,741186,741191,741249,741263,741312,741471,741535,741587,741598,741604,741646,741649,741656,741658,741668,741671,741686,741707,741835,741922,741998,742000,742002,742008,742010,742025,742051,742058,742092,742100,742107,742115,742143,742164,742183,742187,742191,742225,742282,742306,742308,742362,742374,742413,742462,742478,742490,742513,742559,742564,742565,742595,742644,742647,742653,742689,742780,742817,742877,742911,742916,742918,743128,743153,743183,743261,743299,743324,743331,743338,743349,743389,743409,743446,743495,743504,743517,743632,743650,743655,743695,743716,743762,743778,743782,743785,743793,743924,743927,743928,744023,744089,744112,744116,744132,744133,744171,744246,744274,744310,744326,744399,744417,744455,744511,744526,744532,744534,744614,744807,744865,744880,744892,744928,744953,744959,745024,745038,745056,745092,745115,745165,745284,745292,745370,745371,745414,745424,745561,745599,745658,745690,745704,745826,745849,745852,745894,745911,745924,745940,745944,745970,745973,745983,745986,746037,746085,746118,746124,746167,746225,746245,746250,746261,746314,746315,746360,746364,746376,746391,746392,746410,746436,746475,746478,746480,746505,746555,746633,746665,746744,746793,746808,746941,746959,746971,746981,746987,746998,747030,747038,747041,747087,747122,747161,747186,747195,747257,747264,747381,747383,747390,747394,747429,747509,747526,747652,747683,747688,747776,747823,747831,747833,747896,747940,747945,747969,747973,747979,747993,747996,748080,748087,748129,748141,748162,748229,748234,748248,748264,748318,748320,748364,748375,748379,748406,748432,748451,748516,748531,748575,748612,748696,748736,748737,748754,748758,748765,748858,748899,748937,748965,748970,748988,748990,749014,749069,749095,749184,749217,749249,749267,749335,749350,749360,749387,749462,749503,749518,749604,749607,749618,749624,749695,749712,749772,749799,749927,749962,750015,750083,750088,750097,750107,750113,750123,750124,750133,750144,750161,750219,750244,750337,750499,750546,750581,750761,750775,750802,750916,750938,750994,751020,751047,751095,751105,751220,751223,751270,751279,751282,751298,751409,751432,751440,751462,751530,751592,751638,751711,751843,751970,752014,752052,752141,752189,752221,752226,752328,752387,752414,752470,752479,752515,752578,752613,752682,752741,752744,752797,752834,752890,752923,753055,753132,753288,753323,753405,753455,753461,753463,753650,753676,753739,753751,753819,753871,753922,753942,754001,754014,754017,754230,754236,754245,754301,754305,754312,754315,754385,754392,754415,754416,754448,754462,754470,754477,754555,754641,754751,754753,754767,754769,754786,754799,754808,754836,754842,754864,754874,755044,755072,755128,755136,755143,755364,755510,755521,755523,755682,755806,755814,755909,755914,755963,755988,756028,756057,756087,756118,756124,756289,756297,756320,756326,756333,756340,756372,756379,756408,756509,756560,756567,756783,756801,756813,756863,757058,757094,757110,757282,757327,757405,757458,757463,757469,757502,757504,757517,757539,757544,757617,757618,757633,757659,757668,757680,757700,757788,757817,757849,757898,757914,757940,757977,758082,758107,758170,758181,758316,758441,758499,758572,758602,758603,758633,758789,758798,758844,758893,758929,758950,758953,758971,759023,759045,759073,759140,759202,759214,759273,759307,759320,759328,759338,759371,759424,759429,759436,759448,759490,759671,759677,759731,759733,759745,759775,759960,759994,760029,760043,760110,760140,760144,760149,760239,760244,760342,760365,760377,760399,760418,760492,760493,760514,760517,760524,760530,760724,760781,760789,760810,760825,760849,760853,760858,760883,760923,760924,761022,761044,761053,761108,761148,761149,761151,761154,761155,761190,761255,761276,761330,761343,761436,761495,761516,761546,761556,761563,761592,761673,761679,761688,761699,761731,761754,761886,761909,761913,761916,761976,761987,761989,762021,762025,762059,762102,762116,762142,762222,762245,762269,762277,762282,762363,762418,762421,762486,762495,762759,762778,762802,762890,762909,762946,762954,763009,763067,763131,763196,763217,763285,763290,763294,763320,763372,763374,763403,763434,763455,763460,763464,763469,763482,763486,763488,763544,763549,763653,763663,763676,763696,763702,763703,763758,763761,763795,763797,763805,763825,763842,763989,764090,764093,764129,764193,764194,764198,764352,764360,764366,764456,764479,764497,764510,764637,764659,764678,764777,764804,764862,764971,764991,765049,765098,765156,765197,765227,765262,765388,765434,765472,765576,765594,765660,765688,765698,765703,765713,765714,765716,765727,765773,765780,765785,765932,765990,766039,766069,766074,766096,766101,766211,766263,766289,766389,766405,766682,766763,766766,766783,767025,767039,767057,767067,767227,767290,767303,767399,767405,767458,767529,767534,767714,767761,767846,767867,767871,767879,767888,767901,767960,767962,768017,768121,768122,768123,768150,768153,768159,768169,768206,768244,768270,768330,768337,768342,768359,768377,768390,768392,768414,768530,768559,768565,768566,768573,768577,768611,768628,768713,768734,768823,768832,768911,769108,769111,769149,769151,769162,769238,769242,769251,769319,769476,769492,769495,769552,769572,769619,769634,769654,769724,769760,769824,769845,769913,769972,770001,770022,770030,770053,770069,770075,770092,770105,770165,770183,770192,770204,770237,770242,770316,770334,770413,770469,770501,770583,770589,770595,770720,770736,770756,770855,770950,770952,771038,771054,771064,771113,771145,771231,771238,771273,771313,771315,771346,771348,771447,771498,771509,771531,771539,771559,771675,771680,771685,771842,771870,771874,771911,771918,771967,772031,772036,772141,772189,772194,772217,772226,772229,772231,772289,772305,772323,772331,772332,772333,772380,772455,772585,772702,772708,772756,772839,772877,772882,772929,772933,772961,773064,773117,773144,773151,773180,773218,773304,773370,773400,773466,773477,773547,773637,773650,773746,773751,773790,773807,773828,773870,773906,773911,773951,773966,774119,774140,774300,774307,774315,774328,774437,774451,774613,774660,774695,774709,774744,774775,774804,774871,774923,774927,774945,774971,774974,774981,774982,775083,775089,775092,775115,775135,775142,775157,775280,775360,775411,775544,775552,775672,775696,775749,775779,775791,775812,775829,775863,775876,775894,775898,775908,776105,776117,776143,776147,776224,776237,776246,776318,776340,776370,776453,776461,776553,776768,776782,776872,777003,777229,777438,777443,777723,777827,778107,778219,778249,778253,778258,778268,778308,778547,778599,778600,778614,778627,778638,778645,778649,778651,778690,778730,778745,778777,778827,778833,778848,778866,778889,778944,779058,779151,779301,779312,779318,779385,779389,779461,779495,779514,779572,779581,779804,779807,779830,779835,779865,779866,779868,779877,779919,779965,780024,780078,780089,780096,780108,780119,780124,780172,780182,780198,780211,780212,780296,780305,780313,780338,780357,780375,780376,780424,780425,780455,780470,780484,780515,780530,780558,780630,780695,780720,780730,780759,780810,780822,780856,780870,780919,780958,780962,780967,780978,781087,781113,781230,781305,781360,781394,781395,781422,781424,781578,781628,781650,781653,781689,781702,781709,781813,781854,782006,782061,782074,782090,782167,782206,782225,782306,782311,782315,782329,782340,782344,782428,782451,782474,782487,782529,782531,782612,782629,782633,782692,782713,782786,782799,782806,782822,782836,782854,782863,782890,782918,782954,782971,782994,783012,783030,783066,783067,783084,783109,783127,783150,783151,783158,783201,783228,783309,783371,783420,783448,783495,783497,783526,783530,783565,783662,783679,783710,783738,783834,783843,783849,783856,783939,783940,783943,783996,784029,784139,784151,784153,784267,784300,784320,784404,784489,784537,784540,784542,784606,784621,784685,784704,784764,784888,784929,784944,784950,784994,785103,785105,785111,785118,785124,785128,785160,785181,785209,785230,785279,785321,785365,785374,785379,785398,785417,785465,785487,785492,785546,785576,785585,785592,785664,785688,785700,785768,785772,785819,785833,785835,785883,785894,786019,786028,786035,786050,786066,786115,786136,786140,786165,786202,786252,786351,786393,786478,786509,786518,786562,786564,786567,786643,786660,786722,786731,786743,786767,786885,786959,786993,787072,787078,787121,787164,787165,787166,787207,787228,787234,787238,787283,787332,787394,787400,787405,787452,787501,787516,787539,787544,787579,787589,787591,787596,787645,787673,787683,787690,787699,787711,787722,787729,787769,787784,787801,787806,787817,787850,787861,787866,787879,787909,787990,788009,788081,788140,788243,788244,788250,788258,788259,788264,788271,788341,788379,788386,788391,788392,788402,788410,788476,788480,788519,788532,788536,788540,788584,788593,788630,788636,788671,788727,788759,788771,788801,788860,788887,788896,788909,788915,788943,788988,789014,789046,789051,789074,789096,789135,789167,789191,789209,789289,789368,789369,789396,789463,789606,789668,789708,789721,789727,789734,789750,789766,789782,789794,789821,789846,789852,789875,789911,789936,789943,789954,790005,790044,790059,790144,790156,790162,790172,790206,790209,790241,790247,790253,790266,790269,790276,790302,790332,790334,790354,790357,790385,790395,790424,790429,790442,790453,790476,790478,790483,790486,790567,790580,790634,790648,790693,790732,790797,790809,790846,790894,790935,791012,791016,791036,791061,791106,791110,791175,791176,791217,791272,791287,791386,791392,791420,791434,791481,791499,791504,791512,791552,791586,791669,791671,791707,791720,791722,791762,791781,791795,791797,791834,791868,791914,791980,791987,791998,792009,792127,792141,792170,792176,792178,792207,792216,792221,792231,792296,792303,792352,792379,792452,792472,792594,792659,792677,792690,792706,792767,792840,792858,792866,792915,792921,792982,793003,793020,793034,793066,793084,793100,793168,793230,793255,793267,793268,793274,793338,793385,793389,793426,793432,793444,793455,793490,793544,793566,793611,793617,793623,793652,793665,793672,793679,793685,793714,793751,793773,793821,793857,793863,793881,793904,793909,793923,793943,793987,794006,794009,794022,794027,794052,794068,794109,794168,794225,794256,794278,794319,794360,794384,794410,794434,794447,794490,794525,794526,794547,794566,794583,794584,794612,794624,794681,794713,794798,794803,794824,794873,794900,794958,795019,795025,795045,795081,795090,795203,795214,795260,795286,795294,795311,795334,795356,795362,795381,795386,795432,795478,795551,795566,795567,795649,795675,795697,795700,795785,795807,795845,795868,795887,795892,795949,795997,796029,796079,796132,796148,796198,796270,796282,796290,796306,796308,796321,796328,796350,796388,796404,796405,796471,796474,796478,796490,796500,796542,796562,796611,796634,796717,796800,796834,796845,796876,796887,796984,796995,797039,797056,797090,797156,797231,797270,797278,797302,797303,797412,797491,797514,797674,797692,797706,797712,797743,797770,797773,797786,797810,797855,797861,797874,797888,797900,797905,797919,797979,797994,798033,798101,798123,798128,798182,798191,798213,798221,798225,798277,798293,798396,798458,798494,798506,798523,798548,798560,798583,798602,798611,798630,798643,798656,798662,798669,798694,798711,798735,798740,798764,798783,798825,798836,798839,798847,798871,798931,798969,799001,799018,799084,799106,799109,799156,799161,799191,799208,799234,799296,799342,799360,799396,799476,799478,799510,799543,799561,799564,799603,799609,799613,799634,799690,799728,799738,799762,799776,799804,799817,799863,799872,799957,799959,799993,800011,800052,800068,800094,800101,800107,800132,800139,800152,800182,800217,800230,800252,800346,800371,800447,800504,800510,800539,800541,800563,800589,800615,800643,800663,800671,800685,800686,800723,800735,800772,800779,800787,800803,800848,800965,801065,801090,801092,801096,801148,801159,801184,801228,801291,801308,801355,801379,801393,801414,801428,801441,801457,801485,801507,801515,801525,801540,801594,801597,801604,801650,801657,801660,801716,801753,801769,801811,801821,801837,801914,801946,801958,801977,802054,802084,802151,802251,802286,802335,802348,802365,802377,802382,802386,802403,802469,802499,802539,802570,802624,802625,802731,802742,802790,802812,802864,802879,802899,802903,802906,802925,802939,802949,802984,803003,803029,803040,803070,803076,803127,803131,803148,803149,803183,803209,803211,803219,803245,803268,803362,803490,803511,803549,803578,803663,803688,803704,803725,803908,803922,803948,803949,803960,803968,803970,803977,804042,804057,804060,804073,804091,804104,804236,804244,804258,804266,804343,804434,804445,804490,804491,804505,804538,804568,804573,804580,804581,804593,804651,804660,804678,804679,804685,804705,804751,804783,804797,804807,804863,804894,804899,804906,804921,804925,804936,804938,804943,804981,805039,805048,805050,805077,805081,805169,805232,805237,805318,805340,805374,805384,805389,805390,805400,805410,805428,805483,805486,805491,805503,805542,805544,805554,805566,805593,805604,805662,805692,805710,805718,805745,805773,805877,805887,805888,805907,805927,805932,805944,805959,805962,805977,805984,806017,806051,806052,806059,806110,806154,806180,806189,806193,806210,806216,806266,806290,806311,806312,806396,806411,806413,806430,806468,806469,806475,806491,806506,806533,806535,806539,806555,806580,806590,806595,806599,806613,806623,806636,806641,806654,806669,806744,806759,806767,806778,806783,806784,806796,806804,806816,806828,806829,806832,806838,806846,806866,806874,806875,806890,806905,806933,806934,806937,806941,806949,806959,806970,806977,807026,807111,807127,807148,807169,807172,807186,807239,807284,807293,807297,807348,807359,807369,807371,807383,807415,807421,807425,807453,807471,807473,807480,807492,807528,807533,807537,807553,807561,807590,807602,807609,807624,807625,807641,807708,807724,807816,807921,807922,807941,807943,807987,807997,808030,808074,808081,808122,808129,808149,808183,808189,808220,808292,808298,808319,808362,808384,808390,808398,808421,808438,808442,808450,808453,808527,808553,808570,808579,808590,808657,808665,808684,808701,808765,808769,808791,808823,808833,808834,808862,808947,808957,808977,809025,809079,809095,809098,809107,809122,809135,809195,809220,809300,809317,809332,809336,809382,809385,809394,809415,809426,809431,809435,809436,809548));
		//   $this->db->where_not_in('student_id',array(718952,764084,777807,779525,798277));
		  
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y','roll_number!='=>'0','university_mode'=>$mode,'old_result_show'=>'Y','exam_pattern'=>'GRADE'));
		}
		// $this->Common_model->last_query();
	 	// if($class->internal=="Y" && $mode!="PVT"){
			$class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,243,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210,267,244);
			if(in_array($class_id , $class_cbcs))
			{
                $this->load->model('GradeSheet_old_model_pg');
                $this->load->model('Gradesheet_model_pg');
				if($class_id==267){
					$this->load->view('admin/student_marksheet_grade_bped',$data);
				}else{
					$this->load->view('admin/student_marksheet_grade_pg',$data);
				}
			}else{
				$this->load->model('Gradesheet_model');
                if($department !=""){
                    $this->load->view('admin/student_marksheet_grade_dept',$data);
                }else{
				$this->load->view('admin/student_marksheet_grade1',$data);
                 }
				
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
            $this->db->where(array("bs.course_group_id"=>$course_id ,'bs.class_id' => $class_id,'bs.exam_form'=>'Y','bs.roll_no!='=>'0','mode'=>$mode,'st.course_complete'=>'Y','st.exam_pattern'=>"GRADE",'bs.exam_year'=>'Dec 2024' ));
            $data['students']=  $this->db->get()->result();
            // $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id,'exam_form'=>'Y','roll_no!='=>'0','mode'=>$mode ));
		}else{
			$this->db->order_by('bs.center_id,bs.roll_no','ASC');
            $this->db->select('bs.*, st.photo,st.session,st.course_name,st.name,st.f_h_name');
            $this->db->from('backlog_student as bs');
            $this->db->join('student as st','st.student_id=bs.student_id');
            $this->db->where(array("bs.course_group_id"=>$course_id ,'bs.class_id' => $class_id,'bs.exam_form'=>'Y','bs.roll_no!='=>'0','mode'=>$mode,'bs.result_show'=>'Y','st.exam_pattern'=>"GRADE",'bs.exam_year'=>'Dec 2024' ));
            $data['students']=  $this->db->get()->result();
			
			// $this->db->limit(1);
			//  $this->db->where('student_id = "721275"');
		// $data['students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id,'exam_form'=>'Y','roll_no!='=>'0','mode'=>$mode));
		}
		// $this->Common_model->last_query();
	 	// if($class->internal=="Y" && $mode!="PVT"){
			$class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,210);
			if(in_array($class_id , $class_cbcs))
			{
                $this->load->model('GradeSheet_old_model_pg');
                $this->load->model('Gradesheet_backlog_model_pg');
                $this->load->view('admin/backlog_student_marksheet_grade_pg',$data);
			}else{
                $this->load->model('Gradesheet_model');
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
		 $this->db->where_in('student.old_class_id',array(103,194,196,198,200,202,204,206,210,212,214,303,276,280,222,224,226,228,121,112,136,109,127,130,133,106));
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
		$where=array('student.student_id'=>$student_id,'paper_master.sub_group_id !='=>1,'new_exam_form.class_id'=>$class_id);
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
		// $this->db->where('university_mode','PVT');
		$this->db->where_in('student.old_class_id',[103,194,196,198,200,202,204,206,210,212,214,303,276,280,222,224,226,228,121,112,136,109,127,130,133,106]);
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
		$this->db->join('student', 'student.student_id = new_exam_form.student_id and student.old_class_id=new_exam_form.class_id');
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
		
		$data['students'] = $this->Common_model->getRecordByWhere('student',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'old_class_id' => $class_id ,'exam_form'=>'Y','roll_number!='=>'0' ,'exam_pattern'=> $pattern));
		//  $data['students'] = $this->Common_model->getRecordByWhere('student',array("university_mode"=>$mode,"course_group_id"=>$course_group_id ,'old_class_id' => $class_id ,'exam_form'=>'Y','roll_number!='=>'0' ,'exam_pattern'=> $pattern));

		//'result_show' => 'N' ,'student_id'=>'685381'
		$data['class_id'] = $class_id;
		$data['course_group_id'] = $course_group_id;
        $data['date_mode'] = $mode;
		$data['title'] = "TR ".$this->Common_model->getCourseNameByCourseId($course_group_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		// $this->load->view('admin/generate_tr/header2',array('title' =>$title));

		// if($class_id == '110' || $class_id == '119' || $class_id == '131')
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136);
		if(in_array($class_id, $class_ids))		
		{
			$this->load->model('Gradesheet_tr_model');
            $this->load->model('Gradesheet_model');
			$this->load->view('admin/generate_tr/practical_internal_tr',$data);
		}else{
			$this->load->view('admin/generate_tr/bed_tr',$data);
		}
		
		// $this->load->view('admin/generate_tr/footer2');
	}

	public function student_notification_list_bed($mode="",$exam_pattern="M",$course_id="",$class_id="",$department=""){
		// echo $mode;die;
		$this->load->model('Gradesheet_tr_model');
        $this->load->model('Gradesheet_model');
        if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}
		$course_id=$this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$this->db->order_by('roll_number','ASC');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
        $class_ids=array(103,106,109,112,118,121,127,130,133,136);
        $dept_ids = array(10,11,12,13,20,21,22,23,24,25,26,27,28,29,30);
        // echo $department.'ss';die;
        if($department !=""){
            $this->db->where_in('center_id', $dept_ids);
        }elseif(in_array($class_id, $class_ids)&& $pattern=="GRADE"){
            $this->db->where_not_in('center_id', $dept_ids);
        }
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id, 'old_class_id' => $class_id, 'exam_form'=>'Y','roll_number!='=>'0','university_mode'=>$mode ,'exam_pattern'=>$pattern));
		$data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$data['mode'] = $mode;
        // $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,267);
        
        if((in_array($class_id, $class_cbcs)) && $pattern=="GRADE"){
			$this->load->model('Gradesheet_tr_model_pg');
			$this->load->view('admin/student_notification_list_pg',$data);
		}else{
            if($department !=""){
                $this->load->view('admin/student_notification_list_bed_dept',$data);
            }else{
                $this->load->view('admin/student_notification_list_bed',$data);
			
            }

        }
	}

    public function backlog_student_notification_list_bed($mode="",$exam_pattern="M",$course_id="",$class_id=""){
		// echo $mode;die;
        if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}
		$this->load->model('Gradesheet_backlog_tr_model');
		$course_id=$this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id=$this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$this->db->order_by('roll_no','ASC');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
        $this->db->select('bs.*,s.exam_pattern');
        $this->db->from('backlog_student as bs');
        $this->db->join('student as s', 's.student_id = bs.student_id');
        $this->db->where(array("bs.course_group_id"=>$course_id ,'bs.class_id' => $class_id,'bs.exam_form'=>'Y' ,'bs.roll_no!='=>'0', 'bs.mode'=>$mode,'bs.exam_year'=>"Dec 2024", 's.exam_pattern'=>$pattern));
        $data['students']= $this->db->get()->result();
        $data['pattern']= $pattern;
		// $data['students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id, 'class_id' => $class_id, 'exam_form'=>'Y','roll_no!='=>'0','mode'=>$mode,'exam_year'=>"June 2024" ));
		$data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		$data['mode'] = $mode;
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
        if((in_array($class_id, $class_cbcs)) && $pattern=="GRADE"){
			$this->load->model('Gradesheet_backlog_tr_model_pg');
			$this->load->view('admin/backlog_student_notification_list_pg',$data);
		}else{
            $this->load->view('admin/backlog_student_notification_list_bed',$data);
        }
		
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


		$query = $this->db->query("SELECT p.* FROM `paper_master` as p join class_master as c on c.id=p.class_id WHERE  type='Theory' and cbcs_paper=cbcs and (exam_date!='0000-00-00' and ( c.exam_form_permission='Y' or c.backlog_exam_form_permission='Y')) order by p.course_group_id,class_id,cbcs_paper,paper_no asc");

		 // and c.id in (137,149,183,185,191,138,184,192,187,143,146,139,144,188,145,147,148,150,186,141,151,142,190)
		 
		// and `exam_date`!='0000-00-00' and cbcs_paper=cbcs  

		// Private Only 104,107,134 
		// $query = $this->db->query("SELECT p.* FROM `paper_master` as p join class_master as c on c.id=p.class_id WHERE  type='Theory' and cbcs_paper=cbcs and (pvt_exam_date!='0000-00-00' and ( c.exam_form_permission='Y' or c.backlog_exam_form_permission='Y')) order by p.course_group_id,class_id,cbcs_paper,paper_no asc");

		// $query = $this->db->query("SELECT p.* FROM `paper_master` as p join class_master as c on c.id=p.class_id WHERE  type='Theory'   and cbcs_paper=cbcs and (exam_date!='0000-00-00' or ( c.exam_form_permission='Y' or c.backlog_exam_form_permission='Y'))  order by p.course_group_id,class_id,cbcs_paper,paper_no asc");

		//`class_id` in (195,197,199,201,203,205,209,211,213,221,223,225,227,275,279) and  `exam_date`='0000-00-00' and old_exam_date!='0000-00-00'

		// $query = $this->db->query("SELECT p.* FROM `paper_master` as p join class_master as c on c.id=p.class_id WHERE type='Theory' and `class_id` in (101,154,172,181,194,198,202,204,206,212,214,216,218,222,224,226,228,232,236,238,240,246,248,250,252,254,300)  order by p.course_group_id,class_id,cbcs_paper,paper_no asc");

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
			// $this->db->where('exam_date!=',"");
			// $this->db->where_not_in('class_id',array("104","107","134"));
			$this->db->where('exam_date!=',"0000-00-00");	
			//$this->db->where('exam_date',"2024-07-23");	
			$this->db->group_by(array('exam_date','exam_shift'));
			$this->db->order_by('exam_date', "asc");
			$this->db->order_by('exam_shift', "desc");
			$data['examDate'] = $this->db->get()->result();

			//

				$this->db->select('*');
			$this->db->from('paper_master');
			// $this->db->where('pvt_exam_date!=',"");
			$this->db->where_in('class_id',array("104","107","134"));
			$this->db->where('pvt_exam_date!=',"0000-00-00");	
			//$this->db->where('exam_date',"2024-07-23");	
			$this->db->group_by(array('pvt_exam_date','pvt_exam_shift'));
			$this->db->order_by('pvt_exam_date', "asc");
			$this->db->order_by('pvt_exam_shift', "desc");
			$data['pvtexamDate'] = $this->db->get()->result();
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
		$data['title'] = "Exam Center Billing June 2025";
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$this->db->order_by('examcentercode');
		// $this->db->where('examcentercode','MDE125');
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
				$this->db->join('student', 'e.student_id = student.student_id and e.class_id = student.class_id');
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('student.result_show','Y');
				// if($mode != 'All'){
				// 	$this->db->where_in('new_exam_form.class_id',$set);
				// }
				$this->db->where_in('e.class_id',$set);
				 $this->db->where('e.theory_marks','');
				$this->db->where('e.paper_type',"theory");
				$this->db->order_by('student.course_group_id','student.class_id','student.university_mode','student.roll_no');
				
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
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136);
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
            $this->load->model('Gradesheet_model');
			$this->load->view('admin/grade_marksheet',$data);
		}else if( $data['exam_data']->university_mode=='REG' && $data['exam_data']->marks_pattern=='GRADE' &&  ($data['class']->cbcs=='Y' || $new_exam_form[0]->class_id == 267)){
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
		$data['not_permited_students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id , 'result_show'=>'N' , 'exam_form'=>'Y' ,'mode'=>$mode,'exam_year'=>'June 2025'));

		$data['permited_students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id , 'result_show'=>'Y' , 'exam_form'=>'Y' ,'mode'=>$mode,'exam_year'=>'June 2025'));
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
			$where = " student_id in (".$student_ids.") and exam_year = 'June 2025' and class_id=".$_POST['class_id']."";//provisional_remark in ('','N') &&
			$update =$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('result_show' => 'N');
			$where ='student_id in ('.$student_ids.') and exam_year = "June 2025" and class_id='.$_POST["class_id"].'';
			$update = 	$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/backlog_student_result_permission/'.$_POST['mode'].'/'.$_POST['course_group_id'].'/'.$_POST['class_id']);
		}
	}

	public function backlog_student_notification_list($mode = "",$exam_pattern="M",$course_id="",$class_id=""){
        if($exam_pattern=="M"){
			$pattern="MARKS";
		}
		else{
			$pattern="GRADE";
		}
		$this->load->model('Gradesheet_backlog_tr_model');
		$course_id = $this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$this->db->order_by('roll_no','ASC');
		$data['mode']= $mode;
        $this->db->select('bs.*,s.exam_pattern');
        $this->db->from('backlog_student as bs');
        $this->db->join('student as s', 's.student_id = bs.student_id');
        $this->db->where(array("bs.course_group_id"=>$course_id ,'bs.class_id' => $class_id,'bs.exam_form'=>'Y' ,'bs.roll_no!='=>'0', 'bs.mode'=>$mode,'bs.exam_year'=>"Dec 2024", 's.exam_pattern'=>$pattern));
        $data['students']= $this->db->get()->result();
        $data['pattern'] = $pattern;
		// $data['students']= $this->Common_model->getRecordByWhere('backlog_student',array("course_group_id"=>$course_id ,'class_id' => $class_id,'exam_form'=>'Y' ,'roll_no!='=>'0', 'mode'=>$mode,'exam_year'=>"June 2024"));//'result_show'=>'Y'
		$data['title'] = "Notification ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
        $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,278,282,250,252,216,232,236,238,240,246,248,254,218,305,210,243);
        if((in_array($class_id, $class_cbcs)) && $pattern=="GRADE"){
			$this->load->model('Gradesheet_backlog_tr_model_pg');
			$this->load->view('admin/backlog_student_notification_list_pg',$data);
		}else{
		    $this->load->view('admin/backlog_student_notification_list',$data);
        }
	}

	public function withheld_backlog_student_list($course_id="",$class_id=""){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		$this->db->select('count(*) as cnt ,student.name,backlog_student.roll_no,student.course_name,backlog_student.class_id  , backlog_student.center_code,backlog_student.course_group_id,backlog_student.student_id');
		$this->db->from('backlog_exam_form');
		$this->db->join('backlog_student', 'backlog_exam_form.student_id = backlog_student.student_id and backlog_exam_form.backlog_student_id=backlog_student.id');
		$this->db->join('student', 'student.student_id = backlog_student.student_id');
		$this->db->where('backlog_student.exam_form','Y'); 
		$this->db->where('backlog_student.exam_year','Dec 2024'); 
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
			$where = 'student_id in ('.$student_ids.') and exam_year = "Dec 2024"';
			$update =$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('result_show' => 'N');
			$where ='student_id in ('.$student_ids.') and exam_year = "Dec 2024"';
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
			$this->db->where(array("backlog_student.course_group_id"=>$course_id ,'backlog_student.class_id' => $class_id,'backlog_student.exam_form'=>'Y','backlog_student.roll_no!='=>'0','student.course_complete'=>'Y','backlog_student.mode'=>$mode,'backlog_student.result_show'=>'Y','backlog_student.exam_year'=>'Dec 2024','student.exam_pattern'=>'MARKS'));
			$this->db->order_by('backlog_student.center_id,backlog_student.roll_no','ASC');
			$data['students']=$this->db->get()->result();
			// $this->Common_model->last_query();
		 }else{
			$this->db->select('backlog_student.*,student.name,student.f_h_name,student.session,student.photo,student.course_name');
			$this->db->from('backlog_student');
			$this->db->join('student','student.student_id = backlog_student.student_id');
			$this->db->where(array("backlog_student.course_group_id"=>$course_id ,'backlog_student.class_id' => $class_id,'backlog_student.exam_form'=>'Y','backlog_student.roll_no!='=>'0','backlog_student.mode'=>$mode,'backlog_student.result_show'=>'Y' ,'backlog_student.exam_year'=>'Dec 2024','student.exam_pattern'=>'MARKS'));
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
			redirect(base_url().'admin/Admins/student_previous_fail/'.$_POST['mode'].'/'. $this->Common_model->encrypt_decrypt($_POST['course_group_id']).'/'. $this->Common_model->encrypt_decrypt($_POST['class_id']));
		}
	}


    public function student_previous_fail($mode = "",$course_id="",$class_id=""){
		
		$course_id = $this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$this->db->order_by('roll_number','ASC');
		$data['mode']= $mode;
		
		$data['students']= $this->Common_model->getRecordByWhere('student',array("course_group_id"=>$course_id ,'old_class_id' => $class_id,'exam_form'=>'Y' ,'roll_number!='=>'0', 'university_mode'=>$mode,'old_result_show'=>'Y', 'exam_pattern'=>'GRADE'));//'result_show'=>'Y'
        //  $this->Common_model->last_query();
		$data['title'] = "Remaining Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
        $data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();

		$this->load->view('header',$data);
        $this->load->view('admin/student_previous_fail', $data);
        $this->load->view('footer');
		
	}

	public function backlog_student_previous_fail($mode = "",$course_id="",$class_id=""){
		
		$course_id = $this->Common_model->encrypt_decrypt($course_id,'decrypt');
		$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		$data = array('course_group_id' => $course_id, 'class_id' => $class_id);
		$this->db->order_by('roll_no','ASC');
		$data['mode']= $mode;

		$this->db->select('bs.*, st.course_name, st.exam_pattern,st.name');
		$this->db->from('backlog_student as bs');
		$this->db->join('student as st', 'st.student_id = bs.student_id');
		$this->db->where(array('st.exam_pattern'=>'GRADE',"bs.course_group_id"=>$course_id ,'bs.class_id' => $class_id,'bs.exam_form'=>'Y' ,'bs.roll_no!='=>'0', 'bs.mode'=>$mode,'bs.result_show'=>'Y','bs.exam_year'=>'Dec 2024'));
		$data['students']=  $this->db->get()->result();
		
		//'result_show'=>'Y'
        //  $this->Common_model->last_query();
		$data['title'] = "Remaining Marksheet ".$this->Common_model->getCourseNameByCourseId($course_id).' '.$this->Common_model->getClassNameByClassId($class_id);
		
        $data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();

		$this->load->view('header',$data);
        $this->load->view('admin/backlog_student_previous_fail', $data);
        $this->load->view('footer');
		
	}

	public function remove_backlog_student_result_permission_previous(){
		
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}
		if($_POST['not_permitted']){
			$student_ids = (implode(',',$_POST['not_permitted']));
			$data = array('result_show' => 'Y');
			$where = 'student_id in ('.$student_ids.') and exam_year = "Dec 2024"';//provisional_remark in ('','N') &&
			$update =$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}else{
			$student_ids = (implode(',',$_POST['permitted']));
			$data = array('result_show' => 'N');
			$where ='student_id in ('.$student_ids.') and exam_year = "Dec 2024"';
			$update = 	$this->Common_model->updateRecordByConditions('backlog_student',$where,$data);
		}  
		if($update){
			redirect(base_url().'admin/Admins/backlog_student_previous_fail/'.$_POST['mode'].'/'. $this->Common_model->encrypt_decrypt($_POST['course_group_id']).'/'. $this->Common_model->encrypt_decrypt($_POST['class_id']));
		}
	}

	public function update_late_exam_fees()
	{

		if ($this->input->method() == "post") 
		{

			$parameter1   	= $this->input->post("param_name");
			$permisssion    = $this->input->post("permission");

			$data = array($parameter1 => $permisssion);
			
			$res = $this->Common_model->updateRecordByConditions('master',$where,$data);
			
			//echo $this->db->last_query();


			$late_exam_fees_privte = $this->Common_model->getRecordByWhere('master');

			if($late_exam_fees_privte[0]->late_exam_fee_status == 'Y')
			{
				$sts_btn = '<a class="btn btn-primary" onclick="update_late_exam_fees(`late_exam_fee_status`,`N`)">All Yes</a>';
			}

			else{
				
				$sts_btn = '<a class="btn btn-danger" onclick="update_late_exam_fees(`late_exam_fee_status`,`Y`)">All No</a>';
				
				
			}


			echo json_encode(array(
				"status" => $res,
				"msg" => "Permission has been updated successfully",
				"sts_btn"=>$sts_btn,
				"p1"=>$parameter1,
			));

		}
	}

    public function deled_result(){
            $this->db->select('s.roll_no,s.`enrollment_no`,`name`,e.paper_code,e.paper_type,e.theory_marks,int_marks,e.p_marks, p.paper_name');
            $this->db->from('student as s');
            $this->db->join('new_exam_form as e','s.`student_id`=e.student_id and s.class_id=e.class_id');
            $this->db->join('paper_master as p', 'e.`paper_id`=p.`id`');
            $this->db->where(array('s.new_exam_form'=>'Y','s.class_id'=>300));
            $this->db->order_by('s.roll_no,e.paper_order');
            $students =  $this->db->get()->result();
            $data = array();
            $data['title'] = "Deled Result Data";
            $this->load->view('header',$data);
            $this->load->view('admin/deled_result', array('students'=> $students));
            $this->load->view('footer');
    }

}// class
