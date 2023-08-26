<div class="row mt-2">
	<div class="col-12 col-md-3 col-sm-12 menu-background p-3" >
		<ul class="nav flex-column nav-pills">
			<li class="nav-item mb-2">
				<a class="nav-link active show  border" id="Enrollment-tab-Regular" data-toggle="tab" href="#Enrollment_Regular">
					<span class="nav-text">Enrollment Regular</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
			<li class="nav-item mb-2">
				<a class="nav-link border" id="Enrollment-tab-Private" data-toggle="tab" href="#Enrollment_Private">
					<span class="nav-text">Enrollment Private</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
		 	<li class="nav-item mb-2">
				<a class="nav-link border" id="payment-tab" data-toggle="tab" href="#payment">
					<span class="nav-text">Account</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
			<li class="nav-item mb-2">
				<a class="nav-link border" id="exam-tab" data-toggle="tab" href="#exam">
					<span class="nav-text">Examination</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-8 col-12 col-sm-12 menu-background p-3">
		<div class="tab-content">
			<div class="tab-pane fade" id="Enrollment_Private" role="tabpanel" aria-labelledby="Enrollment-tab-Private">
				<div class="row">

					
					<a class="border-0 custom-menu-item" href="<?=base_url('instruction_private');?>">
						<div>
							<span class="nav-text">Private Course Details</span>
						</div>
					</a>
				 
				 <?php
				$center_id =  $this->session->center_id;	
				$center_ids_dep = array(21,22,23,24,25,26,27,28,29);
				  if($center->admission_permission_private=='Y' && !in_array($center_id, $center_ids_dep)){
					?>
					<a class="border-0 custom-menu-item kt_popup_private">
						<div>
							<span class="nav-text">Admission Form Private</span>
						</div>
					</a>
				<?php
				  }
				  ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('all_student/PVT');?>">
						<div>
							<span class="nav-text">Student Report</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student_list/unpaid/PVT');?>">
						<div>
							<span class="nav-text">Unpaid Student</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('Document/index/PVT');?>">
						<div>
							<span class="nav-text">Upload Admission Document</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('not_approve_student_list/PVT');?>">
						<div>
							<span class="nav-text">Unapproved Student List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('form_edit_request/PVT');?>">
						<div>
							<span class="nav-text">Form Edit Request</span>
						</div>
					</a>
					
					<!-- <a class="border-0 custom-menu-item" href="<?=base_url('admission_mode_edit_request/PVT');?>">
						<div>
							<span class="nav-text">Mode Change Request (Private to Regular)</span>
						</div>
					</a> -->
					
					<a class="border-0 custom-menu-item" href="<?=base_url('paper_missing_list/private');?>">
						<div>
							<span class="nav-text">Paper Missing List</span>
						</div>
					</a>	
				</div>
			</div>
			<div class="tab-pane fade active show" id="Enrollment_Regular" role="tabpanel" aria-labelledby="Enrollment-tab-Regular">
				<div class="row">

					<a class="border-0 custom-menu-item" href="<?=base_url('instruction');?>">
						<div>
							<span class="nav-text">Regular Course Details</span>
						</div>
					</a>
					<!-- <a class="border-0 custom-menu-item" href="<?=base_url('instruction_private');?>">
						<div>
							<span class="nav-text">Private Course Details</span>
						</div>
					</a> -->
				  <?php
				  $center_id =  $this->session->center_id;	
				//  $center_ids_dep = array(10,11,12,21,22,23,24,25,26,27,28,29,13);
				//  if($center->admission_permission=='Y' || in_array($center_id, $center_ids_dep)){
					if($center->admission_permission=='Y' ){
					  ?>
	        <a class="border-0 custom-menu-item kt_popup" >
						<div>
							<span class="nav-text">Admission Form Regular</span>
						</div>
					</a>
				<?php
				  }
				  ?>
				 <?php
				/* $center_id =  $this->session->center_id;	
				$center_ids_dep = array(21,22,23,24,25,26,27,28,29);
				  if($center->admission_permission_private=='Y' && !in_array($center_id, $center_ids_dep)){
					?>
					<a class="border-0 custom-menu-item" href="<?=base_url('admission_form/private');?>">
						<div>
							<span class="nav-text">Admission Form Private</span>
						</div>
					</a>
				<?php
				  } */
				  ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('all_student/REG');?>">
						<div>
							<span class="nav-text">Student Report</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student_list/unpaid/REG');?>">
						<div>
							<span class="nav-text">Unpaid Student</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('Document/index/REG');?>">
						<div>
							<span class="nav-text">Upload Admission Document</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('not_approve_student_list/REG');?>">
						<div>
							<span class="nav-text">Unapproved Student List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('form_edit_request/REG');?>">
						<div>
							<span class="nav-text">Form Edit Request</span>
						</div>
					</a>
					
					<a class="border-0 custom-menu-item" href="<?=base_url('admission_mode_edit_request/REG');?>">
						<div>
							<span class="nav-text">Mode Change Request (Regular to Private)</span>
						</div>
					</a>
					
					<a class="border-0 custom-menu-item" href="<?=base_url('paper_missing_list/regular');?>">
						<div>
							<span class="nav-text">Paper Missing List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('photo_missing_list');?>">
						<div>
							<span class="nav-text">Photo Missing List</span>
						</div>
					</a>
				</div>
			</div>
			<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
				<div class="row">
					<a class="border-0 custom-menu-item" href="<?=base_url('student_list/paid');?>">
						<div>
							<span class="nav-text">Paid Student</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('payment_complaint');?>">
						<div>
							<span class="nav-text">Payment Complaint </span>
						</div>
					</a>
				</div>
			</div> 
			<div class="tab-pane fade" id="exam" role="tabpanel" aria-labelledby="exam-tab">
				<div class="row">
				
				<?php if ($center->exam_form_permission=='Y'): ?>
				 <a class="border-0 custom-menu-item" href="<?=base_url('exam_form_students');?>">
							<div>
								<span class="nav-text">Exam Form July 2023</span>
							</div>
					</a> 
					<a class="border-0 custom-menu-item" href="<?=base_url('backlog_exam_form_students');?>">
							<div>
								<span class="nav-text">Backlog Exam Form July 2023</span>
							</div>
					</a>  
					<?php endif ?>
					<?php 
					/* $count = $this->Common_model->getCountByWhere('student',array('center_id'=>$center->id,'new_exam_form !='=>'D'));
					//  && $count>0
					if ( $center->exam_form_permission=='Y' ){ ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('exam_form_students');?>">
							<div>
								<span class="nav-text">Exam Form July 2023</span>
							</div>
					</a> 
					<?php }*/
					//  if($count>0){ ?>
				 
					<?php //} ?>
					<?php if($this->session->center_id==12 || $this->session->center_id==28){ ?>
					 <a class="border-0 custom-menu-item" href="<?=base_url('practical_marks_list');?>">
						<div>
							<span class="nav-text">Practical Marks Submission (Regular)</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('internal_marks_list');?>">
						<div>
							<span class="nav-text">Internal Marks Submission (Regular)</span>
						</div>
					</a> 
					<?php } ?>
					<?php if ($center->result_permission=='Y'): ?>
						<a class="border-0 custom-menu-item" href="<?=base_url('result');?>">
							<div>
								<span class="nav-text">Result(March 2023)</span>
							</div>
						</a>
						<a class="border-0 custom-menu-item" href="<?=base_url('backlog_result');?>">
							<div>
								<span class="nav-text">Backlog Result (March 2023)</span>
							</div>
						</a>
					<?php endif ?>
					<!-- <a class="border-0 custom-menu-item" href="<?=base_url('search_exam_by_course');?>">
					 	<div>
							<span class="nav-text">Time Table July 2023</span>
					 	</div>
					</a>   -->
					
					

				<!--	<a class="border-0 custom-menu-item" href="<?=base_url('student_roll_no_list');?>">
						<div>
							<span class="nav-text">Roll No List</span>
						</div>
					</a> -->
					
					<?php /*if ($center->admit_card_permission=='Y'): ?>
						<a class="border-0 custom-menu-item" href="<?=base_url('admit_card_list');?>">
						<div>
							<span class="nav-text">Admit Card</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('admit_card_backlog_student_list');?>">
						<div>
							<span class="nav-text">Backlog Admit Card</span>
						</div>
					</a> 
					<?php endif */ ?>

				</div>
			</div> 
		</div>
	</div>
</div>

	<script type="text/javascript">
$(".kt_popup").click(function(e) {
 
Swal.fire({
	 text:  "एडमिशन सेशन चेक करने के बाद ही फॉर्म भरे, एक बार फॉर्म भरने के पश्चात् सेशन में कोई परिवर्तन नहीं होगा|"
}).then((result) => {
  if (result.isConfirmed) {	
  	window.location.href =  "<?=base_url('admission_form/regular');?>";
      }
})

});

$(".kt_popup_private").click(function(e) {
 
Swal.fire({
	 text:  "एडमिशन सेशन चेक करने के बाद ही फॉर्म भरे, एक बार फॉर्म भरने के पश्चात् सेशन में कोई परिवर्तन नहीं होगा|"
}).then((result) => {
  if (result.isConfirmed) {	
  	window.location.href =  "<?=base_url('admission_form/private');?>";
      }
})

});

</script>