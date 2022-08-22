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
   foreach($students as $student){

    
             
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
