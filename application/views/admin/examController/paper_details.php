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
				<th>Paper</th>
				<th>Option</th>
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
					<td>
                 	<?php
					$url = './assets/model_paper/'.$paper['test_id'].'.pdf';
					if(file_exists($url)) { 
						?>
						<a href="<?php echo site_url($url);?>" download><img src="<?=base_url('assets/images/')?>pdf.png" width="30"></a>
						<?php } ?>
                	 </td>
             
					<td>
						<div style="display: inline-flex;">
							<a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/paper/edit/'.$paper['id']); ?>', '<?php echo 'Upload Modal Paper' ?>')"> <i class="mdi mdi-pencil edit-icon"></i></a>   

						</div>
                    </td>
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