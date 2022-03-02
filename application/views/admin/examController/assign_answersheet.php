<form  class="d-block ajaxForm">


<div class="row">
   
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-group col-md-3">
            <label for="course">Course</label>
            <select name="course_group_id" readonly="readonly" name='course_group_id' id="course_group_id" class="form-control" required>
                <option value="">Select course</option>
                    <?php 
                 
                    foreach($courses as $course)
                    {
                    ?>
                    <option value="<?php echo $course['course_group_id']; ?>"   ><?php echo $course['course_name']; ?></option>
					<?php
                    } 
                    ?>
            </select>
    </div>
    <div class="form-group col-md-2">

            <label for="course">Class</label>
            <select name="class_id" readonly="readonly" name='class_id' id="class_id" class="form-control" required>
                <option value="">Select Class</option>
            </select>
    </div>
    <div class="form-group col-md-4">

            <label for="paper">Paper</label>
            <select name="paper_id" readonly="readonly"  name='paper_id' id="paper_id" class="form-control" required>
                <option value="">Select Paper</option>
            </select>
   </div>
   <div class="form-group col-md-3">

            <label for="Teacher">Teacher</label>
            <select name="teacher_id" readonly="readonly"   id="teacher" class="form-control" required>
                <option value="">Select Techer</option>
                <?php  
                $teachers = $this->Common_model->get_record('teacher','name , id,subject');
                 foreach($teachers as $teacher)
                 {
                 ?>
                 <option value="<?php echo $teacher['id']; ?>"   ><?php echo $teacher['name']; ?>(<?php echo $teacher['subject']; ?>)</option>
                 <?php
                 } 
                 ?>
            </select>
   </div>
     
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="button" id="submit_form">submit</button>
	</div>
</form>
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
                url:BASE_URL+'admin/ExamController/getPaperByClassId',
                type:'post',
                dataType : 'JSON',
                data: data,
                success:function(data)
                {    
                    console.log(data);
              
            var html = '';
            $.each(data.data, function (i, value) {
                html += ('<option value="' + value.id + '">'+'('+ value.paper_code + ') '+value.paper_name + '</option>');
            });
            $("#paper_id").html(html);
                  
                }
                
            })
    });
});

    $('#course_group_id').select2({
        placeholder : 'Select Course',
        id:"course_group_id",
    })
  $('#course_group_id').change(function(){
    var value = $(this).val();
    $('#course_group_id').trigger('change');
  });


  
$(document).on('click', '#submit_form', function() {
	var frm = $('.ajaxForm').serialize();
var url = BASE_URL + "admin/ExamController/get_center_Code_by_class";

$.ajax({
    url: url,
    type: 'POST',
    dataType: 'json',
    data: frm,
    success: function (data) {
        console.log(data);

        $table = $('#dt').html(data.data);
	//	$('#dt').show();

    }
});

});
</script>