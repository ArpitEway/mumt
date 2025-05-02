<table id="kt_datatable" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Course</th>
			<th>Class</th>
			<th>Paper ID</th>
			<th>Paper No</th>
			<th>New Pattern</th>
			<th>Paper Name</th>
			<th>Paper Code</th>
			<th>Type</th>
			<th>Other</th>
			<th>Test ID</th>
			<th>Exam Date</th>
			<th>Exam Shift</th>
			<th>Exam Day</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; ?>
		<?php foreach ($papers as $paper): 
			$old_paper_master ="";
			// $old_paper_master = $this->Common_model->getRecordByWhere('paper_master_july_24',array('id'=>$paper->id));
			
			?>
			<tr>
				<td><?=$i++ ?></td>
				<td><?=$paper->course_name ?></td>
				<td><?=$this->Common_model->getClassNameByClassId($paper->class_id); ?></td>
				<td><?=$paper->id ?></td>
				<td><?=$paper->paper_no ?></td>
				<td><?=$paper->cbcs_paper ?></td>
				<td><?=$paper->paper_name ?></td>
				<td><?=$paper->paper_code ?></td>
				<td><?=$paper->type ?></td>
				<td><?=$paper->ce ?></td>
				<td><?=$paper->test_id ?></td>
				 <td><?=$paper->exam_date ?></td>
				<td><?=$paper->exam_shift ?></td>
				<td><?=$paper->exam_day ?></td>		
				<!-- <td><?php //if(!empty($old_paper_master[0]->exam_date)) echo $old_paper_master[0]->exam_date; ?></td>
				<td><?php //if(!empty($old_paper_master[0]->exam_shift)) echo $old_paper_master[0]->exam_shift ?></td>
				<td><?php //if(!empty($old_paper_master[0]->exam_day))  echo $old_paper_master[0]->exam_day ?></td>  -->
			</tr>
		<?php endforeach ?>
	</tbody>
</table>