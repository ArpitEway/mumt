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
		<link href="<?=BASE_URL();?>assets/css/pages/login/classic/login-2.css" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="<?=BASE_URL();?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?=BASE_URL();?>assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?=BASE_URL();?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="<?=BASE_URL()?>assets/images/maskgroup/MaskGroup1.png" />
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed header-bottom-enabled subheader-enabled page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
							<!--begin::Content-->
                            <div class="order-2 order-lg-1 flex-column-auto flex-lg-row-fluid d-flex flex-column p-7" style="background-repeat: no-repeat; background-size:cover; background-image: url(<?=BASE_URL();?>assets/images/university/login.png);">
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
								<img src="<?=BASE_URL();?>assets/images/logos/logo.png" class="max-h-100px" alt="" />
							</a>
							<!--begin::Signin-->
							<div class="login-form login-signin">
								<div class="text-center mb-10 ">
									<h2 class="font-weight-bold text-warning">Sign In</h2>
									<p class="text-muted font-weight-bold">Enter your username and password</p>
								</div>
								<?php if (isset($error)) { ?>
								<span id="alert-msg" class="aler alert-error p-2 alert-msg" style="color:red;"><?= $error;?></span>
							<?php } ?>
								<!--begin::Form-->
								<form class="form" method="post" action="<?=BASE_URL('admin/teacher/loginSub')?>" >
									<div class="form-group py-3 m-0">
										<input class="form-control  placeholder-dark-75" type="test" placeholder="Phone No" name="phone" autocomplete="off" />
									</div>
									<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
									<div class="form-group py-3 border-top m-0">
										<input class="form-control  placeholder-dark-75" type="Password" placeholder="Password" name="password" />
									</div>
									<div class="form-group d-flex flex-wrap justify-content-between align-items-center mt-3">
										<div class="checkbox-inline">
											<label class="checkbox checkbox-outline m-0 text-muted">
											<input type="checkbox" name="remember" />
											<span></span>Remember me</label>
										</div>
									</div>
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
		<!--end::Main-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="<?=BASE_URL();?>assets/plugins/global/plugins.bundle.js"></script>
		<script src="<?=BASE_URL();?>assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
		<script src="<?=BASE_URL();?>assets/js/scripts.bundle.js"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Scripts(used by this page)-->
		<script src="<?=BASE_URL();?>assets/js/pages/custom/login/login-general.js"></script>
		<!--end::Page Scripts-->
	</body>
	<!--end::Body-->
</html>