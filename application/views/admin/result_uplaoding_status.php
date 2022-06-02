<div class="container">
	<table class="table text-uppercase">
		<thead>
			<tr>
                <th>Sr. No.</th>
				<th>Total</th>
				<th>Uploaded</th>
                <th>Absent</th>
				<th>Rremaining</th>
				
			</tr>
		</thead>
		<tbody>
			<tr>
                <td>1</td>
				<td><?php echo  $total_paper_count ; ?></td>
				<td><?php echo $uploaded=$uploaded-$absent ; ?></td>
                <td><?php echo $absent ; ?></td>
				<td><?php  echo $total_paper_count-$uploaded -$absent; ?></td>
				
			</tr>
		</tbody>
	</table>
</div>