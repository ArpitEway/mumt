<style type="text/css">
	.fit{
	border: none;
	background-color: transparent;
	outline: none;
	padding: 0;
	width:300px;
	}
</style>
<div class="border radius-rounded p-3 mb-10">
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
	<form class="mt-4 shadow">
		<input type="hidden" name="student_id" value="<?=$student[0]->student_id?>">
		<input type="hidden" name="class_id" value="<?=$student[0]->class_id?>">
		<input type="hidden" name="backlog_student_id" value="<?=$student[0]->backlog_student_id?>">
		
	<table class="table border">
		<thead>
			<tr>
				<th>#</th>
				<th>Paper Name</th>
				<th>Paper Type</th>
				<th>Theory Marks</th>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach ($studentPaper as $key => $papers):
				// print_r($papers);die;
				 ?>
				<tr>
					<td><?=$key+1; ?></td>
					<td><?= $papers['paper_code']."-".$papers['paper_name']; ?></td>
					<td><?=ucwords($papers['type']) ?></td>
					<?php if ($papers['type']=='theory') { ?>
					<?php if ($student[0]->mode=='REG'): ?>
					<td class="fit">
					<!-- <?php	//echo $papers['theory_marks'];die; ?> -->
						<input type="hidden" name="paper_code[]" value="<?= $papers['paper_code'];?>">
						<select name="theory_marks[]" class="form-control select2">
							<option value="">Select Marks</option>
							<option <?=($papers['theory_marks']=="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
							<?php
							for ($i=0; $i<=$papers['max_theory_marks']; $i++){
								$selected =  ($papers['theory_marks']==$i && $papers['theory_marks']!="ABS") ? 'selected="selected"' : '';
								$selected = ($papers['theory_marks']=='') ? '' : $selected;
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
							
						</select>
					</td>
					
					<?php  else: ?>
						<td class="fit">
							<input type="hidden" name="paper_code[]" value="<?= $papers['paper_code'];?>">
							<select name="theory_marks[]" class="form-control select2">
								<option value="">Select Marks</option>
								<option <?=($papers['theory_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
								<?php
								for ($i=0; $i<=$papers['private_max_theory_marks']; $i++){
									$selected =  ($papers['theory_marks']==$i && $papers['theory_marks']!="ABS") ? 'selected' : '';
									$selected = ($papers['theory_marks']=='') ? '' : $selected;
									?>
									<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
								<?php } ?>
								
							</select>
						</td>
					<?php endif ?>
				<?php }?>
			</tr>
			<?php endforeach ?>
			<tr>
				<td colspan="5" class="text-center"><button type="button" name="submit" id="submit" class="btn-primary btn">Update</button></td>
			</tr>
		</tbody>
	</table>
</form>
</div>
<?php 
    $url = (isset($wh) && $wh==true) ? 'search_student_result_for_wh' : 'search_student_backlog_result';
?>
<script type="text/javascript">
	$('.select2').select2();
	$(document).on('click','#submit',function(event){
		    event.stopImmediatePropagation();
		var  serialized = $('form').serialize();
		$.ajax({
			url: BASE_URL+"admin/<?=$this->session->account_type;?>/edit_backlog_student_marks_sub",
			type: 'POST',
			dataType : 'json',
			data: serialized ,
			success: function (data) {
				if(data.success){
					toastr.success(data.success);
					setTimeout(function(){location.href=BASE_URL+'<?=$this->session->account_type.'/'.$url;?>'} , 2000);
				}else{
					toastr.error(data.error);
				}
			},
		});
	});
</script>