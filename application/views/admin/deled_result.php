
<div class="container-fluid mt-3" >

	
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Roll No.</th>
				<th>Enrollment No.</th>
				<th>Student Name</th>
				<th>Paper Code</th>
				<th>Paper Name</th>
				<th>Type</th>
				<th>Theory / Practical Marks</th>
				<th>Internal Marks</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $student->roll_no; ?></td>
                    <td><?php echo $student->enrollment_no; ?></td>
                    <td><?php echo $student->name; ?></td>
                    <td><?php echo $student->paper_code; ?></td>
                    <td><?php echo $student->paper_name; ?></td>
                    <td><?php echo $student->paper_type; ?></td>
                    <td><?php echo ($student->paper_type == 'theory')?$student->theory_marks:$student->p_marks; ?></td>
                    <td><?php echo $student->int_marks; ?></td>
				
					
				</tr>
				<?php 
				$i++;
			} 
			?>
		</tbody>
	</table>
</div>
