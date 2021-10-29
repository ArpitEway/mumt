<script type="text/javascript">

var callBackFunction;
var callBackFunctionForGenericConfirmationModal;


function rightModal(url, header)
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
          <h4 class="mt-2"><?php echo 'heads_up' ?>!</h4>
          <p class="mt-3"><?php echo 'are_you_sure'; ?>?</p>
          <form method="POST" class="ajaxDeleteForm" action="" id = "delete_form">
              <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <button type="button" class="btn btn-info my-2" data-dismiss="modal"><?php echo 'cancel'; ?></button>
            <button type="submit" class="btn btn-danger my-2" onclick=""><?php echo 'continue'; ?></button>
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
        
        <script src="<?=base_url()?>assets/js/pages/crud/datatables/basic/basic.js"></script>
          
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="<?=base_url()?>assets/js/pages/widgets.js"></script>
<script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/autosize.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script src="<?=base_url()?>assets/js/pages/widgets.js"></script>
<script src="<?=base_url()?>assets/js/pages/crud/file-upload/image-input.js"></script>
<script src="<?=base_url()?>assets/theme/admin.js?token=<?=date('dmyhis')?>"></script>
<script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js"></script>

<!--end::Page Scripts-->
</body>
    <!--end::Body-->
</html>