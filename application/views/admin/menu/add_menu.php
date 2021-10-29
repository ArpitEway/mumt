<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/add_menu/create'); ?>">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="form-row">
		
		  <div class="form-group col-md-4">
				<label for="course">Select admin</label>
				<select name="admin_id" id="admin_id" class="form-control admin_id" data-target="#heading_id">
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
        <label for="course">Select Heading</label>
        <select name="heading_id" id="heading_id" class="form-control" required >
		<option value="">Select heading</option>
		</select>
			<div class="fv-plugins-message-container"></div>		
		</div>
		
        <div class="form-group col-md-4">
            <label for="name">Menu</label>
            <input type="text" class="form-control" id="menu" name="menu" required >
			<div class="fv-plugins-message-container"></div>
        </div>
		 <div class="form-group col-md-6">
            <label for="name">Menu URL</label>
            <input type="text" class="form-control" id="menu_url" name="menu_url" required >
			<div class="fv-plugins-message-container"></div>
        </div>
		<div class="form-group col-md-6">
            <label for="course">Menu status </label>
            <select name="status" id="status" class="form-control">
			
			<option value="Y">Yes</option>
			<option value="N">No</option>			
                
            </select>       
		</div>
		
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="button" id="menu_submit">Submit</button>
	</div>
</form>
<div id="dt">
</div>
<script>

// Jquery form validation initialization
$("#admin_id").on('change',function (e){
	
	admin_id = $('#admin_id').val();
	if(admin_id != "")
	{
		$('select[name="admin_id"]').next('div').text('');  
	}

});	

$("#menu_submit").on('click',function (e){
	var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
	heading = $('#heading_name').val();
	admin_id = $('#admin_id').val();
	menu = $('#menu').val();
	menu_url = $('#menu_url').val();
	
	if(heading_id == "" || admin_id == "" || menu == "" || menu_url == "")
	{
		
	if(admin_id == "select"){
	$('select[name="admin_id"]').next('div').text('Admin name is Required');
		submit = false;
	}
	
	if( heading_id == '[object HTMLSelectElement]' ){
		$('select[name="heading_id"]').next('div').text('Heading is Required');
		submit = false;
	}
	if(menu == ''){
		$('input[name="menu"]').next('div').text('Menu is Required');
		submit = false;
	}
	if(menu_url == ''){
		$('input[name="menu_url"]').next('div').text('Menu url is Required');
		submit = false;
	}	
	
	}else{ 
	
 	$('select[name="heading_id"]').next('div').text('');
	$('input[name="menu"]').next('div').text('');
	$('input[name="menu_url"]').next('div').text('');
	
	var frm = $('.ajaxForm').serialize();
	
	$.ajax({
		url: '<?php echo site_url('admin/admins/add_menu/create'); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		success: function (datas) {
			
		if(datas.status == "true"){
			
			var data = {
			heading_id : $("#heading_id").val(),
			[csrfName]:csrfHash,
			};
			
			var url = BASE_URL+"admin/Admins/get_menu_data"; 
			var response = call_ajax(data,url);
			console.log(response);
			
			$('#heading_id').val("");
			$('#menu').val("");
			$('#menu_url').val("");
			
			$('#dt').html(response.data);
			toastr.success("Menu Added Successfully");
			
			
		}else{
			toastr.error("Something wrong");
		}
			},
		});	
		
	 }
});	

$("#heading_id").on('change',function (){		
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = {
			heading_id : $("#heading_id").val(),
			[csrfName]:csrfHash,
		};
		if(heading_id != "")
	{
		$('select[name="heading_id"]').next('div').text('');  
	}
		var url = BASE_URL+"admin/Admins/get_menu_data"; 
		var response = call_ajax(data,url);
		console.log(response);
		$('#dt').html(response.data);
});

$("#admin_id").on('change',function (e){
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
	var data = {
		id: $(this).val(),
		[csrfName]:csrfHash,
	};
	var target = $(this).attr("data-target");
	var url = BASE_URL + "admin/Admins/get_heading_list_by_admin";
	var response = call_ajax(data, url);
	if(response.status == true) 
	{
		$(target).html('<option value="all">Select heading</option>');
		for(var i = 0; i < response.data.length; i++) 
		{
			$(target).append('<option value="' + response.data[i].id + '">' + response.data[i].heading + '</option>');
		}
	} 
	
	
	
});
</script>

