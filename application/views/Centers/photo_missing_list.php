<div class="mt-5 dt-responsive " >
	<table id="kt_datatable" class="table table-striped" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Form no</th>
				<th>Session</th>
				<th>Name</th>
				<th>F/H Name</th>
				<th>Course</th>
				<th>Class</th>
				
				<th>Select Paper</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$i = 1;
			foreach($students as $student){
				// $imgurl=base_url('assets/student_image/'.$student->session.'/'.$student->photo); 
				//if($student->photo==""){
					//$img_url = (file_exists(FCPATH.'assets/student_image/'.$student->session.'/'.$student->photo)) ? base_url('assets/student_image/'.$student->session.'/'.$student->photo) : ''; 
				if (!file_exists(FCPATH.'assets/student_image/'.$student->session.'/'.$student->photo) || $student->photo=="") {  
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $student->student_id; ?> </td>
					<td><?php echo $student->session; ?> </td>
					<td><?php echo $student->name; ?> </td>
					<td><?php echo $student->f_h_name; ?> </td>
					<td><?php echo $student->course_name; ?> </td>

					<td><?php echo $student->class_name; ?> </td>
					
					<td>
						<?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id); ?>
						<!-- <button class="btn btn-primary" >Select Photo</button> -->
						<a href="javascript:void(0);" style="margin:5px;" class="btn btn-success" id="<?php echo  $std  ?>"   onclick="rightModal('<?php echo site_url('admin/modal/student_popup/admin/student/update/update_photo/'.$student_id); ?>', '<?php echo 'Select Photo' ?>')">Upload</a>
					</td>
				</tr>
				<?php
				$i++; 
				}
			} 
			?>
		</tbody>
	</table>
</div>