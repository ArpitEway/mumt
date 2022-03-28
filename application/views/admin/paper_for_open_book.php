<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>

<div class=" mt-5" >
			<table id="" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>#</th>
						<th>Course Name</th>
						<th>Class Name</th>
						<th>Paper No</th>
						<th>Paper Name</th>
						<th>Paper Code</th>
						<th>Type</th>
						<th>CE</th>
						<th>Test ID</th>
						<th>Exam Date</th>
						<th>Max Theory Marks</th>
						<th>View Paper</th>
					</tr>
				</thead>
			<tbody>
    		<?php
    		$i = 1;
            foreach($classes as $class){
			$exam_date = $this->Common_model->getRecordByWhere('time_table',array('class_id' => $class->class_id));
    		?>
					<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $class->course_name; ?> </td>	
					<td><?php echo $class->class_name; ?> </td>
					<td><?php echo $class->paper_no; ?> </td>
					<td><?php echo $class->paper_name; ?> </td>
					<td><?php echo $class->paper_code; ?> </td>
					<td><?php echo $class->type; ?> </td>
					<td><?php echo $class->ce; ?> </td>
					<td><?php echo $class->test_id; ?> </td>
                    <td><?php echo $exam_date[0]->exam_start_date; ?> </td>
                    <td><?php echo $exam_date[0]->max_marks; ?> </td>
                    <td>
<?php if(file_exists(FCPATH.'exam_pdf/'.$class->test_id.'.pdf')){ ?>
                     <a target="_blank" href="<?php  echo  base_url('exam_pdf/'.$class->test_id.'.pdf') ?>" >View</a>
<?php }else{
	echo 'N/A';
} ?>
                 </td>
</tr>
				
<?php $i++; } ?>

</tbody>
</table>

</div>
