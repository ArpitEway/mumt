<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
<thead>
		<tr>
					<th>Sno</th>
				<!-- 	<th>Center Id</th>	 -->
					<th>Center Name</th>
					<th>Center Code</th>
					<th>Students Count</th>
		
		</tr>
</thead>
<tbody>
    <?php 
			
    		$i = 1;
			
			foreach($listing as $list)
				{ ?>
		
				<tr>
				<td><?php echo $i; ?></td>
			<!-- 	<td><?php echo $list->center_id; ?></td> -->
				<td><?= $list->center_name?></td>
				<td><?php echo $list->center_code; ?></td>
				<td><a target="_blank" href="<?php echo base_url().'admin/'.$this->session->account_type.'/students_count_list/'.$list->center_id.'/'.$params.'/'.$sessionsSelect.'/'.$mode ;?>"><?php echo $list->student_count; ?></a></td>
					
                 
			</tr>
			<?php
			$i++; } 
			?>
		
</tbody>
</table>