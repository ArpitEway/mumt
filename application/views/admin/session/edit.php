<?php

$departments = $this->db->get_where('department', array('id' => $param1))->result_array();

foreach($departments as $department): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/department/update/'.$param1); ?>">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="name">Department Name</label>
            <input type="text" class="form-control" id="name" name = "name" value="<?php echo $department['name']; ?>" required placeholder="Enter name of department">
            <!--<small id="" class="form-text text-muted">provide department name</small>-->
        </div>
		
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
</div>
</form>

<?php endforeach; ?>

<script>
    $(".ajaxForm").validate({}); 
    $(".ajaxForm").submit(function(e) {
      e.preventDefault();
      var form = $(this);
      ajaxSubmit(e, form, showAlldepartment);
    });
</script>
