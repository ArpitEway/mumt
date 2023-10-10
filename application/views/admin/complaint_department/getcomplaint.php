<select class="form-control" name="<?=$nameAttr; ?>" id="course">
		<option value="">Select Type</option>
		<?php foreach($complaints as $complaint){ ?>
			<option value ="<?= $complaint->name?>"<?php echo set_select($nameAttr, $complaint->id, ( !empty($_POST[$nameAttr]) && $_POST[$nameAttr] == $complaint->id ? TRUE : FALSE )); ?>><?=$complaint->name;?></option>
		<?php } ?>
	</select>