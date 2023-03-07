<div class="text-right mt-3">
</div>
<div class=" mt-5" >
	<table id="kt_datatable_scroll" class="table table-striped dt-responsive" width="100%" >
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<thead>
			<tr>
          <th>S.No</th>
          <th>Class Id</th>
          <th>Course name</th>
          <th>Class</th>
          <th>Mode</th>
          <th>Paper Count</th>
          <th>Exam Form Permission</th>
          <th>Backlog Exam Form Permission</th>
          <th>Result Permission</th>
          <th>Final Result Permission</th>
          <th>Admit Card Permission</th>
			</tr>
		</thead>
		<tbody>
    	<?php
    		
    	$i = 1;

      foreach($classes as $class){			
			$courses = $this->db->get_where('course_group', array('id'=>$class->course_group_id))->row_array();     
			$course_name = $courses['course_name'];
    		?>
			
					<tr>
						<td><?php echo $i; ?></td>
            <td><?php echo $class->id; ?></td>
						<td><?php echo $course_name; ?></td>
						<td><?php echo $class->class_name; ?></td>
						<td><?php echo $class->mode; ?></td>
            <td><?php echo $this->Common_model->getCountByWhere('paper_master',array('class_id' => $class->id)); ?></td>
            <td>
                <button id="btn_<?php echo  $class->id?>" <?php if($class->exam_form_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="statusChange(<?php echo $class->id;  ?>,'<?php echo $class->exam_form_permission;?>')">
                <?php if($class->exam_form_permission=='Y' ){echo "Yes" ;}else{
                  echo " No";
                } ?></button>
            </td>
            <td>
                <button id="btnb_<?php echo  $class->id?>" <?php if($class->backlog_exam_form_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="backlogStatusChange(<?php echo $class->id;  ?>,'<?php echo $class->backlog_exam_form_permission;?>')">
                <?php if($class->backlog_exam_form_permission=='Y' ){echo "Yes" ;}else{
                  echo " No";
                } ?></button>
            </td>
            <td>
             <button id="btn1_<?php echo  $class->id?>" <?php if($class->result_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="statusChangeresult(<?php echo $class->id;  ?>,'<?php echo $class->result_permission;?>')">
              <?php if($class->result_permission=='Y' ){echo "Yes" ;}else{
                echo " No";
              } ?></button>
            </td>
            <td>
             <button id="btnFR_<?php echo  $class->id?>" <?php if($class->final_result_permission=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="statusChangeFinalresult(<?php echo $class->id;  ?>,'<?php echo $class->final_result_permission;?>')">
              <?php if($class->final_result_permission=='Y' ){echo "Yes" ;}else{
                echo " No";
              } ?></button>
            </td>
            <td>
                <button id="btn_a_<?php echo  $class->id?>" <?php if($class->admit_card_permission	=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="statusChangeAdmitCard(<?php echo $class->id;   ?>,'<?php echo $class->admit_card_permission;?>')">
                <?php if($class->admit_card_permission=='Y'){echo "Yes" ;}else{
                  echo " No";
                } ?></button>
            </td>
        
       

					</tr>
			<?php $i++; } ?>
	</tbody>
	</table>

</div>

<script>
    function statusChange(id,exam_form_permission){
        var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
      $.ajax({
       url: BASE_URL+"admin/Permission/update_exam_form_permission",
        type:"post",
        dataType: 'json',
        data:{"class_id":id,"exam_form_permission":exam_form_permission,[csrfName]:csrfHash},
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
  function backlogStatusChange(id,backlog_exam_form_permission){
        var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
      $.ajax({
       url: BASE_URL+"admin/Permission/update_backlog_exam_form_permission",
        type:"post",
        dataType: 'json',
        data:{"class_id":id,"backlog_exam_form_permission":backlog_exam_form_permission,[csrfName]:csrfHash},
        success: function(response){
          if(response.success==true){
          $("#btnb_"+id).removeClass("btn btn-success");
          $("#btnb_"+id).addClass("btn btn-danger");
          $("#btnb_"+id).html("No");
           var s="backlogStatusChange("+ id +",'N')";
          $("#btnb_"+id).attr("onclick",s);
        }else  if(response.error==false){
          $("#btnb_"+id).removeClass("btn btn-danger");
          $("#btnb_"+id).addClass("btn btn-success");
          $("#btnb_"+id).html("Yes");
           var s="backlogStatusChange("+ id +",'Y')";
          $("#btnb_"+id).attr("onclick",s);
        }
      }
    });
  }

  function statusChangeAdmitCard(id,admit_card_permission){
    var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
    $.ajax({
     url: BASE_URL+"admin/Permission/update_admit_card_permission",
      type:"post",
      dataType: 'json',
      data:{"class_id":id,"admit_card_permission":admit_card_permission,[csrfName]:csrfHash},
      success: function(response){
        console.log(response);
        if(response.success==true){
        $("#btn_a_"+id).removeClass("btn btn-success");
        $("#btn_a_"+id).addClass("btn btn-danger");
        $("#btn_a_"+id).html("No");
         var s="statusChangeAdmitCard("+ id +",'N')";
        $("#btn_a_"+id).attr("onclick",s);
      }else  if(response.error==false){
        $("#btn_a_"+id).removeClass("btn btn-danger");
        $("#btn_a_"+id).addClass("btn btn-success");
        $("#btn_a_"+id).html("Yes");
         var s="statusChangeAdmitCard("+ id +",'Y')";
        $("#btn_a_"+id).attr("onclick",s);
      }
    }
  });
}


 function statusChangeresult(id,result_permission){
        var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
      $.ajax({
       url: BASE_URL+"admin/Permission/update_result_permission",
        type:"post",
        dataType: 'json',
        data:{"class_id":id,"result_permission":result_permission,[csrfName]:csrfHash},
        success: function(response){
          if(response.success==true){
          $("#btn1_"+id).removeClass("btn btn-success");
          $("#btn1_"+id).addClass("btn btn-danger");
          $("#btn1_"+id).html("No");
           var s="statusChangeresult("+ id +",'N')";
          $("#btn1_"+id).attr("onclick",s);
        }else  if(response.error==false){
          $("#btn1_"+id).removeClass("btn btn-danger");
          $("#btn1_"+id).addClass("btn btn-success");
          $("#btn1_"+id).html("Yes");
           var s="statusChangeresult("+ id +",'Y')";
          $("#btn1_"+id).attr("onclick",s);
        }
      }
    });
  }

  function statusChangeFinalresult(id,final_result_permission) {
    var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
    $.ajax({
      url: BASE_URL+"admin/Permission/update_final_result_permission",
      type:"post",
      dataType: 'json',
      data:{"class_id":id,"final_result_permission":final_result_permission,[csrfName]:csrfHash},
      success: function(response){
        if(response.success==true){
          $("#btnFR_"+id).removeClass("btn btn-success");
          $("#btnFR_"+id).addClass("btn btn-danger");
          $("#btnFR_"+id).html("No");
          var s="statusChangeFinalresult("+ id +",'N')";
          $("#btnFR_"+id).attr("onclick",s);
        }else  if(response.error==false){
          $("#btnFR_"+id).removeClass("btn btn-danger");
          $("#btnFR_"+id).addClass("btn btn-success");
          $("#btnFR_"+id).html("Yes");
          var s="statusChangeFinalresult("+ id +",'Y')";
          $("#btnFR_"+id).attr("onclick",s);
        }
      }
    });
  }


 </script>