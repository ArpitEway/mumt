<div class="container-fluid mt-5 table-responsive" >
	<table class="table table-striped" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Roll No.</th>
				<th>Enrollment No.</th>
				<th>Student Name</th>
				<th>Course Name</th>
				<th>Class Name</th>
				<th>Admit Card</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i ; ?></td>

					<td><?php echo $student->roll_no; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $this->Common_model->getStudentNameById($student->student_id); ?></td>
                    <td><?php echo $this->Common_model->getCourseNameByCourseId($student->course_group_id); ?> </td>
					<td><?php echo $this->Common_model->getClassNameByClassId($student->class_id); ?> </td>
					<td><a target="_blank" href="<?=base_url('backlog_admit_card/'.$this->Common_model->encrypt_decrypt($student->id,'encrypt'));?>"><i class="far fa-eye text-info mr-5"></i></a></td>	
				</tr>
				<?php 
				$i++;
			}
			?>

		</tbody>
	</table>
</div>