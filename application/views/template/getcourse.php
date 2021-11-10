<!--- use get_record function for this template -->
	<select class="form-control" name="course_group_id" id="course_group_id">
		<option value=""><?=(isset($all)) ? 'All': 'Select Course';?></option>
		<?php foreach($course_group_list as $course){ ?>
			<option value="<?=$course['id']?>"><?=$course['course_name']?></option>
		<?php } ?>
	</select>