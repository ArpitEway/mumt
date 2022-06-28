<div class="dt-responsive">
	<table id="kt_datatable"  class="table table-striped">
		<thead>
			<tr>
				<th>Sno</th>
				<th>Admission Mode</th>
				<th>Form No</th>
				<th>Enrollment No</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Remark</th>
				<th>Provisional</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($student_list as $student){ ?>
				<tr  id="student_tr_<?php echo $student->student_id; ?>">
					<td><?php echo $i; ?></td>
					<td><?php echo $student->university_mode;?></td>
					<td><?php echo $student->student_id; ?></td>
					<td><?php echo $student->enrollment_no; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $student->f_h_name; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php 
					$provisional_remark= $this->Common_model->getRecordByWhere('provisional_remark_details',array('document_category_id'=>$student->provisional_remark));
					echo  $provisional_remark[0]->provisional_remarks; ?>		
				</td>
				<td>
					<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
					<input type="hidden" class="class_id" name="class_id" value="<?= $student->class_id ; ?>">
					<button  class="btn btn-primary remark"  onclick="student_data(<?php echo $student->student_id; ?>)">Reset</button>        
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


	function student_data(student_id)
	{     

		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
		var class_id = $('.class_id').val();
		let data = {
			'student_ids':student_id,
			'class_ids':class_id,
			[csrfName]:csrfHash
		}
		Swal.fire({
			title: "Are you sure?",
			text: "Want to Set Remark ?",
			icon: "info",
			showCancelButton: true,
			confirmButtonText: "Yes"
		})
		.then(function(result) {
			 if(result.isConfirmed){ 
	
			$.ajax({
				type: "POST",
				url: BASE_URL+"admin/Enrollment/update_provisional_status",
				dataType:"json",
				data: data,
				success: function(response){
					if(response.status=='true'){
						 toastr.success('update status remark successfully');
						$('#student_tr_'+student_id).hide();
					}
					else{
						toastr.error(response.error);
					}
				}
			});
		}
		});
	}	
</script>


