
<div class="container mt-3" >

<table id="kt_datatable" class="table table-striped dt-responsive nowrap " width="100%" >
    <thead>
        <tr>
            <th>#</th>

            <th>Course</th>
            <th>Class</th>
            <th>Action</th>			
        </tr> 
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach($classes as $course){
    ?>
        
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $course->course_name; ?></td>
                    <td><?php echo $course->class_name; ?></td>
                    
                    <td><a target="_blank" href="<?=base_url('admin/scripts/Otherscript/view_final_class_merit_list/REG/'.$course->id)?>">View Merit List REG</a> /
                    <a target="_blank" href="<?=base_url('admin/scripts/Otherscript/view_final_class_merit_list/PVT/'.$course->id)?>">PVT</a>
                </td>
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