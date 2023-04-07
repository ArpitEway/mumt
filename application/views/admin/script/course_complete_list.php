<form method="post"  action="<?=base_url('admin/scripts/Postexam/update_course_complete_sub');?>" class="mt-3 answersheet" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">  
<table class="table table-striped" id="kt_datatable">
  <thead >
    <tr>
      <th>#</th>
      <th>Student ID</th>
      <th>Enrollment No</th>
      <th>Class Name</th>
      <th>Student Name</th>
      <th>Father / Husband </th>
      <?php  
      $oldreultcounts = $this->Common_model->getRecordByWhere('old_exam_data',array("student_id"=>$students[0]->student_id));       
      foreach($oldreultcounts as $oldreultcount) {
        ?>
        <th>   
         <?php 
         echo   $this->Common_model->getClassNameByClassId($oldreultcount->class_id);
         ?> 
       </th>      
      <?php }       
       ?> 
      <th><input type="checkbox" id="all_student_checked"></th>
    </tr>
  </thead>
   <tbody>
    <?php 
    $i=1;
    foreach($students as $student) {
      $oldreultdatas = $this->Common_model->getRecordByWhere('old_exam_data',array("student_id"=>$student->student_id));
      ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= $student->student_id ?></td>
        <td><?= $student->enrollment_no ?></td>
        <td><?= $student->class_name ?></td>
        <td><?= $student->name ?></td>
        <td><?= $student->f_h_name ?></td>
        <?php        
        foreach($oldreultdatas as $oldreultdata) {
          ?>
          <td>
           <?php  
             echo  $oldreultdata->exam_result;   
           ?>
         </td>
         <?php  }
       ?> 
        <td><input type="checkbox" class="checkbox" name="student_id[]" value="<?=$student->student_id;?>"></td>
      </tr>
    <?php  }  ?>
  </tbody>
</table>
  <div class="row p-3 justify-content-center">
      <button type="submit" class="btn btn-primary" id="submit" name="submit" >Submit</button>
  </div>
</form>
<script>
  $(document).ready(function() {
    // Check All
    $('#all_student_checked').on('change', function() {
      if($('#all_student_checked').is(":checked")){
        $(":checkbox").attr("checked", true);
      }else{
        $(":checkbox").attr("checked", false);
      }
    });
    initDataTable('basic-datatable');
  });
</script>