<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
<thead>
		<tr>
				<?php 
				if(isset($course_count)){ ?>
				
					<th>Sno</th>
					<th>Course</th>
					<th>Class</th>
					<th>Count</th>
					
				<?php } ?>
					
		</tr>
</thead>
<tbody>
    	
			<?php	 $i=1;
			if(isset($course_count)){ ?>
			
			<?php
			$total = 0;
			foreach($course_count as $student){	
			?>
			<tr>
				
			<td><?php echo  $i++; ?></td>
			<td><?php echo $student["course_name"]; ?></td>
			<td><?php echo $student["class_name"]; ?></td>
			<td><a target="_blank" href="<?php echo site_url('get_student_list_for_session_change_report/').$session.'/'.$payment.'/'.$document_upload.'/'.$student["course_group_id"].'/'.$student["class_id"].'/'.$approved; ?>"  ><?php echo $student["cnt"]; ?></a></td>
			<?php $total = $total + $student["cnt"]; ?>
			</tr>
			
			
			
			<?php } ?>
			<tfoot>
			<tr>
			<td></td>
			<td></td>
			<td><?php echo "Total"; ?></td>

			<td><?php echo $total ?></td>
			</tr>
			<tfoot>
			<?php } ?>
</tbody>
</table>