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

<div class="table-responsive">
<table class="table table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Father's / Husband's Name</th>
			<th>Course Name</th>
			<th>session</th>
			<th>Enrollment No</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$where = array(
		'enrollment_no' => '-',
	);
	$students = $this->Common_model->getRecordByWhere('student',$where);
	foreach ($students as $student) {
		// if($student->session=='July 2021'){
		// 	$enrollment_no = ;
		// }else{
		// 	$enrollment_no =  ;
		// }
	 //  enrollment_code ag21 if jan = 1 july 2     0001
		
		?>
		<tr>
			<td><?=$i++;?></td>
			<td><?=$student->name;?></td>
			<td><?=$student->f_h_name;?></td>
			<td><?=$student->course_name;?></td>
			<td><?=$student->session;?></td>
			<td><?=$student->enrollment_no;?></td>

			

		</tr>
		<?php
		}
	
		?>
</form>