<table id="kt_datatable_scroll" class="table table-striped nowrap" >
        <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <thead>
            <tr>
               <th>Sno</th>
               <th>Form No.</th>
               <th>Enrollment No </th>
               <th>Student Name</th>
               <th>Course</th>
               <th>Class</th>
               <th>Total Fees</th>
               <th>Action</th>
           </tr>
       </thead>
       <tbody>
         <?php
         $i = 1;
         $exam_fess = 100;
         foreach($documents as $student){

            $where = array(
                'session' =>$student->session,
                'course_group_id' => $student->course_group_id,
            );
            $fees = $this->Common_model->getRecordByWhere('course',$where);
              if($student->university_mode=="REG"){
                $program_fees =  $fees[0]->program_fees;    
                $exam_fees =  $fees[0]->exam_fees;    
              }else{
                $program_fees =  $fees[0]->p_program_fees;    
                $exam_fees =  $fees[0]->p_exam_fees;    
              }      
            ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo $student->student_id; ?> </td>
               <td><?php echo $student->enrollment_no; ?> </td>
               <td><?php echo $student->name; ?> </td>
               <td><?php echo $student->course_name; ?> </td>
               <td><?php echo $student->class_name; ?> </td>
               <td><?php 
               if($student->demo=='Y'){
                echo   $exam_fees ;
               }else{
                echo   $program_fees+$exam_fees; 
                }
           ?> </td>  
            <td>    
            <?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id);
                  $class_id = $this->Common_model->encrypt_decrypt($student->class_id);
             ?>
          <a class="btn btn-primary" href="<?=base_url('backlog_showPapers/'.$student_id .'/'.$class_id)?>">Show Paper</a>
          </td>    
          </tr>         
      <?php
      $i++;
        }
    ?>
</tbody>
</table>
