<!--- use get_record function for this template -->
	<select class="form-control" name="<?=$nameAttr; ?>" id="course">
		<option value="">Select District</option>
		<?php foreach($district_list as $district){ ?>
			<option value="<?=$district['distt_id']?>"><?=$district['name']?></option>
		<?php } ?>
	</select>