<form method="post" action="<?=base_url('admin/Admins/update_student_result_permission')?>" class="mt-3" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<input type="hidden" name="mode" value="<?=$mode?>">
<div class="text-center p-3">
		<button type="submit" class="btn btn-primary btn-lg" id="submit" name="submit" >Update</button>
	</div>
<hr>
<h1>Not permitted for result</h1>
<hr>
<table class="table table-striped my-5" id="kt_datatable_3">
	<thead>
		<?php $i=1; ?>
		<tr>
            <th>#</th>
            <th>Form. No</th>
            <th>Roll No</th>
            <th>Enrollment No</th>
            <th>Center Code</th>
            <th>Student Name</th>
            <th>Course</th>
            <th>Class</th>
            <th><input type="checkbox"  id="all_checked_not_permitt"></th>
		</tr>
</thead>
        <tbody>
        <?php $i=1 ;  foreach($not_permited_students as $students) {
          
        ?>
        <tr>
        <input type="hidden" name ="class_id" value = "<?php echo  $students->old_class_id  ?>">
        <input type="hidden" name ="course_group_id" value = "<?php echo  $students->course_group_id  ?>">
        <td><?php  echo $i++ ; ?></td>
        <td><?php  echo $students->student_id ?></td>
        <td><?php  echo $students->roll_no ?></td>
        <td><?php  echo $students->enrollment_no ?></td>
        <td><?php  echo $students->center_code ?></td>
        <td><?php  echo $students->name ?></td>
        <td><?php  echo $students->course_name ?></td>
        <td><?php  echo $students->class_name ?></td>
        <td><input type="checkbox" name="not_permitted[]" value="<?=$students->student_id;?>"></td>
        
		</tr>
        <?php } ;  ?>
	</tbody>
</table>
<hr>
<h1> Permitted for result</h1>
<hr>
<table class="table table-striped my-5" id="kt_datatable_2" >
	<thead>
		<?php $i=1; ?>
		<tr>
            <th>#</th>
            <th>Form. No</th>
            <th>Roll No</th>
            <th>Enrollment No</th>
            <th>Center Code</th>
            <th>Student Name</th>
            <th>Course</th>
            <th>Class</th>
            <th><input type="checkbox" id="all_checked_permitt"></th>
		</tr>
        </thead>
        <tbody>
        <?php $i=1 ;  foreach($permited_students as $students) {

        ?>
        <tr>
        <input type="hidden" name ="class_id" value = "<?php echo  $students->old_class_id  ?>">
        <input type="hidden" name ="course_group_id" value = "<?php echo  $students->course_group_id  ?>">
        <td><?php  echo $i++ ; ?></td>
        <td><?php  echo $students->student_id ?></td>
        <td><?php  echo $students->roll_no ?></td>
        <td><?php  echo $students->enrollment_no ?></td>
        <td><?php  echo $students->center_code ?></td>
        <td><?php  echo $students->name ?></td>
        <td><?php  echo $students->course_name ?></td>
        <td><?php  echo $students->class_name ?></td>
        <td><input type="checkbox" class="checkbox" name="permitted[]" value="<?=$students->student_id;?>"></td>
		</tr>
        <?php } ;  ?>
	</tbody>
</table>
</form>
<script>
//   if($("input:checkbox").filter(":checked").length<1){
//         toastr.error("PLease Select atleast one");
//         return false ;
//     }


$(document).on('click', '#submit', function(e) {
    if($("input:checkbox").filter(":checked").length<1){
        toastr.error("PLease Select atleast one");
        return false ;
    }
})


$('#all_checked_not_permitt').on('change', function() {
			if($('#all_checked_not_permitt').is(":checked")){
				setCheckboxes3(1 , 'not_permitted');
			}else{
				setCheckboxes3(2 , 'not_permitted');
			}
		});

$('#all_checked_permitt').on('change', function() {
    if($('#all_checked_permitt').is(":checked")){
        setCheckboxes3(1 ,'permitted');
    }else{
        setCheckboxes3(2 ,'permitted');
    }
});

		
function setCheckboxes3(act , not_permitted)
  {
    if(not_permitted=='not_permitted'){
        elts = document.getElementsByName("not_permitted[]");
    }else{
        elts = document.getElementsByName("permitted[]");
    }
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