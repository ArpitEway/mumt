<div class="container-fluid mt-5" >
	<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
		<thead>
			<tr>
				<th>Student Id</th>
				<th>Enrollment No</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Fees Head</th>
				<th>Fees Amount</th>
				<th>Payment Date</th>
				<th>Pay</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script>
	$(document).ready(function(){
		var myTable =  $('#memListTable').DataTable({
// Processing indicator
"processing": true,
// DataTables server-side processing mode
"serverSide": true,
// Initial no order.
"order": [0],
// Load data from an Ajax source
"ajax": {
	"url": BASE_URL+'center/center/getFeesList/unpaid',
	"type": "POST"
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
	"className": "btn-info",
	columns: ':not(:first)'
},
{
	"extend": "copy",
	"text": "<i class='fa fa-copy bigger-110 text-custom'></i> Copy",
	"className": "btn-info"
},
{
	"extend": "excel",
	"text": "<i class='fa fa-file-excel bigger-110 text-custom'></i> Excel",
	"className": "btn-info"
},
{
	"extend": "print",
	"text": "<i class='fa fa-print bigger-110 text-custom'></i> Print",
	"className": "btn-info"
},
], 
});
	});


	$(document).on('click','.pay',function(){
		var student_id = $(this).data('student_id');
		var id = $(this).data('id');
		Swal.fire({
				title: "Are you sure?",
				text: "Want To Pay Fees ?",
				icon: "info",
				showCancelButton: true,
				confirmButtonText: "Yes"
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						url: BASE_URL+'center/center/payStudentFees',
						method: 'post',
						dataType: 'json',
						data: {'student_id':student_id, 'id' :id},
						success: function (data) {
							if(data.error== undefined){
								$('#memListTable').DataTable().draw();
								$('#balance').html(data.balance);
								Swal.fire(
									"",
									data.success,
									"success"
									);
							}else{
								Swal.fire(
									"",
									data.error,
									"error"
									);
							}
							
						}
					});
				}
		});
	});
</script>