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
      <td><strong>Enrollment No: </strong> <?=$detail[0]->enrollment_no;?></td>
      <td><strong> Roll No: </strong><?=$detail[0]->$roll;?></td>
      <td  rowspan="5"> <img  class="student_img" src="<?php echo base_url('/assets/student_image/').$detail[0]->session.'/'.$detail[0]->photo;?>" ></td>
    </tr>
    <tr>
      <td><b> Name: </b> <?=$detail[0]->name;?></td>
      <td><b>F/H Name: </b> <?=$detail[0]->f_h_name;?></td>
    </tr>
    <tr>
      <td><b>Course: </b> <?=$detail[0]->course_name;?></td>
      <td><b>Class: </b> <?=$detail[0]->class_name;?></td>
    </tr>
  </tbody>
</table>

<form id="ajaxForm">
  <table  class="table table-responsive" >
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
            
                $ajax_count=count($detail);
                $removeCounter = 0;

                foreach($detail as $student){
                
                $paper_data=  $this->Common_model->getRecordByWhere("paper_master",array('class_id'=>$student->old_class_id,'paper_code'=>$student->paper_code));
                  
                $percentage = 90;  
                $center_ids_dep = array(10,11,12,13,20,21,22,23,24,25,26,27,28,29);
                $center_id =  $this->session->center_id;
                if (in_array($center_id, $center_ids_dep)){
                  $percentage = 100;  
                }
                $max_internal=  $paper_data[0]->max_internal_marks;
                $min_internal=  $paper_data[0]->min_internal_marks; 
                if($max_internal == 0){
                  $removeCounter++;
                  continue;

                }
              ?>
                <tr>
                
        <td><?php echo $s; ?></td>
                  <td><?php echo $student->paper_code; ?></td>
                  <td><?= $paper_data[0]->paper_name;?>

                        <input  type="hidden" name="remove" id="student_tr" value="<?= $student->student_id ?>">  
                        <input  type="hidden"  name="student_id" value="<?=$student->student_id?>"> 
                        <input type="hidden" name="paper_id[]" value="<?=$student->paper_id?>">
                        <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 

                  </td>
                  <td>
                      <?php
                       echo $max_internal;               
                      ?>  
                  </td>
                  <td><?php 
                        echo $min_internal;                      
                       ?>  
                  </td>
                  <td> 
                 
                      <select name="marks[]" class="form-control col-12 increase"  id="<?="id_{$s}"; ?>"  > 
                     <?php
                      $max_internal_percentage = round(($percentage / 100) * $max_internal);
                      for ($i=$min_internal; $i<=$max_internal_percentage; $i++)

                      {
                       
                        ?>
                        
                        <option class="same_num" value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT); ?>"  <?php echo ($student->int_marks == str_pad($i,2,'0',STR_PAD_LEFT))?'selected':'';?>><?php echo str_pad($i,2,'0',STR_PAD_LEFT); ?></option>
                
                        <?php
                      } 

                      ?>
                      <option value="ABS">ABS</option>    
                    </select>        
                  </td>
                
                  
                </tr>
                <?php 
                $s++; } ?>
        <input type="hidden" value="<?php echo $ajax_count-$removeCounter; ?>" name="count_item" id="count_item"/>
      </tbody>
  </table>

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
      // console.log(counts[key]); 
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


var x=confirm(' Are you sure to update marks ? \n प्रविष्ट किये जा रहे निम्न अंक Provisional Marks हैं।');
  if(x==false){
    return false; 
  }

  var frm = $('#ajaxForm').serialize();
var id = $('#student_tr').val();
 

  $.ajax({
    url: '<?php echo site_url('center/center/assignment_marks_sub'); ?>',
    method: 'post',
    data: frm,
    dataType: 'JSON',
    success: function (data) {
      if(data.success){
        toastr.success(data.success);
         
           $('#kt_datepicker_modal').modal('toggle');
           $('.modal-backdrop').remove();
            // $('#student_tr_'+id).hide();
            $('#roll_'+id).hide();
            $('#roll_num'+id).show();
            $('.child').hide();

}else{
  toastr.error(data.error);
}
},
});

});
</script>
