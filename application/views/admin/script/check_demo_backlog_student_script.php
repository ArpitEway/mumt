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
            <a class="text-danger" href="<?=base_url('admin/scripts/Postexam/set_demo/'.$row->student_id.'/'.$row->class_id)?>">Set Demo</a> <?php 
            //  $where = array('student_id'=>$row->student_id,'new_exam_form'=>'D');
            //  $data = array('demo'=>'Y','new_exam_form'=>'N');
            //  $update =$this->Common_model->updateRecordByConditions('student',$where,$data);
            } else{?>
             <a class="text-info" href="<?=base_url('admin/scripts/Postexam/backlog_marks_update_scripts/'.$row->student_id.'/'.$row->class_id)?>">Set Backlog</a> <?php 
        //      $students = $this->Common_model->getRecordByWhere("old_exam_data",array("class_id"=>$row->class_id,'student_id'=>$row->student_id));
        // $whereResult = array("class_id"=>$students[0]->class_id ,"student_id"=>$students[0]->student_id, 'exam_data_id' => $students[0]->id);
        // $old_result_datas = $this->Common_model->getRecordByWhere("old_result_data",$whereResult );
        //     $data = array(
        //         'student_id' => $students[0]->student_id,
        //         'course_group_id' =>$students[0]->course_group_id,
        //         'class_id' => $students[0]->class_id,
        //         'roll_no' => 0,
        //         'session' => $students[0]->session,
        //         'mode'=>$students[0]->university_mode,
        //         'exam_year'=>'Aug 2022',
        //         'exam_form' => 'D',
        //         'enrollment_no' => $students[0]->enrollment_no,
        //         'center_id' => $students[0]->center_id,
        //         'center_code' => $students[0]->center_code,
        //         'attempt_no' => 1,
        //         'exam_center_id' => 0,
        //         'exam_center_code'=>'',
        //         'back_marksheet_no' => '',
        //         'upload_result' =>  'N',
        //         'result_permission' => 'N',
        //        );
        //       $duplicate =  $this->Common_model->getRecordByWhere('backlog_student',array('student_id'=>$students[0]->student_id,'class_id'=>$students[0]->class_id,'exam_year'=>'Aug 2022'));
        //       if( $duplicate){
        //         redirect(base_url('admin/scripts/Postexam/check_demo_backlog_student_script/'.$row->class_id));
        //         $this->session->set_flashdata('ajax_flash_message','Already Submited'); 
        //       }else{

              
        //     $backlog_student_id = $this->Common_model->insertAll('backlog_student',$data);
        //     echo $this->db->last_query().'<br>';
        //     foreach($old_result_datas as $old_result_data)
        //     {
        //         $examData = array(
        //             'student_id' => $old_result_data->student_id ,
        //             'backlog_student_id' => $backlog_student_id,
        //             'course_group_id' =>$old_result_data->course_group_id,
        //             'class_id' => $old_result_data->class_id,
        //             'paper_code' => $old_result_data->paper_code,
        //             'paper_type' => $old_result_data->type,
        //             'group_id' => '',
        //             'paper_order' => $old_result_data->p_order,
        //             'theory_marks' =>$old_result_data->theory_marks,
        //             'int_marks' =>$old_result_data->int_marks,
        //             'p_marks' => $old_result_data->p_marks,
        //             'status' => 'C',
        //          );
        //         if ($old_result_data->result=='FAIL'){
        //             $examData['status'] = 'B';
        //             $examData['theory_marks'] = '';
        //         }
        //         $backlog_exam_form_june = $this->Common_model->insertAll('backlog_exam_form',$examData);
        //         // echo $this->db->last_query().'<br>';
        //     }
        //  } 
            } ?>  
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