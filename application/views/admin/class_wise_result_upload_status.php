<style>
.course_css{
color:red;
}
</style>
<div class="card">
  <div class="card-body">
    <table class="table">
        <thead>
            <th>#</th>
            <th style="width:40%;">Name</th>
            <th>Check Status</th>
           
        </thead>
        <tbody>
        <?php
            foreach($course_group as $course_detail){
                $this->db->limit(1);
                $this->db->order_by('id','desc');
                $course = $this->db->get_where('course', array('course_group_id' => $course_detail['id']))->row();
                ?>
                <tr>
                <td class="course_css"></td>
                <td class="course_css">
                <?php echo $course_detail['course_name']; ?>
                </td>
                <td class="course_css"><a href="<?php echo base_url("class_wise_result_upload_status_report")."/".$course_detail['id']; ?>" target="_blank" >Check Status</a></td>
               
               
            </tr>
                <?php 
                $this->db->order_by('mode','desc');
                $this->db->order_by('class_name','ASC');
                $class_master = $this->db->get_where('class_master', array('course_group_id' => $course_detail['id']))->result_array();
                
                $i = 1; 

                foreach($class_master as $class){
                ?>

                <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $class['class_name']; ?></td>
                <td><a href="<?php echo base_url("class_wise_result_upload_status_report")."/".$course_detail['id']."/".$class['id']; ?>" target="_blank" >Check Status</a></td>
               

                </tr>

                <?php
                    $i++;
                } 
            }
                ?>
    </tbody>
    </table>  
    <div class="text-center">
    <a class="btn btn-success" href="dashboard">Dashboard</a>
    </div>
  </div>
</div>
	