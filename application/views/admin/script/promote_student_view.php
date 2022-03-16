<form method="post"  action="<?=base_url('admin/scripts/Postexam/promote_student_submit');?>" class="mt-3 answersheet" >
<div class="row p-3">
<div class="text-left col-6">
    <?php 
     
  $class_order =  $this->Common_model->getRecordByWhere('class_master',array('id' => $class_id ,'course_group_id'=>$course_group_id));
  $class_order = $class_order[0]->class_order + 1 ;

  $promote_Class =  $this->Common_model->getRecordByWhere('class_master',array('class_order'=>$class_order,'course_group_id'=>$course_group_id));

    ?>
<h4>Course-Name     <?php echo $course_name .'('.$class_name .')'?></h4>
</div>
<div class=" col-2">
<h4>Promote To :   <?php echo $promote_Class[0]->class_name ?></h4>
</div>
<div class=" col-4">
<button type="submit" class="btn btn-primary" id="submit" name="submit" >Promote Now</button>
</div>
</div>
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<input type="hidden"  name="old_class_id" value="<?=$class_id;?>">
<input type="hidden"  name="new_class_id" value="<?=$promote_Class[0]->id;?>">
<input type="hidden"  name="course_group_id" value="<?=$course_group_id;?>">


<input type="hidden"  name="class_name" value="<?=$promote_Class[0]->class_name;?>">

<table class="table table-striped" id="kt_datatable">
	<tbody>
		<tr>
            <th>Institute Code</th>
            <th>Student Name</th>
            <th>Session</th>
            <th><input type="checkbox" id="all_student_checked"></th>
		</tr>
        <?php foreach($students as $student) {
            ?>
        <tr>
			<td><?= $student->center_code ?></td>
			<td><?= $student->name ?></td>
			<td><?= $student->session ?></td>
			<td><input type="checkbox" class="checkbox" name="student_id[]" value="<?=$student->student_id;?>"></td>

		</tr>
        <?php  }  ?>
	</tbody>
</table>
</form>
<script>
		$('#all_student_checked').on('change', function() {
			if($('#all_student_checked').is(":checked")){
				setCheckboxes3(1);
			}else{
				setCheckboxes3(2);
			}
		});

		
function setCheckboxes3(act)
  {
  elts = document.getElementsByName("student_id[]");
  var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
  if (elts_cnt)
    {
    for (var i = 0; i < elts_cnt; i++)
      {
      elts[i].checked = (act == 1 || act == 0) ? act : (elts[i].checked ? 0 : 1);
      }
    }
  }

  
$(document).on('click', '#submit', function(e) {
    if($("input:checkbox").filter(":checked").length<1){
        toastr.error("PLease Select atleast one");
        return false ;
    }


});		
</script>