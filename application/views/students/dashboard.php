


<div class="row mt-2">
	<div class="col-12 col-md-3 col-sm-12 menu-background p-3" >
		<ul class="nav flex-column nav-pills">
			<li class="nav-item mb-2">
				<a class="nav-link active show border" id="Student-tab" data-toggle="tab" href="#Student">
					<span class="nav-text">Student</span>
					<span class="nav-icon flot-right" >
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
					<a class="border-0 custom-menu-item" href="<?=base_url('student/form');?>">
						<div>
							<span class="nav-text">Admission Form</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student/document/list');?>">
						<div>
							<span class="nav-text">Document</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student/payment/all_payment');?>">
						<div>
							<span class="nav-text">Payment Details</span>
						</div>
					</a>
					 <!--  <?php if($this->User_model->hasNewAdmissionAccess()){ ?>
                    <a class="border-0 custom-menu-item" href="<?=base_url('student/new_admission');?>">
                        <div>
                            <span class="nav-text">Admission In Additional Program</span>
                        </div>
                    </a>
                <?php } ?> -->
                
				</div>
			</div>
		</div>
	</div>
</div> 