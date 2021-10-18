<div class="col-md-12">
    <div class="card card-custom bgi-no-repeat gutter-b card-stretch mt-3">
        <div class="card-body">
    <?php if ($this->session->flashdata('success')) { ?>
      <span id="alert-msg" class="aler alert-success p-2 alert-msg"><?php echo $this->session->flashdata('success') ?></span>
    <?php } ?>
<form class="form" method="post" action="<?=base_url('student/Student/enquirySubmit');?>">
				<div class="form-group row">
					<div class="col-lg-6">
						<label>Full Name:</label>
						<input type="hidden" name="student_id" id="student_id" value="<?=$this->session->student_id;?>" />
						<input type="text" name="name" id="name" class="form-control" placeholder="Enter full name" value="<?php echo set_value('name'); ?>" />
						<span id="errName" class="form-text text-danger"><?php echo form_error('name'); ?></span>
					</div>
					<div class="col-lg-6">
						<label>Father's/ Husband's Name:</label>
						<input type="text" name="f_h_name" id="f_h_name" class="form-control" placeholder="Enter full name" value="<?php echo set_value('f_h_name'); ?>" />
						<span id="errFHName" class="form-text text-danger"><?php echo form_error('f_h_name'); ?></span>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<label>E-mail:</label>
						<input type="email" name="email" id="email" value="<?php echo set_value('email'); ?>" class="form-control" placeholder="Enter Email Address"/>
						<span id="errEmail" class="form-text text-danger"><?php echo form_error('email'); ?></span>
					</div>
					<div class="col-lg-4">
						<label>Date Of Birth:</label>
						<input type="text" class="form-control" data-inputmask="'alias': 'dd-mm-yyyy'" name="dob" id="dob" placeholder="dd-mm-yyyy" value="<?php echo set_value('dob'); ?>"/>
						<span id="errDob" class="form-text text-danger"><?php echo form_error('dob'); ?></span>
					</div>
					<div class="col-lg-4">
						<label>Mobile:</label>
						<input type="number" id="mobile" name="mobile" class="form-control" placeholder="Enter your Mobile No" maxlength="10" value="<?php echo set_value('mobile'); ?>"/>
						<span id="errMob" class="form-text text-danger"></span>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-lg-6">
						<label>Course:</label>
						<select class="form-control" name="course" id="course">
							<option value="">Select Course</option>
								<?php foreach($course_group_list as $course){ 
											?>
											<option value="<?= $course['id']; ?>"><?= $course['course_name']; ?></option>
										<?php } ?>
						</select>
						<span id="errCourse" class="form-text text-danger"></span>
					</div>
					<div class="col-lg-6">
						<label>Class:</label>
						<select class="form-control" name="class_id" id="class_id">
							<option value="">Select class</option>
						</select>
						<span id="errClass" class="form-text text-danger"></span>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-lg-12">
						<button type="submit" id="submitEnquiry" class="btn btn-primary mr-2">Create</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$("#dob").inputmask();
</script>
<script src="<?=base_url()?>assets/theme/old_student_enquiry.js?token=<?=date('YmdHis')?>"></script>