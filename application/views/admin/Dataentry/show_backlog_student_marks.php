<style type="text/css">
	.fit{
		border: none;
		background-color: transparent;
		outline: none;
		padding: 0;
		width:300px;
	}
	.bg{
		background-color: #781e19;
	}
</style>
<div class="border border-radius p-3 mb-10">
	<div class="row   mb-4">

		<div class="col-md-4">
			<strong for="">Roll Number :</strong>
			<?=$student[0]->roll_no ?>
		</div>
		<div class="col-md-4">
			<strong for="">Enrollment Number :</strong>
			<?=$student[0]->enrollment_no ?>
		</div>
		<div class="col-md-4">
			<strong for="">Student Name :</strong>
			<?=$student[0]->name ?>
		</div>
	</div> 
	<div class="row mb-4">
		<div class="col-md-4">
			<strong for="">Course Name :</strong>
			<?=$student[0]->course_name ?>
		</div>  
		<div class="col-md-4">
			<strong for="">Class Name :</strong>
			<?= $this->Common_model->getClassNameByClassId($student[0]->class_id) ?>
		</div>
		<div class="col-md-4">
			<strong for="">IC Code :</strong>
			<?= $student[0]->center_code ?>
		</div>
	</div>
</div>
<div>
	<table class="table border table-striped">
		<thead class="bg text-white">
			<tr>
				<th>#</th>
				<th>Paper Name</th>
				<th>Paper Type</th>
				<?php if ($student[0]->mode=='REG'): ?>
					<th>Theory Marks</th>
					<th>Internal Marks</th>
				<?php else: ?>
					<th>Theory Marks</th>
				<?php endif ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($studentPaper as $key => $papers):?>
				<tr>
					<td><?=$key+1; ?></td>
					<td><?= $papers['paper_code']."-".$papers['paper_name']; ?></td>
					<td><?=$papers['type'] ?></td>
					<?php if ($papers['type']=='theory') { ?>
						<?php if ($student[0]->mode=='REG'): ?>
							<td class="fit">
								<?=$papers['theory_marks']?> <?php if($papers['status']=="C") echo " ".$papers['status']; ?>
							</td>
							<?php if($papers['max_internal_marks'] == 0){?>

								<td class="fit">
									<?= "-"; ?>
								</td>
							<?php }else{
								?> 
								<td class="fit">
									<?= $papers['int_marks']; ?> C
									</td><?php
								} ?>
							<?php else: ?>
								<td class="fit">
									<?=$papers['theory_marks']; ?> <?php if($papers['status']=="C") echo " ".$papers['status']; ?>
								</td>
							<?php endif ?>
						<?php }elseif($papers['type']='Practical' || $papers['type']='practical'){ ?>
							<?php if($papers['max_theory_marks'] == 0){?>

								<td class="fit">
									<?= "-"; ?>
								</td>
							<?php }else{ ?>
								<td >
									<?= $papers['p_marks'] ?> <?php if($papers['status']=="C") echo " ".$papers['status']; ?>
								</td>
							<?php } 
							?>


							<?php if($papers['max_internal_marks'] == 0){?>

								<td class="fit">
									<?= "-"; ?>
								</td>
							<?php }else{
								?> 
								<td class="fit">
									<?= $papers['int_marks']; ?> C
									</td><?php
								}

							} ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
	</div>