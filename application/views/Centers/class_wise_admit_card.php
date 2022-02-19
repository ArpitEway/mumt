<div class=" mt-5" >
	<table id="" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Course Name</th>
				<th>Class Name</th>
				<th>Show</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($students as $student){

				

				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $student->course_name; ?> </td>
					<td><?php echo $student->class_name; ?> </td>
					
						<td><a target="_blank" href="<?=base_url('center/center/admit_card_student_list/'.$student->class_id);?>">Show</a></td>
					
				</tr>
				<?php
				$i++;
			}
			?>
		</tbody>
	</table>
</div>