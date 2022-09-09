<div class="container">
    <form  method="POST" class="p-4 ajaxForm col-md-9 m-auto">
        <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
       
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-alert">New Password</label>
            <div class="col-lg-9 col-xl-6">
                <input type="password"  name="new_password" id="new_password" class="form-control form-control-lg form-control-solid"  placeholder="New password">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label text-alert">Verify Password</label>
            <div class="col-lg-9 col-xl-6">
                <input type="password" name="passconf" id="passconf" class="form-control form-control-lg form-control-solid" value="" placeholder="Verify password">
            </div>
        </div>
    <div class="text-center">
        <button type="button" id="submit" class="btn btn-success mr-2">Submit</button>
    </div>
</form>
</div>
<script>
	$("#submit").on('click',function (e){
	account_type = '<?=$this->session->account_type; ?>';
	admin_id =  '<?= $admin_data->id; ?>';

		var frm = $('.ajaxForm').serialize()
		$.ajax({
			url: BASE_URL+ 'change_password_sub/'+admin_id,
			type: 'POST',
			dataType : 'json',
			data: frm,
			success: function (data) {
				$('#new_password').val("");
				$('#passconf').val("");
				if(data.error){
				toastr.error(data.error); 
				}else{
				toastr.success(data.success);
				window.location = BASE_URL;
				}
			}
		});
	});
</script>