<style>
	.btn.btn-primary i {
		color: #FFFFFF !important;
	}
</style>
<div class=" mt-5 dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable_scroll" class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Roll No</th>
				<th>Enrollment No </th>
				<th>Student Name </th>
				<th>Course </th>
				<th>Total Paper </th>
				<th>Remaining Paper </th> 
				<th>View</th>     
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($students as $student){
				$uploaded_paper  = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array('student_id'=>$student->student_id,'course_group_id'=>$student->course_group_id, 'class_id'=>$student->class_id));
				$remaining =$student->cnt - $uploaded_paper;
				$mobile_no  = $this->Common_model->getMobileNoByStudentID($student->student_id);
				if($remaining !=0 ){
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo  $student->roll_no; ?></td>
						<td><?php echo  $student->enrollment_no ; ?></td>
						<td><?php echo $student->name ; ?></td>
						<td><?php echo  $student->course_name ; ?></td>
						<td><?php echo  $student->cnt ; ?></td>
						<td><?php echo $remaining ;?></td>
						<td><a  target="_blank" href="<?=base_url('exam_paper/'.$this->Common_model->encrypt_decrypt($student->student_id,'encrypt'));?>">Papre</a></td>
					</tr>
					<?php $i++;
				}  
			} ?>
		</tbody>
	</table>
</div>