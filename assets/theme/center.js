$("#allClassBycourse").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var course = $(this).val();
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getAllClassByCourse",
		data: { course_group_id : course,
				[csrfName]:csrfHash
				},
	})
	.done(function( msg ) {
        $('#class_id').html(msg);
	});
});

$("#session").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var session = $(this).val();
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getCourseBySession",
		data: { 
			session : session,
			[csrfName]:csrfHash
		},
	})
	.done(function( msg ) {
        $('#allClassBycourse').html(msg);
		$('#eligibility').val("");
		$('#course_group_id').val("");
		console.log("Test "+msg);
	});
});