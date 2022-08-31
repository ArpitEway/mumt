<div class="dt-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Sno</th>
				<th>Course Name</th>
				<th>Class ID</th>
				<th>Class Name</th>
				<th>Total Exam Form</th>
				<th>Fill Exam Form</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($counts as $count){ ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $count['course_name'];?></td>
					<td><?php echo $count['old_class_id']; ?></td>
					<td><?php echo $count['class_name']; ?></td>
					<td><?php echo $count['cnt']; ?></td>
					<?php 
					$where = array('new_exam_form'  =>'Y','old_class_id'=>$count['old_class_id']);
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