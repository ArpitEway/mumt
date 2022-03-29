<form method="post" action="complaint_sub.php" method="post">

	<input type="hidden" value="<?=$student_data["student_id"] ?>" name="sid" id="sid"/>

	<input type="hidden" value="<?=$student_data["enrollment_no"] ?>" name="en" id="en"/>

	<input type="hidden" value="<?=$_SESSION["center_id"] ?>" name="center" id="center"/>

	<input type="hidden" value="<?=$type ?>" name="type" id="complaint_type"/>

	<p align="center"><span style="font-size: 24px; font-family: Calibri">Student Detail</span></p>

	<table align="center" style="width:75%;" class="table table-hover table-striped">

		<tr style="font-weight:bold;">

			<td>S.No</td><td>Session</td><td>Form No</td><td>Enrollment No</td><td>Name</td><td>Course</td><td>Class</td><td>Complaint Type</td>

		</tr>

		$i=1;
		$data .= '<tr>
			<td><?= $i ?></td>
		<td><?= $student_data["session"] ?></td>
		<td><?= $student_data["student_id"] ?></td>
		<td><?= $student_data["enrollment_no"] ?></td>
		<td><?= $student_data["name"] ?></td>
		<td><?= $student_data["course_name"] ?></td>
		<td><?= $student_data["class_name"] ?></td>
		<td><?= $type ?></td>
		</tr>
		<tr>
			<td colspan="8" style="text-align:center !important">
				<h4>Details</h4><br><textarea  value="" name="details" id="details" required="required"/>
			</textarea>
		</td>
		<tr>
			<td colspan="8" style="text-align:center !important"><input type="button" value=" Submit" name="submit" id="submit" class="btn btn-warning" /></td>
		</tr>
	</table>
</form>