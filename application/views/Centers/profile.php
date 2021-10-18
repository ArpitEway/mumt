<div class="card card-custom card-stretch mt-5" id="profile">
	<div class="card-header border-0 bg-profile-header py-3">
		<div class="card-title d-block">
			<div class="small">center Name<br></div>
			<h3 class="card-label text-heading mt-2"><?=$center->center_name; ?></h3>
		</div>
		<div class="card-toolbar d-block">
			<div class="small text-right">center Code<br></div>
			<h3 class="card-label text-heading mt-2"><?=$center->center_code; ?></h3>
		</div>
	</div>
	<div class="container profile mt-10">
		<div class="row">
			<div class="col-md-6">
				<div class="row py-2">
					<label class="col-sm-6 text-heading">Contact person</label>
					<div class="col-sm-6 text-value">
						<?php echo $center->contact_person; ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row py-2">
					<label class="col-sm-6 text-heading">Mobile No.</label>
					<div class="col-sm-6 text-value">
						<?php echo $center->mobile_no.', '.$center->mobile_no_2; ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row py-2">
					<label class="col-sm-6 text-heading">Other Contact person</label>
					<div class="col-sm-6 text-value">
						<?php echo $center->contact_person_2; ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row py-2">
					<label class="col-sm-6 text-heading">Mobile No</label>
					<div class="col-sm-6 text-value">
						<?php echo $center->other_mobile_no.', '.$center->other_mobile_no_2; ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row py-2">
					<label class="col-sm-6 text-heading">Email</label>
					<div class="col-sm-6 text-value">
						<?php echo $center->email; ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row py-2">
					<label class="col-sm-6 text-heading">District</label>
					<div class="col-sm-6 text-value">
						<?php echo $this->Common_model->getDistrict($center->district); ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row py-2">
					<label class="col-sm-6 text-heading">City</label>
					<div class="col-sm-6 text-value">
						<?php echo $center->city; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer border-0 bg-profile-header">
		</div>
	</div>
</div>