

<div class="container">

<div class="row mt-5">
<div class="form-group col-md-4">
 
            <select name="payment_list" id="payment_list" class="form-control"  required >
           <option value="">Select</option>
			<option value="all">All</option>
			<option value="admission">Admission</option>
			<option value="Program Fees">Program Fees</option>
                
            </select>       
</div>


</div>


<div id="dt">
</div>

</div>


<script>

$(document).on("change","#payment_list",function(){
	
	  var payment_list = $('#payment_list').val();
	
	  var data = {
		payment_list : $('#payment_list').val(),
	  };
	
	  var url = "<?php echo site_url('admin/account/get_payment_list'); ?>";
	  var response = call_ajax(data,url);
	  console.log(response);
	
	  $('#dt').html(response.data);
	  KTDatatablesBasicBasic.init();
	
		 
});

</script>