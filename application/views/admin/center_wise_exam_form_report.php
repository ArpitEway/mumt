<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
</style>
<div class="text-right mt-3">

</div>
<div class=" mt-5" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="memListTable" class="table table-striped dt-responsive nowrap" width="100%" >
				<thead>
					<tr>
						<th>Sn No.</th>
						<th>Center Code</th>
						<th>Center Name</th>
						<th>City</th>
						<th>Distirct</th>
						<th>Contact Person</th>
						<th>Mobile No</th>
						<th>Total Count</th>
						<th>Fill Count </th>
						<th>Remaining Count</th>
						
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
			"url": BASE_URL+'admin/admins/get_center_wise_exam_form_report',
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

		
	
       });
</script>