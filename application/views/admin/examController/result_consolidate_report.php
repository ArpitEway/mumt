<div class="container-fluid">
	<div class="row mt-5">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		

		<!-- <div class="form-group col-md-3">
			<label for="center_id">Center</label>
			<select name="center_id" id="center_id" class="form-control "  required >
				<option value="all">All</option>
				<?php 
				$centers = $this->db->get_where('center', array())->result_array();
				foreach($centers as $center)
				{
					?>
					<option value="<?php echo $center['id']; ?>"><?php echo $center['center_code'] ." - ". $center['center_name']; ?></option>

					<?php
				} 
				?> 
			</select>
		</div> -->
<?php 

			//echo "<pre>";
		//	print_r($courses);
			//echo $this->Common_model->last_query();
		//	die;
?>
		<div class="form-group col-md-3">
			<label for="course">Course</label>
			<select name="course_group_id" id="course_group" class="form-control course_group_id" data-target="#class_id" required >
				<!-- <option value="all">All</option> -->
				<?php 
				$this->db->group_by('course_group_id');
				$where=array('result_permission'=>'Y','final_result_permission'=>'Y');
	
			 $courses=$this->Common_model->get_record('class_master','course_group_id',$where);
			
            //$courses = $this->Common_model->get_record('course_group','id,course_name');
				// $courses = $this->db->get_where('course', array())->result_array();
				foreach($courses as $course)
				{
					?>
					<option value="<?php echo $course['course_group_id']; ?>"><?php echo $this->Common_model->getCourseNameByCourseId($course['course_group_id']) ?></option>

					<?php
				} 
				?> 
			</select>       
		</div>
		<div class="form-group col-md-3">
			<label for="class_id">Class</label>
			<select name="class_id" id="class_id" class="form-control"  required >
				<!-- <option value="">All</option> -->
			</select>       
		</div>
		
	
		
		<div class="form-group col-md-2">
			<label for="class">Admission Mode</label>
			<select name="university_mode" id="university_mode" class="form-control" >
			    <!-- <option value="all">All </option>  -->
				<option value="REG">Regular </option> 
				<option value="PVT" >Private</option>
			</select>
		</div>


		<!-- <div class="form-group col-md-2">
			<label for="class">Course Mode</label>
			<select name="mode" id="mode" class="form-control" > 
				<option value="all">All</option>
				<option value="annual">Annual </option> 
				<option value="semester">Semester</option>
			</select>
		</div> -->
		

		<div class="col-md-2 radio-inline" style="top: 7px;">
			<label class="radio radio-success">
				<input type="radio" name="filter" value="list" checked />
				<span></span>
				List
			</label>

			<label class="radio radio-success">
				<input type="radio" name="filter" value="count" checked/>
				<span></span>
				Count
			</label>          
		</div>
		<div class="form-group col-md-2">
			<label for="class">Student Count</label>
			<select name="count_filter" id="count_filter" class="form-control" >
				<option value="first">First Division </option> 
				<option value="second" >Second Division </option>
				<option value="third" >Third Division </option>
			</select>
		</div>
		<!-- <div class="form-group col-md-2">
			<label for="class">Student Count</label>
			<select name="count_filter" id="count_filter" class="form-control" >
				<option value="course_group_id">Course Wise </option> 
				<option value="center_id" >Center Wise </option>
				<option value="class_id" >Class Wise </option>
			</select>
		</div> -->
		
	
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
	$('input:radio[name="filter"]').change(function() {
		if ($(this).val()=='list') {
			$('#count_filter').attr('disabled', true);
		} 
		else if ($(this).val()=='count') {
			$('#count_filter').attr('disabled', false);
		}
	});

	$(document).on("click","#submit_btn",function(){
		$('#dt').hide();

		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = {
			course_group_id : $("#course_group").val(),
			class_id : $("#class_id").val(),
			document_upload : $("#document_upload").val(),
			center_id : $("#center_id").val(),
			approved : $("#approved").val(),
			mode : $("#mode").val(),
			course_type : $("#course_type").val(),
			payment : $("#payment").val(),
			filter : $('input[name="filter"]:checked').val(),
			enrolled : $("#enrolled").val(),
			session : $("#session").val(),
			[csrfName]:csrfHash,
			count_filter:$("#count_filter").val(),
			new_exam_form:$("#new_exam_form").val(),
			university_mode:$('#university_mode').val(),
			center_type:$('#center_type').val()
		};



		$.ajax({
			url: '<?php echo site_url('admin/ExamController/get_student_result_consolidate_data'); ?>',

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

	$("#course_group").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 

	var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"admin/ExamController/getResultClassByCourse",
			data: { course_group_id : course,
					[csrfName]:csrfHash

					},
		})
		.done(function( msg ) {
            $('#class_id').html(msg);
		});
	});
</script>