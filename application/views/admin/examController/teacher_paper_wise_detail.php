
<div class=" mt-3">

<table id="kt_datatable" class="table  dt-responsive table-bordered "  style="width:100%;text-align:left;" >
		<thead>
			<tr>
				<th  width="1%">#</th>
				<th width="20%">Name </th>
				<th width="4%">Phone No. </th>
				<th width="20%">Course Name</th>
				<th width="5%">Class Name</th>
                <th width="5%">Paper Code</th>
                <th width="30%">Paper Name</th>
                <th width="5%">Answersheet Count</th>
                <th width="5%">Total </th>
               
				
			</tr>
		</thead>
		<tfoot>
			<tr>
            <th  width="1%">#</th>
				<th width="20%">Name </th>
				<th width="4%">Phone No. </th>
				<th width="20%">Course Name</th>
				<th width="5%">Class Name</th>
                <th width="5%">Paper Code</th>
                <th width="30%">Paper Name</th>
                <th width="5%">Answersheet Count</th>
                <th width="5%">Total </th>
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
       	
			foreach($teacher_list as $teacher){
             //   if($teacher['id']==99){
                $arr=array();
                $this->db->select(' count(*) as cnt,	course_group_id,class_id,paper_code  ');
                $this->db->from('upload_exam_ans_sheet');
                $this->db->where('teacher_id',$teacher['id']);
                //$this->db->where_in('course_group_id', $ug_array );
                $this->db->group_by('course_group_id');
                $this->db->group_by('class_id');
                $arr= $this->db->get()->result();
                 $rowCount =count($arr);
                 $tcount=0;
                 foreach($arr as $t){$tcount+= $t->cnt; }
             $p=0;
             foreach($arr as $paper){
                $course= $this->Common_model->getCourseNameByCourseId($paper->course_group_id);
                 $class= $this->Common_model->getClassNameByClassId($paper->class_id);
                 $paperName =$this->Common_model->get_record('paper_master','paper_name',array('paper_code ' => $paper->paper_code ,' course_group_id'=>$paper->course_group_id ,' class_id'=>$paper->class_id));
               //print_r($paperName);
              //echo  $this->db->last_query();
                ?>
					<tr>
                        <?php  if($p== 0) { ?>
                            <td  style="vertical-align: middle;" rowspan="<?= $rowCount; ?>"><?=  $i; ?></td>
                            <td style="vertical-align: middle;"rowspan="<?= $rowCount; ?>"><?= $teacher['name']; ?></td>
                            <td style="vertical-align: middle;" rowspan="<?= $rowCount; ?>"><?= $teacher['phone']; ?></td>
                        <?php } ?>
                            <td ><?= $course; ?></td>
                            <td ><?= $class; ?></td>
                            <td ><?= $paper->paper_code; ?></td>
                            <td><?= $paperName[0]['paper_name'] ?></td>
                            <td><?php echo $paper->cnt;  ?></td>
                        <?php  if($p== 0) { ?>    
                            <td style="vertical-align: middle;" rowspan="<?= $rowCount; ?>"><?= $tcount;  ?></td>
                        <?php } ?>
                      
                        
                        
						
						
					</tr>
				
                    <?php $p++; // } ?>
			<?php $i++; } }
			
			 ?>
			</tbody>
		    
	</table>

</div>

