<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table">
			<thead>
				<tr>
					<th>Enrollment No.</th>
					<th>Name</th>
					<th>Exam Status</th>
					<th>Course</th>
					<th>Class</th>
					<th>Remark</th>
				</tr>
			</thead>
    		<tbody id="sortable" class="row_position">
    		<?php 
			
    		$i = 1;
			
			foreach($students as $student){
			
			?>
			
			<tr>
			
				
				<td><?php echo $student->enrollment_no; ?></td>
                <td><?php echo $student->name; ?></td>
				<td><?php echo $student->exam_status; ?></td>
				<td><?php echo $student->course_name; ?></td>
				<td><?php echo $student->class_name; ?></td>
				<td>		
                	<div style="display: inline-flex;">
						<a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/ExamController/student_remark_details/'.$student->student_id); ?>', '<?php echo 'Student Remark Details' ?>')"> <i class="text-white fas fa-pen"></i></a>  						
                	</div>
                </td>
				
			</tr>
			
			
			<?php
			
			$i++;
			} 
			?>
			</tbody>
</table>

<div id="dt">
</div>

<script>

	var $sortable = $("#mytable > tbody");
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 

function delete_menu(para1,param) 
{
	var tr_id = $(param).closest("tr").attr('data-id');
	
	if (confirm('Are you sure ?')) {
	
	var url = '<?php echo BASE_URL('admin/Admins/add_menu/delete/'); ?>'+para1;
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

$(".row_position").sortable({
        delay: -100,
        stop: function() {
            var selectedData = new Array();
         
            $(".row_position>tr").each(function() {
              
                selectedData.push($(this).attr("id"));
               
            });
            updateOrder(selectedData);
        }
    });

    function updateOrder(totalData){
        var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
    $.ajax({
    url :BASE_URL+'admin/Admins/update_menu_order',
    type:'POST' ,
    data :{allData:totalData,[csrfName]:csrfHash},
    success : function(result){
        toastr.success('Order Updated Successfully');
    }
    });
   }

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
				status: "Y",
				[csrfName]:csrfHash
			}; 
			
			var target = $(this).attr("data-target");
			var url = BASE_URL + "admin/Admins/update_menu_status";
			var response = call_ajax(data, url);
			if(response.status == true) 
			{
				
			} 
			
		}else{
			
			var data = {
				id: $(this).attr('data-id'),
				status: "N",
				[csrfName]:csrfHash
			};
			
			var target = $(this).attr("data-target");
			var url = BASE_URL + "admin/Admins/update_menu_status";
			var response = call_ajax(data, url);
			if(response.status == true) {
				
			} 
			
		}
		
	e.stopImmediatePropagation();
     return false;	
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