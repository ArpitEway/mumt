<div class="mt-5 dt-responsive " >
	<table id="kt_datatable" class="table table-striped" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Form no</th>
				<th>Session</th>
				<th>Name</th>
				<th>F/H Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Mode</th>
				<th>Select Paper</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$i = 1;
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $student->student_id; ?> </td>
					<td><?php echo $student->session; ?> </td>
					<td><?php echo $student->name; ?> </td>
					<td><?php echo $student->f_h_name; ?> </td>
					<td><?php echo $student->course_name; ?> </td>

					<td><?php echo $student->class_name; ?> </td>
					<td><?php echo $student->university_mode; ?> </td>

					<td>
						<?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id); ?>
						<a class="btn btn-primary" href="<?=base_url('center/center/select_papers/'.$student_id)?>">Select Papers</a>
					</td>
				</tr>
				<?php
				$i++;
			} 
			?>
		</tbody>
	</table>
</div>