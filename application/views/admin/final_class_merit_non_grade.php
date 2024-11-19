<?php
$merits = [];
$maxValue = PHP_INT_MIN;
foreach($students as $student){
    $isOneClass = $this->Common_model->hasOneClass($student->course_group_id);
    if(!$isOneClass){
        $this->db->where(array('id!='=>$student->class_id));
    }
    $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$student->course_group_id,'mode'=>$classData->mode));
    //  $this->Common_model->last_query();
//    print_r($classes)
   
    $total_obt_marks = 0;
    $total_marks = 0;
    foreach($classes as $cls){
        // echo'ss';
        $count++;
       
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $this->db->where_in('exam_result', array('PASS',"PASS BY GRACE"));
        $old = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$student->student_id,'class_id'=>$cls->id,'course_group_id'=>$student->course_group_id,'university_mode'=>$student->university_mode));
        // $this->Common_model->last_query();
        if(count($old) == 0){
            continue 2;
        }
        $total_obt_marks += $old[0]->obtain_marks;
        $total_marks += $old[0]->total_marks;
        $percent = round(($total_obt_marks/$total_marks)*100,2); 
        if ($percent > $maxValue) {
            $maxValue = $percent;
            $merits = $student; // Update the maximum value
        } 
    }
}


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
            <th>Percent</th>			
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
                    <td><?php echo $maxValue.'%'; ?></td>
               
                </tr>
            
        
    
     </tbody>
</table>

</div>