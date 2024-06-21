<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="container-fluid mt-5">
<div class="row table">
<div class="form-group col-md-4 ">
            <label for="course">Course</label>
            <select name="course_group_id" id="course_group_id" class="form-control course_group_id" data-target=".table #class_id" data-all="all" required >
                <option value="">Select course</option>
                <option value="All">All</option>
                <?php 
                // $courses = $this->db->get_where('course', array())->result_array();
                $courses = $this->Common_model->get_record('course_group','id,course_name');
                    foreach($courses as $course)
                    {
                    ?>
                    <option value="<?php echo $course['id']; ?>" <?php if( $course['id']==$course_group_id) {echo 'selected';}?>><?php echo $course['course_name']; ?></option>    
					<?php
                    } 
                ?>
            </select>       
</div>
<div class="form-group col-md-4">
	<label for="class">Class</label>
    <select name="class_id" id="class_id" class="form-control">
    	<option value="All">Select Class</option>
	</select>
</div>
<div class="form-group col-md-4">
	<label for="class">Modal Paper Status</label>
    <select name="status" id="status" class="form-control">
    	<option value="All">All</option>
        <option value="Y">Uploaded</option>
        <option value="N">Not Uploaded </option>
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

</div>
<script>
loadPaper();
$(document).on("change",".table #status",function(){
    loadPaper();

});
$(document).on("change",".table #class_id",function(){
	    if($("#class_id").val()){
            var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		 var class_id = $(this).val();
        
		} 
        else 
        {
			var class_id = '';
	    }
		var data = {
			class_id : class_id,
            status : $('#status').val(),
			course_group_id : $('.table #course_group_id').val(),
            [csrfName]:csrfHash
			};
        console.log(data);
		var url = BASE_URL + "admin/ExamController/get_papers_by_class_course"; 
		var response = call_ajax(data,url);
        console.log(response);
		$('#dt').html(response.data);
		KTDatatablesBasicBasic.init();	
});

$(document).on("change",".table #course_group_id",function(){
    loadPaper();
});

function loadPaper(){

    var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 

	var course =$("#course_group_id").val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"admin/Admins/getClassByCourse",
			data: { course_group_id : course,
					[csrfName]:csrfHash

					},
		})
		.done(function( msg ) {
            $('#class_id').html(msg);
		});

    $('#dt').hide();
	if($("#course_group_id").val()){
        var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
        var data = {
            class_id : $("#class_id").val(),
            status : $('#status').val(),
            course_group_id : $('.table #course_group_id').val(),
            [csrfName]:csrfHash
        };
	
    $.ajax({
             url:  BASE_URL +'admin/ExamController/get_papers_by_class_course',
             type:'post',
             dataType : 'JSON',
             data:data,
               beforeSend: function()
              {
                $("#myLoader").show();
               },
               success:function(resp)
               {if( $("#myLoader").show()){
               $('#dt').hide();
            // $table = $('#dt').html(status.data);
             }if( $('#myLoader').hide()){
             $('#dt').html(resp.data);
             $('#dt').show();       
          }
                    KTDatatablesBasicBasic.init();
                }//success
            })
	} 
}
</script>