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
					<td><?php echo $count['course_name'];?></td>
					<td><?php echo $count['class_id']; ?></td>
					<td><?php echo $count['class_name']; ?></td>
					<td><?php echo $count['cnt']; ?></td>
					<?php 
					$where = array('new_exam_form'  =>'Y','class_id'=>$count['class_id']);
					$Permitted = $this->Common_model->getCountByWhere('student',$where);
					$where_reg = array('new_exam_form!='  =>'D','university_mode'  =>'REG','class_id'=>$count['class_id']);
					$Permitted_reg = $this->Common_model->getCountByWhere('student',$where_reg);

					$where_pvt = array('new_exam_form!='  =>'D','university_mode'  =>'PVT','class_id'=>$count['class_id']);
					$Permitted_pvt = $this->Common_model->getCountByWhere('student',$where_pvt);
					
					$this->db->select('count(*) num');
					$this->db->from('student s');
					$this->db->where(array('s.class_id' => $count['class_id'], 'new_exam_form!=' => 'D','university_mode'  =>'PVT','paper_type'=> 'theory'));
					$this->db->join('new_exam_form ef','s.student_id=ef.student_id and s.class_id=ef.class_id');
					$pvt_paper_count = $this->db->get()->result()[0]->num;

					$this->db->select('count(*) num');
					$this->db->from('student s');
					$this->db->where(array('s.class_id' => $count['class_id'], 'new_exam_form!=' => 'D','university_mode'  =>'REG','paper_type'=> 'theory'));
					$this->db->join('new_exam_form ef','s.student_id=ef.student_id and s.class_id=ef.class_id');
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
