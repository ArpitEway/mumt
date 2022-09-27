
<div class=" mt-3">

<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Name </th>
				<th>Phone No. </th>
				<th>Account No.</th>
				<th>Account Holder Name</th>
                <th>Bank Name</th>
                <th>IFSC Code</th>
                <th>UG Answersheet</th>
                <th>PG Answersheet</th>
                <th>Amount</th>
				
			</tr>
		</thead>
		<tfoot>
			<tr>
                <th>#</th>
				<th>Name </th>
				<th>Phone No. </th>
				<th>Account No.</th>
				<th>Account Holder Name</th>
                <th>Bank Name</th>
                <th>IFSC Code</th>
                <th>UG Answersheet</th>
                <th>PG Answersheet</th>
                <th>Amount</th>
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
        $ugwhere = "course_type in ('UG', 'Diploma','Certificate')";
        $ug_id = $this->Common_model->get_record('course_group','id',$ugwhere);
        
        $ug_array=array();$n=0;
        foreach($ug_id as $key=> $val){
             $ug_array[$n]=$val['id'];
            $n++;
        }
        $pgwhere = "course_type in ('PG', 'PGDiploma')";
        $pg_id = $this->Common_model->get_record('course_group','id',$pgwhere);
        
        $pg_array=array();$n=0;
        foreach($pg_id as $key=> $val){
             $pg_array[$n]=$val['id'];
            $n++;
        }
      
       
			
			foreach($teacher_list as $teacher){
                $ugcount==0;
                $this->db->select(' count(*) as cnt ');
                $this->db->from('upload_exam_ans_sheet');
                $this->db->where('teacher_id',$teacher['id']);
                $this->db->where_in('course_group_id', $ug_array );
                $ugcount= $this->db->get()->result();
                $this->db->select(' count(*) as cnt ');
                $this->db->from('upload_exam_ans_sheet');
                $this->db->where('teacher_id',$teacher['id']);
                $this->db->where_in('course_group_id', $pg_array );
                $pgcount= $this->db->get()->result();
                $amt=($ugcount[0]->cnt *10)+($pgcount[0]->cnt *15);
             // print_r($ugcount[0]->cnt);
            ?>
					<tr>
						<td><?php echo $i; ?></td>
                        <td><?= $teacher['name']; ?></td>
                        <td><?= $teacher['phone']; ?></td>
                        <td><?= $teacher['account_no']; ?></td>
                        <td><?= $teacher['account_name']; ?></td>
                        <td><?= $teacher['bank_name']; ?></td>
                        <td><?= $teacher['ifsc_code']; ?></td>
                        <td><?php echo $ugcount[0]->cnt ."*10"; ?></td>
                        <td><?php echo $pgcount[0]->cnt ."*15"; ?></td>
                        <td><?= $amt; ?></td>
                        
						
						
					</tr>
				
			
			<?php $i++; }
			
			 ?>
			</tbody>
		    
	</table>

</div>

