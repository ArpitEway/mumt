<style type="text/css">
 select{
    padding: 0px 12px !important;
    height: 30px !important;
}
</style>
<?php
$classid=$this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$details[0]->class_id));
       $max_theory_marks=   $classid[0]->max_theory_marks  ;    
          $marks=  $max_theory_marks/5; ?>

<form id="ajaxForm">
  <div class="row py-3">
    <div class="col-6">
      <label for="example-date-input" ><strong>Enrollment No:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->enrollment_no; ?></label>
    </div>
    <div class="col-6">
      <label for="example-date-input" ><strong>Roll No:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->roll_no; ?></label>
    </div>
  </div>
  <div class="row py-3">
    <div class="col-6">
      <label for="example-date-input"><strong>Course Name:</strong></label>
      <label for="example-date-input"><?php echo $details[0]->course_name; ?></label>
    </div>  
    <div class="col-6">
      <label for="example-date-input"><strong>Class Name:</strong></label>
      <label for="example-date-input"><?php echo $details[0]->class_name; ?></label>
    </div>
  </div>
  <div class="row py-3">
    <div class="col-6">
      <label ><strong>Paper Name:</strong></label>
      <label for="example-date-input">
        <?php
        $papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$details[0]->paper_code));
        ?>
        <?php echo ' ( '.$details[0]->paper_code.' ) '. $papername[0]->paper_name ;
      ?> </label>
    </div>
    <div class="col-6 text-center">
      <a target="_blank" class="btn btn-primary" href="<?php  echo base_url('Teacher/check_answersheet_pdf/'.$this->Common_model->encrypt_decrypt($details[0]->id,'encrypt'));?>" >View</a>
    </div>
  </div>
  <hr>
  <div class="form-group row">
    <div  class="col-3 text-right"><strong>#</strong></div>
    <div  class="col-4 text-center"><strong>Questions</strong></div>
    <div class="col-3 text-center"><strong>Marks</strong></div>
  </div>
  <input  type="hidden" name="id" id="student_tr" value="<?= $details[0]->id ?>">  
  <input  type="hidden" name="id" id="student_id" value="<?= $details[0]->id ?>"> 
  <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
  <div class="">
    <fieldset class="mb-2 row">
      <label for="Questions1" class="col-3 text-right"><strong>1.</strong></label>
      <label for="Questions1" class="col-4 text-center"><strong>Question 1 </strong> </label>
      <select name="marks1" class="form-control col-3 text-left marks" id="marks1" onchange="selectMarks(event)">
        <option value="N/A" selected>N/A</option>
        <?php  
        for ($i=0; $i<=$marks; $i++)
        {
          ?>
          <option student-marks="<?php echo $i; ?>" value="<?php echo $i; ?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
          <?php
        } 
        ?>      
       
      </select>
    </fieldset>
    <fieldset class="mb-2 row">
      <label for="Questions1" class="col-3 text-right"><strong>2.</strong></label>
      <label for="Questions2" class="col-4 text-center"><strong>Question 2</strong></label>
      <select name="marks2" class="form-control col-3 text-left marks" id="marks2"  onchange="selectMarks(event)" >
        <option value="N/A" selected>N/A</option>
        <?php  
        for ($i=0; $i<=$marks; $i++)
        {
          ?>
          <option student-marks="<?php echo $i; ?>" value="<?php echo $i; ?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
          <?php
        } 
        ?>
      </select>
    </fieldset>
    <fieldset class="mb-2 row">
      <label for="Questions3" class="col-3 text-right"><strong>3.</strong></label>
      <label for="Questions3" class="col-4 text-center"><strong>Question 3</strong></label>
      <select name="marks3" class="form-control col-3 text-left marks" id="marks3" onchange="selectMarks(event)" >
        <option value="N/A" selected>N/A</option>
        <?php  
        for ($i=0; $i<=$marks; $i++)
        {
          ?>
          <option student-marks="<?php echo $i; ?>" value="<?php echo $i; ?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
          <?php
        } 
        ?>

      </select>
    </fieldset>

    <fieldset class="mb-2 row">
      <label for="Questions4" class="col-3 text-right"><strong>4.</strong></label>
      <label for="Questions4" class="col-4 text-center"><strong>Question 4</strong></label>
      <select name="marks4" class="form-control col-3 text-left marks" id="marks4" onchange="selectMarks(event)" > 
        <option value="N/A" selected>N/A</option>
        <?php  
        for ($i=0; $i<=$marks; $i++)
        {
          ?>
          <option student-marks="<?php echo $i; ?>" value="<?php echo $i; ?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
          <?php
        } 
        ?>

      </select>        
    </fieldset>
    <fieldset class="mb-2 row">
      <label for="Questions5" class="col-3 text-right"><strong>5.</strong></label>
      <label for="Questions5" class="col-4 text-center"><strong>Question 5</strong></label>
      <select name="marks5" class="form-control col-3 text-left marks" id="marks5"  onchange="selectMarks(event)">
        <option value="N/A" selected>N/A</option>
        <?php  
        for ($i=0; $i<=$marks; $i++)
        {
          ?>
          <option student-marks="<?php echo $i; ?>" value="<?php echo $i; ?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
          <?php
        } 
        ?>

      </select>
    </fieldset>
  <hr>

    <fieldset class="mb-2 row">
      <label for="Questions1" class="col-3 text-right"></label>
      <label for="Questions1" class="col-4 text-center"><strong>Total</strong> </label>
      <p id="total_marks"></p>
    </fieldset>
   

  </div>
  <div class="form-group col-md-7 m-auto">
	<label for="class">Remark</label>
	<textarea class="form-control" id="remark" name="remark" ></textarea>
	</div>
  <hr>
  <div class="text-center py-3">
      <button type="submit" class="btn btn-primary mr-2"  id="markssubmit">Submit</button>
     </div>
