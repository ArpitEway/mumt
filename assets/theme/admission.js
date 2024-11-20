"use strict";
$("#course_group_id").on('change', function(){
	var course = $(this).val();
    var mode = document.getElementById('mode').value;
	
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	console.log("mode "+mode);
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getClassByCourse",
		data: {course : course,[csrfName]:csrfHash , mode : mode},
	})
	.done(function( msg ) {
		$('#class_id').html(msg);
	});
});

$("#eligibility").on('change', function(){
	var eligibility = $(this).val();
	
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val();
	var mode = $('#mode').val(); 
	var session=$('#session').val();
	$('input[name="qualifying_exam"]').val(eligibility); 
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getCourseByEligibility",
		data: {mode:mode,session:session,eligibility : eligibility,[csrfName]:csrfHash},
	})
	.done(function( msg ) {
		$('#course_group_id').html(msg);
		$('#course_group_id_admission').html(msg);
	});
});

$('input[name="adhar_no"]').on('keyup', function(){
	var self = $(this);
	var adhar_no = $(this).val();
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val();

	if($.isNumeric(adhar_no) == false){
		$(self).next().text("Please Enter Correct Adhar No");
		$('input[type="button"]').attr('disabled','disabled');
		return false;
	}
	if(adhar_no.length!=12){
		$(this).next().text("Please Enter Your 12 Digit Adhar Card Numbers");
		    $('input[type="button"]').attr('disabled','disabled');
	}else{
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/checkDuplicateAdharNo",
		data: {adhar_no : adhar_no,[csrfName]:csrfHash},
	})
	.done(function( msg ) {
		if(msg!=''){
		$(self).next().text("Duplicate Adhar Card Number");
		}else{
		$(self).next().text("");
		$('input[type="button"]').removeAttr('disabled');
		}
	});
	}
});

$('input[name="p_mobile_no"]').on('keyup', function(){
	var self = $(this);
	var p_mobile_no = $(this).val();
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val();
	if($.isNumeric(p_mobile_no) == false){
		$(self).next().text("Please Enter Correct Mobile No");
		$('input[type="button"]').attr('disabled','disabled');
		return false;
	}
	if(p_mobile_no.length!=10){
		$(this).next().text("Please Enter Your 10 Digit Mobile No");
		    $('input[type="button"]').attr('disabled','disabled');
	}else{
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/checkDuplicateMobileNo",
		data: {p_mobile_no : p_mobile_no,[csrfName]:csrfHash},
	})
	.done(function( msg ) {
		if(msg!=''){
		$(self).next().text("Duplicate Mobile No");
		}else{
		$(self).next().text("");
		$('input[type="button"]').removeAttr('disabled');
		}
	});
	}
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
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val();
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getDistrictByState",
		data: {state : state, nameAttr: nameAttr,[csrfName]:csrfHash},
	})
	.done(function( msg ) {
		console.log($("select[name='"+nameAttr+"']"));
		$("select[name='"+nameAttr+"']").html(msg);
	});
});

$("#photo").on('change', function(){
	var file = $(this);
	var fileExtensions = file[0].files[0].name.split(".")[1];
	var validFileExtensions = ["jpg", "JPG", "JPEG", "jpeg", "png", "PNG"];
	if(!validFileExtensions.includes(fileExtensions)){
		$('#errPhoto').text('Please Select Valid Image');
		return false;
	}else{
		$('#errPhoto').text('');
	}
	var filesize = parseFloat(file[0].files[0].size / 1024).toFixed(2);
	if(filesize>500){
		$('#errPhoto').text('Document size must be less than 500kb');
		return false;
	}else{
		$('#errPhoto').text('');
	}
});


