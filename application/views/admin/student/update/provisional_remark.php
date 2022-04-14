<style>

.chk{
	width: 20px;
	height: 20px;	
}
.chk_label{
	margin-left: 10px;
    margin-top: 4px;
    font-size: 15px;	
}
</style> 
<form method="POST" class="d-block ajaxForm" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<input type="hidden" class="student_id_model" name="student_id" value="<?php echo  $param1 ?>">
<div class="text-justify row justify-content-around" >
<?php

$student = $courses = $this->db->get_where('student', array("student_id" => $param1))->row_array();
$course_group_id = $student['course_group_id'];

$course = $this->db->get_where('course_group', array("id" => $course_group_id))->row_array();

$document_id = $course['document_id'];

$documnets = $this->db->get_where('document_category', "category in (0)" )->result_array();

foreach($documnets as $doc){
?>
	<div class="col-5 ml-2">
	<input type="checkbox" id="remark<?=$doc['id']; ?>" value="<?php echo $doc['id']; ?>" class="form-check-input chk" name="remark[]" >
	<label for="remark<?=$doc['id']; ?>" class="chk_label"><?php echo $doc['document']; ?><?php if($doc['status'] == "Y"){ ?><span style="color:red;" ><b>*</b></span><?php } ?></label>
	</div>
	
<?php } ?>



<div class="col-5 ml-2">
	</div>
</div>



<div class="form-group text-center">
	<button class="btn btn-lg btn-primary" id="remark_submit" type="button">Submit</button>
</div>



</form>

<script>

//$(".ajaxForm").validate({}); 
//$(".ajaxForm").submit(function(e) {
//  e.preventDefault();
// var form = $(this);
//  return false;
//  ajaxSubmit(e, form, showAlldepartment);
//});

</script>
