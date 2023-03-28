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
		if($this->session->account_type!='Dataentry'){
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


	public function mark_entry_file(){

		$titleData = array('title' => 'Marks Entry Section'); 
		$this->load->view('header',$titleData);
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();	
		$data['courses'] = $this->Common_model->get_record('student','DISTINCT (course_group_id), course_name ','exam_form="Y"');
		$this->load->view('admin/Dataentry/mark_entry_course',$data);
		$this->load->view('footer');
	} 

	public function getPaperByClassId(){

		$data= $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$_POST['class_id'], 'type' => 'theory'));
		echo json_encode(array('data'=>$data));
	}

	public function marks_entry_form($mode='' ,$class_id="",$paper_code='',$exam_centers='',$page = 0)

	{
		if (isset($_POST['paper_code'])) {
			$paper_code =  $this->input->post('paper_code');
			$mode =  $this->input->post('university_mode');
			$exam_center =  $this->input->post('exam_center');
			$class_id=$this->input->post('class_id');
		
		}else{
			$mode  = $this->Common_model->encrypt_decrypt($mode,'decrypt');
			$class_id  = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
			$paper_code  = $this->Common_model->encrypt_decrypt($paper_code,'decrypt'); 
			$page  = $page;
			$exam_center  = $exam_centers;
		}

		$titleData = array('title' => 'Marks Entry'); 
		$this->load->view('header',$titleData);

		$where = array('new_exam_form.paper_code' => $paper_code, 'theory_marks' => '','university_mode' => $mode,'paper_type' => 'theory','exam_center_id'=>$exam_center,'student.exam_form' => 'Y');
		//,'student.result_show'=>'N'
		$this->db->select('student.student_id, student.name,enrollment_no,roll_number');
		$this->db->from('new_exam_form');
		$this->db->order_by("student.roll_number","student.enrollment_no","asc");
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->where('student.old_class_id = new_exam_form.class_id');
		$this->db->where($where); 
		$this->db->limit(20,$page);
		$resultData = $this->db->get();
		$data['resultData'] = $resultData->result();

		$config = array();
		$config["base_url"] = base_url() ."Dataentry/marks_entry_form/".$this->Common_model->encrypt_decrypt($mode,'encrypt')."/".$this->Common_model->encrypt_decrypt($class_id,'encrypt')."/".$this->Common_model->encrypt_decrypt($paper_code,'encrypt')."/".$exam_center;

				$this->db->select('student.student_id, student.name,enrollment_no,roll_number');
		$this->db->from('new_exam_form');
		$this->db->order_by("student.roll_number","student.enrollment_no","asc");
		$this->db->join('student', 'student.student_id = new_exam_form.student_id');
		$this->db->where('student.old_class_id = new_exam_form.class_id');
		$this->db->where($where);
		$config["total_rows"] = $this->db->get()->num_rows();		
		$config["per_page"] = 20;
		$config["uri_segment"] = 7;
		$this->pagination->initialize($config);
		$data["links"] = $this->pagination->create_links();
		$data['paper_code'] = $paper_code ;
		$where = array(
			'paper_code' => $paper_code,
			'class_id' => $class_id,
			
		);
		
		$papersArr = $this->Common_model->getRecordByWhere('paper_master',$where);
		$data['papers'] =$papersArr[0];
		$data['class_id']=$class_id;
		$data['university_mode'] = $mode;
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();
		$this->load->view('admin/Dataentry/marks_entry_form',$data );		
		$this->load->view('footer');
	}


	public function marks_entry_form_sub(){

		$data=array();
		$post = $this->input->post();
		$data['student_id'] = $this->input->post('student_id');	
		$data['class_id']=$class_id = $this->input->post('class_id');	
		$data['marks'] = $this->input->post('marks');
		foreach ($data['student_id'] as $key => $value){
			if($data['marks'][$key]==''){
				continue;
			}
			$studentData = array(
				'theory_marks' => $data['marks'][$key],
			);
			$where =  array(
				'student_id' =>$value,
				'paper_code'  =>$_POST['paper_code'],
				'class_id'=>$class_id,
			);
			$Marksentry = $this->Common_model->updateRecordByConditions('new_exam_form',$where,$studentData);	
		}
		if($Marksentry){
			$returndata = array('success'=> 'Marks Has Been Submited');
			echo json_encode($returndata);
		}else{
			$returndata = array('error'=> 'An Error Occured');
			echo json_encode($returndata);		
		}
	}
	public function exam_center_folio(){
		$titleData = array('title' => 'Exam Center Folio'); 
		$this->load->view('header',$titleData);
		$data['name_csrf'] = $this->security->get_csrf_token_name();
		$data['hash_csrf'] = $this->security->get_csrf_hash();	
		$this->db->order_by('course_name');
		$data['courses'] = $this->Common_model->get_record('student','DISTINCT (course_group_id), course_name ','new_exam_form="Y"');
		$this->load->view('admin/examController/exam_center_folio',$data);
		$this->load->view('footer');
	} 
	public function getDataPaperByClassId(){
		// $_POST['class_id'];
		   $data= $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$_POST['class_id'], 'type' => 'theory'));
		   echo json_encode(array('data'=>$data));
	   }

	   public function search_assign_exam_center(){
		if($_POST['action1']=='submit'){
			$this->db->select('Distinct(examcentercode) ,exam_center_id');
			$this->db->from("student");
			$this->db->join('new_exam_form', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id=student.class_id');
			$this->db->where('new_exam_form.paper_code',$_POST['paper_code']);
			$this->db->where('new_exam_form.course_group_id',$_POST['course_group_id']);
			$this->db->where('new_exam_form.class_id',$_POST['class_id']);
			$this->db->where('student.exam_center_id!=',0);
			$this->db->where('student.new_exam_form','Y');
			$this->db->where('student.university_mode',$_POST['university_mode']);
			$this->db->where('student.roll_no!=',0);
			$this->db->order_by('student.examcentercode');
			$data['examcenters'] = $this->db->get()->result();
			$data['university_mode'] = $_POST['university_mode'];
			$data['class_id'] = $_POST['class_id'];
			$data['paper_code'] = $_POST['paper_code'];
			$data['course_group_id'] = $_POST['course_group_id'];
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();	
			//echo $this->db->last_query();die; 
			$dt = $this->load->view('admin/examController/get_assign_examcenter',$data,true);
			echo json_encode(array(
				"status" => true,
				"data" => $dt
			));
		}
		
		
	}  
	public function show_examcenter_folio(){
		if($_POST['action']=='assign_examcenter'){
			$data_insert['exam_center_id'] =  implode(',',$_POST['exam_center_id']);
			
			$dataArray= array();	
			foreach($_POST['exam_center_id'] as $exam_center_id){
				$this->db->select('*');
				$this->db->from("student");
				$this->db->join('new_exam_form', 'new_exam_form.student_id = student.student_id and new_exam_form.class_id=student.class_id');
				$this->db->where('new_exam_form.paper_code',$_POST['paper_code']);
				$this->db->where('new_exam_form.course_group_id',$_POST['course_group_id']);
				$this->db->where('new_exam_form.class_id',$_POST['class_id']);
				$this->db->where('student.exam_center_id',$exam_center_id);
				$this->db->where('student.roll_no!=',0);
				$this->db->where('student.new_exam_form','Y');
				$this->db->where('student.university_mode',$_POST['university_mode']);
				$this->db->order_by('student.roll_number');
				$dataArray['students'][$exam_center_id] = $this->db->get()->result();
				$dataArray['teachername'][$exam_center_id] = $this->Common_model->getSinglefield('exam_center','superintendent',array('id'=>$exam_center_id));
				$dataArray['detail'][$exam_center_id] = $this->Common_model->getRecordByWhere('exam_center',array('id'=>$exam_center_id));	
			}
			$dataArray['university_mode']=$_POST['university_mode'];
			$dataArray['class_id'] = $_POST['class_id'];
			$dataArray['paper_code'] = $_POST['paper_code'];
			$dataArray['course_group_id'] = $_POST['course_group_id'];
			$dataArray["exam_center_id"]=$_POST['exam_center_id'];
			$dataArray['examname']= $this->Common_model->getCourseNameByCourseId($_POST['course_group_id']);
			$dataArray['class_name']= $this->Common_model->getClassNameByClassId($_POST['class_id']);
			$this->db->where('exam_date!=',"");
			$this->db->where('exam_date!=',"0000-00-00");	
			$dataArray['paper']= $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$_POST['class_id'] , 'paper_code'=>$_POST['paper_code']));
			$dataArray['title'] = 'COUNTERFOIL';
			$dataArray['examSession']="Feb 2023";
			$this->load->view('admin/examController/show_examcenter_folio',$dataArray);
		}
	}

	
}
