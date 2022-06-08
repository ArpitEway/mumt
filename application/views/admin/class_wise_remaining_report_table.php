
    
<hr>


    <table class="table table-striped" id="kt_datatable">
        <tbody>
            <tr>
                <th>#</th>
                <th>Roll No</th>
                <th>ICCode </th>
                <th>Enrollment No </th>
                <th>Student Name </th>
                <th>Course Name</th>
                <th>Class</th>
                <th>Session</th>
                <th>Paper Code</th>
               
            </tr>
            <?php $i=0;foreach($students as $student) {
                
                $i++;
                ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $student->roll_no ?></td>
                <td><?= $student->center_code ?></td>
                <td><?= $student->enrollment_no ?></td>
                <td><?= $student->name ?></td>
                <td><?= $student->course_name ?></td>
                <td><?= $student->class_name ?></td>
                <td><?= $student->session ?></td>
                <td><?= $student->paper_code ?></td>
              
            </tr>
            <?php  }  ?>
        </tbody>
    </table>


