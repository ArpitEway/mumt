<div class="container mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>S No</th>
				<th>Form No</th>
				<th>Course</th>
				<th>Class</th>
				<th>Name</th>
				<th>Father Name</th>
				<th>Mobile</th>
				<th>DOB</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 1;
				foreach($students as $student){
				?>

				<tr id="student_tr_<?php echo $student['student_id']; ?>">
				<td><?php echo $i; ?></td>
				<td><?php echo $student['student_id']; ?></td>
				<td><?php echo $student['course_name']; ?></td>
				<td><?php echo $student['class_name']; ?></td>
				<td><?php echo $student['name']; ?></td>
				<td><?php echo $student['f_h_name']; ?></td>
				<td><?php
					echo $mobile_no = $this->Common_model->getMobileNoByStudentID($student['student_id']); 
					?></td>
					<td><?php
					echo ($student['dob'] != "") ? $this->Common_model->viewDate($student['dob']) :  "" ; 
					?></td>
				</tr>
				<?php 
					$i++;
				}
			?>
		</tbody>
	</table>
</div>
</div>