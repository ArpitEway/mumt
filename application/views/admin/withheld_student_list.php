
<table class="table table-striped" id="kt_datatable">
	<tbody>
		<tr>
            <th>Roll No</th>
            <th>ICCode </th>
            <th>Student Name </th>
            <th>Course Name</th>
            <th>Class</th>
            <th>Paper(WH)</th>
		</tr>
        <?php foreach($students as $student) {
            
             
            ?>
        <tr>
			<td><?= $student->roll_no ?></td>
			<td><?= $student->center_code ?></td>
			<td><?= $student->name ?></td>
			<td><?= $student->course_name ?></td>
			<td><?= $student->class_name ?></td>
            <td><?= $student->cnt ?></td>
		</tr>
        <?php  }  ?>
	</tbody>
</table>
