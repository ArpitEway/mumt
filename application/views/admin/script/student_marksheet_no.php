
<div class=" mt-5" >
    
	<table id="" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
                <!--  Form , Enrollment , Roll , Name , Course , class , centercode , marksheet no -->
				<th>#</th>
				<th>Form No.</th>
				<th>Enrollment No.</th>
				<th>Roll No.</th>
				<th>Student Name </th>
				<th>Course</th>
				<th>Class</th>
				<th>Marksheet No.</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $student->student_id; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->roll_no; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php echo $student->marksheet_no; ?></td>
				</tr>
				<?php $i++; } ?>
			</tbody>
		</table>
	</div>