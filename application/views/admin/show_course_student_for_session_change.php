<div class="container-fluid">
	<div class="row mt-5">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<div class="form-group col-md-3">
			<label for="class">Session</label>
			<select name="session" id="session" class="form-control" >
				
				<?php 
				foreach($sessions as $session)
				{
					?>
					<option value="<?php echo $session['id']; ?>" ><?php echo $session['session']; ?></option>
					<?php
				} 
				?>		
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
				<!-- <option value="all">All</option> -->
				<option value=""> Non-verified </option>
				<!-- <option value="Y">Approved </option>
				<option value="N">Non-Approved </option> -->
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
			course_group_id : 'all',
			class_id : '',
			document_upload : $("#document_upload").val(),
			center_id : 'all',
			approved : $("#approved").val(),
			mode : 'all',
			payment : $("#payment").val(),
			filter : 'count',
			enrolled : 'all',
			session : $("#session").val(),
			[csrfName]:csrfHash,
			count_filter:'course_group_id',
			new_exam_form:'all',
			university_mode:'all'
		};



		$.ajax({
			url: '<?php echo site_url('admin/admins/get_student_for_session_change_report'); ?>',

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
	function getList(){
		console.log("test");
	}
</script>