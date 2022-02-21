<div class="container-fluid mt-5 table-responsive" >
	<table class="table table-striped" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Roll No.</th>
				<th>Enrollment No</th>
                 <th>Date of Birth</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course Name</th>
				<th>Class Name</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i ; ?></td>

					<td><?php echo $student->roll_no; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $this->Common_model->viewdate($student->dob); ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->f_h_name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>			
					
						

				</tr>
				<?php 
				$i++;
			}
			?>

		</tbody>
	</table>
</div>