<div class="card">
  	<div class="card-body row text-center">
  		<div class="form-group col-md-3 m-auto" >		
			<label>Enter Form Number</label>
			<input type="text" name="form_no" id="form_no" class="form-control form-control-lg form-control-solid">
			<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
		</div>		
		<div class="form-group col-md-12">
			<label for="class"></label>
			<button type="button" class="btn btn-primary mt-4" style="margin-top: 24px !important;" id="submit_btn">Submit</button>
		</div>
	</div>
</div>


<div id="dt">
</div>

</div>

<script>

$(document).on("click","#submit_btn",function(){

	var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
	
	var data = {
		form_no : $("#form_no").val(),
		[csrfName]:csrfHash
	};
	var url = BASE_URL+"center/Center/get_student_detail"; 
	var response = call_ajax(data,url);
	console.log(response);
	
	$('#dt').html(response.data);
	KTDatatablesBasicBasic.init();
		 
});

</script>