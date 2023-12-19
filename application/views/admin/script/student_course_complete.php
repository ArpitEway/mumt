<div class="container-fluid">
<div class="row table">
	<div class="form-group col-md-5 m-auto">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	
		<label class="label_form">Course</label>
		<select class="form-control" name="course_group_id" id="final_course_group_id" data-target="#class_id" required >
			 <option value="">Select Course</option>
			<?php foreach ($courses as $course) {
			 ?>
			 <option value="<?=$course['course_group_id']; ?>"><?php echo $this->Common_model->getCourseNameByCourseId($course['course_group_id']); ?></option> 
			<?php 
		} ?>
		</select>
	</div>
	<div class="form-group col-md-5 m-auto">
		<label for="class">Class</label>
		<select name="class_id" id="class_id" class="form-control"  > 
		<option value="">Select Class</option>
		</select>
	</div>
	</div>
</div>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
		<svg>
			<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
		</svg>
	</div>
<div class="container my-5" id="studentData">	
</div>
 <script type="text/javascript">	
 $("#final_course_group_id").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 

	var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"admin/Admins/getFinalClassByCourse",
			data: { course_group_id : course,
					[csrfName]:csrfHash

					},
		})
		.done(function( msg ) {
            $('#class_id').html(msg);
		});
	});
$("#class_id").on('change',function (e){
    $('#studentData').hide();
	var course_group_ids = $('#final_course_group_id').val();	
	var class_id = $('#class_id').val();	
	
    var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
	$.ajax({
		url: '<?php echo site_url('admin/scripts/Postexam/course_complete_script'); ?>',
		type: 'POST',
		data: {course_group_id:course_group_ids,class_id:class_id,[csrfName]:csrfHash},
        beforeSend: function()
			{
				$("#myLoader").show();
			},
		success: function (data) {
            // const obj = JSON.parse(data);
			if( $("#myLoader").show()){
					$('#studentData').hide();
						

					}if( $('#myLoader').hide()){
						
						$('#studentData').html(data);
						$('#studentData').show();
						
					}
		},
	});
});		
	
</script> 