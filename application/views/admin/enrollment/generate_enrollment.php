<form method='POST' action="">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Action</label>
			<select class="form-control" name="action">
				<option value="view" >View</option>
				<option value="generate" >Generate</option>
			</select>
		</fieldset>
		<fieldset class="form-group text-center">
			<button type="submit" class="btn btn-primary">Submit</button>
		</fieldset>
</div>
<?php
	if($action=='view'){
?>
<div class="table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Father's / Husband's Name</th>
			<th>Course Name</th>
			<th>Temp Enrollment No</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	

		foreach($students as $student){
		$where = 'course_group_id='.$student->course_group_id;
		$enrollment_code = $this->Common_model->getSinglefield('course','enrollment_code',$where);
		$en = $enrollment_code.'-'.$en_session.'-'.str_pad($enrollment_no++,4,"0",STR_PAD_LEFT );
	?>
		<tr>
			<td><?=$i++;?></td>
			<td><?=$student->name;?>
				<input name="student_id[]" type="hidden" value="<?=$student->student_id?>">
				<input name="enrollment_no[]" type="hidden" value="<?=$en;?>">
			</td>
			<td><?=$student->f_h_name;?></td>
			<td><?=$student->course_name;?></td>
			<td><?=$en;?></td>
		</tr>
		<?php 
			}
		?>
	</tbody>
</table>
</div>
<?php
		}else if($action=='generate'){
?>
<div class="table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Father's / Husband's Name</th>
			<th>Course Name</th>
			<th>Temp Enrollment No</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
		foreach($students as $student){
	?>
		<tr>
			<td><?=$i++;?></td>
			<td><?=$student->name;?></td>
			<td><?=$student->f_h_name;?></td>
			<td><?=$student->course_name;?></td>
			<td><?=$student->enrollment_no;?></td>
		</tr>
		<?php 
			}
		?>
	</tbody>
</table>
</div>
<?php
		}else{
	}
?>
</form>