<div class="card">
  <div class="card-body">
    <table class="table">
        <thead>
            <th>#</th>
            <th style="width:30%;">Course Name</th>
            <th style="width:25%;">Eligibility</th>
            <th>Duration</th>
            <th>Admisssion Fees</th>
            <th>Program Fees</th>
            <th>Exam Fees</th>
        </thead>
        <tbody>
        <?php

            $i = 1;
            foreach($course_group as $course_detail){

            $this->db->limit(1);
			$this->db->order_by('id','desc');
			$course = $this->db->get_where('course', array('course_group_id'=>$course_detail['id']))->row();
           
            ?>

            <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $course_detail['course_name']; ?></td>
            <td><?php echo $course_detail['eligibility_detail']; ?></td>
            <td><?php echo $course_detail['duration']; ?></td>
            <td><?php echo $course->admission_fees + $course->form_fees; ?></td>

            <td><?php 
            echo $course->program_fees;
            if($course_detail['mode'] == "Semester"){
                echo "/- Sem";
            }
            else if($course_detail['mode'] == "Annual"){
                echo "/- Year";
            }


            ?></td>

            <td><?php echo $course->exam_fees; ?></td>

            </tr>

            <?php
        $i++;
        } ?>
    </tbody>
    </table>  
    <div class="text-center">
    <a class="btn btn-success" href="dashboard">Dashboard</a>
    </div>
  </div>
</div>
	