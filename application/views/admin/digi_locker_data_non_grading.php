<div id="" class="dt-responsive">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	
    <table id="kt_datatable_scroll" class="table table-striped"  >
	<thead>
    	<tr>
		<th>S.No.</th>
    		<th>ORG_NAME</th>
			<th>ACADEMIC_COURSE_ID</th>
			<th>COURSE_NAME</th>
            <th>COURSE_NAME_L</th>
			<th>STREAM</th>
			<th>SESSION</th>
			<th>REGN_NO</th>
			<th>RROLL</th>
			<th>CNAME</th>
			<th>GENDER</th>
			<th>DOB</th>
			<th>FNAME</th>
			<th>MNAME</th>
			<th>RESULT</th>
			<th>YEAR</th>
			<th>MONTH</th>
            <th>DIVISION</th>
            <th>GRADE</th>
            <th>PERCENT</th>
            <th>DOI</th>
			
			<th>SEM</th>
			<th>EXAM_TYPE</th>
			<th>TOT</th>
			<th>TOT_MARKS</th>
			<th>GRAND_TOT_MAX</th>
			<th>GRAND_TOT_MRKS</th>
			<th>REMARKS</th>
			<th>ABC_ACCOUNT_ID</th>
			<th>TERM_TYPE</th>
			<th>TOT_GRADE</th>

	<?php		
		for($sub=1;$sub<=12;$sub++){
			?>
			<th>SUB<?=$sub?>NM</th>
			<th>SUB<?=$sub?></th>
			<th>SUB<?=$sub?>_TH_MAX</th>
			<th>SUB<?=$sub?>_PR_MAX</th>
			<th>SUB<?=$sub?>_CE_MAX</th>
			<th>SUB<?=$sub?>_TH_MRKS</th>
			<th>SUB<?=$sub?>_PR_MRKS</th>
            <th>SUB<?=$sub?>_CE_MRKS</th>
            <th>SUB<?=$sub?>_TOT</th>
		<?php }	?>
            <th>AADHAAR_NAME</th>
            <th>ADMISSION_YEAR</th>
		</tr>
    </thead>
	<tbody>
		
			<?php 
				$sno=1;
            foreach ($rs as $student) {
                $studentDetail = $this->Common_model->getRecordById('student','student_id',$student['student_id']);
				$course_detail = $this->Common_model->getRecordById('course','course_group_id',$student['course_group_id']);
				$class_detail = $this->Common_model->getRecordById('class_master','id',$student['class_id']);
                
                //previous data
                
                $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$student['course_group_id'],'mode'=>'Semester'));
                $admission_year = explode(" ",$studentDetail->session);
                $this->db->limit(1);
                $this->db->order_by('id','desc');
                $old_data =  $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student['student_id'],'class_id'=>$student['class_id']))[0];

                $percentage =  round(($old_data->obtain_marks/$old_data->total_marks)*100,2);
                if($percentage>=60){
                  $division = "First";
                }elseif($percentage<60 && $percentage>=40){
                  $division  = "Second";
                }else{
                  $division = "Third";
                }
               
            //    $count = 0;
            //    $prev_tot_crdit =0;
            //    $prev_tot_grade =0;
            //    $grand_tot_credit=0;
			//    $prev_tot_credit_point=0;
            //    $class_order = 1;
            //    $cgpa = number_format((float)$gradesheetData['agpa'], 2, '.', '');
            //     foreach($classes as $cls){
            //        $count++;
            //        if($cls->id < $student['class_id']){
            //         $class_order = $cls->class_order;
            //        }
            //       if($cls->id < $student['class_id']){
            
            //         $prev_tot_crdit += $gradeData['tot_credit'];
            //         $prev_tot_grade += $gradeData['total_grade_point'];
			// 		$prev_tot_credit_point += $gradeData['credit_point'];
            //         $cgpa += number_format((float)$gradeData['agpa'], 2, '.', '');
            //       }
                
            //     }
                   

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
                echo "<tr><td>".$sno++."</td><td>".$studentDetail->center_name."  </td> <td>".$course_detail->course_code."</td><td>".$studentDetail->course_name."  </td> <td></td> <td></td>";
                //<td>".$student['student_id']." </td>
                echo "<td>".$session_data." </td><td>".$student['enrollment_no']." </td><td>".$student['roll_no']." </td><td>".$student['name']." </td>"."<td>".$gender." </td>"." <td>".$studentDetail->dob." </td><td>".$student['f_h_name']." </td><td>".$student['mother_name']." </td><td>".$old_data->exam_result." </td><td>".$exam_arr[1]." </td><td>".$exam_arr[0]." </td><td></td><td></td><td></td><td></td><td>".$class_name[0]."</td><td>".$student['university_mode']."</td><td>".$old_data->total_marks."</td><td>".$old_data->obtain_marks."</td><td></td><td></td>";
                ?>
                
                <td></td>
				<td><?=$studentDetail->abc_id?></td>
                <?php
               $old_papers = $this->Common_model->get_all_old_papers($student['student_id'],$student['class_id'],$old_data->id);
                echo "<td>SEMESTER</td>";
               
                echo " <td></td> ";
                $sub_total =0;
                foreach($old_papers as $old_paper){
                    if($class_detail->internal == 'Y' && $old_paper['type'] == 'theory' || ($old_paper['type'] == 'theory' && $old_paper['int_marks'] !="N") ){
                        $sub_total = $old_paper['theory_marks']+$old_paper['int_marks'];
                    }elseif($class_detail->$practical_internal_marks == 'Y' && $old_paper['type'] == 'Practical'){
                        $sub_total = $old_paper['p_marks']+$old_paper['int_marks'];
                    }elseif($old_paper['type'] == 'Practical'){
                        $sub_total = $old_paper['p_marks'];
                    }elseif($old_paper['type'] == 'Sessional'){
                        $sub_total = $old_paper['int_marks'];
                    }else{
                        $sub_total = $old_paper['theory_marks'];
                    }
                 
                    echo "<td>".$old_paper['paper_name']."</td>";
                    echo "<td>".$old_paper['paper_code']."</td>";
                    echo "<td>".$old_paper['max_theory_marks']."</td>";
                    echo "<td>".$old_paper['max_p_marks']."</td>";
                    echo "<td>".$old_paper['max_int_marks']."</td>";
                    echo "<td>".$old_paper['theory_marks']."</td>";
                    echo "<td>".$old_paper['p_marks']."</td>";
                    echo "<td>".$old_paper['int_marks']."</td>";
                    echo "<td>".$sub_total."</td>";
                }
               
				$td_count=0;
				$td_count=count($old_papers)*9;
			
				 $loop_td_count=108-$td_count;
				for($c=1;$c<=$loop_td_count;$c++){
					echo "<td> </td>";
				}
				
				 echo "<td></td><td>".$admission_year[1]."</td>";
			
                echo "</tr>";
                $i++;
               //break;
            }
            ?>
		</tbody>
	</table>
</div>