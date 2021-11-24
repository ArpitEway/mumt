<style>
	a.bg-custom:hover, a.bg-custom:focus, button.bg-custom:hover, button.bg-custom:focus{
		background-color: #0c4682 !important;
	}
	.bg-custom {
		background-color: #3f55ad !important;
	}
	a.bg-custom-2:hover, a.bg-custom-2:focus, button.bg-custom-2:hover, button.bg-custom-2:focus{
		background-color: #f59e00 !important;
	}
	.bg-custom-2{
		background-color: #ffad05 !important;
	}
</style>

<div class="row mt-5">
	<div class="col-xl-4">
		<!--begin::Stats Widget 13-->
		<a href="#" class="card card-custom bg-danger bg-hover-state-danger card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-graduation-cap icon-3x"></span>
				<div class="text-inverse-danger font-weight-bolder font-size-h5 mb-2 mt-5">Students</div>
				<div class="font-weight-bold text-inverse-danger font-size-sm">Total Student : <?=$total_student;?></div>
				
			</div>
			<!--end::Body-->
		</a>
		<!--end::Stats Widget 13-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 14-->
		<a href="#" class="card card-custom bg-primary bg-hover-state-primary card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-rupee-sign icon-3x"></span>
				<div class="text-inverse-primary font-weight-bolder font-size-h5 mb-2 mt-5">Admission Fee Payments</div>
				<div class="font-weight-bold text-inverse-primary font-size-sm">Paid Student: <?=$tot_paid;?></div>
				<div class="font-weight-bold text-inverse-primary font-size-sm">Unpaid Student: <?=$tot_unpaid;?></div>
				
			</div>
			<!--end::Body-->
		</a>
		<!--end::Stats Widget 14-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 15-->
		<a href="#" class="card card-custom bg-success bg-hover-state-success card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-file-upload icon-3x"></span>
				<div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">Document</div>
				<div class="font-weight-bold text-inverse-success font-size-sm">Student's Document Uploaded: <?=$uploaded;?></div>
				<div class="font-weight-bold text-inverse-success font-size-sm">Student's Document Not Uploaded: <?=$not_uploaded;?></div>
			</div>
			<!--end::Body-->
		</a>
		<!--end::Stats Widget 15-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 15-->
		<a href="#" class="card card-custom bg-info bg-hover-state-info card-stretch card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-check-double icon-3x"></span>
				<div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">Approved</div>
  
				
				<div class="font-weight-bold text-inverse-danger font-size-sm">Approved Students : <?=$approved;?></div>
				<!-- <div class="font-weight-bold text-inverse-success font-size-sm">Verified Student : <?=$verified;?></div> -->
				<div class="font-weight-bold text-inverse-success font-size-sm">Not Verified Student : <?=$not_verified;?></div>
				<div class="font-weight-bold text-inverse-danger font-size-sm">Non-Approved Students : <?=$non_approved;?></div>
			</div>
			<!--end::Body-->
		</a>
		<!--end::Stats Widget 15-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 16-->
		<a href="#" class="card card-custom bg-custom card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body" >
				<span class="text-white fas fa-money-check-alt icon-3x"></span>
				<div style="color:white!important;" class="text-custom font-weight-bolder font-size-h5 mb-2 mt-5">Generating Enrollment</div>
				<div  class="font-weight-bold text-inverse-danger font-size-sm">Total Generated : <?=$en_generated?></div>
				<div style="color:white!important;" class="font-weight-bold text-custom font-size-sm">Remain to generate: <?=$not_en_generated;?></div>
				
			</div>
			<!--end::Body-->
		</a>
		<!--end::Stats Widget 16-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 15-->
		<a href="#" class="card card-custom bg-custom-2 bg-hover-state-info card-stretch card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body" >
				<span class="text-white fas fa-user-plus icon-3x"></span>
				<div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">Enrolled Status</div>
				<div class="font-weight-bold text-white font-size-sm">Total Enrolled: <?=$tot_enrolled;?></div>
				<div class="font-weight-bold text-inverse-success font-size-sm">Remain to Enrolled : <?=$tot_not_enrolled;?></div>
			</div>
			<!--end::Body-->
		</a>
		<!--end::Stats Widget 15-->
	</div>
</div>