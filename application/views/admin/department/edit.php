<?php

$departments = $this->db->get_where('department', array('id' => $param1))->result_array();
 ?>

<form method="POST" enctype="multipart/form-data" class="d-block ajaxForm"  action="<?php echo site_url('admin/Enquiry/department/update/'.$param1); ?>">
    <div class="form-row">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Department Name</label>
            <input type="text" class="form-control" id="" value="<?= $departments[0]['department_name']; ?>" name = "department_name" required placeholder="Enter department name">
        </div>
		
		<div class="form-group col-md-6">
            <label for="name">Image</label>
            <input type="file" class="form-control" id=""  value="<?= $departments[0]['image']; ?>" name="photos" >
            <input type="hidden" class="form-control" id=""  value="<?= $departments[0]['image']; ?>" name="old_image"  >
        </div>

    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
	</div>
</form>



