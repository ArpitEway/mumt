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
	$isFinalClass = false;
	$notification_no = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id' => $class_id));
	$classData = $this->Common_model->getRecordById('class_master','id',$class_id);
	$isOneClass = $this->Common_model->hasOneClass($course_group_id);
	if($classData->last_class == 'L'){
		$isFinalClass = true;
	  }
	$course_duration = ($isOneClass) ? "(One Year Course)" : $classData->class_name;
	// $notification=$notification_no[0]->notification_no;
	$notification=($mode == "REG")?$notification_no[0]->notification_no:$notification_no[0]->pvt_notification_no;
	$date=($mode == "REG")?$notification_no[0]->result_date:$notification_no[0]->pvt_result_date;
	$exam_session=$notification_no[0]->exam_session;
	$page_no = 0;
	$abs_count = 0;
	$page_break_count = 0 ;
	$i=1;

	foreach($students as $student){
		$total_theory_abs_count=0;
		$total_int_abs_count=0;
		$theory_paper_count=0;
		$practical_paper_count =0;
		$practical_abs_count = 0;
		$theory_abs_count=0;
		$fail_count = 0;
		$check_grace_marks = false;	
		$fail_past=false;
		$get_tot_marks = 0;
		$require_tot_marks = 0;
		$Withheld = false; 
		$ATKT_paper_codes = array(); 
		$abs_count=0;
		$p_fail_count=0;
		$int_fail_count=0;
		$total_obtained_marks=0;
		$total_max_marks=0;
		$fc1 =0;
    	$fc2=0;
		$fc1_abs ='';
		$fc2_abs='';
   		$fc1_max =0;
   		$fc2_max =0;
  		 $fc1_min =0;
   		$fc2_min =0;
		   //$grand_obt=0;
		   //$grand_tot =0;
		   $grand_obtain =0;
			$grand_total =0;
		$paper_marks = $this->Common_model->notification_marks_details_($student->student_id,$student->old_class_id);
		// $class_ids=array(101,104,107,110,116,119,125,128,131,134);
        $class_ids = array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
		foreach($paper_marks as  $marks){
			if((in_array($student->old_class_id, $class_ids)) && $student->exam_pattern=="GRADE" )	// && $mode=='REG'
			{
			if($marks->type=="theory" ){
				$theory_paper_count++;
				if($marks->sub_group_id == 1){
					if($marks->group_paper_name == 'FC1' ){
					  if($marks->theory_marks==''){
						$rw_count++;
					 
					  }
					  if($marks->theory_marks=='ABS'){
						$fc1_abs .= $marks->theory_marks;
					 
					  }
					$fc1 += (int) $marks->theory_marks;
					$fc1_max += (int) $marks->max_theory_marks;
					$fc1_min += (int) $marks->min_theory_marks;
					}else{
					  if($marks->theory_marks==''){
						$rw_count++;
					   
					  }
					  if($marks->theory_marks=='ABS'){
						$fc2_abs .= $marks->theory_marks;
					 
					  }
					  $fc2 += (int) $marks->theory_marks;
					  $fc2_max += (int) $marks->max_theory_marks;
					  $fc2_min += (int) $marks->min_theory_marks;
					}
					
				  }else{
					if($classData->internal!="N"){
						$total_obtained_marks +=$marks->theory_marks+$marks->int_marks;
						$total_max_marks +=$marks->max_theory_marks+$marks->max_internal_marks;
					}else{
						$total_obtained_marks +=$marks->theory_marks;
						$total_max_marks +=$marks->max_theory_marks;
					}
					if($marks->theory_marks==''){
						$Withheld = true;
					}
					if($marks->theory_marks=="ABS"){
						$theory_abs_count++;
						$abs_count++;
						array_push( $ATKT_paper_codes,$marks->paper_code );
					}elseif($marks->theory_marks+$marks->int_marks<$marks->min_theory_marks+$marks->min_internal_marks){
						$fail_count++;
						$get_tot_marks += $marks->theory_marks+$marks->int_marks ;
						$require_tot_marks += $marks->min_theory_marks+$marks->min_internal_marks;
						array_push( $ATKT_paper_codes,$marks->paper_code );
					}
					if($classData->internal!="N" && $mode == "REG"){
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
					}
				}
			}else{
				$practical_paper_count++;
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
					$practical_abs_count++;
					array_push( $ATKT_paper_codes,$marks->paper_code );
				}
				
			}
		}else{
			if($marks->type=="theory" ){
				$theory_paper_count++;
				if($classData->internal!="N"){
					$total_obtained_marks +=$marks->theory_marks+$marks->int_marks;
					$total_max_marks +=$marks->max_theory_marks+$marks->max_internal_marks;
				}else{
                    if($mode != 'PVT'){
					$total_obtained_marks +=$marks->theory_marks;
					$total_max_marks +=$marks->max_theory_marks;
                    }else{
                        $total_obtained_marks +=$marks->theory_marks;
					$total_max_marks +=$marks->private_max_theory_marks;
                    }
				}
				if($marks->theory_marks==''){
					$Withheld = true;
				}
				if($marks->theory_marks=="ABS"){
					$theory_abs_count++;
					$abs_count++;
					array_push( $ATKT_paper_codes,$marks->paper_code );
				}
                if($mode != 'PVT'){
				if($marks->theory_marks<$marks->min_theory_marks){
					$fail_count++;
					$get_tot_marks += $marks->theory_marks;
					$require_tot_marks += $marks->min_theory_marks;
					array_push( $ATKT_paper_codes,$marks->paper_code );
				}
                }else{
                    if($marks->theory_marks<$marks->private_min_theory_marks){
                        $fail_count++;
                        $get_tot_marks += $marks->theory_marks;
                        $require_tot_marks += $marks->private_min_theory_marks;
                        array_push( $ATKT_paper_codes,$marks->paper_code );
                    }  
                }
				if($classData->internal!="N" && $mode == "REG"){
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
				}
			}else if($marks->type=="Sessional"){
				$total_obtained_marks +=$marks->int_marks;
				$total_max_marks +=$marks->max_internal_marks;
				if($classData->internal!="N" && $mode == "REG"){
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
				}
			}else{
				$practical_paper_count++;
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
                    $practical_abs_count++;
				}
			}
		}
	}
		 $total_obtained_marks +=$fc1+$fc2;
		 $total_marks_obt  +=$fc1+$fc2;
		$total_max_marks +=$fc1_max +$fc2_max;
		// $tot_marks += $fc1_max +$fc2_max;
		// $tot_std_marks += $fc1+$fc2;
		// $count_theory +=  $fc1+$fc2;
		if($fc1_abs === 'ABSABS'){
			$theory_abs_count++;
		   }
		   if($fc2_abs === 'ABSABS'){
			$theory_abs_count++;
		   }
	   
		if($fc1 < $fc1_min ){
		  array_push( $ATKT_paper_codes ,'FC1' );
			  $fail_count++;
			  $get_tot_marks += $fc1;
			  $require_tot_marks += $fc1_min;
	
		}
		if($fc2 < $fc2_min){
		  array_push( $ATKT_paper_codes ,'FC2' );
		  $fail_count++;
		  $get_tot_marks += $fc2;
		  $require_tot_marks += $fc2_min;
	
		}
		if((in_array($student->old_class_id, $class_ids))  && $student->exam_pattern=='GRADE'  )	//$mode=='REG'
			{
				
			 $require_grace_marks = $require_tot_marks-$get_tot_marks.'<br>';
				              }else{
								$require_grace_marks = $require_tot_marks-$get_tot_marks;
							  }	              // echo $fail_count;
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
					<p class="size" style="line-height:2px">AGPA-Annual Grade Point Average</p>
		 			<p class="size" style="line-height:2px">SGPA-Semester Grade Point Average</p>
					<p class="size" style="line-height:2px">RW-Result Withheld</p>
					<p class="size" style="line-height:2px">RWE-Want of Enrolment</p>
					<p class="size"style="line-height:2px">RWPM-Want of Prev. Sem/Year Marks</p>
					<p class="size"style="line-height:2px">RWPR-Practical Marks Not Received</p>
					<p class="size" style="line-height:2px">RWAS-Assignment Marks Not Received</p>
					<p class="size"style="line-height:2px">RWPJ-Project Marks Not Received</p>
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
		<h2 align="center"><strong>Result Notification of</strong><br><p style="margin-top:8px"><strong><?php echo $this->Common_model->getCourseNameByCourseId($course_group_id).' '. $course_duration .'  '. $exam_session?></strong></p></h2>
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
				<th class="text-center" scope="row" width="10%" ><span class="style5">Roll No.</span></th>
				<th  class="text-center" style="text-align:left" scope="row"  width="35%" ><span class="style5" style="padding-left: 10px;" >Name of the Candidate and F/H Name</span></th>
				<th class="text-center" scope="row"  width="10%" >Result</span></th>
				
				<?php if((!in_array($student->old_class_id, $class_ids)) || $student->exam_pattern=='MARKS'){ //$mode=='PVT' ?>	<th class="text-center" style="padding:0px" align="center" class="text-center" scope="row"  width="20%" colspan='<?php echo ($isFinalClass && !$isOneClass)?"2":"1";?>'><?php if($isFinalClass){
						?>
						<table width="100%" border="1" class="m-0">
							<tr>
							<td class="text-center">Marks Obtd</td>
							<?php if(!$isOneClass){?>
							<td class="text-center">Marks Obtd</td>
							<?php }?>
							
					</tr>
					<tr>
							<td class="text-center"><?= $student->class_name?> </td>
							<?php if(!$isOneClass){?>
							<td class="text-center">Grand Total</td>
							<?php }?>	
					</tr>
								
								
					</table>
						<?php
				}else{ ?><span class="style5">Total</span> <?php }
				?></th><?php } ?>
				
				
				<?php if((in_array($student->old_class_id, $class_ids)) && $student->exam_pattern=='GRADE')	//&& $mode=='REG'
			{ ?>
				<th class="text-center" scope="row" width="10%"><span class="style5">
					<?php if($classData->mode=="Annual") echo 'AGPA'; else echo 'SGPA'; ?></span></th>
				<?php } ?>
				<?php	if ($isFinalClass) {	?>
					<th class="text-center" scope="row"  width="10%"><span class="style5">Division</span></th>
				<?php	}	?>
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
					// echo 'RW';
					echo $final_result = "RW";
				}else{
					if($isFinalClass && $isOneClass == false){
						$classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$course_group_id,'mode'=>$classData->mode,'id!='=>$class_id
					));
					// echo '<pre>';
					// print_r($classes);die;
					
						foreach($classes as $cls){
							$this->db->where_in('exam_result',array('PASS','PASS BY GRACE'));
							$results = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id='=>$cls->id));
							// $this->Common_model->last_query();
							// echo count($results);die;
							
							if(count($results)!=0){
								foreach($results as $row){
									
									$grand_obtain += $row->obtain_marks;
									$grand_total += $row->total_marks;
								}
								if($fail_count>0 || $abs_count>0){
									$final_result = ($check_grace_marks) ? 'PASS BY GRACE' : 'FAIL';

								}else{
									$final_result = 'PASS';
								}
							}else{
								 $final_result = 'RWPM';
								 break;
							}
							
							
						}
				  
				   echo $final_result;
					    $grand_obtain +=  $total_obtained_marks;
						$grand_total += $total_max_marks;
					}else{
						if($p_fail_count>0){
							$final_result = 'FAIL';
						}
						else if($fail_count>0 || $abs_count>0){
							 $final_result = ($check_grace_marks) ? 'PASS BY GRACE' : 'FAIL';

						}else{
							 $final_result = 'PASS';
							
						}
						if((in_array($student->old_class_id, $class_ids)) && $student->exam_pattern=='MARKS' ){//&& $mode=='REG'
						echo $this->Gradesheet_tr_model->view_notification_result($student->student_id,$student->course_group_id,$student->old_class_id,$student->university_mode);
						}else{
							echo $final_result;
						}
					}
				}
				
				?>
			</td>
			<?php
			if($isFinalClass){
				
				if($final_result == "RWPM" ){
?>
<td class="text-center" style="padding:0px" width='10%' align="center"></td>
<td class="text-center" style="padding:0px" width='10%' align="center"></td>
<?php
				}else{
?>
<td class="text-center" style="padding:0px" width='10%' align="center"><?php  if(!in_array($final_result, array("FAIL","RW") )){ echo $total_obtained_marks .' / '. $total_max_marks ;}?></td>
<?php if(!$isOneClass) { ?>
<td class="text-center" style="padding:0px" align="center"><?php if(!in_array($final_result, array("FAIL","RW") )){ echo  $grand_obtain .' / '. $grand_total;} ?></td>
<?php
			}			}
			}else if((!in_array($student->old_class_id, $class_ids)) || $student->exam_pattern=="MARKS"){ //$mode=='PVT'
				?>
			<td  class="text-center" style="padding:0px" align="center"><?php
			if(!in_array($final_result, array("FAIL","RW") )){
				echo $total_obtained_marks .' / '. $total_max_marks;
			}
		}
			?>
		</td>

		<?php	
		if((in_array($student->old_class_id, $class_ids)) && $student->exam_pattern=='GRADE'){//&& $mode=='REG'
		
			if($final_result != 'FAIL' && $final_result!="RW"){
				
				$gradesheetData = $this->Gradesheet_tr_model->view_notification($student->student_id,$student->course_group_id,$student->old_class_id,$student->university_mode);
			}else{
				?>
				<td  class="text-center" style="padding:0px" align="center"></td>
				<?php
			}
		}
	
		
		if ($isFinalClass) {	?>
		<td  class="text-center" style="padding:0px" align="center"><?php
			if(!$isOneClass){
				$percentage = round(($grand_obtain/$grand_total)*100,2);  
			}else{
				$percentage = round(($total_obtained_marks/$total_max_marks)*100,2);	
			}
			  
			if($percentage>=60){
			  $division = "First";
			}elseif($percentage<60 && $percentage>=40){
			  $division  = "Second";
			}else{
			  $division = "Third";
			}
			 if($final_result == 'RWPM' ){
				echo '';
			}else
			if($final_result != 'FAIL'){
			echo ($Withheld && $isFinalClass) ? '' : $division;
			}
			?>
		</td>
		<?php
		} ?>
		<td class="text-center" >
			<?php
		if((in_array($student->old_class_id, $class_ids)) &&  $student->exam_pattern=="GRADE" ){	//$mode=='REG'
			
			if($final_result == 'RWPM'){
				echo 'RWPM';
			}else{
			if(count($ATKT_paper_codes)==0 || $Withheld) {
				$remark='';
			}elseif($theory_paper_count ==$theory_abs_count && $practical_paper_count == $practical_abs_count){
				echo 'Year Break';
			}
			
			elseif($practical_paper_count == $practical_abs_count && $practical_paper_count!=0){
				echo 'Absent In Practical';
			}elseif(($theory_paper_count-2)==$theory_abs_count){
				echo 'Year Break';
			}else{
				if($fail_count == $theory_paper_count){
					echo 'Year Break';
				}else{
					if($require_grace_marks>=4 || $abs_count!=0 ){

						$remark= ($check_grace_marks) ? 'FAIL' : 'SUPP IN ';
						echo $remark;
	
						foreach($ATKT_paper_codes as $paper_code){
							echo  "". $paper_code.' ' ;
						} 
					}
				}
				
			}
		}	
	}else{
		
		if($final_result == 'RWPM'){
			echo 'RWPM';
		}else{
		if(count($ATKT_paper_codes)==0 || $Withheld) {
			$remark='';
		}elseif($theory_paper_count ==$theory_abs_count && ($practical_paper_count == $practical_abs_count && $practical_paper_count!=0)){
			echo 'Absent in All';
		}
		elseif($practical_paper_count == $practical_abs_count && $practical_paper_count!=0){
			echo 'Absent In Practical';
		}elseif($theory_paper_count==$theory_abs_count){
			echo 'Absent In Theory';
		}else{
			// if($fail_count == $theory_paper_count ){
			// 	echo 'Year Break';
			// }else{
				if($require_grace_marks>=4 || $abs_count!=0 ){

					$remark= ($check_grace_marks) ? 'FAIL' : 'ATKT IN ';
					echo $remark;

					foreach($ATKT_paper_codes as $paper_code){
						echo  "". $paper_code.' ' ;
					} 
				}
			// }
			
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
		
			<p class="size" style="line-height:2px">AGPA-Annual Grade Point Average</p>
		
			<p class="size" style="line-height:2px">SGPA-Semester Grade Point Average</p>
		
			<p class="size" style="line-height:2px">RW-Result Withheld</p>
			<p class="size" style="line-height:2px">RWE-Want of Enrolment</p>
			<p class="size"style="line-height:2px">RWPM-Want of Prev. Sem/Year Marks</p>
			<p class="size"style="line-height:2px">RWPR-Practical Marks Not Received</p>
			<p class="size" style="line-height:2px">RWAS-Assignment Marks Not Received</p>
			<p class="size"style="line-height:2px">RWPJ-Project Marks Not Received</p>
			<p class="size" style="line-height:2px">UFM-Unfair Means</p>
			<p class="size" style="line-height:2px">GR-Grace Mark In One Theory Paper For Passing</p>
			<p class="size" style="line-height:2px">VCG-Vice-Chancellor's One Grace Mark In Division</p>
		</td>
	</tr>
	<tr><td>&nbsp;</td> <td class="size" align="right"><!-- Asst. Registrar --></td>
	<td class="size"align="center">Registrar/Controller Of Examination</td></tr>
	<tr><td colspan="2" class="size">Copy of Result Notification is forwarded for information to
		<p style="padding-top: 15px;" class="size">1.Notice Board of the University</p>
	</table>
</body>
</html>