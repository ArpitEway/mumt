<?php
$notification_no = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id' => $students[0]->class_id));
$notification=$notification_no[0]->notification_no;
$date=$notification_no[0]->result_date;
$exam_session=$notification_no[0]->exam_session;
$page_break_count = -1 ;
$page_no = 1 ;
$page_break_count++;
$abs_count = 0 ;
?>

<style>
	.break { page-break-before: always; }
	.flex-container {
		display: inline-flex;
		flex-wrap: wrap;
	}

	.flex  {
		padding-left: 1000px; 
	}
	.size  {
		font-size: 15px; 
	}

	.alternate:nth-child(odd) {
		background-color: yellow;
	}
</style>



<p align="right"><?php echo "Page : ". $page_no ; ?></p> 
<div style="width:75px;float:left"><img src="<?=base_url('assets/logo.png')?>" ></div>
<h3 class="text-center">
	<strong> Maharishi Mahesh Yogi Vedic Vishwavidyalaya </strong>
</h3>
<p align="center" style="line-height:0px">Head Office: Karaundi, Post-Mahner ,Distt- Katni(MP) Website www.mmyvvdde.com </p>
<h3 align="center"><strong>Result Notification of</strong> <br><h3>
	<h3 class="text-center mb-2">	<strong><?php echo $students[0]->course_name.' - '. $students[0]->class_name .' '. $exam_session?></strong><h3>
		<title>Notification <?php echo $students[0]->course_name?></title>
		<div class="flex-container">
			<div style="font-size:15px;" >Notification No : <?php //echo $notification;?></div>

			<div style="font-size:15px;" class="flex">Date : <?php echo $date;?></div>  
		</div>
		<div style="font-size:15px;" align="center">The Result of the following examinees of the above exam is hereby declared as under : </div>
		<hr>
		<table width="100%"  border="1">
			<tr bgcolor="#FFFF00">
				<th scope="row" class="text-center" width="5%"> S.No. </th>
				<th scope="row" class="text-center" width="20%"><span class="style5">Roll No.</span></th>
				<th scope="row"><p class="style5">MS No.</p></th>
				<th style="text-align:left" scope="row"  width="30%"><span class="style5" >Name and F/H Name</span></th>
				<th scope="row"  width="15%">Result</span></th>
				<th scope="row"  width="10%"><span class="style5">Total</span></th>
				<th scope="row"><span class="style5">Remark</span></th>
			</tr>
		</table>
		<center style="font-size:15px;">Directorate of Distance Education</center>

		<table width="100%"  border="1">
			<tbody>
				<?php
				$i=1;
				foreach($students as $student){
					$page_break_count++;
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
					$fail_count = 0;
					$fail_tot_marks = 0;
					$final_result = '';
					$theory_paper_count = 0;
					$p_paper_count = 0;
					$paper_marks = $this->Common_model->notification_marks_details_($student->student_id);

					foreach($paper_marks as  $new_exam_form){

						if($new_exam_form->type=='theory'){
							$theory_paper_count++;
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
							}

							if($new_exam_form->theory_marks<$new_exam_form->min_theory_marks  && $new_exam_form->theory_marks!=''){
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
								$fail_count++;
								$fail_tot_marks += $new_exam_form->theory_marks;
								$require_tot_marks += $new_exam_form->min_theory_marks;
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
						if($new_exam_form->type!='theory'){
							$p_paper_count++;
							$total_paper_marks +=$new_exam_form->max_theory_marks+$new_exam_form->max_internal_marks; 
							$total_marks_obt += $new_exam_form->p_marks+$new_exam_form->int_marks;
							if($new_exam_form->p_marks=='' || $new_exam_form->p_marks=='N'){
								$rw_count++;
							}
							if($new_exam_form->p_marks=='ABS'){
								$p_abs_count++;
							}
							if($new_exam_form->p_marks<$new_exam_form->min_theory_marks){
								$p_fail_count++;
								array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
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
					if ($fail_count==0 && $rw_count==0 && $p_fail_count==0 && $int_fail_count==0 && $theory_abs_count==0) {
						$final_result = "PASS";
					}else{
						$require_grace_marks = $require_tot_marks-$fail_tot_marks;
      // tot 3 grace marks in 2 subjects
						if ($fail_count<3 && $require_grace_marks<4 && $int_fail_count==0 && $p_fail_count==0 && $rw_count==0 && $theory_abs_count==0 && $p_abs_count==0 &&  $int_abs_count==0) {
							$check_grace_marks = true;
							$final_result = "PASS BY GRACE";
						}elseif($rw_count>0){
							$final_result = "RW";
						}else{
							$final_result = "FAIL";
						}
					}

					if($page_break_count%1==0 ){
						$page_no++;
						?>
						<tr class="alternate">
							<td scope="row" class="text-center" width="5%">
								<?php echo $i++; ?>
							</td>
							<td class="style6 text-center" scope="row" width="20%">
								<?php echo $student->roll_number; ?>
							</td>
							<td width="9%"></td>
							<td width="30%" scope="row" class="style6" >
								<?php echo $student->name  .' / '.  $student->f_h_name; ?>
							</td>
							<td align="center" width="15%" >
								<?=$final_result; ?>  	
							</td>
							<td align="center" width="10%">					
								<?php echo $total_marks_obt.'/'.$total_paper_marks ?>
							</td>
							<td class="text-center">
								<?php if($check_grace_marks){
									echo " ";
								}elseif(sizeof($atkt_paper_codes_array)==1){
									echo "ATKT in";
									$atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
									foreach($atkt_paper_codes_array as $paper_code){
										echo  "<br>". $paper_code;
									}
								}else{
									echo '';
								} ?>
							</td>
						</tr>
						<?php
					}
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
				<td class="size" align="right">Asst. Registrar</td>
				<td class="size"align="center">Registrar/Controller Of Examination</td>
			</tr>
			<tr>
				<td colspan="2" class="size">Copy of Result Notification is forwarded for information to
					<p style="padding-top: 15px;" class="size">1.Notice Board of the University</p>
				</td>
			</tr>
		</table>