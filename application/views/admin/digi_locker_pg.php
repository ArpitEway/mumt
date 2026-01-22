<div id="" class="dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	
    <table id="kt_datatable_scroll" class="table table-striped"  >
	<thead>
    	<tr>
		<th>S.No.</th>
    		<th>ORG_NAME</th>
			<th>ACADEMIC_COURSE_ID</th>
			<th>COURSE_NAME</th>
			<th>STREAM</th>
			<th>SESSION</th>
			<th>REGN_NO</th>
			<th>RROLL</th>
			<th>CNAME</th>
			<th>GENDER</th>
			<th>DOB</th>
			<th>FNAME</th>
			<th>MNAME</th>
			<th>PHOTO</th>
            <th>MRKS_REC_STATUS</th>
			<!-- <th>RESULT</th> -->
			<th>YEAR</th>
			<th>MONTH</th>
			<th>DOI</th>
            <!-- <th>CERT_NO</th> -->
			<th>SEM</th>
			<th>TOT_CREDIT</th>
			<th>TOT_CREDIT_POINTS</th>
			<th>TOT_GRADE_POINTS</th>
			<th>PREV_TOT_CREDIT</th>
			<th>PREV_TOT_GRADE_POINTS</th>
			<!-- <th>GRAND_TOT_CREDIT_POINTS</th> -->
			<th>GRAND_TOT_CREDIT</th>
            <th>CGPA</th>
			<th>REMARKS</th>
			<th>SGPA</th>
			<th>ABC_ACCOUNT_ID</th>
			<th>TERM_TYPE</th>
            <th>TOT_GRADE</th>
            <th>TOT_CGPA</th>
            <th>GRAND_TOT_GRADE_POINTS</th>
            <!-- <th>TOT_CREDIT_HOURS</th> -->
			<th>DEPARTMENT</th>
			<!-- 
			
			
			
			<th>TOT_GRADE</th>
			<th>TOT_CGPA</th> -->
	<?php		
		for($sub=1;$sub<=13;$sub++){
			?>
			<th>SUB<?=$sub?>NM</th>
			<th>SUB<?=$sub?></th>
            <!-- <th>SUB<?php // echo $sub?>_CREDIT_HOURS</th> -->
            <!-- <th>SUB<? //echo $sub?>_CE1_MRKS</th> -->
            <!-- <th>SUB<? //echo $sub?>_CE2_MRKS</th> -->
            <!-- <th>SUB<? //echo $sub?>_TH_MRKS</th> -->
            <!-- <th>SUB<? //echo $sub?>_PR_MRKS</th> -->
            <!-- <th>SUB<?php //echo $sub?>_TOT</th> -->
			<th>SUB<?=$sub?>_GRADE</th>
			<th>SUB<?=$sub?>_CREDIT</th>
			<th>SUB<?=$sub?>_CREDIT_POINTS</th>
			<th>SUB<?=$sub?>_GRADE_POINTS</th>
			<!-- <th>SUB<? //echo $sub?>_REMARKS</th> -->
		<?php }	?>
			

			<!-- <th>SUB2NM</th>
			<th>SUB2</th>
			<th>SUB2_GRADE</th>
			<th>SUB2_CREDIT</th>
			<th>SUB2_CREDIT_POINTS</th>
			<th>SUB2_GRADE_POINTS</th>
			<th>SUB3NM</th>
			<th>SUB3</th>
			<th>SUB3_GRADE</th>
			<th>SUB3_CREDIT</th>
			<th>SUB3_CREDIT_POINTS</th>
			<th>SUB3_GRADE_POINTS</th>
			<th>SUB4NM</th>
			<th>SUB4</th>
			<th>SUB4_GRADE</th>
			<th>SUB4_CREDIT</th>
			<th>SUB4_CREDIT_POINTS</th>
			<th>SUB4_GRADE_POINTS</th>
			<th>SUB5NM</th>
			<th>SUB5</th>
			<th>SUB5_GRADE</th>
			<th>SUB5_CREDIT</th>
			<th>SUB5_CREDIT_POINTS</th>
			<th>SUB5_GRADE_POINTS</th>
			<th>SUB6NM</th>
			<th>SUB6</th>
			<th>SUB6_GRADE</th>
			<th>SUB6_CREDIT</th>
			<th>SUB6_CREDIT_POINTS</th>
			<th>SUB6_GRADE_POINTS</th>
			<th>SUB7NM</th>
			<th>SUB7</th>
			<th>SUB7_GRADE</th>
			<th>SUB7_CREDIT</th>
			<th>SUB7_CREDIT_POINTS</th>
			<th>SUB7_GRADE_POINTS</th>
			<th>SUB8NM</th>
			<th>SUB8</th>
			<th>SUB8_GRADE</th>
			<th>SUB8_CREDIT</th>
			<th>SUB8_CREDIT_POINTS</th>
			<th>SUB8_GRADE_POINTS</th>
			<th>SUB9NM</th>
			<th>SUB9</th>
			<th>SUB9_GRADE</th>
			<th>SUB9_CREDIT</th>
			<th>SUB9_CREDIT_POINTS</th>
			<th>SUB9_GRADE_POINTS</th>
			<th>SUB10NM</th>
			<th>SUB10</th>
			<th>SUB10_GRADE</th>
			<th>SUB10_CREDIT</th>
			<th>SUB10_CREDIT_POINTS</th>
			<th>SUB10_GRADE_POINTS</th>
			<th>SUB11NM</th>
			<th>SUB11</th>
			<th>SUB11_GRADE</th>
			<th>SUB11_CREDIT</th>
			<th>SUB11_GRADE_POINTS</th>
			<th>SUB11_CREDIT_POINTS</th> -->
            <!-- <th>GRAND_TOT_CREDIT_HOURS</th>
            <th>GPA</th> -->
			 <th>ADMISSION_YEAR</th>
			<th>TGPA</th>
			<th>GRAND_TOT_CREDITS</th>
		</tr>
    </thead>
	<tbody>
		
			<?php 
				$sno=1;
            foreach ($rs as $student) {
                $studentDetail = $this->Common_model->getRecordById('student','student_id',$student['student_id']);
				// $course_detail = $this->Common_model->getRecordById('course','course_group_id',$student['course_group_id']);
				$course_detail = $this->Common_model->getRecordById('course_group','id',$student['course_group_id']);
				$class_detail = $this->Common_model->getRecordById('class_master','id',$student['class_id']);
                $gradesheetData = $this->GradeSheet_old_model_pg->view_result_grade_for_dg_locker($student['student_id'],$student['course_group_id'],$student['class_id'],$student['university_mode'],$student['id']);
			
                //previous data
                
                $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$student['course_group_id'],'mode'=>'Semester'));
             
               $count = 0;
               $prev_tot_crdit =0;
               $prev_tot_grade =0;
               $grand_tot_credit=0;
			   $prev_tot_credit_point=0;
               $class_order = 1;
               $admission_year = explode(" ", $studentDetail->session);
            //    $cgpa = number_format((float)$gradesheetData['agpa'], 2, '.', '');
                foreach($classes as $cls){
                   $count++;
                   if($cls->id < $student['class_id']){
                    $class_order = $cls->class_order;
                   }
				   
                  if($cls->id < $student['class_id']){
					$old_data = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student['student_id'],'class_id'=>$cls->id));
                    $gradeData   = $this->GradeSheet_old_model_pg->view_old_results($student['student_id'],$student['course_group_id'],$cls->id,$student['university_mode'],$old_data[0]->id);
				// print_r( $gradeData);die;
                    $prev_tot_crdit += $gradeData['tot_credit'];
                    $prev_tot_grade += $gradeData['total_grade_point'];
					// echo $prev_tot_crdit."---".$prev_tot_grade."<br>";
					$prev_tot_credit_point += $gradeData['credit_point'];
                    // $cgpa += number_format((float)$gradeData['agpa'], 2, '.', '');
                  }
                
                }
                 	$cgpa = number_format((float)(((int)$prev_tot_credit_point + (int)$gradesheetData['credit_point'])/((int)$prev_tot_crdit + (int)$gradesheetData['tot_credit'])), 2, '.', '');  

                // print_r($gradesheetData);die;
                $exam_arr=explode(" ",$student['exam_year']);
				$session=$exam_arr[1]-1;
				$session_data=$session.'-'.$exam_arr[1];
				$class_name = explode(" ",$class_detail->class_name);
				if($studentDetail->gender=='Male')
				{
					$gender='M';
				}
				elseif($studentDetail->gender=='Female'){
					$gender='F';
				}

				$course_name_full = $studentDetail->course_name; // e.g., "Master of Arts (Economics)"

				// Use regex to extract course name and stream
				preg_match('/^(.*?)\s*\((.*?)\)$/', $course_name_full, $matches);
				$course_name = $matches[1] ?? $course_name_full;
				$stream = $matches[2] ?? '';

                // echo "<tr><td>".$sno++."</td><td>".$studentDetail->center_name."  </td> <td>".$course_detail->paper_code_pattern."</td><td>".$course_name."</td><td>".$stream."</td> ";
                // //<td>".$student['student_id']." </td>
                // echo "<td>".$session_data." </td><td>".$student['enrollment_no']." </td><td>".$student['roll_no']." </td><td>".$student['name']." </td>"."<td>".$gender." </td>"." <td>".$studentDetail->dob." </td><td>".$student['f_h_name']." </td><td>".$student['mother_name']." </td><td></td><td>O</td><td>".$gradesheetData['result']." </td><td>".$exam_arr[1]." </td><td>".$exam_arr[0]." </td><td></td><td></td><td>".$class_name[0]."</td><td>".$gradesheetData['tot_credit']." </td><td>". $gradesheetData['credit_point']." </td><td>".$gradesheetData['total_grade_point']."</td><td>".$prev_tot_crdit."</td><td></td><td>".($gradesheetData['credit_point']+$prev_tot_credit_point)."</td><td>".($gradesheetData['tot_credit'] +$prev_tot_crdit)."</td>";
				echo "<tr>";
				echo "<td>".$sno++."</td>";
				echo "<td>".$studentDetail->center_name."</td>";
				echo "<td>".$course_detail->paper_code_pattern."</td>";
				echo "<td>".$course_name."</td>";
				echo "<td>".$stream."</td>";
				echo "<td>".$session_data."</td>";
				echo "<td>".$student['enrollment_no']."</td>";
				echo "<td>".$student['roll_no']."</td>";
				echo "<td>".$student['name']."</td>";
				echo "<td>".$gender."</td>";
				echo "<td>".$studentDetail->dob."</td>";
				echo "<td>".$student['f_h_name']."</td>";
				echo "<td>".$student['mother_name']."</td>";
				echo "<td>".$student['photo']."</td>";
				echo "<td>O</td>";
				// echo "<td>".$gradesheetData['result']."</td>";
				echo "<td>".$exam_arr[1]."</td>";
				echo "<td>".$exam_arr[0]."</td>";
				echo "<td></td>";
				// echo "<td></td>";
				echo "<td>".$class_name[0]."</td>";
				echo "<td>".$gradesheetData['tot_credit']."</td>";
				echo "<td>".$gradesheetData['credit_point']."</td>";
				echo "<td>".$gradesheetData['total_grade_point']."</td>";
				echo "<td>".$prev_tot_crdit."</td>";
				echo "<td>". $prev_tot_grade ."</td>";
				// echo "<td>".($gradesheetData['credit_point'] + $prev_tot_credit_point)."</td>";
				echo "<td>".($gradesheetData['tot_credit'] + $prev_tot_crdit)."</td>";

				?>
                <td><?= $cgpa ?></td>
                <td></td>
                <td><?=number_format((float)$gradesheetData['agpa'], 2, '.', '')?></td>
				<td><?=$studentDetail->abc_id?></td>
                <?php
               $tot_grade_point=((int)$gradesheetData['total_grade_point'] + (int)$prev_tot_grade);
                echo "<td>SEMESTER</td>";
            //    echo "<td></td><td></td><td></td>";//<td></td>
                echo "<td>".$tot_grade_point."</td>";
                echo "<td>".$cgpa."</td>";
                echo "<td>".$tot_grade_point."</td>";
                echo " <td>".$studentDetail->center_name."  </td> ";
                
                echo $gradesheetData['html'];
				$td_count=0;
				$td_count=$gradesheetData['papercount']*6;
			
				 $loop_td_count=78-$td_count;
				for($c=1;$c<=$loop_td_count;$c++){
					echo "<td> </td>";
				}
				//<td></td><td></td>
                echo "<td>".$admission_year[1]."</td><td></td><td>".($prev_tot_crdit + $gradesheetData['tot_credit'])."</td>";
				// $sess_arr=explode(" ",$student['session']);
				// echo "<td>".$sess_arr[1]."</td>";
				// echo "<td></td>";
				// echo "<td>".$gradesheetData['tot_credit']."</td>";
                echo "</tr>";
                $i++;
               //break;
            }
            ?>
		</tbody>
	</table>
</div>