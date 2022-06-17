<div id="" class="dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable_scroll" class="table table-striped nowrap">
		<thead>
			<tr>
				<th>#</th>
				<th>Center Code</th>
				<th>Center Name</th>
				<th>Mobile No</th>
				<th>Roll No.</th>
				<th>Enrollment No.</th>
				<th>Name</th>
				<th>Course</th>
				<th>Class</th>
				<?php
				$count = 1;
				while ($count <= 10) {
					?>
					<th><?php echo 'paper'.$count;?></th>
					<?php
					$count++; 		
				} ?>

			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo $student->center_code; ?></td>
					<td><?php echo $this->Common_model->getCenterNameById($student->center_id); ?></td>
					<td><?php echo $this->Common_model->getMobileNoByStudentID($student->student_id); ?></td>
					<td><?php echo $student->roll_no; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<?php
					$tot_papermark_count=0;				
					$tot_papermark=0;
					$papermarks =  $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array('student_id'=>$student->student_id,'teacher_id!='=>'','total_marks!='=>0));	

					$new_exam_form_count =  $this->Common_model->getCountByWhere('new_exam_form',array('student_id'=>$student->student_id,'theory_marks'=>''));	
					foreach($papermarks as $paper){   
						$tot_papermark += $paper->total_marks;
						$tot_papermark_count++;
					} 
					$average_tot_marks= $tot_papermark / $tot_papermark_count;
					$avg_all = round($average_tot_marks/5);
					$marks_5 = $average_tot_marks - ($avg_all*4);
					  if($new_exam_form_count<1){
						$blankmark = array('total_marks'=>$marks_5 );
						$where = array('student_id'=>$student->student_id, 'total_marks'=>'');
						$this->Common_model->updateRecordByConditions('upload_exam_ans_sheet', $where, $blankmark);
					}
					?>
					<?php
					$marksdatas =  $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array('student_id'=>$student->student_id,'teacher_id!='=>''));	
					$paper_count = 1;

					foreach($marksdatas as $marksdata){   
						?>
						<td><?=($marksdata->total_marks=='') ? '0 F' : $marksdata->total_marks;	?> </td>
						<?php
						$paper_count++;
					}
					while ($paper_count <= 10) {
						$paper_count++;
						?>
						<th></th>
					<?php } ?>

				</tr>
			<?php  }	?>
		</tbody>
	</table>
</div>