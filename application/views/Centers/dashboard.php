<div class="row mt-2">
	<div class="col-12 col-md-3 col-sm-12 menu-background p-3" >
		<ul class="nav flex-column nav-pills">
			<li class="nav-item mb-2">
				<a class="nav-link active show border" id="Enrollment-tab" data-toggle="tab" href="#Enrollment">
					<span class="nav-text">Enrollment</span>
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
			<div class="tab-pane fade active show" id="Enrollment" role="tabpanel" aria-labelledby="Enrollment-tab">
				<div class="row">

					<a class="border-0 custom-menu-item" href="<?=base_url('instruction');?>">
						<div>
							<span class="nav-text">Regular Course Details</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('instruction_private');?>">
						<div>
							<span class="nav-text">Private Course Details</span>
						</div>
					</a>
				  <?php
				  if($center->admission_permission=='Y'){
					  ?>
	                <a class="border-0 custom-menu-item" href="<?=base_url('admission_form/regular');?>">
						<div>
							<span class="nav-text">Admission Form Regular</span>
						</div>
					</a>
				<?php
				  }
				  ?>
				 <?php
				$center_id =  $this->session->center_id;	
				$center_ids_dep = array(21,22,23,24,25,26,27,28);
				  if($center->admission_permission_private=='Y' && !in_array($center_id, $center_ids_dep)){
					?>
					<a class="border-0 custom-menu-item" href="<?=base_url('admission_form/private');?>">
						<div>
							<span class="nav-text">Admission Form Private</span>
						</div>
					</a>
				<?php
				  }
				  ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('all_student');?>">
						<div>
							<span class="nav-text">Student Report</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student_list/unpaid');?>">
						<div>
							<span class="nav-text">Unpaid Student</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('Document');?>">
						<div>
							<span class="nav-text">Upload Admission Document</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('not_approve_student_list');?>">
						<div>
							<span class="nav-text">Unapproved Student List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('form_edit_request');?>">
						<div>
							<span class="nav-text">Form Edit Request</span>
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
						<!--<a class="border-0 custom-menu-item" href="<?=base_url('center/paper_missing_list');?>">
						<div>
							<span class="nav-text">Paper Missing List</span>
						</div>
					</a> -->
					<a class="border-0 custom-menu-item" href="<?=base_url('remaining_exam_answersheet');?>">
						<div>
							<span class="nav-text">Remaining Exam Answersheet</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('practical_marks_list');?>">
						<div>
							<span class="nav-text">Practical Marks Submission</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('internal_marks_list');?>">
						<div>
							<span class="nav-text">Internal Marks Submission</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('exam_form_students');?>">
						<div>
							<span class="nav-text">Exam Form Dec 2021</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student_roll_no_list');?>">
						<div>
							<span class="nav-text">Roll No List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('result');?>">
						<div>
							<span class="nav-text">Result</span>
						</div>
					</a>
				</div>
			</div> 
		</div>
	</div>
</div>
