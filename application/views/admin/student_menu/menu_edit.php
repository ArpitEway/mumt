<?php
$menus = $this->db->get_where('student_menu', array('id' => $param1))->result_array();

foreach($menus as $menu): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/student_add_menu/update/'.$menu['id']); ?>">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="form-row">

		 
        <div class="form-group col-md-6">
			<label for="course">Select Heading</label>
			
			<select name="heading_id" id="heading_id" class="form-control">
			<?php
					$menu_headings = $this->db->get_where('student_menu_heading', array())->result_array();

					foreach($menu_headings as $menu_heading)
					{
					?>
					
					<option value="<?php echo $menu_heading['id']; ?>" <?php if($menu_heading['id'] == $menu['heading_id']){ echo "selected"; } ?>><?php echo $menu_heading['heading']; ?>
					
					</option>
					
					<?php
					}
					?>
			</select>      
	   </div>
	   <div class="form-group col-md-6">
            <label for="name">Menu</label>
            <input type="text" class="form-control" id="menu" value="<?php echo $menu['option']; ?>" name="menu" required >
        </div>
		 <div class="form-group col-md-6">
            <label for="name">Menu URL</label>
            <input type="text" class="form-control" id="menu_url" value="<?php echo $menu['url']; ?>" name="menu_url" required >
        </div>
		<div class="form-group col-md-6">
            <label for="course">Menu status </label>
            <select name="status" id="status" class="form-control">
			
			<option value="Y" <?php if($menu['status'] == "Y"){ echo "selected"; } ?>>Yes</option>
			<option value="N" <?php if($menu['status'] == "N"){ echo "selected"; } ?>>No</option>			
                
            </select>       
		</div>
</div>

<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="button" id="edit_menu_submit">Submit</button>
</div>

</form>

<div id="dt"></div>

<?php endforeach; ?>

<script>

	$("#edit_menu_submit").on('click',function (e){
	
	var frm = $('.ajaxForm').serialize();
		
	$.ajax({
		url: '<?php echo site_url('admin/admins/student_add_menu/update/'.$param1); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		success: function (datas) {
		
		if(datas.status == "true"){
			
			var data = {
			admin_id : $("#admin_id").val(),
			};
				
			var url = BASE_URL+"admin/Admins/get_student_menu_data"; 
			var response = call_ajax(data,url);
			console.log(response);
				
			$('#dt').html(response.data);
			$('#right-modal').modal('toggle');
			toastr.success("Menu Updated Successfully");
			
			
		}else{
			toastr.error("Something wrong");
		}
			},
		});	
});
	
</script>

