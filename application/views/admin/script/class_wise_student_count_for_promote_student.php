
<table class="table table-striped" id="kt_datatable">
	<tbody>
		<tr>
            <th>Course Name</th>
            <th>Class Name </th>
            <th>Student Count </th>
            <th>Action </th>
		</tr>
        <?php foreach($courses as $course) {
            ?>
        <tr>
			<td><?= $course->course_name ?></td>
			<td><?= $course->class_name ?></td>
			<td><?= $course->cnt ?></td>
			<td><a class="btn btn-primary" href="<?=base_url('admin/scripts/Postexam/promote_student/').$course->class_id.'/'.$course->course_group_id;?>" >Promote</a></td>
		</tr>
        <?php  }  ?>
	</tbody>
</table>
