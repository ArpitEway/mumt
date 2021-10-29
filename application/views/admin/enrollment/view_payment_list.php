<div class="container">
	<div class="row my-5">
		<div class="form-group col-md-4 m-auto">
		<label class="form-label">Select Payment Type</label>
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <select name="payment_list" id="payment_list" class="form-control"  required >
				<option value="">Select</option>
				<option value="all">All</option>
				<option value="admission">Admission</option>
				<option value="Program Fees">Program Fees</option>
			</select>
		</div>
	</div>
	<div id="dt">
	</div>
</div>


<script>
	
	$(document).on("change","#payment_list",function(){
		
		var payment_list = $('#payment_list').val();
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = {
			payment_list : $('#payment_list').val(),
			[csrfName]:csrfHash
		};
		var url = "<?php echo site_url('admin/enrollment/get_payment_list'); ?>";
		var response = call_ajax(data,url);
		$('#dt').html(response.data);
		KTDatatablesBasicBasic.init();
	});
	
</script>