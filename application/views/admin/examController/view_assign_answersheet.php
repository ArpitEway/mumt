<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>
<div class="text-right mt-3">
<?php if($this->session->account_type=='Admins'){ ?>
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/center/create/'.$center_code); ?>', 'Create center')"  >Create Center</a>
<?php } ?>
</div>
<div class=" mt-5" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>#</th>
                        <th>Action</th>
						<th>Teacher Name</th>
						<th>Course</th>
						<th>Class</th>
                        <th>College Name</th>
						<th>Paper Code</th>
						<th>Paper Name</th>
					
					</tr>
				</thead>
			<tbody>
    		<?php
    		$i = 1;
            foreach($teachers as $teacher){
            $course = $this->Common_model->getCourseNameByCourseId($teacher->course_group_id);
            $class = $this->Common_model->getClassNameByClassId($teacher->class_id);
            $where = array(
                'class_id'=> $teacher->class_id,
                'paper_code'=> $teacher->paper_code,
            );
            $paper= $this->Common_model->getRecordByWhere('paper_master',$where);
    		?>
					<tr>

					<td><?php echo $i; ?></td>
					<td><a target='_blank' href='<?php echo base_url('admin/ExamController/teacher_alloted_exam_center/').$this->Common_model->encrypt_decrypt($teacher->teacher_id,'encrypt'); ?>'>View Details</a></td>
					<td><?php echo $teacher->name ?> </td>	
					<td><?php echo  $course?> </td>	
					<td><?php echo $class ?> </td>	
                    <td><?php echo $teacher->clg_name ?> </td>	
					<td><?php echo $teacher->paper_code ?> </td>	
					<td><?php echo  $paper[0]->paper_name ?> </td>	
</tr>
				
<?php $i++; } ?>

</tbody>
</table>

</div>
