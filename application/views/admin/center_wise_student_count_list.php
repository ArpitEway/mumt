<style type="text/css">
	@media print {
		body *{
			visibility: hidden;
		}
    div#dt *, h5 {
    	visibility: visible !important;
    }
    div#dt{
    	margin-top: -250px;
    }
}
</style>
<div class="row justify-content-center">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="mx-3 col-md-4">
		<label for="course">Course</label>
		<select name="course_group_id" id="course_group_id" class="form-control course_group_id" data-target="#class_id" required >
		<option value="">Select Course</option>			
			<?php 
			foreach($courses as $course)
			{
				?>
				<option value="<?php echo $course->id; ?>"><?php echo $course->course_name; ?></option>
				<?php
			} 
			?>
		</select>
	</div>
	<div align="center" id="myLoader" class="loader_div" style="display: none;" >
		<svg>
			<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
		</svg>
	</div>
</div>
<div id="dt" class="row justify-content-center">
</div>
<script>
	$(document).on("change","#course_group_id",function(){
		$('#dt').hide();

		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = {
			course_group_id : $("#course_group_id").val(),
			[csrfName]:csrfHash,
			
		};
		$.ajax({
			url: '<?php echo site_url('admin/scripts/otherscript/get_student_count_data'); ?>',
			type:'post',
			dataType : 'JSON',
			data:data,
			beforeSend: function()
			{
				$("#myLoader").show();
			},
			success:function(status)
			{
				if( $("#myLoader").show()){
					$('#dt').hide();
						// $table = $('#dt').html(status.data);
				}if( $('#myLoader').hide()){
					$table = $('#dt').html(status.data);
					$('#dt').show();
				}
				KTDatatablesBasicBasic.init();
			},
			complete: function()
			{
				$('#myLoader').hide();
			},
		})
	});
</script>