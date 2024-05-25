
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
    $class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
    $class_cbcs = array(193,197,201,203,205,211,213,221,223,225,227,275,279,194,198,202,204,206,209,212,214,215,222,224,226,228,231,235,237,239,245,247,249,251,253,303,302,217,276,277,280,281,304,305);
    foreach($courses as $course){
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $course->course_name; ?></td>
                    <td><?php echo $course->class_name; ?></td>
                    <td><?php echo $course->university_mode; ?></td>
                    <td><?php echo $course->cnt; ?></td>
                    <?php if(in_array($course->class_id, $class_ids) && $course->university_mode == 'REG'){
                            ?>
                            <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_grade_data_script/'.$course->class_id.'/'.$course->university_mode)?>">upload</a></td>
                            <?php
                    }else if((in_array($course->class_id, $class_cbcs)) && $course->university_mode=='REG'){
                        ?>
                        <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_grade_data_script_pg/'.$course->class_id.'/'.$course->university_mode)?>">upload</a></td>
                        <?php
                    }
                    else{
                        ?>
                         <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_data_script/'.$course->class_id.'/'.$course->university_mode)?>">upload</a></td>
                        <?php

                    }
                  ?> 
                </tr>
            
        
     <?php 
     $i++;
     } 
     
     ?>
     </tbody>
</table>

</div>
<script>


</script>