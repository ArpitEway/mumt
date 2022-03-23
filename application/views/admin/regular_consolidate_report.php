<div class="container">
	<div class="row mt-5"> 
		<div class="form-group col-md-3">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<label for="session_id">Session</label>
		<select name="session_id" id="session_id" class="form-control" >
		<option value="all">All</option>
		<?php 
			
			foreach($sessions as $session)
			{
			?>
			<option value="<?php echo $session['session']; ?>"><?php echo $session['session']; ?></option>
			<?php
			} 
		?>		
		</select>
	</div>

	<div class="form-group col-md-3">
		<label for="center_id">Center</label>
		<select name="center_id" id="center_id" class="form-control"  required >
		<option value="all">All</option>
		<?php 
		$centers = $this->db->get_where('center', "center_code not in ('IC5000', 'IC5001','IC5002')")->result_array();
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
		<label for="class">Course Mode</label>
		<select name="mode" id="mode" class="form-control" > 
			<option value="all">All</option>
			<option value="annual">Annual </option> 
			<option value="semester">Semester</option>
		</select>
	</div>

	<div class="form-group col-md-3">
		<label for="class">Student Count</label>
		<select name="filter" id="filter" class="form-control" > 
			<option value="course">Course wise </option> 
			<option value="center">Center wise</option>
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

$(document).on("click","#submit_btn",function(){
	$('#dt').hide();
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = {
		course_group_id : $("#course_group_id").val(),
		class_id : $("#class_id").val(),
		document_upload : $("#document_upload").val(),
		center : $("#center_id").val(),
		mode : $("#mode").val(),
		approved : $("#approved").val(),
		payment : $("#payment").val(),
		filter :$("#filter").val(),
		enrolled : $("#enrolled").val(),
		session : $("#session_id").val(),
		[csrfName]:csrfHash
	};

	$.ajax({
		url: '<?php echo site_url('admin/Admins/get_student_consolidate_data_regular'); ?>',

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

	// var url = BASE_URL+"admin/director/get_student_consolidate_data"; 
	// var response = call_ajax(data,url);
	
	// console.log(response);
	
	// $('#dt').html(response.data);
	// KTDatatablesBasicBasic.init();
		 
	
});



var showAllpaper = function () 
    {
        var url = '<?php echo site_url('admin/Admins/paper'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                initDataTable('basic-datatable');
            }
        });
    }
</script>

<!-- <script src="<?=base_url()?>assets/js/pages/crud/forms/widgets/select2.js"></script> -->