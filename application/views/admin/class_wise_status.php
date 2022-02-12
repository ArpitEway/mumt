<table id="" class="table table-striped dt-responsive" width="100%" >
<thead>
		<tr>
					<th>Sno</th>
				
					<th>Course Name</th>
					<th>Course Name</th>
					<th>Students Count</th>
					<th>Filled Students Counts  </th>
		
		</tr>
</thead>
<tbody>

    <?php 
			
    		$i = 1;
			
			foreach($counts as $count)
				{ ?>
		
				<tr>
				<td><?php echo $i; ?></td>
			
				<td><?php echo $count['course_name'];?></td>
				<td><?php echo $count['class_name']; ?></td>
				<td><?php echo $count['cnt']; ?></td>
				<?php 

				$where = array('new_exam_form'  =>'Y','class_id'=>$count['class_id']);
				$Permitted = $this->Common_model->getCountByWhere('student',$where);
				?>
				<td><?php echo $Permitted; ?></td>
			</tr>
			<?php
			$i++; } 
			?>
		
</tbody>
</table>