<table id="" class="table table-striped dt-responsive" width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<th>Center Code</th>
			<th>Center Name</th>
		    <th> Student counts</th>

		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($listing as $list)
		{ ?>
			<tr>
				<td><?= $i; ?></td>
				
				<td><?= $list->center_code; ?></td>
				<td><?= $list->center_name; ?></td>
				<td><?= $list->student_count; ?></td>
			</tr>
		<?php
		$i++; } 
		?>
	</tbody>
</table>