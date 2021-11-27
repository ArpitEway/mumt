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
					<th>Action</th>
					
				
				<?php
				} 
				if(isset($course_count)){ ?>
				
					<th>Sno</th>
					<th>Course</th>
					<th>Count</th>
					
				<?php } ?>
					
		</tr>
</thead>
<tbody>
    <?php 
			
    		$i = 1;
			
			if(isset($students)){
			foreach($students as $student){
			
			// $userData = $this->Common_model->getRecordById('user_enquiry','student_id',$student['student_id'])
			
			?>
			
				<tr>
				<td><?php echo $i; ?></td>
				<td><?=$student["student_id"]?></td>
				
				<td><?php echo $student["enrollment_no"]; ?></td>
				<td><?php echo ($student["name"]=='') ? 'N/A' : $student["name"]; ?></td>
				<td><?php echo $student["f_h_name"]; ?></td>
				<td><?php echo $this->Common_model->getMobileNoByStudentID($student["student_id"]) ?></td>
				 <td><?php 
					$newDate = ($student["dob"]=='') ? 'N/A' : $student["dob"];
					
					echo date("d-m-Y", strtotime($newDate)); ?></td>
				<td><?php if($student["course_name"]==''){
				
					$course_group_id = 'N/A';
					echo $this->Common_model->getSinglefield('course_group','course_name','id='.$course_group_id);
					}else{
					echo $student["course_name"];
					} ?></td>
					<td><?php if($student["class_name"]==''){
				
					$class_id = $student['class_id'];
					echo $this->Common_model->getSinglefield('class_master','class_name','id='.$class_id);
					}else{
					echo $student["class_name"];
					} ?></td>
					<td>
						<a class="btn btn-sm btn-primary" href="<?=BASE_URL('admin/master/show_form/'.$student['student_id']);?>" target="_blank"><i class="fa fa-eye"></i></a>
						<!-- <a class="btn btn-sm btn-danger" href="<?=BASE_URL('admin/master/edit_student/'.$student['student_id']);?>" target="_blank"><i class="fa fa-pen"></i></a> -->
					</td>
                 
			</tr>
			<?php
			$i++; } }
			?>
			
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
			<?php $total = $total + $student["cnt"]; ?>
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