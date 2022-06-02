<div class=" mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="dt-responsive">
		<table id="kt_datatable" class="table table-striped" >
			<thead>
				<tr>
					<th>#</th>
					<th>Center</th>
					<th>Teacher Name</th>
					<th>Course</th>
					<th>Class</th>
					<th>College Name</th>
					<th>Paper Code</th>
					<th>Paper Name</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
				foreach($teachers as $teacher){
					$course = $this->Common_model->getCourseNameByCourseId($teacher->course_group_id);
					$class = $this->Common_model->getClassNameByClassId($teacher->class_id);
					$where = array(
						'class_id'=> $teacher->class_id,
						'paper_code'=> $teacher->paper_code,
					);
					$paper= $this->Common_model->getRecordByWhere('paper_master',$where);
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<?php 
						if($teacher->center_id != ''){
							$teacher_id = $this->Common_model->encrypt_decrypt($teacher->teacher_id,'encrypt');
							$course_group_id = $this->Common_model->encrypt_decrypt($teacher->course_group_id,'encrypt');
							$class_id = $this->Common_model->encrypt_decrypt($teacher->class_id,'encrypt');
							$paper_code =  $this->Common_model->encrypt_decrypt($teacher->paper_code,'encrypt');
							?>
							<td><a  href='<?php echo base_url('admin/ExamController/teacher_alloted_exam_center/'). $teacher_id.'/'.$class_id.'/'.$course_group_id.'/'.$paper_code; ?>'>View</a></td>
							<?php	}else{ ?>
							<td>NA</td>
							<?php	}	?>
						<td><?php echo $teacher->name ?> </td>	
						<td><?php echo  $course?> </td>	
						<td><?php echo $class ?> </td>	
						<td><?php echo $teacher->clg_name ?> </td>	
						<td><?php echo $teacher->paper_code ?> </td>	
						<td><?php echo  $paper[0]->paper_name ?> </td>	
					</tr>
					<?php $i++; } ?>
				</tbody>
			</table>
		</div>
	</div>