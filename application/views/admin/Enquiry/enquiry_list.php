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
			foreach($inquiries as $Enquiry)
			{
				$department = $this->Common_model->getRecordByWhere("department",array("id"=>$Enquiry['department']));
				$program = $this->Common_model->getRecordByWhere("program",array("id"=>$Enquiry['program']));
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $Enquiry['name']; ?></td>
					<td><?php echo $Enquiry['email']; ?></td>
					<td><?php echo $Enquiry['mobile']; ?></td>
					<td><?php echo $Enquiry['City']; ?></td>
					<td><?php echo $department[0]->department_name; ?></td>
					<td><?php echo $program[0]->program_name; ?></td>
				</tr>
				<?php 
				$i++;
			} ?>
		</tbody>

	</table>
</div>