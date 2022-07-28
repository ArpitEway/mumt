<div class="container-fluid">
	 <div class="row mt-5">
		
		<div class="form-group col-md-3">
			<label for="class">Session</label>
			<select name="session" id="session" class="form-control" >
				<option></option>
				<?php 
				foreach($sessions as $session)
				{
					?>
					<option value="<?php echo $session['id']; ?>" <?php if($session==$listSession) echo "selected"; ?> ><?php echo $session['session']; ?></option>
					<?php
				} 
				?>		
			</select>
		</div>
        <div class="form-group col-md-3" style="margin-top:13px;">
       
            <button class="btn btn-md btn-primary mt-4" type="button" id="submit_btn">Update Session</button>
        </div>        
	<!--

		<div class="form-group col-md-3">
			<label for="class">Payment</label>
			<select name="payment" id="payment" class="form-control"  > 
				<option value="all">All</option>
				<option value="Y">Paid</option>
				<option value="N">Unpaid</option>

			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="class">Admission document</label>
			<select name="document_upload" id="document_upload" class="form-control"  > 
				<option value="all">All</option>
				<option value="Y">Uploaded</option>
				<option value="N">Not uploaded </option>

			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="class">Approved Status</label>
			<select name="approved" id="approved" class="form-control"  > 
				<!-- <option value="all">All</option> -->
				<!-- <option value=""> Non-verified </option> -->
				<!-- <option value="Y">Approved </option>
				<option value="N">Non-Approved </option> -->
			<!-- </select> 
		</div> -->

	
	
	</div>

	<div class="form-group text-center">
		
	</div>
	<div align="center" id="myLoader" class="loader_div" style="display: none;" >
		<svg>
			<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
		</svg>
	</div>
	<div id="dt">
