<?php

$courses = $this->db->get_where('course', array('id' => $param1))->result_array();

foreach($courses as $course): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/course/update/'.$param1); ?>">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="session">Session</label>
            <select name="session" id="session" class="form-control" required>
                <option value="">Select Session</option>
                    <?php 
                    $sessions = $this->Common_model->get_record('session','*');
                    foreach($sessions as $session)
                    {
                    ?>
                    
                    <option value="<?php echo $session['session']; ?>" <?php if($course['session'] == $session['session']) echo "selected" ?> ><?php echo $session['session']; ?></option>
                    
                    <?php
                    } 
                    ?>
            </select>
            
        </div>
		
        <div class="form-group col-md-6">
            <label for="name">Course Name</label>
            <input type="text" class="form-control" id="course_name" value="<?php echo $course['course_name']; ?>" name = "course_name" required placeholder="Enter name of course">
            
        </div>
		
        <div class="form-group col-md-4">
            <label for="name">Course Code</label>
            <input type="text" class="form-control" id="course_code" value="<?php echo $course['course_code']; ?>" name = "course_code" required placeholder="Enter course code">
            
        </div>

       
		
		<?php 
		$course_groups = $this->db->get_where('course_group', array('id' => $course['course_group_id']))->result_array();
		foreach($course_groups as $course_group){
		?>

         <div class="form-group col-md-4">
            <label for="paper_code_pattern">Paper Code Pattern</label>
            <input type="text" class="form-control" id="paper_code_pattern" value="<?php echo $course_group['paper_code_pattern']; ?>" name = "paper_code_pattern"  placeholder="Enter Paper code pattern">
        </div>

		<div class="form-group col-md-4">
            <label for="name">Eligibility</label> 
            <input type="text" class="form-control" value="<?php echo $course_group['eligibility']; ?>" id="eligibility" name="eligibility" required placeholder="Enter eligibility">
            <input type="hidden" name="group_id" value="<?php echo $course_group['id']; ?>">
        </div>

		<div class="form-group col-md-3">
            <label for="session">Mode</label>
            <select name="mode" id="Mode" class="form-control" required>
                <option value="annual" <?php if($course_group['mode'] == "annual") echo "selected" ?>  >Annual</option>
				<option value="semester" <?php if($course_group['mode'] == "semester") echo "selected" ?>>Semester</option>
				<option value="month" <?php if($course_group['mode'] == "month") echo "selected" ?>>Month</option>
            </select>
            
        </div>

        <div class="form-group col-md-3">
            <label for="session">University Mode</label>
            <select name="university_mode" id="university_mode" class="form-control" >
            <option value="all" <?php if($course_group['university_mode'] == "all") echo "selected" ?> >All</option>
			<option value="regular" <?php if($course_group['university_mode'] == "regular") echo "selected" ?> >Regular</option>
			<option value="private" <?php if($course_group['university_mode'] == "private") echo "selected" ?>>Private</option>
            
        </select>
            
        </div>

		<?php } ?>

        <div class="form-group col-md-3">
            <label for="name">Min Duration</label>
            <input type="text" class="form-control" id="min_duration" value="<?php echo $course['min_duration']; ?>" name = "min_duration" required placeholder="Enter min duration">
            
        </div>
        <div class="form-group col-md-3">
            <label for="name">Max Duration</label>
            <input type="text" class="form-control" id="max_duration" value="<?php echo $course['max_duration']; ?>" name = "max_duration" required  placeholder="Enter max duration">
            
        </div>

        <div class="form-group col-md-12" style="margin-bottom: 5px;">
            <label for="name">Regular</label>
        </div>

        <div class="form-group col-md-3">
            <label for="name">Admission Fees</label>
            <input type="number" class="form-control" value="<?php echo $course['admission_fees']; ?>" id="admission_fees" name = "admission_fees" value="500"  placeholder="Enter admission fees">
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Program Fees</label>
            <input type="text" class="form-control" value="<?php echo $course['program_fees']; ?>" id="program_fees" name = "program_fees"   placeholder="Enter program fees" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Form Fees</label>
            <input type="text" class="form-control" value="<?php echo $course['form_fees']; ?>" id="form_fees" name = "form_fees"   placeholder="Enter form fees" >
        </div>
		<div class="form-group col-md-3">
            <label for="name">Exam Fees</label>
            <input type="text" class="form-control" value="<?php echo $course['exam_fees']; ?>"  id="exam_fees" name = "exam_fees"  placeholder="Enter exam fee">
        </div>

        <div class="form-group col-md-12" style="margin-bottom: 5px;">
            <label for="name">Private</label>
        </div>

        <div class="form-group col-md-3">
            <label for="name">Admission Fees</label>
            <input type="number" class="form-control" value="<?php echo $course['p_admission_fees']; ?>" id="p_admission_fees" name = "p_admission_fees" value="500" placeholder="Enter admission fees">
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Program Fees</label>
            <input type="text" class="form-control" value="<?php echo $course['p_program_fees']; ?>" id="p_program_fees" name = "p_program_fees" placeholder="Enter program fees" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Form Fees</label>
            <input type="text" class="form-control" value="<?php echo $course['p_form_fees']; ?>" id="p_form_fees" name = "p_form_fees" placeholder="Enter form fees" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Exam Fees</label>
            <input type="text" class="form-control" value="<?php echo $course['p_exam_fees']; ?>" id="p_exam_fees" name = "p_exam_fees" placeholder="Enter exam fee">
        </div>
        
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
	</div>
</form>


<?php endforeach; ?>

<script>
    $(".ajaxForm").validate({}); 
    $(".ajaxForm").submit(function(e) {
      e.preventDefault();
      var form = $(this);
      ajaxSubmit(e, form, showAllcourse);
    });
</script>
