    <table  class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Student ID</th>
          <th>Roll No</th>
          <th>Student Name</th>
          <th>Course</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <?php
        $i=1;
        foreach($students as $row){ 
         $paper_details = $this->Common_model->getRecordByWhere('old_result_data',array('student_id' => $row->student_id,'class_id'=>$row->class_id,'type'=>'theory'));
         $paperCount = $this->Common_model->getCountByWhere('old_result_data',array('student_id' => $row->student_id,'class_id'=>$row->class_id,'type'=>'theory'));
         $absCount = $this->Common_model->getCountByWhere('old_result_data',array('student_id' => $row->student_id,'class_id'=>$row->class_id,'type'=>'theory','theory_marks'=>'ABS'));
         ?>
         <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $row->student_id; ?></td>
          <td><?php echo $row->roll_no; ?></td>
          <td><?php echo $row->name; ?></td>
          <td><?php echo $row->course_name ; ?></td>
          <td> <?php  if($paperCount==$absCount){?> 
            <a class="text-danger" href="<?=base_url('admin/scripts/Postexam/set_demo/'.$row->student_id.'/'.$row->class_id)?>">Set Demo</a> <?php } else{?>
             <a class="text-info" href="<?=base_url('admin/scripts/Postexam/backlog_marks_update_scripts/'.$row->student_id.'/'.$row->class_id)?>">Set Backlog</a> <?php } ?>  
           </td>   
         </tr> 
         <?php 
         $c =1;
         foreach ($paper_details as $paper) {  ?>
          <tr>
            <td><?php echo $c++; ?></td>
            <td><?php echo $paper->paper_code; ?></td>
            <td><?php echo $paper->type; ?></td>
            <td><?php if($paper->theory_marks=='ABS'){ ?><span style="color:red">ABS</span> <?php }else{
             echo $paper->theory_marks;  }
           ?></td>
           <td> <?php if($paper->int_marks=='ABS'){ ?><span style="color:red">ABS</span> <?php
         }else{echo $paper->int_marks; } ?>    
       </td>
       <td></td>
     </tr>
     <?php
   }    
 }    
 ?>
</tbody>
</table>