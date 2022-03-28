<form method="POST" enctype="multipart/form-data" class="d-block ajaxForm"  action="<?php echo site_url('center/Center/activity/create'); ?>">
    <div class="form-row">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Activity Name</label>
            <input type="text" class="form-control" id="" name = "activity_name" required placeholder="Enter activity name">
        </div>

        <div class="form-group col-md-6">
            <label for="name">Discription</label>
            <input type="text" class="form-control" id="" name = "description"  placeholder="Enter description">
        </div>
		
		<div class="form-group col-md-6">
            <label for="name">Photos</label>
            <input type="file" class="form-control" id="" name="photos"  >
        </div>
		
        <div class="form-group col-md-3">
            <label for="name">Date</label>
            <input type="date" class="form-control" id="" required name = "date">
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