<div class="container">
<div class="row justify-content-center ">
<h6 class="font-weight-bolder">Main Exam Result Upload Status</h6>	
</div>
	<table class="table text-uppercase table table-striped dt-responsive">
		<thead>
			<tr>
                <th>Sr. No.</th>
				<th>Total</th>
				<th>Uploaded</th>
                <th>Absent</th>
				<th>Remaining</th>
				
			</tr>
		</thead>
		<tbody>
			<tr>
                <td>1</td>
				<td><?php echo  $total_paper_count ; ?></td>
				<td><?php echo $uploaded;//=$uploaded-$absent ; ?></td>
                <td><?php echo $absent ; ?></td>
				<td><?php echo $total_paper_count-$uploaded -$absent; ?></td>
				
			</tr>
		</tbody>
	</table>
	<div class="row justify-content-center pt-5">
<h6 class="font-weight-bolder">Backlog Exam Result Upload Status</h6>	
</div>
	<table class="table text-uppercase  table-striped dt-responsive">
		<thead>
			<tr>
                <th>Sr. No.</th>
				<th>Total</th>
				<th>Uploaded</th>
                <th>Absent</th>
				<th>Remaining</th>
				
			</tr>
		</thead>
		<tbody>
			<tr>
                <td>1</td>
				<td><?php echo  $total_paper_count_backlog ; ?></td>
				<td><?php echo $uploaded_backlog ; ?></td>
                <td><?php echo $absent_backlog ; ?></td>
				<td><?php  echo $total_paper_count_backlog-$uploaded_backlog -$absent_backlog; ?></td>
				
			</tr>
		</tbody>
	</table>
</div>