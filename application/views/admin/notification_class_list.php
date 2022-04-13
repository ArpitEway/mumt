<table class="table table-striped" id="kt_datatable">
	<tbody>
		<?php $i=1; ?>
		<?php foreach ($courses as $course){
        $classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$course['id']));
        ?>
		<tr>
			<th><?=$i++?></th>
			<th><?=$course['course_name']?></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
		</tr>
        <?php foreach($classes as $class) { ?>
        <tr>
        <td></td>
			<td><?= $class->class_name ?></td>
			
			<td>
				<?php $course_id = $this->Common_model->encrypt_decrypt($course['id']);
				$class_id = $this->Common_model->encrypt_decrypt($class->id);
				 ?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list/'.$course_id.'/'.$class_id)  ?>">Notification List</a>
			</td>
		</tr>
        <?php } ; }  ?>
	</tbody>
</table>
