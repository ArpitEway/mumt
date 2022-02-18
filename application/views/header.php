<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="">
	<meta charset="utf-8" />
	<title>MMYVV || Admin</title>
	<meta name="description" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Vendors Styles(used by this page)-->
	<link href="<?=base_url()?>assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Page Vendors Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="<?=base_url()?>assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url()?>assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url()?>assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="<?=base_url()?>assets/css/theme.css" rel="stylesheet" type="text/css" />
			<link href="<?=base_url()?>assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<!--end::Layout Themes-->
	<link href="https:////cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link href="<?=base_url()?>assets/light_box/css/jquery.magnify.css" rel="stylesheet">
	<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
	<script type="text/javascript">
		var BASE_URL = "<?php echo base_url();?>";
		var account_type = "<?php echo  ($this->session->has_userdata('account_type')) ? $this->session->account_type : '' ;?>";
	</script>

</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-mobile-fixed header-bottom-enabled page-loading">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="">
			<!--begin::Wrapper-->
			<div  id="kt_wrapper">
				<!--begin::Header-->
				<div id="center" class="header flex-column header-fixed">
					<!--begin::Top-->
					<div class="header-top" style="
					">
					<!--begin::Container-->
					<div class="container-fluid">
						<!--begin::Left-->
						<div class="d-none d-lg-flex align-items-center mr-3">
							<div class="float-right">
								<span class="text-custom font-weight-bolder">
									Jai Guru Dev
								</span>
							</div>
						</div>
						<!--end::Left-->
						<div class="float-right mt-1" style="
						z-index: 9999999;
						display: flex;
						align-items: center;
						">
						<?php if($this->session->has_userdata('adminData')){ ?>
							<a href="<?=base_url($this->session->account_type)?>" class="btn btn-custom-white mr-3">Dashboard</a>
						<div class="dropdown">
							<a class="btn btn-custom-white dropdown-toggle mr-3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Action
							</a>

							<div class="dropdown-menu l-50" aria-labelledby="dropdownMenuLink">
								<!-- <a class="dropdown-item" href="<?=base_url('admin/change_password')?>">Change Password</a> -->
								<a href="<?=base_url('logout')?>" class="dropdown-item">Log Out</a>
							</div>
						</div>
						
					<?php }else{ ?>
						<a href="<?=base_url('admin/login')?>" class="btn btn-custom-white mr-3">Sign In</a>
					<?php } ?>
					</div>
				</div>
				<!--end::Container-->
			</div>
			<!--end::Top-->
			<div style="background: #fff; box-shadow: 1px 4px 15px -14px black; height: 100px; z-index: 999;">
				<div class="container-fluid">
					<img src="<?=base_url('assets/images/center/logo.png')?>" alt="" style="
					padding: 15px 0px;
					">
					<img src="<?=base_url('assets/images/center/name.png')?>" alt="" class="img2">
				</div>
			</div>
		</div>
		<div class="container-fluid mt-5">
			<div class="card card-custom gutter-b example example-compact">
				<div class="card-body " style="min-height: 70vh;">
					<div class="content-head row   justify-content-between mb-3">
						<h3 class="text-primary"><?php echo (isset($title)) ? $title : ''; ?></h3>
					</div>