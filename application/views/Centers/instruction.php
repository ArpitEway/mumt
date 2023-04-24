<div class="card">
  <div class="card-body table-responsive">
    <table class="table">
        <thead>
            <th>#</th>
            <th style="width:30%;">Course Name</th>
            <th style="width:25%;">Eligibility</th>
            <th>Duration</th>
            <th>Admisssion Fees</th>
            <th>Program Fees</th>
            <th>Exam Fees</th>
            <th>Syllabus</th>
            <th>Papers</th>
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
            <td center>
                <?php
                $course_name =  str_replace('/', ' ', $course_detail['course_name']); 
                $url = './assets/regular_syllabus/'.$course_name.'.pdf';

                if(file_exists($url)){
                    ?>
                    <a href="<?php echo site_url($url);?>" download ><img src="<?=base_url('assets/images/')?>pdf.png" width="55"></a>
                <?php } ?>
                </td>
                <?php $paper =  $this->Common_model->getCountByWhere('paper_master',array('course_group_id'=>$course_detail['id']));
                if($paper !=0){
                ?>

                <td>
                 <a target="_blank"  class="" href="<?=base_url('course_wise_paper/'.$course_detail['id'].'/'.'REG'.'/'.$course_detail['mode']);?>"><i class="fa fa-eye" aria-hidden="true"></i></a>
                </td>
                <?php
                }else{
                    ?>
                    <td></td>
                    <?php
                }
                ?>
            </tr>
            <?php
        $i++;
        } ?>
    </tbody>
    </table>
<?php if($this->session->has_userdata('centerdata')){ ?>
    <div class="text-center">
    <a class="btn btn-success" href="dashboard">Dashboard</a>
    </div>
<?php } ?>
  </div>
</div>
	