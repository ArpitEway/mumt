<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="container mt-5">
<div class="text-right py-3">
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/paper/create'); ?>', 'Create paper')"  >Create paper</a>
</div>

	

	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
					<th>Sno</th>
					<th>Course</th>
					<th>Class</th>
					<th>Paper</th>
					<th>Paper Code</th>
					<th>Type</th>
					<th>CE</th> 					
					<!--<th>Options</th>-->
				</tr>
			</thead>
			<tbody>
    		<?php
    		
    		$i = 1;
			
            $papers = $this->db->get_where('paper_master', array())->result_array();
            
			foreach($papers as $paper){
				
			$courses = $this->db->get_where('course_group', array('id' => $paper['course_group_id']))->row_array();
		 	$classes = $this->db->get_where('class_master', array('id' => $paper['class_id']))->row_array();
			$course_name = $courses['course_name'];
			$class_name  = $classes['class_name'];
				
    		?>
					<tr>
						<td><?php echo $i; ?></td>
						
						<td><?php echo $course_name; ?></td>
						<td><?php echo $class_name; ?></td>
						<td><?php echo $paper['paper_name']; ?></td>
						<td><?php echo $paper['paper_code']; ?></td>

						<td><?php echo $paper['type']; ?></td>
						<td><?php echo $paper['ce']; ?></td>
						
                	    <!--<td>
                			<div style="display: inline-flex;">
								<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/paper/edit/'.$paper['id']); ?>', '<?php echo 'Update class' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
								<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Admins/paper/delete/'.$paper['id']); ?>', showAllpaper )"><i class="mdi mdi-delete delete-icon"></i></a>
							</div>
                        </td>-->
						
					</tr>
				
			
			
			<?php $i++; } ?>
			</tbody>
	</table>

</div>
<script>
var showAllpaper = function () 
    {
        var url = '<?php echo site_url('admin/Admins/paper'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
               // initDataTable('basic-datatable');
            }
        });
    }
</script>