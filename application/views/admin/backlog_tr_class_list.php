<style>
	.table th, .table td {
    padding: 0.75rem 0.50rem;
    vertical-align: center;
    border-top: 1px solid #EBEDF3;
}
</style>
<table class="table table-striped" id="kt_datatable">
	<tbody>
		<?php $i=1; ?>
		<?php foreach ($courses as $course){
			$this->db->order_by('id');
			$this->db->where_in('id',array(101,104,107,110,125,128,131,134,168,172,181,194,283,285,202,287,289,291,293,214,295,224,226,297,273));
			
        $classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$course['id'],'backlog_exam_form_permission'=>'Y'));
		// , "backlog_result_permission"=>'Y'
		
        ?>
		<tr>
			<th><?=$i++?></th>
			<th colspan="6"><?=$course['course_name']?></th>
           
		</tr>
        <?php foreach($classes as $class) { 
        	$course_id = $this->Common_model->encrypt_decrypt($course['id']);
			$class_id = $this->Common_model->encrypt_decrypt($class->id);
        	?>
        <tr>
        <td></td>
		<?php 
		$class_ids=array(101,104,107,110,116,119,125,128,131,134);
		$cbcs = (in_array($class->id, $class_ids))?' (CBCS)':'';?>
			<td><?= $class->class_name.$cbcs ?></td>
			<td><?php 
			$flag=($class->regular_class == 'Y' && $class->private_class == 'Y')? '/': '';
			$notification =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Notification ': '';
			$marksheet =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Marksheet ': '';
			$result_permission =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Result permission ': '';
			
			if($class->regular_class=='Y') { 
				
				?> 
				<a target="_blank" href="<?php echo  base_url('admin/admins/backlog_student_result_permission/REG/'.$course['id'].'/'.$class->id)  ?>">Result permission Regular </a>
				<?php } if($class->private_class=='Y') {  echo $flag; ?>
					<a target="_blank" href="<?php echo  base_url('admin/admins/backlog_student_result_permission/PVT/'.$course['id'].'/'.$class->id)  ?>"><?= $result_permission?>Private</a>
					<?php }  ?>
					</td>
			<td>
				<?php
				
				if ($class->practical_internal_marks=='Y'){ 
					 
					 
					 if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/backlog_generate_tr_bed")."/REG/".$course['id']."/".$class->id; ?>">Regular Tr</a>
					 <?php } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/backlog_generate_tr_bed")."/PVT/".$course['id']."/".$class->id; ?>">Private Tr</a>
					  <?php }  
				

					 }else{ 
				
				if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/backlog_generate_tr")."/REG/".$course['id']."/".$class->id; ?>">Regular Tr</a>
					 <?php } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/backlog_generate_tr")."/PVT/".$course['id']."/".$class->id; ?>">Private Tr</a>
					  <?php }  
				 } ?>
			</td>
			<td>
				<?php if ($class->practical_internal_marks=='Y'){ 
					if($class->regular_class=='Y') {?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list_bed/'."/REG/".$course_id.'/'.$class_id)  ?>">Notification Regular</a>
					<?php }if($class->private_class=='Y') { echo $flag;  ?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list_bed/'."/PVT/".$course_id.'/'.$class_id)  ?>"><?= $notification?>Private</a>
				<?php
					} 
				}else{
					if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/backlog_student_notification_list")."/REG/".$course_id."/".$class_id; ?>">Notification Regular</a>
					 <?php } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/backlog_student_notification_list")."/PVT/".$course_id."/".$class_id; ?>"><?= $notification?>Private</a>
					  <?php }  
				
					 }?>
			</td>
			<td>
				<?php
			if($class->regular_class=='Y') { 
				$class_ids=array(101,104,107,110,116,119,125,128,131,134);
				if(in_array($class->id , $class_ids)){
					$std_marksheet = 'student_marksheet_grade';
				}else{
					$std_marksheet = 'backlog_student_marksheet';
				}
				?>   
				<a target="_blank" href="<?php echo  base_url('admin/admins/'.$std_marksheet."/REG/".$course['id'].'/'.$class->id)  ?>">Marksheet Regular</a>
				<?php } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/student_marksheet")."/PVT/".$course['id']."/".$class->id; ?>"><?= $marksheet?>Private</a>
					  <?php }  ?>
			</td>
			<td ><a target="_blank" href="<?php echo  base_url('admin/admins/withheld_backlog_student_list/'.$course['id'].'/'.$class->id)  ?>">Withheld Result (WH)</a></td>
		</tr>
        <?php } ; }  ?>
	</tbody>
</table>
