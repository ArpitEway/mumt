<form method="post"  action="<?=base_url('admin/ExamController/get_center_Code_by_class');?>" class="mt-3 answersheet" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">

<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
	<thead>
		<tr>
			<th>Sno.</th>
			<th>center_code</th>
			<th>Total Papers</th>
            <th><input type="checkbox" id="allAssign_answersheet"></th>
		</tr>
	</thead>
	<tbody>      
		<?php
		$i=1;
        foreach($centers as $center_code)
		{  
 
			?>
			<tr>

				<td><?php echo $i++; ?></td>
				<td><?php echo $center_code->center_code;?></td>
				<td><?php echo $center_code->cnt;?></td>
				<td><input type="checkbox" name="center_id[]" value="<?=$center_code->center_id;?>"></td>
			</tr>
			<?php 
			}
		?>
	</tbody>
</table>
<div class="text-center p-3">
		<input type="hidden" name="action" value="assign_answersheet">
		<input type="hidden" name="teacher_id" value="<?php echo $teacher_id ; ?>">
		<input type="hidden" name="class_id" value="<?php echo $class_id ; ?>">
		<input type="hidden" name="course_group_id" value="<?php  echo $course_group_id ;  ?>">
		<input type="hidden" name="paper_code" value="<?php echo  $paper_code ; ?>">
		<button type="button" class="btn btn-primary btn-lg" id="submit" name="submit" >submit</button>
</div>
</form>
<script>
	$(document).ready(function() {
		// Check All
		$('#allAssign_answersheet').on('change', function() {
			if($('#allAssign_answersheet').is(":checked")){
				$(":checkbox").attr("checked", true);
				}else{
				$(":checkbox").attr("checked", false);
			}
		});
	});



	
</script>
