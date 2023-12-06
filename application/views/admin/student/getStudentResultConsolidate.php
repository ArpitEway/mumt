<div class="dt-responsive">
	<table id="kt_datatable" class="table table-striped">
		<thead>
			<tr>
				<?php if(isset($students)){ ?>					
					<th>Sno</th>
					<th>Center Code</th>
					<th>University Mode</th>
					<th>Session</th>
					<th>Form no</th>
					<th>Enrollment no</th>
					<th>Name</th>
					<th>F/H Name</th>
					
					<th>Course</th>
					<th>Class</th>
					
					<th>Division</th>
					
					<th>(%)</th>
					<th>Mobile no</th>
					<th>Roll No.</th>
					<th>Exam Center Code</th>
					<th>Gender</th>
					<th>Category</th>
					<th>Center Name</th>	
					<th>Aadhaar No.</th>
					
					<?php if($this->session->account_type =="Enrollment"){?>
					<th>TC Generate</th><?php	}?>
					<?php
					}
					if(isset($course_count) ){
						if($_POST['count_filter']=='all'){
							?>
						<th>Sno</th>
						<th>Course</th>
						<th>Class</th>
						<th>First</th>
						<th>Second</th>
						<th>Third</th>
						
					<?php }else{   ?>
						 <th>Sno</th>
						 <th>Course</th>
						 <th>Class</th>
						 <th>Count</th>
						<?php 
						  }
						}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 1;
				if(isset($students)){
					foreach($students as $student){
						?>
					<tr data-id="tr_<?= $student['student_id']?>" id="<?= $student['student_id'];?>">
							<td><?php echo $i; ?></td>
							<td><?php echo $student["center_code"]; ?></td> 
							<td><?php 
							if($student["university_mode"]=="REG"){
								echo "Regular";
							}else{
								echo "Private";
							}
							?></td> 
							<td><?php echo $student["session"]; ?></td> 
							<td><a target="_blank" href="<?php echo site_url('admin/'.$this->session->account_type.'/show_form/'.$this->Common_model->encrypt_decrypt($student['student_id'],'encrypt')); ?>"> <?=$student["student_id"]?></a></td>
                              							
							<td><?php echo $student["enrollment_no"]; ?></td>
							<td><?php echo $student["name"] ; ?></td>
							<td><?php echo $student["f_h_name"]; ?></td>
							
							
							<td><?php 
								echo $student["course_name"];
							?></td>
							<td><?php 
								echo $student["class_name"];
							 ?></td>
							<td><?php echo $student["div"]; ?></td>	
							<td><?php echo $student["per"]; ?></td>	
							<td><?php echo $this->Common_model->getMobileNoByStudentID($student["student_id"]) ?></td>
							

											
						<?php $student_id = $this->Common_model->encrypt_decrypt($student['student_id']); ?>
					</td>
					<td><?php echo $student["roll_no"]; ?></td>
					<td><?php echo $student["examcentercode"]; ?></td>
					<td><?php echo $student["gender"]; ?></td>
					<td><?php echo $student["category"]; ?></td>
					<td><?php echo $student["center_name"]; ?></td>
				   <td><?php echo $student["adhar_no"]; ?></td>
					
				 <?php if($this->session->account_type =="Enrollment"){?>
				   <td>
					   <?php if($student["tc_date"]){ echo $this->Common_model->viewDate($student["tc_date"]) ; } else{ ?>
				   <a href="javascript:void(0);" style="margin:5px;" class="btn btn-success" id="<?php echo  $std  ?>"   onclick="rightModal('<?php echo site_url('admin/modal/student_popup/admin/student/update/tc_generate/'.$student_id); ?>', '<?php echo 'Fill TC Detail' ?>')">TC</a>
				   <?php } ?>
				   </td>    <?php } ?>
						</tr>
					<?php
					$i++; 
				}
			}
		
			if(isset($course_count) && isset($course_count["course_name"])){
				$total = 0;
				
			?>
			<tr>
				
			<td><?php echo $i; ?></td>
			<td><?php 	 echo $course_count["course_name"]; ?></td>
	     	<td><?php 	 echo $course_count["class_name"]; ?>
	     	
			<?php if($_POST['count_filter']=='all'){ ?>
			<td><?php echo $course_count["first"]; ?></td>
			<td><?php echo $course_count["second"]; ?></td>
			<td><?php echo $course_count["third"]; ?></td>	
			<?php }else{ ?>
				<td><?php 
				echo $course_count[$_POST['count_filter']];
			} ?>		
			</tr>
			
			
			
			<?php $i++;  ?>
			
			<?php } ?>


		</tbody>
	</table>
</div>


