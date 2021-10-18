$("#course_group_id").on('change', function(){
	var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"admin/Admins/getClassByCourse",
			data: {course_group_id : course},
		})
		.done(function( msg ) {
            $('#class_id').html(msg);
		});
	});