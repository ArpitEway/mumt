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
					<th>
					<?php if($this->session->account_type == "Admins" || $this->session->account_type == "Enrollment"){ ?>	
					Edit	
					<?php } ?>
					</th>
					<th>Mobile no</th>
					<th>Payment</th>
					<th>Document Uploaded</th>
					<th>Approved</th>
					<th>Enrolled</th>
					<th>Exam Form</th>
					<th>Roll No.</th>
					<th>Category</th>
					<th>Paper</th>
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
							<td>
							<?php if(($this->session->account_type == "Admins" || $this->session->account_type == "Enrollment") && $student['enrolled']!='Y'){ ?> 
								
								<a target="_blank"  href='<?php echo base_url($this->session->account_type.'/editForm/').$this->Common_model->encrypt_decrypt($student["student_id"],'encrypt'); ?>'><i class="fa fa-pen"></i></a> 
								
							<?php } ?></td>
							<td><?php echo $this->Common_model->getMobileNoByStudentID($student["student_id"]) ?></td>
							<td><?php if( $student["payment_status"]=='Y'){echo 'Paid' ;}else{echo 'Unpaid' ;} ?></td>
							<td><?php if( $student["document_uploaded"]=='Y'){echo 'Uploaded' ;}else{echo 'Not Uploaded' ;} ?></td>

							<td><?php if( $student["approved"]=='Y'){echo 'Approved' ;}else if($student["approved"]=='N'){echo 'Non Approved' ;}else{echo 'Non Verified';} ?></td>

							<td><?php if( $student["enrolled"]=='Y'){echo 'Enrolled' ;}else{echo 'Non Enrolled' ;} ?></td>

							<td><?php if( $student["new_exam_form"]=='Y'){echo 'Submit' ;}else if($student["new_exam_form"]=='D'){echo 'Not Permitted' ;}else{echo 'Not Submitted';} ; ?></td>					
						<?php $student_id = $this->Common_model->encrypt_decrypt($student['student_id']); ?>
					</td>
					<td><?php echo $student["roll_no"]; ?></td>
					<td><?php echo $student["category"]; ?></td>
					<td>
						<?php if($student["temp_exam_form"]=='Y'){ ?>
						<a target="_blank"  class="" href="<?=base_url('show_paper/'.$student_id);?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
					 	<?php if($this->session->account_type =="Admins"){?>
					  <button  class="btn btn-sm  " onclick="delete__student_paper(this)" data-id = "<?=$student['student_id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>   
					 <?php }
					 }else{ ?>
							<a target="_blank"  class="" href="<?=base_url('select_paper/'.$student_id);?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
							<?php } ?>
				   </td>
						</tr>
					<?php
					$i++; 
				}
			}
		
			if(isset($course_count)){
				$total = 0;
				foreach($course_count as $student){	
				
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


 <script type="text/javascript">
function delete__student_paper(student_id)
    {
    if (confirm('Are you sure to remove  ?')) {
   var csrfName = $('.csrfname').attr('name');
   var csrfHash = $('.csrfname').val(); 
     var student_id = $(student_id).attr('data-id');
    // alert(student_id);
$.ajax({
	type: "POST",
	url: BASE_URL+"admin/Admins/student_paper_delete",
	dataType:"json",
	  data: {student_id: student_id,[csrfName]:csrfHash},
	success: function(response){
	console.log(response);
if(response.status=='true'){
toastr.success("successfully Deleted all paper");
}

else{
	toastr.error("Something wrong");
	}
}
});	
}
}
</script> 
