<style type="text/css">
	
#kt_image_1.image-input .image-input-wrapper {
    background-size: contain;
}
</style>
<form class="p-5">
	<h4 class="my-5 font-weight-bold text-dark">Educational Details</h4>
	<input type="hidden" id="center_id"  name="center_id" value="<?=$this->session->center_id?>">
<?php if( $this->session->center_id==100) { ?>
	<div class="row">
	<div class="form-group col-md-9">
			<label for="center_id">Center</label>
			<select name="forCenter" id="forCenter" class="form-control "  required >
				<option value="">Select Center</option>
				<?php 
				$centers = $this->db->get_where('center', array())->result_array();
				foreach($centers as $center)
				{
					?>
					<option value="<?php echo $center['id']; ?>"><?php echo $center['center_code'] ." - ". $center['center_name']; ?></option>

					<?php
				} 
				?> 
			</select>
			<div class="fv-plugins-message-container"></div>
		</div>
	</div>
	<?php } ?>
	<div class="row">
		<div class="col-md-9">
			<div class="row">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">   
    <input type="hidden"  id="mode" name="mode" value="<?= $mode; ?>">   
				<div class="col-md-6">
					<div class="form-group ">
						<label>Session</label><span class="text-danger"> *</span>
						<select name="session" required id="session" class="form-control" >
							<option value="">Select Session</option>
							<?php
							foreach ($sessions as $session) {
								?>
								<option><?=$session['session'] ?></option>
								<?php
							}
							?>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-6">
					<!--begin::Input-->
					<div class="form-group ">
						<label>Eligibility</label>
						<select name="eligibility" id="eligibility" class="form-control" >
							<option value="">--Select--</option>
							<?php foreach ($eligibility_list as $row) { ?>
								<option value="<?php echo  $row['eligibility']?>"><?=$row['eligibility']?></option>
							<?php } ?>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Course</label><span class="text-danger">*</span>
							<input type="hidden" id="mode" value="<?php echo $mode ;  ?>">
						<select name="course_group_id" id="course_group_id" class="form-control " >
							<option value="">--Select--</option>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
				<div class="col-md-6">
					<!--begin::Input-->
					<div class="form-group ">
						<label>Class</label><span class="text-danger"> *</span>
						<select name="class_id" id="class_id" class="form-control ">
							<option value="">Select Class</option>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-6 d-none" id="additional_course_div">
					<h6 class="my-5 text-dark"><input type="checkbox" id="additional_course"> &nbsp;Additional Course</h6>
				</div>
				<div id="additional_course_details" class="w-100 d-none ">
					<div class="col-md-6">
					<div class="form-group">
						<label>Course</label><span class="text-danger">*</span>
							<input type="hidden" id="additional_mode" value="<?php echo $mode ;  ?>">
						<select name="additional_course_group_id" id="additional_course_group_id" class="form-control " >
							<option value="">--Select--</option>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
				<div class="col-md-6">
					<!--begin::Input-->
					<div class="form-group ">
						<label>Class</label><span class="text-danger"> *</span>
						<select name="additional_class_id" id="additional_class_id" class="form-control ">
							<option value="">Select Class</option>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				</div>
			</div>
		</div>
		
		<div class="col-xl-3 text-center">
			<div class="image-input image-input-outline" id="kt_image_1">
				<div class="image-input-wrapper" style="background-image: url(<?=base_url('assets/images/center/student.bmp')?>)"></div>

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
	<h4 class="my-5 font-weight-bold text-dark">Personal Details</h4>
	<div class="row">
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Student Name:</label><span class="text-danger"> *</span>
				<input type="text" class="form-control " name="name" id="name" value="" placeholder="Student Name">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Father / Husband Name:</label><span class="text-danger"> *</span>
				<input type="text" class="form-control " name="f_h_name" id="f_h_name" placeholder="Father / Husband Name">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Mother's Name</label><span class="text-danger"> *</span>
				<input type="text" class="form-control " name="mother_name" id="mother_name" placeholder="Mother's Name">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-form-label">Gender</label>
				<div class="col-form-label">
					<div class="radio-inline">
						<label class="radio radio-success">
							<input type="radio" name="gender" value="Male" >
							<span></span>Male
						</label>
						<label class="radio radio-success">
							<input type="radio" name="gender" value="Female" checked="checked">
							<span></span>Female
						</label>
					</div>
					<div class="text-muted"></div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-form-label">Medium</label>
				<div class="col-form-label">
					<div class="radio-inline">
						<label class="radio radio-success">
							<input type="radio" name="medium" value="Hindi" checked="checked">
							<span></span>Hindi
						</label>
						<label class="radio radio-success">
							<input type="radio" name="medium" value="English">
							<span></span>English
						</label>
					</div>
					<div class="text-muted"></div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-form-label">Marital Status</label>
				<div class="col-form-label">
					<div class="radio-inline">
						<label class="radio radio-success">
							<input type="radio" name="marital_status" value="Married" >
							<span></span>Married
						</label>
						<label class="radio radio-success">
							<input type="radio" name="marital_status" value="Unmarried" checked="checked">
							<span></span>Unmarried
						</label>
					</div>
					<div class="text-muted"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Mobile No :</label><span class="text-danger"> *</span>
				<input type="text"  class="form-control " name="p_mobile_no" id="p_mobile_no" placeholder="Mobile">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Email :</label><span class="text-danger"> *</span>
				<input type="text" class="form-control " name="p_email" id="p_email" placeholder="E-mail">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>

		<div class="col-xl-4">
			<div class="form-group ">
				<label>Date Of Birth</label><span class="text-danger"> *</span>
				<input type="text"  class="form-control " data-inputmask="'alias': 'dd-mm-yyyy'" name="dob" id="dob" placeholder="dd-mm-yyyy">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group ">
				<label>Nationality:</label><span class="text-danger"> *</span>
				<select name="nationality" class="form-control " id="nationality">
					<option value="">Select Nationality</option>
					<option value="INDIAN">Indian</option>
					<option value="OTHER">Other </option>
				</select>
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group ">
				<label>Religion:</label><span class="text-danger"> *</span>
				<select name="religion" class="form-control " id="religion">
					<option value="">Select Religion</option>
					<option value="Hindu">Hindu</option>
					<option value="Muslim">Muslim </option>
					<option value="Sikh">Sikh </option>
					<option value="Christian">Christian</option>
				</select>
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group ">
				<label>Category:</label><span class="text-danger"> *</span>
				<select name="category" class="form-control " id="category">
					<option value="">Select Category</option>
					<option>General</option>
					<option>OBC</option>
					<option>ST</option>
					<option>SC</option>
				</select>
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-form-label">Minority</label>
				<div class="col-form-label">
					<div class="radio-inline">
						<label class="radio radio-success">
							<input type="radio" name="minority" value="Y" >
							<span></span>Yes
						</label>
						<label class="radio radio-success">
							<input type="radio" name="minority" value="N" checked="checked">
							<span></span>No
						</label>
					</div>
					<div class="text-muted"></div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-form-label">Person with Disability</label>
				<div class="col-form-label">
					<div class="radio-inline">
						<label class="radio radio-success">
							<input type="radio" name="handicapped" value="Y" >
							<span></span>Yes
						</label>
						<label class="radio radio-success">
							<input type="radio" name="handicapped" value="N" checked="checked">
							<span></span>No
						</label>
					</div>
					<div class="text-muted"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Aadhar Number:</label>
				<input type="text" class="form-control " name="adhar_no"  placeholder="Aadhar Number" id="adhar_no">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
	</div>
	<h4 class="my-5 font-weight-bold text-dark">Current Address of Student</h4>
	<div class="row">
		<div class="col-xl-12">
			<!--begin::Input-->
			<div class="form-group ">
				<label>Address</label><span class="text-danger"> *</span>
				<input type="text" class="form-control " name="c_address" id="c_address" />
				<div class="fv-plugins-message-container"></div></div>
				<!--end::Input-->
			</div>
			<div class="col-xl-3">
				<!--begin::Input-->
				<div class="form-group">
					<label>State</label><span class="text-danger"> *</span>
					<select class="form-control  state" name="c_state" id="c_state" data-district="c_district">
						<option value='' >Select State</option>
						<?php 
						foreach($state_list as $state){ ?>
							<option><?=$state['name']?></option>
						<?php } ?>
					</select>
					<div class="fv-plugins-message-container"></div>
				</div>
				<!--end::Input-->
			</div>
			<div class="col-xl-3">
				<!--begin::Input-->
				<div class="form-group">
					<label>District</label><span class="text-danger"> *</span>
					<select class="form-control  district" name="c_district" id="c_district">
						<option value='' >Select District</option>

					</select>
					<div class="fv-plugins-message-container"></div>
				</div>
				<!--end::Input-->
			</div>
			<div class="col-xl-3">
				<!--begin::Input-->
				<div class="form-group">
					<label>City</label><span class="text-danger"> *</span>
					<input class="form-control " name="c_city" id="c_city">
					<div class="fv-plugins-message-container"></div>
				</div>
				<!--end::Input-->
			</div>
			<div class="col-xl-3">
				<!--begin::Input-->
				<div class="form-group">
					<label>Pin Code</label><span class="text-danger"> *</span>
					<input class="form-control " name="c_pin_code" id="c_pin_code" type="number">
					<div class="fv-plugins-message-container"></div>
				</div>
				<!--end::Input-->
			</div>
		</div>
		<h4 class="my-5 font-weight-bold text-dark">Permanent Address of Student: Same as current Address <input type="checkbox" id="copyAddress"></h4>
		<div class="row">
			<div class="col-xl-12">
				<!--begin::Input-->
				<div class="form-group ">
					<label>Address</label><span class="text-danger"> *</span>
					<input type="text" class="form-control " name="p_address" id="p_address"/>
					<div class="fv-plugins-message-container"></div></div>
					<!--end::Input-->
				</div>
				<div class="col-xl-3">
					<!--begin::Input-->
					<div class="form-group">
						<label>State</label><span class="text-danger"> *</span>
						<select class="form-control  state" name="p_state" id="p_state" data-district="p_district">
							<option value='' >Select State</option>
							<?php 
							foreach($state_list as $state){ ?>
								<option ><?=$state['name']?></option>
							<?php } ?>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
				<div class="col-xl-3">
					<!--begin::Input-->
					<div class="form-group">
						<label>District</label><span class="text-danger"> *</span>
						<select class="form-control  district" name="p_district" id="p_district">
							<option value='' >Select District</option>
							<?php foreach ($district_list as $district) { ?>
								<option><?=$district['name']?></option>
							<?php } ?>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
				<div class="col-xl-3">
					<!--begin::Input-->
					<div class="form-group">
						<label>City</label><span class="text-danger"> *</span>
						<input class="form-control " name="p_city" id="p_city">
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
				<div class="col-xl-3">
					<!--begin::Input-->
					<div class="form-group">
						<label>Pin Code</label><span class="text-danger"> *</span>
						<input class="form-control " name="p_pin_code" id="p_pin_code" type="number">
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
			</div>

			<h4 class="my-5 font-weight-bold text-dark">Particulars of the qualifying examination:</h4>
			<div class="row  p-2">
				<div class="col-md-3">Qualifying Exam</div>
				<div class="col-md-3">Board/University Name</div>
				<div class="col-md-2">Year of Passing</div>
				<div class="col-md-2">Maximum Marks</div>
				<div class="col-md-2">Obtain Marks</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<input name="qualifying_exam" readonly="readonly" placeholder='Qualifying Exam'  class="form-control " type="text" />
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<input name="board"  placeholder="विश्वविद्यालय/शिक्षामण्डल" class="form-control " type="text" />
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="passing_year">
							<option value="">---Select---</option>
							
							<?php for ($year=$passing_exam_years; $year > 1990; $year--) { ?>
								<option><?=$year?></option>
							<?php } ?>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<input name="total_marks" placeholder='Total Marks' class="form-control" type="text" />
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<input name="marks" placeholder="Marks" class="form-control" type="text" />
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<input type="button" name="submit" id="submit" class="btn btn-primary m-auto" value="submit">
			</div>
		</div>
	</form>

	<script type="text/javascript">
		$("#dob").inputmask();
		
		var avatar1 = new KTImageInput('kt_image_1');
		setTimeout(function () { document.getElementById("alert-msg").style.display="none"; }, 3000);
	</script>
	<script src="<?=base_url();?>assets/theme/admission.js?token=<?=date('Ymd')?>"></script>