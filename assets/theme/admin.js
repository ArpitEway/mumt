$("#course_group_id").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var mode = document.getElementById('mode').value;
	var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"admin/Admins/getClassByCourse",
			data: { course_group_id : course,
					[csrfName]:csrfHash
					, mode : mode
					},
		})
		.done(function( msg ) {
            $('#class_id').html(msg);
		});
	});
	$("#course_group_id_admission").on('change', function(){
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var mode = document.getElementById('mode').value;
		var course = $(this).val();
			$.ajax({
				method: "POST",
				url: BASE_URL+"admin/Admins/getClassByCourseInAdmission",
				data: { course_group_id : course,
						[csrfName]:csrfHash
						, mode : mode
						},
			})
			.done(function( msg ) {
				$('#class_id').html(msg);
			});
		});