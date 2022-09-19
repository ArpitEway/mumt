<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo (isset($title)) ? $title : ''; ?></title>

	<style>
		body{
			font-size: 12px;
			font-family: Arial, Helvetica, sans-serif;
			padding: 5px 15px;
		}
		.text-center {
			text-align: center;
		}
		.break{
			page-break-before: always;
		}
		.flex-container {
			display: inline-flex;
			flex-wrap: wrap;
		}
		
		.size  {
			font-size: 15px; 
		}
		.alternate:nth-child(even)  {
			background-color: yellow;
		}
		h1{
			line-height: 15px;
			font-size: 20px;
			margin-bottom: 0;
		}
		th,td{
			padding: 10px;
		}
		@page{
			size: auto;
		}
		.style-p-0 th{
			padding: 0;
		}
		h2{
			font-size: 16px;
		}
		@media print {
            body {
                -webkit-print-color-adjust: exact;
                -moz-print-color-adjust: exact;
                -ms-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
	</style>
</head>
<body>
	<br>
	<?php
	$notification_no = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id' => $class_id));
	$notification=$notification_no[0]->notification_no;
	$date=$notification_no[0]->result_date;
	$exam_session=$notification_no[0]->exam_session;
	$page_no = 0;
	$abs_count = 0;
	$page_break_count = 0 ;
	$i=1;

	foreach($students as $student){
		$total_theory_abs_count=0;
		$total_int_abs_count=0;
		$theory_paper_count=0;
		$theory_abs_count=0;
		$fail_count = 0;
		$check_grace_marks = false;	
		$get_tot_marks = 0;
		$require_tot_marks = 0;
		$Withheld = false; 
		$ATKT_paper_codes = array(); 
		$abs_count=0;
		$p_fail_count=0;
		$int_fail_count=0;
		$total_obtained_marks=0;
		$paper_marks = $this->Common_model->notification_marks_details_($student->student_id,$student->old_class_id);

		foreach($paper_marks as  $marks){
			if($marks->type=="theory" ){
				$theory_paper_count++;
				$total_obtained_marks +=$marks->theory_marks+$marks->int_marks;
				$total_max_marks +=$marks->max_theory_marks+$marks->max_internal_marks;
				if($marks->theory_marks==''){
					$Withheld = true;
				}
				if($marks->theory_marks=="ABS"){
					$theory_abs_count++;
					$abs_count++;
					array_push( $ATKT_paper_codes,$marks->paper_code );
				}
				if($marks->theory_marks<$marks->min_theory_marks){
					$fail_count++;
					$get_tot_marks += $marks->theory_marks;
					$require_tot_marks += $marks->min_theory_marks;
					array_push( $ATKT_paper_codes,$marks->paper_code );
				}
				if($marks->int_marks<$marks->min_internal_marks){
					$int_fail_count++;
					array_push($ATKT_paper_codes ,$marks->paper_code );
				}
				if($marks->int_marks=='N' || $marks->int_marks==''){
					$Withheld = true;
				}
				if($marks->int_marks=="ABS"){
					$abs_count++;
				}
			}else{
				$total_obtained_marks +=$marks->p_marks;
				$total_max_marks +=$marks->max_theory_marks;
				if($marks->p_marks=='' || $marks->p_marks=='N'){
					$Withheld = true;
				}
				if($marks->p_marks<$marks->min_theory_marks){
					$result = "FAIL";
					$p_fail_count++;
				}
				if($marks->p_marks=='ABS'){
					$abs_count++;
				}
			}
		}
		$require_grace_marks = $require_tot_marks-$get_tot_marks;
			              // echo $fail_count;
			              // echo $require_grace_marks;
		if ($fail_count<2 && $require_grace_marks<4  && $abs_count==0 && $int_fail_count==0 && $p_fail_count==0) {
			$check_grace_marks = true;
		}

		?>
		<?php 
		if($page_break_count%12==0 || $page_break_count==0){
			$page_no++ ;
			if ($page_break_count>1) {
				?>
			</tbody>
		</table>
		<table width="100%">
			<hr>
			<tr>
				<td colspan="3">
					<p class="size" style="line-height:2px">RW-Result Withheld</p>
					<p class="size" style="line-height:2px">RWE-Want of Enrolment</p>
					<p class="size"style="line-height:2px">RWPM-Want of Prev. Sem/Year Marks</p>
					<p class="size"style="line-height:2px">RWPR-Practical Marks Not Received</p>
					<p class="size" style="line-height:2px">RWAS-Assignment Marks Not Received</p>
					<p class="size"style="line-height:2px">RWPJ-Project Marks Not Received</p>
					<p class="size" style="line-height:2px">RWPM-Project Marks Not Received</p>
					<p class="size" style="line-height:2px">UFM-Unfair Means</p>
					<p class="size" style="line-height:2px">GR-Grace Mark In One Theory Paper For Passing</p>
					<p class="size" style="line-height:2px">VCG-Vice-Chancellor's One Grace Mark In Division</p>
				</td>
			</tr>
		</table> 
		<?php
	}  
	?>      

	<p align="right" class="mt-4 <?=($page_no!=1) ? 'break' : ''; ?>"><?php echo "Page : ". $page_no ; ?></p> 
	<div style="width:50px;float:left"><img src="<?=base_url('assets/logo.png')?>" ></div>
	<div>
		<h1 class="text-center" ><strong> Maharishi Mahesh Yogi Vedic Vishwavidyalaya </strong> </h1>
		<p align="center" style="line-height:0px">Head Office: Karaundi, Post-Mahner ,Distt- Katni(MP) </p>
		<h2 align="center"><strong>Result Notification of</strong><br><p style="margin-top:8px"><strong><?php echo $this->Common_model->getCourseNameByCourseId($course_group_id).' - '. $this->Common_model->getClassNameByClassId($class_id) .'  '. $exam_session?></strong></p></h2>
	</div>
	<title>Notification <?php echo $this->Common_model->getCourseNameByCourseId($course_group_id)?></title> 

	<table class="style-p-0" width="100%">
		<tr>
			<th align="left">Notification No :  <?php echo $notification;?></th>
			<th align="right">Date : <?php echo $date;?></th>
		</tr>
		<tr> <th class="text-center" colspan="2">The Result of the following examinees of the above exam is hereby declared as under :</th> </tr>
	</table>
	<hr>
	<table width="100%"  border="1">
		<thead>
			<tr bgcolor="#FFFF00">
				<th class="text-center" scope="row" width="10%"><span class="style5">Roll No.</span></th>
				<th  class="text-center" style="text-align:left" scope="row"  width="45%"><span class="style5" style="padding-left: 10px;" >Name and F/H Name</span></th>
				<th class="text-center" scope="row"  width="15%">Result</span></th>
				<th class="text-center" scope="row"  width="10%"><span class="style5">Total</span></th>
				<th class="text-center" scope="row" width="20%"><span class="style5">Remark</span></th>
			</tr>
		</thead>
		<tbody>
			<?php
		}
		$page_break_count++;	
		?>
		<tr class="alternate">
			<td class="text-center">
				<?php echo $student->roll_number; ?>
			</td>
			<td scope="row" style="padding-left: 10px;" >
				<?php echo $student->name  .' / '.  $student->f_h_name; ?>
			</td>
			<td class="text-center" align="center" >
				<?php
				if($Withheld){
					echo 'RW';
				}else{
					if($fail_count>0 || $abs_count>0){
						echo ($check_grace_marks) ? 'PASS BY GRACE' : 'FAIL';
					}else{
						echo 'PASS';
					}
				}
				?>
			</td>
			<td  class="text-center" style="padding:0px" align="center"><?php
			if($fail_count==0 && $abs_count==0 && $p_fail_count==0 && $int_fail_count==0){
				echo $total_obtained_marks .' / '. $total_max_marks;
			}
			?>
		</td>
		<td class="text-center" >
			<?php
			if(count($ATKT_paper_codes)==0) {
				$remark='';
			}elseif($theory_paper_count==$theory_abs_count){
				echo 'ABS In Theory';
			}else{
				if($require_grace_marks>=4 || $abs_count!=0 ){

					$remark= ($check_grace_marks) ? 'FAIL' : 'ATKT IN';
					echo $remark;

					foreach($ATKT_paper_codes as $paper_code){
						echo  "". $paper_code.' ' ;
					} 
				}
			}			
			?>	
		</td>
	</tr>
	<?php
}
?>
</tbody>
</table>
<table width="100%">
	<hr>
	<tr>
		<td>&nbsp;
		</td>
		<td class="size" colspan="2" align="right" >
			Order for Declaration of Result & Publication
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<p class="size" style="line-height:2px">RW-Result Withheld</p>
			<p class="size" style="line-height:2px">RWE-Want of Enrolment</p>
			<p class="size"style="line-height:2px">RWPM-Want of Prev. Sem/Year Marks</p>
			<p class="size"style="line-height:2px">RWPR-Practical Marks Not Received</p>
			<p class="size" style="line-height:2px">RWAS-Assignment Marks Not Received</p>
			<p class="size"style="line-height:2px">RWPJ-Project Marks Not Received</p>
			<p style="line-height:2px">RWPM-Project Marks Not Received</p>
			<p class="size" style="line-height:2px">UFM-Unfair Means</p>
			<p class="size" style="line-height:2px">GR-Grace Mark In One Theory Paper For Passing</p>
			<p class="size" style="line-height:2px">VCG-Vice-Chancellor's One Grace Mark In Division</p>
		</td>
	</tr>
	<tr><td>&nbsp;</td> <td class="size" align="right">Asst. Registrar</td><td class="size"align="center">Registrar/Controller Of Examination</td></tr>
	<tr><td colspan="2" class="size">Copy of Result Notification is forwarded for information to
		<p style="padding-top: 15px;" class="size">1.Notice Board of the University</p>
	</table>
</body>
</html>