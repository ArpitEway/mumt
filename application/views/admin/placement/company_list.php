<div class="container" style="margin-top:30px;">
<div style="text-align:right;margin:10px">
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/placement/add_company'); ?>', 'Add Company')">Add Company</a>
</div>
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>

					<th>Sno</th>
					<th>Company Name</th>
					<th>Description</th>
					<th>Job Titles</th> 					
					<th>Minimum Qualification</th>
                    <th>Other Detail</th>
					<th>Action</th>

				</tr>
			</thead>
    		<?php
    		
    		$i = 1;
			
            $companies = $this->db->get_where('company', array())->result_array();
            
			foreach($companies as $company){
    		?>
			<tbody>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $company['company_name']; ?></td>
						<td><?php echo $company['description']; ?></td>
						<td><?php echo $company['job_title']; ?></td>
						<td><?php echo $company['min_qualification']; ?></td>
						<td><?php echo $company['other_detail']; ?></td>
						
                	    <td>
                			<div style="display: inline-flex;">
								<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/placement/edit/'.$company['id']); ?>', '<?php echo 'Update comapany' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
								<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Admins/placement/delete/'.$company['id']); ?>', showAllpaper )"><i class="mdi mdi-delete delete-icon"></i></a>
							</div>
                        </td>
					</tr>
				
			</tbody>
			
			
			<?php $i++; } ?>
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
                
                initDataTable('basic-datatable');
            }
        });
    }
</script>