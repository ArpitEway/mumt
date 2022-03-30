<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Name </th>
				<th>Email  </th>
				<th>Department </th>

			</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
        	foreach($inquiries as $inquiry)
			{
		
            ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $inquiry['name']; ?></td>
						<td><?php echo $inquiry['email']; ?></td>
						<td><?php echo $inquiry['department']; ?></td>
					</tr>
				
			
			<?php 
			$i++;
			} ?>
			</tbody>
		    
	</table>
