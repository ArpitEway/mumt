
<form method="POST" enctype="multipart/form-data" class="d-block ajaxForm"  action="<?php echo site_url('admin/Enquiry/program/create'); ?>">
    <div class="form-row">
      <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
      <div class="form-group col-md-6">
      <label for="name">Department Name</label>
			<select name="department_id" id="department_id" required class="form-control" >
				<option value="">Select Department</option>
				<?php 
                	$departments= $this->Common_model->get_record("department","*");
				foreach($departments as $department)
				{
					?>
					<option value="<?php echo $department['id']; ?>" ><?php echo $department['department_name']; ?></option>
					<?php
				} 
				?>		
			</select>
		</div>

		<div class="form-group col-md-6">
      	<label for="course_type">Course Type</label>
			<select name="course_type" id="course_type" required class="form-control" >
				<option value="">Select</option>
				<option value="Phd">Phd</option>
				<option value="PG">PG</option>
				<option value="UG">UG</option>
				<option value="PGDiploma">PG Diploma</option>
				<option value="Diploma">Diploma</option>
				<option value="Certificate">Certificate</option>		
			</select>
		</div>
		
        <div class="form-group col-md-6">
            <label for="name">Program Name</label>
            <input type="text" class="form-control" id="" name = "program_name" required placeholder="Enter program name">
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