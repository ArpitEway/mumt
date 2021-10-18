<div class="text-right mt-3">
<?php if($this->session->account_type=='Admin'){ ?>
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/center/create/'.$center_code); ?>', 'Create center')"  >Create center</a>
<?php } ?>
</div>
<div class="container mt-5" >

	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>Id</th>
				<th>Balance</th>
				<th>center code</th>
				<th>center name</th>
				<th>Contact Person</th>
				<th>Mobile No</th>
				<th>Mobile No 2</th>
				<th>Contact Person 2</th>
				<th>Other Mobile No</th>
				<th>Other Mobile No 2</th>
				<th>Email</th>
				<th>City</th>
				<?php if($this->session->account_type=='Admin'){ ?>
				<th>Options</th>
			<?php } ?>
				<!-- <th>Action</th> -->
			</tr>
		</thead>
		<tbody>
    		<?php
    		$i = 1;
            foreach($center as $center){
    		?>
			
					<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $center['balance']; ?> /-</td>
					<td><?php echo $center['center_code']; ?> </td>	
					<td><?php echo $center['center_name']; ?> </td>
					<td><?php echo $center['contact_person']; ?> </td>
					<td><?php echo $center['mobile_no']; ?> </td>
					<td><?php echo $center['mobile_no_2']; ?> </td>
					<td><?php echo $center['contact_person_2']; ?> </td>
					<td><?php echo $center['other_mobile_no']; ?> </td>
					<td><?php echo $center['other_mobile_no_2']; ?> </td>
					<td><?php echo $center['email']; ?> </td>
					<td><?php echo $center['city']; ?> </td>
<?php if($this->session->account_type=='Admin'){ ?>
<td>				
	<div>
	<a href="javascript:void(0);" class="btn btn-primary btn-md" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/center/edit/'.$center['id']); ?>', '<?php echo 'Update center' ?>')"> <i class="fas fa-pencil-alt"></i> </a>   <!--  <a href="javascript:void(0);" class="btn btn-danger btn-md" onclick="confirmModal('<?php echo site_url('admin/Admins/centers/delete/'.$center['id']); ?>', showAllcenters )"><i class="fas fa-trash-alt"></i> </a> -->
	</div>
</td>
<?php } ?>
<!-- 						<td>
							<a href="<?php echo site_url('admin/Admins/allot_course/'.$center['id']); ?>" class="btn btn-primary btn-sm" >Add courses </a>
						</td> -->
</tr>
				
<?php $i++; } ?>

</tbody>
</table>

</div>
<script>
var showAllcenters = function () 
    {
        var url = '<?php echo site_url('admin/Admins/centers'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                initDataTable('basic-datatable');
            }
        });
    }
</script>