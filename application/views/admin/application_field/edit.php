<?php

// $accounts = $this->db->get_where('application_field', array('id' => $param1))->result_array();
$field = $this->Common_model->getRecordById('application_field','id',$param1);

// foreach($accounts as $account): 
?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/application_field/update/'.$param1); ?>">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="field">Field Name</label>
            <input type="text" class="form-control" value="<?php echo $field->field; ?>" id="field" name = "field" required placeholder="Enter field">
            
        </div>
		<div class="form-group col-md-6">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" value="<?php echo $field->amount; ?>" id="amount" name = "amount" required placeholder="Enter amount">
        </div>
		
		
		
    </div>
	<div class="form-group text-center p-3">
	<button class="btn btn-md btn-primary" type="submit">Update Field</button>
	</div>
</form>


<style>
.plus_btn{
    color: #FFFFFF;
    background-color: #052C68;
    border-color: #052C68;
}
.minus_btn{
    color: #FFFFFF;
    background-color: #052C68;
    border-color: #052C68;
}
</style>
<script>
    //$(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
</script>
