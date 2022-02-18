<table id="" class="table table-striped dt-responsive" width="100%" >
	<thead>
		<tr>
			<th>Sno.</th>
			<th>Total Exam Form</th>
			<th>Fill Exam Form</th>
			<th>Skip Exam Form</th>
			<th>Remaining Exam Form</th>
		</tr>
	</thead>
	<tbody>      
		<?php
		$i=1;
		{
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $permitted_student;?></td>
				<td><?php echo $filled_student; ?></td>
				<td><?php echo $skipped_student; ?></td>
				<td> <a target="_blank" href="<?= base_url('center_wise_remains_count');?>" ><?php echo $not_filled_student; ?></a>
				</td>
			</tr>
			<?php 
			}
		?>
	</tbody>
</table>