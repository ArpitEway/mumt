<div class="text-center">
	<table id="table" class="table table-striped dt-responsive nowrap" width="70%" >
		<thead>
			<tr>
				
				<th>S.No.</th>
				<th>Student Name</th>
				<th>Form no</th>
				<th>Course </th>
				<th>Class</th>
				<th>Detail</th>
				<th>Date</th>
				<th>Status</th>
				<th>Remark</th>
				<th>View/Action</th>

			</tr>
		</thead>
		<tbody>
			<?php 
			
			$i = 1;
			
			foreach($center_details as $center){

				$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $center["student_id"]));
				$student_id = $this->Common_model->encrypt_decrypt($center["student_id"]);
				?>

				<tr>

					<td><?php echo $i; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $center["student_id"]; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php echo $center["details"]; ?></td>
					<td><?php echo $this->Common_model->viewDate($center["date"]); ?></td>

					<td >

						<?php
						if($center['status'] == 'Done')
						{
							?>

							<input type="button" name="update_req_stats" data-id = "<?=$center["id"];?>" class="btn btn-success req_check" value="Done">

						<?php }else{ ?>

							<input type="button" name="update_req_stats" data-id = "<?=$center["id"];?> " class="btn btn-danger req_check" value="Pending">

							<?php 
						}	
						?> 

					</td>
					<td>

						<?php
						if($session['remark'] == '' || $session['remark'] != 'Invalid')
						{
							?>

							<input type="button" name="update_req_remark" data-id = "<?=$center["id"];?>" class="btn btn-success remark_check" value="Set">

						<?php }else{ ?>

							<input type="button" name="req_remark" data-id = "<?=$center["id"];?>" class="btn btn-danger remark_check" value="Invalid">

							<?php 
						}
						?>
					</td>
					<td>
						<a href="<?=base_url('admin/Account/view_student_transaction/'.$student_id);?>" target="_blank" >View Details</a>
					</td>
				</tr>


				<?php

				$i++;
			} 

			?>
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

			var url = BASE_URL + "admin/Account/update_payment_complaint_status";

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

		$(document).on('click', '.remark_check', function() 
		{

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
			var url = BASE_URL + "admin/Account/update_payment_complaint_remark";

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (data) {
					$(self).parent().prev().html(data.statusBtn);
					$(self).parent().html(data.remarkBtn);
				}
			});
		});
	</script>