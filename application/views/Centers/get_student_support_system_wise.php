<div id="formData">
	<table id="table" class="table" >
		<thead>
			<tr>
				<th>Session</th>
				<th>Form No</th>
				<th>Enrollment No</th>	
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
					<td><?php echo $student["session"]; ?></td>
					<td><?php echo $student["student_id"]; ?></td>
					<td><?php echo $student["enrollment_no"]; ?></td>
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
	<form method="POST" class="d-block  mt-15" enctype="multipart/form-data">
	<div class="form-group m-auto my-5 w-50">	
        <input type="hidden" value="3" name="complaint_department" id="complaint_department"/>
			<!-- <label for="Complaint" class=" font-weight-bold h5">Department</label> -->
			<!-- <select name="complaint_department" id="complaint_department" class="form-control" data-id='complaint_type'> -->
				<!-- <option value="N">Select Department</option> -->
                <!-- <option value="3" selected>Exam Control</option> -->
				<?php
				
                // $departments = $this->Common_model->getRecordByWhere('department_complaint',array('status !='=>'N'));
				// foreach($departments as $department){
				// 	?>
				<!-- // 	<option value="<?php //echo $department->id; ?>"><?php //echo $department->name; ?></option> -->
			 	<?php
				// } 
				// ?> 
			<!-- </select>     -->
		</div> 
    <div class="form-group mx-auto w-50 mt-5">	
			<!-- <label for="Complaint" class=" font-weight-bold h5">Complaint Type</label> -->
            <input type="hidden" name="complaint_type" id="Complaint" value="Exam Form Complaint"/>
			<!-- <select name="complaint_type" id="Complaint" class="form-control"> -->
            <!-- <option value="Exam Form Complaint" selected>Exam Form Complaint</option> -->
				<!-- <option value="N">Select Type</option> -->
				<?php
				// $supports = $this->Common_model->getRecordByWhere('support_system',array('status !='=>'N'));
				// foreach($supports as $support){
				// 	?>
				<!-- // 	<option value="<?php //echo $support->name; ?>"><?php //echo $support->name; ?></option> -->
				 	<?php
				// } 
				?> 
			<!-- </select>     -->
		</div> 
		
		<div class="form-group text-center">
			<label class="h3 font-weight-bold mt-5">Details</label>
			<div class="mt-5 m-auto">
				<textarea type="text" rows="4" name="detail" id="detail" class="form-control form-control-lg form-control-solid message_detail" ></textarea>
				<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
				<input type="hidden" name="student_id" id="student_id" value="<?=$student_id?>">
			</div>
		</div>
		<!-- <div class="from-group text-center">
		<label class="h5 mt-5">Attach File : </label>
		<input type="file" name="photo" id="photo" accept=".png, .jpg, .jpeg .pdf"/>
		
		</div> -->
		<div class="form-group col-md-12 text-center">
			<label for="class"></label>
			<button type="button" class="btn btn-lg btn-custom mt-4 col-sm-3" id="submit">Submit</button>
		</div>
	</form>
</div>
<script>
	$("#complaint_department").on('change', function(){
		var department = $(this).val();
		var nameAttr = $(this).attr('data-id');
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
    	var url = '<?= base_url("center/center/getComplaintType")?>';
	$.ajax({
		method: "POST",
		url: url,
		data: {department : department, nameAttr: nameAttr,[csrfName]:csrfHash},
	})
	.done(function( msg ) {
		console.log($("select[name='"+nameAttr+"']"));
         $("select[name='"+nameAttr+"']").removeAttr("disabled");
		$("select[name='"+nameAttr+"']").html(msg);
       
	});
	})
</script>