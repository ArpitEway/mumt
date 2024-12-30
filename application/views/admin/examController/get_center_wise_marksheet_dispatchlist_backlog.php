<style>
.break{
page-break-before: always;
}
@page {
    	size: auto;
    }
</style><?php 
$pageno=1;
$perpagecount=30;

foreach($centers as $center)  {
	$this->db->select('backlog_student.*,student.name,student.course_name');
	$this->db->from('backlog_student');
    $this->db->join('student','student.student_id=backlog_student.student_id');
	$this->db->order_by("backlog_student.roll_no", "asc");
	$where = array('backlog_student.center_id'=>$center->id, 'backlog_student.roll_no!=' => 0 ,'backlog_student.exam_form'=>'Y','backlog_student.exam_year'=>'June 2024');//,'marksheet_dispatch' =>'N'
	$this->db->where($where);	
	$center_students = $this->db->get()->result();
	if($center_students){
		$totalStudent=count($center_students);
		$studentCount=0;
		$sno=0;
		foreach($center_students as $student)  { 
			if($sno==0) { ?>
			<p class="break" style="font-size: 16px;"></p>
			<p align="center" style="margin-top:10px;line-height:15px;font-size:15px;"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya </b></p>
			<p align="center" style="line-height:12px;font-size:14px;"><b>Backlog List of Roll No. Exam <?=$examTitle?></b></p>
			<p align="center" style="line-height:12px;font-size:12px;"><b><i>Center : <?php echo $center->center_code;?></i></b></p>
			<p align="right">Page No :<?php echo $pageno++; ?></p>
			<table align="center" cellpadding="6" border="1" width="100%">
				<tr bgcolor="#FFCC99" style="text-align:center;">
					<th>#</th>
					<th>Roll No.</th>
					<th>Enrollment No.</th>
					<th>Student Name</th>
					<th>Course</th>
					<th>Class</th>
					<th>Remark</th>
				</tr>
				<?php } $sno++; ?><tr style="text-align:center;">
					<td valign="middle"><?php	echo ++$studentCount ;?></td>
					<td valign="middle"><?php	echo $student->roll_no ;?></td>
					<td valign="middle"><?php	echo $student->enrollment_no ;?></td>
					<td valign="middle" align="left"><?php	echo $student->name ;?></td>
					<td valign="middle" align="left"><?php	echo $student->course_name ;?></td>
					<td valign="middle"><?php	echo $this->Common_model->getClassNameByClassId($student->class_id); ?></td>
					<td valign="middle" style="width: 20%;"></td>
				</tr>
			<?php  
			if($sno==$perpagecount){ 
			$sno=0; 
			if($totalStudent>$studentCount) { echo "</table>"; } 
			}   
			} ?><tr>
		<td valign="middle" colspan="3">
			<p style="margin-top: 1rem;">Total Marksheet:</p>
			<p>Dispatch No:</p>
			<p>Dispatch Dt:</p>
		</td>
		<td valign="bottom" colspan="4">
			
			<p align="center"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya </b></p>
		</td>
		</tr>
		</table><?php 
	}// student loop 
} //center loop ?>		