<div class="container mb-5">
	<h3 class="text-primary text-center h2"><?= ' ( '.$centerData->center_code.' ) ( '.$centerData->center_name.' ) ( '.$centerData->contactpersonname.' ) ( '.$centerData->mobile_no_1.' , '.$centerData->mobile_no_1.' ) '; ?></h3>
</div>
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
				

			</tr>
		</thead>
		<tbody>
			<?php 
			
			$i = 1;
			
			foreach($complaints as $complaint){

				$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $complaint["student_id"]));
				$student_id = $this->Common_model->encrypt_decrypt($complaint["student_id"]);
				?>

				<tr>

					<td><?php echo $i; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $complaint["student_id"]; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php echo $complaint["details"]; ?></td>
					<td><?php echo $this->Common_model->viewDate($complaint["date"]); ?></td>

					<td >

						<?php
						if($complaint['status'] == 'Done')
						{
							?>

							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?>" class="btn btn-success req_check" value="Done">

						<?php }else{ ?>

							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?> " class="btn btn-danger req_check" value="Pending">

							<?php 
						}	
						?> 

					</td>
					<td>

						<?php
						if($session['remark'] == '' || $session['remark'] != 'Invalid')
						{
							?>

							<input type="button" name="update_req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-success remark_check" value="Set">

						<?php }else{ ?>

							<input type="button" name="req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-danger remark_check" value="Invalid">

							<?php 
						}
						?>
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

			var url = BASE_URL + "admin/admins/update_mode_change_complaint_status";

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
			var url = BASE_URL + "admin/admins/update_mode_change_complaint_remark";

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