<form action="<?=BASE_URL('admin/master/insertcenterPayment')?>" class="ajaxForm" >
	<div class="row">
		<fieldset class="form-group col-md-4">
			<label for="center_code">center Code</label>
			<select class="form-control" id="center_code" name="center_code">
				<option value="">Select center Code</option>
					<?php foreach ($center_codes as $center) {
						echo "<option>".$center['center_code']."</option>";
					} ?>
			</select>
			<small class="text-danger"></small>

		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="type">Payment Type</label>
			<select class="form-control" id="type" name="type">
				<option value="">Select Type</option>
				<option>Credit Card</option>
				<option>Debit Card</option>
				<option>Net Banking</option>
				<option>IMPS</option>
				<option>Wallets</option>
				<option>UPI</option>
				<option>NEFT/RTGS</option>
				<option>SabPaisa Payment by Link</option>
			</select>
			<small class="text-danger"></small>
		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="date">Payment Date</label>
			<input type="date" class="form-control" id="payment_date" placeholder="" name="payment_date">
			<small class="text-danger"></small>
		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="amount">Amount</label>
			<input type="number" class="form-control" id="amount" name="amount" placeholder="Amount">
			<small class="text-danger"></small>
		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="txnid">Txn Id</label>
			<input type="text" class="form-control" id="txnid" name="txnid"/>
			<small class="text-danger"></small>
		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="bank">Bank</label>
			<input type="text" class="form-control" id="bank" name="bank">
			<small class="text-danger"></small>
		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="bank">Receipt Number</label>
			<input type="text" class="form-control" id="receipt_number" name="receipt_number">
			<small class="text-danger"></small>
		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="bank">Receipt Date</label>
			<input type="date" class="form-control" id="receipt_date" name="receipt_date">
			<small class="text-danger"></small>
		</fieldset>
		<fieldset class="form-group col-md-4">
			<label for="bank">Remark</label>
			<textarea class="form-control" id="remark" name="remark"></textarea>
			<small class="text-danger"></small>
		</fieldset>
	</div>
	<button type="submit" id="submit" class="btn btn-primary">Submit</button>
</form>
<script type="text/javascript">
    $("#submit").click(function(e) {
    	e.preventDefault();
    	var center_code = $('#center_code').val();
    	var type = $('#type').val();
    	var payment_date = $('#payment_date').val();
    	var amount = $('#amount').val();
    	var txnid = $('#txnid').val();
    	var bank = $('#bank').val();
    	var submit = true;

    	if(center_code==''){
    		$('#center_code').next().text('Please Select center Code');
    		submit = false;
    	}else{
    		$('#center_code').next().text('');
    	}
    	if(type==''){
    		$('#type').next().text('Please Enter type');
    		submit = false;
    	}else{
    		$('#type').next().text('');
    	}
    	if(payment_date==''){
    		$('#payment_date').next().text('Please Select Payment Date');
    		submit = false;
    	}else{
    		$('#payment_date').next().text('');
    	}
    	if(amount==''){
    		$('#amount').next().text('Please Enter amount');
    		submit = false;
    	}else{
    		$('#amount').next().text('');
    	}
    	if(txnid==''){
    		$('#txnid').next().text('Please Enter Txnid');
    		submit = false;
    	}else{
    		$('#txnid').next().text('');
    	}
    	if(bank==''){
    		$('#bank').next().text('Please Enter Bank Name');
    		submit = false;
    	}else{
    		$('#bank').next().text('');
    	}
    	if(!submit){
    	return submit;
    	}
        var form = $('.ajaxForm');
        ajaxSubmit(e, form, drow_table);
    });
</script>