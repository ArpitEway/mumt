<style type="text/css">
 select{
    padding: 0px 12px !important;
    height: 30px !important;
}
</style>
<form id="ajaxForm">
  <div class="row py-3">
    <div class="col-6">
      <label for="example-date-input" ><strong>Enrollment No:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->enrollment_no; ?></label>
    </div>
    <div class="col-6">
      <label for="example-date-input" ><strong>Roll No:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->roll_no; ?><?= 'ss'.$details[0]->student_id;?></label>
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
    <div class="col-2 text-left"><strong>Marks</strong></div>
  </div>
  <input  type="hidden" name="id" id="student_tr" value="<?= $details[0]->id ?>">  
  <input  type="hidden" name="id" id="student_id" value="<?= $details[0]->id ?>"> 
  <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
  <div class="">
    <fieldset class="mb-2 row">
      <label for="Questions1" class="col-3 text-right"><strong>1.</strong></label>
      <label for="Questions1" class="col-4 text-center"><strong>Questions 1 </strong> </label>
      <select name="marks1" class="form-control col-2 text-left" id="marks1">
        <option value="">N/A</option>
        <?php   for ($i=0; $i<=14; $i++){   ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php } ?>
      </select>
    </fieldset>
    <fieldset class="mb-2 row">
      <label for="Questions1" class="col-3 text-right"><strong>2.</strong></label>
      <label for="Questions2" class="col-4 text-center"><strong>Questions 2</strong></label>
      <select name="marks2" class="form-control col-2 text-left" id="marks2">
        <option value="">N/A</option>
        <?php for ($i=0; $i<=14; $i++){ ?>
          <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
          <?php } ?>
      </select>
    </fieldset>
    <fieldset class="mb-2 row">
      <label for="Questions3" class="col-3 text-right"><strong>3.</strong></label>
      <label for="Questions3" class="col-4 text-center"><strong>Questions 3</strong></label>
      <select name="marks3" class="form-control col-2 text-left" id="marks3">
        <option value="">N/A</option>
        <?php for ($i=0; $i<=14; $i++){ ?>
          <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
          <?php } ?>
      </select>
    </fieldset>

    <fieldset class="mb-2 row">
      <label for="Questions4" class="col-3 text-right"><strong>4.</strong></label>
      <label for="Questions4" class="col-4 text-center"><strong>Questions 4</strong></label>
      <select name="marks4" class="form-control col-2 text-left" id="marks4">
        <option value="">N/A</option>
        <?php
        for ($i=0; $i<=14; $i++)
        {
          ?>
          <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
          <?php
        } 
        ?>
      </select>        
    </fieldset>
    <fieldset class="mb-2 row">
      <label for="Questions5" class="col-3 text-right"><strong>5.</strong></label>
      <label for="Questions5" class="col-4 text-center"><strong>Questions 5</strong></label>
      <select name="marks5" class="form-control col-2 text-left" id="marks5">
        <option value="">N/A</option>
        <?php  for ($i=0; $i<=14; $i++){    ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
        <?php  }  ?>
      </select>
    </fieldset>
  </div>
  <hr>
  <div class="text-center py-3">
      <button type="submit" class="btn btn-primary mr-2"  id="markssubmit">Submit</button>
     </div>
</form>
<script>
  $("#markssubmit").on('click',function (e){
   e.preventDefault();
   var marks1 = $('#marks1').val();
   var marks2 = $('#marks2').val();
   var marks3 = $('#marks3').val();
   var marks4 = $('#marks4').val();
   var marks5 = $('#marks5').val();

   var submit = true;
   if(marks1==''){
    $(marks1).next().text('Please Select Marks');
    submit = false;
  }else{
    $(marks1).next().text('');
  }

  if(marks2==''){
    $(marks2).next().text('Please Select Marks');
    submit = false;
  }else{
    $(marks2).next().text('');
  }
  if(marks3==''){
    $(marks3).next().text('Please Select Marks');
    submit = false;
  }else{
    $(marks3).next().text('');
  }

  if(marks4==''){
    $(marks4).next().text('Please Select Marks');
    submit = false;
  }else{
    $(marks4).next().text('');
  }
  if(marks5==''){
    $(marks5).next().text('Please Select Marks ');
    submit = false;
  }else{
    $(marks5).next().text('');
  }
  if(submit==false){
    return false;
  }
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