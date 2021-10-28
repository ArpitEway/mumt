<?php
$menu_headings = $this->db->get_where('institute_menu_heading', array('id' => $param1))->result_array();

foreach($menu_headings as $menu_heading): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/add_institute_menu_heading/update/'.$menu_heading['id']); ?>">
    
	<div class="form-row">
		
        <div class="form-group col-md-4">
            <label for="name">Heading name</label>
            <input type="text" class="form-control" value="<?php echo $menu_heading['heading']; ?>" id="heading_name"  name="heading_name" required placeholder="Enter heading">
        </div>
		
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="button" id="edit_heading_submit" >Submit</button>
	</div>
</form>
<div id="dt">
</div>

<?php endforeach; ?>

<script>

	$("#edit_heading_submit").on('click',function (e){
	
	var frm = $('.ajaxForm').serialize();
		
	$.ajax({
		url: '<?php echo site_url('admin/admins/add_institute_menu_heading/update/'.$param1); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		success: function (datas) {
		
		if(datas.status == "true"){
			
			var data = {
			admin_id : $("#admin_id").val(),
			};
				
			var url = base_url+"admin/Admins/get_institute_heading_data"; 
			var response = call_ajax(data,url);
			console.log(response);
				
			$('#dt').html(response.data);
			$('#right-modal').modal('toggle');
			toastr.success("Menu Heading Updated Successfully");
			
		}else{
			toastr.error("Something wrong");
		}
			},
		});	
});
	
</script>

