
<div class=" mt-3 dt-responsive">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped nowrap" >
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
                <th>Regular Total Students</th>	
				<th>Regular Backlog Students</th>		
				<th>Regular Paper N/A</th>
				<th>Regular Total Paper</th>
				<?php if($multiple){ 
					echo "<th>Private Paper Count/No.</th>";
				}?>
				<th>Private Total Students</th>	
				<th>Private Backlog Students</th>		
				<th>Private Paper N/A</th>
				<th>Private Total Paper</th>
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
               	<th>Regular Total Students</th>	
				<th>Regular Backlog Students</th>		
				<th>Regular Paper N/A</th>
				<th>Regular Total Paper</th>
				<?php if($multiple){ 
					echo "<th>Private Paper Count/No.</th>";
				}?>
				<th>Private Total Students</th>	
				<th>Private Backlog Students</th>		
				<th>Private Paper N/A</th>
				<th>Private Total Paper</th>

			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
      
			foreach($list as $row){
				if($multiple){
					$datas=$this->Common_model->getRecordByWhere('paper_master',array("test_id"=>$row->test_id));
					$allcounter_reg=$counter_reg=$back_counter_reg=$allcounter=$counter=$back_counter=0;$paper_code_count=$paper_code_count_reg=$course_name=$class_name=$paper_code=$paper_name=$ce="";
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

						/*********** Regular Start ****************/

						// $sql_reg="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$data->paper_code."' AND `s`.`class_id` = '".$data->class_id."' and university_mode='REG' AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2024' AND `s`.`class_name` = 'I Year' ));";    
						// // ( `s`.`session` = 'July 2023' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2024' AND `s`.`class_name` = 'I SEM' )
						// $query_reg = $this->db->query($sql_reg);
						// $count_reg = $query_reg->result_array();

						// $sql_back_reg="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` AND `s`.`id` = `e`.`backlog_student_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."' AND exam_year='June 2025'  AND exam_form!='D' AND `e`.`status`='B' and mode='REG'";    
						// $query_back_reg = $this->db->query($sql_back_reg);
						// $count_back_reg = $query_back_reg->result_array();
						
						// $qu_reg="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$data->class_id."' AND `p`.`paper_code` = '".$data->paper_code."'  AND temp_exam_form='N' and new_exam_form!='D' and university_mode='REG'";
						// $query_reg = $this->db->query($qu_reg);
						// $all_reg = $query_reg->result_array();

						// $allcounter_reg+=$all_reg[0]['num'];	
						// $counter_reg+=$count_reg[0]['cnt'];
						// $back_counter_reg += $count_back_reg[0]['cnt']; //0;
						// $paper_code_count_reg.=" <br>".'( ALL - '.$all_reg[0]['num'].')'.' ('.$count_reg[0]['cnt'].')';

						/*********** Regular End ****************/


						/*********** Private Start ****************/

						$sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$data->paper_code."' AND `s`.`class_id` = '".$data->class_id."' and university_mode='PVT' AND new_exam_form='Y' and  `examcentercode`='MDE028'";

						// $sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$data->paper_code."' AND `s`.`class_id` = '".$data->class_id."' and university_mode='PVT' AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2024' AND `s`.`class_name` = 'I Year' ) );";   
						//OR ( `s`.`session` = 'Jan 2024' AND `s`.`class_name` = 'I SEM' ) 
						$query = $this->db->query($sql);
						$count = $query->result_array();

						$sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` AND `s`.`id` = `e`.`backlog_student_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."' AND exam_year='June 2025'  AND exam_form='Y' AND `e`.`status`='B' and mode='PVT' and `exam_center_code`='MDE028'";  

						// $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` AND `s`.`id` = `e`.`backlog_student_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."' AND exam_year='June 2025'  AND exam_form!='D' AND `e`.`status`='B' and mode='PVT'";    

						$query_back = $this->db->query($sql_back);
						$count_back = $query_back->result_array();

						// $qu="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$data->class_id."' AND `p`.`paper_code` = '".$data->paper_code."'  AND temp_exam_form='N' and new_exam_form!='D' and university_mode='PVT'";

						$qu="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$data->class_id."' AND `p`.`paper_code` = '".$data->paper_code."'  AND temp_exam_form='N' and new_exam_form='Y' and university_mode='PVT' and `examcentercode`='MDE028'";

						//`session` = 'July 2022'";
						$query = $this->db->query($qu);
						$all = $query->result_array();

						$allcounter+=$all[0]['num'];	
						$counter+=$count[0]['cnt'];
						$back_counter += $count_back[0]['cnt']; //0;
						$paper_code_count.=" <br>".'( ALL - '.$all[0]['num'].')'.' ('.$count[0]['cnt'].')';

						/*********** Private End ****************/

						 $course_name.=" <br>".$data->course_name;
						 $class_name=" <br>".$class[0]->class_name;
						 $paper_code.=" <br>".$data->paper_code;
						 $paper_name=" <br>".$data->paper_name;
						 $ce.=" <br>".$data->ce;
						 
					}
				?>	<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $course_name; ?></td>
						<td><?php echo $class_name; ?></td>
						<td><?php echo $row->test_id; ?></td>
						<td><?php echo $paper_code; ?></td>
						<td><?php echo $paper_code_count_reg; ?></td>
                        <td><?php echo $paper_name; ?></td>
						<td><?php echo $ce; ?></td>
                	    <td><?php echo $counter_reg; ?></td>
						<td><?php echo $back_counter_reg; ?></td>
						<td><?php echo $allcounter_reg; ?></td>
						<td><?php echo $counter_reg+$allcounter_reg+$back_counter_reg; ?></td>
						<!-- *********** Regular End **************** -->

						<td><?php echo $paper_code_count; ?></td>
                	    <td><?php echo $counter; ?></td>
						<td><?php echo $back_counter; ?></td>
						<td><?php echo $allcounter; ?></td>
						<td><?php echo $counter+$allcounter+$back_counter; ?></td>

					</tr>
				
			
			<?php  $i++; 

				}
				else{
					//$data=$row;
					$class=$this->Common_model->getRecordByWhere('class_master',array("id"=>$row->class_id));
 
					/*********** Regular Start ****************/

					// $sql_reg="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."' and university_mode='REG'  AND (new_exam_form!='D' OR  ( `s`.`session` = 'July 2024' AND `s`.`class_name` = 'I Year' ))";

					// // ( `s`.`session` = 'July 2023' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2024' AND `s`.`class_name` = 'I SEM' )    
					
					// $query_reg = $this->db->query($sql_reg);
					// $count_reg = $query_reg->result_array();

					//  $sql_back_reg="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` AND `s`.`id` = `e`.`backlog_student_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."'  AND exam_year='June 2025' AND exam_form!='D'  AND `e`.`status`='B' and mode='REG'";    
					//  $query_back_reg = $this->db->query($sql_back_reg);
					//  $count_backlog_reg = $query_back_reg->result_array();
				
					// $qun_reg="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$row->class_id."' AND `p`.`paper_code` = '".$row->paper_code."'  AND temp_exam_form='N'   and new_exam_form!='D' and university_mode='REG'";
					
					// $queryn_reg = $this->db->query($qun_reg);
					// $all_reg = $queryn_reg->result_array();
				
					/*********** Regular End ****************/


					/*********** Private Start ****************/
					// $sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."' and university_mode='PVT'  AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2024' AND `s`.`class_name` = 'I Year' ) );";    //OR ( `s`.`session` = 'Jan 2024' AND `s`.`class_name` = 'I SEM' )

					$sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."' and university_mode='PVT'  AND new_exam_form='Y' and `examcentercode`='MDE028' ";    
					$query = $this->db->query($sql);
					$count = $query->result_array();

					 // $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` AND `s`.`id` = `e`.`backlog_student_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."'  AND exam_year='June 2025' AND exam_form!='D'  AND `e`.`status`='B' and mode='PVT'";    

					 $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND `s`.`class_id` = `e`.`class_id` AND `s`.`id` = `e`.`backlog_student_id` WHERE `e`.`paper_code` = '".$row->paper_code."' AND `s`.`class_id` = '".$row->class_id."'  AND exam_year='June 2025' AND exam_form='Y'  AND `e`.`status`='B' and mode='PVT' and `exam_center_code`='MDE028'";   

					 $query_back = $this->db->query($sql_back);
					 $count_backlog = $query_back->result_array();
					//I Year
					//$qu="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$row->class_id."' AND `p`.`paper_code` = '".$row->paper_code."'  AND temp_exam_form='N' and `session` = 'July 2022' and s.class_name='I Year'";
					//II Year

					// $qun="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$row->class_id."' AND `p`.`paper_code` = '".$row->paper_code."'  AND temp_exam_form='N'   and new_exam_form!='D' and university_mode='PVT'";
					//and `session` = 'July 2022' and s.class_name='II Year'

					$qun="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE s.class_id ='".$row->class_id."' AND `p`.`paper_code` = '".$row->paper_code."'  AND temp_exam_form='N'   and new_exam_form='Y' and university_mode='PVT' and `examcentercode`='MDE028'";

					$queryn = $this->db->query($qun);
					$all = $queryn->result_array();
				//	echo $this->db->last_query();
				//print_r($count);	die;
					/*********** Private End ****************/
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
                	    <td><?php echo $count_reg[0]['cnt'];  ?></td>
						<td><?php echo $count_backlog_reg[0]['cnt'];  ?></td>
						<td><?php echo $all_reg[0]['num']; ?></td>
						<td><?php echo  $count_reg[0]['cnt']+$all_reg[0]['num']+$count_backlog_reg[0]['cnt']; ?></td>
						<!-- *********** Regular End **************** -->
						<td><?php echo $count[0]['cnt'];  ?></td>
						<td><?php echo $count_backlog[0]['cnt'];  ?></td>
						<td><?php echo $all[0]['num']; ?></td>
						<td><?php echo  $count[0]['cnt']+$all[0]['num']+$count_backlog[0]['cnt']; ?></td>


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
