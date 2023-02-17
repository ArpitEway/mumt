<div class="mt-5" >
	<div class="row mb-4">
		<div class="col-md-4">
			<div class="form-group">
				<label>Session</label>
				<select class="form-control filter" name="session" id="session">
					<option>All</option>
					<?php foreach ($session_list as $session) {
					echo "<option>".$session['session']."</option>";
					} ?>
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label>Course</label>
				<select class="form-control filter" name="course_group_id" id="allClassBycourse">
					<option>All</option>
					<?php foreach($courses as $course){
			 ?>
			 <option value="<?php echo $course['course_group_id']; ?>"><?php echo $course['course_name']; ?></option>
		     <?php  } ?>
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label>Class</label>
				<select class="form-control filter" name="class_id" id="class_id">
					<option>All</option>
				</select>
			</div>
		</div>
 
       <div class="form-group col-md-3">
			<label for="class">Admission Mode</label>
			<select name="university_mode" id="university_mode" class="form-control filter" >
			    <!-- <option>All</option> -->
				<option value="REG" <?php if($course_type=='REG') { echo 'selected'; } ?> >Regular</option> 
				<option value="PVT" <?php if($course_type=='PVT') { echo 'selected'; } ?> >Private</option>
			</select>
		</div> 

		<div class="col-md-3">
			<div class="form-group">
				<label>Approved</label>
				<select class="form-control filter" name="approved" id="approved">
					<option>All</option>
					<option value="Y">Approved</option>
					<option value="N">Not Approved</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Enrollment Status</label>
				<select class="form-control filter" name="enrolled" id="enrolled">
					<option>All</option>
					<option value="Y">Enrolled</option>
					<option value="N">Not Enrolled</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Document Status</label>
				<select class="form-control filter" name="document" id="document">
					<option>All</option>
					<option value="Y">Uploaded</option>
					<option value="N">Not Uploaded</option>
				</select>
			</div>
		</div>
	</div>


	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">

	<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
		<thead>
			<tr>
				<th>Form No</th>
				<th>Enrollment No</th>
				<th>Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Class</th>
<!-- 				<th>Aadhar No</th>
				<th>Mobile</th> -->
				<th>View Form</th>
				<th>Show Paper</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
</div>
<script>
	$(document).ready(function(){
		var csrfName = $('.csrfname').attr('name');
    	var csrfHash = $('.csrfname').val(); 
  
  	    	var myTable =  $('#memListTable').DataTable({
				// Processing indicator
				"processing": true,
				// DataTables server-side processing mode
				"serverSide": true,
				// Initial no order.
				"order": [0],
				// Load data from an Ajax source
				"ajax": {
					"url": BASE_URL+'center/center/getStudentList',
					"type": "POST",
					"data": function ( d ) {
                  		return $.extend( {}, d, {
                  			[csrfName]:csrfHash,
                  			session: $('#session').val(),
                  			course_group_id: $('#allClassBycourse').val(),
                  			class_id: $('#class_id').val(),
                  			approved: $('#approved').val(),
                  			enrolled: $('#enrolled').val(),
                  			document: $('#document').val(),
                  			university_mode: $('#university_mode').val(),

	                  	} );
              		},
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
		$(document).on('change','.filter', function() {
			myTable.draw();
		});
	});
</script>
