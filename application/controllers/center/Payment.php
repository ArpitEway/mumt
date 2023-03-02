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
	
		$txnAmt = $this->Common_model->getRecordByWhere("course",array('course_group_id'=>$student['course_group_id']));

		if($student['payment_status']=='Y'){
			$this->session->set_flashdata('warning','Payment Already Submitted');
			redirect(base_url('dashboard'));
		}
		$data['student'] = $student;
		$data['url'] = 'paynow';
		$data['paymentType'] = 'admission';
		$mode = $this->input->post('mode');
		if($student['university_mode']=='REG'){
			$data['txnAmt'] = $txnAmt[0]->form_fees+$txnAmt[0]->admission_fees;
		}else{
			$data['txnAmt']= $txnAmt[0]->p_form_fees+ $txnAmt[0]->p_admission_fees;
		}
	
		
		$this->load->view('Centers/header',$titleData);
		$this->load->view('Centers/admission_payment',$data);
		$this->load->view('Centers/footer');
	}
	
	public function admission_payment($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('login'));
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		if($student_id!=''){

			$student = $this->Common_model->student_info($student_id);
	      	$txnAmt = $this->Common_model->getRecordByWhere("course",array('course_group_id'=>$student['course_group_id'],'session'=>$student['session']));

			if($student['university_mode']=='REG'){
				$mode = "Regular";
				$txnAmt = $txnAmt[0]->form_fees+$txnAmt[0]->admission_fees;
			}else{
				$mode = "Private";
				$txnAmt= $txnAmt[0]->p_form_fees+ $txnAmt[0]->p_admission_fees;
			}
			if($student['payment_status']=='Y'){
				$this->session->set_flashdata('warning','Payment Already Submitted');
				redirect(base_url('dashboard'));
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
			$posted['udf2'] = $mode;
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

		$date = date('Y-m-d'); 
		$time = date('h:i:s');
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
				"admission_type" =>$udf2,
			);
			$student = $this->Common_model->getRecordById('student','student_id',$student_id);

			$where = 'student_id='.$student_id.' and fees_head="'.$productinfo.'" and class_id='.$student->class_id;
			$txnData = $this->Common_model->get_record('online_payment_transaction','*',$where);

			if($productinfo == 'Admission Fees'){
			$this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$response);
				$status = 'payment_status';
				$txnid = $txnData[0]['id'];
			}elseif($productinfo == 'Exam Fees'){
				if(count($txnData)>0){
					$response["exam_session"] = $udf3;
					$this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$response);
					$txnid = $txnData[0]['id'];
				}else{ 
				$response['student_id'] = $student_id;
				$response['fees_head'] = $productinfo;
				$response['course_group_id'] = $student->course_group_id;
				$response['class_id'] = $student->class_id;
				$response['center_id'] = $student->center_id;
				$response['student_name'] = $student->name;
				$response['admission_type'] = $udf2;
				$response["exam_session"] = $udf3;

				$txnid = $this->Common_model->insertAll('online_payment_transaction',$response);
				}
				$status = 'new_exam_form';
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
			$id = $this->Common_model->encrypt_decrypt($txnid);
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
			redirect(base_url('dashboard'));
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

	public function exam_form($student_id){
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('login'));
		}else{
				$center_id =  $this->session->center_id;
				$center_permission = $this->Common_model->get_record('center','exam_form_permission',array('id'=>$center_id));
				if($center_permission[0]['exam_form_permission']!='Y'){
					$this->session->set_flashdata('error','Exam form fill & Payment Permission is denied !');
					redirect(base_url());
				}else{
						$titleData = array('title'=>'Exam Form Payment');
						$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
						$student = $this->Common_model->student_info($student_id);
						if($student['new_exam_form']=='Y'){
							$this->session->set_flashdata('warning','Payment Already Submitted');
							redirect(base_url('dashboard'));
						}

						$where = array(
							'session' =>$student['session'],
							'course_group_id' => $student['course_group_id'],
						);
						$fees = $this->Common_model->getRecordByWhere('course',$where);
						$data['student'] = $student;
						$data['url'] = 'paynow';
						$data['paymentType'] = 'Exam Fees';
						if ($student['university_mode']=='REG') {
							if($student['demo']=='Y'){
								$data['txnAmt'] = $fees[0]->exam_fees;
							}else{
								$data['txnAmt'] = $fees[0]->program_fees+$fees[0]->exam_fees;
							}
						}else{
							if($student['demo']=='Y'){
								$data['txnAmt'] = $fees[0]->p_exam_fees;
							}else{
								$data['txnAmt'] = $fees[0]->p_program_fees+$fees[0]->p_exam_fees;
							}
						}
						$this->load->view('Centers/header',$titleData);
						$this->load->view('Centers/exam_form_payment',$data);
						$this->load->view('Centers/footer');
					}	
			}			
	}


	public function exam_form_payment($student_id){
		
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('login'));
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		if($student_id!=''){
			$student = $this->Common_model->student_info($student_id);
			$where = array(
				'session' =>$student['session'],
				'course_group_id' => $student['course_group_id'],
			);
			$fees = $this->Common_model->getRecordByWhere('course',$where);
			if ($student['university_mode']=='REG') {
				$mode = "regular";
				if($student['demo']=='Y'){
					$txnAmt = $fees[0]->exam_fees;
				}else{
					$txnAmt = $fees[0]->program_fees+$fees[0]->exam_fees;
				}
			}else{
				$mode = "private";
				if($student['demo']=='Y'){
					$txnAmt = $fees[0]->p_exam_fees;
				}else{
					$txnAmt = $fees[0]->p_program_fees+$fees[0]->p_exam_fees;
				}
			}
			if($student['new_exam_form']=='Y'){
				$this->session->set_flashdata('warning','Payment Already Submitted');
				redirect(base_url('dashboard'));
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
			$posted['productinfo'] = "Exam Fees";
			$posted['address1'] = $student['p_address'];
			$posted['city'] = $student['p_city'];
			$posted['state'] = $student['p_state'];
			$posted['country'] = $student['nationality'];
			$posted['zipcode'] = $student['p_pin_code'];
			$posted['udf1'] = $student_id;
			$posted['udf2'] = $mode ;
			$posted['udf3'] = "Dec 2022";
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


   public function backlog_exam_form($student_id,$class_id){
   	if(!$this->session->has_userdata('centerdata')){
   		redirect(base_url('login'));
   	}
   	$titleData = array('title'=>'Exam Form Payment');
   	$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
   	$class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
   	$student = $this->Common_model->student_info($student_id);
    $failCount = $this->Common_model->getCountByWhere('backlog_exam_form',array('student_id'=>$student_id,'class_id'=>$class_id,'paper_type'=>'Theory' ,'status'=>'B'));
	if( $failCount < 8){
		$exam_fees =$failCount * 100;
	 }else{
		$exam_fees = 750; 
	 }
   	$data['txnAmt'] = $exam_fees;
   	$data['student'] = $student;
   	$data['class_id'] = $class_id;
   	$data['url'] = 'paynow';
   	$data['paymentType'] = 'Backlog Exam Fees';
   	$this->load->view('Centers/header',$titleData);
   	$this->load->view('Centers/backlog_exam_form_payment',$data);
   	$this->load->view('Centers/footer');
   }


    public function backlog_exam_form_payment($student_id,$class_id){
		
		if(!$this->session->has_userdata('centerdata')){
			redirect(base_url('login'));
		}
		$student_id = $this->Common_model->encrypt_decrypt($student_id,'decrypt');
		 $class_id = $this->Common_model->encrypt_decrypt($class_id,'decrypt');
		if($student_id!=''){
		   $student = $this->Common_model->student_info($student_id);		
  	    //    $exam_fess = 100; 	
           $failCount = $this->Common_model->getCountByWhere('backlog_exam_form',array('student_id' => $student_id,'class_id'=>$class_id,'paper_type'=>'Theory','status'=>'B'));
		   if( $failCount < 8){
			$exam_fees =$failCount * 100;
		 }else{
			$exam_fees = 750; 
		 }
   		   $txnAmt  =  $exam_fees;   
			$hash_string = '';
		/*  testing credential  
			$MERCHANT_KEY = "9WEOTe";
			$SALT = "uFYw7ClQ"; 
			$PAYU_BASE_URL = "https://test.payu.in";*/
		/*  live credential   */
			$MERCHANT_KEY = "h9OyBB";
			$SALT = "rzu8VRFb";
			$PAYU_BASE_URL = "https://secure.payu.in";
			$action = '';
			$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
			$posted = array();
			$posted['key'] = $MERCHANT_KEY;
			$posted['txnid'] = $txnid;
			$posted['surl'] =base_url('center/payment/backlog_response');
			$posted['furl'] =base_url('center/payment/backlog_response');
			$posted['amount'] =$txnAmt;
			$posted['firstname'] = $student['name'];
			$posted['email'] = $student['p_email'];
			$posted['phone'] = $student['p_mobile_no'];
			$posted['productinfo'] = "Backlog Exam Fees";
			$posted['address1'] = $student['p_address'];
			$posted['city'] = $student['p_city'];
			$posted['state'] = $student['p_state'];
			$posted['country'] = $student['nationality'];
			$posted['zipcode'] = $student['p_pin_code'];
			$posted['udf1'] = $student_id;
			$posted['udf2'] = $mode ;
			$posted['udf3'] = "Dec 2022";
			$posted['udf4'] = $student["center_id"].' / '.$class_id;
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


    public function backlog_response(){
    	$date = date('Y-m-d'); 
		$time = date('h:i:s');
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
		$class_exp = explode('/',$udf4);
		$class_id = $class_exp[1];

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
				"admission_type" =>$udf2,
			);
		$student = $this->Common_model->getRecordByWhere('backlog_student',array('student_id'=>$student_id,'class_id'=>$class_id));
       $student_name =  $this->Common_model->getSinglefield('student','name',array('student_id'=>$student_id));
			$where = 'student_id='.$student_id.' and fees_head="'.$productinfo.'" and class_id='.$class_id.' and exam_session= "'.$udf3.'"';
			$txnData = $this->Common_model->get_record('online_payment_transaction','*',$where);
			if($productinfo == 'Backlog Exam Fees'){
				if(count($txnData)>0){
					$response["exam_session"] = $udf3;
					$this->Common_model->updateRecordByConditions('online_payment_transaction',$where,$response);
					$txnid = $txnData[0]['id'];
				}else{
				$response['student_id'] = $student_id;
				$response['fees_head'] = $productinfo;
				$response['course_group_id'] = $student[0]->course_group_id;
				$response['class_id'] = $class_id;
				$response['center_id'] = $student[0]->center_id;
				$response['student_name'] = $student_name;
				$response['admission_type'] = $udf2;
				$response["exam_session"] = $udf3;

				$txnid = $this->Common_model->insertAll('online_payment_transaction',$response);
				}
				$status = 'exam_form';
			}
			if($payment=='Y'){
				$where = array('student_id'=>$student_id,'class_id'=>$class_id);
				$student = array($status=>'Y');
				$this->Common_model->updateRecordByConditions('backlog_student',$where,$student);
			}
			$this->session->set_flashdata($remsg,$msg);
			$id = $this->Common_model->encrypt_decrypt($txnid);
			redirect(base_url('center/payment/detail/'.$id));
		}
	}		
}