<div class="container">
	<table class="table text-uppercase">
		<thead>
			<tr>
				<th>Total</th>
				<th>Uploaded</th>
				<th>Rremaining</th>
				<th>Checked</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo  $total_paper_count ; ?></td>
				<td><?php echo $uploaded ; ?></td>
				<td><?php  echo $total_paper_count-$uploaded ; ?></td>
				<td><?php echo $checked ; ?></td>
			</tr>
		</tbody>
	</table>
</div>