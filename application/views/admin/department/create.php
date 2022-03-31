
<form method="POST" enctype="multipart/form-data" class="d-block ajaxForm"  action="<?php echo site_url('admin/Inquiry/department/create'); ?>">
    <div class="form-row">
      <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Department Name</label>
            <input type="text" class="form-control" id="" name = "department_name" required placeholder="Enter activity name">
        </div>

        <div class="form-group col-md-6">
            <label for="name">Image</label>
            <input type="file" class="form-control" id="" name = "photos"  placeholder="Enter description">
        </div>
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Submit</button>
	</div>
</form>

<script>
    //$(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
</script>