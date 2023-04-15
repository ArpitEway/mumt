<div class="mt-5" >	
	<table id="kt_datatable_scroll" class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Course Name</th>
				<th>Stream Language</th>
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
                <th>DIVISION</th>
				<!-- <th>SEM/year</th> -->
				<th>EXAM_TYPE</th>
				
				<?php
				$count = 1;
				while ($count <= 13) {
					?>
					<th><?php echo 'SUB'.$count.'NM';?></th>
                    <th><?php echo 'SUB'.$count.' Code';?></th>
					<th><?php echo 'SUB'.$count.'_TH_MAX';?></th>
					<th><?php echo 'SUB'.$count.'_PR_MAX';?></th> 
                    <th><?php echo 'SUB'.$count.'_CE_MAX';?></th> 
					<th> <?php echo 'SUB'.$count.'_TH_MRKS';?></th>
					<th><?php echo 'SUB'.$count.'_PR_MRKS';?></th>
                    <th><?php echo 'SUB'.$count.'_CE_MRKS';?></th>
					<!-- <th><?php //echo 'SUB'.$count.'_TOTal'.$count;?></th> -->
                    <th><?php echo 'SUB'.$count.'_STATUS';?></th>
                    <th><?php echo 'SUB'.$count.'_REMARKS';?></th>
				
					<?php
					$count++; 
					
				} ?>		
			</tr>
		</thead>
		<tbody>
				<?php
				$j = 1;
				foreach($studentData as $student){
                    if($student->exam_result == "FAIL"){
                        $division = "FAIL";
                    }
                    else if($student->percentage>=60){
                        $division = "First";
                      }elseif($student->percentage<60 && $student->percentage>=40){
                        $division  = "Second";
                      }else{
                        $division = "Third";
                      }
                  
                   
                    $course = explode('(',$student->course_name);
                     $stream = rtrim($course[1],')');
					?>
					<tr>
						<td><?=$j++; ?></td>
						<td><?php echo $course[0]; ?></td>
                        <td><?php echo $stream; ?></td>
                        <td><?php echo $student->exam_year; ?></td>
                        <td><?php echo $student->enrollment_no; ?> </td>
                        <td><?php echo $student->roll_no; ?> </td>
                        <td><?php echo $student->name; ?> </td>
                        <td><?php echo $student->gender; ?> </td>
                        <td><?php echo date('d-m-Y',strtotime($student->dob)); ?> </td>
					    <td><?php echo $student->f_h_name; ?> </td>
                        <td><?php echo $student->mother_name; ?> </td>
                        <td><?php echo $student->exam_result; ?> </td>
                        <td><?php echo '2022'; ?> </td>
                        <td><?php echo $division; ?> </td>
                        <!-- <td><?php //echo $this->Common_model->getClassNameByClassId($student->class_id) ?></td> -->
						<td><?php echo ($student->university_mode == 'REG')?'Regular':'Private';?></td>
						
                        <?php
                        $i=0;
                        $this->db->order_by('p_order');
                        $marksdatas= $this->Common_model->getRecordByWhere('old_result_data',array('student_id'=>$student->student_id,'exam_data_id' => $student->id));
                        // echo count($marksdatas).'<br>';
                        foreach($marksdatas as $marksdata){
                            // echo '<pre>';
                            // print_r($marksdata);
                            $i++;
                            if($marksdata->type=='theory'){
                                    
                                    ?>
                                    <td><?php echo $marksdata->paper_name; ?> </td>
                                    <td><?php echo $marksdata->paper_code; ?> </td>
                                    <td><?php echo ($marksdata->max_theory_marks == 0)?'':$marksdata->max_theory_marks; ?> </td>
                                    <td><?php echo ($marksdata->max_p_marks == 0)?'':$marksdata->max_p_marks; ?> </td>
                                    <td><?php echo ($marksdata->max_int_marks==0)?'':$marksdata->max_int_marks; ?> </td>
                                    <td><?php echo ($marksdata->theory_marks == 'N')?'':$marksdata->theory_marks; ?> </td>
                                    <td><?php echo ($marksdata->p_marks == 'N')?'':$marksdata->p_marks; ?></td>
                                    <td><?php echo ($marksdata->int_marks == 'N')?'':$marksdata->int_marks; ?> </td>
                                    <!-- <td><?php //echo $marksdata->theory_marks+$marksdata->int_marks; ?> </td>	 -->
                                    <td><?php echo $marksdata->result; ?> </td>
                                    <td></td>
                                    <?php 
                                }else{
                                    ?>
                                    <td><?php echo $marksdata->paper_name; ?> </td>
                                    <td><?php echo $marksdata->paper_code; ?> </td>
                                    <td><?php echo ($marksdata->max_p_marks == 0)?'':$marksdata->max_p_marks; ?> </td>
                                    <td><?php echo ($marksdata->max_theory_marks == 0)?'':$marksdata->max_theory_marks; ?> </td>
                                    <td><?php echo ($marksdata->max_int_marks==0)?'':$marksdata->max_int_marks; ?> </td>
                                    <td><?php echo ($marksdata->theory_marks == 'N')?'':$marksdata->theory_marks; ?> </td>
                                    <td><?php echo ($marksdata->p_marks == 'N')?'':$marksdata->p_marks; ?></td>
                                    <td><?php echo($marksdata->int_marks == 'N')?'':$marksdata->int_marks; ?> </td>
                                    <!-- <td><?php //echo $marksdata->p_marks+$marksdata->int_marks; ?></td> -->
                                    <td><?php echo $marksdata->result; ?> </td>
                                    <td> </td>
                                    <?php
                                }
                        
                                  }	
                                  if(count($marksdatas)<13){
                                    $count_cell = 13 -count($marksdatas);
                                    $x=1;
                                    while ($x <=$count_cell){
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                                       echo "<td>".''."</td>";
                       
                                    $x++;}
                       
                               }
                                 
                                  
                                  ?>
                                
                                    
                         
                    </tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

