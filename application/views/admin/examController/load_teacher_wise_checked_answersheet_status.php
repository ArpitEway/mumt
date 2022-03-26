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
				<th>Open Answersheet Count </th>
				<th>Remark Count </th>
				<th>Checked </th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($teachers as $teacher){
                    $paper_name = $this->Common_model->getSinglefield('paper_master','paper_name',array('class_id'=>$teacher->class_id));
                    $teacher_name = $this->Common_model->getSinglefield('teacher','name',array('id'=>$teacher->teacher_id));

                    $class = $this->Common_model->getClassNameByClassId($teacher->class_id);
                    $course = $this->Common_model->getCourseNameByCourseId($teacher->course_group_id);
                    $this->db->select('count(*) as cnt ');
                    $this->db->from('upload_exam_ans_sheet');
                    $this->db->group_by('paper_code,class_id');
                    $this->db->where('teacher_id',$teacher->teacher_id);
                    $this->db->where('paper_code',$teacher->paper_code);
                    $checked_count = $this->db->get()->result();
                    $this->db->select('count(*) as open_answersheet_count');
                    $this->db->from('upload_exam_ans_sheet');
                    $this->db->join('answer_sheet_json_data', 'upload_exam_ans_sheet.id = answer_sheet_json_data.uplode_examsheet_id');
                    $this->db->where('upload_exam_ans_sheet.paper_code',$teacher->paper_code); 
                    $this->db->where('upload_exam_ans_sheet.class_id', $teacher->class_id); 
                    $this->db->where('upload_exam_ans_sheet.teacher_id', $teacher->teacher_id); 
                    $open_answersheet_count = $this->db->get()->result();

                    $this->db->select('count(*) as remark_count');
                    $this->db->from('upload_exam_ans_sheet');
                    $this->db->join('answer_sheet_json_data', 'upload_exam_ans_sheet.id = answer_sheet_json_data.uplode_examsheet_id');
                    $this->db->where('upload_exam_ans_sheet.paper_code',$teacher->paper_code); 
                    $this->db->where('upload_exam_ans_sheet.class_id', $teacher->class_id); 
                    $this->db->where('upload_exam_ans_sheet.teacher_id', $teacher->teacher_id); 
                    $this->db->where('json_data!=',""); 
                   $remark_count = $this->db->get()->result();
				?>
				<tr>
					<td><?php echo 	$i; ?></td>
					<td><?php echo $teacher_name; ?></td>
					<td><?php echo 	$course; ?></td>
					<td><?php echo  $class; ?></td>
					<td><?php echo $paper_name; ?></td>
					<td><?php echo $open_answersheet_count[0]->open_answersheet_count  ?></td>
					<td><?php echo $remark_count[0]->remark_count  ?></td>
					<td><?php echo  $checked_count[0]->cnt; ?></td>
				</tr>
			<?php $i++; } ?>
		</tbody>
	</table>
</div>