<?php $teachers = $this->db->get_where('teacher', array('id' => $param1))->result_array(); ?>

<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/ExamController/teachers/update/'.$param1); ?>">
    <div class="form-row">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<div class="form-group col-md-6">
            <label for="class">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $teachers[0]['name'];  ?>" placeholder="Enter name" required >
        </div>
        <div class="form-group col-md-6">
            <label for="class">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="<?php echo $teachers[0]['email'];  ?>" placeholder="Enter email name" required >
        </div>
        <div class="form-group col-md-6">
            <label for="class">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $teachers[0]['address'];  ?>" placeholder="Enter address name" required >        
        </div>
        <div class="form-group col-md-6">
            <label for="class">Mobile No</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $teachers[0]['phone'];  ?>" placeholder="Enter phone name" required >
        </div>
        <div class="form-group col-md-6">
            <label for="class">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $teachers[0]['subject'];  ?>" placeholder="Enter subject name" required >        
        </div>
        <div class="form-group col-md-6">
            <label for="class">College Name</label>
            <input type="text" class="form-control" id="clg_name" name="clg_name" value="<?php echo $teachers[0]['clg_name'];  ?>" placeholder="Enter College name" required >        
        </div>
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Update</button>
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
