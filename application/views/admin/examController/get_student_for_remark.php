<div id="">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
					<th>Enrollment No.</th>
					<th>Name</th>
					<th>Exam Status</th>
					<th>Course</th>
					<th>Class</th>
					<th>Remark</th>
				</tr>
			</thead>
    		<tbody id="" class="">
    		<?php 
			
    		$i = 1;
			
			foreach($students as $student){
			
			?>
			
			<tr>
				<td><?php echo $student->enrollment_no; ?></td>
                <td><?php echo $student->name; ?></td>
				<td><?php echo $student->exam_status; ?></td>
				<td><?php echo $student->course_name; ?></td>
				<td><?php echo $student->class_name; ?></td>
				<td>		
                	<div style="display: inline-flex;">
						<a href="javascript:void(0);" class="btn btn-sm btn-info" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/ExamController/student_remark_details/'.$student->student_id); ?>', '<?php echo 'Student Remark Details' ?>')">Remark</a>  						
                	</div>
                </td>
				
			</tr>
			
			
			<?php
			
			$i++;
			} 
			?>
			</tbody>
</table>
</div>