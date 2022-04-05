<style type="text/css">
 select{
    padding: 0px 12px !important;
    height: 30px !important;
}
</style>


 <div  class="row py-3">
        <div class="col-6 font-weight-bolder">
         Information Center :
        </div>
      </div>

<div class="row py-3">
    <div class="col-6">
      <label for="example-date-input" ><strong> Center Code::</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->center_code; ?></label>
    </div>
    <div class="col-6">
      <label for="example-date-input" ><strong> Center Name:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->center_name; ?></label>
    </div>
  </div>

<hr>

<div  class="row py-3">
        <div class="col-6 font-weight-bolder">
         Student Details :
        </div>
      </div>
<div class="row py-3">
    <div class="col-6">
      <label for="example-date-input" ><strong>  Enrollment No:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->enrollment_no; ?></label>
    </div>
    <div class="col-6">
      <label for="example-date-input" ><strong>   Roll No:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->roll_no; ?></label>
    </div>
  </div>


<div class="row py-3">
    <div class="col-6">
      <label for="example-date-input" ><strong>    Name:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->name; ?></label>
    </div>
    <div class="col-6">
      <label for="example-date-input" ><strong>    F/H Name:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->f_h_name; ?></label>
    </div>
  </div>



<div class="row py-3">
    <div class="col-6">
      <label for="example-date-input" ><strong>    Course:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->course_name; ?></label>
    </div>
     <div class="col-6">
      <label for="example-date-input" ><strong>    Class:</strong></label>
      <label for="example-date-input" ><?php echo $details[0]->class_id; ?></label>
    </div> 
  </div>


  <hr>
<form id="ajaxForm">
<table  class="table "  >
    <thead>
      <tr>
       <!--  <th>Sno</th> -->
        <th>Paper Code</th>
        <th>Paper Name</th>
        <th>Max Marks</th>
        <th> Min Marks</th>
        <th>Marks</th>
               
      </tr>
    </thead>
    <tbody>
      <?php
      // $i = 1;
      foreach($details as $student){
        ?>
        <tr>
          <!-- <td><?php echo $i ; ?></td> -->

          <td><?php echo $student->paper_code; ?></td>
          <td><?=$this->Common_model->getPaperNameById($student->paper_id); ?>

   <input  type="hidden"  name="student_id" value="<?=$student->student_id?>"> 
                <input type="hidden" name="paper_id[]" value="<?=$student->paper_id?>">
                <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 

        </td>
          <td>
              <?php 
       
$view=  $this->Common_model->getRecordByWhere("paper_master",'class_id='.$details[0]->class_id);
           echo $view[0]->max_theory_marks;
              //$this->Common_model->last_query();     
           
            ?>  
          </td>
          <td>    <?php 
       
$view=  $this->Common_model->getRecordByWhere("paper_master",'class_id='.$details[0]->class_id);
           echo $view[0]->min_theory_marks;
              //$this->Common_model->last_query();     
           
            ?>  </td>
          <td> 
          
            <select name="marks[]" class="form-control col-12 " id="marks"  > 
        <option value="Absent" selected>Absent</option>
        <?php
        for ($i=0; $i<=14; $i++)
        {
          ?>
          <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
          <?php
        } 
        ?>
      </select>        
          </td>
        
          
        </tr>
        <?php 
        // $i++;
      }
      ?>

    </tbody>
  </table>



  <div class="text-center py-3">
      <button type="button" class="btn btn-primary mr-2"  id="markssubmit">Submit</button>
     </div>
</form>



<script>
  
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

  // var id = $('#student_tr').val();
  $.ajax({
    url: '<?php echo site_url('Center/center/marks_paper_sub'); ?>',
    method: 'post',
    data: frm,
    dataType: 'JSON',
    success: function (data) {
      if(data.success){
        toastr.success(data.success);
           // $('.student').remove();
           $('#kt_datepicker_modal').modal('toggle');

           $('.modal-backdrop').remove();
           // $('#student_tr_'+id).hide();

  // $('#kt_datatable info_row[id="'+tr_id+'"]').remove();

}else{
  toastr.error(data.error);
}
},
});
});
</script>