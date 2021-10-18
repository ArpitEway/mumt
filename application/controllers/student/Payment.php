<?php
defined('BASEPATH') OR exit('No direct script access allowed');	
class Payment extends CI_Controller {
	
	private $spDomain="https://securepay.sabpaisa.in/SabPaisa/sabPaisaInit";
	private $username="bhabesh.jha_3287"; 
	private $password="MPSEV_SP3287"; //Password provided by Sabpaisa(Mandatory)
	private $clientCode="MPSEV"; //Client Code Provided by Sabpaisa(Mandatory)
	private $authKey="ftrL8lTjk7ZuEp6b"; //Authentication Key Provided BySabpaisa
	private $authIV="k7a1h0z8FZNcHckf"; //Authentication IV Provided bySabpaisa

	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Students/Student_model');
		include APPPATH . 'third_party/AesCipher.php';
	}
	
	public function admission($student_id){
		if(!$this->session->has_userdata('studentdata')){
			redirect(base_url('student/login'));
		}
		$titleData = array('title'=>'Admission Payment');
		$wherestudent = 'student_id='.$student_id;
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		$userData = $this->Common_model->getRecordById('user_enquiry','id',$student[0]['user_id']);
		if($student[0]['payment_status']=='Y'){
			$this->session->set_flashdata('warning','Payment Already Submitted');
			redirect(base_url('student/dashboard'));
		}

		$data['student'] = $student[0];
		$data['userData'] = $userData;
		$data['url'] = 'paynow';
		$data['paymentType'] = 'Admission';
		$data['txnAmt'] = 272;
		
		$this->load->view('students/header',array('title'=> ''));
		$this->load->view('students/admission_payment',$data);
		$this->load->view('students/footer');
	}
	
	public function admission_payment($student_id){
		if(!$this->session->has_userdata('studentdata')){
				redirect(base_url('student/login'));
			}
			if($student_id!=''){
				
				$wherestudent = 'student_id='.$student_id;
				$userData = $this->Common_model->getRecordById('user_enquiry','student_id',$student_id);
				$student = $this->Common_model->get_record('student','*',$wherestudent);
				$txnAmt="272"; //Transaction Amount (Mandatory)
				if($student[0]['payment_status']=='Y'){
					$this->session->set_flashdata('warning','Payment Already Submitted');
					redirect(base_url('student/dashboard'));
				}
				
				
				$txnId = substr(hash('sha256', mt_rand() . microtime()), 0, 20); //Transaction ID(Mandatory)
				$URLsuccess=base_url('student/payment/response'); //Return URL upon successful transaction(Mandatory)
				$URLfailure=base_url('student/payment/response'); //Return URL upon failed Transaction(Mandatory)
				
				$sname = explode (' ', $student[0]['name']);
				$payerFirstName= $sname[0]; //Payer's First Name (Optional)
				$payerLastName=$sname[1]; //Payer's Last Name(Optional)
				$payerContact=$userData->mobile_no;//Payer's Contact Number(Mandatory)
				$payerEmail=$userData->email; //Payer's Email Address(Mandatory)
				$payerAddress=''; //Payer's Address (Optional)
				
				
				$udf5 = $student_id;
				
				$udf6 = "MPSVV";
				
				$udf7 = 'admission';
				
				$udf8 = $student[0]["course_group_id"];
				$udf9 = $student[0]["class_id"];
				
				$udf10 = $student[0]["name"]."/".$student[0]["f_h_name"];
				
				$spURL =
				"?clientName=".$this->clientCode."&usern=".$this->username."&pass=".$this->password."&amt=".$txnAmt."&txnId=".$txnId."&firstName=".$payerFirstName."&lstName=".$payerLastName."&contactNo=".$payerContact."&Email=".$payerEmail."&Add=".$payerAddress."&ru=".$URLsuccess."&failureURL="
				.$URLfailure."&udf5=".$udf5."&udf6=".$udf6."&udf7=".$udf7."&udf8=".$udf8."&udf9=".$udf9."&udf10=".$udf10;
				
				$AesCipher = new AesCipher();
				$spURL = $AesCipher->encrypt($this->authKey,$this->authIV,$spURL);
				$spURL = str_replace("+", "%2B",$spURL);
				$spURL="?query=".$spURL."&clientName=".$this->clientCode;
				$spURL = $this->spDomain.$spURL;
				//print_r($spURL);
				redirect($spURL);	
			}
	}
		
	public function response(){
			$query=$_REQUEST['query'];
			$authKey='ftrL8lTjk7ZuEp6b';
			$authIV='k7a1h0z8FZNcHckf';
			$decText = null;
			$AesCipher = new AesCipher();
			$decText = $AesCipher->decrypt($authKey,$query);
			$decText = array_map (
			function ($_) {return explode ('=', $_);},
			explode ('&', $decText)
			);
			/* 			echo "<pre>";
				print_r($decText);
			die; */
			$payment = ($decText['18']['1']=='SUCCESS') ? 'Y' : 'N';
			$remsg = ($decText['18']['1']=='SUCCESS') ? 'success' : 'error';
			$msg = ($decText['18']['1']=='SUCCESS') ? 'Payment submited Successfuly' : 'An error occurred';
			$paymentDateTime = $decText['17']['1'];
			$paymentDateTime = explode (' ', $paymentDateTime);
			
			$response = array(
			"student_id" => $decText['30']['1'],
			"course_group_id" => $decText['33']['1'],
			"class_id" => $decText['34']['1'],
			"amount" => $decText['22']['1'],
			"fees_head" => $decText['32']['1'],
			"student_name" => $decText['7']['1'].' '.$decText['8']['1'],
			"payment" => $payment, 
			"payment_status" => $decText['18']['1'],
			"payment_date" => $paymentDateTime['0'],
			"payment_time" => $paymentDateTime['1'],
			"PGTxnNo" => $decText['1']['1'],
			"SabPaisaTxId" => $decText['2']['1'],
			"issuerRefNo" => $decText['3']['1'],
			"clientTxnId" => $decText['6']['1'],
			);
			
			$where = 'fees_head="'.$decText['32']['1'].'" and '.'student_id='.$decText['30']['1'];
			$paymentEntryCount = $this->Common_model->getCountByWhere('online_payment_transaction',$where);
			if($paymentEntryCount>0){
				$id = $this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$response);
				}else{
				$id = $this->Common_model->insertAll('online_payment_transaction',$response);
			}
			$status = ($decText['32']['1'] == 'admission') ? 'payment_status' : 'program_fees';
			
			if($payment=='Y'){
				$where = 'student_id='.$decText['30']['1'];
				$student = $this->Common_model->get_record('student','*',$where);
				$student = array($status=>'Y');
				if($student[0]['installment_permission']=='Y'  && $status='program_fees'){
					$txnAmt=$txnAmt/2;
				$student = array($status=>'H');
				}
				$this->Common_model->updateRecordByConditions('student',$where,$student);
			}
			
			$this->session->set_flashdata($remsg,$msg);
			redirect(base_url('student/payment/detail/'.$id));
	}
		
	public function detail($id){
	if(!$this->session->has_userdata('studentdata')){
			redirect(base_url('student/login'));
		}
		$where = 'id='.$id;
		$transaction = $this->Common_model->get_record('online_payment_transaction','*',$where);
		if($transaction[0]['student_id']!=$this->session->student_id){
			$this->session->set_flashdata('error','Details Not Found');
			redirect(base_url('student/dashboard'));
		}
		$wherestudent = 'student_id='.$transaction[0]['student_id'];
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		
		$data = array(
		'student' => $student[0],
		'transaction' => $transaction[0],
		);
		$titleData = array('title'=>'Payment Details');
		$this->load->view('students/header',$titleData);
		$this->load->view('students/payment_detail',$data);
		$this->load->view('students/footer');
	}
	
	public function program_fees($student_id){
		if(!$this->session->has_userdata('studentdata')){
			redirect(base_url('student/login'));
		}
		$wherestudent = 'student_id='.$student_id;
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		
		$txnAmt = $this->Common_model->getStudentProgramFeeByClass($student[0]['course_group_id'],$student[0]['class_id'],$student[0]['gender']);
		if($student[0]['installment_permission']=='Y'){
			$txnAmt=$txnAmt/2;
		}
			
		if($student[0]['approved']!='Y'){
			$this->session->set_flashdata('error','Wait Until You Get Approved');
			redirect(base_url('student/dashboard'));
			}else if($student[0]['program_fees']=='Y'){
			$this->session->set_flashdata('warning','Payment Already Submitted');
			redirect(base_url('student/dashboard'));
		}
		$studentData = $this->Common_model->get_record('student_data','*',$wherestudent);
		$data['student'] = $student[0];
		$data['studentData'] = $studentData[0];
		$data['url'] = 'payProgramFess';
		$data['paymentType'] = 'Program Fess';
		$data['txnAmt'] = $txnAmt;
		
		$this->load->view('students/header',array('title'=> 'Program Fess'));
		$this->load->view('students/payment',$data);
		$this->load->view('students/footer');
	}

	public function payProgramFess($student_id){
		if(!$this->session->has_userdata('studentdata')){
			redirect(base_url('student/login'));
		}
		if($student_id!=''){
			
			$wherestudent = 'student_id='.$student_id;
			$student = $this->Common_model->get_record('student','*',$wherestudent);
			$studentData = $this->Common_model->get_record('student_data','*',$wherestudent);
			$whereCourse = "course_group_id=".$student[0]['course_group_id'];
			$courseData = $this->Common_model->get_record('course','*',$whereCourse);
			$txnAmt = $this->Common_model->getStudentProgramFeeByClass($student[0]['course_group_id'],$student[0]['class_id'],$student[0]['gender']);
			if($student[0]['installment_permission']=='Y'){
				$txnAmt=$txnAmt/2;
			}
			if($student[0]['approved']!='Y'){
				$this->session->set_flashdata('error','Wait Until You Get Approved');
				redirect(base_url('student/dashboard'));
				}else if($student[0]['program_fees']=='Y'){
				$this->session->set_flashdata('warning','Payment Already Submitted');
				redirect(base_url('student/dashboard'));
			}
			
			
			$txnId = substr(hash('sha256', mt_rand() . microtime()), 0, 20); //Transaction ID(Mandatory)
			$URLsuccess=base_url('student/payment/response'); //Return URL upon successful transaction(Mandatory)
			$URLfailure=base_url('student/payment/response'); //Return URL upon failed Transaction(Mandatory)
			
			$sname = explode (' ', $student[0]['name']);
			$payerFirstName= $sname[0]; //Payer's First Name (Optional)
			$payerLastName=$sname[1]; //Payer's Last Name(Optional)
			$payerContact=$studentData[0]['p_mobile_no']; //Payer's Contact Number(Mandatory)
			$payerEmail=$studentData[0]['p_email']; //Payer's Email Address(Mandatory)
			$payerAddress=$studentData[0]['p_city']; //Payer's Address (Optional)
			
			
			$udf5 = $student_id;
			
			$udf6 = "MPSVV";
			
			$udf7 = 'Program Fees';
			
			$udf8 = $student[0]["course_group_id"];
			$udf9 = $student[0]["class_id"];
			
			$udf10 = $student[0]["name"]."/".$student[0]["f_h_name"];
			
			$spURL =
			"?clientName=".$this->clientCode."&usern=".$this->username."&pass=".$this->password."&amt=".$txnAmt."&txnId=".$txnId."&firstName=".$payerFirstName."&lstName=".$payerLastName."&contactNo=".$payerContact."&Email=".$payerEmail."&Add=".$payerAddress."&ru=".$URLsuccess."&failureURL="
			.$URLfailure."&udf5=".$udf5."&udf6=".$udf6."&udf7=".$udf7."&udf8=".$udf8."&udf9=".$udf9."&udf10=".$udf10;
			
			//print_r($spURL);
			$AesCipher = new AesCipher();
			$spURL = $AesCipher->encrypt($this->authKey,$this->authIV,$spURL);
			$spURL = str_replace("+", "%2B",$spURL);
			$spURL="?query=".$spURL."&clientName=".$this->clientCode;
			$spURL = $this->spDomain.$spURL;
			redirect($spURL);	
		}
	}

	public function getStudentProgramFeeByClass($course_group_id,$class_id,$gender){

		$whereCourse = "course_group_id=".$course_group_id;
		$courseData = $this->Common_model->get_record('course','*',$whereCourse);
		$txnAmt = ($gender=='Male') ? $courseData[0]['program_fees_male'] : $courseData[0]['program_fees_female'];
		$classData = $this->Common_model->getRecordById('class_master','id',$class_id);
		return ($classData->admission_permission=='Y') ?	$txnAmt : $txnAmt-500;
	}
	public function all_payment(){
			
			if(!$this->session->has_userdata('studentdata')){
				redirect(base_url('student/login'));
			}

			$user_id = $this->session->Users_id;

			$userData = $this->Common_model->getRecordById('user_enquiry','id',$user_id);

			$student_id = $userData->student_id;
			$whereStudent = 'student_id IN ('.$student_id.')';

			$data = array(
				'payments' => $this->Common_model->getRecordByWhere('online_payment_transaction',$whereStudent)
			);
			$this->load->view('students/header',array('title'=> 'Payment List'));
			$this->load->view('students/view_student_payment',$data);
			$this->load->view('students/footer');
		}
}