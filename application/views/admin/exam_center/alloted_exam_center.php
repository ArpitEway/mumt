
<div class=" mt-3">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>ICC Name </th>
				<th>ICC Code </th>
				<th>Exam Center Name</th>
				<th>Exam Center Code</th>
				<th>EC City</th>
				<th>EC Address</th>
				<th>Options</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
            <th>#</th>
				<th>ICC Name </th>
				<th>ICC Code </th>
				<th>Exam Center Name</th>
				<th>Exam Center Code</th>
				<th>EC City</th>
				<th>EC Address</th>
				<th>Options</th>
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
      
			foreach($exam_center_alloted as $examCenter){
            ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $examCenter->center_name; ?></td>
						<td><?php echo $examCenter->center_code; ?></td>
						<td><?php echo $examCenter->schoolcollegename; ?></td>
						<td><?php echo $examCenter->examcentercode; ?></td>
						<td><?php echo $examCenter->city; ?></td>
                        <td><?php echo $examCenter->examcenteraddress; ?></td>
						
                	<td>
                	<div style="display: inline-flex;">
						<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/ExamController/alloted_exam_center/delete/'.$examCenter->id); ?>', showAllcourse )"><i class="mdi mdi-delete delete-icon"></i></a>
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
