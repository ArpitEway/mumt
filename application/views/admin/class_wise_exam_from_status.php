<div class="dt-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Sno</th>
				<th>Course Name</th>
				<th>Course Name</th>
				<th>Student Count</th>
				<th>Fill Exam Form Student Count</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($counts as $count){ ?>
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
				$i++; 
			}
			?>
		</tbody>
	</table>
</div>