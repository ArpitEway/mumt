//js for users 
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
$("#course").on('change', function(){
	var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"user/Users/getClassByCourse",
			data: {course : course},
		})
		.done(function( msg ) {
            $('#class').html(msg);
		});
	});

	$("#submitEnquiry").on('click', function(){
		var name = $('#name').val();
		var f_h_name = $('#f_h_name').val();
		var email = $('#email').val();
		var dob = $('#dob').val();
		var mobile = $('#mobile').val();
		var department = $('#department').val();
		var course = $('#course').val();
		var submit = true;
		if(name == ''){
			$('#errName').text("Please Enter Full Name");
			submit = false;
		}else{
			$('#errName').text('');
		}
		if(f_h_name == ''){
			$('#errFHName').text("Please Enter Full Father's/ Husband's Name");
			submit = false;
		}else{
			$('#errFHName').text('');
		}
		if(email == ''){
			$('#errEmail').text("Please Enter Email");
			submit = false;
		}else{
			$('#errEmail').text('');
		}
		if(dob == ''){
			$('#errDob').text("Please Enter Date Of Birth");
			submit = false;
		}else{
			$('#errDob').text('');
		}
		if(mobile == ''){
			$('#errMob').text("Please Enter Mobile NO");
			submit = false;
		}else if(mobile.length!=10){
			$('#errMob').text("Please Enter Correct Mobile No");
			submit = false;
		}else{
			$.ajax({
				method: "POST",
				url: BASE_URL+"user/Users/isDuplicateMobile",
				data: {mobile : mobile},
				async: false,
				success: function (response) {
                  if(response>0){
				//alert(response);
				submit = false;
				$('#errMob').text("Duplicate Mobile Number");
				}else{
					$('#errMob').text('');
				} 
                },
				});
		}
		
		if(category == ''){
			$('#errCategory').text("Please Select Category");
			submit = false;
		}else{
			$('#errCategory').text('');
		}
		if(course == ''){
			$('#errCourse').text("Please Select Course");
			submit = false;
		}else{
			$('#errCourse').text('');
		}
		//alert(submit);
		if(!submit){
			return false;
		}
	});