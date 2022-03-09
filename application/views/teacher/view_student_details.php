<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
<table id="" class="table " width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<th>Roll No</th>
			<th>Enrollment No</th>
			<th> Course</th>
			<th>Class</th>
			<th>paper</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($answersheetData as $anssheet)
			{ ?>
				<tr>
					<td><?= $i; ?></td>

					<td><?= $anssheet->roll_no; ?></td>
					<td><?= $anssheet->enrollment_no; ?></td>
					<td><?= $anssheet->course_name; ?></td>
					<td><?= $anssheet->class_name; ?></td>
					<td class="col-md-2 text-center">
						<button  class="btn btn-primary btn-sm font-weight-bold student"  data-toggle="modal" data-target="#kt_datepicker_modal"  data-id="<?=$anssheet->id;?>">View Paper</button></td>
				</tr>
				<?php
				$i++; } 
				?>
			</tbody>
		</table>



<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Student Deatils</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">

			</div>
		</div>
	</div>
</div>







<script>

	$(document).ready(function(){

		$(".student").click(function(){
			var upload_exam_ans_id = $(this).attr('data-id');
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();

			$.ajax({
				type: "POST",
				url: BASE_URL+"Teacher/student_details_uplode",
				dataType:"JSON",
				data: {upload_exam_ans_id: upload_exam_ans_id, [csrfName]:csrfHash},
				success: function(response){

					$('.modal-body').html(response.data);

				},
			});
		});
	});
</script>




