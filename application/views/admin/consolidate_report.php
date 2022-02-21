<div class="container-fluid">
	<div class="row mt-5">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<div class="form-group col-md-3">
			<label for="class">Session</label>
			<select name="session" id="session" class="form-control" >
				<option>All</option>
				<?php 
				foreach($sessions as $session)
				{
					?>
					<option value="<?php echo $session['session']; ?>" ><?php echo $session['session']; ?></option>
					<?php
				} 
				?>		
			</select>
		</div>

		<div class="form-group col-md-3">
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
		</div>

		<div class="form-group col-md-3">
			<label for="course">Course</label>
			<select name="course_group_id" id="course_group_id" class="form-control course_group_id" data-target="#class_id" required >
				<option value="all">All</option>
				<?php 
				$courses = $this->db->get_where('course', array())->result_array();
				foreach($courses as $course)
				{
					?>

					<option value="<?php echo $course['course_group_id']; ?>"><?php echo $course['course_name']; ?></option>

					<?php
				} 
				?> 
			</select>       
		</div>
		<div class="form-group col-md-3">
			<label for="class_id">Class</label>
			<select name="class_id" id="class_id" class="form-control"  required >
				<option value="">All</option>
			</select>       
		</div>

		<div class="form-group col-md-3">
			<label for="class">Payment</label>
			<select name="payment" id="payment" class="form-control"  > 
				<option value="all">All</option>
				<option value="Y">Paid</option>
				<option value="N">Unpaid</option>

			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="class">Admission document</label>
			<select name="document_upload" id="document_upload" class="form-control"  > 
				<option value="all">All</option>
				<option value="Y">Uploaded</option>
				<option value="N">Not uploaded </option>

			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="class">Approved Status</label>
			<select name="approved" id="approved" class="form-control"  > 
				<option value="all">All</option>
				<option value=""> Non-verified </option>
				<option value="Y">Approved </option>
				<option value="N">Non-Approved </option>
			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="class">Enrollment Status</label>
			<select name="enrolled" id="enrolled" class="form-control" > 
				<option value="all">All</option>
				<option value="Y">Enrolled </option> 
				<option value="N">Non Enrolled</option>
			</select>
		</div>

		<div class="form-group col-md-3">
			<label for="class">Mode</label>
			<select name="mode" id="mode" class="form-control" > 
				<option value="all">All</option>
				<option value="annual">Annual </option> 
				<option value="semester">Semester</option>
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="class">Exam Form Status</label>
			<select name="new_exam_form" id="new_exam_form" class="form-control"  > 
				<option value="all">All</option>
				<option value="D"> Not Permitted </option>
				<option value="Y">Submitted </option>
				<option value="N">Not Submitted </option>
			</select>
		</div>

		<div class="col-md-3 radio-inline" style="top: 7px;">
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

		<div class="form-group col-md-3">
			<label for="class">Student Count</label>
			<select name="count_filter" id="count_filter" class="form-control" >

				<option value="course_group_id">Course Wise </option> 
				<option value="center_id" >Center Wise </option>
				<option value="class_id" >Class Wise </option>
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
			course_group_id : $("#course_group_id").val(),
			class_id : $("#class_id").val(),
			document_upload : $("#document_upload").val(),
			center_id : $("#center_id").val(),
			approved : $("#approved").val(),
			mode : $("#mode").val(),
			payment : $("#payment").val(),
			filter : $('input[name="filter"]:checked').val(),
			enrolled : $("#enrolled").val(),
			session : $("#session").val(),
			[csrfName]:csrfHash,
			count_filter:$("#count_filter").val(),
			new_exam_form:$("#new_exam_form").val(),
		};



		$.ajax({
			url: '<?php echo site_url('admin/admins/get_student_consolidate_data'); ?>',

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