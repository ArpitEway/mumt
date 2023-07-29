<div class="card">
  <div class="card-body">
    <table class="table table-striped">
        <thead>
            <th>#</th>
            <th>Course</th>
            <th>Class</th>
            <th>Total</th>
            <th>Remaining</th>
           
           
        </thead>
        <tbody>
            <?php
                $i = 1;
                foreach($course_group as $course_detail){
                
                    
                    $account_type = ($this->session->account_type=='Admins') ? '' : $this->session->account_type.'/';
                
                        $flag="";     
                        $this->db->select('count(*) as num');
                        $this->db->from('backlog_exam_form');
                        $this->db->join('backlog_student', 'backlog_exam_form.student_id = backlog_student.student_id  and backlog_exam_form.class_id = backlog_student.class_id and backlog_exam_form.backlog_student_id=backlog_student.id');
                        $this->db->where('backlog_student.exam_form','Y');
                        $this->db->where('backlog_student.exam_year','Dec 2022');
                    
                        $this->db->where('backlog_exam_form.course_group_id',$course_detail['course_group_id']);
                        $this->db->where('backlog_exam_form.class_id',$course_detail['class_id']);
                        $this->db->where('backlog_exam_form.paper_type',"theory");
                        $this->db->where('backlog_exam_form.status',"B");
                        $count = $this->db->get()->result();
                        // print_r($count);die;

                        $this->db->select('count(*) as num');
                    $this->db->from('backlog_exam_form');
                    $this->db->join('backlog_student', 'backlog_exam_form.student_id = backlog_student.student_id and backlog_exam_form.class_id = backlog_student.class_id and backlog_exam_form.backlog_student_id=backlog_student.id');
                    $this->db->where('backlog_student.exam_form','Y');
                    $this->db->where('backlog_student.exam_year','Dec 2022');
                    
                        // $this->db->where('student.university_mode',$courseType);
                    $this->db->where('backlog_exam_form.course_group_id',$course_detail['course_group_id']);
                    $this->db->where('backlog_exam_form.class_id',$course_detail['class_id']);
                    $this->db->where('backlog_exam_form.paper_type',"theory");
                    $this->db->where('backlog_exam_form.status',"B");
                    $this->db->where('backlog_exam_form.theory_marks',"ABS");
                    $abs = $this->db->get()->result();

                    $this->db->select('count(*) as num');
                    $this->db->from('backlog_exam_form');
                    $this->db->join('backlog_student', 'backlog_exam_form.student_id = backlog_student.student_id and backlog_exam_form.class_id = backlog_student.class_id  and backlog_exam_form.backlog_student_id=backlog_student.id');
                    $this->db->where('backlog_student.exam_form','Y');
                    $this->db->where('backlog_student.exam_year','Dec 2022');
                    
                        // $this->db->where('student.university_mode',$courseType);
                    $this->db->where('backlog_exam_form.course_group_id',$course_detail['course_group_id']);
                    $this->db->where('backlog_exam_form.class_id',$course_detail['class_id']);
                    $this->db->where('backlog_exam_form.theory_marks !=', "");
                    $this->db->where('backlog_exam_form.status',"B");
                    $this->db->where('backlog_exam_form.paper_type',"theory");
                    $uploaded = $this->db->get()->result();
                
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td ><?php echo $this->Common_model->getCourseNameByCourseId($course_detail['course_group_id']); ?></td>
                        <td><?php echo $this->Common_model->getClassNameByClassId($course_detail['class_id']); ?></td>
                        <td><?php echo $count[0]->num; ?></td>
                        <td>
                        <a href="<?php echo base_url($account_type."class_wise_backlog_remaining_report/").$course_detail['course_group_id']."/".$course_detail['class_id']; ?>" target="_blank" >
                        <?php echo $count[0]->num-$uploaded[0]->num;//-$abs[0]->num ; ?></td>  
                            </a>    
                    
                    
                    </tr>
                    <?php
                    
                    } 
                
                    ?>
            
        </tbody>
    </table>  
  </div>
</div>
	
