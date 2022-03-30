<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Placement extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('admin/Account_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='Placement'){
			redirect(base_url('admin/logout')); 
		}
	}

	public function index(){
        
		if($this->session->has_userdata('adminData')){

			$admin_id = $this->session->admin_id;

			$where = 'admin_id='.$admin_id.' and status="Y"';

			$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
			);

			$this->load->view('header',array('title' => 'Placement Section'));
			$this->load->view('admin/placement/dashboard',$menu);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

	public function dashboard(){

		if($this->session->has_userdata('adminData')){

			$admin_id = $this->session->admin_id;

			$where = 'admin_id='.$admin_id.' and status="Y"';

			$menu = array(
				"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
				"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
			);

			$this->load->view('header');
			$this->load->view('admin/director/dashboard',$menu);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('admin/login'));
		}
	}

    public function company_list(){
			
        if($this->session->has_userdata('adminData')){
            $data = array();
            $data['title'] = "About Company";
            $this->load->view('header',$dt);
            $this->db->order_by('id', 'Desc');
            $data['sessions'] = $this->db->get_where('session', array())->result_array();
            $data['name_csrf'] = $this->security->get_csrf_token_name();
            $data['hash_csrf'] = $this->security->get_csrf_hash();
            $this->load->view('admin/placement/company_list',$data);
            $this->load->view('footer');
        }
        else
        {
            redirect(base_url('admin/login'));
        }
    }

    public function company($param1 = '', $param2 = '', $param3 = '')
	{

		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{

			if($param1 == 'add'){
				$response = $this->admin_model->add_company();
				$this->session->set_flashdata('ajax_flash_message','Company Successfully Added');
				redirect(base_url().'admin/placement/company_list');
			}
			if($param1 == 'update'){
				$response = $this->admin_model->update_company($param2);
				$this->session->set_flashdata('ajax_flash_message','Company Successfully Updated');
				redirect(base_url().'admin/placement/company_list');
			}

			if($param1 == 'delete'){
				$response = $this->admin_model->delete_company($param2);
				$this->session->set_flashdata('ajax_flash_message','Company Successfully Deleted');
				redirect(base_url().'admin/placement/company_list');
			}

			if(empty($param1) ){
				$data = array();
				$data['title'] = "Company";
				$this->load->view('header',$data);
				$csrf = array(
					'name_csrf' => $this->security->get_csrf_token_name(),
					'hash_csrf' => $this->security->get_csrf_hash()
				);
				$this->load->view('admin/placement/company_list',$csrf);
				$this->load->view('footer');
			}
		}
	}
}