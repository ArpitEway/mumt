<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/classes/create'); ?>">
    <div class="form-row">
	
        <div class="form-group col-md-6">
            <label for="course">Course</label>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <select name="course_group_id" id="course_group_id" class="form-control" required>
                <option value="">Select course</option>
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
		
		<div class="form-group col-md-6">
            <label for="class">Class</label>
            <input type="text" class="form-control" id="class_name" name="class_name" placeholder="Enter class name" required >        
        </div>
		
        <div class="form-group col-md-3">
            <label for="session">Group</label>
            <select name="class_group" id="class_group" class="form-control" required>
                <option value="">Select group</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
                
            </select>
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
            <label for="session">Select min group</label>
            <select name="select_group" id="select_group" class="form-control">
                <option value="">Select group</option>
                <option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
            </select>
        </div>
		<div class="form-group col-md-3">
            <label for="class">Total paper</label>
            <input type="text" class="form-control" id="total_paper" name="total_paper" placeholder="Enter total paper">        
        </div>
		<br>
		<div class="form-group col-md-12" id="addedRow" style="text-align: end;"></div>
		<br>
		<div class="form-group col-md-12" id="addedRows" style="text-align: end;"></div>
		
       
        
		
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Submit</button>
	</div>
</form>
<style>
.plus_btn{
    color: #FFFFFF;
    background-color: #052C68;
    border-color: #052C68;
}
.minus_btn{
    color: #FFFFFF;
    background-color: #052C68;
    border-color: #052C68;
}
</style>
<script>
    //$(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });

$('#class_group').on('change', function() {
  if(this.value == "Y"){
	
	  addRow(this.form);
  }
});


function addRow(frm){ 
	
	var field = '<div class="form-group col-sd-8"><input type="text" class="form-control" id="group" name="group_name[]" placeholder="Enter group name" required ><br><button class="plus_btn" onclick="addMoreRows(this.form);" >Add more</button></div>';
	$('#addedRow').append(field);
}

function addMoreRows(frm){
	var rowCount = $("#rowcount").val();
	var field = '<div class="group_'+rowCount+' "><input type="text" class="groups form-control " placeholder="Enter group name" id="group" name="group_name[]" required=""><br><button class="minus_btn" onclick="RemoveRow('+rowCount+');">Remove </button></div><input type="hidden" id="rowcount" value="1">';
	rowCount++;
	$("#rowcount").val(rowCount);
	$('#addedRows').append(field);
}

function RemoveRow(id){
	$('.group_'+id).remove();
}
	
</script>

