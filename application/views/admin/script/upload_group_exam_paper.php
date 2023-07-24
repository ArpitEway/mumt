<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>Course name</th>
			<th>Class Name</th>
			<th>Total Student</th>
		</tr>
	</thead>
	<tbody>
<?php
$i = 0;
 foreach ($studentClasses as $class): ?>
		<tr>
			<td><?=++$i?></td>
			<td><?=$class->course_name;?></td>
			<td><?=$class->class_name;?></td>
		
			<td><a href="<?=base_url('admin/scripts/Preexam/group_upload_group_exam_paper_sub/').$class->class_id;?>" target="_blank"> <?=$class->num?></a></td>
		</tr>
<?php endforeach ?>
	</tbody>
</table>
</div>