<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo (isset($title)) ? $title : ''; ?></title>
</head>
<body>
	<br><?php
	
	$isFinalClass = false;
$notification_no = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id' => $students[0]->class_id));
$classData = $this->Common_model->getRecordById('class_master','id',$class_id);
// print_r($classData);die;
$isOneClass = $this->Common_model->hasOneClass($course_group_id);
if($classData->last_class == 'L'){
	$isFinalClass = true;
  }
$notification=($mode == "REG")?$notification_no[0]->backlog_notification_no:$notification_no[0]->backlog_pvt_notification_no;
$date=($mode == "REG")?$notification_no[0]->backlog_result_date:$notification_no[0]->backlog_pvt_result_date;
$exam_session=$notification_no[0]->exam_session;
$page_no = 0 ;
$abs_count = 0 ;
?>
<style>
	@media print {
		body {
			-webkit-print-color-adjust: exact;
			-moz-print-color-adjust: exact;
			-ms-print-color-adjust: exact;
			print-color-adjust: exact;
		}
	}
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
</style>

	            <?php
				$page_break_count = 0 ;
				$i=1;
				foreach($students as $student){
                    $student_data =  $this->Common_model->getRecordById('student','student_id',$student->student_id);
					$total_theory_marks_obt = 0;
					$total_int_marks_obt = 0;
					$total_marks_obt = 0;
					$total_paper_marks = 0;
					$check_grace_marks = false;
					$require_tot_marks = 0;
					$tot_std_marks = 0;
					$tot_marks = 0;
					$rw_count = 0;
					$theory_abs_count = 0;
					$atkt_paper_codes_array = array(); 
					$int_abs_count = 0;
					$int_fail_count = 0;
					$p_abs_count = 0;
					$p_fail_count = 0;
					$rwpr_count = 0;
					$fail_count = 0;
					$fail_tot_marks = 0;
					$final_result = '';
					$theory_paper_count = 0;
					$p_paper_count = 0;
					$Withheld = false;
					$WithheldPR = false;
					$fc1 =0;
					$fc2=0;
					$fc1_abs ='';
					$fc2_abs='';
					$fc1_max =0;
					$fc2_max =0;
					$fc1_min =0;
					$fc2_min =0;
					$grand_obt=0;
					$grand_tot =0;
					$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136);
					$paper_marks = $this->Common_model->backlog_student_info_for_BEd_result($student->student_id,$student->class_id,$student->id);
					// $this->Common_model->last_query();
					foreach($paper_marks as  $new_exam_form){
					if((in_array($student->class_id, $class_ids)) && $mode=='REG')	
					{
						
						if($new_exam_form->type=='theory'){
                            if($new_exam_form->status =='B'){
                                $theory_paper_count++;
                            }
							

							
							if($new_exam_form->sub_group_id == 1){
								
								if($new_exam_form->group_paper_name == 'FC1' ){
									if($new_exam_form->theory_marks==''){
										$rw_count++;
                                        $Withheld =true;
									}
									if($new_exam_form->theory_marks=='ABS'){
										$fc1_abs .= $new_exam_form->theory_marks;
									 
									  }
						 			$fc1 += (int) $new_exam_form->theory_marks;
						  			$fc1_max += (int) $new_exam_form->max_theory_marks;
						  			$fc1_min += (int) $new_exam_form->min_theory_marks;
						 		}else{
									if($new_exam_form->theory_marks==''){
									$rw_count++;
									 $Withheld =true;
									}
									if($new_exam_form->theory_marks=='ABS'){
										$fc2_abs .= $new_exam_form->theory_marks;
									 
									  }
									$fc2 += (int) $new_exam_form->theory_marks;
									$fc2_max += (int) $new_exam_form->max_theory_marks;
									$fc2_min += (int) $new_exam_form->min_theory_marks;
						  		}


							}else{
								$total_theory_marks_obt += $new_exam_form->theory_marks;
							$total_int_marks_obt += $new_exam_form->int_marks;
							$total_marks_obt  += $new_exam_form->theory_marks+ $new_exam_form->int_marks;
							$total_paper_marks += $new_exam_form->max_theory_marks + $new_exam_form->max_internal_marks;
							$tot_std_marks += $new_exam_form->theory_marks;
							$tot_marks += $new_exam_form->max_theory_marks;
								if($new_exam_form->theory_marks=='ABS'){
									array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
									$theory_abs_count++;
								}
								if($new_exam_form->theory_marks==''){
									$rw_count++;
                                    $Withheld =true;
								}
								if($new_exam_form->theory_marks+$new_exam_form->int_marks<$new_exam_form->min_theory_marks+$new_exam_form->min_internal_marks && $new_exam_form->theory_marks!=''){
									array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
									$fail_count++;
									$fail_tot_marks += $new_exam_form->theory_marks+$new_exam_form->int_marks;
									$require_tot_marks += $new_exam_form->min_theory_marks+$new_exam_form->min_internal_marks;
								}
								if($new_exam_form->int_marks=='N'){
									$rw_count++;
								}
								if($new_exam_form->int_marks<$new_exam_form->min_internal_marks){
									$int_fail_count++;
									array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
								}
								if($new_exam_form->int_marks=="ABS"){
									$int_abs_count++;
									$int_fail_count++;
								}
							}
						}
						if($new_exam_form->type!='theory'){
							$p_paper_count++;
							$total_paper_marks +=$new_exam_form->max_theory_marks+$new_exam_form->max_internal_marks; 
							$total_marks_obt += $new_exam_form->p_marks+$new_exam_form->int_marks;
							if($new_exam_form->p_marks=='' || $new_exam_form->p_marks=='N'){
								$rwpr_count++;
							}
							if($new_exam_form->p_marks=='ABS'){
								$p_abs_count++;
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
							}
							if($new_exam_form->p_marks<$new_exam_form->min_theory_marks){
								$p_fail_count++;
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
							}
							if($new_exam_form->type!='Project'){
							if($new_exam_form->int_marks=='N'){
								$rwpr_count++;
							}
						}

							if($new_exam_form->int_marks<$new_exam_form->min_internal_marks){
									$int_fail_count++;
									array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
							}
							if($new_exam_form->int_marks=="ABS"){
								$int_abs_count++;
								$int_fail_count++;
							}
						}
					

					}else{
						if($new_exam_form->type=='theory'){
                            if($new_exam_form->status =='B'){
                                $theory_paper_count++;
                            }
							$total_theory_marks_obt += $new_exam_form->theory_marks;
							$total_int_marks_obt += $new_exam_form->int_marks;
							$total_marks_obt  += $new_exam_form->theory_marks+ $new_exam_form->int_marks;
							$total_paper_marks += $new_exam_form->max_theory_marks + $new_exam_form->max_internal_marks;
							$tot_std_marks += $new_exam_form->theory_marks;
							$tot_marks += $new_exam_form->max_theory_marks;

							if($new_exam_form->theory_marks=='ABS'){
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
								$theory_abs_count++;
							}
							if($new_exam_form->theory_marks==''){
								$rw_count++;
								$Withheld =true;
							}
							if($new_exam_form->theory_marks<$new_exam_form->min_theory_marks  && $new_exam_form->theory_marks!=''){
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
								$fail_count++;
								$fail_tot_marks += $new_exam_form->theory_marks;
								$require_tot_marks += $new_exam_form->min_theory_marks;
							}
							if($new_exam_form->theory_marks == "ABS" && $new_exam_form->status == "B"){
								$abs_count++;
							}
							if($new_exam_form->int_marks=='N'){
								$rw_count++;
								$Withheld;
							}
							if($new_exam_form->int_marks<$new_exam_form->min_internal_marks){
								$int_fail_count++;
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
							}
							if($new_exam_form->int_marks=="ABS"){
								$int_abs_count++;
								$int_fail_count++;
							}
						}
						if($new_exam_form->type!='theory'){
							$p_paper_count++;
							$total_paper_marks +=$new_exam_form->max_theory_marks+$new_exam_form->max_internal_marks; 
							$total_marks_obt += $new_exam_form->p_marks+$new_exam_form->int_marks;
							if($new_exam_form->p_marks=='' || $new_exam_form->p_marks=='N'){
								$rwpr_count++;
								$WithheldPR=true;
							}
							if($new_exam_form->p_marks=='ABS'){
								$p_abs_count++;
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
							}
							if($new_exam_form->p_marks<$new_exam_form->min_theory_marks){
								$p_fail_count++;
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
							}
							if($new_exam_form->int_marks=='N'){
								$rwpr_count++;
								$WithheldPR =true;
							}

		         		if($new_exam_form->int_marks<$new_exam_form->min_internal_marks){
								$int_fail_count++;
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
							}
							if($new_exam_form->int_marks=="ABS"){
								$int_abs_count++;
								$int_fail_count++;
							}
						}
					}		
					}
					$total_theory_marks_obt +=$fc1+$fc2;
					$total_marks_obt  +=$fc1+$fc2;
					$total_paper_marks +=$fc1_max +$fc2_max;
					$tot_marks += $fc1_max +$fc2_max;
					$tot_std_marks += $fc1+$fc2;
					// $count_theory +=  $fc1+$fc2;
					if($fc1_abs === 'ABS'){
						$theory_abs_count++;
					   }
					   if($fc2_abs === 'ABS'){
						$theory_abs_count++;
					   }
	   
		if($fc1 < $fc1_min ){
		  array_push( $atkt_paper_codes_array ,'FC1' );
			  $fail_count++;
			  $fail_tot_marks += $fc1;
			  $require_tot_marks += $fc1_min;
	
		}
		if($fc2 < $fc2_min){
		  array_push( $atkt_paper_codes_array ,'FC2' );
		  $fail_count++;
		  $fail_tot_marks += $fc2;
		  $require_tot_marks += $fc2_min;
	
		}
					if ($fail_count==0 && $rw_count==0 && $p_fail_count==0 && $int_fail_count==0 && $theory_abs_count==0 && $p_abs_count==0 && $rwpr_count==0) {
						$final_result = "PASS";
					}else{
		// 				if((in_array($student->class_id, $class_ids)) && $mode=='REG')	
		// 	{
		
		// $require_grace_marks =$require_tot_marks-$fail_tot_marks;
		// 		              }else{
								
		// 						$require_grace_marks =$require_tot_marks-$fail_tot_marks;
		// 					  }	  
						
      // tot 3 grace marks in 1 subjects
	 
						// if ($fail_count<2 && $require_grace_marks<4 && $int_fail_count==0 && $p_fail_count==0 && $rw_count==0 && $theory_abs_count==0 && $p_abs_count==0 &&  $int_abs_count==0 && $rwpr_count==0) {
						// 	$check_grace_marks = true;
						// 	$final_result = "PASS BY GRACE";
						// }else
                        if($rwpr_count>0){
							 $final_result = "RWPR";
						}elseif($rw_count>0){
							 $final_result = "RW";
						}else{
							 $final_result = "FAIL";
						}
					}
		    if($page_break_count%18==0 ||  $page_break_count==0 ){
			$page_no++;
		   if ($page_break_count>1) {
           ?>
           </tbody>
           </table>
	  <hr>
      <table width="100%" >
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
			<!-- <p class="size" style="line-height:2px">RWPM-Project Marks Not Received</p> -->
			<p class="size" style="line-height:2px">UFM-Unfair Means</p>
			<p class="size" style="line-height:2px">GR-Grace Mark In One Theory Paper For Passing</p>
			<p class="size" style="line-height:2px">VCG-Vice-Chancellor's One Grace Mark In Division</p>
		</td>
	</tr>
	</table> 
		<?php
		  }
		?>
		<p align="right"  class="mt-4 <?=($page_no!=1) ? 'break' : ''; ?> size"><?php echo "Page : ". $page_no ; ?></p> 
		
		<div style="width:75px;float:left"><img src="<?=base_url('assets/logo.png')?>" ></div>
		<div>
			<h1 class="text-center" ><strong> Maharishi Mahesh Yogi Vedic Vishwavidyalaya </strong> </h1>
		<p align="center" style="line-height:0px">Head Office: Karaundi, Post-Mahner ,Distt- Katni(MP) </p>
		
		<h2 align="center"><strong>Backlog Result Notification of</strong><br><p style="margin-top:8px"><strong><?php echo $this->Common_model->getCourseNameByCourseId($course_group_id).' - '. $this->Common_model->getClassNameByClassId($class_id) .'  '. $exam_session?></strong></p></h2>
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
				
				<table width="100%"  class="fully_size"  border="1">
					<thead>
						<tr bgcolor="#FFFF00">
							<th scope="row" class="text-center" width="10%"><span class="style5">Roll No.</span></th>
							<th style="text-align:left" scope="row"  width="35%"><span class="style5" >Name of the Candidate and F/H Name</span></th>
							<th scope="row" class="text-center"  width="12%">Result</span></th>
							<?php if((!in_array($student->class_id, $class_ids)) || $mode=='PVT'){ ?>
								<th class="text-center" style="padding:0px" align="center" class="text-center" scope="row"  width="20%" colspan='<?php echo ($isFinalClass && !$isOneClass)?"2":"1";?>'><?php
								if($isFinalClass){ ?>
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
				?></th>
							<!-- <th scope="row" class="text-center" width="10%"><span class="style5">Total</span></th> -->
							<?php	
							}
							if((in_array($student->class_id, $class_ids)) && $mode=='REG')	
							{
						
								?>
								<th scope="row" class="text-center" width="10%"><span class="style5"><?php if($classData->mode=="Annual") echo 'AGPA'; else echo 'SGPA'; ?></span></th>
								<?php

							}
					?>
									<?php	if ($isFinalClass) {	?>
								<th class="text-center" scope="row"  width="10%"><span class="style5">Division</span></th>
							<?php	}	?>
							<th scope="row" class="text-center" width="20%"><span class="style5">Remark</span></th>
						</tr>
					</thead>
					<tbody>
						<?php
					}
					$page_break_count++;	
					?>
					
					<tr class="alternate">
					
						<td class="text-center" scope="row">
							<?php echo $student->roll_no ?>
							</td>
							<td scope="row"  style="padding-left: 10px;" >
								<?php echo $student_data->name .' / '.  $student_data->f_h_name; ?>
							</td>
							<td align="center" >
							<?php
				if($Withheld){
					// echo 'RW';
					echo $final_result = "RW";
				}
				elseif($WithheldPR){
					
					echo $final_result = "RWPR";
				}else{
					if($isFinalClass && $isOneClass == false){
						$final_fail =0;
						$classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$course_group_id,'mode'=>$classData->mode,'id!='=>$class_id
					));
					// echo '<pre>';
					// print_r($classes);die;
					
						foreach($classes as $cls){
						
							$this->db->order_by('id','desc');
							$this->db->limit(1);
							// $this->db->where_in('exam_result',array('PASS','PASS BY GRACE'));
							$results = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id='=>$cls->id));
							// echo  $this->db->last_query();
							//  echo count($results);die;
							// if(count($results)>0){
								foreach($results as $row){

									if($row->exam_result == "FAIL"){
										$final_fail++;
										$row->obtain_marks ='-';
										$row->total_marks = '-';
										
										 }
									
									$grand_obt += $row->obtain_marks;
									$grand_tot += $row->total_marks;
								}
								if($fail_count>0 || $abs_count>0){
									$final_result = 'FAIL';

								}elseif($final_fail !=0){
									$final_result = 'RWPM';
								}else{
									$final_result = 'PASS';
								}
							// }else{
							// 	$final_result = 'RWPM';
							// }
							
							
							
						}
				  
				   echo $final_result;
					$grand_obtain = $grand_obt + $total_marks_obt;
						$grand_total = $grand_tot+$total_paper_marks;
					}else{
	
						if($p_fail_count>0 || $p_abs_count>0){
							$final_result = 'FAIL';
						}
						else if($fail_count>0 || $abs_count>0){
							 $final_result =  'FAIL';

						}else{
							 $final_result = 'PASS';
							
						}
						if((in_array($student->class_id, $class_ids)) && $mode=='REG'){
						echo $this->Gradesheet_backlog_tr_model->view_notification_result($student->student_id,$student->course_group_id,$student->class_id,$student->mode,$student->id);
						}else{
							echo $final_result;
						}
					}
				}
				
				?>
							</td>
							<!-- <?php //if((!in_array($student->class_id, $class_ids)) || $mode=='PVT'){ ?>
							<td align="center" style="padding:0px">					
								<?php 
								// if($final_result=='PASS' || $final_result=='PASS BY GRACE'){
								// 	echo $total_marks_obt.'/'.$total_paper_marks;
								// }else{
								// 	echo '-';
								// } 
								?>
							</td>-->
							<?php //} -->
							
							if($isFinalClass){
								
								if($final_result == "RWPM"){
				?>
				<td class="text-center" style="padding:0px" width='10%' align="center"></td>
				<td class="text-center" style="padding:0px" width='10%' align="center"></td>
				<?php
								}else{
				?>
				<td class="text-center" style="padding:0px" width='10%' align="center"><?php  if(!in_array($final_result, array("FAIL","RW") )){ echo  $total_marks_obt.' / '. $total_paper_marks ;}?></td>
				<?php if(!$isOneClass) { ?>
				<td class="text-center" style="padding:0px" align="center"><?php if(!in_array($final_result, array("FAIL","RW") )){ echo  $grand_obtain .' / '. $grand_total;} ?></td>
				<?php
							}			}
							}else if((!in_array($student->class_id, $class_ids)) || $mode=='PVT'){ 
								?>
							<td  class="text-center" style="padding:0px" align="center"><?php 
							if(!in_array($final_result, array("FAIL","RW","RWPR") )){
								
								//echo $total_obtained_marks .' / '. $total_max_marks;
								echo $total_marks_obt .' / '. $total_paper_marks;
							}
						}
							?>
						</td>
						<?php
							if((in_array($student->class_id, $class_ids)) && $mode=='REG'){
								if($final_result != 'FAIL' && !$Withheld){
							$gradesheetData = $this->Gradesheet_backlog_tr_model->view_notification($student->student_id,$student->course_group_id,$student->class_id,$student->mode, $student->id);
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
											//$percentage = round(($total_obtained_marks/$total_max_marks)*100,2);
											$percentage = round(($total_marks_obt/$total_paper_marks)*100,2);	
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
							<td class="text-center"> 
								<?php 
								
								if((in_array($student->class_id, $class_ids)) && $mode=='REG')	
								{
									// echo $gradesheetData['result'];
									if($Withheld || $gradesheetData['result'] == 'RW'){
										echo " ";
									}
                                   // elseif( $theory_abs_count== ($theory_paper_count-2) && $p_abs_count==$p_paper_count){
										//echo 'Year Break';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
									//   }else
                                      elseif( $theory_abs_count== $theory_paper_count){
										echo 'ABSENT';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
									  }
									 elseif( $p_abs_count==$p_paper_count){
										echo 'Absent In Practical';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
									  }
									elseif(sizeof($atkt_paper_codes_array)>0){
										
											echo "SUPP in ";
										$atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
										foreach($atkt_paper_codes_array as $paper_code){
											echo  "<br>". $paper_code;
										}
										
										
									}else{
										echo '';
									}
								}else{
									
									if($theory_abs_count == $theory_paper_count){
										echo 'ABSENT';
									  }
									elseif( $theory_abs_count==$theory_paper_count && $p_abs_count==$p_paper_count && $student->course_group_id == 76){
										echo 'Absent In ALL';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
									  }
									elseif( $theory_abs_count==$theory_paper_count && $p_abs_count==$p_paper_count){
										echo 'Absent In All';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
							  }
									elseif( $p_abs_count==$p_paper_count){
										echo 'Absent In Practical';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
									  }
									elseif(sizeof($atkt_paper_codes_array)==1){
										echo "ATKT in ";
										$atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
										foreach($atkt_paper_codes_array as $paper_code){
											echo  "<br>". $paper_code;
										}
									}else{
										echo '';
									}	
								} ?>
							</td>
						</tr>
						<?php
					}

					?> 	
				</tbody>
			</table>
		<table width="100%">
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
					<!--<p style="line-height:2px">RWPM-Project Marks Not Received</p>-->
					<p class="size" style="line-height:2px">UFM-Unfair Means</p>
					<p class="size" style="line-height:2px">GR-Grace Mark In One Theory Paper For Passing</p>
					<p class="size" style="line-height:2px">VCG-Vice-Chancellor's One Grace Mark In Division</p>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="size" align="right"><!-- Asst. Registrar --></td>
				<td class="size"align="center">Registrar/Controller Of Examination</td>
			</tr>
			<tr>
				<td colspan="2" class="size">Copy of Result Notification is forwarded for information to
					<p style="padding-top: 15px;" class="size">1.Notice Board of the University</p>
				</td>
			</tr>
		</table>
	</body>
</html>