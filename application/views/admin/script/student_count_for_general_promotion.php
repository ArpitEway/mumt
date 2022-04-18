
<table class="table table-striped" id="kt_datatable">
	<tbody>
		<tr>
			
            <th>Course Name</th>
            <th>Class Name </th>
            <th>Paper Count </th>
            <th>Update </th>
		</tr>

        <?php foreach($courses as $course) {
        	
            ?>
        <tr>
        	
			<td><?= $course->course_name ?></td>
			<td><?= $course->class_name ?></td>
			<td><?= $course->cnt ?></td>
			<td><a class="btn btn-primary" href="<?=base_url('admin/scripts/Postexam/general_promotion_student_list/').$course->class_id;?>" >Update</a></td>
		</tr>
        <?php
       }  ?>
	</tbody>
</table>
