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
		
	<table class="table border">
		<thead>
			<tr>
				<th>#</th>
				<th>Paper Name</th>
				<th>Paper Type</th>
				<?php if ($student[0]->mode=='REG'): ?>
					<th>Theory/Practical/Project Marks</th>
					<th>Internal Marks</th>
				<?php else: ?>
					<th>Theory Marks</th>
				<?php endif ?>
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
					</td><?php
					if($papers['max_internal_marks'] !=0){?>
					<td class="fit">
						<select name="int_marks[]" class="form-control select2">
							<option value="N">Select Marks</option>
							<option <?=($papers['int_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
							
							<?php
							
							for ($i=$papers['min_internal_marks']; $i<=$papers['max_internal_marks']; $i++){
								$selected =  ($papers['int_marks']==$i && $papers['int_marks']!="ABS") ? 'selected="selected"' : '';
								$selected = ($papers['int_marks']=='' || $papers['int_marks']=='N') ? '' : $selected;
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
								
						</select>
					</td>
					<?php }else{ ?> 
						<td class="fit">
							<select name="int_marks[]" class="form-control select2" readonly>
								<option value="N" selected>N</option>
								
							</select>
						</td>
						
						<?php }?>
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
				<?php }elseif($papers['type']=='Project' || $papers['type'] == 'project'){ ?>
					<td>
						<input type="hidden" name="pro_marks_paper_code[]" value="<?= $papers['paper_code'];?>">
						<select name="pro_marks[]" class="form-control select2">
							<option value="">Select Marks</option>
							<option <?=($papers['p_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
								<?php
								for ($i=$papers['min_theory_marks']; $i<=$papers['max_theory_marks']; $i++){
									$selected =  ($papers['p_marks']==$i && $papers['p_marks']!="ABS") ? 'selected' : '';
									?>
									<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
								<?php } ?>
								
							</select>
					</td>
							<?php if($papers['practical_internal_marks'] == 'Y' && $papers['max_internal_marks'] !=0){?>
					<td>
						<select name="proj_int_marks[]" class="form-control select2">
						<option value="">Select Marks</option>
						<option <?=($papers['p_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
							<?php
							for ($i=$papers['min_internal_marks']; $i<=$papers['max_internal_marks']; $i++){
								$selected =  ($papers['int_marks']==$i && $papers['int_marks']!="ABS") ? 'selected' : '';
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
						
						</select>
						</td>
						<?php }
						else{ ?> 
							<td class="fit">
							<select name="proj_int_marks[]" class="form-control select2" readonly>
								<option value="N" selected>N</option>
								
							</select>
					</td>
							
							<?php }?>
							<?php }elseif($papers['type']=='Practical' || $papers['type']=='practical'){
							?>
					<td>
					<input type="hidden" name="p_marks_paper_code[]" value="<?= $papers['paper_code'];?>">
					<select name="p_marks[]" class="form-control select2">
						<option value="N">Select Marks</option>
						<option <?=($papers['p_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
							<?php
							for ($i=$papers['min_theory_marks']; $i<=$papers['max_theory_marks']; $i++){
								$selected =  ($papers['p_marks']==$i && $papers['p_marks']!="ABS") ? 'selected' : '';
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
							
						</select>
						</td>
						<?php if($papers['practical_internal_marks'] == 'Y'){?>
						<td class="fit">
						<select name="paper_int_marks[]" class="form-control select2">
							<option value="N">Select Marks</option>
							<option <?=($papers['int_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
							<?php
							for ($i=$papers['min_internal_marks']; $i<=$papers['max_internal_marks']; $i++){
								$selected =  ($papers['int_marks']==$i && $papers['int_marks']!="ABS") ? 'selected="selected"' : '';
								$selected = ($papers['int_marks']=='' || $papers['int_marks']=='N') ? '' : $selected;
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
								
						</select>
					</td>
				<?php } else{ ?> 
						<td class="fit">
						<select name="paper_int_marks[]" class="form-control select2" readonly>
							<option value="N" selected>N</option>
							
						</select>
					</td>
						
						<?php }
			
			} elseif ($papers['type']=='Sessional') {?>
					
					<td colspan="2">
					<input type="hidden" name="s_marks_paper_code[]" value="<?= $papers['paper_code'];?>">
					<select name="sessional_marks[]" class="form-control select2">
						<option value="">Select Marks</option>
						<option <?=($papers['p_marks']==="ABS") ? 'selected="selected"' : ''; ?> value="ABS">ABS</option>
							<?php

							for ($i= 0; $i<=$papers['max_internal_marks']; $i++){
								$selected =  ($papers['int_marks']==$i && $papers['p_marks']!="ABS") ? 'selected' : '';
								?>
								<option <?=$selected ?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
							<?php } ?>
						
						</select>
						</td>
				<?php } ?>
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