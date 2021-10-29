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
<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/enrollment/student_doc_update/'.$param1); ?>">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="form-check text-justify" >
<?php

$student = $courses = $this->db->get_where('student', array("student_id" => $param1))->row_array();
$course_group_id = $student['course_group_id'];

$course = $this->db->get_where('course_group', array("id" => $course_group_id))->row_array();

$document_id = $course['document_id'];

$documnets = $this->db->get_where('document_category', "category in (".$document_id." , 0)" )->result_array();

foreach($documnets as $doc){
?>
	<div>
	<input type="checkbox" id="remark<?=$doc['id']; ?>" value="<?php echo $doc['id']; ?>" class="form-check-input chk" name="remark[]" >
	<label for="remark<?=$doc['id']; ?>" class="chk_label"><?php echo $doc['document']; ?><?php if($doc['status'] == "Y"){ ?><span style="color:red;" ><b>*</b></span><?php } ?></label>
	</div>
	
<?php } ?>
</div>

<div class="form-group mt-3 col-sm-6">
    <label class="col-form-label">Remark detail</label>
    <div>
     <textarea class="form-control remark_detail" placeholder="Remark detail" id="kt_autosize_2" rows="4" name="remark_detail"  ></textarea>
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

$("#remark_submit").on('click',function (e){
	
	
	var ck_box = $('input[type="checkbox"]:checked').length;
	
	if(ck_box > 0){
	

		var frm = $('.ajaxForm').serialize();
		var rem = <?php echo $param1 ?>;
        
	$.ajax({
	url: '<?php echo site_url('admin/enrollment/student_doc_update/'.$param1); ?>',
	type: 'POST',
	dataType : 'json',
	data: frm,
	success: function (data) {
	if(data){
		console.log(data);
			$('#right-modal').modal('toggle');
			toastr.success("Non approved");
				
			console.log(data.remark);
				
			$('.remark_span_'+rem).html(data.remark);
				
			}else{
				toastr.error("Something wrong");
			}
		},
	});	
	}else{
	toastr.error("Please check atleast one checkbox");
	}
});	

</script>
