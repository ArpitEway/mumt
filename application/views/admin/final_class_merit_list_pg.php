<?php
$merits = [];
$arr = [];
$maxValue = PHP_INT_MIN;
foreach($students as $student){
    $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$student->course_group_id,'mode'=>'Semester','id !='=>$student->class_id));
    $count = 0;
    $total_grade_point = 0;
    $total_course_credit = 0;
    ?>
    <span style="display:none;"><?php
      $gradesheetData = $this->Gradesheet_tr_model_pg->view_result($student->student_id,$student->course_group_id,$student->class_id,$student->university_mode);
    ?></span>
  
    <?php
   
     if($gradesheetData['result'] == '' || $gradesheetData['result'] == 'Fail' || $gradesheetData['result'] == 'RW' || $gradesheetData['result'] == 'RWAS' || $gradesheetData['result'] == 'RWPR'){
       continue;
    }            
  
     foreach($classes as $cls){
        $count++;
       
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $old = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$student->student_id,'class_id'=>$cls->id,'course_group_id'=>$student->course_group_id,'university_mode'=>$student->university_mode));

         $gradeData   = $this->GradeSheet_old_model_pg->view_old_results($student->student_id,$student->course_group_id,$cls->id,$student->university_mode,$old[0]->id);
       
            $total_grade_point += number_format((float)$gradeData['agpa'], 2, '.', '') * $gradeData['obt_credit']; 
            $total_course_credit +=$gradeData['tot_credit'];
     }
    
     $total_grade_point += number_format((float)$gradesheetData['agpa'], 2, '.', '') * $gradesheetData['obt_credit']; 
     $total_course_credit +=$gradesheetData['tot_credit'];
    //  echo $total_grade_point.' -- '.$total_course_credit.'<br>';
     $cgpa = number_format((float)($total_grade_point/$total_course_credit), 2, '.', '');
    
     if ($cgpa > $maxValue) {
        $maxValue = $cgpa;
        $merits = $student; // Update the maximum value
    }
   
    //  echo $student->name.' - '.$student->enrollment_no.' - '.$cgpa.' - '.$gradesheetData['result'].'<br>';
    //  $arr[] = array('student'=> $student,'cgpa'=>$cgpa);
    //  $arr[$student->class_id] = $cgpa;
    //  $arr['enrollment'] = $student->enrollment_no;
    //  $arr['cgpa'] = $cgpa;
    //  $arr['class']= $student->class_id;
    //    array_push($merits , array('student'=> $student,'cgpa'=>$cgpa));
}

// echo '<pre>';
// echo "The maximum value is: " . $maxValue;
// print_r($merits)
// foreach($merits as $key=>$merit){
//     echo '<pre>';
//     echo 'Merit'.$key;
//     echo  max($merit['cgpa']);
// }
// $maxScore = max(array_column($merits, 'cgpa'));
// echo '<pre>';
// echo 'Merit'.$maxScore;
//  print_r($merits);
// echo 'students ug' .count($students_ug).'<br>';
// echo 'students other' .count($students_others).'<br>';
?>

<div class="container mt-3" >

<table id="kt_datatable" class="table table-striped dt-responsive nowrap " width="100%" >
    <thead>
        <tr>
            
            <th>Form</th>
            <th>Enrollment No.</th>
            <th>Student Name</th>
            <th>Father Name</th>
            <th>Course</th>
            <th>Class</th>
            <th>Cgpa</th>			
        </tr> 
    </thead>
    <tbody>
    <?php
   
   
    ?>
        
                <tr>
                    <td><?php echo $merits->student_id; ?></td>
                    <td><?php echo $merits->enrollment_no; ?></td>
                    <td><?php echo $merits->name; ?></td>
                    <td><?php echo $merits->f_h_name; ?></td>
                    <td><?php echo $merits->course_name; ?></td>
                    <td><?php echo $merits->class_name; ?></td>
                    <td><?php echo $maxValue; ?></td>
               
                </tr>
            
        
    
     </tbody>
</table>

</div>
