<table class="table table-hover">
	<thead>
		<tr>
			<th>Form No</th>
			<th>Student Name</th>
			<th>Course</th>
			<th>Class</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$count = count($other_student);
		if($count==0){
			?>
			<tr>
				<th colspan="3" class="text-center"><?='Other Course Not Found' ?></th>
			</tr>
			<?php
		}else{
		foreach ($other_student as $student) { ?>
		<tr>
			<td><?=$student->student_id?></td>
			<td><?=$student->name?></td>
			<td><?=$student->course_name?></td>
			<td><?=$student->class_name?></td>
		</tr>

	<?php }
		 } ?>
	</tbody>
</table>