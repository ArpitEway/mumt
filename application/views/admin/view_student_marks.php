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
<table class= "table table-bordered">
  <tbody>
    <tr>
     <td><strong>Enrollment No: </strong> <?=$detail[0]->enrollment_no;?></td>
     <td><strong> Roll No: </strong><?=$detail[0]->roll_no;?></td>
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
<div class="table-responsive">
  <table  class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Paper Code</th>
        <th>Paper Name</th>
        <th>Internal/Practical Marks</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $s=1;
      foreach($detail as $student){
        ?>
        <tr>
         <td><?php echo $s; ?></td>
         <td><?php echo $student->paper_code; ?></td>
         <td><?=$this->Common_model->getPaperNameById($student->paper_id); ?>
          </td>
        <td class="text-center">
       <?php
          if($student->paper_type=='theory' || $student->paper_type=='Sessional' ){
            echo $student->int_marks;
          }else{
            if($classData->practical_internal_marks=="Y")
               echo $student->int_marks.'/'.$student->p_marks;
            else
                echo $student->p_marks;
          }
        ?>
      </td>
    </tr>
    <?php
    $s++;
  }
  ?>
</tbody>
</table>
</div>
<div class="text-center py-3">
 <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
</div>