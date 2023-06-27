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
				<th>Regular Skip Form</th>
				<th>Private Fill Form</th>
				<th>Private Skip Form</th>
			
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
					<td><?php echo $this->Common_model->getClassNameByClassId($count['old_class_id']) ?></td>
					<td><?php echo $count['cnt']; ?></td>
					<?php 
					$where = array('exam_form'  =>'Y','old_class_id'=>$count['old_class_id']);
					$Permitted = $this->Common_model->getCountByWhere('student',$where);
					$where_reg = array('exam_form'  =>'Y','university_mode'  =>'REG','old_class_id'=>$count['old_class_id']);
					$Permitted_reg = $this->Common_model->getCountByWhere('student',$where_reg);
					$this->db->where_in('exam_form',array('S','N'));
					$skip_where_reg = array('university_mode'  =>'REG','old_class_id'=>$count['old_class_id']);
					$skip_reg = $this->Common_model->getCountByWhere('student',$skip_where_reg);
					$where_pvt = array('exam_form'  =>'Y','university_mode'  =>'PVT','old_class_id'=>$count['old_class_id']);
					$Permitted_pvt = $this->Common_model->getCountByWhere('student',$where_pvt);
					$this->db->where_in('exam_form',array('S','N'));
					$skip_where_pvt = array('university_mode'  =>'PVT','old_class_id'=>$count['old_class_id']);
					$skip_pvt = $this->Common_model->getCountByWhere('student',$skip_where_pvt);
					
					?>
					<td><?php echo $Permitted; ?></td>
					<td><?php echo $Permitted_reg; ?></td>
					<td><?php echo $skip_reg; ?></td>
					
					<td><?php echo $Permitted_pvt; ?></td>
					<td><?php echo $skip_pvt; ?></td>
					
				</tr>
			<?php
				$i++; 
			}
			?>
		</tbody>
	</table>
</div>
