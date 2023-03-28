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
<form method="POST" class="d-block ajaxForm" enctype="multipart/form-data" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<input type="hidden" class="student_id_model" name="student_id" value="<?php echo  $param1 ?>">
<div class="text-justify row justify-content-around" >
<?php

$student = $courses = $this->db->get_where('student', array("student_id" => $param1))->row_array();
$course_group_id = $student['course_group_id'];




?>
<div class="row">
Form Number <b><?=$student['student_id']?></b>  Name <b><?=$student['name']?></b><br>
</div>
<div class="row">
<div class="col-5 ">
		<div class="col-xl-3 text-center">
			<div class="image-input image-input-outline" id="kt_image_1">
				<?php if($student_detail->photo){ ?>

					<div class="image-input-wrapper" style="background-image: url('<?=base_url('assets/student_image/'.$student_detail->session.'/'.$student_detail->photo); ?>')"></div>
				
					<?php }else{ ?>

					<div class="image-input-wrapper" style="background-image: url(<?=base_url('assets/images/center/student.bmp')?>)"></div>
				
					<?php } ?>	
				<label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Image">
					<i class="fa fa-pen icon-sm text-muted"></i>
					<input type="file" name="photo" id="photo" accept=".png, .jpg, .jpeg"/>
				</label>
				<span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Remove Image">
					<i class="ki ki-bold-close icon-xs text-muted"></i>
				</span>
				<div id="errPhoto" class="fv-plugins-message-container"></div>
			</div>
		</div>
	





</div>
</div>



<div class="form-group text-center">
	<button class="btn btn-lg btn-primary" id="remark_submit" type="button">Submit</button>
</div>



</form>

<script type="text/javascript">
	var avatar1 = new KTImageInput('kt_image_1');

	$(document).on('click', '#remark_submit', function(e) {
	// alert();
	$('#remark_submit').prop('disabled', true);
	var ck_box = $('input[type="checkbox"]:checked').length;
	    e.preventDefault();
		var frm = $('.ajaxForm').serialize();
		var image_form = $("#photo").prop("files")[0];
		var fdata = new FormData(); // Creating object of FormData class
		fdata.append("form", frm);
		fdata.append("image_form", image_form);
		var rem = $('.student_id_model').val();
           console.log(rem);
		var imgname  =  $('input[type=file]').val();
		console.log("image_form"+image_form);
		console.log("imgname"+imgname);
        var ext =  imgname.substr( (imgname.lastIndexOf('.') +1) );
    //if(ext=='jpg' || ext=='jpeg' || ext=='png' || ext=='gif' || ext=='PNG' || ext=='JPG' || ext=='JPEG')
  //  {	   console.log("ext"+ext);
		$.ajax({
		url: '<?php echo site_url('update_student_photo/'); ?>'+ rem,
		type: 'POST',
		//enctype: 'multipart/form-data',
		dataType : 'json',
		data: frm,
		processData: false,
		contentType: false,
		cache: false,
		success: function (data) {
		if(data){
			console.log(data);
				$('#right-modal').modal('toggle');
			
				// $('#'+rem).html("Approved");
				
				// $('#' +rem).prop("onclick",null).off("click");
				
				// var appid="#makeNonApprove_"+rem;
				// $(appid).css("display", "none");
				// var id="#nonVerified_"+rem;
				// $(id).css("display", "block");
				// console.log("id "+id);
				// }else{
				// 	toastr.error("Something wrong");
				}
			},
		});	
	// }else{
	// 	toastr.error("Please select Image !");
	// }

});	
</script>
