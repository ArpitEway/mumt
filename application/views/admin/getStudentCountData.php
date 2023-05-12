
	<h5 class="m-3"> <?= $this->Common_model->getCourseNameByCourseId($course_group_id)?></h5>
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
</div>