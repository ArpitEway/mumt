
<?php

$page=1;
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

</style>
<body>

	<!-- <p align="right"><?php echo "Page : ". $page; ?></p> -->
	<div style="width:75px;float:left"><img src="<?=base_url('assets/logo.png')?>" ></div>
	<h3 class="text-center" ><strong> Maharishi Mahesh Yogi Vedic Vishwavidyalaya </strong> </h3>
	<p align="center" style="line-height:0px">Head Office: Karaundi, Post-Mahner ,Distt- Katni(MP) Website www.mmyvvdde.com </p>
	<h3 align="center"><strong>Result Notification of</strong> <br><h3>
		<h3 align="center">	<strong><?php echo $students[0]->course_name.' - '. $students[0]->class_name .' Examination '. $students[0]->session ?></strong><br><h3>
			<title>Notification <?php echo $students[0]->course_name?></title> 



			<div class="flex-container">
				<div style="font-size:15px;" >Notification No : <?php echo $notification_no;?></div>

				<div style="font-size:15px;" class="flex">Date : <?php echo $notification_no;?></div>  
			</div>
			<div style="font-size:15px;" align="center">The Result of the following examinees of the above exam is hereby declared as under : <?php echo $notification_no;?></div>
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


 <?php 
                        $this->db->select('*');
                        $this->db->from('new_exam_form');
                        $this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
                        $this->db->where('new_exam_form.student_id',$student->student_id); 
                        $paper_marks = $this->db->get()->result();
                        $check_grace_marks = false;
                        $fail_count = 0;
                        $fali_tot_marks = 0;
                        $require_tot_marks = 0;
                        $tot_marks = 0;
                        foreach($paper_marks as $marks){
                            if($marks->type=='theory'){
                                    $tot_marks += $marks->max_theory_marks;
                                if($marks->theory_marks>=$marks->min_theory_marks){
                                    $result = "Pass";
                                }else{
                                    $result = "Fail";
                                    $fail_count++;
                                    $fali_tot_marks += $marks->theory_marks;
                                    $require_tot_marks += $marks->reg_min_marks;
                                }
                            }else if($marks->type=='practical'){
                                $tot_std_marks += $marks->p_marks;
                                $tot_marks += $marks->max_theory_marks;
                                if($marks->p_marks>=$marks->min_theory_marks){
                                    $result = "Pass";
                                }else{
                                    $result = "Fail";
                                    $fail_count++;
                                    $fali_tot_marks += $marks->p_marks;
                                    $require_tot_marks += $marks->reg_min_marks;
                                }
                            }
                        }
                        // echo 'tot_marks'.$tot_marks;
                        // echo 'tot_std_marks ='.$tot_std_marks;
                        
                      
                        $aggregate_per =   ($tot_std_marks/$tot_marks) * 100;     
                        $require_grace_marks = $require_tot_marks-$fali_tot_marks;
                      
                        if ($fail_count<3 && $require_grace_marks<4  && $aggregate_per) {
                            $check_grace_marks = true;
                        }
                


                        $total_paper_marks = 0;
                        $total_student_marks = 0 ;
                        $result = "";
                        $fail_count = 0;

?>



<table width="100%"  border="1">
	<tbody>
		<?php
		$i=1;
		foreach($students as $student){
			?>
			<tr>

				<td >
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

					$this->db->select('*');
					$this->db->from('new_exam_form');
					$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
					$this->db->where('new_exam_form.student_id',$student->student_id); 
					$paper_marks = $this->db->get()->result();

					$total_int_marks = 0 ;
       // $total_int_obtain_marks = 0;
					$total_obtain_marks = 0;
					foreach($paper_marks as  $marks)
					{  

						if($marks->type=='theory'){

							if($marks->type=="theory"){
								$mx_int_marks=  $marks->min_theory_marks + $marks->min_int_marks;
							}else{
								$mx_int_marks=$marks->min_theory_marks;
							}
							$total_int_marks  += $mx_int_marks ;
							if($marks->type=="theory")
							{
								$obtain_marks1= $marks->theory_marks + $marks->int_marks;
							}else{
								$obtain_marks1= $marks->p_marks;
							}
							$total_obtain_marks+=$obtain_marks1;

						}
						// else if($marks->type=='practical')
						// {


						// 	if($marks->type=="practical"){
						// 		$mx_int_marks=  $marks->min_theory_marks + $marks->min_int_marks;
						// 	}else{
						// 		$mx_int_marks=$marks->min_theory_marks;
						// 	}
						// 	$total_int_marks  += $mx_int_marks ;
						// 	if($marks->type=="theory")
						// 	{
						// 		$obtain_marks1= $marks->theory_marks + $marks->int_marks;
						// 	}else{
						// 		$obtain_marks1= $marks->p_marks;
						// 	}
						// 	$total_obtain_marks+=$obtain_marks1;

						// }
					}

					if($total_obtain_marks >=  $total_int_marks ){

						echo 'Pass'  ;}
						else{
							echo 'Fail'  ;
						}



						?>	  	
					</td>

					<td align="center" width="10%">					

						<?php 

						$this->db->select('*');
						$this->db->from('new_exam_form');
						$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
						$this->db->where('new_exam_form.student_id',$student->student_id); 
						$marks = $this->db->get()->result();

						$total_max_marks = 0 ;
						$total_obtained_marks = 0;
						foreach($paper_marks as  $key =>  $marks)
						{  

							if($marks->type=='theory'){

								if($marks->type=="theory"){
									$mx_marks=  $marks->max_theory_marks + $marks->max_int_marks;
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
							// else if($marks->type=='practical')
							// {

							// 	if($marks->type=="practical"){
							// 		$mx_marks=  $marks->max_theory_marks + $marks->max_int_marks;
							// 	}else{
							// 		$mx_marks=$marks->max_theory_marks;
							// 	}
							// 	$total_max_marks1= $total_max_marks +$mx_marks;
							// 	if($marks->type=="practical")
							// 	{
							// 		$obtain_marks= $marks->theory_marks + $marks->int_marks +  $marks->p_marks ;
							// 	}else{
							// 		$obtain_marks= $marks->p_marks;
							// 	}
							// 	$total_obtained_marks1= $total_obtained_marks +$obtain_marks;

							// }
						}

						echo $total_obtained_marks .' / '. $total_max_marks;

						?>
					</td>

					<td>Remarks</td>		 	


					
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

