<div id="" class="dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	
    <table id="kt_datatable_scroll" class="table table-striped" border="1">
	<thead>
    	<tr>
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
			<th>YEAR</th>
			<th>MONTH</th>
			<th>DOI</th>
			<th>SEM</th>
			<th>TOT_CREDIT</th>
			<th>TOT_CREDIT_POINTS</th>
			<th>TOT_GRADE_POINTS</th>
			<th>PREV_TOT_CREDIT</th>
			<th>PREV_TOT_GRADE_POINTS</th>
			<th>GRAND_TOT_CREDIT</th>
			<th>CGPA</th>
			<th>REMARKS</th>
			<th>SGPA</th>
			<th>ABC_ACCOUNT_ID</th>
			<th>TERM_TYPE</th>
			<th>TOT_GRADE</th>
			<th>TOT_CGPA</th>
			<th>GRAND_TOT_GRADE_POINTS</th>
			<th>DEPARTMENT</th>
			<th>SUB1NM</th>
			<th>SUB1</th>
			<th>SUB1_GRADE</th>
			<th>SUB1_CREDIT</th>
			<th>SUB1_CREDIT_POINTS</th>
			<th>SUB1_GRADE_POINTS</th>
			<th>SUB2NM</th>
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
			<th>SUB11_CREDIT_POINTS</th>
			<th>SUB12NM</th>
			<th>SUB12</th>
			<th>SUB12_GRADE</th>
			<th>SUB12_CREDIT</th>
			<th>SUB12_GRADE_POINTS</th>
			<th>SUB12_CREDIT_POINTS</th>
			<th>SUB13NM</th>
			<th>SUB13</th>
			<th>SUB13_GRADE</th>
			<th>SUB13_CREDIT</th>
			<th>SUB13_GRADE_POINTS</th>
			<th>SUB13_CREDIT_POINTS</th>
			<th>ADMISSION_YEAR</th>
			<th>TGPA</th>
			<th>GRAND_TOT_CREDITS</th>
		</tr>
    </thead>
	<tbody>
		
			<?php 
            foreach ($rs as $student) {
                $studentDetail = $this->Common_model->getRecordById('student','student_id',$student['student_id']);
				$course_detail = $this->Common_model->getRecordById('course','course_group_id',$student['course_group_id']);
				$class_detail = $this->Common_model->getRecordById('class_master','id',$student['class_id']);
                $gradesheetData = $this->Gradesheet_old_model->view_result_grade_for_dg_locker($student['student_id'],$student['course_group_id'],$student['class_id'],$student['university_mode'],$student['id']);
                $exam_arr=explode(" ",$student['exam_year']);
				$session=$exam_arr[1]-1;
				$session_data=$session.'-'.$exam_arr[1];
				if($studentDetail->gender=='Male')
				{
					$gender='M';
				}
				elseif($studentDetail->gender=='Female'){
					$gender='F';
				}
                echo "<tr><td>".$studentDetail->center_name."  </td> <td>".$course_detail->course_code."</td><td>".$studentDetail->course_name."  </td> <td></td> ";
                //<td>".$student['student_id']." </td>
                echo "<td>".$session_data." </td><td>".$student['enrollment_no']." </td><td>".$student['roll_no']." </td><td>".$student['name']." </td>"."<td>".$gender." </td>"." <td>".$studentDetail->dob." </td><td>".$student['f_h_name']." </td><td>".$student['mother_name']." </td><td>".$student['photo']." </td><td> O </td><td>".$exam_arr[1]." </td><td>".$exam_arr[0]." </td><td></td><td></td><td>".$gradesheetData['tot_credit']." </td><td>". $gradesheetData['credit_point']." </td><td>".$gradesheetData['total_grade_point']."</td><td></td><td></td><td></td>";
                ?>
                <td><?=number_format((float)$gradesheetData['agpa'], 2, '.', '')?></td>
                <?php
                echo "<td></td>";
                ?>
                <td><?=number_format((float)$gradesheetData['agpa'], 2, '.', '')?></td>
                <?php
                echo "<td></td>";
                echo "<td>".$class_detail->class_name."</td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo " <td>".$studentDetail->center_name."  </td> ";
                echo $gradesheetData['html'];
				$td_count=0;
			//	echo $gradesheetData['papercount'];
				 $td_count=($gradesheetData['papercount']-2)*6;
				
				$add_extra=114;
				//$add_extra=108;
			
				  $loop_td_count=$add_extra-36-$td_count;
				for($c=1;$c<=$loop_td_count;$c++){
					echo "<td> </td>";
					
				}
				$sess_arr=explode(" ",$student['session']);
				echo "<td>".$sess_arr[1]."</td>";
				echo "<td></td>";
				echo "<td>".$gradesheetData['tot_credit']."</td>";
                echo "</tr>";
                $i++;
               //break;
            }
            ?>
		</tbody>
	</table>
</div>