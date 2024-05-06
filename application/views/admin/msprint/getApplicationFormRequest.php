<div class="container mb-5">
	<h3 class="text-primary text-center h2"><?= ' ( '.$centerData->center_code.' ) ( '.$centerData->center_name.' ) ( '.$centerData->contactpersonname.' ) ( '.$centerData->mobile_no_1.' , '.$centerData->mobile_no_1.' ) '; ?></h3>
</div>
<div class="text-center">
	<table id="table" class="table table-striped dt-responsive nowrap" width="70%" >
		<thead>
			<tr>
				
				<th>S.No.</th>
				<th>Form no</th>
				<th>Student Name</th>
				<th>Course </th>
				<th>Class</th>
				<th>Mode</th>
				<th>Payment Status</th>
				<th>Detail</th>
				<th>Date</th>
				<!-- <th>Status</th> -->
                <th>Action</th>
                <th>Downlod</th>
			
				

			</tr>
		</thead>
		<tbody>
			<?php 
			
			$i = 1;
			
			foreach($complaints as $complaint){

				$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $complaint["student_uid"]));
				$student_id = $this->Common_model->encrypt_decrypt($complaint["student_id"]);
				$payment = $this->Common_model->getSingleRow("online_payment_transaction",'*',array("student_id" =>$complaint["student_uid"] ,"payment"=>"Y"));
				?>

				<tr>

					<td><?php echo $i; ?></td>
					<td><?php echo $complaint["student_uid"]; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php if($student->university_mode=='REG') echo "Regular"; else echo "Private"; ?></td>
					<td><?php  if($complaint["payment"]=='Y') echo "<span style='color:green;'>Paid</span>"; else echo "<span style='color:red;'>Unpaid</span>"; ?></td>
					<td><?php echo $complaint["apply_for"]; ?></td>
					<td><?php echo $this->Common_model->viewDate($payment->payment_date); ?></td>

					<!-- <td > -->
							
						<?php
					/*	if($complaint['status'] == 'Done')
						{
							?>

							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?>" class="btn btn-success req_check" value="Done">

						<?php }else{ ?>
							
							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?> " class="btn btn-danger req_check" value="Pending">

							<?php 
						}	*/
						?> 

					<!-- </td> -->
				
					<td><a href="<?= base_url('MsPrint/view_application/'.$complaint['id'].'')?>" class="text-primary"  ><i class="fas fa-solid fa-eye"></i></a></td>
                    <td><?php if($student->course_complete == "Y"){?><a href="<?= base_url( 'MsPrint/degree_view/'.$student->student_id.'')?>" target="_blank" data-title="Click to see Degree View & Download it!"> Degree</a><?php }?></td>
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

			var url = BASE_URL + "admin/msprint/update_application_form_status";

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
		$(document).on('change', '.select_remark_check', function() 
		{

			var val = $(this).val();
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			var self = this;

			//var remark = (val=='Set') ? 'Invalid' : 'Set';
			var remark =val;
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
					//$(self).parent().html(data.remarkBtn);
				}
			});
		});
	</script>