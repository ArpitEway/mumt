<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>Course name</th>
			<th>Class Name</th>
			<th>Regular Student</th>
			<th>Private Student</th>
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
			<td><?php if($class->regularCount){ ?><a href="<?=base_url('admin/scripts/Preexam/upload_exam_paper_sub/').$class->class_id."/REG";?>" target="_blank"><?=$class->regularCount;?></a><?php } else echo $class->regularCount;?></td>
			<td><?php if($class->privateCount){ ?><a href="<?=base_url('admin/scripts/Preexam/upload_exam_paper_sub/').$class->class_id."/PVT";?>" target="_blank"><?=$class->privateCount;?></a><?php } else echo $class->privateCount;?></td>
			<td><?=$class->num?></td>
		</tr>
<?php endforeach ?>
	</tbody>
</table>
</div>