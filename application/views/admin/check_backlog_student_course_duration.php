

<div class="mt-5 p-5" >

<table id="kt_datatable" class="table table-striped dt-responsive  " width="100%" >
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Form</th>
            <th>Enrollment No.</th>
            <th>Student Name</th>
            <th>Course Name</th>
            <th>Class Name</th>
            <th>Session</th>
            <th>Center Code</th>		
        </tr> 
    </thead>
    <tbody>
        <?php
        $sno =1;
        foreach($students as $student){
            ?>
            <tr>
                <td><?= $sno++;?></td>
                <td><?php echo $student->student_id; ?></td>
                <td><?php echo $student->enrollment_no; ?></td>
                <td><?php echo $student->name; ?></td>
                <td><?php //echo $student->course_name; ?></td>
                <td><?php echo $student->class_id; ?></td>
                <td><?php //echo $student->session; ?></td>
                <td><?php echo $student->center_code; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

</div>