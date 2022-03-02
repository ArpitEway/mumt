<table id="" class="table table-striped dt-responsive" width="100%" >
	<thead>
		<tr>
			<th>Sno.</th>
			<th>center_code</th>
		</tr>
	</thead>
	<tbody>      
		<?php
		$i=1;
        foreach($center_codes as $centers)
		{  
 
			?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $centers->center_code;?></td>
			
			</tr>
			<?php 
			}
		?>
	</tbody>
</table>