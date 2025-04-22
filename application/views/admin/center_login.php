<style>
.btn.btn-primary i {
    color: #FFFFFF !important;
}
.btn.btn-primary{
	padding: 8px 10px;
}
</style>
<div class="text-right mt-3">

</div>
<div class=" mt-5" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<table id="memListTable" class="table table-striped dt-responsive" width="100%" >
				<thead>
					<tr>
						<th>#</th>
						<th>Center Id</th>
						<th>Center Code</th>
						<th>Center Name</th>
						<th>City</th>
						<th>Contact Person</th>
						<th>Mobile No</th>
						<th>Options</th>
						<th>Payment Gateway Permission</th>
						<th>Temp Exam Form</th>
						<th>Temp Admission Payment</th>
						<th>Exam Form</th>
						<th>Permission Old</th>
						
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
			"url": BASE_URL+'admin/admins/getCenterLogin',
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

		$(document).on('click', '.center_payment_permission_checks', function() {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var val = $(this).val();
			var status = (val=='Yes') ? 'N' : 'Y';
			var data = {
				id: $(this).attr('data-id'),
				update_payment_by_center_permission: status,
				[csrfName]:csrfHash,
			}; 	
			var url = BASE_URL + "admin/Permission/update_center_payment_permission_checks";
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function (data) {
				 $('#memListTable').DataTable().draw();
				},
			});		
		});

		$(document).on('click', '.permission_checks', function() {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var val = $(this).val();
			var status = (val=='Yes') ? 'N' : 'Y';
			var data = {
				id: $(this).attr('data-id'),
				status: status,
				[csrfName]:csrfHash,
			}; 	
			var url = BASE_URL + "admin/Permission/update_center_old_session_permission";
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function (data) {
				 $('#memListTable').DataTable().draw();
				},
			});		
		});

		$(document).on('click', '.exam_form_permission_checks', function() {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var val = $(this).val();
			var exam_form_permission = (val=='Yes') ? 'N' : 'Y';
			var data = {
				id: $(this).attr('data-id'),
				exam_form_permission: exam_form_permission,
				[csrfName]:csrfHash,
			}; 	
			var url = BASE_URL + "admin/Permission/update_exam_form_permission_notSubmitted";
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function (data) {
				 $('#memListTable').DataTable().draw();
				},
			});		
		});
		$(document).on('click', '.temp_exam_form_permission_checks', function() {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var val = $(this).val();
			
			var temp_exam_form_permission = (val=='Yes') ? 'N' : 'Y';
			var data = {
				id: $(this).attr('data-id'),
				temp_exam_form_permission: temp_exam_form_permission,
				[csrfName]:csrfHash,
			}; 	
			var url = BASE_URL + "admin/Permission/update_temp_exam_form_permission";
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function (data) {
				 $('#memListTable').DataTable().draw();
				},
			});		
		});
		$(document).on('click', '.temp_admission_payment_checks', function() {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var val = $(this).val();
			
			var temp_admission_payment = (val=='Yes') ? 'N' : 'Y';
			var data = {
				id: $(this).attr('data-id'),
				temp_admission_payment: temp_admission_payment,
				[csrfName]:csrfHash,
			}; 	
			var url = BASE_URL + "admin/Permission/update_temp_admission_payment";
			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function (data) {
				 $('#memListTable').DataTable().draw();
				},
			});		
		});
		$(document).on('click', '.center_status_check', function() {
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var val = $(this).val();
			var self =this;
			var status = (val=='Yes') ? 'N' : 'Y';
			var data = {
				id: $(this).attr('data-id'),
				status: status,
				[csrfName]:csrfHash,
			};   
			var url = BASE_URL + "admin/Admins/update_center_status";
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (data) {	  
					$(self).parent().html(data.data);	  
				}
			});
		});
       });
</script>