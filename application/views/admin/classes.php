<div class="text-right mt-3">
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/class/create'); ?>', 'Create class')"  >Create class</a>
</div>
<div class="mt-5" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
	<thead>
		<tr>
			<th>#</th>
			<th>Course name</th>
			<th>Class</th>
			<th>Mode</th> 
			<th>Options</th>
		</tr>
	</thead>
	<tbody>
    <?php
    	$i = 1;
        $classes = $this->db->get_where('class_master', array())->result_array();
        foreach($classes as $class){				
		$courses = $this->db->get_where('course_group', array('id'=>$class['course_group_id']))->row_array();
		$course_name = $courses['course_name'];				
    	?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $course_name; ?></td>
			<td><?php echo $class['class_name']; ?></td>
			<td><?php echo $class['mode']; ?></td>
			<td>
                <div style="display: inline-flex;">
					<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/class/edit/'.$class['id']); ?>', '<?php echo 'Update class' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
					<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Admins/classes/delete/'.$class['id']); ?>', showAllclass )"><i class="mdi mdi-delete delete-icon"></i></a>
				</div>
            </td>
		</tr>
				
		<?php $i++; } ?>
	
	</tbody>
</table>

</div>
<script>
var showAllclass = function () 
    {
        var url = '<?php echo site_url('admin/Admins/classes'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                initDataTable('basic-datatable');
            }
        });
    }
</script>