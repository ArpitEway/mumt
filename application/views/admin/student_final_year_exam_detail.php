<div id="content" align="center">

<table align="center" cellpadding="10"  border="1" >
 	<thead>
        <tr >
        <td>&nbsp;</td>
        <td><?php echo $exam_session; ?></td>  <td>&nbsp;</td><td>&nbsp;</td>
        <td colspan="3" >Total Number of Students Appeared in Final Year</td>
        <td colspan="3" >Total Number of Students Passed/Awarded Degree</td>
        <td colspan="3" >Out of Total, Number of Students Passed with 60% or Above</td>
        <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
        </tr>
        <tr >
            <td>#</td>
            <td>Course Name</td>
            <td>Class Name</td>
            <td>Category</td>
            <td>Total</td>
            <td>Girls</td>
            <td>Boys</td>

            <td>Total</td>
            <td>Girls</td>
            <td>Boys</td>
            <td>Total</td>
            <td>Girls</td>
            <td>Boys</td>
            <td>Appeared</td>
            <td>Passed</td>
            <td>60% or Above</td>

        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
            foreach($courses as $course){
                $this->db->select("COUNT(CASE WHEN gender != '' THEN od.student_id END) as std_total,COUNT(CASE WHEN gender = 'Male' THEN od.student_id END) AS male,
            COUNT(CASE WHEN gender = 'Female' THEN od.student_id END) AS female");
            $this->db->from('old_exam_data as od');
            $this->db->join('student as st','od.student_id = st.student_id and od.class_id = st.class_id');
            $this->db->where(array('st.enrolled'=>'Y', 'od.class_id'=>$course->id,'od.exam_year'=>$exam_session,'od.university_mode'=>'REG','od.exam_status'=>'R', 'od.marks_pattern'=>'MARKS'));
            $total = $this->db->get()->result();
            // $this->Common_model->last_query();
            $this->db->select("COUNT(CASE WHEN gender != '' THEN od.student_id END) as std_total,COUNT(CASE WHEN gender = 'Male' THEN od.student_id END) AS male,
            COUNT(CASE WHEN gender = 'Female' THEN od.student_id END) AS female");
            $this->db->from('old_exam_data as od');
            $this->db->join('student as st','od.student_id = st.student_id and od.class_id = st.class_id');
            $this->db->where(array('st.enrolled'=>'Y', 'od.class_id'=>$course->id,'od.exam_year'=>$exam_session,'od.university_mode'=>'REG','od.exam_status'=>'R', 'od.marks_pattern'=>'MARKS'));
            $this->db->where_in('exam_result', array('PASS', 'PASS BY GRACE'));
            $total_passed = $this->db->get()->result();
            
                //  $this->db->select(" COUNT(CASE  (SUM(obtain_marks) / SUM(total_marks)) * 100 AS percentage  WHEN percentage > 60 THEN od.student_id END) as  std_total");
               
                // $this->db->from('old_exam_data as od');
                // $this->db->join('student as st','od.student_id = st.student_id and od.class_id = st.class_id');
                // $this->db->where(array('st.enrolled'=>'Y', 'od.course_group_id'=>$course->course_group_id,'od.exam_year'=>$exam_session,'od.university_mode'=>'REG','od.exam_status'=>'R', 'od.marks_pattern'=>'MARKS'));
                // $this->db->where_in('exam_result', array('PASS', 'PASS BY GRACE'));
                // $total_first = $this->db->get()->result();
                //ss
//                 $this->db->select("COUNT(od.student_id) AS std_total", false)
//          ->from('old_exam_data as od')
//          ->join('student as st', 'od.student_id = st.student_id AND od.class_id = st.class_id', 'inner')
//          ->where(array(
//              'st.enrolled' => 'Y',
//              'od.course_group_id' => $course->course_group_id,
//              'od.exam_year' => $exam_session,
//              'od.university_mode' => 'REG',
//              'od.exam_status' => 'R',
//              'od.marks_pattern' => 'MARKS'
//          ))
//          ->where_in('od.exam_result', array('PASS', 'PASS BY GRACE'))
//          ->group_start()
//          ->having('(SUM(od.obtain_marks) / SUM(od.total_marks)) * 100 >', 60)
//          ->group_end();

// $query = $this->db->get();
// $total_first = $query->row()->std_total;
// //ss
// echo "Total students above 60%: " . $total_first;

                // $this->Common_model->last_query();
                 // $this->db->query("SELECT COUNT(*) AS total_above_60 FROM (SELECT student_id,(SUM(obtain_marks) / (total_marks)) * 100 AS percentage FROM old_exam_data GROUP BY student_id HAVING percentage > 60) AS percentage_data");
//         $this->db->select('COUNT(*) AS total_above_60', false)
//          ->from("(SELECT 
//                      student_id, 
//                      class_id, 
//                      (SUM(obtain_marks) / SUM(total_marks)) * 100 AS percentage 
//                   FROM old_exam_data 
//                   WHERE course_group_id = {$course->course_group_id}
//                     AND exam_year = '{$exam_session}'
//                     AND university_mode = 'REG'
//                     AND exam_status = 'R'
//                     AND marks_pattern = 'MARKS'
//                     AND exam_result IN ('PASS', 'PASS BY GRACE')
//                   GROUP BY student_id, class_id
//                   HAVING percentage > 60) AS percentage_data", false);

// $this->db->join('student as st', 'percentage_data.student_id = st.student_id AND percentage_data.class_id = st.class_id', 'inner');
// $this->db->where(array('st.enrolled' => 'Y'));

// $query = $this->db->get();
// $total_first = $query->row()->total_above_60;

// echo "Total students above 60%: " . $total_first;

// echo "Total students above 60%: " . $total_first;

                // $this->Common_model->last_query();
                ?>
                <tr>
                    <td><?= $i++?></td>
                    <td><?= $course->course_name?></td>
                    <td><?= $course->class_name?></td>
                    <td>Total</td>
                    <td><?= $total[0]->std_total?></td>
                    <td><?=$total[0]->female?></td>
                    <td><?=$total[0]->male?></td>
                    <td><?= $total_passed[0]->std_total?></td>
                    <td><?=$total_passed[0]->female?></td>
                    <td><?=$total_passed[0]->male?></td>
                   
                    <td>Total </td>
                    <td>Girls</td>
                    <td>Boys</td>
                    <td>Appeared</td>
                    <td>Passed</td>
                    <td>60% or Above</td>
                </tr>
                
                <?php
                // echo $course->course_name.'<br>';
            }
        ?>

    </tbody>
</table>
</div>