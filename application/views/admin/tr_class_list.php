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
			
      	$this->db->where_in('id', array(162,174,165,170,186,187,188,268));
        $classes= $this->Common_model->getRecordByWhere('class_master',array("course_group_id"=>$course['id'] , 'exam_form_permission' => 'Y' ));
	
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
		$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136,267,268,325);
		
		$cbcs = ($class->cbcs == 'Y' || in_array($class->id, $class_ids))?' (CBCS)':'';
        ?>
			<td><?= $class->class_name.$cbcs ?></td>
			<td><?php 
			$flag=($class->regular_class == 'Y' && $class->private_class == 'Y')? '/': '';
			$notification =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Notification ': '';
			$marksheet =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Marksheet ': '';
			$result_permission =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Result permission ': '';
			$previous_fail =($class->regular_class == 'N' && $class->private_class == 'Y')? 'Previous Fail ': '';
			if($class->regular_class=='Y') { 
				
				?> 
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/REG/'.$course['id'].'/'.$class->id)  ?>">Result permission Regular </a>
				<?php } if($class->private_class=='Y') {  echo $flag; ?>
					<a target="_blank" href="<?php echo  base_url('admin/admins/student_result_permission/PVT/'.$course['id'].'/'.$class->id)  ?>"><?= $result_permission?>Private</a>
					<?php }  ?>
					</td>
			<td>
				<?php
				
				// if ($class->practical_internal_marks=='Y' && $class->id !=205 && $class->id !=206 && $class->id !=239 && $class->id !=278&& $class->id !=282){ 
					 $class_pg= array(205,206,239,240,248,278,282,252,267,268);
                    if ($class->practical_internal_marks=='Y' && (!in_array($class->id, $class_pg))){ 
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
				     } 
                ?>
			</td>
			<td>
				<?php 
                 $class_pg= array(205,206,239,240,248,278,282,252);
                  if ($class->practical_internal_marks=='Y' && (!in_array($class->id, $class_pg))){ 
                // if ($class->practical_internal_marks=='Y' && $class->id !=205 && $class->id !=206){ 
					if($class->regular_class=='Y') {?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list_bed/'."/REG/M/".$course_id.'/'.$class_id)  ?>">Notification Regular</a>
                <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/student_notification_list_bed")."/REG/G/".$course_id."/".$class_id; ?>">Grade</a>
					 <?php 
                     if(in_array($class->id, $class_ids)){
                        ?>
                        / <a href="<?php echo base_url("admin/admins/student_notification_list_bed")."/REG/G/".$course_id."/".$class_id."/Department"; ?>"> Department</a>
                        <?php
                     }  
                    } 
					 }if($class->private_class=='Y') { echo $flag;  ?>
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_notification_list_bed/'."/PVT/M/".$course_id.'/'.$class_id)  ?>"><?= $notification?>Private</a>
				<?php
					} 
				}else{
					if($class->regular_class=='Y') { ?>    
					 <a href="<?php echo base_url("admin/admins/student_notification_list")."/REG/M/".$course_id."/".$class_id; ?>">Notification Regular</a>
                     <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/student_notification_list")."/REG/G/".$course_id."/".$class_id; ?>"> Grade</a>
                     
					 <?php 
                     if(in_array($class->id, $class_ids) && $class->last_class == 'L'){
                        ?>
                        / <a href="<?php echo base_url("admin/admins/student_notification_list")."/REG/G/".$course_id."/".$class_id."/Department"; ?>"> Department</a>
                        <?php
                     } 
                    } 
					  } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/student_notification_list")."/PVT/M/".$course_id."/".$class_id; ?>"><?= $notification?>Private</a>
					  <?php }  
				
					 }?>
			</td>
			<td>
				<?php
			if($class->regular_class=='Y') { 
				$class_ids=array(101,104,107,110,116,119,125,128,131,134,102,105,108,111,117,120,126,129,132,135,103,106,109,112,118,121,127,130,133,136);
				// $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280);
                $class_cbcs = array(193,194,197,198,201,202,203,204,205,206,211,212,213,214,221,222,223,224,225,226,227,228,275,276,279,280,217,231,235,237,239,245,215,247,249,251,253,277,281,209,302,303,304,305,210);
				if(in_array($class->id , $class_ids) || in_array($class->id , $class_cbcs)){
					$std_marksheet = 'student_marksheet_grade';
				}else{
					$std_marksheet = 'student_marksheet';
				}
				?>   
				<a target="_blank" href="<?php echo  base_url('admin/admins/student_marksheet'."/REG/".$course['id'].'/'.$class->id)  ?>">Marksheet Regular</a>
                <?php if(!empty($cbcs) ){ ?>
					 / <a href="<?php echo base_url("admin/admins/student_marksheet_grade")."/REG/".$course['id']."/".$class->id; ?>"> Grade</a>
                     <?php
                     if(in_array($class->id, $class_ids) && $class->last_class == 'L'){
                        ?>
                        / <a href="<?php echo base_url("admin/admins/student_marksheet_grade")."/REG/".$course['id']."/".$class->id."/Department"; ?>"> Department</a>
                        <?php
                     } 
					  } 
				 } if($class->private_class=='Y') { echo $flag; ?>
					 <a href="<?php echo base_url("admin/admins/student_marksheet")."/PVT/".$course['id']."/".$class->id; ?>"><?= $marksheet?>Private</a>
					  <?php }  ?>
			</td>
			<td><a target="_blank" href="<?php echo  base_url('admin/admins/withheld_student_list/'.$course['id'].'/'.$class->id)  ?>">Withheld Result (WH)</a></td>
            <td><a target="_blank" href="<?php echo  base_url('admin/admins/student_previous_fail/REG/'.$course_id.'/'.$class_id)  ?>">Previous Fail REG</a>
					<?php
					if($class->private_class=='Y') { echo $flag; ?>
						<a href="<?php echo base_url("admin/admins/student_previous_fail")."/PVT/".$course_id."/".$class_id; ?>"><?= $previous_fail?> Private</a>
						 <?php } 
					?>
		</td>
		</tr>
        <?php } ; }  ?>
	</tbody>
</table>
