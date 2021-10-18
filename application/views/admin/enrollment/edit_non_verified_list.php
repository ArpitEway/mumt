<div class="container mt-5" >
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>S No</th>
				<th>Form No</th>
				<th>Course</th>
				<th>Class</th>
				<th>Name</th>
				<th>Mobile</th>
				<th>DOB</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		
        foreach($students as $student){
			
		$courses = $this->db->get_where('course_group', array('id' => $student['course_group_id']))->row_array();
		if(isset($courses['course_name'])){
		$course_name = $courses['course_name'];
		}
		$classes = $this->db->get_where('class_master', array('id' => $student['class_id']))->row_array();
		if(isset($classes['class_name'])){
		$class_name = $classes['class_name'];
		}
        ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $student['student_id']; ?></td>
				<td><?php echo $course_name; ?></td>
				<td><?php echo $class_name; ?></td>
				<td><?php echo $student['name']; ?></td>
				<td><?php echo $student['p_mobile_no']; ?></td>
				<td><?php echo date("d-m-Y", strtotime($student['dob'])); ?></td>
				
				<td>
                			
                				<div style="display: inline-flex;">
									<a href="<?=BASE_URL('admin/enrollment/show_form/').$student['student_id']?>" class="dropdown-item" > <i class="mdi mdi-eye view-icon"></i></a>
                					<a target="_blank" href="<?=BASE_URL('admin/enrollment/edit_student/').$student['student_id']?>" class="dropdown-item" > <i class="mdi mdi-pencil edit-icon"></i></a>
                			         
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