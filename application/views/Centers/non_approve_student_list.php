<div class=" mt-5" >
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
					<th>Sno</th>
                   <th>Student ID</th>
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
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $document->student_id; ?> </td>
					<td><?php echo $document->name; ?> </td>
                    <td><?php echo $document->f_h_name; ?> </td>
					<td><?php echo $document->course_name; ?> </td>
					<td><?php echo $document->class_name; ?> </td>
                 
<td>
  <?php
  
$admissionDocCount = $this->Common_model->getCountByWhere('admission_document'," student_id = ".$document->student_id." and  status='N'");
if($admissionDocCount){
echo "Doument Uploaded"; 
		}
else { ?>
<a class="btn btn-primary" href="<?=base_url('center/center/madetoapproval/'.$document->student_id)?>">Upload Document</a>

	<?php
	}
	?></td>

<?php
$i++;
				} 
				?>
			</tbody>
		</table>
	</div>