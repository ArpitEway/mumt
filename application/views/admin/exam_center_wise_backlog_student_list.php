<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<!-- <th>Image</th> -->
			<th>Roll No</th>
			<th>Enrollment</th>
			
			<th>Name</th>	
			<th>Father Name</th>
			<th>Course Name</th>
            <th>Class Name</th>
            <!-- <th>Aadhar No</th> -->
            <!-- <th>Contact No</th> -->
		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($listing as $list)
		{ 
		$student=	$this->Common_model->getRecordByWhere('student',array("student_id"=>$list->student_id));
		//print_r($student);
			?>
			<tr>
				<td><?= $i; ?></td>
				<!-- <td> <a href="<?php echo base_url('assets/student_image/'.$list->session.'/'.$list->photo);?>" download="<?php echo $student->student_id ;?>">
				<img src="<?= base_url('assets/student_image/'.$list->session.'/'.$list->photo) ?>" height="50px"  width="50" style="vertical-align:top;"></a>
				</td> -->
				<td><?= $list->roll_no; ?></td>
				<td><?= $list->enrollment_no; ?></td>
				
				<td><?= $student[0]->name; ?></td>
				<td><?= $student[0]->f_h_name; ?></td>
				<td><?= $student[0]->course_name; ?></td>
				<td><?= $student[0]->class_name; ?></td>
				<!-- <td><?= $list->adhar_no; ?></td> -->
				<!-- <td><?php echo $this->Common_model->getMobileNoByStudentID($list->student_id); ?></td> -->
			</tr>
		<?php
		$i++; } 
		?>
	</tbody>
</table>
<script>
	$("#headerTitle").html("MDE001 Student List");
		</script>	