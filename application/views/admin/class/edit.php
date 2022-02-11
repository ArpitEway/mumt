<?php
$classes = $this->db->get_where('class_master', array('id' => $param1))->result_array();

foreach($classes as $class): ?>
<?php 

$group_info = $this->db->get_where('group', array('class_id' => $class['id']))->result_array();

?>
<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/classes/update/'.$param1); ?>">

    <div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="course">Course</label>
            <select name="course_group_id" readonly="readonly" id="course_group_id" class="form-control" required>
                <option value="">Select course</option>
                    <?php 
                    $courses = $this->db->get_where('course_group', array())->result_array();
                    foreach($courses as $course)
                    {
                    ?>
                    <option value="<?php echo $course['id']; ?>" <?php if($course['id'] == $class['course_group_id'] ){ echo "selected"; }  ?>  ><?php echo $course['course_name']; ?></option>
					<?php
                    } 
                    ?>
            </select>
            
        </div>
		
		<div class="form-group col-md-6">
            <label for="class">Class</label>
            <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo $class['class_name'];  ?>" placeholder="Enter class name" required >        
        </div>



<div class="form-group col-md-4">
            <label for="Select Group">Select Group</label>
            <select name="select_groups" id="select_groups" class="form-control" required>

                <option value="select group">select group</option>
                <option value="group" <?php if($class['group_name'] == 'group' ){ echo "selected"; }  ?>>group</option>
                <option value="paper" <?php if($class['group_name'] == 'paper' ){ echo "selected"; }  ?>>paper</option>
            
            </select>
        </div> 

		
        <div class="form-group col-md-3">
            <label for="session">Group</label>
            <select name="class_group" id="class_group" class="form-control" required>
                <option value="">Select group</option>
				<option value="Y" <?php if($class['class_group'] == 'Y' ){ echo "selected"; }  ?>>Yes</option>
				<option value="N" <?php if($class['class_group'] == 'N' ){ echo "selected"; }  ?>>No</option>
                
            </select>
        </div> 
		<div class="form-group col-md-3">
            <label for="session">Mode</label>
            <select name="mode" id="mode" class="form-control" required>
                <option value="annual" <?php if($class['mode'] == 'annual' ){ echo "selected"; }  ?>>Annual</option>
				<option value="semester" <?php if($class['mode'] == 'semester' ){ echo "selected"; }  ?>>Semester</option>
				<option value="month" <?php if($class['mode'] == 'month' ){ echo "selected"; }  ?>>Month</option>
            </select>
            
        </div>
        <div class="form-group col-md-3">
            <label for="session">Select min group</label>
            <select name="select_group" id="select_group" class="form-control">
                <option value="">Select group</option>
                <option value="1" <?php if($class['select_group'] == '1'){ echo "selected"; }  ?> >1</option>
				<option value="2" <?php if($class['select_group'] == '2'){ echo "selected"; }  ?> >2</option>
				<option value="3" <?php if($class['select_group'] == '3'){ echo "selected"; }  ?> >3</option>
				<option value="4" <?php if($class['select_group'] == '4'){ echo "selected"; }  ?> >4</option>
				<option value="5" <?php if($class['select_group'] == '5'){ echo "selected"; }  ?> >5</option>
            </select>
        </div>
		<div class="form-group col-md-3">
            <label for="class">Total paper</label>
            <input type="text" class="form-control" value="<?php echo $class['total_paper']; ?>" id="total_paper" name="total_paper" placeholder="Enter total paper"  >        
        </div>
		<br>
		<div class="form-group col-md-12" id="addedRow" style="text-align: end;"></div>
		<?php
		foreach($group_info as $group){
		?>
        <div class="form-group col-md-12" style="text-align: right;">
        <input type="text" class="form-control" id="group" name="group_name[]" value="<?php echo $group['group_name'] ?>" placeholder="Enter group name" required ><br><button class="plus_btn" onclick="addMoreRows(this.form);" >Add more</button>
		<input type="hidden" name="group_id[]" value="<?php echo $group['id']; ?>">
		</div>
		<br>
		<?php } ?>
		<div class="form-group col-md-12" id="addedRows" style="text-align: end;"></div>
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
	</div>
</form>
<?php endforeach; ?>

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
