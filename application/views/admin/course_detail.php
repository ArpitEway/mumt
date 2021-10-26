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
            <th>ID</th>
            <th>Order</th>
            <th>Total Papers</th>
            <th>Group Available</th>
            <th>Total Groups</th>
            <th>Select Group</th>
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

                <td class="course_css"><?php echo $course_detail['id']; ?></td>
                <td></td>
                <td class="course_css" colspan="4"><?php echo $course_detail['mode']; ?></td>

            </tr>

                <?php 

                $class_master = $this->db->get_where('class_master', array('course_group_id' => $course_detail['id']))->result_array();
                
                $i = 1; 

                foreach($class_master as $class){
                ?>

                <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $class['class_name']; ?></td>
                <td><?php echo $class['id']; ?></td>
                <td><?php echo $class['class_order']; ?></td>
                <td><?php echo $class['total_paper']; ?></td>
                <td ><?php echo $class['class_group']; ?></td>
                <td ><?php echo $class['total_group'];; ?></td>
                <td ><?php echo $class['select_group'];; ?></td>

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
	