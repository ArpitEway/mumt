<div class=" mt-3">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Exam Center Code </th>
				<th>School/College Name </th>
				<th>City</th>
				<th>Max Student</th>
				<?php  if($this->session->account_type !="ExamController"){ ?>
				<th>Ans Sheet</th>
				<th>Amount</th>
				<?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>#</th>
				<th>Exam Center Code </th>
				<th>School/College Name </th>
				<th>City</th>
				<th>Max Student </th>
				<?php  if($this->session->account_type !="ExamController"){ ?>
				<th>Ans Sheet</th>
				<th>Amount</th>
				<?php } ?>
				
			</tr>
		</tfoot>
		<tbody>

			<?php
			$i = 1;

			
			foreach($examCenters as $examCenter){
				// if($this->session->account_type =="ExamController"){ 
				// $this->db->select('count(*) as cnt');
				// $this->db->from('student as s');
				// $this->db->join('new_exam_form  as e', 'e.student_id = s.student_id AND s.class_id = e.class_id');
				// $this->db->join('paper_master  as p', 's.class_id=p.class_id AND e.paper_code = p.paper_code');
				// $this->db->where('s.examcentercode',$examCenter['examcentercode']);	
				// $this->db->where('s.exam_center_id',$examCenter['id']);	
				// $this->db->where('exam_date!=',"0000-00-00");	
				// // $this->db->where_not_in('s.class_id',array(264,268,270));
				// $this->db->where_not_in('paper_no_for_time_table', array('1B','2B'));
				// $this->db->where_in('s.new_exam_form ',array('Y'));
				// $this->db->group_by(array('exam_date','exam_shift'));
				// $this->db->order_by('cnt', "desc");
				// $this->db->limit(1);
				// $main_count = $this->db->get()->result();
				//REG
				$classIdsRegOnly = array(104, 107, 134);

				$this->db->select('count(*) as cnt');
				$this->db->from('student as s');
				$this->db->join('new_exam_form as e', 'e.student_id = s.student_id AND s.class_id = e.class_id');
				$this->db->join('paper_master as p', 's.class_id = p.class_id AND e.paper_code = p.paper_code');
				// Apply conditional student_type filter
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where_in('s.class_id', $classIdsRegOnly);
				$this->db->where('s.university_mode', 'REG');
				$this->db->group_end();
				$this->db->or_group_start();
				$this->db->where_not_in('s.class_id', $classIdsRegOnly);
				$this->db->where_in('s.university_mode', array('REG', 'PVT'));
				$this->db->group_end();
				$this->db->group_end();
				$this->db->where('s.examcentercode', $examCenter['examcentercode']);
				$this->db->where('s.exam_center_id', $examCenter['id']);
				$this->db->where('exam_date !=', "0000-00-00");
				$this->db->where_not_in('paper_no_for_time_table', array('1B','2B'));
				$this->db->where_in('s.new_exam_form', array('Y'));
				$this->db->group_by(array('exam_date', 'exam_shift'));
				$this->db->order_by('cnt', "desc");
				$this->db->limit(1);

				$main_count = $this->db->get()->result();



				// pvt 
				$this->db->select('count(*) as cnt');
				$this->db->from('student as s');
				$this->db->join('new_exam_form  as e', 'e.student_id = s.student_id AND s.class_id = e.class_id');
				$this->db->join('paper_master  as p', 's.class_id=p.class_id AND e.paper_code = p.paper_code');
				$this->db->where('s.examcentercode',$examCenter['examcentercode']);	
				$this->db->where('s.exam_center_id',$examCenter['id']);	
				$this->db->where('pvt_exam_date!=',"0000-00-00");	
				$this->db->where_in('s.class_id',array(104,107,134));
				$this->db->where('s.university_mode', 'PVT');
				$this->db->where_not_in('paper_no_for_time_table', array('1B','2B'));
				$this->db->where_in('s.new_exam_form ',array('Y'));
				$this->db->group_by(array('pvt_exam_date','pvt_exam_shift'));
				$this->db->order_by('cnt', "desc");
				$this->db->limit(1);
				$pvt_main_count = $this->db->get()->result();

				// $this->db->select('count(*) as cnt');
				// $this->db->from('backlog_student as s');
				// $this->db->join('backlog_exam_form  as e', 'e.student_id = s.student_id AND s.class_id = e.class_id and s.id=e.backlog_student_id');
				// $this->db->join('paper_master  as p', 's.class_id=p.class_id AND e.paper_code = p.paper_code');
				// $this->db->where('s.exam_center_code',$examCenter['examcentercode']);	
				// $this->db->where('s.exam_center_id',$examCenter['id']);	
				// $this->db->where('e.status','B');	
				// $this->db->where('exam_date!=',"0000-00-00");	
				// $this->db->where('exam_year' , 'June 2025');
				// // $this->db->where_not_in('s.class_id',array(264,268,270));
				// $this->db->where_not_in('paper_no_for_time_table', array('1B','2B'));
				// $this->db->where_in('s.exam_form ',array('Y'));
				// $this->db->group_by(array('exam_date','exam_shift'));
				// $this->db->order_by('cnt', "desc");
				// $this->db->limit(1);
				// $backlog_count = $this->db->get()->result();
				
				// backlog reg

				$classIdsRegOnly = array(104, 107, 134);

				$this->db->select('count(*) as cnt');
				$this->db->from('backlog_student as s');
				$this->db->join('backlog_exam_form as e', 'e.student_id = s.student_id AND s.class_id = e.class_id AND s.id = e.backlog_student_id');
				$this->db->join('paper_master as p', 's.class_id = p.class_id AND e.paper_code = p.paper_code');
				// Conditional student_type filter
				$this->db->group_start();
				$this->db->group_start();
				$this->db->where_in('s.class_id', $classIdsRegOnly);
				$this->db->where('s.mode', 'REG');
				$this->db->group_end();

				$this->db->or_group_start();
				$this->db->where_not_in('s.class_id', $classIdsRegOnly);
				$this->db->where_in('s.mode', array('REG', 'PVT'));
				$this->db->group_end();
				$this->db->group_end();

				$this->db->where('s.exam_center_code', $examCenter['examcentercode']);
				$this->db->where('s.exam_center_id', $examCenter['id']);
				$this->db->where('e.status', 'B');
				$this->db->where('exam_date !=', "0000-00-00");
				$this->db->where('exam_year', 'June 2025');
				$this->db->where_not_in('paper_no_for_time_table', array('1B', '2B'));
				$this->db->where_in('s.exam_form', array('Y'));
				$this->db->group_by(array('exam_date', 'exam_shift'));
				$this->db->order_by('cnt', "desc");
				$this->db->limit(1);

				$backlog_count = $this->db->get()->result();

				// backlog pvt

				$this->db->select('count(*) as cnt');
				$this->db->from('backlog_student as s');
				$this->db->join('backlog_exam_form  as e', 'e.student_id = s.student_id AND s.class_id = e.class_id and s.id=e.backlog_student_id');
				$this->db->join('paper_master  as p', 's.class_id=p.class_id AND e.paper_code = p.paper_code');
				$this->db->where('s.exam_center_code',$examCenter['examcentercode']);	
				$this->db->where('s.exam_center_id',$examCenter['id']);	
				$this->db->where('e.status','B');	
				$this->db->where('s.mode', 'PVT');
				$this->db->where('exam_date!=',"0000-00-00");	
				$this->db->where('exam_year' , 'June 2025');
				$this->db->where_in('s.class_id',array(104,107,134));
				$this->db->where_not_in('paper_no_for_time_table', array('1B','2B'));
				$this->db->where_in('s.exam_form ',array('Y'));
				$this->db->group_by(array('exam_date','exam_shift'));
				$this->db->order_by('cnt', "desc");
				$this->db->limit(1);
				$pvt_backlog_count = $this->db->get()->result();

				$tot_max_count=$main_count[0]->cnt+$backlog_count[0]->cnt+$pvt_main_count[0]->cnt+$pvt_backlog_count[0]->cnt;
				// }else{
				// 	$tot_max_count=0;
				// }
               // echo $this->db->last_query();
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $examCenter['examcentercode']; ?></td>
					<td><?php echo $examCenter['schoolcollegename']; ?></td>
					<td><?php echo $examCenter['city']; ?></td>
					<td><?php echo $tot_max_count; ?></td>
					<?php  if($this->session->account_type !="ExamController"){ ?>
						<td><?php echo $examCenter['study_center_id']; ?></td>
						<td><?php echo $examCenter['billing_amount']; ?></td>
					<?php } ?>
				</tr>
				<?php $i++; }
				?>
			</tbody>
		</table>
	</div>