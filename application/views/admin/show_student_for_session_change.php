<div class="container-fluid">
	 <div class="row mt-5 ">
		
		<div class="form-group col-md-3 mx-auto">
			<label for="class">Session</label>
			<select name="session" id="session" class="form-control" >
				<option>Select Session</option>
				<?php 
				foreach($sessions as $session)
				{
					?>
					<option value="<?php echo $session['id']; ?>" <?php if($session==$listSession) echo "selected"; ?> ><?php echo $session['session']; ?></option>
					<?php
				} 
				?>		
			</select>
		
       
            <button class="btn btn-md btn-primary mt-4" type="button" id="submit_btn">Update Session</button>
        </div>        
	

	
	
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
					
                    <th>Session</th>
					<th>Name</th>
					
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
					
					<th>Center Code</th>
					<th>University Mode</th>
					<?php
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
                              							
							
                            <td><?php echo $student["session"]; ?></td>
							<td><?php echo $student["name"] ; ?></td>
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
					//$i++; 
				}
			}
		?>
		
    
			


		</tbody>
	</table>
</div>
<form action="<?php echo site_url('admin/admins/update_student_session'); ?>" method="POST">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <input type="hidden" name="session" id="newSession">
	<input type="hidden" name="approved" value="<?php echo $approved; ?>">
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