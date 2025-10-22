<div id="content" align="center">

<table align="center" cellpadding="10"  border="1" >
 	<thead>
        <tr >
        <td>&nbsp;</td>
        <td><?php echo $exam_session; ?></td>  <td>&nbsp;</td><td>&nbsp;</td>
        <td colspan="3" >Total Number of Students Appeared in Final Year</td>
        <td colspan="3" >Total Number of Students Passed/Awarded Degree</td>
        <td colspan="3" >Out of Total, Number of Students Passed with 6.5 CGPA or Above</td>
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
            <td>6.5 CGPA or Above</td>

        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
       
            foreach($courses as $course){
                $awarded= 0;
                $awarded_male= 0;
                $awarded_female= 0;
                $this->db->select("COUNT(CASE WHEN gender != '' THEN od.student_id END) as std_total,COUNT(CASE WHEN gender = 'Male' THEN od.student_id END) AS male,
            COUNT(CASE WHEN gender = 'Female' THEN od.student_id END) AS female");
            $this->db->from('old_exam_data as od');
            $this->db->join('student as st','od.student_id = st.student_id and od.class_id = st.class_id');
          
            // if(in_array($course->id, $class_ids)){
            //     $this->db->where_not_in('od.center_id',$dept_ids);
            // }
              $this->db->where_in('st.session',array('July 2021','Jan 2022'));
             $this->db->where_in('od.exam_year',array('June 2025','July 2025'));
		    $this->db->where('st.enrollment_no!=','-');
            $this->db->where(array('st.enrolled'=>'Y', 'od.class_id'=>$course->id,'od.university_mode'=>$mode, 'od.marks_pattern'=>$pattern));//,'od.exam_status'=>'R' ,'od.exam_year'=>$exam_session
            $total = $this->db->get()->result();

            $this->db->select("COUNT(CASE WHEN gender != '' THEN od.student_id END) as std_total,COUNT(CASE WHEN gender = 'Male' THEN od.student_id END) AS male,
            COUNT(CASE WHEN gender = 'Female' THEN od.student_id END) AS female");
            $this->db->from('old_exam_data as od');
            $this->db->join('student as st','od.student_id = st.student_id and od.class_id = st.class_id');
            // if(in_array($course->id, $class_ids)){
            //     $this->db->where_not_in('od.center_id',$dept_ids);
            // }
            // $this->db->where(array('st.enrolled'=>'Y', 'od.class_id'=>$course->id,'od.exam_year'=>$exam_session,'od.university_mode'=>$mode,'st.course_complete'=>'Y', 'od.marks_pattern'=>$pattern));
            ///'od.exam_status'=>'R'
            $this->db->where(array('st.enrolled'=>'Y', 'od.class_id'=>$course->id,'od.university_mode'=>$mode,'st.course_complete'=>'Y', 'od.marks_pattern'=>$pattern));
            $this->db->where_in('exam_result', array('PASS', 'PASS BY GRACE'));
            $this->db->where_in('st.session',array('July 2021','Jan 2022'));
           $this->db->where_in('od.exam_year',array('June 2025','July 2025'));
		    $this->db->where('st.enrollment_no!=','-');
            $total_passed = $this->db->get()->result();
            
            foreach($students as $student){
                if(in_array($course->id, $class_ids)){
                  $divided = 3;
                }else{
                  $divided = 4;
                }
                $this->db->select('sum(agpa_sgpa) as cgpa')->from('old_exam_data as od')
                ->join('student as st', 'od.student_id = st.student_id', 'inner')
                ->where(array(
                    'st.enrolled' => 'Y',
                    'od.student_id' => $student->student_id,
                    'st.course_complete' => 'Y',
                    'od.course_group_id' => $course->course_group_id,
                    'od.university_mode' => $mode,
                   //  'od.exam_status' => 'R',
                    'od.marks_pattern' => $pattern
                ))
                ->where_in('od.exam_result', array('PASS', 'PASS BY GRACE'))
                 ->where_in('st.session',array('July 2021','Jan 2022'))
		        ->where('st.enrollment_no!=','-');
                $query = $this->db->get();
                $total_first = $query->row();
                // $this->Common_model->last_query();
                $percentage = ($total_first->cgpa / $divided);
                if($percentage >= 6.5){
                    $awarded++;
                }
                if($percentage >= 6.5 && $student->gender == 'Male'){
                    $awarded_male++;
                }
                if($percentage >= 6.5 && $student->gender == 'Female'){
                    $awarded_female++;
                }
               
            }
              
              

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
                   
                    <td><?= $awarded;?></td>
                    <td><?= $awarded_female;?></td>
                    <td><?= $awarded_male;?></td>
                    <td>
			            <a href="<?php echo base_url().'ExamController/student_exam_appeared_details_grade/'.$course->course_group_id.'/'.$course->id.'/'.$mode; ?>" target="_blank"><strong>All</strong></a>
		            </td>
                    <td>
                    <a href="<?php echo base_url().'ExamController/pass_student_details_grade/'.$course->course_group_id.'/'.$course->id.'/'.$mode; ?>" target="_blank"><strong>PASS</strong></a>
		            </td>
                    <td>
			            <a href="<?php echo base_url().'ExamController/student_above_sixty_five_cgpa_details_grade/'.$course->course_group_id.'/'.$course->id.'/'.$mode; ?>" target="_blank"><strong>VIEW</strong></a>
		            </td>
		
                </tr>
                
                <?php
            }
        ?>

    </tbody>
</table>
</div>