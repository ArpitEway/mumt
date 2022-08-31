<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<th>Image</th>
			<th>Enrollment</th>
			<th>Roll No</th>
			<th>Name</th>	
			<th>Father Name</th>
			<th>Course Name</th>
            <th>Class Name</th>
            <th>Adhar No</th>
            <th>Contact No</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($listing as $list)
		{ ?>
			<tr>
				<td><?= $i; ?></td>
				<td>
				<img src="<?= base_url('assets/student_image/'.$list->session.'/'.$list->photo) ?>" height="100px" style="vertical-align:top;">
				</td>
				<td><?= $list->enrollment_no; ?></td>
				<td><?= $list->roll_no; ?></td>
				<td><?= $list->name; ?></td>
				<td><?= $list->f_h_name; ?></td>
				<td><?= $list->course_name; ?></td>
				<td><?= $list->class_name; ?></td>
				<td><?= $list->adhar_no; ?></td>
				<td><?php echo $this->Common_model->getMobileNoByStudentID($list->student_id); ?></td>
			</tr>
		<?php
		$i++; } 
		?>
	</tbody>
</table>