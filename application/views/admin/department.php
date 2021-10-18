<div class="text-right mt-5">
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/department/create'); ?>', 'Create department')"  >Create department</a>
</div>
<div class="container mt-3" >


	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Options</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
        $departments = $this->db->get_where('department', array())->result_array();
        foreach($departments as $department){
            ?>
			
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $department['name']; ?></td>
						
                	<td>
                			
                				<div style="display: inline-flex;">
                					<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/department/edit/'.$department['id']); ?>', '<?php echo 'Update department' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                			         <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Admins/department/delete/'.$department['id']); ?>', showAlldepartment )"><i class="mdi mdi-delete delete-icon"></i></a>
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
var showAlldepartment = function () 
    {
        var url = '<?php echo site_url('admin/Admins/department'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                initDataTable('basic-datatable');
            }
        });
    }
</script>