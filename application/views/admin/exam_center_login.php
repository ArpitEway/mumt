<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped">
			<thead>
				<tr>
					<th>Sno.</th>
					<th>Exam Center Code</th>
					<th>Exam Center Name</th>
					<th>Contact Person</th>
					<th>Mobile No</th>
				    <th>Login</th>
				</tr>
			</thead>
    		<tbody id="sortable">
    		<?php 	
    		$i = 1;	
			foreach($exam_centers as $exam_center){       
			?>	
			<tr>
				<td><?php echo $i; ?></td>	
				<td><?php echo $exam_center['examcentercode']; ?></td>
				<td><?php echo $exam_center['schoolcollegename']; ?></td>
				<td><?php echo $exam_center['superintendent']; ?></td>
				<td><?php echo $exam_center['phonenumber']; ?></td>
                <div style="display: inline-flex;">					
						<td>
							<button  class="btn btn-danger login"  data-user = "<?=$exam_center['id']; ?>">Login</button>
						</td>
					</div>
			</tr>	
			<?php
			$i++;
			} 
			?>
			</tbody>
    </table>
    <script>
    $(document).ready(function(){
    $(".login").click(function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
    var id = $(this).attr('data-user');
    $.ajax({
        type: "POST",
        url: BASE_URL+"admin/Admins/exam_center_login_sub",
        dataType:"json",
        data: {id: id,[csrfName]:csrfHash},
        success: function(response){
        console.log(response);
            if(response.status=='true'){
                var link = BASE_URL+"Examcenter/dashboard";      
                window.open(link, '_blank').focus();
            }else{
                toastr.error(response.error);
            }
        }
    });
});
});
</script>