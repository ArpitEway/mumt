<?php 
$studentData = $this->Common_model->getRecordById('student','student_id',$this->session->student_id);
$whereClass = array('class_id' => $studentData->class_id,
	'exam_permission' => 'Y',
);
$timeTableData = $this->Common_model->getRecordByWhere('time_table',$whereClass);
?>
<?php if (count($timeTableData)!=0 && $studentData->new_exam_form=="Y"): ?>
	<div class="row justify-content-center align-items-center" >
		<h5>Click Here to Examination</h5>

	<a href="<?=base_url('exam_paper')?>" class="btn btn-primary float-right ml-2"> Exam Paper</a>
</div>
<?php endif ?>
<div class="notification"> 
	<ul>
		<li>Dec 2021 परीक्षा कार्यक्रम - <strong><a target="_blank" href="<?=base_url('assets/instruction/') ?>examdate.pdf">Click Here</a></strong></li>
	</ul>
	<h5 class="mt-4 mb-3">आंसरशीट अपलोड करने की प्रक्रिया</h5>
	<ol>
		<li>उत्तर पुस्तिका के लिए फ्रंट पेज <a target="_blank" href="<?=base_url('assets/instruction/')?>Answer sheet Front Page.pdf"> Click Here</a>
		</li>
		<li>प्रत्येक पेपर के आंसरशीट हल करके उसकी PDF अपलोड कर सकते है| PDF फाइल की साइज 5 MB से ज्यादा नहीं होना चाहिए|</li>
	</ol>
</div>