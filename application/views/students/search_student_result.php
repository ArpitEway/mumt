<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head><base href="">
	<meta charset="utf-8" />
	<?php  //$this->uri->segment(1); ?>
	<title id="headerTitle" >MMYVV || Student  <?php // if($this->uri->segment(1)=="ExamController") echo $title; ?> </title>
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
		<div class="container-fluid mt-5" >
			<div class="card card-custom gutter-b example example-compact" style="min-height: 63vh;">
				<div class="card-body ">
					<div class="content-head row   justify-content-between mb-3">
						<h3 class="text-primary"><?php echo (isset($title)) ? $title : ''; ?></h3>
					</div>
                <form id="radio_btn_select">
                    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
                    <div class="row">
                        <div class="col-lg-6 m-auto">
                            <div class="form-group row">
                                <label class=" col-form-label col-2" >Roll No: </label> 
                            <input class="form-control  placeholder-dark-75 col-9" type="text" placeholder="Roll no" name="roll_no" id="roll_no" autocomplete="off" required />	
                            </div>
                            <div class="form-group row">
                                <label class="col-2 col-form-label" >DOB : </label>
                                <input data-inputmask="'alias': 'dd-mm-yyyy'" class="form-control  placeholder-dark-75 col-9" type="text" placeholder="dd-mm-yyyy" name="dob" id="dob" />
                                </div>
                            </div> <!-- from-group row -->
                             
                        </div>
                        <div class="row"><div class="col-lg-6 m-auto">
                            <div class="form-group row">
                                <label class="col-2 col-form-label" >Search By : </label>
                                <div class="col-9 col-form-label">
                                    <div class="radio-inline">
                                    
                                        <label class="radio radio-success">
                                            <input type = "radio"
                                            name = "radio_stduent_search"
                                            id = "radio_main"
                                            value = "main" 
                                            checked
                                            />
                                            <span></span>
                                            Main Exam 
                                        </label>
                                        <label class="radio radio-success">
                                            <input type = "radio"
                                            name = "radio_stduent_search"
                                            id = "radio_backlog"
                                            value = "backlog" />
                                            <span></span>
                                            Backlog Exam
                                        </label>

                                    
                                    </div>
                                </div>
                            </div>
                        </div> </div>
                        
                         <div class="row d-flex justify-content-center">
                        <div class="form-group row">
                                <button type="button" class="btn btn-primary btn-sm m-auto" onclick="search_student_data()">Submit</button>
                            </div>
                    </div>
                    </div>
                   
                </form>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div class="row" >
    <div class="col-md-12 col-lg-12" id="student_data_tbl">
<!-- table by ajax append here -->
    </div>
</div>
<script type="text/javascript">
    var site_url = "<?php echo base_url(); ?>"

    function search_student_data()
    {    
       
        $('#student_data_tbl').hide();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        var roll_no = $('#roll_no').val();
        var dob = $('#dob').val();
        var radio_val = $('input[name="radio_stduent_search"]:checked').val();
      
      
        if(roll_no =='')
        { 
            alert('Roll Number is required !');
        }
       
        else if(dob=='')
        {
            alert('DOB is required !');
        }
       else
        {
          
            let data = {
                    'roll_no':roll_no.trim(),
                    'dob':dob,
                    'radio_val':radio_val,
                    [csrfName]:csrfHash
                }
            $.ajax({
                url:site_url+'student/Student/getStudentMarksheetData',
                type:'post',
                dataType : 'JSON',
                data: data,
                beforeSend: function()
              {
                $("#myLoader").show();
               },
                success:function(resp)
                {
                    if( $("#myLoader").show()){
						$('#student_data_tbl').hide();
						// $table = $('#dt').html(status.data);

					}if( $('#myLoader').hide()){
                        $('#student_data_tbl').html(resp.data);
						$('#student_data_tbl').show();
						
					}
                   
                    KTDatatablesBasicBasic.init();            
                }//success
                
            })//ajax
        }
    }
    	$("#dob").inputmask();
			
</script>

                    <!-- footer -->
                     <script type="text/javascript">

var callBackFunction;
var callBackFunctionForGenericConfirmationModal;


