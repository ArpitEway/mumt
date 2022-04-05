<style type="text/css">
 select{
    padding: 0px 12px !important;
    height: 30px !important;

}
.student_img{
     width: 79%;
    height: 77%;
 
}

.align{
     width: 79%;
    height: 77%;
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
    <div class="col-6 align">
      <label for="example-date-input"  ><strong> Center Name:</strong></label>
      <label for="example-date-input"><?php echo $details[0]->center_name; ?></label>
    </div>
  </div>

<hr>

<div  class="row py-3">
        <div class="col-6 font-weight-bolder">
         Student Details :
        </div>
      </div>


<table class= "table table-bordered">
              
              <tbody>
             
              <tr>
                <td ><b>Enrollment No: </b> <?=$details[0]->enrollment_no;?></td>
                <td  colspan="2"><b> Roll No: </b><?=$details[0]->roll_no;?></td>
                 <td  rowspan="4"> <img  class="student_img" src="<?php echo base_url('/assets/student_image/').$details[0]->session.'/'.$details[0]->photo;?>"
  ></img></td> 
              </tr>
              <tr> 
                <td><b> Name: </b> <?=$details[0]->name;?></td>
                <td colspan="2"><b>F/H Name: </b> <?=$details[0]->f_h_name;?></td>
              </tr>
              <tr>
                <td><b>Course: </b> <?=$details[0]->course_name;?></td>
                <td colspan="2"><b>Class: </b> <?=$details[0]->class_name;?></td>
              </tr>
              
              </tbody>
            </table>



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
  //  var marks = $('#marks').val();
  //  var submit = true;
  //  if(marks==''){
  //   $(marks).next().text('Please Select Marks');
  //   submit = false;
  // }else{
  //   $(marks).next().text('');
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