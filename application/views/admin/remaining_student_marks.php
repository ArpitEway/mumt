<div id="" class="dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped nowrap">
		<thead>
			<tr>
				<th>#</th>
				<th>Center Code</th>
				<th>Center Name</th>
				<th>Mobile No</th>
				<th>Roll No.</th>
				<th>Enrollment No.</th>
				<th>Name</th>
				<th>Course</th>
				<th>Class</th>
				<?php
				$count = 1;
				while ($count < 10) {
					?>
					<th><?php echo 'paper'.$count;?></th>
					<?php
					$count++; 		
				} ?>
	
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo $student->center_code; ?></td>
					<td><?php echo $this->Common_model->getCenterNameById($student->center_id); ?></td>
					<td><?php echo $this->Common_model->getMobileNoByStudentID($student->student_id); ?></td>
					<td><?php echo $student->roll_no; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<?php 
					$marksdatas =  $this->Common_model->getRecordByWhere('new_exam_form',array('student_id'=>$student->student_id,'paper_type'=>'theory'));	
					$paper_count = 1;
					foreach($marksdatas as $marksdata){
						?>
						<td><?php if($marksdata->theory_marks==''){ 
							echo '0 F'; 
						}else {
						echo $marksdata->theory_marks; 
                         }
						?> </td>
						
						
					<?php
					$paper_count++;
					  } ?>
					<?php
			
				while ($paper_count < 10) {
					?>
					<th></th>
					<?php
					$paper_count++; 		
				} ?>
				</tr>			
				<?php

			} 
			?>
		</tbody>
	</table>
</div>