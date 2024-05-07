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
		<div class="col-sm-8"><?=$student['course_name']?> / <?=$student['class_name']?>
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
	$checkbox="";
		$student_id = $this->Common_model->encrypt_decrypt($student['student_id']);
		if(@($this->session->center_id) && ($this->session->admission_by!="web" ) ){
		$url = 'center/payment/admission_payment/'.$student_id;
		}elseif($paymentType=="Form Fees"){
			$url = 'Payment/formfees_payment/'.$student_id;
		}
		elseif($paymentType=="admission"){
			$url = 'Payment/admission_payment/'.$student_id;
			
		}

		if($this->session->admission_by=="web"  && $paymentType=="Form Fees"){
			$checkbox='<p style="padding: 5px;font-weight: 600;"><input  type="checkbox" name="iagree" id="iagree" > यह केवल ऑनलाइन फॉर्म फी Rs. 200 ली जा रही है, जो कि किसी भी परिस्थिति में वापस नहीं की जाएगी| एलिजिबिलिटी या डॉक्यूमेंट की कमी होने पर फॉर्म निरस्त किया जा सकता है| </p> ';
			$urlhide=$url;
			$url="";
		}
		echo $checkbox;
	?>
	<a id="btn"  class="btn btn-default text-dark font-weight-bold <?php if(!empty($checkbox)) { echo  'disabled'; } ?>" href="<?=base_url($url);?>">Pay Now</a>
</div>
</div>
<script>
var url='<?=base_url($urlhide);?>';
$('#iagree').change(function() {
        if(this.checked) {
			$('#btn').removeClass("disabled");
			$('#btn').attr("href", url); 
			console.log(" checked "+url);   
			
        }
		else{
			  
			$('#btn').addClass('disabled'); 
			$('#btn').attr("href", '#'); 
			console.log("unchecked");   
           
		}
           
    });
</script>