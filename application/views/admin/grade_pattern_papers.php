<table id="kt_datatable_scroll" class="table table-striped nowrap">
    <thead>
        <tr>
            <th>#</th>
            <th>Course Name</th>
            <th>Class Name</th>
            <th>Paper Code</th>
            <th>Paper Name</th>
            <th>Paper Type</th>
            <th>CE</th>
            <th>Credit Point</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        foreach($papers as $paper){
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $paper->course_name; ?></td>
                <td><?php echo $paper->class_name; ?></td>
                <td><?php echo $paper->paper_code; ?></td>
                <td><?php echo $paper->paper_name; ?></td>
                <td><?php echo $paper->type; ?></td>
                <td><?php echo $paper->ce; ?></td>
                <td></td>
            </tr>
        <?php  }	?>
    </tbody>
</table>
