<div class="text-right mt-3">
</div>
<div class=" mt-5">
	<table id="kt_datatable" class="table table-striped dt-responsive" width="100%" >
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <thead>
     <tr>
      <th>S.No</th>
      <th>Center Name </th>
      <th>Center Code</th>
      <th>Admission Permission Regular</th>
      <th>Admission Permission Private</th>
      <th>Exam Form Permission</th>
      <th>Admit Card Permission</th>
      <th>Result Permission</th>
    </tr>
  </thead>
  <tbody>
   <?php 		
   $i = 1;
   foreach($centers as $center){			
    ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $center->center_name; ?></td>
      <td><?php echo $center->center_code; ?></td>
      <td>
        <button id="btn_admission_reg_<?php echo  $center->id?>" <?php if($center->admission_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="admission_permission_reg(<?php echo $center->id ;  ?>,'<?php echo $center->admission_permission;?>')">
          <?php if($center->admission_permission=='Y' ){echo "Yes" ;}else{
            echo " No";
          } ?></button>
        </td>
        <td>
          <button id="btn_admission_pvt_<?php echo  $center->id?>" <?php if($center->admission_permission_private=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="admission_permission_pvt(<?php echo $center->id ;  ?>,'<?php echo $center->admission_permission_private;?>')">
            <?php if($center->admission_permission_private=='Y' ){echo "Yes" ;}else{
              echo " No";
            } ?></button>
          </td>
          <td>
            <button id="btn_exam_form_<?php echo  $center->id?>" <?php if($center->exam_form_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="exam_form_permission(<?php echo $center->id ;  ?>,'<?php echo $center->exam_form_permission;?>')">
              <?php if($center->exam_form_permission=='Y' ){echo "Yes" ;}else{
                echo " No";
              } ?></button>
            </td>
            <td>
              <button id="btn_admit_card_<?php echo  $center->id?>" <?php if($center->admit_card_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="admit_card_permission(<?php echo $center->id ;  ?>,'<?php echo $center->admit_card_permission;?>')">
                <?php if($center->admit_card_permission=='Y' ){echo "Yes" ;}else{
                  echo " No";
                } ?></button>
              </td>
              <td>
                <button id="btn_result_permission_<?php echo  $center->id?>" <?php if($center->result_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="result_permission(<?php echo $center->id ;  ?>,'<?php echo $center->result_permission;?>')">
                  <?php if($center->result_permission=='Y' ){echo "Yes" ;}else{
                    echo " No";
                  } ?></button>
                </td>
              </tr>
              <?php $i++; } ?>
            </tbody>
          </table>
        </div>

        <script>

          function admission_permission_reg(id,admission_permission){

            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $.ajax({
             url: BASE_URL+"admin/Permission/update_admission_permission_regular",
             type:"post",
             dataType: 'json',
             data:{"center_id":id,"admission_permission":admission_permission,[csrfName]:csrfHash},
             success: function(response){
              if(response.success==true){
                $("#btn_admission_reg_"+id).removeClass("btn btn-success");
                $("#btn_admission_reg_"+id).addClass("btn btn-danger");
                $("#btn_admission_reg_"+id).html("No");
                var s="admission_permission_reg("+ id +",'N')";
                $("#btn_admission_reg_"+id).attr("onclick",s);
              }else  if(response.error==false){
                $("#btn_admission_reg_"+id).removeClass("btn btn-danger");
                $("#btn_admission_reg_"+id).addClass("btn btn-success");
                $("#btn_admission_reg_"+id).html("Yes");
                var s="admission_permission_reg("+ id +",'Y')";
                $("#btn_admission_reg_"+id).attr("onclick",s);
              }
            }
          });
          }

          function admission_permission_pvt(id,admission_permission_private){

            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $.ajax({
             url: BASE_URL+"admin/Permission/update_admission_permission_private",
             type:"post",
             dataType: 'json',
             data:{"center_id":id,"admission_permission_private":admission_permission_private,[csrfName]:csrfHash},
             success: function(response){
              if(response.success==true){
                $("#btn_admission_pvt_"+id).removeClass("btn btn-success");
                $("#btn_admission_pvt_"+id).addClass("btn btn-danger");
                $("#btn_admission_pvt_"+id).html("No");
                var s="admission_permission_pvt("+ id +",'N')";
                $("#btn_admission_pvt_"+id).attr("onclick",s);
              }else  if(response.error==false){
                $("#btn_admission_pvt_"+id).removeClass("btn btn-danger");
                $("#btn_admission_pvt_"+id).addClass("btn btn-success");
                $("#btn_admission_pvt_"+id).html("Yes");
                var s="admission_permission_pvt("+ id +",'Y')";
                $("#btn_admission_pvt_"+id).attr("onclick",s);
              }
            }
          });
          }


          function exam_form_permission(id,exam_form_permission)
          {

            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $.ajax({
             url: BASE_URL+"admin/Permission/update_center_exam_form_permission",
             type:"post",
             dataType: 'json',
             data:{"center_id":id,"exam_form_permission":exam_form_permission,[csrfName]:csrfHash},
             success: function(response){
              if(response.success==true){
                $("#btn_exam_form_"+id).removeClass("btn btn-success");
                $("#btn_exam_form_"+id).addClass("btn btn-danger");
                $("#btn_exam_form_"+id).html("No");
                var s="exam_form_permission("+ id +",'N')";
                $("#btn_exam_form_"+id).attr("onclick",s);
              }else  if(response.error==false){
                $("#btn_exam_form_"+id).removeClass("btn btn-danger");
                $("#btn_exam_form_"+id).addClass("btn btn-success");
                $("#btn_exam_form_"+id).html("Yes");
                var s="exam_form_permission("+ id +",'Y')";
                $("#btn_exam_form_"+id).attr("onclick",s);
              }
            }
          });
          }


          function admit_card_permission(id,admit_card_permission)
          {
            
            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $.ajax({
             url: BASE_URL+"admin/Permission/update_center_admit_card_permission",
             type:"post",
             dataType: 'json',
             data:{"center_id":id,"admit_card_permission":admit_card_permission,[csrfName]:csrfHash},
             success: function(response){
              if(response.success==true){
                $("#btn_admit_card_"+id).removeClass("btn btn-success");
                $("#btn_admit_card_"+id).addClass("btn btn-danger");
                $("#btn_admit_card_"+id).html("No");
                var s="admit_card_permission("+ id +",'N')";
                $("#btn_admit_card_"+id).attr("onclick",s);
              }else  if(response.error==false){
                $("#btn_admit_card_"+id).removeClass("btn btn-danger");
                $("#btn_admit_card_"+id).addClass("btn btn-success");
                $("#btn_admit_card_"+id).html("Yes");
                var s="admit_card_permission("+ id +",'Y')";
                $("#btn_admit_card_"+id).attr("onclick",s);
              }
            }
          });
          }


          function result_permission(id,result_permission)
          {
            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $.ajax({
             url: BASE_URL+"admin/Permission/update_center_result_permission",
             type:"post",
             dataType: 'json',
             data:{"center_id":id,"result_permission":result_permission,[csrfName]:csrfHash},
             success: function(response){
              if(response.success==true){
                $("#btn_result_permission_"+id).removeClass("btn btn-success");
                $("#btn_result_permission_"+id).addClass("btn btn-danger");
                $("#btn_result_permission_"+id).html("No");
                var s="result_permission("+ id +",'N')";
                $("#btn_result_permission_"+id).attr("onclick",s);
              }else  if(response.error==false){
                $("#btn_result_permission_"+id).removeClass("btn btn-danger");
                $("#btn_result_permission_"+id).addClass("btn btn-success");
                $("#btn_result_permission_"+id).html("Yes");
                var s="result_permission("+ id +",'Y')";
                $("#btn_result_permission_"+id).attr("onclick",s);
              }
            }
          });
          }
        </script>