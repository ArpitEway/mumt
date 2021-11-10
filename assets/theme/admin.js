$("#course_group_id").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"admin/Admins/getClassByCourse",
			data: { course_group_id : course,
					[csrfName]:csrfHash
					},
		})
		.done(function( msg ) {
            $('#class_id').html(msg);
		});
	});