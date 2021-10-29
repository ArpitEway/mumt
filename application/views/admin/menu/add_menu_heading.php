<form method="POST" id="ajaxForm" class="d-block ajaxForm" >
    
	<div class="form-row">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<div class="form-group col-md-4">
            <label for="course">Select admin</label>
            <select name="admin_id" id="admin_id" class="form-control admin_id" onchange="getval();" >
           
			<option value="select">Select admin</option>
            <?php
            foreach($admins as $admin)
            {
            ?>
				
			<option value="<?php echo $admin['id']; ?>"><?php echo $admin['name']; ?></option>
                
			<?php
            }
            ?>
            </select>
			<div class="fv-plugins-message-container"></div>			
		</div>
        <div class="form-group col-md-4">
            <label for="name">Heading name</label>
            <input type="text" class="form-control" id="heading_name" name="heading_name"  placeholder="Enter heading" required = "true" >
			
			<div class="fv-plugins-message-container"></div>
			
        </div>
		
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="button" id="heading_submit">Submit</button>
	</div>
</form>
<div id="dt">
</div>
<div id="dt2">
</div>

<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script>


$("#admin_id").on('change',function (e){
	
	admin_id = $('#admin_id').val();
	if(admin_id != "")
	{
		$('select[name="admin_id"]').next('div').text('');  
	}
 
});	


$("#heading_submit").on('click',function (e){
	e.preventDefault();
	var frm = $('.ajaxForm').serialize();
	
	heading  = $('#heading_name').val();
	admin_id = $('#admin_id').val();
	
	if(heading == '' || admin_id == ""){
				
		$('input[name="heading_name"]').next('div').text('Heading name is Required');
		submit = false;
		
		if(admin_id == "select")
		{
			
		$('select[name="admin_id"]').next('div').text('Admin name is Required');
		submit = false;
		
		}
			
		}else{
		
		$('#heading_name').val("");
		
		
        $('input[name="admin_id"]').next('div').text('');   
		$('input[name="heading_name"]').next('div').text('');
		
		$.ajax({
		url: '<?php echo site_url('admin/admins/add_menu_heading/create'); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		success: function (datas) {
			
		if(datas.status == "true"){
		
		getval();
			
		}else{
		toastr.error("Something wrong");
		}
			},
		});	
				}
});	

function getval()
{
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	console.log(csrfHash);
	var data = {
	admin_id : $("#admin_id").val(),
	[csrfName]:csrfHash
	};
	var url = BASE_URL+"admin/Admins/get_heading_data"; 
	var response = call_ajax(data,url);
	console.log(response);
	$('#dt').html(response.data);	 
}

    
</script>

