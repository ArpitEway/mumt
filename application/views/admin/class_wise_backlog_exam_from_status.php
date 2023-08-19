<div class="dt-responsive">
	<table class="table table-striped" id="kt_datatable">
		<thead>
			<tr>
				<th>Sno</th>
				<th>Course Name</th>
				<th>Class ID</th>
				<th>Class Name</th>
				<th>Total Exam Form</th>
				<th>Fill Exam Form</th>
				<th>Regular Fill Form</th>
				<th>Regular Paper</th>
				<th>Private Fill Form</th>
				<th>Private Paper</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			foreach($counts as $count){ ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $this->Common_model->getCourseNameByCourseId($count['course_group_id']);?></td>
					<td><?php echo $count['class_id']; ?></td>
					<td><?php echo $this->Common_model->getClassNameByClassId($count['class_id']); ?></td>
					<td><?php echo $count['cnt']; ?></td>
					<?php 
					$where = array('exam_form'  =>'Y','class_id'=>$count['class_id'],'exam_year'=>'June 2023');
					$Permitted = $this->Common_model->getCountByWhere('backlog_student',$where);
					$where_reg = array('exam_form'  =>'Y','mode'  =>'REG','class_id'=>$count['class_id'],'exam_year'=>'June 2023');
					$Permitted_reg = $this->Common_model->getCountByWhere('backlog_student',$where_reg);
					$where_pvt = array('exam_form'  =>'Y','mode'  =>'PVT','class_id'=>$count['class_id'],'exam_year'=>'June 2023');
					$Permitted_pvt = $this->Common_model->getCountByWhere('backlog_student',$where_pvt);
					
					$this->db->select('count(*) num');
					$this->db->from('backlog_student s');
					$this->db->where(array('s.class_id' => $count['class_id'], 'exam_form' => 'Y','mode'  =>'PVT','paper_type'=> 'theory','ef.status'=> 'B','s.exam_year'=>'June 2023'));
					$this->db->join('backlog_exam_form ef','s.student_id=ef.student_id and s.class_id=ef.class_id');
					$pvt_paper_count = $this->db->get()->result()[0]->num;

					$this->db->select('count(*) num');
					$this->db->from('backlog_student s');
					$this->db->where(array('s.class_id' => $count['class_id'], 'exam_form' => 'Y','mode'  =>'REG','paper_type'=> 'theory','ef.status'=> 'B','s.exam_year'=>'June 2023'));
					$this->db->join('backlog_exam_form ef','s.student_id=ef.student_id and s.class_id=ef.class_id');
					
					$reg_paper_count = $this->db->get()->result()[0]->num;
				
					?>
					<td><?php echo $Permitted; ?></td>
					<td><?php echo $Permitted_reg; ?></td>
					<td><?php echo $reg_paper_count; ?></td>
					<td><?php echo $Permitted_pvt; ?></td>
					<td><?php echo $pvt_paper_count; ?></td>
				</tr>
			<?php
				$i++; 
			}
			?>
		</tbody>
	</table>
</div>
