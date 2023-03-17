
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
				<th>Ans Sheet</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>#</th>
				<th>Exam Center Code </th>
				<th>School/College Name </th>
				<th>City</th>
                <th>Max Student</th>
				<th>Ans Sheet</th>
				<th>Amount</th>
				
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
      
			
			foreach($examCenters as $examCenter){
               
                $this->db->select('count(*) as cnt');
                $this->db->from('student as s');
                $this->db->join('new_exam_form  as e', 'e.student_id = s.student_id AND s.class_id = e.class_id');
                $this->db->join('paper_master  as p', 's.class_id=p.class_id AND e.paper_code = p.paper_code');
                $this->db->where('s.examcentercode',$examCenter['examcentercode']);	
                $this->db->where('s.exam_center_id',$examCenter['id']);	
                $this->db->where('exam_date!=',"0000-00-00");	
                $this->db->where_not_in('paper_no_for_time_table', array('1B','2B'));
                $this->db->where_in('s.new_exam_form ',array('Y'));
                $this->db->group_by(array('exam_date','exam_shift'));
                $this->db->order_by('cnt', "desc");
                $this->db->limit(1);
                $count = $this->db->get()->result();
               // echo $this->db->last_query();
               
            ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $examCenter['examcentercode']; ?></td>
						<td><?php echo $examCenter['schoolcollegename']; ?></td>
						
						<td><?php echo $examCenter['city']; ?></td>
                        <td><?php  echo $count[0]->cnt; ?></td>
						<td><?php  echo $examCenter['study_center_id']; ?></td>
						
						<td><?php echo $examCenter['billing_amount']; ?></td>
						
					</tr>
				
			
			<?php $i++; }
			
			 ?>
			</tbody>
		    
	</table>

</div>

