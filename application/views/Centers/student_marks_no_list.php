 <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
<div class="container-fluid mt-5 table-responsive" >
	 <table id="kt_datatable" class="table table-striped nowrap"  >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Student Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Enrollment No</th>
				<th>Roll No</th>
                <th>Session</th>
				<th>Marks Submit</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($students as $student){
				?>
				   <tr id="student_tr_<?php echo $student->student_id; ?>">  
					<td><?php echo $i ; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->roll_no; ?></td>
					<td><?php echo $student->session; ?></td>
					<td class="col-md-2 ">
						<button  class="btn btn-primary btn-sm font-weight-bold student"  data-toggle="modal" data-target="#kt_datepicker_modal"  data-id="<?=$student->student_id;?>">View</button></td>
				</tr>
				<?php 
				$i++;
			}
			?>
		</tbody>
	</table>
</div>
	<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<!-- <div class="modal-header">
			</div>  -->
			<div class="modal-body">

			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$(".student").click(function(){
			var student_id = $(this).attr('data-id');
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			$.ajax({
				type: "POST",
				url: BASE_URL+"center/center/student_details_upload",
				dataType:"JSON",
				data: {student_id: student_id, [csrfName]:csrfHash},
				success: function(response){
					$('.modal-body').html(response.data);
				},
			});
		});
	});
</script>