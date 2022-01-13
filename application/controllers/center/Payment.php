<?php
defined('BASEPATH') OR exit('No direct script access allowed');	
class Payment extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('Center/Center_model');
	}
	
	public function admission($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/login'));
		}
		$titleData = array('title'=>'Admission Payment');
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		$student = $this->Common_model->student_info($student_id);
		if($student['payment_status']=='Y'){
			$this->session->set_flashdata('warning','Payment Already Submitted');
			redirect(base_url('center/dashboard'));
		}
		$data['student'] = $student;
		$data['url'] = 'paynow';
		$data['paymentType'] = 'admission';
		$data['txnAmt'] = 1500;
		
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/admission_payment',$data);
		$this->load->view('Centers/footer');
	}
	
	public function admission_payment($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/login'));
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		if($student_id!=''){
			$student = $this->Common_model->student_info($student_id);
			$txnAmt=1500;
			if($student['payment_status']=='Y'){
				$this->session->set_flashdata('warning','Payment Already Submitted');
				redirect(base_url('center/dashboard'));
			}
			$hash_string = '';
		/*  testing credential 
			$MERCHANT_KEY = "9WEOTe";
			$SALT = "uFYw7ClQ"; 
			$PAYU_BASE_URL = "https://test.payu.in"; */
		/*  live credential  */
			$MERCHANT_KEY = "h9OyBB";
			$SALT = "rzu8VRFb";
			$PAYU_BASE_URL = "https://secure.payu.in";
		
			$action = '';
			$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);

			$posted = array();
			$posted['key'] = $MERCHANT_KEY;
			$posted['txnid'] = $txnid; 
			$posted['surl'] =base_url('center/payment/response');
			$posted['furl'] =base_url('center/payment/response');
			$posted['amount'] =$txnAmt;
			$posted['firstname'] = $student['name'];
			$posted['email'] = $student['p_email'];
			$posted['phone'] = $student['p_mobile_no'];
			$posted['productinfo'] = "Admission Fees";
			$posted['address1'] = $student['p_address'];
			$posted['city'] = $student['p_city'];
			$posted['state'] = $student['p_state'];
			$posted['country'] = $student['nationality'];
			$posted['zipcode'] = $student['p_pin_code'];
			$posted['udf1'] = $student_id;
			$posted['udf2'] = "Regular";
			$posted['udf3'] = "-";
			$posted['udf4'] = $student["center_id"].' / '.$student['class_id'];
			$posted['udf5'] = $student["name"]."/".$student["f_h_name"];
			$hash = '';

			$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

			$hashVarsSeq = explode('|', $hashSequence);

			foreach($hashVarsSeq as $hash_var) {
				$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
				$hash_string .= '|';
			}

			$hash_string .= $SALT;
			$hash = strtolower(hash('sha512', $hash_string));
			$action = $PAYU_BASE_URL . '/_payment';
			$posted['hash'] = $hash;
			$posted['action'] = $action;
			$this->load->view('template/payment_submit',$posted);
		}
	}
		
	public function response(){

		$date=date('Y-m-d'); 
		$time=date('h:i:s');
		$student_id=$_POST["udf1"];
		$udf2=$_POST["udf2"];
		$udf3=$_POST["udf3"];
		$udf4=$_POST["udf4"];
		$udf5=$_POST["udf5"];
		$status=$_POST["status"];
		$firstname=$_POST["firstname"];
		$amount=$_POST["amount"]; 
		$txnid=$_POST["txnid"];
		$posted_hash=$_POST["hash"];
		$key=$_POST["key"]; 
		$productinfo=$_POST["productinfo"];
		$email=$_POST["email"];
		$salt="rzu8VRFb"; 

		If (isset($_POST["additionalCharges"])) {

			$additionalCharges=$_POST["additionalCharges"];

			$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||'.$udf5.'|'.$udf4.'|'.$udf3.'|'.$udf2.'|'.$student_id.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

		}else {
			$retHashSeq = $salt.'|'.$status.'||||||'.$udf5.'|'.$udf4.'|'.$udf3.'|'.$udf2.'|'.$student_id.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
		}
		$hash = hash("sha512", $retHashSeq);
		 if ($hash != $posted_hash) {

		 	$this->session->set_flashdata('error','Transaction has been tampered. Please try again');
		 	redirect(base_url('center'));

		 }else{

			$payment = ($status=='success') ? 'Y' : 'N';
			$remsg = ($status=='success') ? 'success' : 'error';
			$msg = ($status=='success') ? 'Payment submitted Successfully' : 'An error occurred';

			$response = array(
				"student_id" => $student_id,
				"amount" => $amount,
				"fees_head" => $productinfo,
				"payment" => $payment, 
				"payment_status" => $status,
				"payment_date" => $date,
				"payment_time" => $time,
				"txnId" => $txnid,
				"admission_type" => 'Regular',
			);
			$where = 'student_id='.$student_id.' and fees_head="'.$productinfo.'"';
			$txnData = $this->Common_model->get_record('online_payment_transaction','*',$where);
			$this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$response);

			if($productinfo == 'Admission Fees'){
				$status = 'payment_status'; 	
			}
			if($payment=='Y'){
				$where = 'student_id='.$student_id;
				$student = array($status=>'Y');
				$this->Common_model->updateRecordByConditions('student',$where,$student);
			}
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);
			$sessionData = $data = array('loged_in' => true,
				'centerdata' => $student->center_code,
				'center_id' => $student->center_id,
				'account_type' => 'center'
			);
			$this->session->set_userdata($sessionData);
			$this->session->set_flashdata($remsg,$msg);
			
			$id = $this->Common_model->encrypt_decrypt($txnData[0]['id']);
			redirect(base_url('center/payment/detail/'.$id));
		}
	}
		
	public function detail($id){
	if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('center/login'));
		}
		$id = $this->Common_model->encrypt_decrypt($id,'decrypt');
		$where = 'id='.$id;
		$transaction = $this->Common_model->get_record('online_payment_transaction','*',$where);
		if($transaction[0]['center_id']!=$this->session->center_id){
			$this->session->set_flashdata('error','Details Not Found');
			redirect(base_url('center/dashboard'));
		}
		$wherestudent = 'student_id='.$transaction[0]['student_id'];
		$student = $this->Common_model->get_record('student','*',$wherestudent);
		
		$data = array(
		'student' => $student[0],
		'transaction' => $transaction[0],
		);
		$titleData = array('title'=>'Payment Details');
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/payment_detail',$data);
		$this->load->view('Centers/footer');
	}
}