<?php
include_once(APPPATH.'core/ADMIN_controller.php');

defined('BASEPATH') OR exit('No direct script access allowed');

class Dataentry extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		if($this->session->account_type!='DataEntry'){
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
		$this->load->view('header',array('title' => 'Data Entry Section'));
		$this->load->view('admin/Dataentry/dashboard',$menu);
		$this->load->view('footer');	
	}


	public function mark_entry_file()
	{
    $titleData = array('title' => 'Marks Entry Section'); 
	$this->load->view('header',$titleData);
	$this->load->view('admin/Dataentry/mark_entry_file');
	$this->load->view('footer');
	}

	public function marks_entry_form($mode,$paper_id,$page = 0)
	{
		$titleData = array('title' => 'Marks Entry Form'); 
		$this->load->view('header',$titleData);

		$where = array('new_exam_form.paper_id' => $paper_id,'new_exam_form' => 'Y', 'theory_marks' => '','mode' => $mode,'paper_type' => 'theory');
		$this->db->select('student.student_id, student.name,enrollment_no,roll_no');
		$this->db->from('new_exam_form');
		$this->db->order_by("student.roll_no","student.enrollment_no","asc");
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->where('student.class_id = new_exam_form.class_id');
		$this->db->where($where); 
		$this->db->limit(20,$page);
		$counts = $this->db->get();
		$data['counts'] = $counts->result();
		// pagination 
		$config = array();
		$config["base_url"] = base_url() . "admin/Dataentry/marks_entry_form/".$mode."/".$paper_id;
		$this->db->where('`student_id` IN (SELECT `student_id` FROM `student` where exam_form="Y" and mode="'.$mode.'")', NULL, FALSE);
		$config["total_rows"] = $this->Common_model->getCountByWhere('new_exam_form',array('paper_id' => $paper_id,'theory_marks' => ''));
		$config["per_page"] = 20;
		$config["uri_segment"] = 6;
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();

        $data['paper_id'] = $paper_id ;
		$where = array('new_exam_form.paper_id' => $paper_id);
		$this->db->select('*');
		$this->db->from('new_exam_form');
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->where('student.class_id = new_exam_form.class_id');
		$this->db->join('paper_master', 'paper_master.id = new_exam_form.paper_id');
		$this->db->where($where); 
		$data['papers'] = $this->db->get()->row();
		$data['mode'] = $mode;

		$this->load->view('admin/Dataentry/marks_entry_form',$data );		
		$this->load->view('footer');
	}


    public function marks_entry_form_sub(){

		$data=array();
		$post = $this->input->post();
		$data['student_id'] = $this->input->post('student_id');	
		$data['marks'] = $this->input->post('marks');
		$data['mode'] = $this->input->post('mode');
		$int_marks = (isset($_POST['int_marks'])) ? $this->input->post('int_marks') : array();

		foreach ($data['student_id'] as $key => $value){
			if($data['marks'][$key]==''){
				continue;
			}
			$studentData = array(
				'theory_marks' => $data['marks'][$key],
				'student_id' =>  $data['student_id'][$key],
			);
			if (count($int_marks)>0) {
				$studentData['int_marks'] = $int_marks[$key];
			}
			$where =  array(
				'student_id' =>$value,
                'paper_id'  =>$_POST['paper_id']
			);
			$Marksentry = $this->Common_model->updateRecordByConditions('new_exam_form',$where,$studentData);
		  }
		if($Marksentry){
			$returndata = array('success'=> 'Form Has Been Submited');
			echo json_encode($returndata);
		}else{
			$returndata = array('error'=> 'An Error Occured');
			echo json_encode($returndata);		
		}
	}



}
