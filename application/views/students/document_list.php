<div class="container mt-5" >
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
					<th>Sno</th>
                    <th>Course</th>
                    <th>Class</th>
                    <th>Document</th>
				</tr>
			</thead>
				<tbody>
			<?php

			$i = 1;
			foreach($students as $student){
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $student->course_name; ?> </td>
					<td><?php echo $student->class_name; ?> </td>
					<td>
						<?php 
						if($student->document_uploaded == 'Y'){
							if($student->remark!='N'){
							$admissionDocWhere = " student_id = ".$student->student_id." and document_category_id in  (".$student->remark.") and status='N'";
							$admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
							$remarkCount= substr_count($student->remark,',');
							$remarkCount+=1;
							if($admissionDocCount!=$remarkCount){
								?><a class="btn btn-warning" href="<?=base_url('student/Document/remainingDocument/'.$student->student_id)?>">Remaining Document</a><?php
							}else{
								echo "Doument Uploaded"; 
							}
							}else{
								echo "Doument Uploaded"; 
							}							
						} else { ?>
							<a class="btn btn-success" href="<?php echo base_url("student/document/upload/".$student->student_id); ?>" > Upload Document</a>
							<?php
						}
						?>
					</td>
					<td></td>
					<?php
					$i++;
				} 
				?>
			</tbody>
		</table>
	</div>