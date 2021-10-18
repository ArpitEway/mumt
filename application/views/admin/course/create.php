<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/course/create'); ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="session">Session</label>
            <select name="session" id="session" class="form-control" required>
                <option value="">Select session</option>
                <option value="2021" selected >2021</option>
                    <?php 
                    $sessions = $this->db->get_where('session', array())->result_array();
                    foreach($sessions as $session)
                        {
                    ?>
                    <option value="<?php echo $session['id']; ?>"><?php echo $session['session']; ?></option>
                    <?php
                        } 
                    ?>
            </select>
            
        </div>
        <div class="form-group col-md-6">
            <label for="session">Department</label>
            <select name="department_id" id="department_id" class="form-control" required>
                <option value="">Select Department</option>
                    <?php 
                    $departments = $this->db->get_where('department', array())->result_array();
                    foreach($departments as $department)
                        {
                    ?>
                    <option value="<?php echo $department['id']; ?>"><?php echo $department['name']; ?></option>
                    <?php
                        } 
                    ?>
            </select>
            
        </div>
        <div class="form-group col-md-6">
            <label for="session">Category</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">Select Category</option>
				
                <option value="चतुर्वार्षिक स्नातक पाठ्यक्रम">चतुर्वार्षिक स्नातक पाठ्यक्रम</option>
                <option value="त्रिवार्षिक स्नातक पाठ्यक्रम">त्रिवार्षिक स्नातक पाठ्यक्रम</option>
                <option value="चतुःसत्रार्द्ध स्नातकोत्तर पाठ्यक्रम" >चतुःसत्रार्द्ध स्नातकोत्तर पाठ्यक्रम</option>
				<option value="एकवर्षीय स्नातकोत्तर पत्रोपाधि (डिप्लोमा पाठ्यक्रम)" >एकवर्षीय स्नातकोत्तर पत्रोपाधि (डिप्लोमा पाठ्यक्रम)</option>
				<option value="एकवर्षीय स्नातक पत्रोपाधि (डिप्लोमा पाठ्यक्रम)">एकवर्षीय स्नातक पत्रोपाधि (डिप्लोमा पाठ्यक्रम)</option>
				<option value="प्रमाणपत्र (सर्टिफिकेट)">प्रमाणपत्र (सर्टिफिकेट)</option>
                <option>शोध प्रवेश परीक्षा</option>
				
				
            </select>
            
        </div>
        <div class="form-group col-md-6">
            <label for="name">Course Name</label>
            <input type="text" class="form-control" id="course_name" name = "course_name" required placeholder="Enter name of course">
            
        </div>
        <div class="form-group col-md-6">
            <label for="name">Course code</label>
            <input type="text" class="form-control" id="course_code" name = "course_code" required placeholder="Enter course code">
            
        </div>
		<div class="form-group col-md-6">
            <label for="name">Enrollment code</label>
            <input type="text" class="form-control" id="enrollment_code" name = "enrollment_code" required placeholder="Enter enrollment code">
            
        </div>
		<div class="form-group col-md-6">
            <label for="name">Eligibility</label>
            <input type="text" class="form-control" id="eligibility" name="eligibility" required placeholder="Enter eligibility">
            
        </div>
		
		<div class="form-group col-md-6">
            <label for="session">Mode</label>
            <select name="mode" id="Mode" class="form-control" required>
                <option value="annual">Annual</option>
				<option value="semester">Semester</option>
				<option value="month">Month</option>
            </select>
            
        </div>
        <div class="form-group col-md-6">
            <label for="name">Min duration</label>
            <input type="text" class="form-control" id="min_duration" name = "min_duration" required placeholder="Enter min duration">
            
        </div>
        <div class="form-group col-md-6">
            <label for="name">Max duration</label>
            <input type="text" class="form-control" id="max_duration" name = "max_duration"  placeholder="Enter max duration">
            
        </div>
        <div class="form-group col-md-3">
            <label for="name">Admission fees</label>
            <input type="number" class="form-control" id="admission_fees" name = "admission_fees" value="500" required placeholder="Enter admission fees">
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Program Fees Male</label>
            <input type="text" class="form-control" id="program_fees" name = "program_fees_male"  required placeholder="Enter program female fees" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Program Fees Female</label>
            <input type="text" class="form-control" id="program_fees" name = "program_fees_female"  required placeholder="Enter program Male fees" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Exam fees</label>
            <input type="text" class="form-control" id="exam_fees" name = "exam_fees" required placeholder="Enter exam fee">
        </div>
       
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Submit</button>
	</div>
</form>

<script>
    //$(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });
</script>