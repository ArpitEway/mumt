<div class="container">
	<table class="table text-uppercase">
		<thead>
			<tr>

				<th>Sn.no</th>
				<th>Course</th>
				<th>Class</th>
				<th>Paper</th>
				<th>Total Count</th>
				<th>Checked</th>
				<th>Remaining</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;

			foreach($assigns as $assign){	

				$where = array(
					'center_id in ('.$assign->center_id.')',
					'paper_code' => $assign->paper_code,
					'file_exist'=>'Y',
					'class_id' => $assign->class_id,
					'course_group_id' => $assign->course_group_id
				);

				$total_paper_count=$this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
//$this->Common_model->last_query();
				$checked = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array('paper_code'=>$assign->paper_code,'teacher_id!='=> ''));

				?>

				<tr>

					<td><?php echo $i; ?></td>
					<td><?=$this->Common_model->getCourseNameByCourseId($assign->course_group_id); 	
					?>
				</td>
				<td><?=$this->Common_model->getClassNameByClassId($assign->class_id); ?></td>

				<td>
					<?php
					$papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$assign->paper_code));

					?>
					<?php echo '('.$assign->paper_code.')'. $papername[0]->paper_name ; ?>	
				</td>

				<td>
					<?php echo  $total_paper_count ; ?>
				</td>

				<td>
					<?php echo $checked  ; ?>
				</td>
				<td><?php  echo $total_paper_count-$checked_count ; ?></td>


			</tr>
			<?php  $i++; } ?>
		</tbody>
	</table>
</div>