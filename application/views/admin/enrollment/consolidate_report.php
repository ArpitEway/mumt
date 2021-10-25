<div class="container">

<div class="row mt-5"> 

<div class="form-group col-md-3">
	<label for="session_id">Session</label>
    <select name="session_id" id="session_id" class="form-control" >
	<option value="all">All</option>
	<?php 
        
        foreach($sessions as $session)
		{
        ?>
		<option value="<?php echo $session['session']; ?>" ><?php echo $session['session']; ?></option>
		<?php
		} 
		?>		
		
	</select>
</div>

<div class="form-group col-md-3">
    <label for="course">Course</label>
    <select name="course_group_id" id="course_group_id" class="form-control course_group_id" data-target="#class_id" required >
    <option value="all">All</option>
        <?php 
        $courses = $this->db->get_where('course', array())->result_array();
        foreach($courses as $course)
		{
        ?>
		
		<option value="<?php echo $course['course_group_id']; ?>"><?php echo $course['course_name']; ?></option>
        
		<?php
        } 
        ?> 
       </select>       
</div>

<div class="form-group col-md-3">
    <label for="class_id">Class</label>
    <select name="class_id" id="class_id" class="form-control"  required >
	<option value="">All</option>
    </select>       
</div>

<div class="form-group col-md-3">
    <label for="center_id">Center</label>
    <select name="center_id" id="center_id" class="form-control "  required >
    <option value="all">All</option>
    <?php 
        $centers = $this->db->get_where('center', array())->result_array();
        foreach($centers as $center)
		{
        ?>
		
		<option value="<?php echo $center['center_id']; ?>"><?php echo $center['center_name']; ?></option>
        
	<?php
        } 
    ?> 
    </select>       
</div>

<div class="form-group col-md-3">
	<label for="class">Payment</label>
    <select name="payment" id="payment" class="form-control"  > 
		<option value="all">All</option>
		<option value="Y">Paid</option>
		<option value="N">Unpaid</option>
		 
	</select>
</div>

<div class="form-group col-md-3">
	<label for="class">Admission document</label>
    <select name="document_upload" id="document_upload" class="form-control"  > 
		<option value="all">All</option>
		<option value="Y">Uploaded</option>
		<option value="N">Not uploaded </option>
		
	</select>
</div>

<div class="form-group col-md-3">
	<label for="class">Approved Status</label>
    <select name="approved" id="approved" class="form-control"  > 
		<option value="all">All</option>
		<option value=""> Non-verified </option>
		<option value="Y">Approved </option>
		<option value="N">Non-Approved </option>
	</select>
</div>

<div class="form-group col-md-3">
	<label for="class">Enrollment Status</label>
    <select name="enrolled" id="enrolled" class="form-control" > 
		<option value="all">All</option>
		<option value="Y">Enrolled </option> 
		<option value="N">Non Enrolled</option>
	</select>
</div>

<div class="form-group col-md-3">
	<label for="class">Mode</label>
    <select name="mode" id="mode" class="form-control" > 
		<option value="all">All</option>
		<option value="annual">Annual </option> 
		<option value="semester">Semester</option>
	</select>
</div>

<div class="col-md-3 radio-inline" style="top: 7px;">
<label class="radio radio-success">
<input type="radio" name="filter" value="list" checked />
<span></span>
List
</label>

<label class="radio radio-success">
<input type="radio" name="filter" value="count" />
       <span></span>
            Count
       </label>          
</div>
</div>

<div class="form-group text-center">
	<button class="btn btn-md btn-primary mt-4" type="button" id="submit_btn">Submit</button>
</div>

<div id="dt">
</div>

</div>


<script>

$(document).on("click","#submit_btn",function(){
	
	var data = {
		course_group_id : $("#course_group_id").val(),
		class_id : $("#class_id").val(),
		document_upload : $("#document_upload").val(),
		center : $("#center_id").val(),
		mode : $("#mode").val(),
		approved : $("#approved").val(),
		payment : $("#payment").val(),
		filter : $('input[name="filter"]:checked').val(),
		enrolled : $("#enrolled").val(),
		session : $("#session_id").val(),
		program_fees : $("#program_fees").val()
	};

	console.log(data);

	var url = BASE_URL+"admin/enrollment/get_student_consolidate_data"; 
	var response = call_ajax(data,url);
	
	console.log(response);
	
	$('#dt').html(response.data);
	KTDatatablesBasicBasic.init();
		 
	
});



var showAllpaper = function () 
    {
        var url = '<?php echo site_url('admin/Admins/paper'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                initDataTable('basic-datatable');
            }
        });
    }
</script>