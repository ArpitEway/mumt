<table id="kt_datatable_scroll" class="table table-striped nowrap">
    <thead>
        <tr>
            <th>#</th>
            <th>Course Name</th>
            <th>Class Name</th>
            <th>Class ID</th>
            <th>Student Count</th>
           
            
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        foreach($class_list as $class){
           
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php
                
                $classData = $this->Common_model->getRecordByID('class_master','id',$class->class_id);
	
               echo $this->Common_model->getCourseNameByCourseId($classData->course_group_id); ?></td>
                <td><?php echo $classData->class_name; ?></td>
                <td><?php echo $class->class_id; ?></td>
               
                <td><a target="_blank" href="<?php echo base_url(); ?>admin/scripts/Otherscript/update_AGPA_CGPA/<?=$class->class_id?>/<?=$classData->cbcs?>"><?=$class->total ?>    </a>   </td>
            </tr>
        <?php  }	?>
    </tbody>
</table>
