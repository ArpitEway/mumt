<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/account_register/create'); ?>">
    
	<div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name = "name" required placeholder="Enter name">
            
        </div>
		<div class="form-group col-md-6">
            <label for="name">Account type</label>
            <input type="text" class="form-control" id="account_type" name = "account_type" required placeholder="Enter account type">
        </div>
		
		<div class="form-group col-md-4">
            <label for="name">Designation</label>
            <input type="text" class="form-control" id="designation" name = "designation" required placeholder="Enter designation">
        </div>
		<div class="form-group col-md-4">
            <label for="name">Username</label>
            <input type="text" class="form-control" id="username" name = "user_name" required placeholder="Enter Username">
            
        </div>
		<div class="form-group col-md-4">
            <label for="name">Password</label>
            <input type="password" class="form-control" id="password" name ="password" required placeholder="Enter password">            
        </div>
		
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Create Account</button>
	</div>
</form>

<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllaccount);
    });    
</script>

