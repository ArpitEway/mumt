<div class="text-center">
<table id="table" class="table table-striped dt-responsive nowrap" width="70%" >
			<thead>
				<tr>
				
				<th>S.No.</th>
				<th>Session</th>
                <th>Form no</th>	
				<th>Student Name</th>
				<th>Course </th>
				<th>Class</th>
					
				</tr>
			</thead>
    		<tbody>
    		<?php 
			
    		$i = 1;
			
			foreach($students as $student){
		
			?>
			
			<tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $student["session"]; ?></td>
				<td><?php echo $student["student_id"]; ?></td>
				<td><?php echo $student["name"]; ?></td>
                <td><?php echo $student["course_name"]; ?></td>
				<td><?php echo $student["class_name"]; ?></td>
				
			</tr>
			
			
		<?php
            $student_id = $student["student_id"];
	    $i++;
		} 

		?>
			</tbody>
</table>

<form method="POST" class="d-block ajaxForm">
    
		<div class="form-group row text-right">
			<label class="col-md-4">Details</label>
			<div class="col-md-6 ">
				<textarea type="text" name="detail" id="detail" class="form-control form-control-lg form-control-solid" ></textarea>
				<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
			</div>
		</div>
        <div class="form-group col-md-12 text-center">
            <label for="class"></label>
            <button type="button" class="btn btn-primary mt-4" style="margin-top: 24px !important;" id="submit">Submit</button>
        </div>
    

</form>


<div>

<div class="text-center">
<table id="table" class="table table-striped dt-responsive nowrap" width="70%" >
			<thead>
			<tr>
				
				<th>S.No.</th>
				<th>Student Name</th>
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
			
			foreach($center_details as $center){

			$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $center["student_id"]));
			
			?>
			
			<tr>

                <td><?php echo $i; ?></td>
				<td><?php echo $student->name; ?></td>
				<td><?php echo $center["student_id"]; ?></td>
                <td><?php echo $student->course_name; ?></td>
				<td><?php echo $student->class_name; ?></td>
				<td><?php echo $center["details"]; ?></td>
				<td><?php echo $center["date"]; ?></td>
				<td><?php
				if($center["status"] == "P"){
					echo "Pending";
				}else{
					echo "Done";
				} ?>
				</td>
				<td><?php echo $center["remark"]; ?></td>
				
			</tr>
			
			
		<?php
            	
	    		$i++;
		} 

		?>
			</tbody>
</table>


<script>

$("#submit").on('click',function (e)
{
    var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 

	detail = $('#detail').val(); 

	if(detail)
	{

			var frm = $('.ajaxForm').serialize();
			
			$.ajax({
			url: '<?php echo site_url('center/Center/payment_complaint/'.$student_id); ?>',
			type: 'POST',
			dataType : 'json',
			data: frm,
				success: function (data) 
				{

					console.log(data.msg);
					
						if(data.msg){
									
							toastr.success(data.msg);		
						}
						else if(data.err_msg){
									
							toastr.error(data.err_msg);
						}
						else{

							toastr.error("Something wrong");

						}
				},
			});	
			
	}else{

		toastr.error("Please Enter detail");

	}

});	


</script>