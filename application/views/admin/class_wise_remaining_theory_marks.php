<div class="card card-custom card-stretch">
	<div class="row justify-content-center p-3">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
      
        <div class="form-group col-md-6">
            <label for="">Mode</label>
            <select  class="form-control" name="mode"  id="mode">
            	<option value="">Select</option>
                <!-- <option value="All">All</option> -->
                <!-- <option value="Annual">Annual</option> -->
                <option value="Semester">Semester</option>
            </select>
        </div>
       
        
    </div>   
  
	<div align="center" id="myLoader" class="loader_div" style="display: none;" >
		<svg>
			<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
		</svg>
	</div>
	<div id="dt" class="px-3">
	</div>              
               
</div>
<script>
	
$(document).on("change","#mode",function(){
		$('#dt').hide();
        var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var mode=$("#mode").val();
		if(mode){
				var data = {
					mode : mode,
					[csrfName]:csrfHash
					
				};

				$.ajax({
					url: '<?php echo base_url('admin/admins/class_wise_remaining_student_report'); ?>',

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
			}		
	});
</script>