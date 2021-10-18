<div class="container mt-5" >


	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>Id</th>
				<th>Form No</th>
				<th>Name</th>
				<th>Email</th>
				<th>Mobile</th>
				<th>DOB</th>
				<th>Course</th>
				
				
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		
        foreach($user_enquiry as $enquiry){
			
		$courses = $this->db->get_where('course_group', array('id' => $enquiry['course_group_id']))->row_array();
		if(isset($courses['course_name'])){
		$course_name = $courses['course_name'];
		}
        ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $enquiry['student_id']; ?></td>
					<td><?php echo $enquiry['name']; ?></td>
					<td><?php echo $enquiry['email']; ?></td>
					<td><?php echo $enquiry['mobile_no']; ?></td>
					<td><?php echo date("d-m-Y", strtotime($enquiry['dob'])); ?></td>
					<td><?php echo $course_name; ?></td>		
				</tr>
		 <?php 
		 $i++;
		 } 
		 
		 ?>
		 </tbody>
	</table>

</div>
  
           
