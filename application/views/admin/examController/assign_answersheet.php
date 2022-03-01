<?php
// $classes = $this->db->get_where('class_master', array('id' => $param1))->result_array();

// foreach($classes as $class): ?>
 <?php 

// $group_info = $this->db->get_where('group', array('class_id' => $class['id']))->result_array();

?>
<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/classes/update/'.$param1); ?>">

    <div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="course">Course</label>
            <select name="course_group_id" readonly="readonly" id="course_group_id" class="form-control" required>
                <option value="">Select course</option>
                    <?php 
                 
                    foreach($courses as $course)
                    {
                       
                    ?>
                    <option value="<?php echo $course['course_group_id']; ?>"   ><?php echo $course['course_name']; ?></option>
					<?php
                    } 
                    ?>

            </select><br>
            <select name="class_id" readonly="readonly" id="class_id" class="form-control" required>
                <option value="">Select Class</option>
                    <?php 
                 
                    foreach($courses as $course)
                    {
                        $class = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=>$course['course_group_id']));
                     
                    ?>
                    <option value="<?php echo $course['course_group_id']; ?>"   ><?php echo $class[0]->class_name; ?></option>
					<?php
                    } 
                    ?>

            </select>
        </div>
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
	</div>
</form>

