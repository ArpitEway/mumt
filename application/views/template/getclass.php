<!--- use get_record function for this template -->
	<select class="form-control" name="class_id" id="class">
		<option value=""><?=(isset($all)) ? 'All': 'Select class';?></option>
		<?php foreach($class_list as $class){ ?>
			<option value="<?=$class['id']?>"><?=$class['class_name']?></option>
		<?php } ?>
	</select>