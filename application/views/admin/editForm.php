<form class="p-5">
	<h4 class="my-5 font-weight-bold text-dark">Educational Details</h4>
	<div class="row">
		<div class="col-md-9">
			<div class="row">
                <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">   
                <div class="col-md-6">

                	<?php if($this->session->account_type=='Admins')
                	{
                	$sessions = $this->db->get_where('session', array())->result_array();
                		?>
                		<div class="form-group ">
                			<label>Session</label><span class="text-danger"> *</span>

                			<select class="form-control" name="session" id="session">
       
                				<?php foreach ($sessions as $session) {
                					$selected = ($session['session']==$student_detail->session) ? 'selected' : '';
                					?>
                					<option <?=$selected?>><?php echo $session['session']; ?></option> 
                					<?php 
                				} ?>
                			</select>
					</div>
             <?php } else { ?>
					<div class="form-group ">
						<label>Session</label><span class="text-danger"> *</span>
						<input type="text" readonly="readonly" class="form-control " name="session" id="session" placeholder="session" value="<?=$student_detail->session?>" >
						<div class="fv-plugins-message-container"></div>
					</div>
				<?php } ?>
				</div>
				<div class="col-md-6">
					<!--begin::Input-->
					<div class="form-group ">
						<label>Eligibility</label>
						<select name="eligibility" id="eligibility" class="form-control" >
							<option value="">--Select--</option>
							<?php foreach ($eligibility_list as $row) { ?>
								<option <?php if($student_data->eligibility == $row['eligibility'] ) {echo "selected";} ?> ><?=$row['eligibility']?></option>
							<?php } ?>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					<input type="hidden" name="old_course_group_id" id="old_course_group_id"  value="<?=$student_detail->course_group_id;?>">
					<input type="hidden" name="mode" id="mode"  value="<?=$student_detail->university_mode;?>">
					<label>Course</label><span class="text-danger"> *</span>
					<select name="course_group_id" id="course_group_id_admission" class="form-control " >
					<option value="" >--Select--</option>
                    <?php
                   
					$eligibility=$student_data->eligibility;  
				   $this->db->select('course_group.id,course.course_name');
				   $this->db->from('course');
				   $this->db->join('course_group', 'course_group.id = course.course_group_id'); 
				   $this->db->where('eligibility',$eligibility);
				   $this->db->where('course.session',$student_detail->session);
				   if($student_detail->university_mode=='REG' ){
					  
					   $this->db->where(" (admission_permission_regular = 'Y' or course_group.id=$student_detail->course_group_id) ");
					 }else{
					
						$this->db->where(" (admission_permission_private = 'Y' or course_group.id=$student_detail->course_group_id) ");
					 }
				   
				    $query = $this->db->get();
				    $course_group_list= $query->result_array();
                    foreach ($course_group_list as $row) { ?>
                                <option value="<?=$row['id'];?>" <?php if($student_detail->course_group_id == $row['id']){ echo "selected";} ?>><?=$row['course_name'];?></option>
					<?php } ?>

						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
				<div class="col-md-6">
					<!--begin::Input-->
					<div class="form-group ">
						<label>Class</label><span class="text-danger"> *</span>
						<select name="class_id" id="class_id" class="form-control " >
							<option value="">Select Class</option>
                            <?php 
                            
                            $class_list = $this->Common_model->get_record('class_master','*',"id='".$student_detail->class_id."'  ");
                            foreach ($class_list as $row) { ?>
                                <option value="<?=$row['id'];?>" <?php if($student_detail->class_id == $row['id']){ echo "selected";} ?>><?=$row['class_name'];?></option>
					        <?php } ?>
                            
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 text-center">
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
	<h4 class="my-5 font-weight-bold text-dark">Persnal Details</h4>
	<div class="row">
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Student Name:</label><span class="text-danger"> *</span>
				<input type="text" class="form-control " name="name" value="<?=$student_detail->name;?>" placeholder="Student Name">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Father / Husband Name:</label><span class="text-danger"> *</span>
				<input type="text" class="form-control" name="f_h_name" value="<?=$student_detail->f_h_name;?>" placeholder="Father / Husband Name">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Mother's Name</label><span class="text-danger"> *</span>
				<input type="text" class="form-control " name="mother_name" value="<?=$student_detail->mother_name;?>" placeholder="Mother's Name">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-form-label">Gender</label>
				<div class="col-form-label">
					<div class="radio-inline">
						<label class="radio radio-success">
							<input type="radio" name="gender" value="Male"  <?php if($student_detail->gender == 'Male'){ echo "checked"; } ?> >
							<span></span>Male
						</label>
						<label class="radio radio-success">
							<input type="radio" name="gender" value="Female"  <?php if($student_detail->gender == 'Female'){ echo "checked"; } ?>>
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
							<input type="radio" name="medium" value="Hindi"  <?php if($student_detail->medium == 'Hindi'){ echo "checked"; } ?>>
							<span></span>Hindi
						</label>
						<label class="radio radio-success">
							<input type="radio" name="medium" value="English" <?php if($student_detail->medium == 'English'){ echo "checked"; } ?>>
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
							<input type="radio" name="marital_status" value="Married" <?php if($student_data->marital_status == 'Married'){ echo "checked"; } ?> >
							<span></span>Married
						</label>
						<label class="radio radio-success">
							<input type="radio" name="marital_status" value="Unmarried"  <?php if($student_data->marital_status == 'Unmarried'){ echo "checked"; } ?>>
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
				<input type="text"  class="form-control " name="p_mobile_no" placeholder="Mobile" value="<?= $student_data->p_mobile_no; ?>">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-xl-4">
			<div class="form-group ">
				<label>Email :</label>
				<input type="text" class="form-control " name="p_email" placeholder="E-mail" value="<?= $student_data->p_email; ?>">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>

		<div class="col-xl-4">
			<div class="form-group ">
				<label>Date Of Birth</label><span class="text-danger"> *</span>
				<input type="text"  class="form-control " data-inputmask="'alias': 'dd-mm-yyyy'" name="dob" id="dob"placeholder="dd-mm-yyyy" value="<?= $this->Common_model->viewDate($student_detail->dob); ?>">
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group ">
				<label>Nationality:</label><span class="text-danger"> *</span>
				<select name="nationality" class="form-control ">
					<option value="">Select Nationality</option>
					<option value="INDIAN" <?php if($student_data->nationality == 'INDIAN'){ echo "selected"; } ?>>Indian</option>
					<option value="OTHER" <?php if($student_data->nationality == 'OTHER'){ echo "selected"; } ?>>Other </option>
				</select>
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group ">
				<label>Religion:</label><span class="text-danger"> *</span>
				<select name="religion" class="form-control ">
					<option value="">Select Religion</option>
					<option value="Hindu" <?php if($student_data->religion == 'Hindu'){ echo "selected"; } ?> >Hindu</option>
					<option value="Muslim" <?php if($student_data->religion == 'Muslim'){ echo "selected"; } ?>>Muslim </option>
					<option value="Sikh" <?php if($student_data->religion == 'Sikh'){ echo "selected"; } ?>>Sikh </option>
					<option value="Christian" <?php if($student_data->religion == 'Christian'){ echo "selected"; } ?>>Christian</option>
				</select>
				<div class="fv-plugins-message-container"></div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group ">
				<label>Category:</label><span class="text-danger"> *</span>
				<select name="category" class="form-control ">
					<option value="">Select Category</option>
					<option <?php if($student_detail->category == 'General'){ echo "selected"; } ?>>General</option>
					<option <?php if($student_detail->category == 'OBC'){ echo "selected"; } ?>>OBC</option>
					<option <?php if($student_detail->category == 'ST'){ echo "selected"; } ?>>ST</option>
					<option <?php if($student_detail->category == 'SC'){ echo "selected"; } ?>>SC</option>
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
							<input type="radio" name="minority" value="Y" <?php if($student_data->minority == 'Y'){ echo "checked"; } ?> >
							<span></span>Yes
						</label>
						<label class="radio radio-success">
							<input type="radio" name="minority" value="N"  <?php if($student_data->minority == 'N'){ echo "checked"; } ?>>
							<span></span>No
						</label>
					</div>
					<div class="text-muted"></div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-form-label">Handicapped</label>
				<div class="col-form-label">
					<div class="radio-inline">
						<label class="radio radio-success">
							<input type="radio" name="handicapped" value="Y" <?php if($student_data->handicapped == 'Y'){ echo "checked"; } ?> >
							<span></span>Yes
						</label>
						<label class="radio radio-success">
							<input type="radio" name="handicapped" value="N"  <?php if($student_data->handicapped == 'N'){ echo "checked"; } ?>>
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
				<input type="text" class="form-control " name="adhar_no"  placeholder="Aadhar Number" value="<?= $student_detail->adhar_no; ?>">
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
				<input type="text" class="form-control " name="c_address" id="c_address" value="<?= $student_data->c_address; ?>" />
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
							<option <?php if($student_data->c_state == $state["name"]){ echo "selected";} ?> ><?=$state['name']?></option>
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
                        <?php
                            foreach ($district_list as $district) { ?>
								<option <?php if($student_data->c_district == $district['name']){ echo "selected";} ?>  ><?=$district['name']?></option>
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
					<input class="form-control " name="c_city" id="c_city" value="<?= $student_data->c_city; ?>">
					<div class="fv-plugins-message-container"></div>
				</div>
				<!--end::Input-->
			</div>
			<div class="col-xl-3">
				<!--begin::Input-->
				<div class="form-group">
					<label>Pin Code</label><span class="text-danger"> *</span>
					<input class="form-control " name="c_pin_code" id="c_pin_code" type="number" value="<?= $student_data->c_pin_code; ?>">
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
					<input type="text" class="form-control " name="p_address" id="p_address" value="<?= $student_data->p_address; ?>" />
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
								<option  <?php if($student_data->p_state == $state["name"]){ echo "selected";} ?> ><?=$state['name']?></option>
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
							<?php
                            foreach ($district_list as $district) { ?>
								<option <?php if($student_data->p_district == $district['name']){ echo "selected";} ?>  ><?=$district['name']?></option>
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
						<input class="form-control " name="p_city" id="p_city" value="<?= $student_data->p_city; ?>">
						<div class="fv-plugins-message-container"></div>
					</div>
					<!--end::Input-->
				</div>
				<div class="col-xl-3">
					<!--begin::Input-->
					<div class="form-group">
						<label>Pin Code</label><span class="text-danger"> *</span>
						<input class="form-control " name="p_pin_code" id="p_pin_code" type="number" value="<?= $student_data->p_pin_code; ?>">
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
						<input name="qualifying_exam" readonly="readonly" placeholder='Qualifying Exam'  class="form-control " type="text"  value="<?= $student_data->eligibility; ?>"/>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<input name="board"  placeholder="विश्वविद्यालय/शिक्षामण्डल" class="form-control " type="text" value="<?= $student_data->board; ?>" />
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="passing_year">
							<option value="">---Select---</option>
							<?php for ($year=2023; $year > 1990; $year--) { ?>
								<option <?php if($student_data->passing_year == $year){ echo "selected";} ?>><?=$year?></option>
							<?php } ?>
						</select>
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<input name="total_marks" placeholder='Total Marks' class="form-control" type="text" value="<?= $student_data->total_marks; ?>" />
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<input name="marks" placeholder="Marks" class="form-control" type="text" value="<?= $student_data->marks; ?>" />
						<div class="fv-plugins-message-container"></div>
					</div>
				</div>
			</div>
			<div class="row">

				<input type="hidden" name="student_id" id="student_id"  value="<?=$student_detail->student_id;?>">
				<input type="hidden" name="center_id"  id="center_id"   value="<?=$student_detail->center_id;?>" >
				<input type="button" name="edit_submit" id="edit_submit" class="btn btn-primary m-auto" value="submit">
			
			</div>
		</div>
	</form>

	<script type="text/javascript">
		$("#dob").inputmask();
		
		var avatar1 = new KTImageInput('kt_image_1');
		setTimeout(function () { document.getElementById("alert-msg").style.display="none"; }, 3000);
	</script>
	<script src="<?=base_url();?>assets/theme/admission.js?token=<?=date('YmdHis')?>"></script>
