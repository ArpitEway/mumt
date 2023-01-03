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
		
	</ul>
</div>

<div class="card-body row text-center">
	<div class="form-group col-md-3 m-auto">
		<input type="hidden"  name="mode" id="mode" value="<?=$course_type ?>">
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<input type="hidden"  class="course_type" value="<?= $course_type; ?>" >
		<label for="center_id">Session</label>
		<select name="session" id="session_course_type" class="form-control" required >
			<option value="">Select</option>
			<?php
			
			//$sessions = $this->Common_model->get_record('student','distinct(session)',array('center_id ' => $this->session->center_id));
			$sessions = $this->Common_model->get_record('session','distinct(session)',array('enrollment_permission ' => 'Y'));
			
			foreach($sessions as $session){
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
		<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#mode_alert-modal">Submit</button>
		<!-- <button type="button" class="btn btn-primary mt-4" id="submit_btn">Submit</button> -->
	</div>
</div>
</div>
<div class="dt-responsive mt-10">
	<table id="memListTable" class="table table-striped">
		<thead>
			<tr>
				<th>S.No.</th>
				<th>Student Name </th>
				<th>Form no</th>
				<th>Mode From</th>
				<th>Mode To</th>
				<th>Detail</th>
				<th>Date</th>
				<th>Status</th>
				<th>Remark</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>



<div id="mode_alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="text-center">
          <i class="dripicons-information h1 text-info"></i>
          <h4 class="mt-2"><?php echo 'Heads Up' ?>!</h4>
		  <?php $msg=""; if($course_type=="REG"){
			  $msg="Regular to Private";
		  } else if($course_type=="PVT"){
			$msg="Private to Regular";
		} 
		  ?>
          <p class="mt-3"><?php echo 'Are You Sure to change '.$msg.' admision mode'; ?>?</p>
          
            <button type="button" class="btn btn-info my-2" data-dismiss="modal" id="close_modal"><?php echo 'No'; ?></button>
            <button type="submit" class="btn btn-danger my-2" onclick="" id="submit_btn"><?php echo 'Yes'; ?></button>
         
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
$(document).ready(function(){
		
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var course_ty=$('.course_type').val();
	var myTable =  $('#memListTable').DataTable({
	// Processing indicator
	"processing": true,
	// DataTables server-side processing mode
	"serverSide": true,
	// Initial no order.
	"order": [0],
	// Load data from an Ajax source
	"ajax": {
		"url": BASE_URL+'center/center/getModeEditRequest',
		"type": "POST",
		"data": {[csrfName]:csrfHash,course_type:course_ty}
	},
	"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	//Set column definition initialisation properties
	"columnDefs": [{ 
		"targets": [0],
		"orderable": true
	}],
	dom: '<"row" <"col-md-4" l><"col-md-4 text-center" B> <"col-md-4 col-md-4" f>>rt<"bottom"ip>',
	buttons: [
	{
		"extend": "colvis",
		"text": "<i class='fa fa-search bigger-110 text-custom'></i>",
		"className": "btn-custom",
		columns: ':not(:first)'
	},
	{
		"extend": "copy",
		"text": "<i class='fa fa-copy bigger-110 text-custom'></i> Copy",
		"className": "btn-custom"
	},
	{
		"extend": "excel",
		"text": "<i class='fa fa-file-excel bigger-110 text-custom'></i> Excel",
		"className": "btn-custom"
	},
	{
		"extend": "print",
		"text": "<i class='fa fa-print bigger-110 text-custom'></i> Print",
		"className": "btn-custom"
	},
	], 

	
	});
$(document).on("click","#submit_btn",function(){

	var student = $("#student").val();
    var detail = $(".detail").val();
	var course_group_id = $("#allClassBycourse").val();

	if(course_group_id != "" )
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
					mode : $("#mode").val(),
					[csrfName]:csrfHash
				};

				var url = BASE_URL + "center/center/create_admission_mode_edit_request"; 
				var response = call_ajax(data,url);
				console.log(response);
				if(response.data=="error"){
					toastr.error("Mode change request already registered !");
				}
				else{
					toastr.success("Mode change request is registered !");
				}
				$("#session").val("");
				$("#student").val("");
				$(".detail").val("");
				$("#allClassBycourse").val("");
				$("#close_modal").click();
				$('#mode_alert-modal').modal('toggle');

				myTable.draw();

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
	var course_group_id = $(this).val();
	var session_course_type=$("#session_course_type").val();
	$.ajax({
		method: "POST",
		url: BASE_URL + "center/center/getStudent_By_Course",
		data: { 
			course_group_id : course_group_id,
			session_course_type:session_course_type,
			[csrfName]:csrfHash
		},
	})
	.done(function( msg ) {
        $('#student').html(msg);
		console.log(msg);
	});

});

$("#session_course_type").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var session = $(this).val();
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getCourseBySession",
		data: { 
			session : session,
			[csrfName]:csrfHash,
			course_type:course_ty
		},
	})
	.done(function( msg ) {
        $('#allClassBycourse').html(msg);
		console.log(msg);
	});
});
});
</script>