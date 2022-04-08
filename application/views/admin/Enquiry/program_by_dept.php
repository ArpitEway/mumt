

<div class="mt-5 text-right">
    <a type="button"  class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/program/create'); ?>', 'Create Program')" >Create Program </a>
</div> 
<div class=" mt-3">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Department Name </th>
				<th>Program Name </th>
				<th>Course Type</th>
				<th>Action </th>
			</tr>
		</thead>
		<tbody id="sortable" class="row_position">
		<?php
		$i = 1;              
        	foreach($programs as $program)
			{
				
                $this->db->select('*');
                $this->db->from('department');
                $this->db->join('program', 'program.department_id = department.id');
                $this->db->where('program.id', $program['id']); 
                $department = $this->db->get()->result(); 

            ?>
				<tr id="<?php echo $program['id']?>">
					<td><?php echo $i; ?></td>
					<td><?php echo $department[0]->department_name; ?></td>
					<td><?php echo $program['program_name']; ?></td>
					<td><?php echo $program['course_type']; ?></td>
                    <td>
                	<div style="display: inline-flex;">
                		<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/program/edit/'.$program['id']); ?>', '<?php echo 'Update Program' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                		<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Enquiry/program/delete/'.$program['id']); ?>', program )"><i class="mdi mdi-delete delete-icon"></i></a>
                	</div>	

                    </td>
				</tr>
				
			
			<?php 
			$i++;
			} ?>

			</tbody>
		    
	</table>

</div>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script>
var program = function () 
    {
        var url = '<?php echo site_url('admin/Enquiry/program'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                
            }
        });
    }


var $sortable = $("#kt_datatable > tbody");

function updateOrder(totalData){
        var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
    $.ajax({
    url :BASE_URL+'admin/Enquiry/update_program_list_order',
    type:'POST' ,
    data :{allData:totalData,[csrfName]:csrfHash},
    success : function(result){
        toastr.success('Order Updated Successfully');
    }
    });
   }

// $sortable.sortable({
// 	stop:function(event, ui){
// 		var parameters = $sortable.sortable("toArray");
// 		console.log(parameters);
// 		$.post("<?php echo BASE_URL(); ?>admin/Enquiry/update_program_list_order", {
// 		value:parameters},function(result){
// 			toastr.success(result);
// 		});
// 	}

// });


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
    url :BASE_URL+'admin/Enquiry/update_program_list_order',
    type:'POST' ,
    data :{allData:totalData,[csrfName]:csrfHash},
    success : function(result){
        toastr.success('Order Updated Successfully');
    }
    });
   }


</script>