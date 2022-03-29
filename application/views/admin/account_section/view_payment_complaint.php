<div class="card border-0">
  	<div class="card-body row text-center" >
        <div class="form-group col-md-3 m-auto">
        <label for="center_id">Center</label>
        <select name="center_id" id="center_id" class="form-control" required >
            <option value="">All</option>
            <?php
            foreach($centers as $center)
            {
            ?>            
           		<option value="<?php echo $center['center_id']; ?>"><?php echo $this->Common_model->getSinglefield("center","center_code",array("id" => $center['center_id'] ))." (".$center['count'].")"; ?></option>
            <?php
            } 
            ?> 
        </select>       
        </div>	
		<div class="form-group col-md-12">
			<label for="class"></label>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
			<button type="button" class="btn btn-primary mt-4" style="margin-top: 24px !important;" id="submit_btn">Submit</button>
		</div>
	</div>
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
		center_id : $("#center_id").val(),
		[csrfName]:csrfHash
	};
	

	$.ajax({
		url:  BASE_URL+ 'admin/'+account_type+'/get_payment_complaints',

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

		KTDatatablesBasicBasic.init();
		 
});

</script>