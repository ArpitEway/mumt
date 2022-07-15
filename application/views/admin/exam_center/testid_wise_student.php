
<div class=" mt-3">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>#</th>
				<th>Course Name </th>
				<th>Class Name </th>
				<th>Test ID</th>
				<th>Paper Code</th>
				<th>Paper No.</th>
				<th>Paper Name</th>		
                <th>ce</th>	
                <th>Total Students</th>		
			</tr>
		</thead>
		<tfoot>
			<tr>
            <th>#</th>
            <th>Course Name </th>
				<th>Class Name </th>
				<th>Test ID</th>
				<th>Paper Code</th>
				<th>Paper No.</th>
				<th>Paper Name</th>	
                <th>ce</th>	
                <th>Total Students</th>				
			</tr>
			</tfoot>
		<tbody>

		<?php
		$i = 1;
      
			foreach($list as $row){
                $class=$this->Common_model->getRecordByWhere('class_master',array("id"=>$row->class_id));
                $table1="new_exam_form";
                $table2="student";
                $joincondition="e.student_id=s.student_id";
                $join = array(
                    array('student as s', 'e.student_id = s.student_id'),
                   
                );
                $where= array(
                    array('e.paper_code'=>$row->paper_code),
                );
                
                //$count=$this->Common_model->getCountOnJoin("new_exam_form  as e",$join,$where);
                //  "select COUNT(id) as total from new_exam_form as e join student as s on e.student_id=s.student_id where class_id='".$row["class_id"]."' and paper_code='".$row["papercode"]."' and s.pattern='NEW' and s.new_exam_form!='D' and cls_id='".$row["class_id"]."'"; 
               //echo $this->db->last_query();
               // print_r( $class);
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
                	    <td><?php echo $row->ce; ?></td>
					</tr>
				
			
			<?php $i++; }
			
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
