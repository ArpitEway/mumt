<?php

$accounts = $this->db->get_where('admin_master', array('id' => $param1))->result_array();

foreach($accounts as $account): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/account_register/update/'.$param1); ?>">

    <div class="form-row">
	
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" value="<?php echo $account['name']; ?>" id="name" name = "name" required placeholder="Enter name">
            
        </div>
		<div class="form-group col-md-6">
            <label for="name">Account type</label>
            <input type="text" class="form-control" value="<?php echo $account['account_type']; ?>" id="account_type" name = "account_type" required placeholder="Enter account type">
        </div>
		
		<div class="form-group col-md-6">
            <label for="name">Designation</label>
            <input type="text" class="form-control" value="<?php echo $account['designation']; ?>" id="designation" name = "designation" required placeholder="Enter designation">
        </div>
		<div class="form-group col-md-6">
            <label for="name">Email</label>
            <input type="text" class="form-control" value="<?php echo $account['email']; ?>" id="email" name = "email" required placeholder="Enter email">
            
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
