<?php 
	$classes = $this->db->get_where('class_master', array('id' => $param1))->row();
	$where = array('class_id' => $param1,'group_pattern'=>'NEW');
	$groups = $this->Common_model->getRecordByWhere('group',$where);
	$sub_groups = $this->Common_model->getRecordByWhere('sub_group',array('id !=' => 1));
	$where['ce !='] = 'compulsory';
	$this->db->order_by('id');
	$papers = $this->Common_model->getRecordByWhere('paper_master',$where);

 ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/allot_papers/'); ?>">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="form-row">
		<div class="col-md-4">
			<label>Sub Group</label>
			<div class="form-group">
				<select class="form-control" name="group[]">
					<?php foreach ($groups as $group): ?>
						<option value="<?=$group->id ?>" ><?=$group->group_name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<label for="group_<?=$group->id ?>">Sub Group</label>
			<div class="form-group">
				<select class="form-control" name="sub_group[]">
					<?php foreach ($sub_groups as $sub_group): ?>
						<option value="<?=$sub_group->id ?>" ><?=$sub_group->sub_group_name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<label for="group_<?=$group->id ?>">Papers</label>
			<div class="form-group">
				<select class="form-control" name="papers[]">
					<?php foreach ($papers as $paper): ?>
						<option value="<?=$paper->id ?>" ><?=$paper->paper_name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class="">
			<button onclick="add_row()" type="button">Add Row</button>
		</div>
	</div>
	<div class="extra_row">
		
	</div>
	<input type="hidden" id="rowcount" value="1">
	<div class="text-center mt-3">
		<button type="submit" class="btn btn-primary">Submit</button>
	</div>
</form>

<script type="text/javascript">
	function add_row() {
		var rowCount = $("#rowcount").val();
		rowCount++;
		var row = `<div class='form-row mt-3 group_`+rowCount+`'>
		<div class='col-md-4'>
			<label>Sub Group</label>
			<div class='form-group'>
				<select class='form-control' name='group[]'>
					<?php foreach ($groups as $group): ?>
						<option value='<?=$group->id ?>' ><?=$group->group_name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class='col-md-4'>
			<label>Sub Group</label>
			<div class='form-group'>
				<select class='form-control' name='sub_group[]'>
					<?php foreach ($sub_groups as $sub_group): ?>
						<option value='<?=$sub_group->id ?>' ><?=$sub_group->sub_group_name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<div class='col-md-4'>
			<label>Papers</label>
			<div class='form-group'>
				<select class='form-control' name='papers[]'>
					<?php foreach ($papers as $paper): ?>
						<option value='<?=$paper->id ?>' ><?=$paper->paper_name; ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>
		<button onclick="add_row()" type="button">Add Row</button>
		<button class="RemoveRow" type="button" onclick='RemoveRow(`+rowCount+`)'>Remove Row</button>
	</div>`;
	
	$("#rowcount").val(rowCount);
	$('.extra_row').append(row);
}

function RemoveRow(id){
	$('.group_'+id).remove();
}

</script>