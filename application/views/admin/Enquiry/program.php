<div class="row text-center">
<div class="form-group col-md-4">
</div>
	<div class="form-group col-md-4">
		<select name="department" id="department" class="form-control department "  required >
			<option style="text-align:center;"  value="">Select Department</option>
			<?php 

			$dt = $this->db->get_where('department', array())->result_array();
			foreach($dt as $dept)
			{

			?>
						
				<option value="<?php echo $dept['id']; ?>"><?php echo $dept['department_name']; ?></option>
						
			<?php

			} 

			?>
		</select>       
</div>
<div class="form-group col-md-4">
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

<script>

$(document).on("change","#department",function(){
	$('#dt').hide();

	var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
	
	var data = {
		department : $("#department").val(),
		[csrfName]:csrfHash
	};
	

	$.ajax({
		url:  BASE_URL+ 'admin/Enquiry/getListByDepartment',

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