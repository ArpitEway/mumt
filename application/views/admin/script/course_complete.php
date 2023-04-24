<div class="container-fluid">
	<div class="form-group col-md-5 m-auto">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	
		<label class="label_form">Course Course</label>
		<select class="form-control" name="course_group_id" id="course_group_id">
			 <option value="">Select</option>
			<?php foreach ($courses as $course) {
			 ?>
			 <option value="<?=$course['id']; ?>"><?php echo $this->Common_model->getCourseNameByCourseId($course['id']); ?></option> 
			<?php 
		} ?>
		</select>
	</div>
</div>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
		<svg>
			<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
		</svg>
	</div>
<div class="container mt-5" id="studentData">	
</div>
 <script type="text/javascript">	
$("#course_group_id").on('change',function (e){
	$('#studentData').hide();
	var course_group_ids = $('#course_group_id').val();	
	 var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			
	$.ajax({
		url: '<?php echo site_url('admin/scripts/Postexam/course_complete_list'); ?>',
		type: 'POST',
		data: {course_group_id:course_group_ids,[csrfName]:csrfHash},
		beforeSend: function()
			{
				$("#myLoader").show();
			},
		success: function (data) {
			if( $("#myLoader").show()){
					$('#studentData').hide();
						

					}if( $('#myLoader').hide()){
						
						$('#studentData').html(data);
						$('#studentData').show();
						
					}
			
			// initDataTable('basic-datatable');
			KTDatatablesBasicBasic.init()
		},
	});
});		
	
</script> 