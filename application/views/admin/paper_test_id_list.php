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
			<th>Exam Time</th>
			<th>Exam Day</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; ?>
		<?php 
		$class_ids = array(104,101,107,110,116,119,273,125,128,131,134,162,163,164,165,283,285,287,289,310,291,293,295,274,297,168,169,170,171,214,106,103,109,112,118,121,127,130,133,136,173,174,175,177,180);
		$exam_time ="";
		foreach ($papers as $paper): 
			$old_paper_master ="";
			//$old_paper_master = $this->Common_model->getRecordByWhere('paper_master_july_24',array('id'=>$paper->id));
			if($paper->exam_shift=='Afternoon' && in_array($paper->class_id,$class_ids)){
				$exam_time = '3:00 PM To 6:00 PM';		
			}
			elseif($paper->exam_shift=='Afternoon'){
				$exam_time = '2:00 PM To 5:00 PM';
			}
			elseif($paper->exam_shift=='Morning' ){
				$exam_time ='10:00 AM To 1:00 PM';
			}
			
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
				<!-- <td><?=$paper->pvt_test_id ?></td>
				<td><?=$paper->pvt_exam_date ?></td>
				<td><?=$paper->pvt_exam_shift ?></td> -->
				<td><?=$exam_time ?></td>
				<td><?=$paper->exam_day ?></td>	
				<!--<td><?php if(!empty($old_paper_master[0]->exam_date)) echo $old_paper_master[0]->exam_date; ?></td>
				<td><?php if(!empty($old_paper_master[0]->exam_shift)) echo $old_paper_master[0]->exam_shift ?></td>
				<td><?php if(!empty($old_paper_master[0]->exam_day))  echo $old_paper_master[0]->exam_day ?></td> 	 -->
			</tr>
		<?php endforeach ?>
	</tbody>
</table>