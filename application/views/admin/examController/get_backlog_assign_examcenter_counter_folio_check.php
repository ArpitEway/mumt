<form method="post"  action="<?=base_url('ExamController/show_backlog_counter_folio_check');?>" class="mt-3 answersheet" target="_blank">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="dt-responsive d-flex justify-content-center">
		<table id=""  class="table table-striped w-50 table-hover" >
			<thead>
				<tr>
					<th class="text-center">Sno.</th>
                    <!-- <th>Exam Center Name</th> -->
					<th class="text-center">Exam Center Code</th>
					
					<th><input type="checkbox" id="allAssign_answersheet"></th>
				</tr>
			</thead>
			<tbody class="text-center">      
				<?php
				$i=1;
				$total_paper = 0 ;
				$available=0 ;
				$checked = 0 ;
               // print_r($examcenters);die;
				foreach($examcenters as $center)
				{  
					
					?>
					<tr>
						<td><?php echo $i++; ?></td>
                        <!-- <td><?php //echo $center->schoolcollegename;?></td> -->
						<td class="font-weight-bolder text-primary"><?php echo $center->exam_center_code;?></td>
						
						<td><input type="checkbox" class="checkbox" name="exam_center_id[]" value="<?=$center->exam_center_id;?>"></td>
					</tr>
					<?php 
				}
				?>
			</tbody>
			<tfoot>
		
			<tfoot>
		</table>
	</div>
	<div class="text-center p-3">
		<input type="hidden" name="action" value="assign_examcenter">
		<input type="hidden" name="university_mode" value="<?php echo $university_mode ; ?>">
		<input type="hidden" name="class_id" value="<?php echo $class_id ; ?>">
		<input type="hidden" name="course_group_id" value="<?php  echo $course_group_id ;  ?>">
		<input type="hidden" name="paper_code" value="<?php echo  $paper_code ; ?>">
		<button target="_blank" type="submit" class="btn btn-primary" id="submit" name="submit"  value="submit" >submit</button>
	</div>
</form>

<script>
		$('#allAssign_answersheet').on('change', function() {
			if($('#allAssign_answersheet').is(":checked")){
				setCheckboxes3(1);
			}else{
				setCheckboxes3(2);
			}
		});

		
function setCheckboxes3(act)
  {
  elts = document.getElementsByName("exam_center_id[]");
  var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
  if (elts_cnt)
    {
    for (var i = 0; i < elts_cnt; i++)
      {
      elts[i].checked = (act == 1 || act == 0) ? act : (elts[i].checked ? 0 : 1);
      }
    }
  }
</script>