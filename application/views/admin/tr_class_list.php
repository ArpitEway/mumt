<table class="table table-striped" id="kt_datatable">
	<tbody>
		<?php $i=1; ?>
		<?php foreach ($courses as $course){
        $classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$course['id'], 'admission_permission' => 'Y'));
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
			<td><a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/'.$course['id'].'/'.$class->id)  ?>">Result permission</a></td>
			<td><a target="_blank" href="<?php echo  base_url('admin/admins/generate_tr/'.$course['id'].'/'.$class->id)  ?>">Generate Tr</a></td>
			<td><a target="_blank" href="<?php echo  base_url('admin/admins/student_marksheet/'.$course['id'].'/'.$class->id)  ?>">Marksheet</a></td>
			<td><a target="_blank" href="<?php echo  base_url('admin/admins/withheld_student_list/'.$course['id'].'/'.$class->id)  ?>">Withheld Result (WH)</a></td>
		</tr>
        <?php } ; }  ?>
	</tbody>
</table>