function rightModal(url, header)//header means heading of page/modal popup
{
  // LOADING THE AJAX MODAL
  var csrfName = $('.csrfname').attr('name');
  var csrfHash = $('.csrfname').val();

  jQuery('#right-modal').modal('show', {backdrop: 'true'});
  // SHOW AJAX RESPONSE ON REQUEST SUCCESS
  $.ajax({
    url: url,
    data: {[csrfName]:csrfHash},
    success: function(response)
    {
      //console.log(response);
      jQuery('#right-modal .modal-body').html(response);
      jQuery('#right-modal .modal-title').html(header);
	    KTAutosize.init();
    }
  });
}
function confirmModal(delete_url, param)
{
  jQuery('#alert-modal').modal('show', {backdrop: 'static'});
  callBackFunction = param;
  document.getElementById('delete_form').setAttribute('action' , delete_url);
}

function genericConfirmModal(callBackFunction)
{
  jQuery('#genric-confirmation-modal').modal('show', {backdrop: 'static'});
  callBackFunctionForGenericConfirmationModal = callBackFunction;
}

function callTheCallBackFunction() {
  $('#genric-confirmation-modal').modal('hide');
  callBackFunctionForGenericConfirmationModal();
}

</script>
<!-- Toastr and alert notifications for PHP scripts -->
<!-- SHOW TOASTR NOTIFICATION FOR AJAX-->
<?php if ($this->session->flashdata('ajax_flash_message') != ""):?>

<script type="text/javascript">
	toastr.success('<?php echo $this->session->flashdata("ajax_flash_message");?>');
</script>

<?php endif;?>

<?php if ($this->session->flashdata('ajax_error_message') != ""):?>

<script type="text/javascript">
	toastr.error('<?php echo $this->session->flashdata("ajax_error_message");?>');
</script>
<?php endif;?>
<!-- Right modal content -->
<div id="right-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-right">
    <div class="modal-content modal_height">
      <div class="modal-header border-1">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body" style="overflow-x:scroll;">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="text-center">
          <i class="dripicons-information h1 text-info"></i>
          <h4 class="mt-2"><?php echo 'Heads Up' ?>!</h4>
          <p class="mt-3"><?php echo 'Are You Sure'; ?>?</p>
          <form method="POST" class="ajaxDeleteForm" action="" id = "delete_form">
              <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <button type="button" class="btn btn-info my-2" data-dismiss="modal"><?php echo 'Cancel'; ?></button>
            <button type="submit" class="btn btn-danger my-2" onclick=""><?php echo 'Continue'; ?></button>
          </form>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>
    </div>
</div>

<div class="offcanvas-footer text-center p-3">
    <a href="" class="text-custom ">&#169; <?=date('Y')?> MMYVV </a>
</div>
                <!--end::Purchase-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Demo Panel-->
        <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
        <!--begin::Global Config(global config for global JS scripts)-->
        <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
        <!--end::Global Config-->
        <!--begin::Global Theme Bundle(used by all pages)-->
        <script src="<?=base_url()?>assets/plugins/global/plugins.bundle.js"></script>
        <script src="<?=base_url()?>assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
        <script src="<?=base_url()?>assets/js/scripts.bundle.js"></script>
		<script src="<?=base_url()?>assets/js/function.js"></script>
		<script src="<?=base_url()?>assets/js/jquery.fancybox.js"></script>
        <!--end::Global Theme Bundle--> 
        <!--begin::Page Vendors(used by this page)-->
        <script src="<?=base_url()?>assets/plugins/custom/datatables/datatables.bundle.js"></script>
        
        <script src="<?=base_url()?>assets/js/pages/crud/datatables/basic/basic.js?token=<?=date('dmyhis')?>"></script>
          

<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="<?=base_url()?>assets/js/pages/widgets.js"></script>
<script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/autosize.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="<?=base_url()?>assets/js/pages/widgets.js"></script>
<script src="<?=base_url()?>assets/js/pages/crud/file-upload/image-input.js"></script>
<script src="<?=base_url()?>assets/theme/admin.js?token=<?=date('dmyhis')?>"></script>
<script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js"></script>



<!--end::Page Scripts-->
</body>
    <!--end::Body-->
</html>
<?php if ($this->session->flashdata('success') != ""):?>

<script type="text/javascript">
  toastr.success('<?php echo $this->session->flashdata("success");?>');
</script>

<?php endif;?>

<?php if ($this->session->flashdata('error') != ""):?>

<script type="text/javascript">
  toastr.error('<?php echo $this->session->flashdata("error");?>');
</script>
<?php endif;?>
<?php if ($this->session->flashdata('warning') != ""):?>

<script type="text/javascript">
  toastr.warning('<?php echo $this->session->flashdata("warning");?>');
</script>
<?php endif;?>