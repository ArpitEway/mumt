<div class="text-right mt-5">
	<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/session/create'); ?>', 'Create session')"  >Create Session</a>
</div>
<div class="container-fluid mt-3" >

	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Session</th>
				<th>Enrollment code</th>
				<th>Unpaid Permission</th>
				<th>Document Permission</th>
				<th>Enrollment Permission</th>
				<th>Exam Form Permission</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			$sessions = $this->db->get_where('session', array())->result_array();
			foreach($sessions as $session){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $session['session']; ?></td>
					<td><?php echo $session['enrollment_code']; ?></td>
					<td>
						<?php
						if($session['unpaid_permission'] == 'Y')
						{
							?>
							<input type="button" name="update_stats" data-id = "<?=$session["id"];?>" class="btn btn-success unpaid_permission_check" value="Yes">
						<?php }else{ ?>
							<input type="button" name="update_stats" data-id = "<?=$session["id"];?>"  class="btn btn-danger unpaid_permission_check" value="No">

							<?php 
						}
						?>
					</td>
					<td>
						<?php

						if($session['document_permission'] == 'Y')
						{
							?>
							<input type="button" name="update_doc_stats" data-id = "<?=$session["id"];?>" class="btn btn-success doc_permission_check" value="Yes">

						<?php }else{ ?>

							<input type="button" name="update_doc_stats" data-id = "<?=$session["id"];?> " class="btn btn-danger doc_permission_check" value="No">

							<?php 
						}	

						?>
					</td>
					<td>
						<?php

						if($session['enrollment_permission'] == 'Y')
						{

							?>
							<input type="button" name="update_enroll_stats" data-id = "<?=$session["id"];?>" class="btn btn-success enroll_permission_check" value="Yes">

						<?php }else{ ?>

							<input type="button" name="update_enroll_stats" data-id = "<?=$session["id"];?> " class="btn btn-danger enroll_permission_check" value="No">

							<?php 
						}	

						?>
					</td>
					<td>
						<?php
						if($session['exam_form_permission'] == 'Y')
						{
							?>
							<input type="button" name="update_exam_form_stats" data-id = "<?=$session["id"];?>" class="btn btn-success exam_form_permission_check" value="Yes">
						<?php }else{ ?>
							<input type="button" name="update_exam_form_stats" data-id = "<?=$session["id"];?> " class="btn btn-danger exam_form_permission_check" value="No">
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
</div>
<script>
	var showAlldepartment = function () 
	{
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();

		var url = '<?php echo site_url('admin/Admins/session'); ?>';
		$.ajax({
			type : 'GET',
			url: url,
			data: {[csrfName]: csrfHash},
			success : function(response) {

				initDataTable('basic-datatable');
			}
		});
	}


	$(document).on('click', '.unpaid_permission_check', function() {

		var val = $(this).val();
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
		var self =this;

		var status = (val=='Yes') ? 'N' : 'Y';

		var data = {
			id: $(this).attr('data-id'),
			status: status,
			[csrfName]: csrfHash,
		}; 

		var url = BASE_URL + "admin/Admins/update_session_unpaid_permission_status";

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

	$(document).on('click', '.doc_permission_check', function() {
		var val = $(this).val();
		var self =this;
		var status = (val=='Yes') ? 'N' : 'Y';
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
		var data = {
			id: $(this).attr('data-id'),
			status: status,
			[csrfName]: csrfHash,
		}; 

		var url = BASE_URL + "admin/Admins/update_doc_permission_status";

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



	$(document).on('click', '.enroll_permission_check', function() {
		var val = $(this).val();
		var self =this;
		var status = (val=='Yes') ? 'N' : 'Y';
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
		var data = {
			id: $(this).attr('data-id'),
			status: status,
			[csrfName]: csrfHash,
		}; 

		var url = BASE_URL + "admin/Admins/update_enroll_permission_status";

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

	$(document).on('click', '.exam_form_permission_check', function() {
		var val = $(this).val();
		var self =this;
		var status = (val=='Yes') ? 'N' : 'Y';
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
		var data = {
			id: $(this).attr('data-id'),
			status: status,
			[csrfName]: csrfHash,
		}; 

		var url = BASE_URL + "admin/Admins/update_exam_form_permission_status";

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