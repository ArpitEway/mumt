<div class="text-center">

            <table id="table" class="table table-striped dt-responsive nowrap" width="70%" >
                <thead>
                    <tr>
                        
                        <th>S.No.</th>
                        <th>Student Name </th>
                        <th>Form no</th>
                        <th>Course </th>
                        <th>Class</th>
                        <th>Detail</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remark</th>
                
                    </tr>
                </thead>
    		<tbody>

    		<?php 
			
    		$i = 1;

			foreach($request_detail as $request){

			$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $request["student_id"]));
			
			?>
			
			<tr>

                <td><?php echo $i; ?></td>
				<td><?php echo $student->name; ?></td>
				<td><?php echo $request["student_id"]; ?></td>
                <td><?php echo $student->course_name; ?></td>
				<td><?php echo $student->class_name; ?></td> 
				<td><?php echo $request["detail"]; ?></td>
				<td><?php echo $request["date"]; ?></td>
				<td><?php echo $request["status"]; ?></td>
				<td><?php echo $request["remark"]; ?></td>

			</tr>
			
			
		<?php
            	
	    		$i++;
		} 

		?>
			</tbody>
</table>

</div>