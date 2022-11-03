<table class="table table-striped" id="kt_datatable">
	<tbody>
		<?php $i=1; ?>
		<?php foreach ($courses as $course){
        $classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$course['id'], 'exam_form_permission' => 'Y'));
        ?>
		<tr>
			<th><?=$i++?></th>
			<th><?=$course['course_name']?></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
		</tr>
        <?php foreach($classes as $class) { 
        	$course_id = $this->Common_model->encrypt_decrypt($course['id']);
			$class_id = $this->Common_model->encrypt_decrypt($class->id);
        	?>
        <tr>
        <td></td>
			<td><?= $class->class_name ?></td>
<!-- 			<td><a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/'.$course['id'].'/'.$class->id)  ?>">Result permission</a></td> -->
			<td>
				<?php if ($class->practical_internal_marks=='Y'): ?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/generate_tr_bed/'.$course['id'].'/'.$class->id)  ?>">Generate Tr</a>
					<?php else: ?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/generate_tr/'.$course['id'].'/'.$class->id)  ?>">Generate Tr</a>
				<?php endif ?>
			</td>
			<td>
				<?php if ($class->practical_internal_marks=='Y'): ?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list_bed/'.$course_id.'/'.$class_id)  ?>">Notification</a>
				<?php else:?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list/'.$course_id.'/'.$class_id)  ?>">Notification</a>
				<?php endif?>
			</td>
			<td>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_marksheet/'.$course['id'].'/'.$class->id)  ?>">Marksheet</a>
			</td>
			<td><a target="_blank" href="<?php echo  base_url('admin/admins/withheld_student_list/'.$course['id'].'/'.$class->id)  ?>">Withheld Result (WH)</a></td>
		</tr>
        <?php } ; }  ?>
	</tbody>
</table>
