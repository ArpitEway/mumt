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
	.bg-custom-2.bg-hover-state-info: hover{
		-webkit-transition: all 0.15s ease;
		transition: all 0.15s ease;
		background-color: #eba617 !important;
	}
</style>
<?php 
$userType = $this->session->userdata['account_type'];

?>
<div class="mt-3">
<div   class="row text-center p-3">	
	<div class="form-group col-md-3 mx-auto mb-10">
	</div>
	<div class="form-group col-md-3 mx-auto mb-10">
	<label for="class">Session</label>
	<select name="session" id="session" class="form-control" onchange="getSessionRecord()" >
		<!-- <option <?php //if($sessionsSelect=="All") echo "selected"; ?>>All</option> -->
		<?php 
		foreach($sessions as $session)
		{
		?>
		<option value="<?php echo $session['id']; ?>" <?php if($sessionsSelect==$session['id']) echo "selected"; ?>><?php echo $session['session']; ?></option>
		<?php
	} 
	?>		
	</select>
	</div>
	<div class="form-group col-md-3 mx-auto mb-10">
	<label for="class">University Mode</label>
	<select name="mode" id="mode" class="form-control" onchange="getSessionRecord()" >
		<option value="" <?php if($mode=="") echo "selected"; ?>>All</option>
		<option value="REG" <?php if($mode=="REG") echo "selected"; ?>>Regular</option>
		<option value="PVT" <?php if($mode=="PVT") echo "selected"; ?>>Private</option>
	</select>
	</div>
	<div class="form-group col-md-3 mx-auto mb-10">
	</div>
</div>
<div class="row mt-5">
	<div class="col-xl-4">
		<!--begin::Stats Widget 13-->
		<div  class="card card-custom bg-danger bg-hover-state-danger card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-graduation-cap icon-3x"></span><br><br>
				<div class="text-inverse-danger font-weight-bolder font-size-h5 mb-2 mt-5">Students</div>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/all/'.$sessionsSelect.'/'.$mode.'/');?>" class="font-weight-bold text-inverse-danger font-size-sm">Total Student : <?=$total_student;?></a>
				
			</div>
			<!--end::Body-->
		</div>
		<!--end::Stats Widget 13-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 14-->
		<div  class="card card-custom bg-primary bg-hover-state-primary card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-rupee-sign icon-3x"></span><br><br>
				<div class="text-inverse-primary font-weight-bolder font-size-h5 mb-2 mt-5">Admission Fee Payments</div>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/paid/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-primary font-size-sm">Paid Student: <?=$tot_paid;?></a><br>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/not_paid/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-primary font-size-sm">Unpaid Student: <?=$tot_unpaid;?></a>
			</div>
			<!--end::Body-->
		</div>
		<!--end::Stats Widget 14-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 15-->
		<div  class="card card-custom bg-success bg-hover-state-success card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-file-upload icon-3x"></span><br><br>
				<div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">Document</div>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/uploaded/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-success font-size-sm">Student's Document Uploaded: <?=$uploaded;?></a><br>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/not_uploaded/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-success font-size-sm">Student's Document Not Uploaded: <?=$not_uploaded;?></a>
			</div>
			<!--end::Body-->
		</div>
		<!--end::Stats Widget 15-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 15-->
		<div  class="card card-custom bg-info bg-hover-state-info card-stretch card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body">
				<span class="text-white fas fa-check-double icon-3x"></span><br><br>
				<div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">Verification</div>
				<a target="_blank"href="<?= base_url('admin/'.$userType.'/center_wise_list/approved/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-danger font-size-sm">Approved Students : <?=$approved;?></a><br>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/not_verified/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-success font-size-sm">Not Verified Student : <?=$not_verified;?></a><br>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/non_approved/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-danger font-size-sm">Non-Approved Students : <?=$non_approved;?></a>
			</div>


			<!--end::Body-->
		</div>
		<!--end::Stats Widget 15-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 16-->
		<div  class="card card-custom bg-custom card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body" >
				<span class="text-white fas fa-money-check-alt icon-3x"></span><br><br>
				<div style="color:white!important;" class="text-custom font-weight-bolder font-size-h5 mb-2 mt-5">Generating Enrollment</div>
				<a target="_blank"href="<?= base_url('admin/'.$userType.'/center_wise_list/generated/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-danger font-size-sm">Total Generated : <?=$en_generated?></a><br>
				<a target="_blank"href="<?= base_url('admin/'.$userType.'/center_wise_list/not_generated/'.$sessionsSelect.'/'.$mode.'/')?>"style="color:white!important;" class="font-weight-bold text-custom font-size-sm">Remain to generate: <?=$not_en_generated;?></a>
		
		
			</div>
			<!--end::Body-->
		</div>
		<!--end::Stats Widget 16-->
	</div>
	<div class="col-xl-4">
		<!--begin::Stats Widget 15-->
		<div  class="card card-custom bg-custom-2 bg-hover-state-info card-stretch card-stretch gutter-b">
			<!--begin::Body-->
			<div class="card-body" >
				<span class="text-white fas fa-user-plus icon-3x"></span><br><br>
				<div class="text-inverse-success font-weight-bolder font-size-h5 mb-2 mt-5">Enrolled Status</div>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/enrolled/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-white font-size-sm">Total Enrolled: <?=$tot_enrolled;?></a><br>
				<a target="_blank" href="<?= base_url('admin/'.$userType.'/center_wise_list/not_enrolled/'.$sessionsSelect.'/'.$mode.'/')?>" class="font-weight-bold text-inverse-success font-size-sm">Remain to Enrolled : <?=$tot_not_enrolled;?></a>
			</div>
			<!--end::Body-->
		</div>
		<!--end::Stats Widget 15-->
	</div>
</div>
<script>
  function getSessionRecord(){

    //var csrfName = $('.csrfname').attr('name');
    //var csrfHash = $('.csrfname').val(); 
    var sess=$("#session").val();
	var mode=$("#mode").val();
    //location.href =BASE_URL+"admin/Enrollment/enrollment_status/"+sess;
    console.log(window.location.href );
	var currentURL= window.location.href;
	currentURL = currentURL.split('enrollment_status')[0];
	console.log(currentURL );
	location.href=currentURL+"enrollment_status/"+sess+"/"+mode;
    }

</script>
