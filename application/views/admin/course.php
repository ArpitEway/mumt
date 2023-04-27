<div class="mt-5 text-right">
<a type="button"  class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/course/create'); ?>', 'Create course')" >Create course</a>
</div> 
<div class=" mt-3">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Course name </th>
				<th>Course code </th>
				<th>Mode</th>
				<th>Eligibility</th>
				<th>Form fees</th>
				<th>Admission fees</th>
				<th>Program fees</th>
				<th>Exam fees</th>
				<th>Private Program fees</th>
				<th>Private Exam fees</th>
				<th>Options</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>#</th>
				<th>Course name </th>
				<th>Course code </th>
				<th>Mode</th>
				<th>Eligibility</th>
				<th>Form fees</th>
				<th>Admission fees</th>
				<th>Program fees</th>
				<th>Exam fees</th>
				<th>Private Program fees</th>
				<th>Private Exam fees</th>
				<th>Options</th>
			</tr>
			</tfoot>
		<tbody>
		<?php
		$i = 1;
        $courses_groups = $this->db->get_where('course_group', array())->result_array();
        	foreach($courses_groups as $course_group)
			{
				$this->db->order_by('id','desc');
				$this->db->limit(1);	
			$courses = $this->db->get_where('course', array('course_group_id' => $course_group['id']))->result_array();
			foreach($courses as $course){
            ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $course_group['course_name']; ?></td>
						<td><?php echo $course['course_code']; ?></td>
						<td><?php echo $course_group['mode']; ?></td>
						<td><?php echo $course_group['eligibility']; ?></td>
						<td><?php echo $course['form_fees']; ?></td>
						<td><?php echo $course['admission_fees']; ?></td>
						<td><?php echo $course['program_fees']; ?></td>
						<td><?php echo $course['exam_fees']; ?></td>
						<td><?php echo $course['p_program_fees']; ?></td>
						<td><?php echo $course['p_exam_fees']; ?></td>
                	<td>
                	<div style="display: inline-flex;">
					
                		<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/course/edit/'.$course['id']); ?>', '<?php echo 'Update course' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   
                		<a href="javascript:void(0);" class="dropdown-item" onclick="confirmModal('<?php echo site_url('admin/Admins/course/delete/'.$course['id']); ?>', showAllcourse )"><i class="mdi mdi-delete delete-icon"></i></a>
                	</div>	

                    </td>
					</tr>
				
			
			<?php }
			$i++;
			} ?>
			</tbody>
		    
	</table>

</div>
<script>
var showAllcourse = function () 
    {
        var url = '<?php echo site_url('admin/Admins/course'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                
            }
        });
    }
</script>
