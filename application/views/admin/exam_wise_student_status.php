<div class="row justify-content-center">
<h6 class="font-weight-bolder">Main Exam Form Status</h6>	
</div>
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
<div class="row justify-content-center pt-5">
<h6 class="font-weight-bolder">Backlog Exam Form Status</h6>	
</div>
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
				<td><?php echo $permitted_backlog_student;?></td>
				<td><?php echo $filled_backlog_student; ?></td>
				<td><?php echo $skipped_backlog_student; ?></td>
				<td> <a target="_blank" href="<?= base_url('center_wise_remains_backlog_count');?>" ><?php echo $not_filled_backlog_student; ?></a>
				</td>
			</tr>
			
			<?php 
			}
		?>
	</tbody>
</table>

