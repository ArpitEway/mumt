<style type="text/css">
  .color{
    background-color: #ffaa80;
  }
</style>


<div class=" table-responsive ">
  <form method="post"  action="<?=base_url('admin/scripts/Postexam/general_promtion_student_submit');?>" class="mt-3 answersheet" >
  <table  class="table table-striped">
       <thead>
          <tr class="color">
            
            <th>Enrollment No.</th>
            <th> Student Name</th>
            <th> F/H Name</th>
            <th>Course</th>
            
          </tr>

       <tr>
      
            <th>Paper Code</th>
            <th></th>
            <th> Current Theory marks (Teacher)</th>
            <th> Final Theory marks</th>
            
          </tr>

        </thead>
    <tbody>
  <?php foreach($students as $row){  ?>
        <tr >
          <td ><?php echo $row->enrollment_no; ?></td>
          <td><?php echo $row->name; ?></td>
          <td ><?php echo $row->f_h_name; ?></td>
           <td><?php echo $row->course_name ; ?></td>
        </tr>
         <?php
       
    $paper_details = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array('student_id' => $row->student_id));
       $i=1;
       
       foreach ($paper_details as $paper) { ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $paper->paper_code; ?></td>
           <td><?php echo $paper->total_marks; ?></td>
          <td><?php echo $paper->total_marks; ?></td>
          <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
          <input type="hidden"  name="papar_id" value="<?=$paper->id;?>">
          <input type="hidden"  name="student_id" value="<?=$papar->student_id;?>">
        </tr>
        <?php
       }        
    }
  ?>
    </tbody>
  </table>
</form>
</div>