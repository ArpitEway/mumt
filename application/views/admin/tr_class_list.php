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
			$this->db->where_in('id',array(193,197,201,203,205,209,211,213,215,217,221,223,225,227,231,235,237,239,245,247,249,251,253,275,277,279,281,302));
			 // $this->db->where_in('id',array(102,105,108,111,117,120,126,129,132,135,194,198,202,204,206,212,214,222,224,226,228,276,280,303));
			// 187,134,135,159,178,137,138,140,143,146,149,169,170
		   // $this->db->where_in('id',array(267,269,263,261,255,257,172,259,193,195,229,199,233,205,239,302,207,241,209,243,211,245,213,215,223,225,227,253,299));
        $classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$course['id'] , 'old_exam_form_permission' => 'Y' ));
	
		//, 'result_permission' => 'Y'
        // , 'exam_form_permission' => 'Y'
		//, 'old_exam_form_permission' => 'Y' 
        ?>
		<tr>
			<th><?=$i++?></th>
			<th colspan="7"><?=$course['course_name']?></th>
          
		</tr>
        <?php foreach($classes as $class) { 
        	$course_id = $this->Common_model->encrypt_decrypt($course['id']);
			$class_id = $this->Common_model->encrypt_decrypt($class->id);
           
        	?>
        <tr>
        <td></td>
		<?php 
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
		
		$cbcs = ($class->cbcs == 'Y' || in_array($class->id, $class_ids))?' (CBCS)':'';
        ?>
			<td><?= $class->class_name.$cbcs ?></td>
			<td><?php 
			$flag=($class->regular_class == 'Y' && $class->private_class == 'Y')? '/': '';
			$notification =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Notification ': '';
			$marksheet =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Marksheet ': '';
			$result_permission =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Result permission ': '';
			
			if($class->regular_class=='Y') { 
				
				?> 
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/REG/'.$course['id'].'/'.$class->id)  ?>">Result permission Regular </a>
				<?php } if($class->private_class=='Y') {  echo $flag; ?>
					<a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/PVT/'.$course['id'].'/'.$class->id)  ?>"><?= $result_permission?>Private</a>
					<?php }  ?>
					</td>
			<td>
				<?php
				
				if ($class->practical_internal_marks=='Y' && $class->id !=205 && $class->id !=206 && $class->id !=239){ 
					 
					 
					 if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/generate_tr_bed")."/REG/M/".$course['id']."/".$class->id; ?>">Regular Tr</a>
					 <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/generate_tr_bed")."/REG/G/".$course['id']."/".$class->id; ?>">Grade Tr</a>
					 <?php } 
					 } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/generate_tr_bed")."/PVT/M/".$course['id']."/".$class->id; ?>">Private Tr</a>
					  <?php }  
				

					 }else{ 
				
				if($class->regular_class=='Y') { 
                        if($class->id == 239){
                        ?>
                        <a href="<?php echo base_url("admin/admins/generate_tr_bed")."/REG/M/".$course['id']."/".$class->id; ?>">Regular Tr</a>
                        <?php
                        }else{
                            ?>
                            <a href="<?php echo base_url("admin/admins/generate_tr")."/REG/M/".$course['id']."/".$class->id; ?>">Regular Tr</a>
                            <?php
                        }
                    
                    ?>  
                      
					
					 <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/generate_tr")."/REG/G/".$course['id']."/".$class->id; ?>"> Grade Tr</a>
					 <?php } 
					 } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/generate_tr")."/PVT/M/".$course['id']."/".$class->id; ?>">Private Tr</a>
					  <?php }  
				 } ?>
			</td>
			<td>
				<?php if ($class->practical_internal_marks=='Y' && $class->id !=205 && $class->id !=206){ 
					if($class->regular_class=='Y') {?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list_bed/'."/REG/M/".$course_id.'/'.$class_id)  ?>">Notification Regular</a>
                <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/student_notification_list_bed")."/REG/G/".$course_id."/".$class_id; ?>">Grade</a>
					 <?php } 
					 }if($class->private_class=='Y') { echo $flag;  ?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list_bed/'."/PVT/M/".$course_id.'/'.$class_id)  ?>"><?= $notification?>Private</a>
				<?php
					} 
				}else{
					if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/student_notification_list")."/REG/M/".$course_id."/".$class_id; ?>">Notification Regular</a>
                     <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/student_notification_list")."/REG/G/".$course_id."/".$class_id; ?>"> Grade</a>
					 <?php } 
					  } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/student_notification_list")."/PVT/M/".$course_id."/".$class_id; ?>"><?= $notification?>Private</a>
					  <?php }  
				
					 }?>
			</td>
			<td>
				<?php
			if($class->regular_class=='Y') { 
				$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135);
				// $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
                $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302);
				if(in_array($class->id , $class_ids) || in_array($class->id , $class_cbcs)){
					$std_marksheet = 'student_marksheet_grade';
				}else{
					$std_marksheet = 'student_marksheet';
				}
				?>   
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_marksheet'."/REG/".$course['id'].'/'.$class->id)  ?>">Marksheet Regular</a>
                <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/student_marksheet_grade")."/REG/".$course['id']."/".$class->id; ?>"> Grade</a>
					 <?php } 
				 } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/student_marksheet")."/PVT/".$course['id']."/".$class->id; ?>"><?= $marksheet?>Private</a>
					  <?php }  ?>
			</td>
			<td><a target="_blank" href="<?php echo  base_url('admin/admins/withheld_student_list/'.$course['id'].'/'.$class->id)  ?>">Withheld Result (WH)</a></td>
            <td><a target="_blank" href="<?php echo  base_url('admin/admins/student_previous_fail/REG/'.$course_id.'/'.$class_id)  ?>">Previous Fail</a></td>
		</tr>
        <?php } ; }  ?>
	</tbody>
</table>
