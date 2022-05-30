<form method='POST' ?>
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Course</label>
			<select class="form-control" name="course" id="course" class="course">
				<option value="" >Select Course</option>
				<?php
				$i = 1;
				foreach ($courses as $course) {
                      $course_name = $this->Common_model->getCourseNameByCourseId($course['course_group_id']);
                    ?>
					<option value="<?=$course['course_group_id'];?>" ><?=$course_name;?></option>
				<?php } ?>
			</select>

		</fieldset>
		<fieldset class="form-group text-center">
			<input type="hidden" name="action" value="course_wise_amswersheet_list">
			<button type="button" class="btn btn-primary " id="submit">Submit</button>
		</fieldset>
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
<script>
$(document).on('click', '#submit', function() {
      var course_group_id =   $('#course').val();
      var csrfName = $('.csrfname').attr('name');
	  var csrfHash = $('.csrfname').val();
    //  alert(course_group_id);
    //  return false ;
      var data = {
        course_group_id: course_group_id,
			[csrfName]: csrfHash,
		}; 

      $.ajax({
                url:BASE_URL+'admin/ExamController/load_course_wise_answersheet_status',
                type:'post',
                dataType : 'JSON',
                data: data,
                beforeSend: function()
               {
                $("#myLoader").show();
               },
                success:function(data)
                {    
                console.log(data);
                $("#dt").html(data.data); 
                KTDatatablesBasicBasic.init();
                $('#myLoader').hide() ;

                }
                
            })
    });
</script>