<form  class="d-block ajaxForm">
    <div class="row">
       <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
       <div class="form-group col-md-3">
        <label for="course">Course</label>
        <select  name="course_group_id" readonly="readonly" name='course_group_id' id="course_group_id" class="form-control course" required>
            <option value="">Select course</option>
            <?php
            foreach($courses as $course)
            {
                ?>
                <option value="<?php echo $course['course_group_id']; ?>"   ><?php echo $this->Common_model->getCourseNameByCourseId($course['course_group_id']); ?></option>
                <?php
            } 
            ?>
        </select>
    </div>
    <div class="form-group col-md-2">

        <label for="course">Class</label>
        <select name="class_id" name='class_id' id="class_id" class="form-control class" required>
            <option value="0">Select Class</option>
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="paper">Paper</label>
        <select name="paper_code" id="paper_id" class="form-control paper" required>
            <option value="0">Select Paper</option>
        </select>
    </div>
    <div class="form-group col-md-2">
			<label for="class">Admission Mode</label>
			<select name="university_mode" id="university_mode" class="form-control" >
			 
				<option value="REG">Regular </option> 
				<option value="PVT" >Private</option>
			</select>
	</div>

    <!--<div class="form-group col-md-3">

        <label for="Teacher">Teacher</label>
        <select name="teacher_id"   id="teacher" class="form-control teacher" required>
            <option value="">Select Techer</option>
            <?php  
          /*  $teachers= $this->Common_model->getRecordByWhere('teacher',array('status'=>'Y'));
            foreach($teachers as $teacher){
               ?>
               <option value="<?php echo $teacher->id; ?>"   ><?php echo $teacher->name; ?>(<?php echo $teacher->subject; ?>)</option>
               <?php
           } */
           ?>
       </select>
   </div>-->

</div>
<div class="form-group text-center">
	<input type="hidden" class="" name="action1" value="submit">

	<button class="btn btn-md btn-primary" type="button" id="submit_form">Search</button>
</div>
</form>
<div align="center" id="myLoader" class="loader_div" style="display: none;" >
  <svg>
    <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
    <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
  </svg>
</div>
<div id="dt">
</div>
<!-- select2 cdn -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        $('#class_id').change(function(){
          var class_id =   $('#class_id').val();

          var data = {
            class_id: class_id,
            [csrfName]: csrfHash,
        }; 

        $.ajax({
            url:BASE_URL+'admin/<?=$this->session->account_type; ?>/getBacklogPaperByClassId',
            type:'post',
            dataType : 'JSON',
            data: data,
           
            success:function(data)
            {    
                console.log(data);

                var html = '';
                html += ('<option value="0">Select Paper</option>');
                $.each(data.data, function (i, value) {
                    html += (
                        '<option value="' + value.paper_code + '">'+'('+ value.paper_code + ') '+value.paper_name + '</option>');

                });
                $("#paper_id").html(html);

            }
           
        })
    });
    });

    $('#course_group_id').select2({
        placeholder : 'Search Course',
        allowClear: true
    })
  /*  $('#teacher').select2({
        placeholder : 'Search Teacher',
        allowClear: true
    }) */
    $('#course_group_id').change(function(){
        var value = $(this).val();
        $('#course_group_id').trigger('change');
    });



    $(document).on('click', '#submit_form', function() {
        $('#dt').hide();
        var course = document.getElementById('course_group_id').value;
        var class_id = document.getElementById('class_id').value;
        var university_mode = document.getElementById('university_mode').value;
        var paper_id = document.getElementById('paper_id').value;

        if (course== "" || class_id =="0" ||  paper_id=="0") {
           toastr.error("PLease select all fields");
           return false;
       }

       var frm = $('.ajaxForm').serialize();
       var url = BASE_URL + "admin/<?=$this->session->account_type; ?>/backlog_search_assign_exam_center";

       $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: frm,
        beforeSend: function()
			{
				$("#myLoader").show();
			},
        success: function (data) {
            if( $("#myLoader").show()){
					$('#dt').hide();
					// $table = $('#dt').html(status.data);
					}if( $('#myLoader').hide()){
                        $table = $('#dt').html(data.data);
						$('#dt').show();
						
					}
            KTDatatablesBasicBasic.init();
        },
        complete: function()
				{
					$('#myLoader').hide();
				},
    });
});

        function Reset() {
        var paper_id = document.getElementById("paper_id");
        paper_id.selectedIndex = 0;
      //  $('#teacher').val('').trigger('change');
        }

        $(document).on('click', '#submit', function(e) {	

        if($("input:checkbox").filter(":checked").length<1){
        toastr.error("PLease Select atleast one");
        return false ;
        }
var frm = $('.answersheet').serialize();
console.log("hello test ");
  /*  $.ajax({
    	url: '<?php echo site_url('admin/<?=$this->session->account_type; ?>/show_backlog_examcenter_folio'); ?>',
    	type: 'POST',
    	dataType : 'json',
    	data: frm,
    	success: function (data) {
            if(data){
                $('#dt').empty();
                Reset();
                toastr.success("Assign Answershet Successfully");	
            }else{
                toastr.error("Something wrong");
            }
        },
    });*/
});		
</script>