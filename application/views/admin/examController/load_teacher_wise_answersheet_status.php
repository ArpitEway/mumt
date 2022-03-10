<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>
<div class=" mt-5">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>#</th>
						<th>Teacher</th>

						<th>Course</th>
						<th>Class</th>
						<th>Paper Name</th>
						<th>Total </th>
						<th>Available </th>
						<th>Checked </th>


                      
					
					</tr>
				</thead>
			<tbody>
    		<?php
    		$i = 1;
            foreach($teachers as $teacher){
              
            $paper_name = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$teacher->paper_code , 'class_id'=>$teacher->class_id));
            $teacher_name = $this->Common_model->getRecordByWhere('teacher',array('id'=>$teacher->teacher_id ));
           

            $this->db->select(' count(*) as cnt ,center_code,center_id');
            $this->db->from('new_exam_form');
            $this->db->join('student', 'new_exam_form.student_id = student.student_id');
            $this->db->where('new_exam_form.class_id',$teacher->class_id);
            $this->db->where('new_exam_form.paper_code',$teacher->paper_code); 
            $this->db->where('student.new_exam_form','Y');
            $this->db->where('student.center_id in ('.$teacher->center_id.')');

            $total_count= $this->db->get()->result();
     
            $course = $this->Common_model->getCourseNameByCourseId($teacher->course_group_id);
            $class = $this->Common_model->getClassNameByClassId($teacher->class_id);

            $this->db->select(' count(*) as cnt');
			$this->db->from('upload_exam_ans_sheet');
			$this->db->where('paper_code',$teacher->paper_code);
			$this->db->where('class_id',$teacher->class_id); 
			$this->db->where('center_id in ('.$teacher->center_id.')');
			$count_for_available= $this->db->get()->result();
            $count_for_checked =  $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array("paper_code"=>$teacher->paper_code,'class_id' =>$teacher->class_id,'teacher_id='=>$teacher->teacher_id));
    		?>
					<tr>
					<td><?php echo $i; ?></td>
                    <td><?php echo $teacher_name[0]->name; ?></td>

					<td><?php echo  $course; ?></td>
					<td><?php echo  $class; ?></td>
					<td><?php echo  $paper_name[0]->paper_name; ?></td>
					<td><?php echo  	$total_count[0]->cnt ; ?></td>
					<td><?php echo  	$count_for_available[0]->cnt ; ?></td>
					<td><?php echo  	$count_for_checked ; ?></td>


				

					
					



                   
</tr>
<?php $i++; } ?>

</tbody>
</table>

</div>
