<?php
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Document extends CI_Controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('Common_model');
			$this->load->model('Students/Student_model');
			if(!$this->session->account_type=='master'){
				exit();
			}
		}
		
		public function uploadDoc(){
			$student_id = $this->input->post('student_id');
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
					$error = $this->session->set_flashdata('error',$this->upload->display_errors());
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
			$documentData = $this->Common_model->get_record('document_category','*','document_id='.$document_id.' and status="Y"');
		
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
	}