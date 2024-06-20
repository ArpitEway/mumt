<div class="dt-responsive">
	<table id="kt_datatable" class="table table-striped" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Course</th>
				<th>Class</th>
				<!-- <th>Group Name</th> -->
				<th>Paper Code</th>
				<th>Paper Name</th>
				<th>Type</th>
				<!-- <th>CE</th> -->
				<!-- <th>Max Theory Marks</th>
				<th>Min Theory Marks</th>
				<th>Max Internal Marks</th>
				<th>Min Internal Marks</th> -->
               <th>Exam Date</th>
				<th>Exam Day</th>
				<th>Exam Time</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($papers as $paper){
				?>
				<tr>
					<td><?=$i?></td>
					<td><?=$this->Common_model->getCourseNameByCourseId($paper["course_group_id"])?></td>
					<td><?=$this->Common_model->getClassNameByClassId($paper["class_id"])?></td>
					<!-- <td>//$this->Common_model->getPaperNameById($paper["id"]);?></td> -->
					<td><?=$paper["paper_code"]?></td>
					<td><?=$paper["paper_name"]?></td>
					<td><?=$paper["type"]?></td>
					<!-- <td><?=$paper["ce"]?></td> -->
					<!-- <td><?=$paper["max_theory_marks"]?></td>
					<td><?=$paper["min_theory_marks"]?></td>
					<td><?=$paper["max_internal_marks"]?></td>
					<td><?=$paper["min_internal_marks"]?></td> -->
                     <td>
						<?php 
						if($paper["exam_date"] != "0000-00-00" ){
							echo	$this->Common_model->viewDate($paper["exam_date"]);
						 }
						?>
                    </td>
					<td><?=$paper["exam_day"]?></td>
					<td><?=$paper["exam_shift"]?></td> 		
				</tr>
			<?php $i++; }  ?>			
		</tbody>
	</table>
</div>