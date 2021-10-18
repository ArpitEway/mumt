<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
				
					<th>Sno.</th>
					<th>Course Name</th>	
					<th>Student Name</th>
					<th>Fees head</th>
					<th>Amount</th>
					
					
				</tr>
			</thead>
    		<tbody>
    		<?php 
			
    		$i = 1;
			
			foreach($accData as $acc){
				
			
			?>
			
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php 
				$course_name = $this->Common_model->getCourseNameByCourseId($acc["course_group_id"]);
				if(isset($course_name)){
					echo $course_name;
				}
				?></td>
				<td><?php echo $acc["student_name"]; ?></td>
				<td><?php echo $acc["fees_head"]; ?></td>
				<td><?php echo $acc["amount"]; ?></td>
				
			</tr>
			
			
			<?php
			
			$i++;
			} 
			?>
			</tbody>
</table>