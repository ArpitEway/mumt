
<div class="container mt-3" >

<table id="kt_datatable" class="table table-striped dt-responsive nowrap " width="100%" >
    <thead>
        <tr>
            <th>#</th>

            <th>Course</th>
            <th>Class</th>
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
                    <td><?php echo $course->course_name; ?></td>
                    <td><?php echo $course->class_name; ?></td>
                    <td><?php echo $course->cnt; ?></td>
                    <?php if(in_array($course->class_id, $class_ids)){
                            ?>
                            <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_grade_data_script/'.$course->class_id)?>">upload</a></td>
                            <?php
                    }else{
                        ?>
                         <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/upload_old_data_script/'.$course->class_id)?>">upload</a></td>
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