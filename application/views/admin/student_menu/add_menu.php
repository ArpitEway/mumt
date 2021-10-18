<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/student_add_menu/create'); ?>">
    
	<div class="form-row">
	
		<div class="form-group col-md-6">
        <label for="heading">Select Heading</label>
        <select name="heading_id" id="heading_id" class="form-control">
		<option value="" >Select heading</option>
		<?php
			$student_menu_headings = $this->db->get_where('student_menu_heading', array())->result_array();
				
				foreach($student_menu_headings as $student_menu_heading)
				{
				?>
				
				<option value="<?php echo $student_menu_heading['id']; ?>"><?php echo $student_menu_heading['heading']; ?></option>
					
				<?php
				}
				?>		
		</select>
		<div class="fv-plugins-message-container"></div>
		</div>
        <div class="form-group col-md-6">
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
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<table id="mytable" class="table">
			<thead>
				<tr>
				
					<th>Sno.</th>
					<th>Heading Name</th>
					<th>Menu</th>
					<th>Menu url</th>
					<th>Status</th>
					<th>Action</th>
					
				</tr>
			</thead>
    		<tbody id="sortable">
    		<?php 
			
    		$i = 1;
			
			foreach($menus as $menu){
			
			?>
			
			<tr id="<?php echo $menu['id'];  ?>" data-id = "tr_<?php echo $menu['id']; ?>">
			<td><?php echo $i; ?></td>	
<td><?php echo $this->Common_model->getStudentHeadingNameById($menu["heading_id"]); ?></td>
			<td><?php echo $menu["option"]; ?></td>
			<td><?php echo $menu["url"]; ?></td>
			<td> 
			<input type="checkbox" name="update_status" id="update_chk" <?php if($menu['status'] == "Y") echo "checked" ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-on="Yes" data-off="No" data-id="<?= $menu['id']; ?>" class="status_checks btn-success" value="1" >
			</td>
			<td>		
                <div style="display: inline-flex;">
						<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/student_menu/menu_edit/'.$menu['id']); ?>', '<?php echo 'Update menu' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
						
						<a href="javascript:void(0);" id="delete" class="dropdown-item" onclick="delete_menu(<?php echo $menu['id']; ?>,this);" ?> <i class="mdi mdi-delete delete-icon"></i></a>
                </div>
           </td>
				
		</tr>
			
			
			<?php
			
			$i++;
			} 
			?>
			</tbody>
</table>


<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script>

var $sortable = $("#mytable > tbody");

$sortable.sortable({
	stop:function(event, ui){
		var parameters = $sortable.sortable("toArray");
		console.log(parameters);
		$.post("<?php echo BASE_URL(); ?>admin/admins/update_student_menu_order", {
		value:parameters},function(result){
			toastr.success(result);
		});
	}

});
$("#heading_id").on('change',function (){		
		var data = {
			heading_id : $("#heading_id").val(),
		};
		var url = BASE_URL+"admin/Admins/get_student_menu_data"; 
		var response = call_ajax(data,url);
		console.log(response);
		
		$('#dt').html(response.data);
});
$(document).on('change', '.status_checks', function(e) {
  var val = $(this).val();
  
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
				status: "Y"
			}; 
			
			var target = $(this).attr("data-target");
			var url = BASE_URL + "admin/Admins/update_student_menu_status";
			var response = call_ajax(data, url);
			if(response.status == true) 
			{
				
			} 
			
		}else{
			
			var data = {
				id: $(this).attr('data-id'),
				status: "N"
			};
			
			var target = $(this).attr("data-target");
			var url = BASE_URL + "admin/Admins/update_student_menu_status";
			var response = call_ajax(data, url);
			if(response.status == true) {
				
			} 
			
		}
		
    e.stopImmediatePropagation();
    return false;
});


function delete_menu(para1,param) 
{
	var tr_id = $(param).closest("tr").attr('data-id');
	
	if (confirm('Are you sure ?')) {
	
	var url = '<?php echo BASE_URL('admin/Admins/student_add_menu/delete/'); ?>'+para1;
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                if(response){
				$('#mytable tr[data-id="'+tr_id+'"]').remove();
				toastr.success("Deleted successfully");
				}
            }
        });
	}
}

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

</div>
<script>

// Jquery form validation initialization

$("#menu_submit").on('click',function (e)
{
	heading = $('#heading_name').val();
	admin_id = $('#admin_id').val();
	menu = $('#menu').val();
	menu_url = $('#menu_url').val();
	
	if(heading_id == "" || menu == "" || menu_url == "")
	{
		
		if(admin_id == ""){
		$('select[name="admin_id"]').next('div').text('Admin name is Required');
			submit = false;
		}
		
		if(heading_id == '[object HTMLSelectElement]'){
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
	
	}
	
	else{
		
	$('select[name="admin_id"]').next('div').text('');   
	$('select[name="heading_id"]').next('div').text('');
	$('input[name="menu"]').next('div').text('');
	$('input[name="menu_url"]').next('div').text('');
	
	
	var frm = $('.ajaxForm').serialize();
	$.ajax({
		url: '<?php echo site_url('admin/admins/student_add_menu/create'); ?>',
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
			
			$('#heading_id').val("");
			$('#admin_id').val("");
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
</script>

