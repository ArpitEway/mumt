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
     <td  rowspan="5"> <img  class="student_img" src="<?php echo base_url('/assets/student_image/').$detail[0]->session.'/'.$details[0]->photo;?>" ></td> 
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
        <th>Theory Marks</th>
        <th>Internal Marks</th>
        <th>Practical Marks</th>

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
       <td><?php echo $student->theory_marks;?> </td>
      <td><?php echo $student->int_marks;?> </td>
      <td><?php echo $student->p_marks;?>  </td>
    </tr>
    <?php 
    $s++;
  }
  ?>
</tbody>
</table>

<div class="text-center py-3">

 <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
</div>
</form>


