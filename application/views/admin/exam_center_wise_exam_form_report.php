<table id="" class="table table-striped dt-responsive" width="100%" >
	<thead>
		<tr>
			<th>Sno.</th>
			<th>Exam Center Code</th>
			<th>Exam Center Name</th>
			<th>City</th>
			<th>Total Exam Form</th>
			<th>Fill Exam Form</th>
			<th>Remaining Exam Form</th>
			
		</tr>
	</thead>
	<tbody>      
		<?php
		$i=1;
		$param  =  'fill';
		foreach ($exam_centers as $center) {
			$total_count = $this->Common_model->getcountbywhere('student',array('exam_center_id'=>$center->id,'new_exam_form != '=>'D'));
			$fill_count = $this->Common_model->getcountbywhere('student',array('exam_center_id'=>$center->id,'new_exam_form'=>'Y'));
			?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $center->examcentercode; ?></td>
				<td><?php echo $center->schoolcollegename; ?></td>
				<td><?php echo $center->city; ?></td>
				<td><?php echo $total_count ;?></td>
				<td>
			    <a target="_blank" href="<?php echo base_url().'exam_center_wise_student_list/'.$this->Common_model->encrypt_decrypt($center->id,'encrypt') .'/'.$this->Common_model->encrypt_decrypt($param,'encrypt') ;?>"><?php echo $fill_count ;?></a></td>
				<td><a target="_blank" href="<?php echo base_url().'exam_center_wise_student_list/'.$this->Common_model->encrypt_decrypt($center->id,'encrypt');?>"><?php echo ($total_count-$fill_count);?></a></td>	
			</tr>
			<?php 
		}
		?>
	</tbody>
</table>