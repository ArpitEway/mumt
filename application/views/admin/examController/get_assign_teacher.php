<form method="post"  action="<?=base_url('admin/ExamController/show_counter_folio');?>" class="mt-3 answersheet" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="dt-responsive">
		<table id=""  class="table table-striped" >
			<thead>
				<tr>
					<th>Sno.</th>
                    <th>Teacher Name</th>
					<!--<th>Center Code</th>
					<th>Total Paper</th>
					<th>Available</th>
					<th>Checked</th>-->
					<th><input type="checkbox" id="allAssign_answersheet"></th>
				</tr>
			</thead>
			<tbody>      
				<?php
				$i=1;
				$total_paper = 0 ;
				$available=0 ;
				$checked = 0 ;
				foreach($teachers as $teacher)
				{  
					/*$where = array(
						'paper_code' =>$paper_code,
						'class_id' =>$class_id,
						'center_id' =>$teacher->id,
					);
					$count_for_available = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
					$count_for_checked =  $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array("paper_code"=>$paper_code,'center_id'=>$center->center_id,'teacher_id!='=>''));
					$total_paper = $total_paper + $center->cnt ;
					$available = $available + $count_for_available ;
					$checked = $checked + $count_for_checked ;*/
					?>
					<tr>
						<td><?php echo $i++; ?></td>
                        <td><?php echo $teacher->name;?></td>
						<!--<td><?php //echo $center->center_code;?></td>
						<td><?php //echo $center->cnt;?></td>
						<td><?php //echo $count_for_available ;?></td>
						<td><?php //echo $count_for_checked ;?></td>-->
						<td><input type="checkbox" class="checkbox" name="teacher_id[]" value="<?=$teacher->teacher_id;?>"></td>
					</tr>
					<?php 
				}
				?>
			</tbody>
			<tfoot>
		<!--	<tr>
		
			<td></td>
			<td><?php //echo "Total"; ?></td>
			<td><?php //echo $total_paper ?></td>
			<td><?php //echo $available  ?></td>
			<td><?php //echo $checked ?></td>
			<td></td>
			</tr>-->
			<tfoot>
		</table>
	</div>
	<div class="text-center p-3">
		<input type="hidden" name="action" value="assign_answersheet">
	<!--	<input type="hidden" name="teacher_id" value="<?php echo $teacher_id ; ?>">-->
		<input type="hidden" name="class_id" value="<?php echo $class_id ; ?>">
		<input type="hidden" name="course_group_id" value="<?php  echo $course_group_id ;  ?>">
		<input type="hidden" name="paper_code" value="<?php echo  $paper_code ; ?>">
		<button type="submit" class="btn btn-primary" id="submit" name="submit" >submit</button>
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
  elts = document.getElementsByName("teacher_id[]");
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