<?php
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class updateStudentData extends CI_Controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('Common_model');
			if(!$this->session->has_userdata('loged_in')){
				exit;
			}
		}
		
		public function index($step){
			if($step==1){
				$course_group_id = html_escape($this->input->post('course_group_id'));
				$class_id = html_escape($this->input->post('class_id'));
				$data = array( 
				'session' => html_escape($this->input->post('session')),
				'course_group_id' => $course_group_id,
				'course_name' => $this->Common_model->getCourseNameByCourseId($course_group_id),
				'class_name' => $this->Common_model->getClassNameByClassId($class_id),
				'class_id' => $class_id,
				'eligibility' => html_escape($this->input->post('eligibility')),
				'course_category' => html_escape($this->input->post('course_category')),
				);
				$student_id = $this->input->post('student_id');
				
				$whereStudent = 'student_id='.$student_id;
				$student = $this->Common_model->get_record('student','*',$whereStudent);
				if($student[0]['course_group_id']!=$course_group_id){
					$data['temp_exam_form'] = 'N';
					$this->Common_model->deleteById('new_exam_form','student_id',$student_id);
				}
				$this->Common_model->updateRecordByConditions('student',$whereStudent,$data);
				
				}else if($step==2){
				
				$student_id = $this->input->post('student_id');
				if(isset($_FILES['photo']) && $_FILES['photo']['tmp_name']!=''){
					$path = './assets/student_image';
					$upload = $this->do_upload('photo',$path,$student_id);
					print_r($upload);
					$data['photo'] = $upload['file_name'];
				}
				
				$data['category'] = html_escape($this->input->post('category'));
				$data['gender'] = html_escape($this->input->post('gender'));
				$studentData['freedom_fighter'] = html_escape($this->input->post('freedom_fighter'));
				$studentData['national_award'] = html_escape($this->input->post('national_award'));
				$studentData['NCC_NSS'] = html_escape($this->input->post('NCC_NSS'));
				$studentData['p_handicapped'] = html_escape($this->input->post('p_handicapped'));
				$data['name_hindi'] = html_escape($this->input->post('name_hindi'));
				$data['name'] = html_escape($this->input->post('name'));
				$data['f_h_name_hindi'] = html_escape($this->input->post('f_h_name_hindi'));
				$data['f_h_name'] = html_escape($this->input->post('f_h_name'));
				$studentData['f_h_occupation'] = html_escape($this->input->post('f_h_occupation'));
				$data['mother_name_hindi'] = html_escape($this->input->post('mother_name_hindi'));
				$data['mother_name'] = html_escape($this->input->post('mother_name'));
				$studentData['mother_occupation'] = html_escape($this->input->post('mother_occupation'));
				$studentData['p_mobile_no'] = html_escape($this->input->post('p_mobile_no'));
				$studentData['f_h_mobile_no'] = html_escape($this->input->post('f_h_mobile_no'));
				$studentData['p_email'] = html_escape($this->input->post('p_email'));
				$data['dob'] = html_escape(date("Y-m-d", strtotime($this->input->post('dob'))));
				$data['adhar_no'] = html_escape($this->input->post('adhar_no'));
				$data['sm_id'] = html_escape($this->input->post('sm_id'));
				
				$where = 'student_id='.$student_id;
				
				$this->Common_model->updateRecordByConditions('student',$where,$data);
				
				$count = $this->Common_model->getCountByWhere('student_data',$where);
				if($count>0){
					$this->Common_model->updateRecordByConditions('student_data',$where,$studentData);	
					}else{
					$studentData['student_id'] = $student_id;
					$this->Common_model->insertAll('student_data',$studentData);
				}
				
				}else if($step==3){
				$studentData['p_address'] = html_escape($this->input->post('p_address'));
				$studentData['p_city'] = html_escape($this->input->post('p_city'));
				$studentData['p_state'] = html_escape($this->input->post('p_state'));
				$studentData['p_district'] = html_escape($this->input->post('p_district'));
				$studentData['p_pin_code'] = html_escape($this->input->post('p_pin_code'));
				$student_id = $this->input->post('student_id');
				$where = 'student_id='.$student_id;
				
				$this->Common_model->updateRecordByConditions('student_data',$where,$studentData);
				}else if($step==4){
				$studentData['retirement'] = html_escape($this->input->post('retirement'));
				$studentData['gap_certificate'] = html_escape($this->input->post('gap_certificate'));
				$studentData['complaint'] = html_escape($this->input->post('complaint'));
				$student_id = $this->input->post('student_id');
				$where = 'student_id='.$student_id;
				$this->Common_model->updateRecordByConditions('student_data',$where,$studentData);
				}else if($step==5){
				$class_id = html_escape($this->input->post('class_id'));
				$course_group_id = html_escape($this->input->post('course_group_id'));
				$group_id = html_escape($this->input->post('group_id'));
				$papers = $this->input->post('papers');
				$paper_type = $this->input->post('paper_type');
				$paper_code = $this->input->post('paper_code');
				
				$student_id = $this->input->post('student_id');
				
				$data['class_id'] = $class_id;
				$data['student_id'] = $student_id;
				$data['course_group_id'] = $course_group_id;
				
				$i=0;
				foreach($papers as $paper){
					$data['paper_id'] = $paper;
					$data['paper_type'] = $paper_type[$i];
					$data['paper_code'] = $paper_code[$i];
					$this->Common_model->insertAll('new_exam_form',$data);
					$i++;
				}
				$student['temp_exam_form'] = 'Y';
				if($group_id!=''){
					$student['group_id'] = $group_id;
					$grouppaper= $this->Common_model->get_record('group_paper','*','group_id='.$group_id);
					$j=0;
					foreach($grouppaper as $paper){
						$data['group_id'] = $group_id;
						$data['paper_id'] = $paper['paper_id'];
						$data['paper_type'] = 'theory';
						$data['paper_code'] = $paper['paper_code'];
						$this->Common_model->insertAll('new_exam_form',$data);
						$j++;
					}
				}
				$where = 'student_id='.$student_id;
				
				$this->Common_model->updateRecordByConditions('student',$where,$student);
				$student = $this->Common_model->get_record('student','*',$where);
				$courseData = $this->Common_model->getRecordById('course_group','id',$student[0]['course_group_id']);
				$docLength = $this->Common_model->getCountByWhere('document_category','document_id ='.$courseData->document_id.' and status="Y"');
				
				$exam_papers = $this->Common_model->get_record('new_exam_form','paper_id,paper_code',$where);
				$viewData = array('exam_papers'=>$exam_papers,
				'student' => $student[0],
				'docLength' => $docLength,
				);
				echo $this->load->view('users/admission_papers',$viewData,true);
				}elseif($step==6){
				$studentData['ten_marks'] = html_escape($this->input->post('ten_marks'));
				$studentData['ten_total_marks'] = html_escape($this->input->post('ten_total_marks'));
				$studentData['ten_year'] = html_escape($this->input->post('ten_year'));
				$studentData['ten_subjects'] = html_escape($this->input->post('ten_subjects'));
				$studentData['ten_board'] = html_escape($this->input->post('ten_board'));
				
				$studentData['twowelth_marks'] = html_escape($this->input->post('twowelth_marks'));
				$studentData['twowelth_total_marks'] = html_escape($this->input->post('twowelth_total_marks'));
				$studentData['twowelth_year'] = html_escape($this->input->post('twowelth_year'));
				$studentData['twowelth_subject'] = html_escape($this->input->post('twowelth_subject'));
				$studentData['twowelth_board'] = html_escape($this->input->post('twowelth_board'));
				
				$studentData['graduation_marks'] = html_escape($this->input->post('graduation_marks'));
				$studentData['graduation_university'] = html_escape($this->input->post('graduation_university'));
				$studentData['graduation_year'] = html_escape($this->input->post('graduation_year'));
				$studentData['graduation_subject'] = html_escape($this->input->post('graduation_subject'));
				$studentData['graduation_total_marks'] = html_escape($this->input->post('graduation_total_marks'));

				$studentData['pg_marks'] = html_escape($this->input->post('pg_marks'));
				$studentData['pg_university'] = html_escape($this->input->post('pg_university'));
				$studentData['pg_year'] = html_escape($this->input->post('pg_year'));
				$studentData['pg_subject'] = html_escape($this->input->post('pg_subject'));
				$studentData['pg_total_marks'] = html_escape($this->input->post('pg_total_marks'));
				
				$student_id = $this->input->post('student_id');
				$where = 'student_id='.$student_id;
				$this->Common_model->updateRecordByConditions('student_data',$where,$studentData);
				echo true;
				}elseif ($step==7){
				$student_id = $this->input->post('student_id');
				$studentData = array('form_status' => 'Y');
				$where = 'student_id='.$student_id;
				$this->session->set_flashdata('success','Your Admission Form Submited');
				$this->Common_model->updateRecordByConditions('student',$where,$studentData);
				if($this->session->has_userdata('studentdata')){
					echo "student/dashboard";	
				}else{
					echo "admin/enrollment/edit_non_verified_list";
				}
			}
			
		}
		
		private function set_upload_options($path,$name)
		{   
			//upload an image options
			$config = array();
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|jpg|png|pdf';
			$config['max_size']      = '0';
			$config['overwrite']     = True;
			$config['file_name'] =  $name;
			
			return $config;
		}
		
		
		public function do_upload($file,$path,$name)
        {
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name'] =  $name;
			print_r($config);
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload($file))
			{
				return $error = array('error' => $this->upload->display_errors());
			}
			else
			{
				return   $this->upload->data();
			}
		}
		
		public function getPapersByClassId(){
			$student_id = $this->input->post('student_id');
			$wherestudent = 'student_id='.$student_id;
			$student = $this->Common_model->get_record('student','*',$wherestudent);
			$courseData = $this->Common_model->getRecordById('course_group','id',$student[0]['course_group_id']);
			$docLength = $this->Common_model->getCountByWhere('document_category','document_id ='.$courseData->document_id.' and status="Y"');
			if($student[0]['temp_exam_form']=='N'){
				$class_id = $this->input->post('class_id');
				$class = $this->Common_model->get_record('class_master','*',"id='".$class_id."'");
				
				$compulsoryPapers = $this->Common_model->get_record('paper_master','*','class_id='.$class[0]['id'].' and ce="compulsory"');
				$data = array(
				'compulsoryPapers' => $compulsoryPapers,
				'student' => $student[0],
				'class' => $class[0],
				);
				$data['docLength'] = $docLength;
				
				if($class[0]['class_group']=='Y'){
					$groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id where class_id='.$class[0]['id'])->result();
					$data['groupPaper'] = $groupPaper; 
				}
				}else{
				$data['docLength'] = $docLength;
				$exam_papers = $this->Common_model->get_record('new_exam_form','paper_id,paper_code',$wherestudent);
				$data['exam_papers'] = $exam_papers;
				$data['student'] = $student[0];
			}
			echo $this->load->view('users/admission_papers',$data,true);
		}
	}	