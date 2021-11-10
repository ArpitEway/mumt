<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Document extends CI_Controller {
		function __construct(){

			parent::__construct();
			$this->load->model('Common_model');
			$this->load->model('Center/center_model');
			$this->load->model('Datatable_join_model');
			if(!$this->session->has_userdata('centerdata')){
				redirect(base_url('center/login'));
			}
		}

	public function index(){
		$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
		$titleData = array('title' => 'Upload Admission Document List'); 
		$this->load->view('Centers/header',$titleData);	
		$this->load->view('Centers/upload_admission_document',$csrf);	
		$this->load->view('Centers/footer');
	}

	public function Doc_list(){
			$data = array();
		$where = 'document_uploaded!="Y" and payment_status="Y" and center_id='.$this->session->center_id;
		$column_order = array('student_id','enrollment_no', 'name', 'f_h_name', 'course_name','class_name',null);
		$column_search = array('student','enrollment_no', 'name', 'f_h_name', 'course_name','class_name');
		$DataTableArray = array(
			'column_order' => $column_order,
			'column_search' => $column_search,
			'where' => $where,
			'table' => 'student',
		);

		$tableData = $this->Datatable_join_model->getRows($_POST,$DataTableArray);
		$i = $_POST['start'];
		foreach($tableData as $result){
			$btn = '<a href="'.base_url('center/document/upload/'.$this->Common_model->encrypt_decrypt($result->student_id)).'" target="_blank" class="btn btn-primary btn-sm" target="_blank" >Upload</a>';			
			$i++;
			$data[] = array($result->student_id, $result->name, $result->f_h_name, $result->course_name,$result->class_name,$btn);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Datatable_join_model->countAll('student',$where),
			"recordsFiltered" => $this->Datatable_join_model->countFiltered($_POST,$DataTableArray),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);	
	}

		public function upload($student_id){
			$csrf = array(
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
			$titleData = array('title'=>'Upload Admission Document');
			$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
			$where = 'student_id='.$student_id;
			$student = $this->Common_model->student_info($student_id);
			if($student['approved']=='Y' && $student['document_uploaded']=='Y'){
			$this->session->set_flashdata('warning','Document Already Submitted');
			redirect(base_url('student/dashboard'));
			}
			$courseData	= $this->Common_model->getRecordById('course_group','id',$student['course_group_id']);
			$documentData = $this->Common_model->get_record('document_category','*','category in ('.$courseData->document_id.',0)');
			$data = array(
			'courseData' => $courseData,
			'documentData' => $documentData,
			'student' => $student,
			'name_csrf' => $this->security->get_csrf_token_name(),
			'hash_csrf' => $this->security->get_csrf_hash()
		);
			$this->load->view('Centers/header',$titleData);
			$this->load->view('Centers/document',$data);
			$this->load->view('Centers/footer');
		}
		
		public function uploadDoc(){
			$student_id = html_escape($this->input->post('student_id'));
			$document_name = html_escape($this->input->post('document_name'));
			$document_category_id = html_escape($this->input->post('document_category_id'));
			$course_group_id = html_escape($this->input->post('course_group_id'));
			$admissionDocWhere = " student_id = ".$student_id." and document_category_id = ".$document_category_id;
			$admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
				
			$path = './assets/documents/';
			$this->load->library('upload');
				if($_FILES['document']['name']==''){
					echo 'an error occcerd';
					exit;
				}
				
				if($admissionDocCount>0){
				$nextid = $this->Common_model->getSinglefield('admission_document','id',$admissionDocWhere);
				}else{
				$nextid = $this->Common_model->getNextOrder('admission_document','id');
				}
				$this->upload->initialize($this->set_upload_options($path,$nextid));
				if(!$this->upload->do_upload('document')){
					$error = $this->upload->display_errors();
					$msg = array('error'=>$error);
					echo json_encode($msg);
					exit();
				}
				$uploadData = $this->upload->data();
				
				$docData['document_name'] = $document_name;
				$docData['document_image'] = $uploadData['file_name'];
				$image_name = $uploadData['file_name'];
				$docData['document_category_id'] = $document_category_id;
				
				
				if($admissionDocCount>0){
					$this->Common_model->updateRecordByConditions('admission_document',$admissionDocWhere,$docData);
				}else{
				
				$docData['student_id'] = $student_id;
				$docData['course_group_id'] = $course_group_id;
					$this->Common_model->insertAll('admission_document',$docData);
				}
				
				$msg = array(
				'success'=>"document Uploaded Successfully",
				'btn' => "<a href='".base_url('assets/documents/'.$image_name)."' download>
								Download
							</a>",
				);
					echo json_encode($msg);
					exit();
		}
		
		private function set_upload_options($path,$name)
		{   
			//upload an image options
			$config = array();
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|jpg|png|pdf|JPEG|jpeg';
			$config['max_size']      = '0';
			$config['overwrite']     = True;
			$config['file_name'] =  $name;
			
			return $config;
		}
		
		
		public function checkDocumentStatus(){
			$student_id =  html_escape($this->input->post('student_id'));;
			
			$course_group_id = html_escape($this->input->post('course_group_id'));
			
			$document_id = $this->Common_model->getSinglefield('course_group','document_id',' id='.$course_group_id);
			$documentData = $this->Common_model->get_record('document_category','*','category='.$document_id);
		
			foreach($documentData as $document){
			$admissionDocWhere = " student_id = ".$student_id." and document_category_id = ".$document['id'];
				
				$admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
				if($admissionDocCount==0){
					$msg = array('error'=>'Please Submit All Required Document');
					echo json_encode($msg);
					exit();
				}
				
			}
			$student['document_uploaded'] = 'Y';
			$where = 'student_id='.$student_id;
			$document_uploaded = $this->Common_model->updateRecordByConditions('student',$where,$student);
				$msg = array('success'=>'All Required Document Submited');
					echo json_encode($msg);
					exit();
		}
		
		public function remainingDocument($student_id){
			if($student_id!=''){
				$student = $this->Common_model->getRecordById('student','student_id',$student_id);
				$remark = $student->remark;
			$admissionDocWhere = " student_id = ".$student_id." and document_category_id in  (".$remark.") and status='N'";
			$admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
			$remarkCount= substr_count($remark,',');
			$remarkCount+=1;
			if($admissionDocCount==$remarkCount){
			$this->session->set_flashdata('warning',"Document Already Submited");
				redirect(base_url('student/dashboard'));
			}
				$where = ' id in ( '.$remark.' ) ';
				$document = $this->Common_model->getRecordByWhere('document_category',$where);
				$titleData = array('title' => 'Unapproved Document List');
				
				$data = array(
					'student' => $student,
					'documentData' => $document,
				);
			$this->load->view('Centers/header',$titleData);
			$this->load->view('Centers/remaining_document',$data);
			$this->load->view('Centers/footer');
			}
		}
		
		public function uploadRemainingDocument(){
			$student_id = html_escape($this->input->post('student_id'));
			$document_name = html_escape($this->input->post('document_name'));
			$document_category_id = html_escape($this->input->post('document_category_id'));
			$course_group_id = html_escape($this->input->post('course_group_id'));
			$admissionDocWhere = " student_id = ".$student_id." and document_category_id = ".$document_category_id." and status='N'";
			$admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
				
			$path = './assets/documents/';
			$this->load->library('upload');
				if($_FILES['document']['name']==''){
					echo 'an error occcerd';
					exit;
				}
				
				if($admissionDocCount>0){
				$nextid = $this->Common_model->getSinglefield('admission_document','id',$admissionDocWhere);
				}else{
				$nextid = $this->Common_model->getNextOrder('admission_document','id');
				}
				$this->upload->initialize($this->set_upload_options($path,$nextid));
				if(!$this->upload->do_upload('document')){
					$error = $this->upload->display_errors();
					$msg = array('error'=>$error);
					echo json_encode($msg);
					exit();
				}
				
			$uploadData = $this->upload->data();
			
			$docData['document_name'] = $document_name.' Not Found';
			$docData['document_image'] = $uploadData['file_name'];
			$image_name = $uploadData['file_name'];
			$docData['document_category_id'] = $document_category_id;
			$docData['status'] = 'N';

			if($admissionDocCount>0){
				$this->Common_model->updateRecordByConditions('admission_document',$admissionDocWhere,$docData);
				}else{
					$docData['student_id'] = $student_id;
					$docData['course_group_id'] = $course_group_id;
					$this->Common_model->insertAll('admission_document',$docData);
			}
			
			$msg = array('success'=>"Document Uploaded Successfully",
							'btn' => "<a href='".base_url('assets/documents/'.$image_name)."' download>Download</a>",
							);
			echo json_encode($msg);
			exit();
		}
	}