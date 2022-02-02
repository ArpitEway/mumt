

<form method='POST'  action="<?=base_url('admin/ExamController/generate_enrollment');?>">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Action</label>
			<select class="form-control" name="session" id="session" class="session">
            <option value="" >select </option>

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


<table id="mytable" class="table">
			<thead>
				<tr>
				
					<th>Sno.</th>
					<th>Student Name</th>
					<th>Course Name</th>
					<th>Class Name</th>

					<th>Enrollment no</th>
					
					
				</tr>
			</thead>
			<tbody id="sortable">
<?php
	if($_POST['action']=='generate_enrollment'){
		 	
    	$where = array(
			'session' => $_POST['session'],
			'approved' =>'Y',
			'enrolled' =>'N',
			'enrollment_no'=>'-'
			);
	 $students = $this->db->get_where('student',$where)->result_array();

		$whereEnrollmentNoCount = array(
			'enrollment_no!=' => '-',
		);
	
     $year = $_POST['session'];

	 $lastTwoNumbers = (int) substr($year, -2);
	
	 $month =  strtok($_POST['session'], " "); 

	 $enrolment_code = $this->db->get_where('session', array('session'=>$_POST['session']))->result_array();
	 $last_number = $this->Common_model->getCountByWhere('student',$whereEnrollmentNoCount);

	  foreach($students as $student){
		
	   $last_number = str_pad($last_number,5,'0',STR_PAD_LEFT);
	   if($month=='Jan'){
		$enrollment = $enrolment_code[0]['enrollment_code'].$lastTwoNumbers.'1'.str_pad($last_number,3,"0",STR_PAD_LEFT );

	   }else{
		$enrollment = $enrolment_code[0]['enrollment_code'].$lastTwoNumbers.'2'.str_pad($last_number,3,"0",STR_PAD_LEFT );
	   }
		$last_number++ ;
		
			$whereUpdate = array('student_id' => $student['student_id']);
			$updateData = array('enrollment_no' =>$enrollment);
			$updateEnrollment = $this->Common_model->updateRecordByConditions('student',$whereUpdate,$updateData);

?>
         <tr>
            <td><?php echo $i++; ?></td>
			<td><?php echo $student["name"]; ?></td>
			<td><?php echo $student["course_name"]; ?></td>
			<td><?php echo $student["class_name"]; ?></td>
			<td><?php echo $enrollment; ?></td>
		</tr>
		<?php
	  }
	}  
?>
    		
    		
			
			
			</tbody>
</table>

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
