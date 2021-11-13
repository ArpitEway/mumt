<table id="table" class="table" >
	<thead>
		<tr>

			<!-- <th>S.No.</th> -->
			<th>Session</th>
			<th>Form no</th>	
			<th>Student Name</th>
			<th>Course </th>
			<th>Class</th>

		</tr>
	</thead>
	<tbody>
		<?php 

		$i = 1;

		foreach($students as $student){

			?>
			
			<tr>
				<!-- <td><?php echo $i; ?></td> -->
				<td><?php echo $student["session"]; ?></td>
				<td><?php echo $student["student_id"]; ?></td>
				<td><?php echo $student["name"]; ?></td>
				<td><?php echo $student["course_name"]; ?></td>
				<td><?php echo $student["class_name"]; ?></td>
				
			</tr>
			
			
			<?php
			$student_id = $student["student_id"];
			$i++;
		} 

		?>
	</tbody>
</table>
<form method="POST" class="d-block ajaxForm mt-15">
		<div class="form-group text-center">
			<label class="h3 font-weight-bold mt-5">Details</label>
			<div class="mt-5 m-auto ">
				<textarea type="text" rows="8" name="detail" id="detail" class="form-control form-control-lg form-control-solid message_detail" ></textarea>
				<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
				<input type="hidden" name="student_id" id="student_id" value="<?=$student_id?>">
			</div>
		</div>
        <div class="form-group col-md-12 text-center">
            <label for="class"></label>
            <button type="button" class="btn btn-lg btn-custom mt-4 col-sm-3" style="margin-top: 24px !important;" id="submit">Submit</button>
        </div>
</form>