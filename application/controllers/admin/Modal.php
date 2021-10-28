<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
*  @author   : Creativeitem
*  date      : November, 2019
*  Ekattor School Management System With Addons
*  http://codecanyon.net/user/Creativeitem
*  http://support.creativeitem.com
*/

class Modal extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->database();

		/*LOADING ALL THE MODELS HERE*/
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		
	}

	function popup($folder_name1 = '',$folder_name2 = '', $page_name = '' , $param1 = '' , $param2 = '', $param3 = '')
	{
		 $page_data['param1']	=	$param1;
		 $page_data['param2']	=	$param2;
		 $page_data['param3']	=	$param3;
		$page_data['name_csrf'] = $this->security->get_csrf_token_name();
		$page_data['hash_csrf'] = $this->security->get_csrf_hash();

		$this->load->view($folder_name1.'/'.$folder_name2.'/'.$page_name.'.php' ,$page_data);
	}

	

	function student_popup($folder_name1 = '',$folder_name2 = '',$folder_name3 = '', $page_name = '' , $param1 = '' , $param2 = '', $param3 = '')
	{
		$param1 = $this->Common_model->encrypt_decrypt($param1,'decrypt');
		$page_data['param1']	=	$param1;
		$page_data['param2']	=	$param2;
		$page_data['param3']	=	$param3;
		$page_data['name_csrf'] = $this->security->get_csrf_token_name();
		$page_data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view($folder_name1.'/'.$folder_name2.'/'.'/'.$folder_name3.'/'.$page_name.'.php' ,$page_data);
	}
	
}
