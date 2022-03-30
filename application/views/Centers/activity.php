<div class="mt-5 text-right">
	<a type="button"  class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/Centers/activity/create'); ?>', 'Create Activity')" >Create Activity</a>
</div> 
<div class=" mt-3">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Date </th>
				<th>Activity Name </th>
				<th>Discription </th>
				<th>Photos </th>
				<th>Action </th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			$center_id =  $this->session->center_id;
			
			foreach($activities as $activity){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $this->Common_model->viewDate($activity->date); ?>
					</td>
					<td><?php echo $activity->activity_name; ?></td>
					<td><?php echo $activity->description; ?></td>
					<td><a class="btn btn-primary" target="_blank" href="<?php echo site_url('assets/activity/'.$activity->photos) ?>">Image</a>
					</td>
					<td>
						<div style="display: inline-flex;">

							<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/Centers/activity/edit/'.$activity->id); ?>', '<?php echo 'Update Activity' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
							<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('center/Center/activity/delete/'.$activity->id); ?>', showAllActivity )"><i class="mdi mdi-delete delete-icon"></i></a>
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
	var showAllActivity = function () 
	{
		var url = '<?php echo site_url('Centers/activity'); ?>';
		$.ajax({
			type : 'GET',
			url: url,
			success : function(response) {		
			}
		});
	}
</script>