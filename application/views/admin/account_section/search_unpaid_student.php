<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
<div class="row">
        <div class="col-lg-6 m-auto">
            <div class="form-group row">
                <label class=" col-form-label col-3" >Enter Value : </label> 
                <input class="form-control col-9" type = "text" id="search_text" name ="search_text" />	
            </div>
		
    <div class="form-group row">
        <button type="button" class="btn btn-primary btn-sm m-auto" id="submit_btn" >Submit</button>
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
		form_number : $("#search_text").val(),
		[csrfName]:csrfHash
	};
	

	$.ajax({
		url:  BASE_URL+ 'admin/Account/get_search_unpaid_student',

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