<div class="mt-5" >
	<div class="row mb-4">
		<div class="col-md-6">
			<div class="form-group">
				<label>Course</label>
				<select class="form-control filter" name="course" id="allClassByCourse">
					<option>All</option>
                    <?php foreach($courses as $course){
			         ?>
			 <option value="<?php echo $course->course_group_id; ?>"><?php echo $course->course_name; ?></option>
		     <?php  } ?>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Class</label>
				<select class="form-control filter" name="class_id" id="class_id">
					<option>All</option>
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
				<th>View Result</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
</div>

<script>

$("#allClassByCourse").on('change', function(){
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val(); 
	var course = $(this).val();
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/AllClassByCourseForResult",
		data: { course_group_id : course,
				[csrfName]:csrfHash
				},
	})
	.done(function( msg ) {
        $('#class_id').html(msg);
	});
});

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
					"url": BASE_URL+'center/center/getStudentListForMarksheet',
					"type": "POST",
					"data": function ( d ) {
                  		return $.extend( {}, d, {
                  			[csrfName]:csrfHash,
                  			course_group_id: $('#allClassByCourse').val(),
                  			class_id: $('#class_id').val(),
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