<!-- Start Table -->
<div class="dt-responsive">
	<table id="kt_datatable" class="table table-striped">
		<thead>
			<tr>
				<?php if(isset($students)){ ?>					
					<th>Sno</th>
					<th>Form no</th>
					<th>Enrollment no</th>
                    <th>Session</th>
					<th>Name</th>
					<th>F/H Name</th>
					<th>Mobile no</th>
					<th>DOB</th>
					<th>Course</th>
					<th>Class</th>
					<th>
						<?php if($this->session->account_type == "Admins" || $this->session->account_type == "Enrollment"){ ?>	
							Edit
						<?php } ?>
					</th>
					<th>Payment</th>
					<th>Document Uploaded</th>
					<th>Approved</th>
					<th>Enrolled</th>
					<th>Exam Form</th>
					<th>Center Code</th>
					<th>University Mode</th>
					<?php
					}
					if(isset($course_count)){
						if($_POST['count_filter']=='course_group_id'){
							?>
						<th>Sno</th>
						<th>Course</th>
						<th>Count</th>
					<?php }else if($_POST['count_filter']=='center_id'){  ?>
						<th>Sno</th>
						<th>Center</th>
						<th>Center Code</th>
	
						<th>Count</th> 
						  <?php	}else{   ?>
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
                    $st=array();$i=0;
					foreach($students as $student){
						
						$st[$i]=$student['student_id'];
						$i++;
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><a target="_blank" href="<?php echo site_url('admin/'.$this->session->account_type.'/show_form/'.$this->Common_model->encrypt_decrypt($student['student_id'],'encrypt')); ?>"> <?=$student["student_id"]?></a></td>
                              							
							<td><?php echo $student["enrollment_no"]; ?></td>
                            <td><?php echo $student["session"]; ?></td>
							<td><?php echo $student["name"] ; ?></td>
							<td><?php echo $student["f_h_name"]; ?></td>
							<td><?php echo $this->Common_model->getMobileNoByStudentID($student["student_id"]) ?></td>
							<td><?php $newDate = ($student["dob"]=='') ? 'N/A' : $student["dob"]; echo date("d-m-Y", strtotime($newDate)); ?></td>
							<td><?php 
								echo $student["course_name"];
							?></td>
							<td><?php 
								echo $student["class_name"];
							 ?></td>
							<td>
							<?php if(($this->session->account_type == "Admins" || $this->session->account_type == "Enrollment") && $student['approved']!='Y'){ ?> 
								<a target="_blank"  href='<?php echo base_url($this->session->account_type.'/editForm/').$this->Common_model->encrypt_decrypt($student["student_id"],'encrypt'); ?>'><i class="fa fa-pen"></i></a> 
							<?php } ?></td>
							<td><?php if( $student["payment_status"]=='Y'){echo 'Paid' ;}else{echo 'Unpaid' ;} ?></td>
							<td><?php if( $student["document_uploaded"]=='Y'){echo 'Uploaded' ;}else{echo 'Not Uploaded' ;} ?></td>

							<td><?php if( $student["approved"]=='Y'){echo 'Approved' ;}else if($student["approved"]=='N'){echo 'Non Approved' ;}else{echo 'Non Verified';} ?></td>

							<td><?php if( $student["enrolled"]=='Y'){echo 'Enrolled' ;}else{echo 'Non Enrolled' ;} ?></td>

							<td><?php if( $student["new_exam_form"]=='Y'){echo 'Submit' ;}else if($student["new_exam_form"]=='D'){echo 'Not Permitted' ;}else{echo 'Not Submitted';} ; ?></td>
							
							<td><?php echo $student["center_code"]; ?></td> 
							<td><?php 
							if($student["university_mode"]=="REG"){
								echo "Regular";
							}else{
								echo "Private";
							}
							?></td> 
							
						</tr>
					<?php
					$i++; 
				}
			}
		?>
		
    
			<?php	
			if(isset($course_count)){ ?>
		    
			<?php
			
			$total = 0;
			foreach($course_count as $student){	
				
				//echo"<pre>";
				
			
		   $class = $this->db->get_where("class_master",array('id'=>$student['class_id']))->result_array();
		   $course_group_id = $class[0]['course_group_id'];
		   $course = $this->db->get_where("course_group",array('id'=>$course_group_id))->result_array();
	       
			?>
			<tr>
				
			<td><?php echo $i; ?></td>
			<td><?php 	if($_POST['count_filter']=='course_group_id'){ echo $this->Common_model->getCourseNameByCourseId($student["course_group_id"]);}else if($_POST['count_filter']=='center_id'){echo $this->Common_model->getCenterNameById($student["center_id"]);}else{echo $course[0]['course_name'] ;} ; ?></td>
	     	<?php if($_POST['count_filter']=='center_id'){ ?>	<td><?php 	 echo $this->Common_model->getCenterCodeById($student["center_id"]); ; ?></td><?php } ?>
	     	<?php if($_POST['count_filter']=='class_id'){ ?>	<td><?php echo $class[0]['class_name'] ; ?></td><?php } ?>

			<td><?php echo $student["cnt"]; ?></td>
			<?php $total = $total + $student["cnt"];?>
			</tr>
			
			
			
			<?php $i++; } ?>
			<tfoot>
			<tr>
			<td></td>
			<td><?php echo "Total"; ?></td>
			<?php  if($_POST['count_filter']=='class_id' ||$_POST['count_filter']=='center_id'  ) { ?>  <td></td><?php } ; ?>

			<td><?php echo $total ?></td>
			</tr>
			<tfoot>
			<?php } ?>


		</tbody>
	</table>
</div>
<form action="<?php echo site_url('admin/admins/update_student_session'); ?>" method="POST">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <input type="hidden" name="session" id="newSession">
    <input type="hidden" name="students" value="<?php echo  implode(',',$st);?>">
    <input type="submit" style="display:none" name="submit" value="submit" id="submitForm">
 </form>
<!-- End Table -->

	</div>
</div>
<script>

	$(document).on("click","#submit_btn",function(){
	//	$('#dt').hide();
     var sess=   $("#session").val();
     $("#newSession").val(sess);
     if(sess!=""){
        if (window.confirm('Are you sure？')) {
        $("#submitForm").click();
        }
     }
     else{
         alert("Please select Session to Update the old !");
     }
    

		
	});
	
</script>