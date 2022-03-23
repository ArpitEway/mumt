
<style type="text/css">
  .shift{
  margin-left: 135px;
  }

  .positioned {
      /* float:left;*/ 
       display: flex;
  color: black;
  top:40px;
 
}
.align{
  margin-left:250px; 
}

.center{
position: relative;
  padding-left: 106px;
  /*margin-left: 90px;*/
}
.fiexcontains {
  display: flex;
  padding:10px;
    /*flex-wrap: wrap;*/
}
.items {
  
margin-right:60px; 
 
}
</style>

<form id="ajaxForm">
  <div class="fiexcontains ">


 <div class=" items ">
    <label for="example-date-input" ><strong>Enrollment No:</strong></label>
   
    <label for="example-date-input" ><?php echo $details[0]->enrollment_no; ?></label>
  </div>

 <div style="padding-left: 180px;"class="items">
    <label for="example-date-input" ><strong>Roll No:</strong></label>
   
    <label for="example-date-input" ><?php echo $details[0]->roll_no; ?></label>
   </div>

    
    </div>


<div class="fiexcontains ">
  <div class=" items ">
    <label for="example-date-input"><strong>Course Name:</strong></label>
   
    <label for="example-date-input"><?php echo $details[0]->course_name; ?></label>
  </div>  
 <div style="padding-left: 45px;" class=" items ">
    <label for="example-date-input"><strong>Class Name:</strong></label>
  
    <label for="example-date-input"><?php echo $details[0]->class_name; ?></label>
  </div></div>


<div class="fiexcontains ">
  <div class=" items ">
      <label ><strong>Paper Name:</strong></label>
     <label for="example-date-input">
        <?php

        $papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$details[0]->paper_code));
        ?>
        <?php echo ' ( '.$details[0]->paper_code.' ) '. $papername[0]->paper_name ;

        ?> </label>
       </div>
   
    <div class=" item" style="padding-left :50px;">

   <a   target="_blank" class="btn btn-primary" href="<?php  echo base_url('Teacher/check_answersheet_pdf/'.$this->Common_model->encrypt_decrypt($details[0]->id,'encrypt'));?>" >View</a>
 </div>   </div> 
</div>
<div class="card-footer pb-0"></div>
<div class="positioned">
<div  style="padding-left: 155px;" class="center"><strong>#</strong></div>
<div  class="center"><strong>Questions</strong></div><div style="padding-left: 80px;" class="center"><strong>Marks</strong></div>
</div>


<input  type="hidden" name="id" id="student_tr" value="<?= $details[0]->id ?>">  
<input  type="hidden" name="id" id="student_id" value="<?= $details[0]->id ?>"> 
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
<div class="shift">
<fieldset class="form-group col-md-12">
  <label for="Questions1" class="col-2 col-form-label"><strong>1.</strong></label>
  <label for="Questions1" class="col-3 col-form-label "><strong>Questions 1 </strong> </label>    

  <label class="text-heading ">
   <select name="marks1" class="form-control" id="marks1">
    <option value="">N/A</option>

    <?php
    for ($i=0; $i<=14; $i++)
    {
      ?>
      <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
      <?php
    } 
    ?>
  </select>  </label>          
</fieldset>


<fieldset class="form-group col-md-12">
  <label for="Questions1" class="col-2 col-form-label"><strong>2.</strong></label>
  <label for="Questions2" class="col-3 col-form-label"><strong>Questions 2</strong></label>

  <label class="text-heading">
   <select name="marks2" class="form-control" id="marks2">
    <option value="">N/A</option>

    <?php
    for ($i=0; $i<=14; $i++)
    {
      ?>
      <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
      <?php
    } 
    ?>
  </select>  </label>          
</fieldset>



<fieldset class="form-group col-md-12">
  <label for="Questions3" class="col-2 col-form-label"><strong>3.</strong></label>
  <label for="Questions3" class="col-3 col-form-label"><strong>Questions 3</strong></label>

  <label class="text-heading">
   <select name="marks3" class="form-control" id="marks3">
    <option value="">N/A</option>

    <?php
    for ($i=0; $i<=14; $i++)
    {
      ?>
      <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
      <?php
    } 
    ?>
  </select>  </label>          
</fieldset>

<fieldset class="form-group col-md-12">
  <label for="Questions4" class="col-2 col-form-label"><strong>4.</strong></label>
  <label for="Questions4" class="col-3 col-form-label"><strong>Questions 4</strong></label>

  <label class="text-heading mt-3">
   <select name="marks4" class="form-control" id="marks4">
    <option value="">N/A</option>

    <?php
    for ($i=0; $i<=14; $i++)
    {
      ?>
      <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
      <?php
    } 
    ?>
  </select>  </label>          
</fieldset>


<fieldset class="form-group col-md-12">
  <label for="Questions5" class="col-2 col-form-label"><strong>5.</strong></label>

  <label for="Questions5" class="col-3 col-form-label"><strong>Questions 5</strong></label>

  <label class="text-heading mt-3">
   <select name="marks5" class="form-control" id="marks5">
    <option value="">N/A</option>

    <?php
    for ($i=0; $i<=14; $i++)
    {
      ?>
      <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
      <?php
    } 
    ?>
  </select>  </label>          
</fieldset>

</div>
<div class="card-footer pb-0">
 <div class="align">


   <button type="submit" class="btn btn-primary mr-2"  id="markssubmit">Submit</button>

   
 </div>
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
            $('#student_tr_'+id).remove();
  
  // $('#kt_datatable info_row[id="'+tr_id+'"]').remove();
      
          }else{
            toastr.error(data.error);
          }
        },

      }); 

});    

</script> 