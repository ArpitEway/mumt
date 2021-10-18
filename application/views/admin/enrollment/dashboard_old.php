<div class="row mt-2">
	<div class="col-12 col-md-3 col-sm-12 menu-background p-3" >
		<ul class="nav flex-column nav-pills">
			<li class="nav-item mb-2">
				<a class="nav-link active show border" id="Student-tab" data-toggle="tab" href="#Student">
					<span class="nav-text">Student...</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
			<li class="nav-item mb-2">
				<a class="nav-link border" id="Enrollement-tab-5" data-toggle="tab" href="#Enrollement-5" aria-controls="profile">
					<span class="nav-text">Enrollement</span>
					<span class="nav-icon flot-right">
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-8 col-12 col-sm-12 menu-background p-3">
		<div class="tab-content">
			<div class="tab-pane fade active show" id="Student" role="tabpanel" aria-labelledby="Student-tab">
				<div class="row">
					<a class="border-0 custom-menu-item" href="<?=BASE_URL('admin/enrollment/user_enquiry');?>">
						<div>
							<span class="nav-text">User Enquiry</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=BASE_URL('admin/enrollment/consolidate_report');?>">
						<div>
							<span class="nav-text">Consolidate Report</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=BASE_URL('admin/enrollment/edit_non_verified_list');?>">
						<div>
							<span class="nav-text">Edit Student</span>
						</div>
					</a>
				</div>
			</div>
			<div class="tab-pane fade" id="Enrollement-5" role="tabpanel" aria-labelledby="Enrollement-tab-5">
				<div class="row">
					<a class="border-0 custom-menu-item" href="<?=BASE_URL('admin/enrollment/student_report');?>">
						<div>
							<span class="nav-text">Student Verification</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=BASE_URL('admin/enrollment/genrate_enrollment');?>">
						<div class=" mb-2">
							<span class="nav-text">Genrate Enrollement</span>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>