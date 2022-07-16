
<div class=" mt-3">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<!-- <th>Course Name </th>
				<th>Class Name </th> -->
				<th>Test ID</th>
				<th>Paper Code</th>
				<!-- <th>Paper No.</th> -->
				<th>Paper Name</th>		
                <th>ce</th>	
                <th>Total Students</th>		
			</tr>
		</thead>
		<tfoot>
			<tr>
            <th>#</th>
            <!-- <th>Course Name </th>
				<th>Class Name </th> -->
				<th>Test ID</th>
				<th>Paper Code</th>
				<!-- <th>Paper No.</th> -->
				<th>Paper Name</th>	
                <th>ce</th>	
                <th>Total Students</th>				
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
      
			foreach($list as $row){
				if($multiple){
					$datas=$this->Common_model->getRecordByWhere('paper_master',array("test_id"=>$row->test_id));
					$counter=0;$course_name=$class_name=$paper_code=$paper_name=$ce="";
					foreach($datas as $data){
						$class=$this->Common_model->getRecordByWhere('class_master',array("id"=>$row->class_id));
						$where= array(
							'e.paper_code'=>$data->paper_code,
							 //'s.pattern'=>'NEW' ,
							 's.new_exam_form!='=>'D' ,
							 's.class_id'=>$data->class_id,
						 );
						 $tag='count(*) as cnt';
						 $table="new_exam_form  as e";
						 $join_table='student as s';
						 $join_on='e.student_id = s.student_id';
						 $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);
						 $counter+=$count[0]->cnt;
						 $course_name.=" <br>".$data->course_name;
						 $class_name.=" <br>".$class[0]->class_name;
						 $paper_code.=" <br>".$data->paper_code;
						 $paper_name.=" <br>".$data->paper_name;
						 $ce.=" <br>".$data->ce;
					}
				?>	<tr>
						<td><?php echo $i; ?></td>
						<!-- <td><?php echo $course_name; ?></td>
						<td><?php echo $class_name; ?></td> -->
						<td><?php echo $row->test_id; ?></td>
						<td><?php echo $paper_code; ?></td>
						<!-- <td><?php //echo $row->paper_no; ?></td> -->
                        <td><?php echo $paper_name; ?></td>
						<td><?php echo $ce; ?></td>
                	    <td><?php echo $counter; ?></td>
					</tr>
				
			
			<?php  $i++; 

				}
				else{
					//$data=$row;
					$class=$this->Common_model->getRecordByWhere('class_master',array("id"=>$row->class_id));
                
					$where= array(
					   'e.paper_code'=>$row->paper_code,
						//'s.pattern'=>'NEW' ,
						's.new_exam_form!='=>'D' ,
						's.class_id'=>$row->class_id,
					);
					$tag='count(*) as cnt';
					$table="new_exam_form  as e";
					$join_table='student as s';
					$join_on='e.student_id = s.student_id';
					$count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<!-- <td><?php echo $row->course_name; ?></td>
						<td><?php echo $class[0]->class_name; ?></td> -->
						<td><?php echo $row->test_id; ?></td>
						<td><?php echo $row->paper_code; ?></td>
						<!-- <td><?php //echo $row->paper_no; ?></td> -->
                        <td><?php echo $row->paper_name; ?></td>
						<td><?php echo $row->ce; ?></td>
                	    <td><?php echo $count[0]->cnt; ?></td>
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
