<style>
/* .table th {

font-weight: bold;
vertical-align:middle;

} */
</style>
<table id="" class="table table-striped dt-responsive" width="100%" >
	<thead>
		<tr >
			<th rowspan="2">Sno.</th>
			<th rowspan="2">Exam Center Code</th>
			<th rowspan="2">Exam Center Name</th>
			<th rowspan="2">City</th>
			<th colspan="3" style="text-align: center;">Main</th>
			<th colspan="3" style="text-align: center;">Backlog</th>
			
			
		</tr>
		<tr>
			
			<th>Total Exam Form</th>
			<th>Fill Exam Form</th>
			<th>Remaining Exam Form</th>
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
			$backlog_total_count = $this->Common_model->getcountbywhere('backlog_student',array('exam_center_id'=>$center->id,'exam_form != '=>'D'));
			$backlog_fill_count = $this->Common_model->getcountbywhere('backlog_student',array('exam_center_id'=>$center->id,'exam_form'=>'Y'));
			
			
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
				<td><?php echo $backlog_total_count ;?></td>
				<td>
			    <a target="_blank" href="<?php echo base_url().'exam_center_wise_backlog_student_list/'.$this->Common_model->encrypt_decrypt($center->id,'encrypt') .'/'.$this->Common_model->encrypt_decrypt($param,'encrypt') ;?>"><?php echo $backlog_fill_count ;?></a></td>
				<td><a target="_blank" href="<?php echo base_url().'exam_center_wise_backlog_student_list/'.$this->Common_model->encrypt_decrypt($center->id,'encrypt');?>"><?php echo ($backlog_total_count-$backlog_fill_count);?></a></td>	
			</tr>
			<?php 
		}
		?>
	</tbody>
</table>