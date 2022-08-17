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
   $fail_count = 0;
   foreach($documents as $student){

    $old_result_datas = $this->Common_model->getRecordByWhere('backlog_exam_form',array('student_id'=>$student->student_id,'class_id'=>$student->class_id));
    foreach($old_result_datas as $old_result_data)
    {
       if($old_result_data->paper_type=='theory'){
      if($old_result_data->theory_marks<$old_result_data->min_theory_marks)
      {
        $fail_count++; 
      }     
      if($old_result_data->int_marks < $old_result_data->min_int_marks)
      {
        $fail_count++; 
      }  
      }
      else{
       if($old_result_data->p_marks < $old_result_data->min_p_marks)
       {
         $fail_count++; 
       }
       }
       $actual_fess =  $fail_count * $exam_fess;      
       }       
    ?>
    <tr>
     <td><?php echo $i; ?></td>
     <td><?php echo $student->student_id; ?> </td>
     <td><?php echo $student->enrollment_no; ?> </td>
     <td><?php echo $student->name; ?> </td>
     <td><?php echo $student->course_name; ?> </td>
     <td><?php echo $student->class_name; ?> </td>
     <td><?php echo  $actual_fess ; ?> </td>  
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
