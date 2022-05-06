
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
	<h3 class="text-center" ><strong> Maharishi Mahesh Yogi Vedic Vishwavidyalaya </strong> </h3>
	<p align="center" style="line-height:0px">Head Office: Karaundi, Post-Mahner ,Distt- Katni(MP) Website www.mmyvvdde.com </p>
	<h3 align="center"><strong>Result Notification of</strong> <br><h3>
		<h3 align="center">	<strong><?php echo $students[0]->course_name.' - '. $students[0]->class_name .' Examination '. $exam_session?></strong><br><h3>
			<title>Notification <?php echo $students[0]->course_name?></title> 



			<div class="flex-container">
				<div style="font-size:15px;" >Notification No : <?php echo $notification;?></div>

				<div style="font-size:15px;" class="flex">Date : <?php echo $date;?></div>  
			</div>
			<div style="font-size:15px;" align="center">The Result of the following examinees of the above exam is hereby declared as under : </div>
			<hr>


			<table width="100%"  border="1">

				<tr bgcolor="#FFFF00">
					<th scope="row" width="5%"> S.No. </th>
					<th scope="row" width="20%"><span class="style5">Roll No.</span></th>
   <!-- <th scope="row"><p class="style5">MS No.</p></th>
   --> <th style="text-align:left" scope="row"  width="30%"><span class="style5" >Name and F/H Name</span></th>
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
				    $total_theory_abs_count=0;
					$total_int_abs_count=0;
					$fail_count = 0;
					$check_grace_marks = false;	
					$get_tot_marks = 0;
					$require_tot_marks = 0;
					$ATKT_paper_codes = array();
					$Withheld = false; 
					$ATKT_paper_codes = array(); 
                    $paper_marks = $this->Common_model->notification_marks_details_($student->student_id);
					
					foreach($paper_marks as  $marks){
								if($marks->type=="theory" )
							{
								if($marks->theory_marks==''){
									$Withheld = true;
								}
								if($marks->theory_marks>=$marks->min_theory_marks){
									$result = "Pass";
								}
								if($marks->theory_marks=="ABS"){
									$fail_count++;
									$abs_count++;
								

									array_push( $ATKT_paper_codes,$marks->paper_code );
								}
								if($marks->theory_marks<$marks->min_theory_marks){
									$fail_count++;
									$get_tot_marks += $marks->theory_marks;
									$require_tot_marks += $marks->min_theory_marks;
									 array_push( $ATKT_paper_codes,$marks->paper_code );
								}
							}
							else if($marks->type=='practical'){
								if($marks->p_marks==''){
									$Withheld = true;
								}elseif($marks->p_marks>=$marks->min_theory_marks){
									$result = "Pass";	
								}else{
									$result = "Fail";
									$fail_count +=5;

								}
							}
                     }
			              $require_grace_marks = $require_tot_marks-$get_tot_marks;
			              // echo $fail_count;
			              // echo $require_grace_marks;
							if ($fail_count<3 && $require_grace_marks<4  && $abs_count==0) {
								 $check_grace_marks = true;
							}
						
						

			?>
			<?php 
			if($page_break_count%1==0 ){
				$page_no++ ;

				?>

			<tr class="alternate">
           
				<td scope="row" width="5%">
					<?php echo $i++; ?>
				</td>
				<td class="style6" scope="row" width="20%">
					<?php echo $student->roll_number; ?>
				</td>
				<td width="30%" scope="row" class="style6" >
					<?php echo $student->name  .' / '.  $student->f_h_name; ?>
				</td>
				<td align="center" width="15%" >

					<?php

					if($marks->type=="theory" )
					{
						if($Withheld  && $abs_count!=0){
							echo 'RW';
						}else{
							if($fail_count>0 || $abs_count>0){
								echo ($check_grace_marks) ? 'Pass by grace' : 'Fail';
							}else{
								echo 'Pass';
							}
						}}
						elseif($marks->type=="practical" ){

							if($Withheld){
								echo 'RW';
							}else{
								if($fail_count>0){
									echo ($check_grace_marks) ? 'Pass by grace' : 'Fail';
								}else{
									echo 'Pass';
								}
							}
						}


						?>	  	
					</td>

					<td align="center" width="10%">					

						<?php 

						$paper_marks = $this->Common_model->notification_marks_details_($student->student_id);

						$total_max_marks = 0 ;
						$total_obtained_marks = 0;
						
						foreach($paper_marks as  $key =>  $marks)
						{  

							if($marks->type=='theory'){

								if($marks->type=="theory"){
									$mx_marks=  $marks->max_theory_marks + $marks->max_internal_marks;
								}else{
									$mx_marks=$marks->max_theory_marks;
								}
								$total_max_marks+= $mx_marks;
								if($marks->type=="theory")
								{
									$obtain_marks= $marks->theory_marks + $marks->int_marks;
								}else{
									$obtain_marks= $marks->p_marks;
								}
								$total_obtained_marks+=  $obtain_marks;

							}
							else if($marks->type=='practical')
							{

								if($marks->type=="practical"){
									$mx_marks=  $marks->max_theory_marks  ;

									$total_max_marks+=$mx_marks;
								}
								else{

									$obtain_marks= $marks->p_marks;
									$total_obtained_marks+= $obtain_marks;
								}
							}
						}

						echo $total_obtained_marks .' / '. $total_max_marks;

						?>
					</td>

					<td>
						<?php

						if($marks->type=="theory" )
						{
							
							if($marks->theory_marks=='' ){
								echo "RW";
							}
							elseif(empty($ATKT_paper_codes)) {
								$remark='';
							
							}		
							else{ 

								if(($require_grace_marks>=4 || $abs_count!=0 ) &&  $marks->theory_marks!=''){
								
									$remark= ($check_grace_marks) ? 'Fail' : 'ATKT IN';
									echo $remark;

									foreach($ATKT_paper_codes as $paper_code){
										echo  "<br>". $paper_code ;
									}  

								}

							}			
						}

						elseif($marks->type=='practical'){
							if($marks->p_marks=='' || $marks->p_marks=='N'){
								echo "RW";
							}
							elseif($marks->p_marks>=$marks->min_theory_marks){
								$remark='';
							}
							else{ 

								if($fail_count>3){
									$remark= ($check_grace_marks) ? 'Fail' : 'ATKT IN';
									echo $remark;

									foreach($ATKT_paper_codes as $paper_code){
										echo  "<br>". $paper_code ;
									}  

								}

							}	
						}
						?>	
				</td>
					</tr>
       <?php
		}}
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
				<!--<p style="line-height:2px">RWPM-Project Marks Not Received</p>-->
				<p class="size" style="line-height:2px">UFM-Unfair Means</p>
				<p class="size" style="line-height:2px">GR-Grace Mark In One Theory Paper For Passing</p>
				<p class="size" style="line-height:2px">VCG-Vice-Chancellor's One Grace Mark In Division</p>
			</td>
		</tr>
		<tr><td>&nbsp;</td> <td class="size" align="right">Asst. Registrar</td><td class="size"align="center">Registrar/Controller Of Examination</td></tr>
		<tr><td colspan="2" class="size">Copy of Result Notification is forwarded for information to

			<p style="padding-top: 15px;" class="size">1.Notice Board of the University</p>
			<p class="size">2.Directorate of Distance Education</p></td></tr>
		</table>

