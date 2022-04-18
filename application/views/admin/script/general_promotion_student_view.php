<style type="text/css">
  .color{
    background-color: #ffcc80;
  }

  
</style>


  <fieldset  style="border:1px solid #999"; >
  <legend>List Of Student<strong> June 2021</strong> Exam:</legend>
  <form method="post"  action="" class="mt-3 answersheet" >
    <table  class="table table-bordered">
     <thead>
      <tr class="color">
       <th>Sn No.</th>
       <th>Enrollment No.</th>
       <th> Student Name</th>
       <th> F/H Name</th>
       <th>Course</th>

     </tr> </thead>

     <tr> <thead>
      <th></th>
      <th>Paper Code</th>

      <th> Current Theory marks (Teacher)</th>
      <th> Final Theory marks</th>

    </tr>

  </thead>
  <tbody>

    <?php
    $i=1;
    foreach($students as $row){ 
     ?>
     <tr>
      <td><?php echo $i++; ?></td>
      <td ><?php echo $row->enrollment_no; ?></td>
      <td><?php echo $row->name; ?></td>
      <td ><?php echo $row->f_h_name; ?></td>
      <td><?php echo $row->course_name ; ?></td>   
    </tr>
    <?php

    $paper_details = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array('student_id' => $row->student_id));

    foreach ($paper_details as $paper) { 


 $paper_count = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id' => $paper->student_id));

 if (count($paper_count->theory_marks)==0) {
      $old_num = array('theory_marks' => $paper->total_marks,);
    }
      $where = array(
        'student_id'=>$paper->student_id ,
        'paper_code'  =>$paper->paper_code,);
      $update =$this->Common_model->updateRecordByConditions('new_exam_form',$where, $old_num);
 //$this->Common_model->last_query();

      ?>
      <!-- <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">  -->
      <tr>
        <td></td>
        <td><?php echo $paper->paper_code; ?></td>
        <td><?php echo $paper->total_marks; ?></td>
        <td><?php echo $paper->total_marks; ?></td>

      </tr>
      <?php
    }        
  }
  ?>
</tbody>
</table>
</fieldset>
</form>





