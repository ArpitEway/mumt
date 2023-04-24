<div class="container mt-5" >	
	<table id="kt_datatable_scroll" class="table table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Course Name</th>
				<th>Stream Language</th>
				<th>REGN_NO</th>
				<th>RROLL - roll no</th>
				<th>CandidateNAME</th>
				<th>GENDER</th>
				<th>DOB</th>
				<th>FatherNAME</th>
				<th>MotherNAME</th>
				<th>YEAR(session)</th>
				<th>SEM/year</th>
				<th>EXAM_TYPE</th>
				
				<?php
				$count = 1;
				while ($count <= 5) {
					?>
					<th><?php echo 'SUB'.$count.'name';?></th>
					<th><?php echo 'SUB'.$count.'_TH_MAX';?></th>
					<th><?php echo 'SUB'.$count.'_PR_MAX';?></th> 
					<th> <?php echo 'SUB'.$count.'_TH_MRKSobtain';?></th>
					<th><?php echo 'SUB'.$count.'_PR_MRKSobtain';?></th>
					<th><?php echo 'SUB'.$count.'_TOTal'.$count;?></th>
				
					<?php
					$count++; 
					
				} ?>		
			</tr>
		</thead>
		<tbody>
				<?php
				$j = 1;
				foreach($studentData as $student){
                   
                    $course = explode('(',$student->course_name);
                     $stream = rtrim($course[1],')');
					?>
					<tr>
						<td><?=$j++; ?></td>
						<td><?php echo $course[0]; ?></td>
                        <td><?php echo $stream; ?></td>
                        <td><?php echo $student->enrollment_no; ?> </td>
                        <td><?php echo $student->roll_no; ?> </td>
                        <td><?php echo $student->name; ?> </td>
                        <td><?php echo $student->gender; ?> </td>
                        <td><?php echo date('d-m-Y',strtotime($student->dob)); ?> </td>
					    <td><?php echo $student->f_h_name; ?> </td>
                        <td><?php echo $student->mother_name; ?> </td>
                        <td><?php echo '2021'; ?> </td>
                        <td><?php echo $this->Common_model->getClassNameByClassId($student->class_id) ?></td>
						<td><?php echo ($student->university_mode == 'REG')?'Regular':'Private';?></td>
						
                        <?php
                        $i=0;
                        $this->db->order_by('p_order');
                        $marksdatas= $this->Common_model->getRecordByWhere('old_result_data',array('student_id'=>$student->student_id,'exam_data_id' => $student->id));
                        foreach($marksdatas as $marksdata){
                            $i++;
                            if($marksdata->type=='theory'){
                                    
                                    ?>
                                    <td><?php echo $marksdata->paper_name; ?> </td>
                                    <td><?php echo $marksdata->max_theory_marks; ?> </td>
                                    <td><?php echo $marksdata->max_int_marks; ?> </td>
                                    <td><?php echo $marksdata->theory_marks; ?> </td>
                                    <td><?php echo $marksdata->int_marks; ?> </td>
                                    <td><?php echo $marksdata->theory_marks+$marksdata->int_marks; ?> </td>	
                                    <?php 
                                }else{
                                    ?>
                                    <td><?php echo $marksdata->paper_name; ?> </td>
                                    <td><?php echo $marksdata->max_theory_marks; ?> </td>
                                    <td><?php echo $marksdata->max_int_marks; ?> </td>
                                    <td><?php echo $marksdata->p_marks; ?></td>
                                    <td><?php echo $marksdata->int_marks; ?> </td>
                                    <td><?php echo $marksdata->p_marks+$marksdata->int_marks; ?></td>
                                <?php  }	?>
                                
                                    
                            <?php }
                                    ?>
                    </tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