$(document).on('click','#submit', function () {
	if(validation()==false){
		return false;
	}
  
    
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
		$('input[type="button"]').attr('disabled','disabled');
			var form = $('form');
			var formData = new FormData(form[0]);
			$.ajax({
				method: "POST",
				url: BASE_URL+"center/SaveFormdata/",
				data: formData,
				dataType: 'json',
				cache:false,
				contentType: false,
				processData: false,
				success: function (data) {
					if(data.student_id){
					toastr.success('Form Submitted Successfully');
					setTimeout(function(){
						// window.location.href = BASE_URL+"center/payment/admission/"+data.student_id;
						window.location.href = BASE_URL+"center/center/select_papers/"+data.student_id
					}, 1000);
					}
					},
					error: function (data) {
				$('input[type="button"]').removeAttr('disabled','disabled');
						toastr.error('An error occurred.');
					},
				});
			return false;
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

$(document).on('click','#edit_submit', function () {
	if(validation("email_validation")==false){
		return false;
	}
          
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
		$('input[type="button"]').attr('disabled','disabled');
			var form = $('form');
			var formData = new FormData(form[0]);
			console.log(formData);
			$.ajax({
				method: "POST",
				url: BASE_URL+"center/updateFormdata/",
				data: formData,
				dataType: 'json',
				cache:false,
				contentType: false,
				processData: false,
				success: function (data) {
					console.log(data);
					if(data.student_id){
					toastr.success('Form Submitted Successfully');
					setTimeout(function(){
						window.location.href = BASE_URL+"admin/"+data.userType+"/show_form/"+data.student_id;
					}, 1000);
					}
					},
					error: function (data) {
				$('input[type="button"]').removeAttr('disabled','disabled');
                   Swal.fire("Not Permitted For Admission ", "info","error");
					// toastr.error('Course Permission Not Permitted .');
					},
				});
			
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

function validation(step=""){
	var submit = true;
	// console.log(step);
	
	var session = $('select[name="session"]').val();
	var course_group_id = $('select[name="course_group_id"]').val();
	var class_id = $('select[name="class_id"]').val(); 
	var eligibility = $('select[name="eligibility"]').val();
	var course_category = $('select[name="course_category"]').val();
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
	var adhar_no = $('input[name="adhar_no"]').val();
	var c_address = $('input[name="c_address"]').val();
	var c_state = $('select[name="c_state"]').find(":selected").val();
	var c_district = $('select[name="c_district"]').find(":selected").val();
	var c_city = $('input[name="c_city"]').val();
	var c_pin_code = $('input[name="c_pin_code"]').val();
	var p_address = $('input[name="p_address"]').val();
	var p_state = $('select[name="p_state"]').find(":selected").val();
	var p_district = $('select[name="p_district"]').find(":selected").val();
	var p_city = $('input[name="p_city"]').val();
	var p_pin_code = $('input[name="p_pin_code"]').val();
	var marks = $('input[name="marks"]').val();
	var passing_year = $('select[name="passing_year"]').val();
	var board = $('input[name="board"]').val();
	var total_marks = $('input[name="total_marks"]').val();
	var nationality = $('select[name="nationality"]').val();
	var religion = $('select[name="religion"]').val();
	var ph = document.getElementById("photo");

	var current_center_id = $('input[name="center_id"]').val();
	if(current_center_id==100){
		var forCenter = $('select[name="forCenter"]').val();
		if(forCenter==""){
			$('select[name="forCenter"]').next('div').text('Admission for which Center is Required');
			document.getElementById('forCenter').focus();
		// submit = false
		
		return false;
		}
		else{
			$('select[name="forCenter"]').next('div').text('');
		}
	}
	if(session==''){
		$('select[name="session"]').next('div').text('Session is Required');
		document.getElementById('session').focus();
		// submit = false
		
		return false;
	}else{
		$('select[name="session"]').next('div').text('');
	}
	if(eligibility==''){
		$('select[name="eligibility"]').next('div').text('eligibility is Required');
		document.getElementById('eligibility').focus();
		return false;
	}else{
		$('select[name="eligibility"]').next('div').text('');
	}
	if(course_group_id==''){
		$('select[name="course_group_id"]').next('div').text('Course is Required');
		document.getElementById('course_group_id').focus();
		// course_group_id = false
		return false;
	}else{
		$('select[name="course_group_id"]').next('div').text('');
	}
	if(class_id==''){
		$('select[name="class_id"]').next('div').text('Class is Required');
		document.getElementById('class_id').focus();
		// submit = false
		return false;
	}else{
		$('select[name="class_id"]').next('div').text('');
	}
	if(!$('input[name="student_id"]').length>0){
		if(photo==''){
			$('#errPhoto').text('photo is Required');
			ph.focus();
			// submit = false
			return false;
		}else{
			$('#errPhoto').text('');
		}
	}
	if(name==''){
		$('input[name="name"]').next('div').text('Name is Required');
		document.getElementById('name').focus();
		// submit = false
		return false;
	}else{
		$('input[name="name"]').next('div').text('');
	}
	if(f_h_name==''){
		$('input[name="f_h_name"]').next('div').text('Father Name is Required');
		document.getElementById('f_h_name').focus();
		// submit = false
		return false;
	}else{
		$('input[name="f_h_name"]').next('div').text('');
	}
	if(mother_name==''){
		$('input[name="mother_name"]').next('div').text('Mother Name is Required');
		document.getElementById('mother_name').focus();
		// submit = false
		return false;
	}else{
		$('input[name="mother_name"]').next('div').text('');
	}
	if(p_mobile_no==''){
		$('input[name="p_mobile_no"]').next('div').text('Mobile is Required');
		document.getElementById('p_mobile_no').focus();
		// submit = false
		return false;
	}else{
		$('input[name="p_mobile_no"]').next('div').text('');
	}
	if(!(step=="email_validation")){
		if(p_email==''){
			$('input[name="p_email"]').next('div').text('E-mail is Required');
			document.getElementById('p_email').focus();
			// submit = false
			return false;
		}else{
			$('input[name="p_email"]').next('div').text('');
		}
	}
	if(dob==''){
		$('input[name="dob"]').next('div').text('Date of Birth is Required');
		document.getElementById('dob').focus();
		// submit = false
		return false;
	}else{
		$('input[name="dob"]').next('div').text('');
	}
	if(nationality==''){
		$('select[name="nationality"]').next('div').text('Nationality is Required');
		document.getElementById('nationality').focus();
		// submit = false
		return false;
	}else{
		$('select[name="nationality"]').next('div').text('');
	}if(religion==''){
		$('select[name="religion"]').next('div').text('Religion is Required');
		document.getElementById('religion').focus();
		// submit = false
		return false;
	}else{
		$('select[name="religion"]').next('div').text('');
	}
	if(category==''){
		$('select[name="category"]').next('div').text('category is Required');
		document.getElementById('category').focus();
		// submit = false
		return false;
	}else{
		$('select[name="category"]').next('div').text('');
	}

	
	if(adhar_no==''){
		$('input[name="adhar_no"]').next('div').text('Adhar No is Required');
		document.getElementById('adhar_no').focus();
		// submit = false
		return false;
	}else{
		$('input[name="adhar_no"]').next('div').text('');
	}
	if(c_address==''){
		$('input[name="c_address"]').next('div').text('Address is Required');
		document.getElementById('c_address').focus();
		// submit = false
		return false;
	}else{
		$('input[name="c_address"]').next('div').text('');
	}
	if(c_state==''){
		$('select[name="c_state"]').next('div').text('State is Required');
		document.getElementById('c_state').focus();
		// submit = false
		return false;
	}else{
		$('select[name="c_state"]').next('div').text('');
	}
	if(c_district==''){
		$('select[name="c_district"]').next('div').text('District is Required');
		document.getElementById('c_district').focus();
		// submit = false
		return false;
	}else{
		$('select[name="c_district"]').next('div').text('');
	}
	if(c_city==''){
		$('input[name="c_city"]').next('div').text('city is Required');
		document.getElementById('c_city').focus();
		// submit = false
		return false;
	}else{
		$('input[name="c_city"]').next('div').text('');
	}
	if(c_pin_code==''){
		$('input[name="c_pin_code"]').next('div').text('Pin Code is Required');
		document.getElementById('c_pin_code').focus();
		// submit = false
		return false;
	}else{
		$('input[name="c_pin_code"]').next('div').text('');
	}
	if(p_address==''){
		$('input[name="p_address"]').next('div').text('Address is Required');
		document.getElementById('p_address').focus();
		// submit = false
		return false;
	}else{
		$('input[name="p_address"]').next('div').text('');
	}
	if(p_state==''){
		$('select[name="p_state"]').next('div').text('State is Required');
		document.getElementById('p_state').focus();
		// submit = false
		return false;
	}else{
		$('select[name="p_state"]').next('div').text('');
	}
	if(p_district==''){
		$('select[name="p_district"]').next('div').text('District is Required');
		document.getElementById('p_district').focus();
		// submit = false
		return false;
	}else{
		$('select[name="p_district"]').next('div').text('');
	}
	if(p_city==''){
		$('input[name="p_city"]').next('div').text('city is Required');
		document.getElementById('p_city').focus();
		// submit = false
		return false;
	}else{
		$('input[name="p_city"]').next('div').text('');
	}
	if(p_pin_code==''){
		$('input[name="p_pin_code"]').next('div').text('Pin Code is Required');
		document.getElementById('p_pin_code').focus();
		// submit = false
		return false;
	}else{
		$('input[name="p_pin_code"]').next('div').text('');
	}
	if(marks==''){
		$('input[name="marks"]').next('div').text('Marks is Required');
		document.getElementById('marks').focus();
		// submit = false
		return false;
	}else{
		$('input[name="marks"]').next('div').text('');
	}
	if(passing_year==''){
		$('select[name="passing_year"]').next('div').text('Year is Required');
		document.getElementById('passing_year').focus();
		// submit = false
		return false;
	}else{
		$('select[name="passing_year"]').next('div').text('');
	}
	if(board==''){
		$('input[name="board"]').next('div').text('Board is Required');
		document.getElementById('board').focus();
		// submit = false
		return false;
	}else{
		$('input[name="board"]').next('div').text('');
	}
	if(total_marks==''){
		$('input[name="total_marks"]').next('div').text('Total Marks is Required');
		document.getElementById('total_marks').focus();
		// submit = false
		return false;
	}else{
		$('input[name="total_marks"]').next('div').text('');
	}
	if(!$('input[name="student_id"]').length>0){
	var file = $("#photo");
	var fileExtensions = file[0].files[0].name.split(".")[1];
	var validFileExtensions = ["jpg", "JPG", "JPEG", "jpeg", "png", "PNG"];
	if(!validFileExtensions.includes(fileExtensions)){
		$('#errPhoto').text('Please Select Valid Image');
		ph.focus();
		return false;
	}else{
		$('#errPhoto').text('');
	}
	var filesize = parseFloat(file[0].files[0].size / 1024).toFixed(2);
	if(filesize>500){
		$('#errPhoto').text('Document size must be less than 500kb');
		ph.focus();
		return false;
	}else{
		$('#errPhoto').text('');
	}
}
	if(submit == false){
		return false;
	}
	return true;

	
	
		
}

// $("#forCenter").on('change', function(){
// 	var forCenter = $(this).val();
	
// 	var csrfName = $('.csrfname').attr('name');
// 	var csrfHash = $('.csrfname').val();
	
// 	$.ajax({
// 		method: "POST",
// 		url: BASE_URL+"center/center/setAdmissionForCenter",
// 		data: {forCenter:forCenter,[csrfName]:csrfHash},
// 	})
// 	.done(function( msg ) {
// 		$('#course_group_id').html("");
// 		$('#course_group_id_admission').html("");
// 		$('#class_id').html("");
// 		//$("#eligibility").trigger("change");
// 		centerChange();
// 	});
// });

// function centerChange(){
// 	var eligibility = $("#eligibility").val();
	
// 	var csrfName = $('.csrfname').attr('name');
// 	var csrfHash = $('.csrfname').val();
// 	var mode = $('#mode').val(); 
// 	var session=$('#session').val();
// 	$('input[name="qualifying_exam"]').val(eligibility); 
// 	$.ajax({
// 		method: "POST",
// 		url: BASE_URL+"center/center/getCourseByEligibility",
// 		data: {mode:mode,session:session,eligibility : eligibility,[csrfName]:csrfHash},
// 	})
// 	.done(function( msg ) {
// 		$('#course_group_id').html(msg);
// 		$('#course_group_id_admission').html(msg);
// 	});
// }