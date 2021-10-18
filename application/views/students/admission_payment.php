<div class="p-10 col-sm-6 m-auto">
	<img class="img img-fluid mb-4 col-6" src="<?=base_url('assets/images/university/sabpaisa-logo_new.png');?>">
	<div class="row border border-primary bg-primary text-custom p-2">
		Billing Details
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
		<div class="col-sm-4 border-right">Course
		</div>
		<div class="col-sm-8"><?=$this->Common_model->getCourseNameByCourseId($student['course_group_id'])?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Class
		</div>
		<div class="col-sm-8"><?=$this->Common_model->getClassNameByClassId($student['class_id'])?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Mobile
		</div>
		<div class="col-sm-8"><?=$userData->mobile_no?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Email
		</div>
		<div class="col-sm-8"><?=$userData->email?>
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
		$url = 'student/payment/admission_payment/';
	?>
	<a class="btn btn-default text-dark font-weight-bold" href="<?=base_url($url.$student['student_id']);?>">Pay Now</a>
</div>
</div>