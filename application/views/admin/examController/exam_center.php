<div class="mt-5 text-right">
<a type="button"  class="btn btn-outline-primary btn-rounded" href="<?= base_url(); ?>ExamController/allot_exam_center" >Allote Exam Center</a>
<a type="button"  class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/exam_center/create'); ?>', 'Create Exam Center')" >Create Exam Center</a>
</div> 
<div class=" mt-3">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Exam Center Code </th>
				<th>School/College Name </th>
				<th>Exam Center Address</th>
				<th>City</th>
				<th>Superintendent</th>
				<th>Phone Number</th>
				<th>Bank Account Number</th>
				<th>Center Supervisor Name</th>
				<th>Center Supervisor Number-1</th>
				<th>Center Supervisor Number-2</th>
				<th>Options</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>#</th>
				<th>Exam Center Code </th>
				<th>School/College Name </th>
				<th>Exam Center Address</th>
				<th>City</th>
				<th>Superintendent</th>
				<th>Phone Number</th>
				<th>Bank Account Number</th>
				<th>Center Supervisor Name</th>
				<th>Center Supervisor Number-1</th>
				<th>Center Supervisor Number-2</th>
				<th>Options</th>
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
      
			$examCenters = $this->db->get_where('exam_center', array())->result_array();
			foreach($examCenters as $examCenter){
            ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $examCenter['examcentercode']; ?></td>
						<td><?php echo $examCenter['schoolcollegename']; ?></td>
						<td><?php echo $examCenter['examcenteraddress']; ?></td>
						<td><?php echo $examCenter['city']; ?></td>
						<td><?php echo $examCenter['superintendent']; ?></td>
						<td><?php echo $examCenter['phonenumber']; ?></td>
						<td><?php echo $examCenter['bankaccountnumber']; ?></td>
						<td><?php echo $examCenter['exam_fees']; ?></td>
						<td><?php echo $examCenter['csnumber_1']; ?></td>
						<td><?php echo $examCenter['csnumber_2']; ?></td>
                	<td>
                	<div style="display: inline-flex;">
					
                		<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/exam_center/edit/'.$examCenter['id']); ?>', '<?php echo 'Update course' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                		<!-- <a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/ExamController/exam_center/delete/'.$examCenter['id']); ?>', showAllcourse )"><i class="mdi mdi-delete delete-icon"></i></a> -->
                	</div>	

                    </td>
					</tr>
				
			
			<?php $i++; }
			
			 ?>
			</tbody>
		    
	</table>

</div>
<script>
var showAllcourse = function () 
    {
        var url = '<?php echo site_url('admin/Admins/course'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                
            }
        });
    }
</script>
