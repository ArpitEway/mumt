<div class="d-flex justify-content-center">
<div class="form-group col-md-2">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<input type="hidden" class="" name="course_group_id" id="course_group_id" value="<?= $course_group_id; ?>">
<input type="hidden" class="" name="class_id" id="class_id" value="<?= $class_id; ?>">
<input type="hidden" class="" name="remaining" id="remaining" value="<?= $remaining; ?>">
<input type="hidden" class="" name="courseType" id="courseType" value="<?= $courseType; ?>">
			<label for="class"><strong>Exam Center</strong></label>
			<select name="exam_center" id="exam_center" class="form-control" >
			    <option value="all">All </option> 
				<?php

                foreach($examcentercode as $exam_center){
                   
                    ?>
                    <option value="<?=$exam_center->examcentercode?>"><?=$exam_center->examcentercode ?></option> 
                    <?php
                }
                ?>
			</select>
		</div>

</div>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div id="dt">
</div>
      <script>
    $(document).ready(function(){
        getData();
});

$(document).on("change","#exam_center",function(){
    getData();
});

function getData(){
    $('#dt').hide();
    var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
	var data = {
		exam_center : $("#exam_center").val(),
        course_group_id : $('#course_group_id').val(),
        class_id : $('#class_id').val(),
        remaining : $('#remaining').val(),
        courseType : $('#courseType').val(),
        [csrfName]:csrfHash,
		
	};


	$.ajax({
		url: '<?= base_url("admin/ExamController/getExamcenterWiseRemainingStudent")?>',
		type:'post',
		dataType: 'json',
		data:data,
		beforeSend: function()
{
$("#myLoader").show();
},

success:function(resp)
{if( $("#myLoader").show()){
$('#dt').hide();

}if( $('#myLoader').hide()){
$('#dt').html(resp.data);
$('#dt').show();

}
KTDatatablesBasicBasic.init();            
}
    });		 
}

</script>
