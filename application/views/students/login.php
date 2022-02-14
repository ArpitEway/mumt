<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="../../../../">
		<meta charset="utf-8" />
		<title>Login MMYVV</title>
		<meta name="description" content="Login page example" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Custom Styles(used by this page)-->
		<link href="<?=base_url();?>assets/css/pages/login/classic/login-2.css" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="<?=base_url();?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?=base_url();?>assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?=base_url();?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" />
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
				<!--begin::Content-->
				<div class="order-2 order-lg-1 flex-column-auto flex-lg-row-fluid d-flex flex-column p-7" style="background-repeat: no-repeat; background-size:cover; background-image: url(<?=base_url();?>assets/images/university/login.png);">
					<!--begin::Content body-->
					<div class="d-flex flex-column-fluid flex-lg-center">
						<div class="d-flex flex-column justify-content-center">
							
						</div>
					</div>
					<!--end::Content body-->
				</div>
				<!--end::Content-->	
				<!--begin::Aside-->
				<div class="login-aside order-1 order-lg-2 d-flex flex-column-fluid flex-lg-row-auto bgi-size-cover bgi-no-repeat p-7 p-lg-10">
					<!--begin: Aside Container-->
					<div class="d-flex flex-row-fluid flex-column justify-content-between">
						<!--begin::Aside body-->
						<div class="d-flex flex-column-fluid flex-column flex-center mt-5 mt-lg-0">
							<a href="#" class="mb-5 text-center">
								<img src="<?=base_url();?>assets/images/logos/logo.png" class="max-h-100px" alt="" />
							</a>
							<!--begin::Signin-->
							<div class="login-form login-signin">
								<div class="text-center mb-10 ">
									<h2 class="font-weight-bold text-warning">Sign In</h2>
									<p class="text-muted font-weight-bold">Enter your Enrollment no and DOB</p>
								</div>
								<?php if ($this->session->flashdata('success')) { ?>
									<span id="alert-msg" class="aler alert-success p-2 alert-msg"><?php echo $this->session->flashdata('success') ?></span>
								<?php } ?>
								
								<?php if ($this->session->flashdata('error')) { ?>
									<span id="alert-msg" class="aler alert-error p-2 alert-msg" style="color:red;"><?php echo $this->session->flashdata('error') ?></span>
								<?php } ?>
								<!--begin::Form-->
								<form class="form" method="post" action="<?=base_url('student/loginSub')?>" >
									<!-- <div class="form-group py-3 m-0">
										<div class="radio-inline">
											<label class="radio">
												<input type="radio" value="3" checked="checked" name="radio">
											<span></span>All Course</label>
											<label class="radio">
												<input type="radio" value="4" name="radio">
											<span></span>शास्त्री + शिक्षाशास्त्री (BA + BEd)</label>
										</div>
									</div> -->

									<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
									<div class="form-group py-3 m-0">
										<input class="form-control  placeholder-dark-75" type="text" placeholder="Enrollment no" name="enrollment_no" id="enrollment_no" autocomplete="off" required />
									</div>
									
									<div class="form-group py-3 border-top m-0">
										<input data-inputmask="'alias': 'dd-mm-yyyy'" class="form-control  placeholder-dark-75" type="text" placeholder="dd-mm-yyyy" name="dob" id="dob" />
									</div>
									
									
									<div class="form-group d-flex flex-wrap justify-content-between align-items-center mt-3">
										<div class="checkbox-inline">
										</div>
										<div class="my-3 mr-2">
											
										</div></div>
										<div class="form-group d-flex flex-wrap justify-content-center align-items-center mt-2">
											<button class="btn btn-secondary font-weight-bold px-9 py-4 my-3">Sign In</button>
										</div>
								</form>
								<!--end::Form-->
							</div>
							<!--end::Signin-->
							
						</div>
						<!--end::Aside body-->
						<!--begin: Aside footer for desktop-->
						<div class="d-flex flex-column-auto justify-content-between mt-15">
							<div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">© <?=date('Y');?> MMYVV</div>
						</div>
						<!--end: Aside footer for desktop-->
					</div>
					<!--end: Aside Container-->
				</div>
				<!--begin::Aside-->
			</div>
			<!--end::Login-->
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
		<script type="text/javascript">
			$("#dob").inputmask();


			$("#enrollment_no").inputmask({"mask": "AG/-9999-9999"});
			
			// $('input[type=radio][name=radio]').change(function() {
			// 	if (this.value == '3') {
			// 		$("#enrollment_no").inputmask({"mask": "AAA-99-9999"});
			// 	}
			// 	else if (this.value == '4') {
			// 		$("#enrollment_no").inputmask({"mask": "AAAA-99-9999"});
			// 	}
			// });
		</script>
	</body>
	<!--end::Body-->
</html>