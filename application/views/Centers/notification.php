<style>
.doc-notification	li{
    line-height: 2.5;
}
.doc-notification {
    background: beige;
    padding: 15px 15px 0px;
	border-radius: 20px;
}
</style>
<?php 
	if($student->program_fees =='N'){
		if($student->approved =='N'){
			$admissionDocWhere = " student_id = ".$student->student_id." and document_category_id in  (".$student->remark.") and status='N'";
			$admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
			$remarkCount= substr_count($student->remark,',');
			$remarkCount+=1;
			if($admissionDocCount!=$remarkCount){
			?>
<div class="alert alert-custom alert-notice alert-light-warning fade show px-3 py-2 d-inline-flex" role="alert">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">Please Submit Your remaining Document <a class="font-weight-bold" href="<?=base_url('student/Document/list')?>">Click Here</a></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
<?php 
			}
	}else if($student->approved=='Y' ){ ?>
	<div class="alert alert-custom alert-notice alert-light-success fade show px-3 py-2 d-inline-flex" role="alert">
    <div class="alert-icon"><i class="flaticon-price-tag"></i></div>
    <div class="alert-text">Please Submit Your Program Fees <a class="font-weight-bold" href="<?=base_url('student/payment/all_payment')?>">Click Here</a></div>
    <div class="alert-close">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="ki ki-close"></i></span>
        </button>
    </div>
</div>
	<?php
}
if($student->document_uploaded=='Y' && $student->approved!='Y'){
?>
<div class="doc-notification">
<p><strong>आपके डाक्यूमेंट्स सफलतापूर्वक अपलोड हो चुके हैं.</strong></p>
<p>आगामी प्रक्रिया निम्नानुसार रहेगी-</p>
<ul>
	<li>
विवि प्रशासन द्वारा डाक्यूमेंट्स वेरीफाई किये जावेंगे. किसी तरह की कमी पाई जाने पर इसी लॉगइन पर सूचित एवं निर्देशित किया जायेगा.
</li><li>
डाक्यूमेंट्स के approve होने के पश्चात् आपको इसी लॉगइन पर प्रोग्राम फीस पेमेंट का आप्शन दिखने लगेगा. आपको उसी विकल्प से प्रोग्राम फीस का भुगतान करना होगा. कृपया अन्य किसी माध्यम से प्रोग्राम फीस का भुगतान ना करें.
</li>
</div>
<?php
}
	}
?>