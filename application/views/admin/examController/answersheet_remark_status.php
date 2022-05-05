<style>

.center {
   margin: auto;
   width: 50% !important; 
}
</style>
<div class="container-fluid"   >
	<div class="row mt-5  center">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<div class="form-group col-md-6">
			<label for="course " class="">Select Course</label>
			<select name="course_group_id" id="course_group_id" class="form-control course_group_id" data-target="#class_id" required >
				<option value="all">All</option>
				<?php 
				
				foreach($courses as $course)
				{
                     $course_name = $this->Common_model->getCourseNameByCourseId($course['course_group_id']);
					?>

					<option  value="<?php echo $course['course_group_id']; ?>"><?php echo $course_name; ?></option>

					<?php
				} 
				?> 
			</select>       
		</div>
		<div class="form-group col-md-6">
			<label for="class_id">Select Class</label>
			<select name="class_id" id="class_id" class="form-control"  required >
				<option value="all">All</option>
			</select>       
		</div>

	</div>

	<div class="form-group text-center">
		<button class="btn btn-md btn-primary mt-4" type="button" id="submit_btn">Submit</button>
	</div>
	<div align="center" id="myLoader" class="loader_div" style="display: none;" >
		<svg>
			<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
		</svg>
	</div>
	<div id="dt">
	</div>
</div>
<script>

$(document).on("change", "#course_group_id", function() {
    var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
    var type = $('option:selected', this).attr('data-type');
    var data = {
        id: $(this).val(),
        [csrfName]:csrfHash,
    };
    var target = $(this).attr("data-target");
    var url = BASE_URL + "admin/ExamController/get_class_list_by_course";
    var response = call_ajax(data, url);
    if(response.status == true) {
        $(target).html('<option value="">Select class</option>');
        for(var i = 0; i < response.data.length; i++) {
            $(target).append('<option value="' + response.data[i].id + '">' + response.data[i].class_name + '</option>');
        }
    } 
});

$(document).on("click","#submit_btn",function(){
		// $('#dt').hide();
     

		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = {
			course_group_id : $("#course_group_id").val(),
			class_id : $("#class_id").val(),
        [csrfName]:csrfHash,

		};



		$.ajax({
			url: '<?php echo site_url('admin/ExamController/get_student_for_remark'); ?>',

			type:'post',
			dataType : 'JSON',
			data:data,
			beforeSend: function()
			{
				$("#myLoader").show();
			},
			success:function(data)
			{
				//console.log(data.data);
				if( $("#myLoader").show()){
					$('#dt').hide();
						// $table = $('#dt').html(status.data);

					}if( $('#myLoader').hide()){
						$table = $('#dt').html(data.data);
						$('#dt').show();
						
					}

					// KTDatatablesBasicBasic.init();
				},
				complete: function()
				{
					$('#myLoader').hide();
				},
			})
	});
</script>