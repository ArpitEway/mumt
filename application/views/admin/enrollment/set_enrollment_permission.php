<form method="post" action="" class="mt-3" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Father's / Husband's Name</th>
					<th>Course Name</th>
					<th> Enrollment No</th>
					<th><input type="checkbox"  id="allEnrolled"> (Enrollment)</th>
					<th><input type="checkbox" id="all_provision">(Provisional)</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$i=1;
					foreach($students as $student){
					?>
					<tr>
						<td><?=$i++;?></td>
						<td><?=$student->name;?></td>
						<td><?=$student->f_h_name;?></td>
						<td><?=$student->course_name;?></td>
						<td><?=$student->enrollment_no;?></td>
						<td><input type="checkbox" class="enrollment_no" name="enrollment_no[]" value="<?=$student->enrollment_no;?>"></td>
						<td><input type="checkbox" class="provisions" name="student_id[]" value="<?=$student->student_id;?>"></td>
					</tr>
					<?php 
					}
				?>
			</tbody>
		</table>
	</div>
	
	<div class="text-center p-3">
		<input type="hidden" name="action" value="setPermission">
		<input type="hidden" name="action" value="setProvisional">
		<button type="submit" class="btn btn-primary btn-lg" name="submit" >Set Permission</button>
	</div>
</form>
<script>
	$(document).ready(function() {
		// Check All
		$('#allEnrolled').on('change', function() {
			if($('#allEnrolled').is(":checked")){
				$(".enrollment_no").attr("checked", "checked");
				}else{
					$(".enrollment_no").attr("checked",false );
			}
		});

		$('#all_provision').on('change', function() {
			if($('#all_provision').is(":checked")){
				$(".provisions").attr("checked", true);
				}else{
				$(".provisions").attr("checked", false);
			}
		});
	});
</script>