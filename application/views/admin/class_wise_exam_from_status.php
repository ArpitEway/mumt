<div class="dt-responsive">
	<table class="table table-striped" id="kt_datatable">
		<thead>
			<tr>
				<th>Sno</th>
				<th>Course Name</th>
				<th>Class ID</th>
				<th>Class Name</th>
				<th>Total Exam Form</th>
				<th>Fill Exam Form</th>
				<th>Regular Fill Form</th>
				<th>Private Fill Form</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($counts as $count){ ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $count['course_name'];?></td>
					<td><?php echo $count['class_id']; ?></td>
					<td><?php echo $count['class_name']; ?></td>
					<td><?php echo $count['cnt']; ?></td>
					<?php 
					$where = array('new_exam_form'  =>'Y','class_id'=>$count['class_id']);
					$Permitted = $this->Common_model->getCountByWhere('student',$where);
					$where_reg = array('new_exam_form'  =>'Y','university_mode'  =>'REG','class_id'=>$count['class_id']);
					$Permitted_reg = $this->Common_model->getCountByWhere('student',$where_reg);
					$where_pvt = array('new_exam_form'  =>'Y','university_mode'  =>'PVT','class_id'=>$count['class_id']);
					$Permitted_pvt = $this->Common_model->getCountByWhere('student',$where_pvt);
					
					?> 
					<td><?php echo $Permitted; ?></td>
					<td><?php echo $Permitted_reg; ?></td>
					<td><?php echo $Permitted_pvt; ?></td>
				</tr>
			<?php
				$i++; 
			}
			?>
		</tbody>
	</table>
</div>
