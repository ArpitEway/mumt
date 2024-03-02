<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MsPrint extends CI_Controller {

	public $master = array();
	public $exam_table;
	public $exam_form;
	public $roll_no ;
	public $exam_form_table;


	function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_model');
		$this->load->model('Common_model');
		$this->load->model('Datatable_model');
		$this->load->model('Datatable_join_model');
		$this->master = $this->Common_model->getSingleRow('master');
		 $this->exam_table = $this->master->student_exam_table;
		 $this->exam_form = $this->master->exam_form_col;
		 $this->roll_no = $this->master->roll_number_col;
		 $this->result_table = $this->master->student_result_table;
		 $this->exam_form_table = $this->master->exam_form_table;
		 $this->old_result_table = $this->master->old_student_result_table;
		 $this->old_exam_form_table = $this->master->old_exam_form_table;
		if($this->session->account_type!='MsPrint'){
			redirect(base_url('admin/logout')); 
		}
	}

	public function index()
	{
		$admin_id = $this->session->admin_id;
		$where = 'admin_id='.$admin_id.' and status="Y"';
		$menu = array(
			"menu_headings" => $this->Common_model->getRecordByWhereByOrder('menu_heading',$where,'heading_order','ASC'),
			"menus" => $this->Common_model->getRecordByWhereByOrder('menu',$where,'heading_id,menu_order','ASC'),
		);
		$this->load->view('header',array('title' => 'MS Print'));
		$this->load->view('admin/Enquiry/dashboard',$menu);
		$this->load->view('footer');
	}

	public function student_result(){
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
				 $this->db->where_not_in('exam_year',array('June 2023','July 2023'));
				$result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id' =>$student->student_id));
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
		$title = array('title' => 'Result');
		$data['exam_data'] = $this->Common_model->getRecordById('old_exam_data','id',$exam_data_id);
		
		$data['student'] = $this->Common_model->getRecordById('student','student_id', $data['exam_data']->student_id);
		// $course_id !=36 && $course_id !=37
		$class = $this->Common_model->getRecordByID('class_master','id', $data['exam_data']->class_id);

		$class_ids=array(101,104,107,110,116,119,125,128,131,134);
		$class_cbcs = array(193,197,201,203,205,211,213,221,223,225,227,275,279);
		if($data['exam_data']->university_mode == "REG" && in_array($data['class_id'] , $class_ids)){
			$this->load->model('Gradesheet_old_model');
			$dt =  $this->load->view('admin/msprint/student_marksheet_grade',$data);
		}elseif ($data['exam_data']->marks_pattern=='GRADE' && $data['exam_data']->university_mode == "REG" && in_array($data['class_id'] , $class_cbcs)) {
			$this->load->model('GradeSheet_old_model_pg');
			$dt =  $this->load->view('admin/msprint/student_marksheet_grade_pg',$data);
		}else if ($data['exam_data']->exam_status == "B") {
			$this->load->view('admin/msprint/old_backlog_student_marksheet',$data);
		}else if($class->internal=="Y" && $data['exam_data']->university_mode!="PVT" ){ 
			$this->load->view('admin/msprint/old_student_marksheet',$data);
		}else{
			$this->load->view('admin/msprint/old_student_marksheet_certificate',$data);
		}

		// $this->load->view('admin/generate_tr/header2',$title);
		// $this->load->view('admin/old_marksheet_top',$data);
		// $this->load->view('admin/marksheet_student',$data);
		// $this->load->view('admin/generate_tr/footer2');
	}
	public function center_wise_marksheet_dispatch(){
		if(!$this->session->has_userdata('adminData')){
			redirect(base_url());
			exit;
		}else
		{
			$titleData = array('title' => 'Center Wise MarkSheet Dispatch '); 
			$this->load->view('header',$titleData);
			$data['name_csrf'] = $this->security->get_csrf_token_name();
			$data['hash_csrf'] = $this->security->get_csrf_hash();
			$this->db->select('DISTINCT(center_id)');
			$this->db->from($this->result_table);
			$this->db->where(array('exam_form'=>'Y','marksheet_dispatch'=>'Y'));
			$centers = $this->db->get()->result_array();
			$ids = array_column($centers, 'center_id');
			//print_r($ids);die;
			$this->db->select('*');
			$this->db->from('center');
			$this->db->where_in('id',$ids);
			$this->db->order_by('center_code', "ASC");
			$data['centers'] = $this->db->get()->result();
			
			$this->load->view('admin/examController/center_wise_marksheet_dispatch',$data);
			$this->load->view('footer');
		}
	}

	public function get_center_wise_marksheet_dispatchlist(){
		$center = $this->input->post('center');
		$this->db->select('DISTINCT(center_id)');
		$this->db->from($this->result_table);
		$this->db->where(array('exam_form'=>'Y','marksheet_dispatch'=>'Y'));
		$centers = $this->db->get()->result_array();
		$ids = array_column($centers, 'center_id');
	
		$this->db->select('*');
		$this->db->from('center');
		if($center!="All")
			$this->db->where( array('id'=>$center));
		else
			$this->db->where_in('id',$ids);
		$this->db->order_by('center_code', "asc");
		$data['centers'] = $this->db->get()->result();
		$data['examTitle'] = "July 2023";
		
		echo $this->load->view('admin/examController/get_center_wise_marksheet_dispatchlist',$data, TRUE);
	}

	public function update_marksheet_date(){
		
		if ($this->input->method() == "post") 
		{ 
			if(isset($_POST['remark_date'])){ $remark = $this->input->post('remark_date');}else{ $remark = '';}
			$date = $this->input->post('marksheet_date');
			$date = str_replace('/', '-', $date);
			$marksheet_date = date('Y-m-d', strtotime($date));
			$record_id  = $this->input->post("record_id");
	      	$record_id = $this->Common_model->encrypt_decrypt($record_id,'decrypt');
			
			$updateData = array(
				'marksheet_date' => $marksheet_date ,
				'remark_date' => $remark
			);
		
			$where = array(
				'id'=> $record_id
			);
			
			$response = $this->Common_model->updateRecordByConditions('old_exam_data',$where,$updateData);

			if($response){
			echo json_encode(array("status" => 'true'));
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
			redirect(base_url());
		}
	}

	public function view_application_request($center_id=0){
			
		if($this->session->has_userdata('adminData')){
			$where = array("status" => "Pending");//,"payment"=>"Y"
			$centers = $this->Common_model->get_record_group_by_where('application_form','center_id',$where);
			
			$data = array('name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centers' =>$centers,
				'center_id'=>$center_id,
			);
			
			$this->load->view('header',array('title'=>"Center Application Form Request"));
			$this->load->view('admin/msprint/view_application_form_request',$data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url());
		}
	}
	public function get_application_request()
	{
		if ($this->input->method() == "post") 
		{
			$course_group_id = 0;
			$data = array();
			$dt   = array();
				
			$center_id  = $this->input->post("center_id");
			$centerData = $this->Common_model->getRecordById('center','id',$center_id);
			$wherecenter = 'center_id='.$center_id.' and status="Pending" ';//and payment="Y"
			$complaints = $this->Common_model->get_record('application_form','*',$wherecenter);
			
			$data = array('complaints' => $complaints ,'name_csrf' => $this->security->get_csrf_token_name(),
				'hash_csrf' => $this->security->get_csrf_hash(),
				'centerData' => $centerData,
			);

			if($data['complaints']){
				$dt =  $this->load->view('admin/msprint/getApplicationFormRequest',$data,true);
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
	public function update_application_form_status()
	{
		if ($this->input->method() == "post") 
		{
            $id    	= 0;
            $id    	= $this->input->post("id");
			$status = $this->input->post("status");

			
            if ($this->input->post("id")) 
			{
				$data = $this->Common_model->updateRecordByConditions("application_form",array("id" => $id ),array("status" => $status ));
			
				$dt = $this->db->get_where("application_form",array("id" => $id ))->result_array();

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

	public function search_student_marksheet(){
		
		$segment = $this->uri->segment(2);
		
		$this->load->view('header',array('title' => 'Search Students Result'));

		$data = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash(),
			'segment' => $segment
		);

		$this->load->view('admin/msprint/search_student_marksheet',$data);
		$this->load->view('footer');
	}

    public function view_application($id){
        $this->load->view('header',array('title' => 'Student Application'));
        $this->db->select('application_form.*,student.dob,student.class_name,student.photo');
        $this->db->from('application_form');
        $this->db->join('student', 'application_form.student_uid= student.student_id');
        $this->db->where('application_form.id',$id);
        $data = $this->db->get()->result();
        
       $this->load->view('admin/msprint/view_application',array('data'=>$data,'name_csrf' => $this->security->get_csrf_token_name(),
       'hash_csrf' => $this->security->get_csrf_hash()));
		$this->load->view('footer');
    }

    public function store_file(){
        
        $student_id = $this->input->post('student_id');
        $id = $this->input->post('id');
		$center_id = $this->input->post('center_id');
        $session = $this->input->post('session');
        $apply_for = $this->input->post('apply_for');
       

        if(empty($_FILES['doc']['name'])){
            
            $this->form_validation->set_rules('doc','Document', 'required');
            if ($this->form_validation->run() == false) {
              
           
                $this->session->set_flashdata('error','Please Upload a file');
                $this->view_application($id);

            }
        }else{
            $path = './assets/student_application/'.$session;
            if(!file_exists($path)){
                mkdir($path);
            }
            $upload = $this->do_upload('doc',$path,$apply_for.'_'.$student_id);
            $PhotoData = array('document' => $upload['file_name'],'status'=>'Done');
            $this->Common_model->updateRecordByConditions('application_form', array('id'=>$id), $PhotoData);
            $this->session->set_flashdata('success','upload document successfully');
            redirect('MsPrint/view_application_request/'.$center_id);
        }

      

       
    }

    public function do_upload($file,$path,$name)
	{
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
		$config['file_name'] =  $name;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload($file))
		{
			return $error = array('error' => $this->upload->display_errors());
		}else{
			return   $this->upload->data();
		}
	}

	public function getStudentMarksheetData()
	{
		// if(!$this->session->has_userdata('adminData')){
		// 	redirect(base_url());
		// 	exit;
		// }

		$text_val =$this->input->post('text_val');
		$radio_val = $this->input->post('radio_val');


		if($text_val !='')
		{
			if($text_val !='' && $radio_val == 'enrollment_no')
			{
				$where = array('exam_form'=>'Y','enrollment_no'=>$text_val,'old_result_show'=>'Y');
				//,'result_show'=>'Y'

			}else if($text_val !='' && $radio_val == 'roll_no')
			{
				$where = array('exam_form'=>'Y','roll_number'=>$text_val ,'old_result_show'=>'Y');
			//,'result_show'=>'Y'
			}

			
				$student = $this->Common_model->getRecordByWhere($this->result_table,$where);
				//print_r($student); die;
				$msg="";
				if (count($student)==0) {
					
					echo json_encode(array(
						"status" => false,
						"data" => "<p style='text-align: center;'><b>No data found!</b></p>"
					));
					
				}else{
			
					if($student[0]->old_result_show =="N"){
						
							$msg="<p style='text-align: center;' id='result_msg'><b>Student result not declared!</b></p>"; 
					}
						$data['student']=$student[0];
						$data['exam_session']  = 'July 2023';
						/**********************/
						if($data['student']->provisional_remark!="N" && $data['student']->provisional_remark!="")
						{
							$this->db->select('provisional_remarks');
							$this->db->from('provisional_remark_details');
							$this->db->where('document_category_id',$data['student']->provisional_remark);
							$remark = $this->db->get()->row();
							$provisional_remark_details ="<p style='text-align: center;' id='pro_remark'><b>".$remark->provisional_remarks." are not recieved at university</b></p>";
						}
						/************************/
						
						$classData = $this->Common_model->getRecordById('class_master','id',$data['student']->old_class_id);
						$data['practical_internal_marks']=$classData->practical_internal_marks;
						$this->db->select('*');
						$this->db->from($this->exam_form_table);
						$this->db->where(''.$this->exam_form_table.'.student_id',$data['student']->student_id);
						$this->db->where(''.$this->exam_form_table.'.class_id',$data['student']->old_class_id);
						$new_exam_form = $this->db->get()->result();
						$data['classData']  = $classData;
						$data['new_exam_form']  = $new_exam_form;
						// if(($data['student']->old_class_id == '104' || $data['student']->old_class_id == '107' || $data['student']->old_class_id == '101' || $data['student']->old_class_id == '134' || $data['student']->old_class_id == '116' || $data['student']->old_class_id == '110'|| $data['student']->old_class_id == '119' || $data['student']->old_class_id == '131') && $data['student']->university_mode == 'REG')
						
						$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
						$class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
						if((in_array($data['student']->class_id, $class_ids)) && $data['student']->university_mode=='REG' && $data['student']->exam_pattern=='GRADE')	
						{
							$this->load->model('Gradesheet_model');
							$dt = $provisional_remark_details.$msg.$this->load->view('Centers/grade_marksheet',$data,true);
						}else if((in_array($data['student']->class_id, $class_cbcs)) && $data['student']->university_mode=='REG' && $data['student']->exam_pattern=='GRADE'){
							$this->load->model('Gradesheet_model_pg');
							$dt = $provisional_remark_details.$msg.$this->load->view('Centers/grade_marksheet_pg',$data,true);
						}else{
							
							$title = array('title' => 'Result - '.$data['student']->enrollment_no);
							
							$marksheet_top =  $this->load->view('Centers/marksheet_top',$data,true);
							// if ($student[0]->course_group_id==36 || $student[0]->course_group_id==37) {
								
							// 	$marksheet_bottom=  $this->load->view('Centers/marksheet_without_int',$data,true);
							// }else{
								
							// 	$marksheet_bottom=  $this->load->view('Centers/marksheet_bottom',$data,true);
							// }
							if($classData->internal=='N'){
								$marksheet_bottom = $this->load->view('Centers/marksheet_without_int',$data,true);
							}else{
								if($student[0]->class_id=='168'){
									$marksheet_bottom  = $this->load->view('Centers/marksheet_mom',$data,true);
								}else{
									$marksheet_bottom = $this->load->view('Centers/marksheet_bottom',$data,true);
								}
							// $dt =  $marksheet_top.$marksheet_bottom;
							}
						
						
							$dt =$provisional_remark_details. $msg. $marksheet_top.$marksheet_bottom;
						
						}
						echo json_encode(array(
							"status" => true,
							"data" => $dt
						));
					 }
		
	  }
	}//fun
}