<?php

$courses = $this->db->get_where('course', array('id' => $param1))->result_array();

foreach($courses as $course): ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/course/update/'.$param1); ?>">


    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="session">Session</label>
            <select name="session" id="session" class="form-control" required>
                <option value="">Select session</option>
                <option value="2021" selected>2021</option>
                    <?php 
                    $sessions = $this->db->get_where('session', array())->result_array();
                    foreach($sessions as $session)
                    {
                    ?>
                    
                    <option value="<?php echo $session['id']; ?>" <?php if($course['session'] == $session['id']) echo "selected" ?> ><?php echo $session['name']; ?></option>
                    
                    <?php
                    } 
                    ?>
            </select>
            
        </div>
		
		<?php 
		$course_groups = $this->db->get_where('course_group', array('id' => $course['course_group_id']))->result_array();
		foreach($course_groups as $course_group){
		
		?>
		
		<div class="form-group col-md-6">
            <label for="session">Department</label>
            <select name="department_id" id="department_id" class="form-control" required>
                <option value="">Select Department</option>
                    <?php 
                    $departments = $this->db->get_where('department', array())->result_array();
                    foreach($departments as $department)
                        {
                    ?>
                    <option value="<?php echo $department['id']; ?>" <?php if($course_group['department_id'] == $department['id']) echo "selected" ?> ><?php echo $department['name']; ?></option>
                    <?php
                        } 
                    ?>
            </select>
            
        </div>
   	
		<div class="form-group col-md-6">
            <label for="session">Category</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">Select Category</option>
				
                <option value="चतुर्वार्षिक स्नातक पाठ्यक्रम" <?php if($course_group['category'] == "चतुर्वार्षिक स्नातक पाठ्यक्रम" ) echo "selected" ?> >चतुर्वार्षिक स्नातक पाठ्यक्रम</option>
                <option value="त्रिवार्षिक स्नातक पाठ्यक्रम" <?php if($course_group['category'] == "त्रिवार्षिक स्नातक पाठ्यक्रम" ) echo "selected" ?> >त्रिवार्षिक स्नातक पाठ्यक्रम</option>
                <option value="चतुःसत्रार्द्ध स्नातकोत्तर पाठ्यक्रम" <?php if($course_group['category'] == "चतुःसत्रार्द्ध स्नातकोत्तर पाठ्यक्रम" ) echo "selected" ?> >चतुःसत्रार्द्ध स्नातकोत्तर पाठ्यक्रम</option>
				<option value="एकवर्षीय स्नातकोत्तर पत्रोपाधि (डिप्लोमा पाठ्यक्रम)" <?php if($course_group['category'] == "एकवर्षीय स्नातकोत्तर पत्रोपाधि (डिप्लोमा पाठ्यक्रम)" ) echo "selected" ?> >एकवर्षीय स्नातकोत्तर पत्रोपाधि (डिप्लोमा पाठ्यक्रम)</option>
				<option value="एकवर्षीय स्नातक पत्रोपाधि (डिप्लोमा पाठ्यक्रम)" <?php if($course_group['category'] == "एकवर्षीय स्नातक पत्रोपाधि (डिप्लोमा पाठ्यक्रम)" ) echo "selected" ?> >एकवर्षीय स्नातक पत्रोपाधि (डिप्लोमा पाठ्यक्रम)</option>
				<option value="प्रमाणपत्र (सर्टिफिकेट)" <?php if($course_group['category'] == "प्रमाणपत्र (सर्टिफिकेट)" ) echo "selected" ?> >प्रमाणपत्र (सर्टिफिकेट)</option>
				
            </select>
            
        </div>
        
		
		<?php } ?>
		
        
        <div class="form-group col-md-6">
            <label for="name">Course Name</label>
            <input type="text" class="form-control" id="course_name" value="<?php echo $course['course_name']; ?>" name = "course_name" required placeholder="Enter name of course">
            
        </div>
		
        <div class="form-group col-md-6">
            <label for="name">Course code</label>
            <input type="text" class="form-control" id="course_code" value="<?php echo $course['course_code']; ?>" name = "course_code" required placeholder="Enter course code">
            
        </div>
		<div class="form-group col-md-6">
            <label for="name">Enrollment code</label>
            <input type="text" class="form-control" id="enrollment_code" value="<?php echo $course['enrollment_code']; ?>" name = "enrollment_code" required placeholder="Enter enrollment code">
            
        </div>
		<?php 
		$course_groups = $this->db->get_where('course_group', array('id' => $course['course_group_id']))->result_array();
		foreach($course_groups as $course_group){
		?>
		<div class="form-group col-md-6">
            <label for="name">Eligibility</label> 
            <input type="text" class="form-control" value="<?php echo $course_group['eligibility']; ?>" id="eligibility" name="eligibility" required placeholder="Enter eligibility">
            <input type="hidden" name="group_id" value="<?php echo $course_group['id']; ?>">
        </div>
		<div class="form-group col-md-6">
            <label for="session">Mode</label>
            <select name="mode" id="Mode" class="form-control" required>
                <option value="annual" <?php if($course_group['mode'] == "annual") echo "selected" ?>  >Annual</option>
				<option value="semester" <?php if($course_group['mode'] == "semester") echo "selected" ?>>Semester</option>
				<option value="month" <?php if($course_group['mode'] == "month") echo "selected" ?>>Month</option>
            </select>
            
        </div>
		<?php } ?>
		
        <div class="form-group col-md-6">
            <label for="name">Min duration</label>
            <input type="text" class="form-control" id="min_duration" value="<?php echo $course['min_duration']; ?>" name = "min_duration" required placeholder="Enter min duration">
            
        </div>
        <div class="form-group col-md-6">
            <label for="name">Max duration</label>
            <input type="text" class="form-control" id="max_duration" value="<?php echo $course['max_duration']; ?>" name = "max_duration" required  placeholder="Enter max duration">
            
        </div>
        <div class="form-group col-md-3">
            <label for="name">Admission fees</label>
            <input type="text" class="form-control" id="admission_fees" value="<?php echo $course['admission_fees']; ?>" name = "admission_fees" required placeholder="Enter admission fee">
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Program Fees Male</label>
            <input type="text" class="form-control" id="program_fees" name = "program_fees_male"  required placeholder="Enter program female fees" value="<?php echo $course['program_fees_male']; ?>">
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Program Fees Female</label>
            <input type="text" class="form-control" id="program_fees" name = "program_fees_female"  required placeholder="Enter program Male fees" value="<?php echo $course['program_fees_female']; ?>">
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Exam fees</label>
            <input type="text" class="form-control" id="exam_fees" value="<?php echo $course['exam_fees']; ?>" name = "exam_fees" placeholder="Enter exam fee">
           
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
