<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>
<div class="text-right mt-3">
<?php if($this->session->account_type=='Admin'){ ?>
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/center/create/'.$center_code); ?>', 'Create center')"  >Create Center</a>
<?php } ?>
</div>
<div class="container mt-5" >

			<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>#</th>
						<th>Center Code</th>
						<th>Center Name</th>
						<th>Contact Person</th>
						<th>Mobile No</th>
						<th>Other Mobile No</th>
						<th>Email</th>

						<th>Status</th>
						
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
					<td><?php echo $center['center_code']; ?> </td>	
					<td><?php echo $center['center_name']; ?> </td>
					<td><?php echo $center['contactpersonname']; ?> </td>
					<td><?php echo $center['mobile_no_1']; ?> </td>
					<td><?php echo $center['mobile_no_2']; ?> </td>
					<td><?php echo $center['email']; ?> </td>
					<td>

				<?php

				if($session['status'] == 'Y')
				{

				?>
				<input type="button" name="update_center_stats" data-id = <?=$center["id"];?> class="btn btn-success center_status_check" value="Yes">
				
				<?php }else{ ?>

				<input type="button" name="update_center_stats" data-id = <?=$center["id"];?>  class="btn btn-danger center_status_check" value="No">
				
				<?php 
				}	

				?>
					</td>
					

<?php if($this->session->account_type=='Admin'){ ?>
<td>				
	<div>
	<a href="javascript:void(0);" class="btn btn-primary btn-md" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/center/edit/'.$center['id']); ?>', '<?php echo 'Update center' ?>')"> <i class="fas fa-pencil-alt"></i> </a>   <!--  <a href="javascript:void(0);" class="btn btn-danger btn-md" onclick="confirmModal('<?php echo site_url('admin/Admins/centers/delete/'.$center['id']); ?>', showAllcenters )"><i class="fas fa-trash-alt"></i> </a> -->
	</div>
</td>
<?php } ?>
<!--<td>
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



$(document).on('click', '.center_status_check', function() {

var val = $(this).val();

var self =this;

var status = (val=='Yes') ? 'N' : 'Y';

var data = {
		  id: $(this).attr('data-id'),
		  status: status
	  }; 
	  
var url = BASE_URL + "admin/Admins/update_center_status";

$.ajax({
  url: url,
  type: 'POST',
  dataType: 'json',
  data: data,
  success: function (data) {
	  
	  $(self).parent().html(data.data);
	  
  }
});

});

</script>