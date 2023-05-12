<div>
<h5 class="my-10 text-center"> <?= $this->Common_model->getCourseNameByCourseId($course_group_id)?>  July 2022</h5>
</div>
<table id="" class="w-100 table table-bordered">
	<thead>
		<tr>
			<th>#</th>
			<th>IC Code</th>
			<th>Count</th>
			<th>MDE</th>
			
			
		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($students as $student){

			?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $student->center_code; ?></td>
				<td><?php echo $student->num; ?></td>
				<td><?php echo $student->examcentercode; ?></td>
				
				
			</tr>
		<?php  }	?>
	</tbody>
</table>