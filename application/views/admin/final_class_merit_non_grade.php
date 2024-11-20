<?php
$merits = [];

foreach($students as $student){
  
    $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$student->course_group_id,'mode'=>$classData->mode));
 
    $total_obt_marks = 0;
    $total_marks = 0;
    foreach($classes as $cls){
       
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $this->db->where_in('exam_result', array('PASS',"PASS BY GRACE"));
        $old = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$student->student_id,'class_id'=>$cls->id,'course_group_id'=>$student->course_group_id,'university_mode'=>$student->university_mode));
      
        $total_obt_marks += $old[0]->obtain_marks;
        $total_marks += $old[0]->total_marks;
   
    }
    $percent = round(($total_obt_marks/$total_marks)*100,5); 
 
    array_push($merits , array('student_id'=> $student->student_id,'enrollment_no'=>$student->enrollment_no,'name'=>$student->name,'father_name'=>$student->f_h_name,'course_name'=>$student->course_name,'class_name'=>$student->class_name,'center_code'=>$student->center_code,'center_name'=>$student->center_name,'gender'=>$student->gender,'mobile'=>$student->p_mobile_no,'percent'=>$percent));
}
echo 'Student Count :'.count($merits);

?>

<div class=" mt-3" >

    <table id="kt_datatable" class="table table-striped dt-responsive  " width="100%" >
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Form</th>
                <th>Enrollment No.</th>
                <th>Student Name</th>
                <th>Father Name</th>
                <th>Course Name</th>
                <th>Class Name</th>
                <th>Center Code</th>
                <th>Center Name</th>
                <th>Gender</th>
                <th>Mobile No.</th>
                <th>Percent</th>			
            </tr> 
        </thead>
        <tbody>
            <?php
                usort($merits, function($a, $b) {
                    return $b['percent'] <=> $a['percent'];
                });
                $highestValue = $merits[0]['percent'];
                $topValues = array_filter($merits, function ($item) use ($highestValue) {
                        return $item['percent'] === $highestValue;
                    });
                $sn=1;
                foreach($topValues as $merit){
                ?>
                        <tr>
                            <td><?= $sn++?></td>
                            <td><?php echo $merit['student_id']; ?></td>
                            <td><?php echo $merit['enrollment_no']; ?></td>
                            <td><?php echo $merit['name']; ?></td>
                            <td><?php echo $merit['father_name']; ?></td>
                            <td><?php echo $merit['course_name']; ?></td>
                            <td><?php echo $merit['class_name']; ?></td>
                            <td><?php echo $merit['center_code']; ?></td>
                            <td><?php echo $merit['center_name']; ?></td>
                            <td><?php echo $merit['gender']; ?></td>
                            <td><?php echo $merit['mobile']; ?></td>
                            <td><?php echo $merit['percent']; ?></td>
                        
                        </tr> 
                <?php
                }
    
             ?>
            
        </tbody>
    </table>

</div>