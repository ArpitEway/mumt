<table id="" class="table table-striped dt-responsive" width="100%" >
	<thead>
		<tr>
			<th>Sno.</th>
			<th>Center Code</th>
			<th>Center Name</th>
				<th>City</th>
				<th>District</th>
				<th>Contact Person</th>
				<th>Mobile No</th>
			 <th>Total Exam Form</th>
			<th>Fill Exam Form</th>
			<th>Remaining Exam Form</th>
			
		</tr>
	</thead>
	<tbody>      
		<?php
		$i=1;
		  
			foreach ($centers as $center) {

			$total_count = $this->Common_model->getcountbywhere('student',array('center_id'=>$center->id,'new_exam_form'=>'D'));
			$fill_count = $this->Common_model->getcountbywhere('student',array('center_id'=>$center->id,'new_exam_form'=>'Y'));
			
			?>
			<tr>
				<td><?php echo $i++; ?></td>
                <td><?php echo $center->center_code; ?></td>
                <td><?php echo $center->center_name; ?></td>
                <td><?php echo $center->city; ?></td>
                <td><?php echo $this->Common_model->getDistrict($center->distt_id); ?></td>
                <td><?php echo $center->contactpersonname; ?></td>
                <td><?php echo $center->phoneno; ?></td>
				<td><?php echo $total_count ;?></td>
				 <td><?php echo $fill_count ;?></td>
				<td><?php echo ($total_count-$fill_count);?></td> 
	
			</tr>
			<?php 
			}
		?>
	</tbody>
</table>