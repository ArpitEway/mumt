<style type="text/css">
	.fit{
	border: none;
	background-color: transparent;
	outline: none;
	padding: 0;
	width:300px;
	}
</style>
<div class="border border-radius p-3 mb-10">
<div class="row   mb-4">
    <div class="col-md-3">
		<strong for="">Roll Number :</strong>
			<?=$student->roll_no ?>
	</div>
    <div class="col-md-3">
		<strong for="">Enrollment Number :</strong>
			<?=$student->enrollment_no ?>
	</div>
	<div class="col-md-3">
		<strong for="">Student Name :</strong>
			<?=$student->name ?>
	</div>
	
	<div class="col-md-3">
		<strong for="">Course Name :</strong>
			<?=$student->course_name ?>
	</div>
</div> 
<div class="row mb-4">   
	<div class="col-md-3">
		<strong for="">Class Name :</strong>
			<?= $this->Common_model->getClassNameByClassId($student->old_class_id) ?>
	</div>
    <div class="col-md-3">
		<strong for="">IC Code :</strong>
			<?= $student->center_code ?>
	</div>
    <div class="col-md-3">
		<strong for="">Session :</strong>
			<?= $student->session; ?>
	</div>
    <div class="col-md-3">
		<strong for="">Result :</strong>
			<?= $student->result_show ?>
	</div>
</div>
<div class="row  mb-4">
    <div class="col-md-3">
		<strong for="">Marksheet :</strong>
			<?= $student->result_show ?>
	</div>
	
</div>
</div>
<div>

	<table class="table border table-striped">
		<thead class="bg-info text-white">
			<tr>
				<th>#</th>
				<th>Paper Name</th>
				<th>Paper Type</th>
				<?php if ($student->mode=='regular'): ?>
					<th>Theory Marks</th>
					<th>Internal Marks</th>
				<?php else: ?>
					<th>Theory Marks</th>
				<?php endif ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($studentPaper as $key => $papers): ?>
				<tr>
					<td><?=$key+1; ?></td>
					<td><?=$papers['paper_name'] ?></td>
					<td><?=$papers['type'] ?></td>
					<?php if ($papers['type']=='theory') { ?>
					<?php if ($student->mode=='regular'): ?>
					<td class="fit">
							<?=$papers['theory_marks']?>
					</td>
					<td class="fit">
						<?= $papers['int_marks']; ?>
					</td>
					<?php else: ?>
						<td class="fit">
						<?=$papers['theory_marks']; ?>
						</td>
					<?php endif ?>
				<?php }elseif($papers['type']='practical'){ ?>
					<td colspan="2">
						<?= $papers['p_marks'] ?>
					</td>
				<?php } ?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</form>
</div>