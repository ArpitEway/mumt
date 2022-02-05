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
			foreach($documents as $document){

	       $remark = $document->remark;
			$admissionDocWhere = " student_id = ".$document->student_id." and document_category_id in  (".$remark.") and status='N'";
			$admissionDocCount = $this->Common_model->getCountByWhere('admission_document',$admissionDocWhere);
			$remarkCount= substr_count($remark,',');
			
			$remarkCount+=1;
     
        if($admissionDocCount!=$remarkCount){
 
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $document->student_id; ?> </td>
					<td><?php echo $document->name; ?> </td>
                    <td><?php echo $document->f_h_name; ?> </td>
					<td><?php echo $document->course_name; ?> </td>
					<td><?php echo $document->class_name; ?> </td>
                 
       <td>
       	<?php $student_id = $this->Common_model->encrypt_decrypt($document->student_id); ?>
      <a class="btn btn-primary" href="<?=base_url('center/center/remaining_documents/'.$student_id)?>">Upload Document</a>
	</td>

<?php
$i++;
		}} 
	?>
        </tbody>
       </table>
        </div>