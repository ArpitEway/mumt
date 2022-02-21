 <style type="text/css">
 	
 	table.table td {
 		vertical-align: middle;
 	}
 </style>
 <div class="mt-5 table-responsive">

  <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
 	<table  class="table ">
 		<thead>
 			<tr>
 				<th>S.no</th>
 				<th>Course Name</th>
 				<th>Class Name</th>
 				<th>प्रश्न पत्र अपलोड करने की तिथि</th>
 				<th>उत्तर पुस्तिका जमा / अपलोड करने की अंतिम तिथि</th>
 				<th>Exam Permission</th>		
 			</tr>
 		</thead>

 		<tbody>

 			
 			<?php
 			$i = 1;
 			$previusDate ='';

 			foreach($category as $key => $date){


 				?>
 				<tr>
 					<?php 



 					$cnt= $this->Common_model->getCountByWhere('time_table', array('exam_start_date'=>$date['exam_start_date']));	 ?>


 					<td ><?php echo $i; ?></td>
 					<td ><?php echo $date['course_name']; ?></td>
 					<td ><?php echo $date['class_name']; ?></td>

 					<?php 

 					if($date['exam_start_date'] != $previusDate){
 						?>

 						<td rowspan="<?php echo $cnt ;?>">

 							<?php echo $this->Common_model->viewDate($date['exam_start_date']); ?></td>

 							<td rowspan="<?php echo $cnt; ?>"><?php echo $date['exam_end_date']; ?></td>

 							<td rowspan="<?php echo $cnt; ?>" >
 								 <button id="btn_<?php echo $i?>" <?php if($date['exam_permission']=='Y' ){echo "class='btn btn-success'" ;}else{echo "class='btn btn-danger' ";} ?> onclick="statusChange('<?php echo $date['exam_start_date'];  ?>','<?php echo $date['exam_permission'];?>',this)">
            <?php if($date['exam_permission']=='Y'){echo "Yes" ;}else{ 
              echo " No";
            } ?></button>
 								</td>

 							<?php }?>


 						</tr>

 						<?php 
 						$previusDate = $date['exam_start_date'];
 						$i++;
 					} ?>
 				</tbody>

 			</table>

 		</div>

 		<script>
 			function statusChange(exam_start_date,exam_permission,self){


var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
 				$.ajax({
 					url: BASE_URL+"admin/admins/update_exam_datewise_permission",
 					type:"post",
 					dataType: 'json',
 					data:{"exam_start_date":exam_start_date,"exam_permission":exam_permission,[csrfName]:csrfHash},
 					success: function(response){
 						if(response.success==true){
 							$(self).removeClass("btn btn-success");
 							$(self).addClass("btn btn-danger");
 							$(self).html("No");
 							var s="statusChange("+ exam_start_date +",'N',this)";
 							$(self).attr("onclick",s);
 						}else  if(response.error==false){
 							$(self).removeClass("btn btn-danger");
 							$(self).addClass("btn btn-success");
 							$(self).html("Yes");
 							var s="statusChange("+ exam_start_date +",'Y',this)";
 							$(self).attr("onclick",s);
 						}
 					}
 				});
 			}

 		</script>