<?php if($teacher[0]->account_no==''){ ?>
  <div class="col-md-12" id="issue">
    <div class="card card-custom bgi-no-repeat gutter-b card-stretch mt-3">
      <div class="card-body">
        <form id="ajaxForm">
          <input type="hidden" name="id" value="<?=$teacher->id?>">
          <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
          <div class="form-group row m-auto">
           <div class="col-lg-6 ">
            <label>Bank Name:</label>
            <input type="text" id="bankname" name="bankname" class="form-control" placeholder="Enter Your Bank Number" value=""/>
            <span class="form-text text-danger"></span>
          </div>
        </div>
        <div class="form-group row m-auto">
          <div class="col-lg-6">
            <label>Account No.:</label>
            <input type="number" name="accountno" id="accountno" value="" class="form-control" placeholder="Enter Your Number"/>
            <span class="form-text text-danger"></span>
          </div>
        </div>
        <div class="form-group row m-auto">
          <div class="col-lg-6">
            <label>Account Holder Name:</label>
            <input type="text" id="accountholder" name="accountholder" class="form-control" placeholder="Enter Your  Name" maxlength="10" value=""/>
            <span class="form-text text-danger"></span>
          </div>
        </div>
        <div class="form-group row m-auto">
          <div class="col-lg-6">
            <label>IFSC Code:</label>
            <input type="text" name="ifsccode" id="ifsccode" class="form-control" placeholder="Enter Certification Number" value="" />
            <span class="form-text text-danger"></span>
          </div></div>
          <div class="form-group row m-auto">
            <div class="col-md-4 mt-2">
              <label>Bank Passbook or Chequebook:</label>
              <div class="custom-file">
                <input type="file" accept="image/*" class="custom-file-input" id="file" name="file">

                <label class="custom-file-label"></label>
              </div>
              <span class="form-text text-danger"></span>
            </div>
          </div>
          <div class="row">
            <button type="submit"  id="submit" class="btn btn-primary m-auto">submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php }else{  ?>
  <div class="card card-custom bgi-no-repeat gutter-b ">
   <div class="card-body">
    <div class="container-fluid profile  ">
      <!-- <h4 class="card-title">Bank Account Details</h4> -->
      <div class="row">
        <div class="col-md-6">
          <div class="row py-2">
            <label class="col-sm-4 text-heading">Bank Name</label>
            <div class="col-sm-8 text-value">
              <?php echo $teacher[0]->bank_name; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row py-2">
            <label class="col-sm-4 text-heading">Account No.</label>
            <div class="col-sm-8 text-value">
              <?php echo $teacher[0]->account_no; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row py-2">
            <label class="col-sm-4 text-heading">Account Holder Name</label>
            <div class="col-sm-8 text-value">
              <?php echo $teacher[0]->account_name; ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row py-2">
            <label class="col-sm-4 text-heading">IFSC Code</label>
            <div class="col-sm-8 text-value">
              <?php echo $teacher[0]->ifsc_code; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<script>
  $(document).ready(function(){
    $("#submit").on('click',function (e){
      e.preventDefault();
      var csrfName = $('.csrfname').attr('name');
      var csrfHash = $('.csrfname').val();
      var formimage = $('#ajaxForm');
      var frm = new FormData(formimage[0]);

      var bankname = $('#bankname');
      var accountno = $('#accountno');
      var accountholder = $('#accountholder');
      var ifsccode = $('#ifsccode');

      var file= $('#file');
      var submit = true;
      if(bankname.val()==''){
        $(bankname).next().text('Please Select Bank Name');
        submit = false;
      }else{
        $(bankname).next().text('');
      }
      if(accountno.val()==''){
        $(accountno).next().text('Please Enter Your Account NO.');
        submit = false;
      }else{
        $(accountno).next().text('');
      }
      if(accountholder.val()==''){
        $(accountholder).next().text('Please Enter Your Name');
        submit = false;
      }else{
        $(accountholder).next().text('');
      }
      if(ifsccode.val()==''){
        $(ifsccode).next().text('Please Enter Your IFSC Code');
        submit = false;
      }else{
        $(ifsccode).next().text('');
      }

      if(file.val() ==''){
        $(file).parent().next().text('Please submit Passbook Copy.');
        submit = false;
      }else{
        $(file).parent().next().text('');
      }

      if(submit==false){
        return false;
      }
      $.ajax({
        url: '<?php echo site_url('teacher/Teacher/account_transection_details_sub'); ?>',
        type: 'POST',
        dataType : 'json',
        data: frm,
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
          if(data.success){
            toastr.success(data.success);
          }else{
            toastr.error(data.error);
          }
        },
      });

    });
  });
</script>