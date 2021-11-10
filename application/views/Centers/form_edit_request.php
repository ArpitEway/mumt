<style>
li{
	color:red;
}
</style>
<div class="card">

<div class="card-header row">
	<ul>
		<li> यह अनुरोध Enrollment जारी होने के पहले ही लागू रहेगा। </li>
		<li> एक बार Enrollment जारी होने के पश्चात् किसी भी फॉर्म में कोई भी संशोधन नहीं किया जा सकेगा। </li>
		<li> प्रत्येक Request में Detail सेक्शन में पूर्व में भरी गयी गलत जानकारी एवं वांछित सही जानकारी का विवरण स्पष्ट रूप से लिखे। </li>
		<li> एक विद्यार्थी के फॉर्म में जितने भी Modifications वांछित हैं, वे सभी एक ही Request में Submit करें। </li>
		<li> Details section में Google Translate का उपयोग कर हिंदी में भी जानकारी दी जा सकती है| </li>
	</ul>
</div>

<div class="card-body row text-center">

        <div class="form-group col-md-3 m-auto">

            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">

            <label for="center_id">Session</label>
            <select name="session" id="session" class="form-control" required >
                    <option value="">Select</option>
                    <?php 

                    $sessions = $this->db->get_where('session', array())->result_array();

                    foreach($sessions as $session)
                    {
                    ?>
                    
                    <option value="<?php echo $session['session']; ?>"><?php echo $session['session']; ?></option>
                    
                    <?php
                    } 
                    ?> 
                </select>       
        </div>
        
        <div class="form-group col-md-3 m-auto">
			<div class="form-group m-auto">
				<label>Select Course</label>
				<select class="form-control filter" name="course_group_id" id="allClassBycourse">
					<option value ="">Select</option>
				</select>
			</div>
		</div>

        <div class="form-group col-md-3 m-auto">
			<div class="form-group m-auto">
				<label>Select Student</label>
				<select class="form-control filter" name="student" id="student">
					<option value ="" >Select</option>
				</select>
			</div>
		</div>

        <div class="form-group col-md-6 m-auto" >
            <div class="form-group m-auto">
		      
              <textarea style="margin-top:30px;" class="form-control detail" placeholder="Enter detail" id="kt_autosize_2" rows="4" name="detail"></textarea>
		    
            </div>
        </div>

		<div class="form-group col-md-12">
			<label for="class"></label>
			<button type="button" class="btn btn-primary mt-4" style="margin-top: 24px !important;" id="submit_btn">Submit</button>
		</div>


	</div>
</div>


<div id="dt">

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

</div>

<script>

$(document).on("click","#submit_btn",function(){

	var student = $("#student").val();
    var detail = $(".detail").val();
	var course_id = $("#allClassBycourse").val();

	if(course_id != "" )
	{
		if(student != "")
		{
			if(detail != "")
			{
				var csrfName = $('.csrfname').attr('name');
				var csrfHash = $('.csrfname').val(); 

				var data = {
					student : $("#student").val(),
					detail : $(".detail").val(),
					[csrfName]:csrfHash
				};

				var url = BASE_URL + "center/center/get_request_detail"; 
				var response = call_ajax(data,url);
				console.log(response);
				$('#dt').html("");
				$('#dt').html(response.data);
				KTDatatablesBasicBasic.init();

			}else{

			toastr.error("Please Enter Required Fields");
			}	 
		}else{
			toastr.error("Please Enter Required Fields");
		}
	}else{
			toastr.error("Please Enter Required Fields");
		}	 	 
});

$("#allClassBycourse").on('change', function(){

	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var course_id = $(this).val();
	$.ajax({
		method: "POST",
		url: BASE_URL + "center/center/getStudent_By_Course",
		data: { 
			course_id : course_id,
			[csrfName]:csrfHash
		},
	})
	.done(function( msg ) {
        $('#student').html(msg);
		console.log(msg);
	});

});

</script>