<div class="mt-5 text-right">
<a type="button"  class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/program/create'); ?>', 'Create Program')" >Create Program</a>
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
		<tbody>
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
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $department[0]->department_name; ?></td>
						<td><?php echo $program['program_name']; ?></td>
						<td><?php echo $program['course_type']; ?></td>
                	<td>
                	<div style="display: inline-flex;">
                		<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/program/edit/'.$program['id']); ?>', '<?php echo 'Update Program' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                		<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Inquiry/program/delete/'.$program['id']); ?>', program )"><i class="mdi mdi-delete delete-icon"></i></a>
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
var program = function () 
    {
        var url = '<?php echo site_url('admin/Inquiry/program'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                
            }
        });
    }
</script>