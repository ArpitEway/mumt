"use strict";
$("#category").on('change', function(){
	var category = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"user/Users/getCourseByCategory",
			data: {category : category},
		})
		.done(function( msg ) {
            $('#course').html(msg);
            $('#course_group_id').html(msg);
		});
	});

$("#submitcheck").on('change',function (){
	console.log($('#submitcheck').is(":checked"));
	if($('#submitcheck').is(":checked")){
		$('#lastSubmit').removeAttr("disabled");
	}else{
		$('#lastSubmit').attr("disabled","disabled");
}
});

$("#course_group_id").on('change', function(){
	var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"user/Users/getClassAndEligibilityByCourse",
			data: {course : course},
			dataType: "json",
		})
		.done(function( msg ) {
            $('#class').html(msg.class_id);
            $('#eligibility').html(msg.eligibility);
		});
	});

$("#copyAddress").on('change',function (){
	if($('#copyAddress').is(":checked")){
		$('#p_address').val($('#c_address').val());
		$('#p_city').val($('#c_city').val());
		$('#p_state').val($('#c_state').val());
		$('#p_district').val($('#c_district').val());
		$('#p_pin_code').val($('#c_pin_code').val());
		}else{
		$('#p_address').val('');
		$('#p_city').val('');
		$('#p_state').val('');
		$('#p_district').val('');
		$('#p_pin_code').val('');
	}
});
$(".state").on('change', function(){
	var state = $(this).val();
	var nameAttr = $(this).data('district');
	$.ajax({
		method: "POST",
		url: BASE_URL+"student/Student/getDistrictByState",
		data: {state : state, nameAttr: nameAttr},
	})
	.done(function( msg ) {
		console.log($("select[name='"+nameAttr+"']"));
		$("select[name='"+nameAttr+"']").html(msg);
	});
});

