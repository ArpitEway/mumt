<div class=" dt-responsive  mt-5">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped "  >
		<thead>
			<tr>
				<th>#</th>
				<th>Teacher Name</th>
                <th>Course </th>
                <th>Class </th>
                <th>Paper Name </th>
				<th>Total Checked Count </th>
				<th>Open Count </th>
				<th>Not Open Count </th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			$tot_checked_count = 0;
			$tot_open_answersheet = 0;
			$tot_remaining = 0;
			foreach($teachers as $teacher){
                    $paper_name = $this->Common_model->getSinglefield('paper_master','paper_name',array('class_id'=>$teacher->class_id,'paper_code'=>$teacher->paper_code));
                    $teacher_name = $this->Common_model->getSinglefield('teacher','name',array('id'=>$teacher->teacher_id));

                    $class = $this->Common_model->getClassNameByClassId($teacher->class_id);
                    $course = $this->Common_model->getCourseNameByCourseId($teacher->course_group_id);
                    $where = array('teacher_id' =>$teacher->teacher_id,'paper_code'=> $teacher->paper_code, 'class_id' =>$teacher->class_id );
                    $checked_count = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
                    $where['open_answersheet'] = 'Y';
                    $open_answersheet = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
                    $tot_checked_count += $checked_count;
                    $tot_open_answersheet += $open_answersheet;
                    $tot_remaining += $checked_count - $open_answersheet;
				?>
				<tr>
					<td><?php echo 	$i; ?></td>
					<td><?php echo $teacher_name; ?></td>
					<td><?php echo 	$course; ?></td>
					<td><?php echo  $class; ?></td>
					<td><?php echo $paper_name; ?></td>
					<td><?php echo  $checked_count; ?></td>
					<td><?php echo $open_answersheet;  ?></td>
					<td><?php echo $checked_count-$open_answersheet  ?></td>
				</tr>
			<?php $i++; } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5" class="text-right">Total</th>
				<th><?=$tot_checked_count;?></th>
				<th><?=$tot_open_answersheet?></th>
				<th><?=$tot_remaining?></th>
			</tr>
		</tfoot>
	</table>
</div>