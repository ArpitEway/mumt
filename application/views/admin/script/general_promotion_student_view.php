<form method="post"  action="" class="mt-3 answersheet" >
  <table  class="table table-bordered">
    <thead>
      <tr class="color">
        <th>Sn No.</th>
        <th>Enrollment No.</th>
        <th> Student Name</th>
        <th>Course</th>
      </tr>
    </thead>
  <tbody>
    <?php
    $i=1;
    foreach($students as $row){ 
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $row->enrollment_no; ?></td>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $row->course_name ; ?></td>   
      </tr>
       <tr>
        <th></th>
        <th>Paper Code</th>
        <th>Current Theory marks (Teacher)</th>
        <th>Final Theory marks</th>
      </tr>
      <?php
      $paper_details = $this->Common_model->getRecordByWhere('new_exam_form',array('student_id' => $row->student_id,'class_id'=>$row->class_id,'paper_type' => 'theory'));
      foreach ($paper_details as $paper) { 
        $min_marks = $this->Common_model->getRecordByWhere('paper_master',array('paper_code' => $paper->paper_code,'class_id' => $paper->class_id)); 
        $min_theory_marks = $min_marks[0]->min_theory_marks;
        $anssheetData = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array('student_id' => $row->student_id,'class_id'=>$row->class_id,'paper_code' => $paper->paper_code,'teacher_id'=>''));
        // 'teacher_id!='=>''
        $obtain_theory_marks = $anssheetData[0]->total_marks;
        $old_num = array();
        if($obtain_theory_marks >=$min_theory_marks){
          $old_num['theory_marks'] = $anssheetData[0]->total_marks;
          $where = array('student_id'=>$paper->student_id, 'paper_code'  =>$paper->paper_code);
          $this->Common_model->updateRecordByConditions('new_exam_form', $where, $old_num);
        }
        ?>
        <tr>
          <td></td>
          <td><?php echo $paper->paper_code; ?></td>
          <td><?php echo $anssheetData[0]->total_marks; ?></td>
          <td><?=$old_num['theory_marks']; ?></td>
        </tr>
        <?php
      }        
    }
    ?>
  </tbody>
</table>
</form>