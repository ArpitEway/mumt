<ul class="my-5">
	<li class="text-danger">
    Payment Gateway द्वारा किये गए ट्रांसेक्शन से संबधित समस्या के समाधान लिए Form No एवं पूर्ण जानकारी के साथ Submit करे |</li>
</ul>

<div class="card">
  	<div class="card-body row text-center">
  		<div class="form-group col-md-3 m-auto" >		
			<label>Enter Form Number</label>
			<input type="text" name="form_no" id="form_no" class="form-control form-control-lg form-control-solid">
			<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
		</div>		
		<div class="form-group col-md-12">
			<label for="class"></label>
			<button type="button" class="btn btn-primary mt-4" style="margin-top: 24px !important;" id="search_btn">Search</button>
		</div>
	</div>
</div>

<div id="dt">
</div>

<div id="dq">
<div class="text-center">
<table id="memListTable" class="table table-striped dt-responsive nowrap" width="70%" >
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
    		
			</tbody>
</table>
</div>

</div>

<script>
	$(document).ready(function(){
		drowTable();
	});

$(document).on("click","#search_btn",function(){

	var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val(); 
	
	var data = {
		form_no : $("#form_no").val(),
		[csrfName]:csrfHash
	};
	var url = BASE_URL+"center/Center/get_student_detail"; 
	var response = call_ajax(data,url);
	console.log(response);
	
	$('#dt').html(response.data);
	
	
});
function refreshtable(){
	myTable.draw();
}

function drowTable(){
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
	"url": BASE_URL+'center/center/getPaymentComplaint',
	"type": "POST",
	"data": {[csrfName]:csrfHash}
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
}
</script>