<style>

.chk{
	width: 20px;
	height: 20px;	
}
.chk_label{
	margin-left: 10px;
    margin-top: 4px;
    font-size: 15px;	
}
</style> 
<form method="POST" class="d-block ajaxForm" enctype="multipart/form-data" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<input type="hidden" class="student_id_model" name="student_id" value="<?php echo  $param1 ?>">
<div class="text-justify row justify-content-around" >
<?php

$student = $courses = $this->db->get_where('student', array("student_id" => $param1))->row_array();
$course_group_id = $student['course_group_id'];




?>
<input type="hidden" name="session" id="session" value="<?=$student['session']?>">
<!-- <div class="row">
Form Number <b><?=$student['student_id']?></b>  Name <b><?=$student['name']?></b>
</div> -->
<div class="container-fluid profile mt-5">
		
		<div class="row">
			<div class="col-sm-12 row">
				
			<div class="col-sm-7 row">
				<div class="col-md-12">
					<div class="row py-2">
						<label class="col-sm-5 text-heading">Enrollment No</label>
						<div class="col-sm-7 text-value">
							<?php echo $student['enrollment_no']; ?>
						</div>
					</div>
				</div>

				
				<div class="col-md-12">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Student</label>
						<div class="col-sm-8 text-value">
							<?php echo $student['name']; ?>
						</div>
					</div>
				</div>
			
			</div>	
			<div class="col-sm-5 row">
					<div class="col-md-12">
							<div class="row py-2">
								<label class="col-sm-4 text-heading">Form No</label>
								<div class="col-sm-8 text-value">
									<?php echo $student['student_id']; ?>
								</div>
							</div>
						</div>
						<div class="col-md-12">
					<div class="row py-2">
							<label class="col-sm-4 text-heading">Father</label>
							<div class="col-sm-8 text-value">
								<?php echo $student['f_h_name']; ?>
							</div>
						</div>
					</div>
					
						
			</div>
	</div>	
	<div class="col-sm-12 row">	
			<div class="form-group col-sm-12">
				<label for="example-date-input" class="col-6 col-form-label">Issue Date</label>
				<div class="col-7">
				<input class="form-control" type="date" name="payment_date" min="<?= date('Y-m-d', strtotime('-6 month')); ?>" max="<?= date('Y-m-d'); ?>"  id="tc_date"  placeholder="dd-mm-yyyy"/>
				<div class="text-danger" id="error"></div>
				<input type="hidden" value="" name="student_id" id="student_id">
				</div>
			</div>
			<div class="form-group  col-sm-12">
					<label class="col-6 col-form-label">Remark detail</label>
					<div>
					<textarea class="form-control remark_detail" style="margin-left: 10px;"  placeholder="Remark detail" id="kt_autosize_2" rows="4" name="remark_detail"  ></textarea>
					</div>
			</div>
	</div>	



<div class=" text-center" style="margin:auto;">
	<button class="btn btn-lg btn-primary" id="remark_submit" type="button">Submit</button>
</div>



</form>

<script type="text/javascript">
	var avatar1 = new KTImageInput('kt_image_1');
</script>
