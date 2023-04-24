<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/support_system/create'); ?>">
    
	<div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name = "name" required placeholder="Enter name">
            
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

