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
                    <option value="<?php echo $course['course_group_id']; ?>"   ><?php echo $course['course_name']; ?></option>
					<?php
                    } 
                    ?>
            </select>
    </div>
    <div class="form-group col-md-2">

            <label for="course">Class</label>
            <select name="class_id" readonly="readonly" name='class_id' id="class_id" class="form-control class" required>
                <option value="0">Select Class</option>
            </select>
    </div>
    <div class="form-group col-md-4">
            <label for="paper">Paper</label>
            <select name="paper_code" readonly="readonly"   id="paper_id" class="form-control paper" required>
                <option value="0">Select Paper</option>
            </select>
   </div>
   <div class="form-group col-md-3">

            <label for="Teacher">Teacher</label>
            <select name="teacher_id" readonly="readonly"   id="teacher" class="form-control teacher" required>
                <option value="-1">Select Techer</option>
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
	<input type="hidden" class="" name="action1" value="submit">

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
    })
    $('#teacher').select2({
        placeholder : 'Search Teacher',
        allowClear: true
     
    })
  $('#course_group_id').change(function(){
    var value = $(this).val();
    $('#course_group_id').trigger('change');
  });


  
$(document).on('click', '#submit_form', function() {
    var course = document.getElementById('course_group_id').value;
    var class_id = document.getElementById('class_id').value;
    var teacher = document.getElementById('teacher').value;
    var paper_id = document.getElementById('paper_id').value;

    if (course== "" || class_id =="0" || teacher =="-1" || paper_id=="0") {
                 toastr.error("PLease select all fields");
                 return false;
            }
  
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
		

    }
});

});

function Reset() {
      
       // var teacher = document.getElementById("teacher");
        var paper_id = document.getElementById("paper_id");
     

      //  teacher.selectedIndex = 0;
        paper_id.selectedIndex = 0;

      

    }
$(document).on('click', '#submit', function(e) {

	
	var frm = $('.answersheet').serialize();
	$.ajax({
	url: '<?php echo site_url('admin/ExamController/get_center_Code_by_class'); ?>',
	type: 'POST',
	dataType : 'json',
	data: frm,
	success: function (data) {
	if(data){
        $('#dt').empty();
        Reset();
      // $(".select2-selection__clear").trigger('click');
     // $('#teacher').val('-1');
    //$('#teacher').select2().trigger('change');
    //$("#teacher").val([-1]).trigger('change');

		console.log(data);
				toastr.success("Assign Answershet Successfully");	
			}else{
				toastr.error("Something wrong");
			}
		},
	});	
	
});		
</script>