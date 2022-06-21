<div class="mt-3">
  <div class="form-group col-md-3 mx-auto mb-10">
   <label for="class">Session</label>
   <select name="session" id="session" class="form-control" onchange="getSessionRecord()" >
    <!-- <option <?php //if($sessionsSelect=="All") echo "selected"; ?>>All</option> -->
    <?php 
    foreach($sessions as $session)
    {
     ?>
     <option value="<?php echo $session['id']; ?>" <?php if($sessionsSelect==$session['id']) echo "selected"; ?>><?php echo $session['session']; ?></option>
     <?php
   } 
   ?>		
 </select>
</div>
<table  class="table table-striped dt-responsive nowrap" width="100%" >
  <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
  <thead>
    <tr>
      <th>#</th>
      <th>Course ID </th>
      <th>Course Name </th>
      <th>Regular Mode</th>
      <th>Regular Admission</th> 
      <th>Private Mode</th>
      <th>Private Admission</th>
    </tr>
  </thead>    
  <tbody>
    <?php
    $i=1; 
    foreach($course as $r){
      $course_group = $this->db->get_where('course_group', array('id' => $r->course_group_id))->result_array();
      ?>
      <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $r->course_group_id; ?></td>
        <td><?php echo $r->course_name; ?></td>
        <td><?php echo $course_group[0]['mode']; //echo $r->mode; ?></td>
        <td>
         <button id="btn_<?php echo $r->id?>" <?php if($r->admission_permission_regular=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="statusChange(<?php echo $r->id;  ?>,'<?php echo $r->admission_permission_regular;?>')">
          <?php if($r->admission_permission_regular =='Y'){echo "Yes" ;}else{
            echo "No"; 
          } ?></button>
        </td>
        <td><?php echo $course_group[0]['private_mode']; //echo $r->private_mode; ?></td>
        <td>
         <button id="btn_1<?php echo $r->id?>" <?php if($r->admission_permission_private=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="admission_permission_pvt(<?php echo $r->id;  ?>,'<?php echo $r->admission_permission_private;?>')">
          <?php if($r->admission_permission_private =='Y'){echo "Yes" ;}else{
            echo "No"; 
          } ?></button>
        </td>
      </tr>
      <?php 
      $i++;
    }
    ?>
  </tbody>        
</table>
</div>
<script>
  function getSessionRecord(){

    var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
    var sess=$("#session").val();
    location.href =BASE_URL+"admin/Permission/course_wise_permission/"+sess;
     /* $.ajax({
        url: BASE_URL+"admin/Permission/course_wise_permission/"+sess,
          type:"get",
          dataType: 'json',
         data:{"session_id":sess,[csrfName]:csrfHash},
          success: function(response){
            console.log(response);
          
          
          }
   
      });
      */
    }
    function statusChange(id,admission_permission){

     var csrfName = $('.csrfname').attr('name');
     var csrfHash = $('.csrfname').val(); 
     $.ajax({
       url: BASE_URL+"admin/Permission/update_course_wise_permission",
       type:"post",
       dataType: 'json',
       data:{"course_id":id,"admission_permission":admission_permission,[csrfName]:csrfHash},
       success: function(response){
        if(response.success==true){
          $("#btn_"+id).removeClass("btn btn-success");
          $("#btn_"+id).addClass("btn btn-danger");
          $("#btn_"+id).html("No");
          var s="statusChange("+ id +",'N')";
          $("#btn_"+id).attr("onclick",s);
        }else  if(response.error==false){
          $("#btn_"+id).removeClass("btn btn-danger");
          $("#btn_"+id).addClass("btn btn-success");
          $("#btn_"+id).html("Yes");
          var s="statusChange("+ id +",'Y')";
          $("#btn_"+id).attr("onclick",s);
        }
      }
    });
   }


   function admission_permission_pvt(id,admission_permission_pvt){

     var csrfName = $('.csrfname').attr('name');
     var csrfHash = $('.csrfname').val(); 
     $.ajax({
       url: BASE_URL+"admin/Permission/update_course_wise_permission",
       type:"post",
       dataType: 'json',
       data:{"course_id":id,"admission_permission_pvt":admission_permission_pvt,[csrfName]:csrfHash},
       success: function(response){
        console.log(response);
        if(response.success==true){
          $("#btn_1"+id).removeClass("btn btn-success");
          $("#btn_1"+id).addClass("btn btn-danger");
          $("#btn_1"+id).html("No");
          var s="admission_permission_pvt("+ id +",'N')";
          $("#btn_1"+id).attr("onclick",s);
        }else  if(response.error==false){
          $("#btn_1"+id).removeClass("btn btn-danger");
          $("#btn_1"+id).addClass("btn btn-success");
          $("#btn_1"+id).html("Yes");
          var s="admission_permission_pvt("+ id +",'Y')";
          $("#btn_1"+id).attr("onclick",s);
        }
      }
    });
   }
 </script>