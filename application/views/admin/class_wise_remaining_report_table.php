
    
<hr>


    <table class="table table-striped dt-responsive nowrap" id="kt_datatable"  width="100%">
        
        <thead>
            <tr>
                <th>#</th>
                <th>ICCode </th>
                <th>Exam Center </th>
                <th>Roll No</th>
                <th>Enrollment No </th>
                <th>Student Name </th>
                <th>Course Name</th>
                <th>Class</th>
                <!-- <th>Session</th> -->
                <th>Paper Code</th>
               
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>#</th>
                <th>ICCode </th>
                <th>Exam Center </th>
                <th>Roll No</th>
                <th>Enrollment No </th>
                <th>Student Name </th>
                <th>Course Name</th>
                <th>Class</th>
                <!-- <th>Session</th> -->
                <th>Paper Code</th>
               
            </tr>
        </tfoot>
        <tbody>
            <?php $i=0;foreach($students as $student) {
                
                $i++;
                ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $student->center_code ?></td>
                <td><?= $student->examcentercode ?></td>
                <td><?= $student->roll_no ?></td>
                <td><?= $student->enrollment_no ?></td>
                <td><?= $student->name ?></td>
                <td><?= $student->course_name ?></td>
                <td><?= $student->class_name ?></td>
                <!-- <td><?= $student->session ?></td> -->
                <td><?= $student->paper_code ?></td>
              
            </tr>
            <?php  }  ?>
          
        </tbody>
    </table>
    <script>
// $(document).ready(function(){
//     $("#kt_datatable").Datatable();
// });
<script>

