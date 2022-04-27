<script type="text/javascript">
  function DeleteModal(delete_url){
    jQuery('#alert-modal').modal('show', {backdrop: 'static'});
    document.getElementById('delete_form').setAttribute('action' , delete_url);
  }
</script>
</div>
</div>
</div>
<!-- Right modal content -->
<div id="right-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-right">
    <div class="modal-content modal_height">

      <div class="modal-header border-1">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" style="overflow-x:scroll;">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
    </div>
</div>

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
            <button type="button" class="btn btn-info my-2" data-dismiss="modal"><?php echo 'cancel'; ?></button>
            <button type="submit" class="btn btn-danger my-2" onclick=""><?php echo 'continue'; ?></button>
          </form>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="offcanvas-footer text-center p-3">
        <a href="" class="text-custom ">&#169; <?=date('Y')?> MMYVV </a>
</div>
                <!--end::Purchase-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Demo Panel-->
        <!--begin::Global Config(global config for global JS scripts)-->
        <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
        <!--end::Global Config-->
       
        <script src="<?=base_url()?>assets/plugins/global/plugins.bundle.js"></script>
        <script src="<?=base_url()?>assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
        <script src="<?=base_url()?>assets/js/scripts.bundle.js"></script>
        <script src="<?=base_url()?>assets/js/pages/widgets.js"></script>
        <script src="<?=base_url()?>assets/js/pages/crud/file-upload/image-input.js"></script>
                  <script src="<?=base_url();?>assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    toastr.options = {
    "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

</script>

<script type='text/javascript'>
      Dropzone.autoDiscover = false;
      var myDropzone = new Dropzone(".dropzone", { 
        autoProcessQueue: false,
        addRemoveLinks: true,
        acceptedFiles: ".pdf,.PDF",
        maxFilesize: 10,
        timeout: 180000,
      });

      $('#uploadfiles').click(function(){       
       myDropzone.processQueue();
       myDropzone.on("complete", function(file) {
        window.location.href = BASE_URL + 'exam_paper';
      });
     });
   </script>
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

        <!--end::Page Scripts-->
    </body>
    <!--end::Body-->

  
</html>