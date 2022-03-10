<div class="text-right mt-3">
	<?php if($this->session->account_type=='Admins'){ ?>
		<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/center/create/'.$center_code); ?>', 'Create center')"  >Create Center</a>
	<?php } ?>
</div>
<div class=" mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Center Code</th>
				<th>Center Name</th>
				<th>Student Count</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($centers as $center){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $center['center_code']; ?> </td>	
					<td><?php echo $center['center_name']; ?> </td>
					<td><a  href='<?php echo base_url('admin/ExamController/enrollment_permission/').$this->Common_model->encrypt_decrypt($center['center_code'],'encrypt'); ?>'><?php echo $center['count']; ?></a></td>
				</tr>
				<?php $i++; } ?>
			</tbody>
		</table>
	</div>