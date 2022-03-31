<div class="mt-5 text-right">
<a type="button"  class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/department/create'); ?>', 'Create Department')" >Create Department</a>
</div> 
<div class=" mt-3">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Name </th>
				<th>Action </th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;              
        	foreach($departments as $department)
			{
		
			
            ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $department['department_name']; ?></td>
                	<td>
                	<div style="display: inline-flex;">
                		<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/department/edit/'.$department['id']); ?>', '<?php echo 'Update Department' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                		<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Inquiry/department/delete/'.$department['id']); ?>', department )"><i class="mdi mdi-delete delete-icon"></i></a>
                	</div>	

                    </td>
					</tr>
				
			
			<?php 
			$i++;
			} ?>
			</tbody>
		    
	</table>

</div>
<script>
var department = function () 
    {
        var url = '<?php echo site_url('admin/Inquiry/department'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                
            }
        });
    }
</script>