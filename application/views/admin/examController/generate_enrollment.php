<form method='POST'  action="<?=base_url('admin/ExamController/generate_enrollment');?>">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Session</label>
			<select class="form-control" name="session" id="session" class="session">
				<option value="" >Select Session</option>
				<?php
				$i = 1;
				foreach ($session as $row) { ?>
					<option value="<?=$row['session'];?>" ><?=$row['session'];?></option>
				<?php } ?>
			</select>

		</fieldset>
		<fieldset class="form-group text-center">
			<input type="hidden" name="action" value="generate_enrollment">
			<button type="submit" class="btn btn-primary">Submit</button>
		</fieldset>
	</div>

</form>
<?php
		if($_POST['action']=='generate_enrollment'){
			?>

<table id="mytable" class="table">
	<thead>
		<tr>

			<th>Sno.</th>
			<th>Form No</th>
			<th>Student Name</th>
			<th>Course Name</th>
			<th>Class Name</th>
			<th>Center Code</th>
			<th>Enrollment no</th>
		</tr>
	</thead>
	<tbody id="sortable">
		<?php
			$session = $_POST['session'];
			$where = array(
				'session' => $session,
				'approved' =>'Y',
				'enrolled' =>'N',
				'enrollment_no'=>'-'
			);
			
			$students = $this->db->get_where('student',$where)->result_array();
			$enrolment_code = $this->db->get_where('session', array('session'=>$session))->result_array();

			$whereEnrollmentNoCount = array(
				'enrollment_no!=' => '-',
				'session' => $session,
			);
			//$this->Common_model->getCountByWhere('student',$whereEnrollmentNoCount);
			$last_enrollment = $this->Common_model->get_record('student','max(enrollment_no) as enrollment_no');
			$last_number = substr($last_enrollment[0]['enrollment_no'], -3);
			$lastTwoNumbers = (int) substr($session, -2);
			$month =  strtok($session, " ");

			foreach($students as $student){
				$last_number++ ;
				$last_number = str_pad($last_number,5,'0',STR_PAD_LEFT);
				if($month=='Jan'){
					$enrollment = $enrolment_code[0]['enrollment_code'].'/'.$lastTwoNumbers.'1'.$last_number;
				}else{
					$enrollment = $enrolment_code[0]['enrollment_code'].'/'.$lastTwoNumbers.'2'.$last_number;
				}
				

				$whereUpdate = array('student_id' => $student['student_id']);
				$updateData = array('enrollment_no' =>$enrollment);
				$updateEnrollment = $this->Common_model->updateRecordByConditions('student',$whereUpdate,$updateData);

				?>
				<tr>
					<td><?php echo $i++; ?></td>
					<td><?php echo $student["student_id"]; ?></td>
					<td><?php echo $student["name"]; ?></td>
					<td><?php echo $student["course_name"]; ?></td>
					<td><?php echo $student["class_name"]; ?></td>
					<td><?php echo $student["center_code"]; ?></td>
					<td><?php echo $enrollment; ?></td>
				</tr>
				<?php
			}
		}  
		?>
	</tbody>
</table>