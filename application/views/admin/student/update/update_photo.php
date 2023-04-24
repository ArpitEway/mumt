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
				
			<div class="col-sm-8 row">
				<div class="col-md-12">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Enrollment No</label>
						<div class="col-sm-8 text-value">
							<?php echo $student['enrollment_no']; ?>
						</div>
					</div>
				</div>

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
						<label class="col-sm-4 text-heading">Student</label>
						<div class="col-sm-8 text-value">
							<?php echo $student['name']; ?>
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
			<div class="col-sm-4 row">
					
					<div class="image-input image-input-outline" id="kt_image_1">
						<?php if($student_detail->photo){ ?>

							<div class="image-input-wrapper" style="background-image: url('<?=base_url('assets/student_image/'.$student_detail->session.'/'.$student_detail->photo); ?>')"></div>
						
								<?php }else{ ?>

							<div class="image-input-wrapper" style="background-image: url(<?=base_url('assets/images/center/student.bmp')?>)"></div>
						
								<?php } ?>	
								<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
									<i class="fa fa-pen icon-sm text-muted"></i>
									<input type="file" name="photo" id="photo" accept=".png, .jpg, .jpeg"/>
								</label>
								<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Remove Image">
									<i class="ki ki-bold-close icon-xs text-muted"></i>
								</span>
								<div id="errPhoto" class="fv-plugins-message-container"></div>
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