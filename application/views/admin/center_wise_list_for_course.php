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
		    <th> Total Admission counts</th>
            <th> Exam Form Fill counts</th>
            
		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($listing as $list)
		{ 
             $regsql="SELECT COUNT(*) as reg FROM student as s where s.enrolled='Y' and course_complete='N' and new_admission_permission='N' AND university_mode='REG' and center_id='".$list['id']."' and class_id in (193,195,197,199,201,203,205,207,209,211,213,221,223,225,227,275,279,302) and new_exam_form='Y' group by center_id ";
		    $regrs = $this->db->query($regsql)->result_array();
            $fill=0;
             $fill= $regrs[0]['reg'];
            
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
                <td><?=  $fill; ?></td>
              
			</tr>
		<?php 
		$i++; } 
		?>
	</tbody>
</table>