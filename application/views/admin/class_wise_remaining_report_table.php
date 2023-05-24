
    
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
                <!-- <?php if($this->session->account_type == "ExamController"){ ?>
                <th>Action</th>
                <?php } ?> -->
               
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
                <!-- <?php if($this->session->account_type == "ExamController"){ ?>
                <th>Action</th>
                <?php } ?> -->
               
               
            </tr>
        </tfoot>
        <tbody>
            <?php $i=0;
            $roll = $this->Common_model->getMaster('roll_number_col');
            foreach($students as $student) {
                
                $i++;
                ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $student->center_code ?></td>
                <td><?= $student->examcentercode ?></td>
                <td><?= $student->$roll ?></td>
                <td><?= $student->enrollment_no ?></td>
                <td><?= $student->name ?></td>
                <td><?= $student->course_name ?></td>
                <td><?= $this->Common_model->getClassNameByClassId($student->class_id) ?></td>
                <!-- <td><?= $student->session ?></td> -->
                <td><?= $student->paper_code ?></td>
                <!-- <?php if($this->session->account_type == "ExamController"){ ?>
                <td><a target="_blank" href="<?php echo  base_url()."ExamController/search_student_result/$student->roll_number" ?>">Edit </a></td>
                <?php } ?> -->
              
            </tr>
            <?php  }  ?>
          
        </tbody>
    </table>
    <script>
// $(document).ready(function(){
//     $("#kt_datatable").Datatable();
// });
<script>

