
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
    foreach($courses as $course){
    ?>
        
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $course->course_name; ?></td>
                    <td><?php echo $course->class_name; ?></td>
                    <td><?php echo $course->cnt; ?></td>
                    <td><a target="_blank" href="<?=base_url('admin/scripts/Postexam/marks_checking_script/'.$course->class_id)?>">Check</a></td>
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