// Class definition
var KTWizard2 = function () {
	// Base elements
	var _wizardEl;
	var _formEl;
	var _wizardObj;
	var _validations = [];
	var formStep = $('#formStep').val();
	
	// Private functions
	var _initWizard = function () {
		// Initialize form wizard
		_wizardObj = new KTWizard(_wizardEl, {
			startStep: formStep, // initial active step number
			clickableSteps: false // to make steps clickable this set value true and add data-wizard-clickable="true" in HTML for class="wizard" element
			
		});
		
		// Validation before going to next page
		_wizardObj.on('change', function (wizard) {
			if (wizard.getStep() > wizard.getNewStep()) {
				return; // Skip if stepped back
			}
			var step = wizard.getStep();
			return validation(step);
		});
		
		// Change event
		_wizardObj.on('changed', function (wizard) {
			KTUtil.scrollTop();
		});
		
		// Submit event
		_wizardObj.on('submit', function (wizard) {
			Swal.fire({
				text: "All is good! Please confirm the form submission.",
				icon: "success",
				showCancelButton: true,
				buttonsStyling: false,
				confirmButtonText: "Yes, submit!",
				cancelButtonText: "No, cancel",
				customClass: {
					confirmButton: "btn font-weight-bold btn-primary",
					cancelButton: "btn font-weight-bold btn-default"
				}
				}).then(function (result) {
				if (result.value) {
				var student_id = $('input[name="student_id"]').val();
					$.ajax({
						method: "POST",
						async:false,
						url: BASE_URL+"center/updateEnrollmentForm/4",
						data: '?formSubmit=DONE&student_id='+student_id,
						success: function (data) {
						console.log(data);
						window.location.href = BASE_URL+'center';
					},
					error: function (data) {
						console.log('An error occurred.');
					},
					});
					return false;
					//_formEl.submit(); // Submit form
					} else if (result.dismiss === 'cancel') {
					Swal.fire({
						text: "Your form has not been submitted!.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "Ok, got it!",
						customClass: {
							confirmButton: "btn font-weight-bold btn-primary",
						}
					});
				}
			});
		});
	}
	
	function validation(step){
		var submit = true;
		if(step==1){
			var session = $('input[name="session"]').val();
			var student_id = $('input[name="student_id"]').val();
			var course_group_id = $('select[name="course_group_id"]').val();
			var class_id = $('select[name="class_id"]').val();
			var eligibility = $('input[name="eligibility"]').val();
			
			if(session==''){
				$('input[name="session"]').next('div').text('Session is Required');
				submit = false
				}else{
				$('input[name="session"]').next('div').text('');
			}
			
			if(course_group_id==''){
				$('select[name="course_group_id"]').next('div').text('Course is Required');
				submit = false
				}else{
				$('select[name="course_group_id"]').next('div').text('');
			}
			if(class_id==''){
				$('select[name="class_id"]').next('div').text('class is Required');
				submit = false
				}else{
				$('select[name="class_id"]').next('div').text('');
			}
			if(eligibility==''){
				$('input[name="eligibility"]').next('div').text('eligibility is Required');
				submit = false
				}else{
				$('input[name="eligibility"]').next('div').text('');
			}
			if(!submit){
				return false;
				}else{
				$.ajax({
					method: "POST",
					url: BASE_URL+"center/updateEnrollmentForm/1",
					data: "session="+session+'&course_group_id='+course_group_id+'&class_id='+class_id+'&eligibility='+eligibility+'&student_id='+student_id,
					}).done(function( msg ) {
					$('#student_id').val(msg);
					return true;
				});
			}
			}else if(step==2){
			var category = $('select[name="category"]').find(":selected").val();
			var photo = $('input[name="photo"]').val();
			var name_hindi = $('input[name="name_hindi"]').val();
			var name = $('input[name="name"]').val();
			var f_h_name_hindi = $('input[name="f_h_name_hindi"]').val();
			var f_h_name = $('input[name="f_h_name"]').val();
			var f_h_occupation = $('select[name="f_h_occupation"]').find(":selected").val();
			var mother_name_hindi = $('input[name="mother_name_hindi"]').val();
			var mother_name = $('input[name="mother_name"]').val();
			var mother_occupation = $('select[name="mother_occupation"]').find(":selected").val();
			var p_mobile_no = $('input[name="p_mobile_no"]').val();
			var f_h_mobile_no = $('input[name="f_h_mobile_no"]').val();
			var p_email = $('input[name="p_email"]').val();
			var dob = $('input[name="dob"]').val();
			//var blood_group = $('select[name="blood_group"]').find(":selected").val();
			//var adhar_no = $('input[name="adhar_no"]').val();
			//var sm_id = $('input[name="sm_id"]').val();
			
			if(category==''){
				$('select[name="category"]').next('div').text('category is Required');
				submit = false
				}else{
				$('select[name="category"]').next('div').text('');
			}
			if(photo==''){
				$('#errPhoto').text('photo is Required');
				submit = false
				}else{
				$('#errPhoto').text('');
			}
			if(name_hindi==''){
				$('input[name="name_hindi"]').next('div').text('Name is Required');
				submit = false
				}else{
				$('input[name="name_hindi"]').next('div').text('');
			}
			if(name==''){
				$('input[name="name"]').next('div').text('Name is Required');
				submit = false
				}else{
				$('input[name="name"]').next('div').text('');
			}
			if(f_h_name_hindi==''){
				$('input[name="f_h_name_hindi"]').next('div').text('Father Name is Required');
				submit = false
				}else{
				$('input[name="f_h_name_hindi"]').next('div').text('');
			}
			if(f_h_name==''){
				$('input[name="f_h_name"]').next('div').text('Father Name is Required');
				submit = false
				}else{
				$('input[name="f_h_name"]').next('div').text('');
			}
			if(f_h_occupation==''){
				$('select[name="f_h_occupation"]').next('div').text('Father occupation is Required');
				submit = false
				}else{
				$('select[name="f_h_occupation"]').next('div').text('');
			}
			if(mother_occupation==''){
				$('select[name="mother_occupation"]').next('div').text('Mother occupation is Required');
				submit = false
				}else{
				$('select[name="mother_occupation"]').next('div').text('');
			}
			if(mother_name_hindi==''){
				$('input[name="mother_name_hindi"]').next('div').text('Mother Name is Required');
				submit = false
				}else{
				$('input[name="mother_name_hindi"]').next('div').text('');
			}
			if(mother_name==''){
				$('input[name="mother_name"]').next('div').text('Mother Name is Required');
				submit = false
				}else{
				$('input[name="mother_name"]').next('div').text('');
			}
			if(p_mobile_no==''){
				$('input[name="p_mobile_no"]').next('div').text('Mobile is Required');
				submit = false
				}else{
				$('input[name="p_mobile_no"]').next('div').text('');
			}
			if(f_h_mobile_no==''){
				$('input[name="f_h_mobile_no"]').next('div').text('Mobile is Required');
				submit = false
				}else{
				$('input[name="f_h_mobile_no"]').next('div').text('');
			}
			if(p_email==''){
				$('input[name="p_email"]').next('div').text('E-mail is Required');
				submit = false
				}else{
				$('input[name="p_email"]').next('div').text('');
			}
			if(dob==''){
				$('input[name="dob"]').next('div').text('Date of Birth is Required');
				submit = false
				}else{
				$('input[name="dob"]').next('div').text('');
			}
			if(!submit){
				return false;
				}else{
				var formimage = $('#imageform');
				var formData = new FormData(formimage[0]);
				var student_id = $('#student_id').val();
				formData.append('student_id',student_id);
				$.ajax({
					method: "POST",
					url: BASE_URL+"center/updateEnrollmentForm/2",
					data: formData,
					cache:false,
		            contentType: false,
		            processData: false,
					success: function (data) {
						console.log(data);
					},
					error: function (data) {
						console.log('An error occurred.');
					},
				});
			}
			}else if(step==3){
			//var c_address = $('textarea[name="c_address"]').val();
			//var c_state = $('select[name="c_state"]').find(":selected").val();
			//var c_district = $('select[name="c_district"]').find(":selected").val();
			//var c_city = $('input[name="c_city"]').val();
			//var c_pin_code = $('input[name="c_pin_code"]').val();
			
			var p_address = $('textarea[name="p_address"]').val();
			var p_state = $('select[name="p_state"]').find(":selected").val();
			var p_district = $('select[name="p_district"]').find(":selected").val();
			var p_city = $('input[name="p_city"]').val();
			var p_pin_code = $('input[name="p_pin_code"]').val();
				
			/*
			
			if(c_address==''){
				$('textarea[name="c_address"]').next('div').text('Address is Required');
				submit = false
				}else{
				$('textarea[name="c_address"]').next('div').text('');
			}
			if(c_state==''){
				$('select[name="c_state"]').next('div').text('State is Required');
				submit = false
				}else{
				$('select[name="c_state"]').next('div').text('');
			}
			if(c_district==''){
				$('select[name="c_district"]').next('div').text('District is Required');
				submit = false
				}else{
				$('select[name="c_district"]').next('div').text('');
			}
			if(c_city==''){
				$('input[name="c_city"]').next('div').text('city is Required');
				submit = false
				}else{
				$('input[name="c_city"]').next('div').text('');
			}
			if(c_pin_code==''){
				$('input[name="c_pin_code"]').next('div').text('Pin Code is Required');
				submit = false
				}else{
				$('input[name="c_pin_code"]').next('div').text('');
			} 
			*/
				
			if(p_address==''){
				$('textarea[name="p_address"]').next('div').text('Address is Required');
				submit = false
				}else{
				$('textarea[name="p_address"]').next('div').text('');
			}
			if(p_state==''){
				$('select[name="p_state"]').next('div').text('State is Required');
				submit = false
				}else{
				$('select[name="p_state"]').next('div').text('');
			}
			if(p_district==''){
				$('select[name="p_district"]').next('div').text('District is Required');
				submit = false
				}else{
				$('select[name="p_district"]').next('div').text('');
			}
			if(p_city==''){
				$('input[name="p_city"]').next('div').text('city is Required');
				submit = false
				}else{
				$('input[name="p_city"]').next('div').text('');
			}
			if(p_pin_code==''){
				$('input[name="p_pin_code"]').next('div').text('Pin Code is Required');
				submit = false
				}else{
				$('input[name="p_pin_code"]').next('div').text('');
			}
			if(!submit){
				return false;
				}else{
					var student_id = $('input[name="student_id"]').val();
				$.ajax({
					method: "POST",
					url: BASE_URL+"center/updateEnrollmentForm/3",
					data: $('form[name="step_3"]').serialize()+'&student_id='+student_id,
					}).done(function( msg ) {
					return true;
				});
			}
			}else if (step==4){
			$.ajax({
				method: "POST",
				url: BASE_URL+"center/updateEnrollmentForm/4",
				data: $('form[name="step_4"]').serialize(),
				}).done(function( msg ) {
				return true;
			});
			}else if(step==5){
			var graduation_marks = $('input[name="graduation_marks"]').val();
			var graduation_total_marks = $('input[name="graduation_total_marks"]').val();
			var graduation_year = $('input[name="graduation_year"]').val();
			var graduation_subject = $('input[name="graduation_subject"]').val();
			var graduation_university = $('input[name="graduation_university"]').val();
			
			var twowelth_marks = $('input[name="twowelth_marks"]').val();
			var twowelth_year = $('input[name="twowelth_year"]').val();
			var twowelth_subject = $('input[name="twowelth_subject"]').val();
			var twowelth_board = $('input[name="twowelth_board"]').val();
			var twowelth_total_marks = $('input[name="twowelth_total_marks"]').val();
			
			var ten_marks = $('input[name="ten_marks"]').val();
			var ten_year = $('input[name="ten_year"]').val();
			var ten_subjects = $('input[name="ten_subjects"]').val();
			var ten_board = $('input[name="ten_board"]').val();
			var ten_total_marks = $('input[name="ten_total_marks"]').val();
			
			var submit = true;
			var validationcount = $('#docLength').val();
			if(validationcount>4){
				if(graduation_marks==''){
					$('input[name="graduation_marks"]').next('div').text('Graduation Marks is Required');
					submit = false
					}else{
					$('input[name="graduation_marks"]').next('div').text('');
				}
				if(graduation_total_marks==''){
					$('input[name="graduation_total_marks"]').next('div').text('Total Marks is Required');
					submit = false
					}else{
					$('input[name="graduation_total_marks"]').next('div').text('');
				}
				if(graduation_year==''){
					$('input[name="graduation_year"]').next('div').text('Graduation Year is Required');
					submit = false
					}else{
					$('input[name="graduation_year"]').next('div').text('');
				}
				if(graduation_subject==''){
					$('input[name="graduation_subject"]').next('div').text('Graduation Subject is Required');
					submit = false
					}else{
					$('input[name="graduation_subject"]').next('div').text('');
				}
				if(graduation_university==''){
					$('input[name="graduation_university"]').next('div').text('University is Required');
					submit = false
					}else{
					$('input[name="graduation_university"]').next('div').text('');
				}
				
			}
			if(validationcount>3){
				if(twowelth_marks==''){
					$('input[name="twowelth_marks"]').next('div').text('Marks is Required');
					submit = false
					}else{
					$('input[name="twowelth_marks"]').next('div').text('');
				}
				if(twowelth_total_marks==''){
					$('input[name="twowelth_total_marks"]').next('div').text('Total Marks is Required');
					submit = false
					}else{
					$('input[name="twowelth_total_marks"]').next('div').text('');
				}
				if(twowelth_year==''){
					$('input[name="twowelth_year"]').next('div').text('Year is Required');
					submit = false
					}else{
					$('input[name="twowelth_year"]').next('div').text('');
				}
				if(twowelth_subject==''){
					$('input[name="twowelth_subject"]').next('div').text('Subject is Required');
					submit = false
					}else{
					$('input[name="twowelth_subject"]').next('div').text('');
				}
				if(twowelth_board==''){
					$('input[name="twowelth_board"]').next('div').text('Board is Required');
					submit = false
					}else{
					$('input[name="twowelth_board"]').next('div').text('');
				}
				
			}
			if(validationcount>2){
				if(ten_marks==''){
					$('input[name="ten_marks"]').next('div').text('Marks is Required');
					submit = false
					}else{
					$('input[name="ten_marks"]').next('div').text('');
				}
				if(ten_year==''){
					$('input[name="ten_year"]').next('div').text('Year is Required');
					submit = false
					}else{
					$('input[name="ten_year"]').next('div').text('');
				}
				if(ten_subjects==''){
					$('input[name="ten_subjects"]').next('div').text('Subject is Required');
					submit = false
					}else{
					$('input[name="ten_subjects"]').next('div').text('');
				}
				if(ten_board==''){
					$('input[name="ten_board"]').next('div').text('Board is Required');
					submit = false
					}else{
					$('input[name="ten_board"]').next('div').text('');
				}
				if(ten_total_marks==''){
					$('input[name="ten_total_marks"]').next('div').text('Total Marks is Required');
					submit = false
					}else{
					$('input[name="ten_total_marks"]').next('div').text('');
				}
				
			}
			/* for(let i=0; i<docCatIdArray.length; i++){
				
				var data = $('#'+docCatIdArray[i]).val();
				if(data==''){
					submit = false;
					$('#'+docCatIdArray[i]).parent().next('div').text('Document Required');
					}else{
					$('#'+docCatIdArray[i]).parent().next('div').text('');
				}
			} */
			
			if(submit){
				
				var documentForm = $('#documentForm');
				var formData = new FormData(documentForm[0]);
				$.ajax({
					method: "POST",
					url: BASE_URL+"center/updateEnrollmentForm/6",
					data: formData,
					cache:false,
					//async: false,
		            contentType: false,
		            processData: false,
					success: function (data) {
						console.log(data);
					},
					error: function (data) {
						console.log('An error occurred.');
						return false;
					},
				});
				}else{
				return false;
			}
			} if(step==6){
			return true;
		}
	}
	
	
	return {
		// public functions
		init: function () {
			_wizardEl = KTUtil.getById('kt_wizard');
			_formEl = KTUtil.getById('kt_form');
			
			_initWizard();
			//_initValidation();
		}
	};
}();

jQuery(document).ready(function () {
	KTWizard2.init();
});