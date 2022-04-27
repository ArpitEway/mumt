<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
<thead>
		<tr>
				<?php if(isset($students)){ ?>
				
					<th>Sno</th>
					
					<th>Form no</th>
					<th>Enrollment no</th>
					<th>Name</th>
					<th>F/H Name</th>
					<th>Mobile no</th>
					<th>DOB</th>
					<th>Course</th>
					<th>Class</th>
					
				
				<?php
				} 
				if(isset($course_count)){ ?>
				
					<th>Sno</th>
					<th>Course</th>
					<th>Count</th>
					
				<?php } if(isset($center_count)){  ?>
                    <th>Sno</th>
					<th>IC Name</th>
                    <th>IC Code</th>
					<th>Count</th>

                <?php } ?>
					
		</tr>
</thead>
<tbody>
    <?php 
			
    		$i = 1;
			
			if(isset($students)){
			foreach($students as $student){
			
			$userData = $this->Common_model->getRecordById('student_data','student_id',$student['student_id'])
			
			?>
			
				<tr>
				<td><?php echo $i; ?></td>

				<td><a target="_blank" href="<?php echo site_url('admin/'.$this->session->account_type.'/show_form/'.$this->Common_model->encrypt_decrypt($student['student_id'],'encrypt')); ?>"> <?=$student["student_id"]?></a></td>
				
				<td><?php echo $student["enrollment_no"]; ?></td>
				<td><?php echo ($student["name"]=='') ? $userData->name : $student["name"]; ?></td>
				<td><?php echo $student["f_h_name"]; ?></td>
				<td><?php echo $this->Common_model->getMobileNoByStudentID($student["student_id"]) ?></td>
				<td><?php 
				$newDate = ($student["dob"]=='') ? 'N/A' : $student["dob"];
				echo date("d-m-Y", strtotime($newDate)); ?>
				</td>
				<td>
					
				<?php if($student["course_name"]==''){
                    
					$course_group_id = $userData->course_group_id;
					echo $this->Common_model->getSinglefield('course_group','course_name','id='.$course_group_id);
					}else{
					echo $student["course_name"];
					} ?></td>
					<td><?php if($student["class_name"]==''){
				
					$class_id = $student['class_id'];
					echo $this->Common_model->getSinglefield('class_master','class_name','id='.$class_id);
					}else{
					echo $student["class_name"];
					} ?>

				</td>
					
			</tr>
			<?php
			$i++; } }
			?>
			
			<?php	
			if(isset($center_count)){ 

			$total = 0;
			foreach($center_count as $student){	
				
			?>
			<tr>
				
			<td><?php echo $i; ?></td>
			<td><?php echo $this->Common_model->getCenterNameById($student["center_id"]); ?></td>
            <td><?php echo $this->Common_model->getCenterCodeById($student["center_id"]) ?></td>
			<td><?php echo $student["cnt"]; ?></td>
			<?php $total = $total + $student["cnt"];?>
			</tr>
			
			
			
			<?php $i++; } ?>
			<tfoot>
			<tr>
			<td></td>
			<td><?php echo "Total"; ?></td>
			<td></td>
			<td><?php echo $total ?></td>
			</tr>
			<tfoot>
			<?php } ?>
            <?php	
			if(isset($course_count)){ ?>
			
			<?php
			$total = 0;
			foreach($course_count as $student){	
              
				
			?>
			<tr>
				
			<td><?php echo $i; ?></td>
			<td><?php echo $this->Common_model->getCourseNameByCourseId($student["course_group_id"]); ?></td>
			<td><?php echo $student["cnt"]; ?></td>
			<?php $total = $total + $student["cnt"];?>
			</tr>
			
			
			
			<?php $i++; } ?>
			<tfoot>
			<tr>
			<td></td>
			<td><?php echo "Total"; ?></td>
			<td><?php echo $total ?></td>
			</tr>
			<tfoot>
			<?php } ?>
</tbody>
</table>