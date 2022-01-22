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
		</ul>
	</div>
	<div class="col-md-8 col-12 col-sm-12 menu-background p-3">
		<div class="tab-content">
			<div class="tab-pane fade active show" id="Enrollment" role="tabpanel" aria-labelledby="Enrollment-tab">
				<div class="row">

					<a class="border-0 custom-menu-item" href="<?=base_url('center/instruction');?>">
						<div>
							<span class="nav-text">Course Details</span>
						</div>
					</a>

					<a class="border-0 custom-menu-item" href="<?=base_url('center/admission_instruction');?>">
						<div>
							<span class="nav-text">Admission Form</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('center/all_student');?>">
						<div>
							<span class="nav-text">Student Report</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('center/student_list/unpaid');?>">
						<div>
							<span class="nav-text">Unpaid Student</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('center/Document');?>">
						<div>
							<span class="nav-text">Upload Admission Document</span>
						</div>
					</a>

				</div>
			</div>
			<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
				<div class="row">
										<a class="border-0 custom-menu-item" href="<?=base_url('center/student_list/paid');?>">
						<div>
							<span class="nav-text">Paid Student</span>
						</div>
					</a>
				</div>
			</div> 
		</div>
	</div>
</div>