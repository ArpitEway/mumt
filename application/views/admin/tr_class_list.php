<table class="table table-striped" id="kt_datatable">
	<tbody>
		<?php $i=1; ?>
		<?php foreach ($courses as $course){
			$this->db->order_by('id');
        $classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$course['id']));
        // , 'exam_form_permission' => 'Y'
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
			<td><?php 
			$flag=($class->regular_class == 'Y' && $class->private_class == 'Y')? '/': '';
			 if($class->regular_class=='Y') { ?> 
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/REG/'.$course['id'].'/'.$class->id)  ?>">Result Regular permission</a>
				<?php } if($class->private_class=='Y') {  echo $flag; ?>
					<a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/PVT/'.$course['id'].'/'.$class->id)  ?>">Result Private permission</a>
					<?php }  ?>
					</td>
			<td>
				<?php
				
				if ($class->practical_internal_marks=='Y'){ 
					 
					 
					 if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/generate_tr_bed")."/REG/".$course['id']."/".$class->id; ?>">Generate Regular Tr</a>
					 <?php } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/generate_tr_bed")."/PVT/".$course['id']."/".$class->id; ?>"> Generate Private tr</a>
					  <?php }  
				

					 }else{ 
				
				if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/generate_tr")."/REG/".$course['id']."/".$class->id; ?>">Generate Regular Tr</a>
					 <?php } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/generate_tr")."/PVT/".$course['id']."/".$class->id; ?>"> Generate Private tr</a>
					  <?php }  
				 } ?>
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
