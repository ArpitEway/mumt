<?php

$program = $this->db->get_where('program', array('id' => $param1))->result_array();

?>

<form method="POST" enctype="multipart/form-data" class="d-block ajaxForm"  action="<?php echo site_url('admin/Enquiry/program/update/'.$param1); ?>">
    <div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
			<label for="name">Department Name</label>
			<select name="department_id" id="department_id" class="form-control" >
          
				<option>All</option>
				<?php 
                	$departments= $this->Common_model->get_record("department","*");
				foreach($departments as $department)
				{
					?>
					<option value="<?php echo $department['id']; ?>" <?php echo ($department['id'] == $program[0]['department_id']) ? 'selected' : '' ?>><?php echo $department['department_name']; ?></option>
					<?php
				} 
				?>		
			</select>
		</div>

		<div class="form-group col-md-6">
      	<label for="course_type">Course Type</label>
			<select name="course_type" id="course_type" class="form-control">

				<option value="">Select</option>
				<option value="Phd" <?php echo ($program[0]['course_type'] == "Phd") ? 'selected' : '' ?>>Phd</option>
				<option value="PG"  <?php echo ($program[0]['course_type'] == "PG") ? 'selected' : '' ?>>PG</option>
				<option value="UG"  <?php echo ($program[0]['course_type'] == "UG") ? 'selected' : '' ?>>UG</option>
				<option value="PGDiploma" <?php echo ($program[0]['course_type'] == "PGDiploma") ? 'selected' : '' ?>>PG Diploma</option>
				<option value="Diploma" <?php echo ($program[0]['course_type'] == "Diploma") ? 'selected' : '' ?>>Diploma</option>
				<option value="Certificate" <?php echo ($program[0]['course_type'] == "Certificate") ? 'selected' : '' ?>>Certificate</option>
			</select>
		</div>

        <div class="form-group col-md-6">
            <label for="name">Program Name</label>
            <input type="text" class="form-control" id="" value="<?= $program[0]['program_name']; ?>" name = "program_name" required placeholder="Enter program name">
        </div>
	

    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
	</div>
</form>



