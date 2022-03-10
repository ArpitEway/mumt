<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>
<div class="dt-responsive">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="kt_datatable" class="table table-striped " >
				<thead>
					<tr>
						<th>#</th>
                        <th>Course</th>
						<th>Class</th>
						<th>Paper</th>
						<th>Total</th>
						<th>Allot</th>
						<th>Not Assign</th>
						<th>Available</th>
						<th>Checked</th>
					</tr>
				</thead>
			<tbody>
    		<?php
    		$i = 1;
            foreach($papers as $paper){
            $class = $this->Common_model->getClassNameByClassId($paper->class_id);
            // total count
            $this->db->select(' count(*) as cnt ,center_code,center_id');
            $this->db->from('new_exam_form');
            $this->db->join('student', 'new_exam_form.student_id = student.student_id');
            $this->db->where('new_exam_form.class_id',$paper->class_id);
            $this->db->where('new_exam_form.paper_id',$paper->id); 
             $this->db->where('student.new_exam_form','Y');
            $total_count= $this->db->get()->result();

          
            $this->db->select('GROUP_CONCAT(center_id) as center_id');
            $this->db->from('assign_answersheet');
            $this->db->where('class_id',$paper->class_id);
            $this->db->where('paper_code',$paper->paper_code); 
            $center_id= $this->db->get()->result();
   
        $center_ids = $center_id[0]->center_id;

            if($center_ids !=""){
        // count for allot
        $this->db->select(' count(*) as cnt');
        $this->db->from('new_exam_form');
        $this->db->join('student', 'new_exam_form.student_id = student.student_id');
        $this->db->where('new_exam_form.class_id',$paper->class_id);
        $this->db->where('new_exam_form.paper_code',$paper->paper_code);
        $this->db->where('student.center_id in ('.$center_ids.')');
        $this->db->where('student.new_exam_form','Y'); 
        $allot= $this->db->get()->result();

       $allot_cnt = $allot[0]->cnt ;
            }else{
                $allot_cnt = 0;
            }
       
        $where = array(
            'paper_code' =>$paper->paper_code,
            'class_id' =>$paper->class_id,
            'file_exist' => 'Y',
        );
        $count_for_available = $this->Common_model->getCountByWhere('upload_exam_ans_sheet',$where);
        $count_for_checked =  $this->Common_model->getCountByWhere('upload_exam_ans_sheet',array("paper_code"=>$paper->paper_code,'class_id' =>$paper->class_id,'teacher_id!='=>''));
    		?>
					<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $paper->course_name ?> </td>	
					<td><?php echo  $class?> </td>	
					<td>(<?php echo  $paper->paper_code ?>)  <?php echo  $paper->paper_name ?> </td>	
					<td><?php  echo  $total_count[0]->cnt ;?> </td>	
					<td><?php echo $allot_cnt ; ?> </td>	
					<td><?php echo  $total_count[0]->cnt - $allot_cnt  ;?> </td>	
					<td><?php echo   $count_for_available ;?> </td>	
					<td><?php echo $count_for_checked ;?> </td>	                 
</tr>
<?php $i++; } ?>

</tbody>
</table>

</div>
