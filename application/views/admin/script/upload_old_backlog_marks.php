
<div class="container mt-3" >

<table id="kt_datatable" class="table table-striped dt-responsive nowrap " width="100%" >
    <thead>
        <tr>
            <th>#</th>
            <th>Course</th>
            <th>Class</th>
            <th>Mode</th>
            <th>Student Count</th>			
            <th>upload</th>			
        </tr> 
    </thead>
    <tbody>
    <?php
    $i = 1;
    $class_ids=array(101,104,107,110,116,119,125,128,131,134);
    foreach($courses as $course){
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $this->Common_model->getCourseNameByCourseId($course->course_group_id); ?></td>
                    <td><?php echo $this->Common_model->getClassNameByClassId($course->class_id); ?></td>
                    <td><?php echo $course->mode; ?></td>
                    <td><?php echo $course->cnt; ?></td>
                    <td>
                    <?php if(in_array($course->class_id, $class_ids) && $course->mode == 'REG'){
                            ?>
                            <!-- <a target="_blank" href="<?php //echo base_url('admin/scripts/Postexam/upload_old_grade_data_script/'.$course->class_id.'/'.$course->university_mode)?>">upload</a> -->
                            <?php
                    }else{
                        ?>
                        <a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_backlog_data_script/'.$course->class_id.'/'.$course->mode)?>">upload</a>
                        <?php

                    }
                  ?> 
                  </td>
                </tr>
     <?php 
     $i++;
     } 
     
     ?>
     </tbody>
</table>

</div>
