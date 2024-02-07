<table id="kt_datatable" class="table table-striped dt-responsive" width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<th>Center Code</th>
			<th>Center Name</th>
          
            <th>Address</th>
            <th>City</th>
            <th>Contact Personname</th>
            <th>Phone Number</th>
            <th>Mobile no.1</th>
            <th>Mobile no.2</th>
		    <th> Total counts</th>
            <th> Regular counts</th>
            <th> Private counts</th>

		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($listing as $list)
		{ 
             $regsql="SELECT COUNT(*) as reg FROM student as s where s.enrolled='Y' and course_complete='N' and new_admission_permission='N' AND university_mode='REG' and center_id='".$list['id']."' group by center_id ";
		    $regrs = $this->db->query($regsql)->result_array();
            $pvt=0;
            $pvt=$list['total']-$regrs[0]['reg'];
            
            ?>
			<tr>
				<td><?= $i; ?></td>
				
				<td><?= $list['center_code']; ?></td>
				<td><?= $list['center_name']; ?></td>
                <td><?= $list['address']; ?></td>
                <td><?= $list['city']; ?></td>
                <td><?= $list['contactpersonname']; ?></td>
                <td><?= $list['phoneno']; ?></td>
                <td><?= $list['mobile_no_1']; ?></td>
                <td><?= $list['mobile_no_2']; ?></td>
				<td><?= $list['total']; ?></td>
                <td><?= $regrs[0]['reg']; ?></td>
                <td><?=  $pvt; ?></td>
			</tr>
		<?php 
		$i++; } 
		?>
	</tbody>
</table>