<style type="text/css">
 select{
    padding: 0px 12px !important;
    height: 30px !important;

}
.student_img{
     width: 100%;
    height: 77%;
 
}
.increase{
   width:100px ;
}

.align{
   padding-right: 100px;  
}
</style>


  <div>      
  <div class="text-center"><label  style="color:red;">Provisional Internal Marks
</label></div>
<div class="text-center"><label ><strong>प्रविष्ट किये जा रहे निम्न अंक</strong> <label style="color:red;">Provisional Marks</label> <strong>हैं - </strong>
</label></div>
<div class="text-center"><label ><strong>Assignments के विश्वविद्यालय में Verification के पश्चात ही Final Marks प्रदान किये जायेंगे। </strong> 
</label></div></div>
 <hr>
          

<div  style="padding-right: 425px;"  class="row py-3">
        <div class="col-6 font-weight-bolder">
         Student Details :
        </div>
      </div>


<table class= "table table-bordered ">
              
              <tbody>


                 <tr> 
                <td><b> Center Code: </b> <?=$details[0]->center_code;?></td>
                <td ><b>Center Name: </b> <?=$details[0]->center_name;?></td>
                 <td  rowspan="5"> <img  class="student_img" src="<?php echo base_url('/assets/student_image/').$details[0]->session.'/'.$details[0]->photo;?>" ></td> 

              </tr>
             
              <tr>
                  
                <td ><strong>Enrollment No: </strong> <?=$details[0]->enrollment_no;?></td>
                <td  ><strong> Roll No: </strong><?=$details[0]->roll_no;?></td>
              </tr>
              <tr> 
                <td><b> Name: </b> <?=$details[0]->name;?></td>
                <td ><b>F/H Name: </b> <?=$details[0]->f_h_name;?></td>
              </tr>
              <tr>
                <td><b>Course: </b> <?=$details[0]->course_name;?></td>
                <td ><b>Class: </b> <?=$details[0]->class_name;?></td>
              </tr>
              
              </tbody>
            </table>



<form id="ajaxForm">
<table  class="table " >
    <thead>
      <tr>
          <th>#</th>
        <th>Paper Code</th>
        <th>Paper Name</th>
        <th>Max Marks</th>
        <th> Min Marks</th>
        <th class="text-center">Marks</th>
               
      </tr>
    </thead>
    <tbody>
      <?php
   $s=1;
      foreach($details as $student){
        ?>
        <tr>
         
 <td><?php echo $s++; ?></td>
          <td><?php echo $student->paper_code; ?></td>
          <td><?=$this->Common_model->getPaperNameById($student->paper_id); ?>

   <input  type="hidden" name="remove" id="student_tr" value="<?= $student->student_id ?>">  
   <input  type="hidden"  name="student_id" value="<?=$student->student_id?>"> 
                <input type="hidden" name="paper_id[]" value="<?=$student->paper_id?>">
                <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 

        </td>
          <td>
              <?php 
       
$view1=  $this->Common_model->getRecordByWhere("paper_master",'class_id='.$details[0]->class_id);

           echo $view1[0]->max_internal_marks;

                      
            ?>  
          </td>
          <td>    <?php 
       
$view=  $this->Common_model->getRecordByWhere("paper_master",'class_id='.$details[0]->class_id);

           echo $view[0]->min_internal_marks;

                        
            ?>  </td>
          <td> 
          
            <select name="marks[]" class="form-control col-12 increase " id="marks"  > 
        <option value="ABS" selected>Absent</option>
        <?php
          
      $max_internal=  $view[0]->max_internal_marks;
      $min_internal=  $view[0]->min_internal_marks; 
  
        for ($i=$min_internal; $i<=$max_internal; $i++)
        {
          ?>
          <option value="<?php echo $i; ?>"  ><?php echo str_pad($i,2,'0',STR_PAD_LEFT); ?></option>
          <?php
        } 
        ?>
      </select>        
          </td>
        
          
        </tr>
        <?php 
      }
      ?>

    </tbody>
  </table>

  <div class="text-center py-3">
      <button type="button" class="btn btn-primary mr-2"  id="markssubmit">Submit</button>
       <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
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
var id = $('#student_tr').val();
 if (confirm('Are you sure for marks update')) 
  {
 
  $.ajax({
    url: '<?php echo site_url('center/center/assignment_marks_sub'); ?>',
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
}
});
</script>