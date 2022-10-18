<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="">
	<meta charset="utf-8" />
	<meta name="description" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
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
	<title><?= $title ?></title>
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
		<div class="">
			<div class="card card-custom gutter-b example example-compact">
				<div class="card-body p-2">