<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/course/create'); ?>">
    <div class="form-row">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="session">Session</label>
            <select name="session" id="session" class="form-control" required>
                <option value="">Select session</option>
                <?php 
                   $sessions = $this->Common_model->get_record('session','*');
                    foreach($sessions as $session)
                    {
                    ?>
                        <option value="<?php echo $session['session']; ?>"><?php echo $session['session']; ?></option>
                    <?php
                    } 
                ?>
            </select>
            
        </div>

        <div class="form-group col-md-6">
            <label for="name">Course Name</label>
            <input type="text" class="form-control" id="course_name" name = "course_name" required placeholder="Enter name of course">
        </div>

        <div class="form-group col-md-4">
            <label for="course_code">Course Code</label>
            <input type="text" class="form-control" id="course_code" name = "course_code" required placeholder="Enter course code">
        </div>

        <div class="form-group col-md-4">
            <label for="paper_code_pattern">Paper Code Pattern</label>
            <input type="text" class="form-control" id="paper_code_pattern" name = "paper_code_pattern"  placeholder="Enter Paper code pattern">
        </div>
		
		<div class="form-group col-md-4">
            <label for="name">Eligibility</label>
            <input type="text" class="form-control" id="eligibility" name="eligibility" required placeholder="Enter eligibility">
        </div>
		
		<div class="form-group col-md-3">
            <label for="session">Mode</label>
            <select name="mode" id="Mode" class="form-control" required>
                <option value="annual">Annual</option>
				<option value="semester">Semester</option>
				<option value="month">Month</option>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="session">University Mode</label>
            <select name="university_mode" id="university_mode" class="form-control" >
                <option value="all">All </option>
				<option value="regular" selected >Regular</option>
				<option value="private">Private</option>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="name">Min Duration</label>
            <input type="text" class="form-control" id="min_duration" name = "min_duration" required placeholder="Enter min duration">
        </div>

        <div class="form-group col-md-3">
            <label for="name">Max Duration</label>
            <input type="text" class="form-control" id="max_duration" name = "max_duration"  placeholder="Enter max duration">
        </div>

        <div class="form-group col-md-12" style="margin-bottom: 5px;">
            <label for="name"><strong>Regular</strong></label>
        </div>

		<div class="form-group col-md-3">
            <label for="name">Form Fees</label>
            <input type="text" class="form-control" id="form_fees" name = "form_fees" value="200"  placeholder="Enter form fees" >
        </div>
        <div class="form-group col-md-3">
            <label for="name">Admission Fees</label>
            <input type="number" class="form-control" id="admission_fees" name = "admission_fees" value="1300"  placeholder="Enter admission fees">
        </div>
        
		<div class="form-group col-md-3">
            <label for="name">Program Fees</label>
            <input type="text" class="form-control" id="program_fees" name = "program_fees"   placeholder="Enter program fees" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Exam Fees</label>
            <input type="text" class="form-control" id="exam_fees" name = "exam_fees"  placeholder="Enter exam fee">
        </div>

        <div class="form-group col-md-12" style="margin-bottom: 5px;">
            <label for="name"><strong>Private</strong></label>
        </div>
        <div class="form-group col-md-3">
            <label for="name">Form Fees</label>
            <input type="text" class="form-control" id="p_form_fees" value="200" name = "p_form_fees" placeholder="Enter form fees" >
           
        </div>
        <div class="form-group col-md-3">
            <label for="name">Admission Fees</label>
            <input type="number" class="form-control" id="p_admission_fees" value="1300" name = "p_admission_fees"  placeholder="Enter admission fees">
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Program Fees</label>
            <input type="text" class="form-control" id="p_program_fees" value="" name = "p_program_fees" placeholder="Enter program fees" >
           
        </div>
		<div class="form-group col-md-3">
            <label for="name">Exam Fees</label>
            <input type="text" class="form-control" id="p_exam_fees" value="" name = "p_exam_fees" placeholder="Enter exam fee">
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