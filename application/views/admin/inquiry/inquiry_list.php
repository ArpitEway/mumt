<div class="dt-responsive">
	<table id="kt_datatable" class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Name </th>
				<th>Email  </th>
				<th>Contact Info</th>
				<th>City</th>
				<th>Department Name</th>
				<th>Program Name </th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($inquiries as $inquiry)
			{
				$department = $this->Common_model->getRecordByWhere("department",array("id"=>$inquiry['department']));
				$program = $this->Common_model->getRecordByWhere("program",array("id"=>$inquiry['program']));
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $inquiry['name']; ?></td>
					<td><?php echo $inquiry['email']; ?></td>
					<td><?php echo $inquiry['mobile']; ?></td>
					<td><?php echo $inquiry['City']; ?></td>
					<td><?php echo $department[0]->department_name; ?></td>
					<td><?php echo $program[0]->program_name; ?></td>
				</tr>
				<?php 
				$i++;
			} ?>
		</tbody>

	</table>
</div>