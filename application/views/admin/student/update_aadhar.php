<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-6 text-center">
        <?php
        $docs = $this->Common_model->getAllRow("admission_document",'document_image',array("student_id" => $student_id,"document_name" => 'Aadhaar card'),'');
        $ext = explode(".",$docs[0]["document_image"]);
        $pathURL="";
        if($student_detail[0]["enrolled"]=='Y'){
          $pathURL= BASE_URL('assets/enrolled_documents/'.$student_detail[0]['session'].'/'.$docs[0]["document_image"]);
        }
        else
         $pathURL=BASE_URL('assets/documents/'.$docs[0]["document_image"]);
        if($ext[1] == "pdf"){
          ?>
          <a target="_blank" href="<?php echo $pathURL; ?>">
           <iframe src="<?php echo $pathURL ?>" width="600" height="400"></iframe>
         </a>

       <?php }else{ ?>
        <a data-magnify="gallery" data-src="" data-caption="<?php echo $docs[0]["document_name"] ?>" data-group="a" href="<?php echo $pathURL; ?>">
          <img src="<?php echo $pathURL; ?>" class="img img-fluid w-75"> 
        </a>
      <?php } ?>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center"><b>Student Aadhar Number Update</b></div>
        <div class="card-body">
          <form method="POST" class="d-block ajaxForm" >
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="class">Student Name:</label>
              </div>
              <div class="form-group col-md-8 ">
                <?=$student_detail[0]['name'];?>
              </div>
              <div class="form-group col-md-4">
                <label for="class">Aadhar Number:</label>
              </div>
              <div class="form-group col-md-8 aadhar_number_old">
                <b><?=wordwrap($student_detail[0]['adhar_no'], 4, ' ',true) ?></b>
              </div>
              <div class="form-group col-md-4">
                <label for="class">Edit Aadhar Number:</label>
              </div>
              <div class="form-group col-md-8">
                <input type="number" name="aadhar_number" maximum="12" minimum="12" id="aadhar_number" value="<?=$student_detail[0]['adhar_no']?>">
                <div class="fv-plugins-message-container"></div>
              </div>
              <div class="form-group text-center">
                <input type="button" class="btn btn-lg btn-primary" id="submit" value ="Submit">
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<script>
  $("#submit").on('click',function (e){
   var csrfName = $('.csrfname').attr('name');
   var csrfHash = $('.csrfname').val();
   var frm = $('.ajaxForm').serialize();

    $.ajax({
     url: '<?php echo site_url('admin/enrollment/aadhar_update/'.$student_id); ?>',
     type: 'POST',
     dataType : 'json',
     data: frm,
     success: function (data) {
       if(data){
         console.log(data.res);
         $('.aadhar_number_old').html(data.res);
         toastr.success("Aadhar Number updated");
       }else{
         toastr.error("Something wrong");
       }
     },
   });	

  });	


  $( document ).ready(function() {
    $('input[type="button"]').attr('disabled','disabled');
  });

  $('input[name="aadhar_number"]').on('keyup', function(){
   
   var csrfName = $('.csrfname').attr('name');
   var csrfHash = $('.csrfname').val(); 
   var self = $(this);
   var adhar_no = $(this).val();
   if($.isNumeric(adhar_no) == false){
    $(self).next().text("Please Enter Correct Adhar No");
    $('input[type="button"]').attr('disabled','disabled');
    return false;
  }
  if(adhar_no.length!=12){
    $(this).next().text("Please Enter Your 12 Digit Adhar Card Numbers");
    $('input[type="button"]').attr('disabled','disabled');
  }else{
   $.ajax({
    method: "POST",
    url: BASE_URL+"admin/enrollment/checkDuplicateAdharNo",
    data: {adhar_no : adhar_no,
            [csrfName] : csrfHash,
            },
  })
   .done(function( msg ) {
    if(msg!=''){
      $(self).next().text("Duplicate Adhar Card Number");
    }else{
      $(self).next().text("");
      $('input[type="button"]').removeAttr('disabled');
    }
  });
 }
});
</script>
<script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="<?=BASE_URL()?>assets/light_box/js/jquery.magnify.js"></script>
<script type="text/javascript">
  window.prettyPrint && prettyPrint();

  var defaultOpts = {
    draggable: true,
    resizable: true,
    movable: true,
    keyboard: true,
    title: true,
    modalWidth: 320,
    modalHeight: 320,
    fixedContent: true,
    fixedModalSize: false,
    initMaximized: false,
    gapThreshold: 0.02,
    ratioThreshold: 0.1,
    minRatio: 0.05,
    maxRatio: 16,
    headToolbar: ['maximize', 'close'],
    footToolbar: ['zoomIn', 'zoomOut', 'prev', 'fullscreen', 'next', 'actualSize', 'rotateRight'],
    multiInstances: true,
    initEvent: 'click',
    initAnimation: true,
    fixedModalPos: false,
    zIndex: 1090,
    dragHandle: '.magnify-modal',
    progressiveLoading: true
  };

  var vm = new Vue({
    el: '#playground',
    data: {
      options: defaultOpts
    },
    methods: {
      changeTheme: function (e) {
        if (e.target.value === '0') {
          $('.magnify-theme').remove();
        } else if (e.target.value === '1') {
          $('.magnify-theme').remove();
          $('head').append('<link class="magnify-theme" href="css/magnify-bezelless-theme.css" rel="stylesheet">');
        } else if (e.target.value === '2') {
          $('.magnify-theme').remove();
          $('head').append('<link class="magnify-theme" href="css/magnify-white-theme.css" rel="stylesheet">');
        }
      }
    },
    updated: function () {
      $('[data-magnify]').magnify(this.options);
    }
  });
</script>
