
<div class="container mt-3" >

<table id="kt_datatable" class="table table-striped dt-responsive nowrap " width="100%" >
    <thead>
        <tr>
            <th>#</th>
            <th>Course</th>
            <th>Class</th>
            <th>Mode</th>
            <th>Student Count</th>			
            <th>upload Grade</th>
            <th>upload Marks</th>			
        </tr> 
    </thead>
    <tbody>
    <?php
    $i = 1;
    $class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
    $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
    foreach($courses as $course){
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $this->Common_model->getCourseNameByCourseId($course->course_group_id); ?></td>
                    <td><?php echo $this->Common_model->getClassNameByClassId($course->class_id); ?></td>
                    <td><?php echo $course->mode; ?></td>
                    <td><?php echo $course->cnt; ?></td>
                    
                    <?php if(in_array($course->class_id, $class_ids) && $course->mode == 'REG'){
                            ?>
                            <td><a target="_blank" href="<?php echo base_url('admin/scripts/Postexam/upload_old_backlog_grade_data_script/'.$course->class_id.'/'.$course->mode)?>">upload</a></td>
                            <?php
                    
                        }else if((in_array($course->class_id, $class_cbcs)) && $course->mode=='REG'){
                            ?>
                            <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_backlog_grade_data_script_pg/'.$course->class_id.'/'.$course->mode)?>">upload</a></td>
                            <?php
                        }else{
                                ?>
                                <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_backlog_data_script/'.$course->class_id.'/'.$course->mode)?>">upload</a></td>
                                <?php

                            }
                  ?> 
                  <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_backlog_data_script/'.$course->class_id.'/'.$course->_mode)?>">upload</a></td>
                </tr>
     <?php 
     $i++;
     } 
     
     ?>
     </tbody>
</table>

</div>
