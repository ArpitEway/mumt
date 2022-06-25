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
					<td><a target="_blank" class="btn btn-primary" href='<?php echo base_url('/admin/enrollment/editForm/').$this->Common_model->encrypt_decrypt($center["student_id"],'encrypt'); ?>' >Edit Form</a></td>		
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
		</script>