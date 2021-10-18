<div class="table-responsive ">
<table class="table table-hover table-striped w-100">
	<thead>
		<tr>
			<th>Form No</th>
			<th>Enrollment No</th>
			<th>Course</th>
			<th>Class</th>
			<th>Form</th>
			<th>Document</th>
			<th>Verification Status</th>
			<th>Admission Fees</th>
			<th>Program Fees</th>
			<th>Fill/View Form</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($students as $student) {
?>
		<tr>
			<td><?=$student->student_id;?></td>
			<td><?=$student->enrollment_no;?></td>
			<td><?=$student->course_name;?></td>
			<td><?=$student->class_name;?></td>
			<td><?=($student->form_status=='Y') ? 'Submited' : 'Pending';?></td>
			<td><?=($student->document_uploaded=='Y') ? 'Uploaded' : 'Pending';?></td>
			<td><?=($student->approved=='Y') ? 'Approved' : 'Pending';?></td>
			<td><?=($student->payment_status=='Y') ? 'Paid' : 'Pending';?></td>
			<td><?=($student->program_fees=='Y') ? 'Paid' : 'Pending';?></td>
			<td>
				<?php if($student->approved!='Y'){ ?>
				<a class="btn btn-sm btn-danger" href="<?=base_url('student/admission/'.$student->student_id)?>"> <i class="fas fa-pencil-alt"></i></a>
			<?php }else{
				?><a class="btn btn-sm btn-info" href="<?=base_url('student/show_form/'.$student->student_id)?>"><i class="fas fa-eye"></i></a>
			<?php } ?>
			</td>
		</tr>
<?php
		} ?>
	</tbody>
</table>
</div>