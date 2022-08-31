<table id="kt_datatable" class="table table-striped table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Course</th>
			<th>Class</th>
			<th>Paper No</th>
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
		<?php foreach ($papers as $paper): ?>
			<tr>
				<td><?=$i++ ?></td>
				<td><?=$paper->course_name ?></td>
				<td><?=$this->Common_model->getClassNameByClassId($paper->class_id); ?></td>
				<td><?=$paper->paper_no ?></td>
				<td><?=$paper->paper_name ?></td>
				<td><?=$paper->paper_code ?></td>
				<td><?=$paper->type ?></td>
				<td><?=$paper->ce ?></td>
				<td><?=$paper->test_id ?></td>
				<td><?=$paper->exam_date ?></td>
				<td><?=$paper->exam_shift ?></td>
				<td><?=$paper->exam_day ?></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>