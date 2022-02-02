<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<th>Form Number</th>
			<th>Session</th>
			<th>Center Code</th>
			<th>Student Name</th>	
			<th>Father Name</th>
			<th>Course Name</th>

		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($listing as $list)
		{ ?>
			<tr>
				<td><?= $i; ?></td>
				<td><?= $list->student_id; ?></td>
				<td><?= $list->session; ?></td>
				<td><?= $list->center_code; ?></td>
				<td><?= $list->name; ?></td>
				<td><?= $list->f_h_name; ?></td>
				<td><?= $list->course_name; ?></td>
			</tr>
		<?php
		$i++; } 
		?>
	</tbody>
</table>