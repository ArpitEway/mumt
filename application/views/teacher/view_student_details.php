<table id="" class="table " width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<th>Roll No</th>
			<th>Enrollment No</th>
		    <th> Course</th>
 <th>Class</th>
  <th>paper</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($student as $paper_code)
		{ ?>
			<tr>
				<td><?= $i; ?></td>
				
				<td><?= $paper_code->roll_no; ?></td>
				<td><?= $paper_code->enrollment_no; ?></td>
				<td><?= $paper_code->course_name; ?></td>
				<td><?= $paper_code->class_name; ?></td>


<td><a class="btn btn-sm btn-primary" href="<?=BASE_URL('Teacher/student_details_for_question/'.$paper_code->student_id);?>" target="_blank">View Paper</a></td>	

			
			</tr>
		<?php
		$i++; } 
		?>
	</tbody>
</table>