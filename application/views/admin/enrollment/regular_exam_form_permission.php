<div class="">
	 <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
  	<div class="row text-center" >
        <div class="form-group col-md-3 m-auto">
        <label for="center_id">Center</label>
        <select name="center_id" id="center_id" class="form-control" required >
            <option value="">Select Center</option>
            <?php
            foreach($centers as $center)
            {
            ?>            
            <option value="<?php echo $center->center_id; ?>"><?php echo $center->center_code." (".$center->center.")"; ?></option>
            <?php
            } 
            ?> 
        </select>       
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
	$('#dt').html();
	var data = {
		center_id : $("#center_id").val(),
		[csrfName]:csrfHash
	};
	var url = BASE_URL + "admin/Enrollment/getStudentForRegularExamForm"; 
	var response = call_ajax(data,url);
	console.log(response);
	
	$('#dt').html(response.data);
	KTDatatablesBasicBasic.init();
});

</script>