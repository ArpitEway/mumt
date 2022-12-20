<style type="text/css">
	.fit{
	border: none;
	background-color: transparent;
	outline: none;
	padding: 0;
	width:300px;
	}
</style>
<div class="border border-radius p-3 mb-10">
<div class="row   mb-4">
    <div class="col-md-3">
		<strong for="">Roll Number :</strong>
			<?=$student->roll_no ?>
	</div>
    <div class="col-md-3">
		<strong for="">Enrollment Number :</strong>
			<?=$student->enrollment_no ?>
	</div>
	<div class="col-md-3">
		<strong for="">Student Name :</strong>
			<?=$student->name ?>
	</div>
	
	<div class="col-md-3">
		<strong for="">Course Name :</strong>
			<?=$student->course_name ?>
	</div>
</div> 
<div class="row mb-4">   
	<div class="col-md-3">
		<strong for="">Class Name :</strong>
			<?= $this->Common_model->getClassNameByClassId($student->old_class_id) ?>
	</div>
    <div class="col-md-3">
		<strong for="">IC Code :</strong>
			<?= $student->center_code ?>
	</div>
    <div class="col-md-3">
		<strong for="">Session :</strong>
			<?= $student->session; ?>
	</div>
    <div class="col-md-3">
		<strong for="">Result :</strong>
			<?= $student->result_show ?>
	</div>
</div>
<div class="row  mb-4">
    <div class="col-md-3">
		<strong for="">Marksheet :</strong>
			<?= $student->result_show ?>
	</div>
	
</div>
</div>

<div>
	<form class="mt-4 shadow">
		<input type="hidden" name="student_id" value="<?=$student->student_id?>">
		<input type="hidden" name="class_id" value="<?=$student->old_class_id?>">
		<div class="row p-5">
			<div class="col-md-4">
				<label>Name Hindi</label>
				<div class="form-group">
					<input type="text" class="form-control" name="name_hindi" value="<?=$student->name_hindi?>">
				</div>
			</div>
			<div class="col-md-4">
				<label>Father Name Hindi</label>
				<div class="form-group">
					<input type="text" class="form-control" name="f_h_name_hindi" value="<?=$student->f_h_name_hindi; ?>">
				</div>
			</div>
			<div class="col-md-4">
				<label>Mother Name Hindi</label>
				<div class="form-group">
					<input type="text" class="form-control" name="mother_name_hindi" value="<?=$student->mother_name_hindi; ?>">
				</div>
			</div>
		</div>
	<table class="table border">
		<thead>
			<tr>
				<th>#</th>
				<th>Paper Name</th>
				<th>Paper Type</th>
				<?php if ($student->mode=='regular'): ?>
					<th>Theory Marks</th>
					<th>Internal Marks</th>
				<?php else: ?>
					<th>Theory Marks</th>
				<?php endif ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($studentPaper as $key => $papers): ?>
				<tr>
					<td><?=$key+1; ?></td>
					<td><?=$papers['paper_name'] ?></td>
					<td><?=ucwords($papers['type']) ?></td>
					<?php if ($papers['type']=='theory') { ?>
					<?php if ($student->mode=='regular'): ?>
					<td class="fit">
						<input type="hidden" name="paper_id[]" value="<?= $papers['paper_id'];?>">
						<select name="theory_marks[]" class="form-control select2">
							<option value="">Select Marks</option>
							<?php
							for ($i=0; $i<=$papers['reg_max_marks']; $i++){
								$selected =  ($papers['theory_marks']==$i) ? 'selected="selected"' : '';
								$selected = ($papers['theory_marks']==='') ? '' : $selected;
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
							<option <?=($papers['theory_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
						</select>
					</td>
					<td class="fit">
						<select name="int_marks[]" class="form-control select2">
							<option value="">Select Marks</option>
							<?php
							for ($i=0; $i<=$papers['reg_max_int_marks']; $i++){
								$selected =  ($papers['int_marks']==$i) ? 'selected="selected"' : '';
								$selected = ($papers['int_marks']=='' || $papers['int_marks']=='N') ? '' : $selected;
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
								<option <?=($papers['int_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
						</select>
					</td>
					<?php else: ?>
						<td class="fit">
						<input type="hidden" name="paper_id[]" value="<?= $papers['paper_id'];?>">
						<select name="theory_marks[]" class="form-control select2">
							<option value="">Select Marks</option>
							<?php
							for ($i=0; $i<=$papers['pvt_max_marks']; $i++){
								$selected =  ($papers['theory_marks']==$i) ? 'selected' : '';
								$selected = ($papers['theory_marks']=='') ? '' : $selected;
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
							<option <?=($papers['theory_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
						</select>
					</td>
					<?php endif ?>
				<?php }elseif($papers['type']=='project'){ ?>
					<td>
					<input type="hidden" name="pro_marks_paper_id[]" value="<?= $papers['paper_id'];?>">
					<select name="pro_marks[]" class="form-control select2">
						<option value="">Select Marks</option>
							<?php
							for ($i=0; $i<=$papers['reg_max_marks']; $i++){
								$selected =  ($papers['p_marks']==$i) ? 'selected' : '';
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
							<option <?=($papers['p_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
						</select>
						</td>
						<td>
					<select name="proj_int_marks[]" class="form-control select2">
						<option value="">Select Marks</option>
							<?php
							for ($i=0; $i<=$papers['reg_max_int_marks']; $i++){
								$selected =  ($papers['int_marks']==$i) ? 'selected' : '';
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
						<option <?=($papers['p_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
						</select>
						</td>
				<?php }elseif($papers['type']=='practical'){
					?>
					<td colspan="2">
					<input type="hidden" name="p_marks_paper_id[]" value="<?= $papers['paper_id'];?>">
					<select name="p_marks[]" class="form-control select2">
						<option value="">Select Marks</option>
							<?php
							for ($i=0; $i<=$papers['reg_max_marks']; $i++){
								$selected =  ($papers['p_marks']==$i) ? 'selected' : '';
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
							<option <?=($papers['p_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
						</select>
						</td>
				<?php } ?>
			</tr>
			<?php endforeach ?>
			<tr>
				<td colspan="5" class="text-center"><button type="button" name="submit" id="submit" class="btn-primary btn">Update</button></td>
			</tr>
		</tbody>
	</table>
</form>
</div>
<script type="text/javascript">
	$('.select2').select2();
	$(document).on('click','#submit',function(event){
		    event.stopImmediatePropagation();
		var  serialized = $('form').serialize();
		$.ajax({
			url: BASE_URL+"admin/Dataentry/edit_student_marks_sub",
			type: 'POST',
			dataType : 'json',
			data: serialized ,
			success: function (data) {
				if(data.success){
					toastr.success(data.success);
					setTimeout(function(){location.href=BASE_URL+'admin/Dataentry/search_student'} , 2000);
				}else{
					toastr.error(data.error);
				}
			},
		});
	});
</script>