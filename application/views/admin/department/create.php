<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/department/create'); ?>">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="name">Department Name</label>
            <input type="text" class="form-control" id="name" name = "name" required placeholder="Enter name of department">
            <!--<small id="" class="form-text text-muted">provide department name</small>-->
        </div>
        
      
    </div>
<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Submit</button>
</div>
</form>

<script>
    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
    
    
    
</script>

