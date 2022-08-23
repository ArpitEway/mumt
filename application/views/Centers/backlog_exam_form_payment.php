<div class="p-10 col-sm-6 m-auto">
	<div class="row mb-4 text-center">
		<div class="col-6">
	<img src="<?=base_url('assets/images/media/hdfc_logo.gif');?>">
		</div>
		<div class="col-6">
	<img src="<?=base_url('assets/images/media/payu_india.gif');?>">
		</div>
	</div>
	<div class="row border border-primary bg-primary text-custom p-2">
		Billing Details
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Session
		</div>
		<div class="col-sm-8"><?=$student['session']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Form No
		</div>
		<div class="col-sm-8"><?=$student['student_id']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Name
		</div>
		<div class="col-sm-8"><?=$student['name']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Father / Husband Name
		</div>
		<div class="col-sm-8"><?=$student['f_h_name']?>
		</div>
	</div>
	
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Course / Class
		</div>
		<div class="col-sm-8"><?=$student['course_name']?> / <?php  echo $this->Common_model->getClassNameByClassId($class_id);; ?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Mobile
		</div>
		<div class="col-sm-8"><?=$student['p_mobile_no']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Email
		</div>
		<div class="col-sm-8"><?=$student['p_email']?>
		</div>
	</div>
	<div class="row border border-primary bg-primary text-custom p-2">
		<div class="col-sm-4 border-right">Payment Details
		</div>
		<div class="col-sm-8">Amount
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right"><?=$paymentType?> 
		</div>
		<div class="col-sm-8"><?=$txnAmt?>
	</div>
		
	</div>
	<div class="row py-3 border justify-content-center">
	<?php
		$student_id = $this->Common_model->encrypt_decrypt($student['student_id']);
		$url = 'payment/backlog_exam_form_payment/'.$student_id;
	?>
	<a class="btn btn-default text-dark font-weight-bold" href="<?=base_url($url);?>">Pay Now</a>
</div>
</div>