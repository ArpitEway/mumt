<div class="container mb-5">
	<h3 class="text-primary text-center h2"><?= ' ( '.$studentData[0]->center_code.' ) ( '.$studentData[0]->center_name.' ) ( '.$studentData[0]->contactpersonname.' ) ( '.$studentData[0]->mobile_no_1.' , '.$studentData[0]->mobile_no_2.' ) '; ?></h3>
</div>
<div class="text-center dt-responsive" >
	<table id="kt_datatable" class="table table-striped " >
		<thead>
			<tr>
				<th>S.No.</th>
                <th>Session</th>
				<th>Form No</th>
				<th>Enrollment No</th>
				<th>Student Name</th>
                <th>Father Name</th>
                <th>Course</th>
				<th>Class</th>
				<th>Exam Form</th>
				
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($studentData as $student){
				?>
				<tr>
					<td><?php echo $i; ?></td>
                    <td><?php echo $student->session; ?></td>
					<td><?php echo $student->student_id; ?></td>
					<td><?php echo $student->enrollment_no ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->f_h_name; ?></td>
					<td><?php echo $student->course_name ; ?></td>
                    <td><?php echo $student->class_name; ?></td>
                    <td>
						<?php if($student->regular_exam_form_permission == 'Y'){ ?>
							<input type="button" name="update_req_stats" data-id = "<?=$student->student_id;?>" class="btn btn-success req_check" value="Yes">
						<?php }else{ ?>
							<input type="button" name="update_req_stats" data-id = "<?=$student->student_id;?>" class="btn btn-danger req_check" value="No">
						<?php }	?>
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

				var status = (val=='Yes') ? 'N' : 'Y';

				var data = {
					id: $(this).attr('data-id'),
					status: status,
					[csrfName]: csrfHash,
				}; 

				var url = BASE_URL + "admin/Enrollment/update_regular_form_status";

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