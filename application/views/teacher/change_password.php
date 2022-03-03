<div class="container">

    <form  method="POST" class="p-4 ajaxForm col-md-9 m-auto">
        <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-alert">Current Password</label>
            <div class="col-lg-9 col-xl-6">
                <input type="password" name="password" id="password" class="form-control form-control-lg form-control-solid mb-2"  placeholder="Current Password">                                  
            </div>
        </div>
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-alert">New Password</label>
            <div class="col-lg-9 col-xl-6">
                <input type="password"  name="new_password1" id="new_password1" class="form-control form-control-lg form-control-solid"  placeholder="New password">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-alert">Verify Password</label>
            <div class="col-lg-9 col-xl-6">
                <input type="password" name="new_password" id="new_password" class="form-control form-control-lg form-control-solid" value="" placeholder="Verify password">
            </div>
        </div>
    <div class="text-center">
        <button type="button" id="submit" class="btn btn-success mr-2">Submit</button>
    </div>
</form>
</div>

   <!-- loader -->
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>

<script>
    <?php
    $this->session->set_flashdata("success","");
    $this->session->set_flashdata("error","");
    ?>
    $("#submit").on('click',function (e){


        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();

        var frm = $('.ajaxForm').serialize();

        $.ajax({
            url: '<?php echo site_url('teacher/Teacher/password_change/'.$teacher->id); ?>',
            type: 'POST',
            dataType : 'json',
            data: {frm ,[csrfName]:csrfHash},
          
                 beforeSend: function()
              {
                console.log('loading...');
                $("#myLoader").show();
               },
            success: function (data) {

                $('#password').val("");
                $('#new_password1').val("");
                $('#new_password').val("");

                if(data.error){
                    toastr.error(data.error);
                }else{
                    toastr.success(data.success);
                }
            },
            complete: function()
            {
                console.log('loading...over');

              $('#myLoader').hide();
            },
        });
    });
</script>