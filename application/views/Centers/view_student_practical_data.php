<style type="text/css">
 select{
  padding: 0px 12px !important;
  height: 30px !important;

}
.student_img{
 width: 120px;
 height: auto;
}
.increase{
 width:100px ;
}

.align{
 padding-right: 100px;
}

</style>

<?php $roll = $this->Common_model->getMaster('roll_number_col');?>
<table class= "table table-bordered">
  <tbody>
    <tr>
     <td><strong>Enrollment No: </strong> <?=$details[0]->enrollment_no;?></td>
     <td><strong> Roll No: </strong><?=$details[0]->$roll;?></td>
     <td  rowspan="5"> <img  class="student_img" src="<?php echo base_url('/assets/student_image/').$details[0]->session.'/'.$details[0]->photo;?>" ></td>
   </tr>
   <tr>
    <td><b> Name: </b> <?=$details[0]->name;?></td>
    <td><b>F/H Name: </b> <?=$details[0]->f_h_name;?></td>
  </tr>
  <tr>
    <td><b>Course: </b> <?=$details[0]->course_name;?></td>
    <td><b>Class: </b> <?=$details[0]->class_name;?></td>
  </tr>
</tbody>
</table>
<form id="ajaxForm">
 <div class="table-responsive">
  <table  class="table" >
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
      $ajax_count=count($details);
      foreach($details as $student){
        ?>
        <tr>
         <td><?php echo $s; ?></td>
         <td><?php echo $student->paper_code; ?></td>
         <td><?=$this->Common_model->getPaperNameById($student->paper_id); ?>
        <input  type="hidden" id="student_tr" value="<?=$student->student_id?>">
         <input  type="hidden"  name="student_id" value="<?=$student->student_id?>">
         <input type="hidden" name="paper_id[]" value="<?=$student->paper_id?>">
         <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
       </td>
        <td>
          <?php
          echo $student->max_theory_marks;
          ?>
        </td>
        <td>    <?php
        echo $student->min_theory_marks;
        ?>  </td>
        <td>
          <select name="marks[]" class="form-control col-12 increase"  id="<?="id_{$s}"; ?>"  >
            <option value="ABS" selected>Absent</option>
            <?php
            $percentage = 85;
            $center_ids_dep = array(10,11,12,13,20,21,22,23,24,25,26,27,28,29);
            $center_id =  $this->session->center_id;
            if (in_array($center_id, $center_ids_dep)){
              $percentage = 100;
            }
            
            $max_practical=  $student->max_theory_marks;
            $min_practical=  $student->min_theory_marks;

            $max_practical_percentage = round(($percentage / 100) * $max_practical);
          
            for ($i=$min_practical; $i<=$max_practical_percentage; $i++){
              ?>
              <option class="same_num" value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT); ?>"  ><?php echo str_pad($i,2,'0',STR_PAD_LEFT); ?></option>
              <?php
            }
            ?>
          </select>
        </td>
    </tr>
    <?php
    $s++;
  }
  ?>
  <input type="hidden" value="<?php echo $ajax_count; ?>" name="count_item" id="count_item"/>
</tbody>
</table>
</div>
<div class="text-center py-3">
  <button type="button" class="btn btn-primary mr-2"  id="markssubmit" >Submit</button>
  <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
</div>
</form>
<script>
  $("#markssubmit").on('click',function (e){
   e.preventDefault();
   var i = 1;
   var count=document.getElementById("count_item").value;
   var marks='';
   var marksArr=[];
   while (count >= i )
   {
    if (document.getElementById(`id_${i}`).value=='ABS')
    {
      var  check = false;
      break;
    }
    var newMarks = document.getElementById(`id_${i}`).value;
    marksArr.push(newMarks);
    i++;
  }
  const filteredArr = marksArr.filter(el=>{
   if(el=newMarks){
    return el;
  }
});
  var counts = {};
  filteredArr.forEach(function(el) { counts[el] = (counts[el] || 0)+1; });
  for (const key in counts) {
    if (counts.hasOwnProperty(key)) {
      //console.log(counts[key]);
      if(counts[key]>2){
        var  check_marks = false;
        break;
      }
    }
  }
  if(check_marks==false){
    alert('आपने दो से अधिक बार समान अंक दर्ज किए हैं');
    return false;
  }

  if(check==false){
    if(confirm('Are You want to set Absent ?')==false){
      return false;
    }
  }


  var x=confirm(' Are you sure to submit marks ? \n प्रविष्ट किये जा रहे निम्न अंक Provisional Marks हैं।');
  if(x==false){
    return false;
  }

  var frm = $('#ajaxForm').serialize();
  var id = $('#student_tr').val();
  $.ajax({
    url: '<?php echo site_url('center/center/practical_assignment_marks_sub'); ?>',
    method: 'post',
    data: frm,
    dataType: 'JSON',
    success: function (data) {
      if(data.msg){
         toastr.success(data.msg);
        $('#kt_datepicker_modal').modal('toggle');
        $('.modal-backdrop').remove();
         $('#roll_'+id).hide();
         $('#roll_num'+id).show();
      }else{
         toastr.error(data.error);
       }
    },
  });
});
</script>
