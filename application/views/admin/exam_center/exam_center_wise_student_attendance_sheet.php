<?php //print_r($exam_centers); ?>
 <div class="  text-center p-3">
     <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
     <div class="form-group col-md-6 mx-auto">
        <label for="course">Exam Center</label>
        <select  name="exam_center" readonly="readonly" id="exam_center" class="form-control course" required>
            <option value="" selected >Select Exam Center</option>
            <!-- <option value="All"  >All</option> -->
            <?php 

            foreach($exam_centers as $ecenter)
            {
                ?>
                <option value="<?php echo $ecenter->id; ?>"   ><?php echo $ecenter->examcentercode.' ('.$ecenter->schoolcollegename.')'; ?></option>
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
<div class="dt-responsive">

</div> 


<script>
    // $(document).ready(function() {
    //      $('#exam_center').change();
    // });
    $("#exam_center").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var exam_center = $(this).val();
  $('.dt-responsive').html("");
  //console.log($("#multiple").val());
  //var multiple=$("#multiple").val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"ExamController/get_exam_center_wise_student_attendance_sheet",
			data: { exam_center : exam_center,
					[csrfName]:csrfHash
					},
		})
		.done(function( msg ) {
            $('.dt-responsive').html(msg);
		});
	});
</script>