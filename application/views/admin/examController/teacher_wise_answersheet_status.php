<form method='POST' ?>">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Course</label>
			<select class="form-control" name="teacher_id" id="teacher_id" class="teacher_id">
				<option value="" >Select Course</option>
				<?php
				$i = 1;
				foreach ($teachers as $teacher) {
                     // $course_name = $this->Common_model->getCourseNameByCourseId($course['course_id']);
                    ?>
					<option value="<?=$teacher->id;?>" ><?=$teacher->name;?></option>
				<?php } ?>
			</select>

		</fieldset>
		<fieldset class="form-group text-center">
			<input type="hidden" name="action" value="course_wise_amswersheet_list">
			<button type="button" class="btn btn-primary " id="submit">Submit</button>
		</fieldset>
	</div>

</form>
<div id="dt">
</div>
<script>
$(document).on('click', '#submit', function() {
      var teacher_id =   $('#teacher_id').val();
      var csrfName = $('.csrfname').attr('name');
	  var csrfHash = $('.csrfname').val();
    //  alert(course_id);
    //  return false ;
      var data = {
        teacher_id: teacher_id,
			[csrfName]: csrfHash,
		}; 

      $.ajax({
                url:BASE_URL+'admin/ExamController/load_teacher_wise_answersheet_status',
                type:'post',
                dataType : 'JSON',
                data: data,
                success:function(data)
                {    
                console.log(data);
                $("#dt").html(data.data);  
				KTDatatablesBasicBasic.init();
                }
                
            })
    });
</script>