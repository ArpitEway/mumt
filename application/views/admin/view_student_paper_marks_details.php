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

<form  id="ajax-form" >
<table class= "table table-bordered">
  <tbody>
    <tr>
     <td><strong>Enrollment No: </strong> <?=$detail[0]->enrollment_no;?></td>
     <td><strong> Roll No: </strong><?=$detail[0]->roll_no;?></td>

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
<div class="table-responsive">
  <table  class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Paper Code</th>
        <th>Paper Name</th>
        <th>Q.1</th>
        <th>Q.2</th>
        <th>Q.3</th>
        <th>Q.4</th>
        <th>Q.5</th>
        <th>Total Marks</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $s=1;
      foreach($detail as $student){

        $paper= $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',array("student_id"=>$student->student_id,'paper_code'=>$student->paper_code));
        ?>
        <tr>
         <td><?php echo $s; ?></td>
         <td><?php echo $student->paper_code; ?></td>
         <td><?=$this->Common_model->getPaperNameById($student->paper_id); ?>
          </td>
        <td><?php echo  $paper[0]->que_1; ?> </td>
        <td><?php echo  $paper[0]->que_2; ?> </td>
        <td><?php echo  $paper[0]->que_3; ?> </td>
        <td><?php echo  $paper[0]->que_4; ?> </td>
        <td><?php echo $paper[0]->que_5; ?> </td>
        <td>
          <input type="hidden" name="paper_code[]" value="<?php echo $paper[0]->paper_code; ?>">
          <input type="hidden" name="class_id" value="<?php echo $paper[0]->class_id; ?>">
          <input type="hidden" name="student_id" value="<?php echo $paper[0]->student_id; ?>">
          <input type="number" name="marks" id="marks" value="<?=$paper[0]->total_marks;?>"> 
          <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">      
        </td>
    </tr>
    <?php
    $s++;
  }
  ?>
</tbody>
</table>
</div>
 
 <div class="text-center">
       <button class="btn btn-success " type="submit" id="submit" >Submit</button>
    </div>
<!-- <div>
 <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button></div> -->
 
</form>


<script>
  $('#submit').on('click',function (e) {
      
        e.preventDefault();
        let formData = $('#ajax-form').serialize();
        $.ajax({
            url: BASE_URL+ 'admin/Admins/update_mark_for_all_question',
            method: 'post',
            data: formData,
            dataType: 'JSON',
            success: function (response) {
               if(response.status==true){
        
                  $('#kt_datepicker_modal').modal('hide');
                  toastr.success('update Marks successfully');
              }else{
                  toastr.error('something error');
              }
          }
      })
    })
</script>