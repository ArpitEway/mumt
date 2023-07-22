<div class=" mt-5" >
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Form no</th>
				<th>Student name</th>
				<th>Father name</th>
				<th>Course Name</th>
				<th>Class Name</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach($students as $student){

				$remark = $student->remark;
				
				if ($student->remark!='') {

					$admissionDocWhere = " student_id = ".$student->student_id." and document_category_id in  (".$remark.") and status='N'";
					 $admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
					 $remarkCount= substr_count($remark,',');

					$remarkCount+=1;
					
				}else{
					$admissionDocCount =0;
				
				
				$admissionDocWhere = " student_id = ".$student->student_id."  and status='N'";
					 $admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
				  
				   $admissionDocWhereYes = " student_id = ".$student->student_id." and status='Y'";
					$admissionDocCountYes = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhereYes);
					 $doc_count = ( $admissionDocCount ==  $admissionDocWhereYes)?1:0;
					 $remarkCount =0;
				}
				
				

				if(($admissionDocCount!=$remarkCount && $student->remark!='') || ($remark=='' && $doc_count !=1)){

					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $student->student_id; ?> </td>
						<td><?php echo $student->name; ?> </td>
						<td><?php echo $student->f_h_name; ?> </td>
						<td><?php echo $student->course_name; ?> </td>
						<td><?php echo $student->class_name; ?> </td>

						<td>
							<?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id); ?>
							<a class="btn btn-primary" href="<?=base_url('center/center/remaining_documents/'.$student_id)?>">Upload Document</a>
						</td>
					</tr>
						<?php
						$i++;
					}} 
					?>
				</tbody>
			</table>
		</div>