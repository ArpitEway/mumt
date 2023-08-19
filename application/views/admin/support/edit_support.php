<?php
$accounts = $this->db->get_where('support_system', array('id' => $param1))->result_array();
foreach($accounts as $account): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo base_url('support_system/update/'.$param1); ?>">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" value="<?php echo $account['name']; ?>" id="name" name = "name" required placeholder="Enter name">    
        </div>	
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update Account</button>
	</div>
</form>

<?php endforeach; ?>
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