</form>
<script>
  
   function selectMarks(e) {
    var total_marks = $('select option:selected').map(function() {
      return $(this).attr('student-marks');
    })
    .get().map(parseFloat).reduce(function(a, b) {
      return a + b
    });
     console.log(total_marks)
    
      document.getElementById("total_marks").innerHTML = total_marks;
     }
 

  $("#markssubmit").on('click',function (e){
   e.preventDefault();
  //  var marks1 = $('#marks1').val();
  //  var marks2 = $('#marks2').val();
  //  var marks3 = $('#marks3').val();
  //  var marks4 = $('#marks4').val();
  //  var marks5 = $('#marks5').val();

  //  var submit = true;
  //  if(marks1==''){
  //   $(marks1).next().text('Please Select Marks');
  //   submit = false;
  // }else{
  //   $(marks1).next().text('');
  // }

  // if(marks2==''){
  //   $(marks2).next().text('Please Select Marks');
  //   submit = false;
  // }else{
  //   $(marks2).next().text('');
  // }
  // if(marks3==''){
  //   $(marks3).next().text('Please Select Marks');
  //   submit = false;
  // }else{
  //   $(marks3).next().text('');
  // }

  // if(marks4==''){
  //   $(marks4).next().text('Please Select Marks');
  //   submit = false;
  // }else{
  //   $(marks4).next().text('');
  // }
  // if(marks5==''){
  //   $(marks5).next().text('Please Select Marks ');
  //   submit = false;
  // }else{
  //   $(marks5).next().text('');
  // }
  // if(submit==false){
  //   return false;
  // }
  var frm = $('#ajaxForm').serialize();

  var id = $('#student_tr').val();
  $.ajax({
    url: '<?php echo site_url('teacher/Teacher/question_paper_sub'); ?>',
    method: 'post',
    data: frm,
    dataType: 'JSON',
    success: function (data) {
      if(data.success){
        toastr.success(data.success);
           // $('.student').remove();
           $('#kt_datepicker_modal').modal('toggle');

           $('.modal-backdrop').remove();
           $('#student_tr_'+id).hide();

  // $('#kt_datatable info_row[id="'+tr_id+'"]').remove();

}else{
  toastr.error(data.error);
}
},
});
});
</script>