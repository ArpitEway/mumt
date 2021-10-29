<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/session/create'); ?>">
    <div class="form-row">

        <div class="form-group col-md-12">
            <label for="name">Session Name</label>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <input type="text" class="form-control" id="session" name = "session" required placeholder="Enter name of session">
            <!--<small id="" class="form-text text-muted">provide department name</small>-->
        </div>
        
    </div>
<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Submit</button>
</div>
</form>

<script>
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
</script>

