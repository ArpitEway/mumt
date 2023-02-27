<div id="" class="dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable_scroll" class="table table-striped nowrap">
		<thead>
			<tr>
				<th>#</th>
				<th>Center Code</th>
				<th>Exam Center Code</th>
				<th>Roll No.</th>
				<th>Enrollment No.</th>
				<th>Name</th>
				<th>Course</th>
				<th>Class</th>
				<?php
				$this->db->limit('9');
				$papersobj = $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$class_id,'type'=>'theory'));
				foreach ($papersobj as $key => $row) {
					?>
					<th><?php echo $row->paper_code;?></th>
				<?php } ?>
				<th>Average</th>
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
					<td><?php echo $student->examcentercode; ?></td>
					<td><?php echo $student->roll_number; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<?php 
					$marksdatas =  $this->Common_model->getRecordByWhere('new_exam_form',array('student_id'=>$student->student_id,'class_id'=>$student->class_id,'paper_type'=> 'theory'));
					$paper_count = 0;
					$tot_marks = 0;
					$fail_id = 0;
					$max_theory_marks=0;
					$min_fail=0;
					$max_fail=0;
					$max_theory_marks_c=0;
					
					foreach($marksdatas as $marksdata){
						
						if ($student->university_mode=='REG') {
							$min_max_marks = $this->Common_model->getRecordByWhere('paper_master',array('paper_code' => $marksdata->paper_code, 'class_id' => $marksdata->class_id));
							$min_theory_marks = $min_max_marks[0]->min_theory_marks;
							$max_theory_marks_c =  $min_max_marks[0]->max_theory_marks;
							// $max_theory_marks += $this->Common_model->getSinglefield('paper_master','max_theory_marks',array('paper_code' => $marksdata->paper_code, 'class_id' => $marksdata->class_id));


						} else {
							$min_max_marks = $this->Common_model->getRecordByWhere('paper_master',array('paper_code' => $marksdata->paper_code, 'class_id' => $marksdata->class_id));
							$min_theory_marks = $min_max_marks[0]->private_min_theory_marks;
							$max_theory_marks_c =  $min_max_marks[0]->private_max_theory_marks;
							// $max_theory_marks += $this->Common_model->getSinglefield('paper_master','private_max_theory_marks',array('paper_code' => $marksdata->paper_code, 'class_id' => $marksdata->class_id));
						}						
						
						if ($marksdata->theory_marks<$min_theory_marks) {
							$fail_id = $marksdata->id;
							$min_fail = $min_theory_marks;
							 $max_fail = $max_theory_marks_c;

							 // if ($student->university_mode=='REG') {
							 // $max_theory_marks -= $this->Common_model->getSinglefield('paper_master','max_theory_marks',array('paper_code' => $marksdata->paper_code, 'class_id' => $marksdata->class_id));
							 // }else{
							// 	$max_theory_marks -= $this->Common_model->getSinglefield('paper_master','private_max_theory_marks',array('paper_code' => $marksdata->paper_code, 'class_id' => $marksdata->class_id));
							 // }
							echo "<td class='text-danger font-weight-bolder'>". $marksdata->theory_marks.' F</td>';
						}else{
							$tot_marks += $marksdata->theory_marks;
							$tot_max_theory_marks_c += $max_theory_marks_c;
							echo "<td>".$marksdata->theory_marks."</td>";
							// $paper_count++;
							
						}
					}
					$avg_percent = $tot_marks *100/$max_theory_marks;
					// echo $tot_marks.'/'.$max_fail.'/'.$min_fail.'/'.$max_theory_marks.'<br>';
					$avg_marks = round($max_fail*$avg_percent/100);
					?>
					<td>
						<a  target="_blank" href="<?php echo base_url('admin/scripts/Otherscript/update_student_remaining_marks/').$student->student_id.'/'.$fail_id.'/'.$avg ?>"> 
						<?php echo $avg_marks ?>
						</a>
						<?php
						 //if($avg_marks > $min_fail){$this->Common_model->updateRecordByConditions('new_exam_form',array('id'=>$fail_id,'student_id'=>$student->student_id,'theory_marks'=>''),array('theory_marks'=>$avg_marks));
							//}
							?>
					</td>
				</tr>
			<?php  }	?>
		</tbody>
	</table>
</div>