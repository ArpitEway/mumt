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
					<th>DOB</th>
					<th>Course</th>
					<th>Class</th>
					
					<th>Mobile no</th>
					
					<th>Gender</th>
					<th>Category</th>
					
				
					<th>TC Generate</th>
					<?php
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
							
							<td><?php $newDate = ($student["dob"]=='') ? 'N/A' : $student["dob"]; echo date("d-m-Y", strtotime($newDate)); ?></td>
							<td><?php 
								echo $student["course_name"];
							?></td>
							<td><?php 
								echo $student["class_name"];
							 ?></td>
							
							<td><?php echo $this->Common_model->getMobileNoByStudentID($student["student_id"]) ?></td>
											
						
				
					<td><?php echo $student["gender"]; ?></td>
					<td><?php echo $student["category"]; ?></td>
					
				 
				   <td>
					   <?php if($student["tc_date"]){ echo $this->Common_model->viewDate($student["tc_date"]) ; } ?>
				  
				  
				   </td>   
						</tr>
					<?php
					$i++; 
				}
			}
		
			?>
			
			


		</tbody>
	</table>
</div>


