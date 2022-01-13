<div class="">
<div class="row mt-5">
<div class="form-group col-md-3">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<label for="class">Session</label>
    <select name="session" id="session" class="form-control" >
    	<option>All</option>
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
    <label for="center_id">Center</label>
    <select name="center_id" id="center_id" class="form-control " data-target="#course_group_id" required >
    <option value="all">All</option>
    <?php 
        $centers = $this->db->get_where('center', array())->result_array();
        foreach($centers as $center)
		{
        ?>
		
		<option value="<?php echo $center['center_id']; ?>"><?php echo $center['center_code'] ." - ". $center['center_name']; ?></option>
        
	<?php
        } 
    ?> 
    </select>       
</div>




<div class="form-group col-md-3">
            <label for="course">Course</label>
            <select name="course_group_id" id="course_group_id" class="form-control course_group_id"  required >
           
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
	<label for="class">Approved</label>
    <select name="approved" id="approved" class="form-control"  > 
		<option value="all">All</option>
		<option value=""> Non-verified </option>
		<option value="Y">Approved </option>
		<option value="N">Non-Approved </option>
	</select>
</div>

<div class="form-group col-md-3">
	<label for="class"></label>
	<button type="button" class="btn btn-primary mt-4" style="margin-top: 24px !important;" id="submit_btn">Submit</button>
</div>
</div>
<div id="dt">
</div>

</div>


<script>

$(document).on("click","#submit_btn",function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var data = {
		session : $("#session").val(),
		center : $("#center_id").val(),
		course_group_id : $("#course_group_id").val(),
		approved : $("#approved").val(),
		[csrfName]:csrfHash
	};
	var url = BASE_URL+"admin/enrollment/get_student_data"; 
	var response = call_ajax(data,url);
	console.log(response);
	
	$('#dt').html(response.data);
	KTDatatablesBasicBasic.init();
		 
});

</script>