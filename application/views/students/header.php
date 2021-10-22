<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="">
	<meta charset="utf-8" />
	<title>MMYVV || Student</title>
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
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" />
	
	<link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	
	<link href="https:////cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	
	<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
	<script type="text/javascript">
    var BASE_URL = "<?php echo base_url();?>";
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
				<div id="kt_header" class="header flex-column header-fixed">
					<!--begin::Top-->
					<div class="header-top">
						<!--begin::Container-->
						<div class="container">
							<!--begin::Left-->
							<div class="d-none d-lg-flex align-items-center mr-3">
								<div class="float-right">
									<span class="text-custom">
										<i class="fas fa-map-marker-alt"></i>Address
									</span>
									<span class="text-custom ml-3">
										<i class="fa fa-envelope" aria-hidden="true"></i>  email
									</span>
								</div>
							</div>
							<!--end::Left-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Top-->
				</div>
				<div class="container-fluid mt-10 w-90">
					<div class="mb-10">
						<img src="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" alt="">
						<img src="<?=base_url()?>assets/images/maskgroup/Group1.png" alt="" class="img2">
					</div>
    <div class="card card-custom gutter-b example example-compact">
        <div class="card-body " style="min-height: 500px;">
<div class="content-head row   justify-content-between mb-3">
<h3 class="text-primary"><?php echo (isset($title)) ? $title : ''; ?></h3>
<div class="float-right">
<?php 
	if($this->session->has_userdata('studentdata')){ 
?>
	<a href="<?=base_url('student/dashboard')?>" class="btn btn-outline-primary mr-3">Dashboard</a>
<a href="<?=base_url('student/logout')?>" class="btn btn-outline-primary mr-3">Log Out</a>

<?php }else{ ?>
	<a href="<?=base_url('student/login')?>" class="btn btn-outline-primary mr-3">Log IN</a>
<?php } ?>
</div>
</div>