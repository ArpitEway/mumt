<div class="card card-custom my-10" >
	<table  class="table table-striped dt-responsive" >
		<thead>
			<tr>
				<th>S.no</th>
				<th>Course</th>
				<th>Class</th>
				<!-- <th>Paper Code</th> -->
				<th>Paper Name</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$data = $this->Common_model->get_paper_count_list();
						$i=1;
					foreach($data as $dt){
						?>
						<tr>
							<td><?php echo $i++; ?></td>
							<td><?php echo $course_name = $this->Common_model->getCourseNameByCourseId( $dt['course_group_id']); ?></td>
							<td><?php echo $class_name = $this->Common_model->getClassNameByClassId( $dt['class_id']); 
							 ?> </td>
							<!-- <td><?php //echo $dt['paper_code']; ?></td> -->
							<td><?php echo $paper_name = $this->Common_model->getPaperNameById( $dt['paper_id']); ?> </td>
							<td class="d-flex justify-content-around">
								<a target="_blank" href="<?php echo base_url('admin/Dataentry/marks_entry_form/private/'.$dt['paper_id']); ?>" class="btn btn-outline-dark btn-sm" >Private</a>
								<a target="_blank" href="<?php echo base_url('admin/Dataentry/marks_entry_form/regular/'.$dt['paper_id']); ?>" class="btn btn-outline-dark btn-sm">Regular</a>
							</td>
						</tr>
						<?php  
					}
					?>
				</tbody>
			</table>
		</div>