<table class= "table table-bordered">
  <tbody>
    <?php
    $i = 1;
    foreach($details as $detail){
      ?>

      <tr>          
       <td><strong>Practical Marks: </strong>  <?=$detail->p_marks;?></td> 
       <td><strong>Internal Marks: </strong><?=$detail->int_marks;?></td>
     </tr> 
     <?php 
     $i++;
   }
   ?>
 </tbody>
</table>
<div class="text-center py-3">
  <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
</div>




