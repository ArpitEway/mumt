
<div class=" mt-3 dt-responsive">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped" >
		<thead>
			<tr>
				<th>#</th>
				<th>Course Name </th>
				<th>Class Name </th>
				<th>Test ID</th>
				<th>Paper Code</th>
				<th>Paper Wise Count</th>
				<th>Paper Name</th>		
                <th>ce</th>	
                <th>Total Students</th>		
				<th>Paper N/A</th>
				<th>Total Paper</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
            <th>#</th>
             <th>Course Name </th>
				<th>Class Name </th> 
				<th>Test ID</th>
				<th>Paper Code</th>
				<th>Paper Count/No.</th>
				<th>Paper Name</th>	
                		<th>ce</th>	
               			<th>Total Students</th>	
				<th>Paper N/A</th>
				<th>Total Paper</th>
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
      
			foreach($list as $row){
				if($multiple){
					$datas=$this->Common_model->getRecordByWhere('paper_master',array("test_id"=>$row->test_id));
					$allcounter=$counter=0;$paper_code_count=$course_name=$class_name=$paper_code=$paper_name=$ce="";
					foreach($datas as $data){
						$class=$this->Common_model->getRecordByWhere('class_master',array("id"=>$row->class_id));
						// $where= array(
						// 	'e.paper_code'=>$data->paper_code,
						// 	 //'s.pattern'=>'NEW' ,
						// 	 's.new_exam_form!='=>'D' ,
						// 	 's.class_id'=>$data->class_id,
						//  );
						//  $tag='count(*) as cnt';
						//  $table="new_exam_form  as e";
						//  $join_table='student as s';
						//  $join_on='e.student_id = s.student_id AND s.class_id = e.class_id';
						//  $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);

						$sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$data->paper_code."' AND `s`.`class_id` = '".$data->class_id."'  AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2021' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2022' AND `s`.`class_name` = 'I SEM' ));";    
					$query = $this->db->query($sql);
					$count = $query->result_array();
					 $qu="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$data->class_id."' AND `p`.`paper_code` = '".$data->paper_code."'  AND temp_exam_form='N' and `session` = 'July 2021'";
					$query = $this->db->query($qu);
					$all = $query->result_array();
					
						 $allcounter+=$all[0]['num'];	
						 $counter+=$count[0]['cnt'];
						 $course_name.=" <br>".$data->course_name;
						 $class_name=" <br>".$class[0]->class_name;
						 $paper_code.=" <br>".$data->paper_code;
						 $paper_code_count.=" <br>".$data->paper_code.' ( ALL - '.$all[0]['num'].')'.' ('.$count[0]['cnt'].')';
						
						 $paper_name=" <br>".$data->paper_name;
						 $ce.=" <br>".$data->ce;
						 
					}
				?>	<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $course_name; ?></td>
						<td><?php echo $class_name; ?></td>
						<td><?php echo $row->test_id; ?></td>
						<td><?php echo $paper_code; ?></td>
						<td><?php echo $paper_code_count; ?></td>
                        			<td><?php echo $paper_name; ?></td>
						<td><?php echo $ce; ?></td>
                	    			<td><?php echo $counter; ?></td>
						<td><?php echo $allcounter; ?></td>
						<td><?php echo $counter+$allcounter; ?></td>
					</tr>
				
			
			<?php  $i++; 

				}
				else{
					//$data=$row;
					$class=$this->Common_model->getRecordByWhere('class_master',array("id"=>$row->class_id));
                
// 					$where= array(
// 					   'e.paper_code'=>$row->paper_code,
// 						//'s.pattern'=>'NEW' ,
// 						's.new_exam_form!='=>'D' ,
// 						's.class_id'=>$row->class_id,
						
// 					);
// 					$tag='count(*) as cnt';
// 					$table="new_exam_form  as e";
// 					$join_table='student as s';
// 					$join_on='e.student_id = s.student_id AND s.class_id = e.class_id';
// 					$count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);
					
					// $this->db->select('count(*) as cnt');	
					// $this->db->from('new_exam_form  as e'); 
					// $this->db->join('student as s', 'e.student_id = s.student_id AND s.class_id = e.class_id');
					// $this->db->where('e.paper_code',$row->paper_code);
					// $this->db->where('s.class_id',$row->class_id);
					
					// $this->db->group_start();
					// $this->db->or_where('s.session=',"July 2021");
					// $this->db->where("s.class_name=","I Year");
					// $this->db->group_end();
					
					// $this->db->group_start();
					// $this->db->or_where('s.session=',"Jan 2022");
					// $this->db->where("s.class_name=","I SEM");
					// $this->db->group_end();
					// //$this->db->or_where('s.session=',"July 2021");
					// //$this->db->or_where('s.session=',"Jan 2022");
					// //$this->db->or_where('s.class_name=',"I Year");
					// $count = $this->db->get()->result_array();
					$sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."'  AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2021' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2022' AND `s`.`class_name` = 'I SEM' ));";    
					$query = $this->db->query($sql);
					$count = $query->result_array();
					$qu="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$row->class_id."' AND `p`.`paper_code` = '".$row->paper_code."'  AND temp_exam_form='N' and `session` = 'July 2021' and s.class_name='I Year'";
					$query = $this->db->query($qu);
					$all = $query->result_array();
				//	echo $this->db->last_query();
				//print_r($count);	die;
					
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $row->course_name; ?></td>
						<td><?php echo $class[0]->class_name; ?></td>
						<td><?php echo $row->test_id; ?></td>
						<td><?php echo $row->paper_code; ?></td>
						<td><?php echo $row->paper_no; ?></td>
                        			<td><?php echo $row->paper_name; ?></td>
						<td><?php echo $row->ce; ?></td>
                	    			<td><?php echo $count[0]['cnt'];  ?></td>
						<td><?php echo $all[0]['num']; ?></td>
						<td><?php echo  $count[0]['cnt']+$all[0]['num']; ?></td>
					</tr>
				
			
			<?php $i++; }
			
			 
				}
				
               
               
              
               //echo $this->db->last_query(); 
                //print_r( $count);
           ?>
			</tbody>
		    
	</table>

</div>
<script>
var showAllcourse = function () 
    {
        var url = '<?php echo site_url('admin/Admins/course'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                
            }
        });
    }
</script>
