<!--- use get_record function for this template -->
<select class="form-control" name="student_id" id="student_id">
		<option value=""><?=(isset($all)) ? 'All': 'Select Student';?></option>
		<?php foreach($student_list as $student){ ?>
			<option value="<?=$student['id']?>"><?=$student['name'] ?></option>
		<?php } ?>
</select>