<form method='POST' ?>
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Teacher</label>
			<select class="form-control" name="teacher_id" id="teacher_id" class="teacher_id">
				<option value="" >Select Teacher</option>
				<?php
				foreach ($teachers as $teacher) {
					$teacher_name = $this->Common_model->getRecordByWhere('teacher',array('id'=>$teacher->teacher_id));
					?>
					<option value="<?=$teacher_name[0]->id;?>" ><?=$teacher_name[0]->name?></option>
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
	$(document).ready(function() {
$(document).on('click', '#submit', function() {
	$('#dt').hide();
      var teacher_id =   $('#teacher_id').val();
    
      var csrfName = $('.csrfname').attr('name');
	  var csrfHash = $('.csrfname').val();
 
      var data = {
            teacher_id: teacher_id,
			[csrfName]: csrfHash,
		}; 

      $.ajax({
                url:BASE_URL+'admin/ExamController/load_checked_answersheet_status',
                type:'post',
                dataType : 'JSON',
                data: data,
				beforeSend: function()
			  {
				$("#myLoader").show();
			  },
                success:function(data)
                {    
					if( $("#myLoader").show()){
					$('#dt').hide();
					// $table = $('#dt').html(status.data);
					}if( $('#myLoader').hide()){
						$("#dt").html(data.data);  
						$('#dt').show();
					}
                console.log(data);
				KTDatatablesBasicBasic.init();
                },
				complete: function()
				{
					$('#myLoader').hide();
				},
                
            })
    });
	$('#teacher_id').select2({
        placeholder : 'Search Teacher',
        allowClear: true
     
    })
})
</script>