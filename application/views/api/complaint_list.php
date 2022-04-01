<div class="table" id="ss" style="font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;font-size:10px;">
	<center><h5> Requests.</h5></center>
	<form name="printform" method="post" action="print.php">
		<table width="98%"  align="center" cellpadding="5" cellspacing="0" border="1">
			<tr style="font-weight:bold;">
				<td>S.No</td>
				<td>Form No</td>
				<td>Enrollment No</td>
				<td>Name</td>
				<td>Course</td>
				<td>Class</td>
				<td>Complaint Type</td>
				<td>Details</td>
				<td>Date</td>
				<td>Status</td>
				<td>Remark</td>
			</tr>
			<?php 
			$i=1;
			foreach ($center_complaints as $rows) {
				$where = array('student_id' => $rows['student_id']);
				$student = $this->Common_model->get_record('student','*',$where);
				?>

				<input type="hidden" value="<?php echo $rows["id"]; ?>" name="pid" />

				<tr>

					<td><?php echo $i;?></td>
					<td><?php echo $student[0]["student_id"];?></td>
					<td><?php echo $student[0]["enrollment_no"];?></td>
					<td><?php echo $student[0]["name"];?></td>
					<td><?php echo $student[0]["course_name"];?></td>
					<td><?php echo $student[0]["class_name"];?></td>
					<td><?php echo $rows["type"];?></td>
					<td><?php echo $rows["details"];?></td>
					<td><?php echo $rows["date"];?></td>
					<td>
						<?php if ($rows["status"]=='P'){ echo "Pending.."; }else {  echo "Done";  } ?>
					</td>
					<td><?php echo ($rows["remark"]=='invalid') ? 'Invalid' : '';
					echo ($rows["remark_detail"]=='') ? '' : ' - '.$rows["remark_detail"];
				?></td>
			</tr>
			<?php $i++;  } ?>
		</table>
	</form>
</div>