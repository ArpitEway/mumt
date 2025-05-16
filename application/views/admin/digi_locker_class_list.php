<div class="text-right mt-3">
</div>
<div class=" mt-5">
	<table id="" class="table table-striped dt-responsive nowrap" width="100%" >
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <thead>
     <tr>
      <th>S.No</th>
      <th>Course Name </th>
      <th>Student Count</th>
    </tr>
  </thead>
  <tbody>
   <?php 		
   $i = 1;
   foreach($courses as $course){			
    ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $course->course_name; ?></td>
      <td><a href="<?=base_url('admin/scripts/Otherscript/dg_locker_data_non_grading/').$course->course_group_id?>" target="_blank"><?php echo $course->total_students; ?></a></td>
     
    </tr>
    <?php $i++; } ?>
</tbody>
</table>
</div>