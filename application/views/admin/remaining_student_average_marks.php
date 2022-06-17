<div id="" class="dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable_scroll" class="table table-striped nowrap">
		<thead>
			<tr>
				<th>#</th>
				
				<th>Form No.</th>
				<th>Enrollment No.</th>
				<th>Course</th>
				<th>Class</th>
				<th>Paper Code</th>
				<th>View</th>

			</tr>
		</thead>
		<tbody>
			<?php 

			$i = 1;
			foreach($students as $student){
				$new_exam_form_count =  $this->Common_model->getCountByWhere('new_exam_form',array('student_id'=>$student->student_id,'theory_marks'=>'','paper_type'=>'theory'));	

				if($new_exam_form_count==1){
					$tot_papermark_count=0;				
					$tot_papermark=0;
					$papermarks =  $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array('student_id'=>$student->student_id,'teacher_id!='=>'','paper_code!='=>$student->paper_code));	
					
					foreach($papermarks as $paper){   
						$tot_papermark += $paper->total_marks;
						$tot_papermark_count++;
					} 
					$average_tot_marks= $tot_papermark / $tot_papermark_count;
					$avg_all = round($average_tot_marks/5);
					$marks_5 = $average_tot_marks - ($avg_all*4);
					
					// $blankmark = array('total_marks'=>$average_tot_marks ,
					// 	'que_1'=>$avg_all,'que_2'=>$marks_5,'que_3'=>$avg_all,'que_4'=>$avg_all,'que_5'=>$avg_all,
					// );
					// $where = array('student_id'=>$student->student_id, 'total_marks'=>'');
					// $this->Common_model->updateRecordByConditions('upload_exam_ans_sheet', $where, $blankmark);
				}
				?>	
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo $student->student_id; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php echo $student->paper_code; ?></td>
					<td></td>
				</tr>
			<?php  }	?>
		</tbody>
	</table>
</div>