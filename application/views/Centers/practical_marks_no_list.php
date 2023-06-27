<div>
  <div class="text-center"><label  style="color:red;">Provisional Practical Marks
</label></div>
<div class="text-center"><label ><strong>प्रविष्ट किये जा रहे निम्न अंक</strong> <label style="color:red;">Provisional Marks</label> <strong>हैं - </strong>
</label></div>
</div>
 <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="container-fluid mt-5 table-responsive" >
	 <table id="kt_datatable" class="table table-striped nowrap"  >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Roll No</th>
				<th>Enrollment No</th>
				<th>Student Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Marks Submit</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			$roll = $this->Common_model->getMaster('roll_number_col');
			foreach($students as $student){
				?>
				   <tr>
					<td><?php echo $i ; ?></td>
					<td><?php echo $student->$roll; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
                     <td class="col-md-2"   >
					<?php
					if($student->p_marks_sub=='N'){
						?>
							<button  class="btn btn-primary btn-sm font-weight-bold student" id="<?="roll_{$student->student_id}"; ?>" data-toggle="modal" data-target="#kt_datepicker_modal"  data-id="<?=$student->student_id;?>" onclick="mark_submission(<?=$student->student_id;?>)">Submission</button>

							<button style="display: none" class="btn btn-info btn-sm font-weight-bold view" id="<?="roll_num{$student->student_id}"; ?>" data-toggle="modal" data-target="#kt_datepicker_modal"  data-id="<?=$student->student_id;?>" onclick="view_mark(<?=$student->student_id;?>)">view</button>

						<?php }
						else{
							?>
							<button  class="btn btn-info btn-sm font-weight-bold view" id="<?="roll_num_{$student->student_id}"; ?>" data-toggle="modal" data-target="#kt_datepicker_modal"  data-id="<?=$student->student_id;?>" onclick="view_mark(<?=$student->student_id;?>,<?=$student->class_id; ?>)">view</button>
							
							<button style="btn btn-primary btn-sm font-weight-bold student" class="btn btn-primary btn-sm font-weight-bold view" id="<?="roll_{$student->student_id}"; ?>" data-toggle="modal" data-target="#kt_datepicker_modal"  data-id="<?=$student->student_id;?>" onclick="edit_mark(<?=$student->student_id;?>,<?=$student->class_id; ?>)">Edit</button>
							
						<?php }	?></td>

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
			<div class="modal-header">
				<h4>Student Details</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">

			</div>
		</div>
	</div>
</div>
<script>

		function mark_submission(student_id){
			var student_id = student_id;
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			$.ajax({
				type: "POST",
				url: BASE_URL+"center/center/load_student_practical_assignment",
				dataType:"JSON",
				data: {student_id: student_id,[csrfName]:csrfHash},
				success: function(response){
					$('.modal-body').html(response.data);
				},
			});
		}

		function view_mark(student_id,old_class_id){
			var student_id = student_id;
			var old_class_id = old_class_id;
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			$.ajax({
				type: "POST",
				url: BASE_URL+"center/center/view_student_marks",
				dataType:"JSON",
				data: {student_id: student_id,old_class_id: old_class_id, [csrfName]:csrfHash},
				success: function(response){
					$('.modal-body').html(response.data);
				},
			});
		}
		function edit_mark(student_id,old_class_id){
			var student_id = student_id;
			var old_class_id = old_class_id;
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			$.ajax({
				type: "POST",
				url: BASE_URL+"center/center/practical_assignment_marks_edit",
				dataType:"JSON",
				data: {student_id: student_id,old_class_id: old_class_id, [csrfName]:csrfHash},
				success: function(response){
					$('.modal-body').html(response.data);
				},
			});
		}

</script>
