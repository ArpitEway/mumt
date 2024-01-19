<div class="container-fluid mt-5" >
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
		<input type="hidden"  class="course_type" value="<?= $course_type; ?>" >
	<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
		<thead>
			<tr>
				<th>S No.</th>
				<th>Form No.</th>
				<th>Enrollment No.</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Mode</th>
				<th>Fees Head</th>
				<th>Amount</th>
				<!-- <th>Txn Id</th> -->
				<th>Receipt</th>
                <th>Document</th>
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
		var course_ty=$('.course_type').val();
		console.log("course_type "+course_ty);
		var myTable =  $('#memListTable').DataTable({
// Processing indicator
"processing": true,
// DataTables server-side processing mode
"serverSide": true,
// Initial no order.
"order": [0],
// Load data from an Ajax source
"ajax": {
	"url": BASE_URL+'center/center/getApplicationList',
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
	});
</script>