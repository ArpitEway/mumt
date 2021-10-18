
<form method="POST" class="d-block ajaxForm" >
    
	<div class="form-row">
		
        <div class="form-group col-md-4">
        <label for="name">Enter Enrollment</label>
        <input type="text" class="form-control" id="st_enrollment" name="st_enrollment"  placeholder="Enter heading">
		<div class="fv-plugins-message-container"></div>
        </div>
		
    </div>
	<div class="form-group text-center">
		<button class="btn btn-md btn-primary" type="button" id="heading_submit">Submit</button>
	</div>
	
</form>

<div id="dt">

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>



<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

</div>



<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

<script>

$("#heading_submit").on('click',function (e){
	e.preventDefault();
	var heading = $('#st_enrollment').val();
	
	if(heading == ""){
				
		$('input[name="st_enrollment"]').next('div').text('Enrollment is Required');
		submit = false;
		
	}else{
		 
	$('input[name="st_enrollment"]').next('div').text('');
	
	var frm = $('.ajaxForm').serialize();
		
	$.ajax({
		url: '<?php echo site_url('admin/admins/check_enrollment_ajax'); ?>',
		type: 'POST',
		dataType : 'JSON',
		data: frm,
		success: function (datas)
		 {
		 	console.log(datas)
			
			 if(datas.status==false)
		 	 {
					$('#st_enrollment').val("");
					toastr.error("This Enrollment is Already Exist");	
			 }
			 else
			 {
				toastr.success("Enrollment Added Successfully");
				
			 }

		 }//success

		});	//ajax
	}
});	

</script>

