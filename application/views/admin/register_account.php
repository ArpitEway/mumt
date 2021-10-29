<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<div class="mt-5 text-right">
<a type="button" class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/account/create'); ?>', 'Create account')"  >Create account</a>
</div>
<div class="container mt-3" >
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap " width="100%" >
		<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Username</th>
				<th>Status</th>
				<th>Action</th>
				
			</tr> 
		</thead>
		<tbody>
		<?php
		$i = 1;
        $accounts = $this->db->get_where('admin_master', array('id!='=>1))->result_array();
        foreach($accounts as $acc){
        ?>
			
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $acc['name']; ?></td>
						<td><?php echo $acc['user_name']; ?></td>
						<td> 
						<input type="checkbox" name="update_stats" id="update_chk" <?php if($acc['status'] == "Y") echo "checked" ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="Inactive" data-id="<?= $acc['id']; ?>" class="status_checks btn-success" value="1" >
						</td>
					
						
                	<td>
                			
                		<div style="display: inline-flex;">
                			<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/account/edit/'.$acc['id']); ?>', '<?php echo 'Update account' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                			<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Admins/account_register/delete/'.$acc['id']); ?>', showAllaccount )"><i class="mdi mdi-delete delete-icon"></i></a>
                		</div>
                				
                    </td> 
					</tr>
				
			
		 <?php 
		 $i++;
		 } 
		 ?>
		 </tbody>
	</table>

</div>
<script>

 

$(document).on('change', '.status_checks', function() {
  var val = $(this).val();
  var csrfName = $('.csrfname').attr('name');
  var csrfHash = $('.csrfname').val(); 
  var status = '1';
  var selector = $(this);
  var id = $(this).data('id');
  $("#reason").html("");
  
			if ($(this).hasClass("btn-success")) 
			{
				status = '0';
				$("#reason").append(
				  '<div><label> Reason for making it inactive? </label><font color=red>*</font></div>');

				var input = $('<input>', 
				{
				  id: 'reason_elem',
				  name: 'reason_input_elem',
				  type: 'text',
				  class: 'form-control has-error has-danger',
				  focusin: function() {
					$(this).val('');
				  }
				}).appendTo('#reason');
		   }
		   
		console.log("is checked ?" + $(this).is(":checked"))
		
		selector.hasClass("btn-success") ? (selector.removeClass("btn-success").addClass("btn-danger"),
		selector.removeAttr("checked")) : (selector.removeClass("btn-danger").addClass("btn-success"),
		selector.attr('checked', 'checked'));
		
		
		
		if($(this).is(":checked") == true)
		{
			var data = {
				id: $(this).attr('data-id'),
				status: "Y",
				[csrfName]:csrfHash,
			}; 
			
			var target = $(this).attr("data-target");
			var url = BASE_URL + "admin/Admins/update_user_status";
			var response = call_ajax(data, url);
			if(response.status == true) 
			{
				
			} 
			
		}else{
			var data = {
				id: $(this).attr('data-id'),
				status: "N",
				[csrfName]:csrfHash,
			};
			
			var target = $(this).attr("data-target");
			var url = BASE_URL + "admin/Admins/update_user_status";
			var response = call_ajax(data, url);
			if(response.status == true) {
				
			} 
			
		}
		
		
});



var showAllaccount = function () 
    {
        var url = '<?php echo site_url('admin/Admins/account_register'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                initDataTable('basic-datatable');
            }
        });
    }
</script>