<div class="container mb-5">
	<h3 class="text-primary text-center h2"><?= ' ( '.$centerData->center_code.' ) ( '.$centerData->center_name.' ) ( '.$centerData->contactpersonname.' ) ( '.$centerData->mobile_no_1.' , '.$centerData->mobile_no_1.' ) '; ?></h3>
</div>
<div class="text-center dt-responsive" >
	<table id="table" class="table table-striped " >
		<thead>
			<tr>
				<th>S.No.</th>
				<th>Session</th>
				<th>Admission Mode</th>
				<th>Form no</th>
				<th>Student Name</th>
				<th>Detail</th>
				<th>Date</th>
				<th>Status</th>
				<th>Remark</th>
				<th>Image</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($center_details as $center){
				$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $center["student_id"]));
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $student->session; ?></td>
					<td><?php echo ($student->university_mode=='REG') ? 'Regular' : 'Private'; ?></td>
					<td><?php echo $center["student_id"]; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $center["detail"]; ?></td>
					<td><?php echo $this->Common_model->viewDate($center["date"]); ?></td>
					<td>
						<?php if($center['status'] == 'Done'){ ?>
							<input type="button" name="update_req_stats" data-id = "<?=$center["id"];?>" class="btn btn-success req_check" value="Done">
						<?php }else{ ?>
							<input type="button" name="update_req_stats" data-id = "<?=$center["id"];?> " class="btn btn-danger req_check" value="Pending">
						<?php }	?>
					</td>
					<td>
						<?php if($center['remark'] == 'Invalid'){ ?>
							<input type="button" name="req_remark" data-id = "<?=$center["id"];?>" class="btn btn-danger remark_check" value="Invalid">
						<?php }else{ ?>
							<input type="button" name="req_remark" data-id = "<?=$center["id"];?>" class="btn btn-success remark_check" value="Set">
						<?php } ?>
					</td>
					<td>
						<button  class="btn btn-sm  " onclick="delete__student_image(this)" data-id = "<?=$center['student_id']; ?>" title="Delete Image"><i class="fa fa-trash" aria-hidden="true"></i></button>  
					</td>
					<td>
						<a target="_blank"  href='<?php echo base_url('/admin/enrollment/editForm/').$this->Common_model->encrypt_decrypt($center["student_id"],'encrypt'); ?>' title="Edit Form"><i class="fa fa-edit" aria-hidden="true"></i></a>
						<button  class="btn btn-sm  " onclick="delete__student_paper(this)" data-id = "<?=$center['student_id']; ?>" title="Delete Paper"><i class="fa fa-trash" aria-hidden="true"></i></button> 
								
					</td>		
				</tr>
				<?php	$i++; } ?>
			</tbody>
		</table>
		<script>

			$(document).on('click', '.req_check', function() {

				var val = $(this).val();
				var csrfName = $('.csrfname').attr('name');
				var csrfHash = $('.csrfname').val();
				var self = this;

				var status = (val=='Done') ? 'Pending' : 'Done';

				var data = {
					id: $(this).attr('data-id'),
					status: status,
					[csrfName]: csrfHash,
				}; 

				var url = BASE_URL + "admin/Enrollment/update_form_edit_request_status";

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

			$(document).on('click', '.remark_check', function(){

				var val = $(this).val();
				var csrfName = $('.csrfname').attr('name');
				var csrfHash = $('.csrfname').val();
				var self = this;
				var remark = (val=='Set') ? 'Invalid' : 'Set';
				var data = {
					id: $(this).attr('data-id'),
					remark: remark,
					[csrfName]: csrfHash,
				};
				var url = BASE_URL + "admin/Enrollment/update_form_request_remark";
				$.ajax({
					url: url,
					type: 'POST',
					dataType: 'json',
					data: data,
					success: function (data) {
						$(self).parent().prev().html(data.sts_btn);
						$(self).parent().html(data.remark_btn);
					}
				});
			});
	function delete__student_paper(student_id)
	{
		if (confirm('Are you sure to remove  ?')) {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var student_id = $(student_id).attr('data-id');
			// alert(student_id);
			$.ajax({
				type: "POST",
				url: BASE_URL+"admin/Admins/student_paper_delete",
				dataType:"json",
				data: {student_id: student_id,[csrfName]:csrfHash},
				success: function(response){
				console.log(response);
					if(response.status=='true'){
					toastr.success("successfully Deleted all paper");
					}

					else{
					toastr.error("Something wrong");
					}
				}
			});	
		}
	}
	function delete__student_image(student_id)
	{
		if (confirm('Are you sure to remove  ?')) {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var student_id = $(student_id).attr('data-id');
			// alert(student_id);
			$.ajax({
				type: "POST",
				url: BASE_URL+"admin/Admins/student_image_delete",
				dataType:"json",
				data: {student_id: student_id,[csrfName]:csrfHash},
				success: function(response){
				console.log(response);
					if(response.status=='true'){
					toastr.success("successfully Image Deleted");
					}

					else{
					toastr.error("Something wrong");
					}
				}
			});	
		}
	}
		</script>