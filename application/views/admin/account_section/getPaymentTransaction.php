
<div class="text-center">
	<table id="kt_datatable" class="table table-striped dt-responsive" width="80%" >
		<thead>
			<tr>
				<th>#</th>
                <th>F.No</th>
                <th>Session</th>
                <th>Student Name</th>
                <th>Course</th>
                <th>Class</th>
                <th>Prospectus Fees</th>
                <th>Registration Fees</th>
                <th>Program Fees</th>
                <th>Exam Fees</th>
                <th>Backlog Exam Fees</th>
                <th>Late Fees</th>
                <th>Total Fees</th>
                <th>View Detail</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			
			$i = 1;
          
			
			foreach($studentData as $student){
                  $prospectus='-';
            $registration='-';
            $program ='-';
            $exam='-';
            $backlog='-';
            $late='-';
            $total=0;
            $fees =0;
                if($student->fees_head == 'Admission Fees'){
                    $prospectus = $student->form_fees;
                    $registration = ($student->mode =="REG") ?$student->admission_fees:$student->admission_fees;
                    $total = $prospectus + $registration;
                    $late = $student->remark== 'With Late Fees'? $student->amount - $total : '-';
                }elseif($student->fees_head == 'Exam Fees'){
                    $exam = ($student->mode == 'REG')?$student->exam_fees:$student->p_exam_fees;

                if($student->remark == 'Demo Exam Fees' || $student->remark == 'Late Demo Exam Fees'){
                        $program = '-';
                    }else{
                         $program = ($student->mode == 'REG')?$student->program_fees:$student->p_program_fees;
                    }
                  
                    $total = $exam + ($program != '-' ? $program : 0);
                    $late = ($student->remark == 'Late Exam Fees' || $student->remark =='Late Demo Exam Fees') ? $student->amount - $total : '-';
                    
                }elseif($student->fees_head == 'Backlog Exam Fees'){
                    $backlog_id = $this->Common_model->getRecordByWhere('backlog_student', array('student_id' => $student->student_id, 'course_group_id' => $student->course_group_id, 'class_id' => $student->class_id, 'exam_year' => $student->exam_session));
                    $where = array(
                    'course_group_id' => $student->course_group_id,
                    'class_id' => $student->class_id,
                    'backlog_student_id' => $backlog_id[0]->id,
                    'paper_type' =>'Theory',
                    'status' => 'B'
		        );
		
		        $fees = $this->Common_model->getCountByWhere('backlog_exam_form',$where);

                    $backlog = ( $fees < 8)?$fees*100:750;
                    $total = $backlog;
                }elseif($student->fees_head == 'Form Fees'){
                    $prospectus = $student->form_fees;
                   $total = $prospectus;
                }
                   
                ?>
                <tr>

                <td><?php echo $i++; ?></td>
                <td><?php echo $student->student_id; ?></td>
                <td><?php echo $student->session; ?></td>
                <td><?php echo $student->name; ?></td>
                <td><?php echo $student->course_name; ?></td>
                <td><?php echo $this->Common_model->getClassNameByClassId($student->class_id); ?></td>
                <td><?= $prospectus?></td>
                <td><?= $registration?></td>
                <td><?=$program?></td>
                <td><?=$exam?></td>
                <td><?= $backlog?></td>
                <td><?=$late?></td>
                <td><?= $student->amount?></td>
                <td></td>
                </tr>
                <?php
            }
            
            ?>
		</tbody>
	</table>


	<script>

		
	</script>