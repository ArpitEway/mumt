<div class="card Payment-complaint">
	<div class="card-body row text-center">
		<div class="form-group m-auto w-50" >		
			<label class="font-weight-bold">Enter Form Number</label>
			<input type="text" name="form_no" id="form_no" class="form-control form-control-lg form-control-solid">
			<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">	
		</div>		
		<div class="form-group col-md-12">
			<label for="class"></label>
			<button type="button" class="btn btn-lg btn-custom mt-4 col-sm-3" style="margin-top: 24px !important;" id="search_btn">Search</button>
		</div>
		<div id="dt" class="table-responsive">
		</div>
	</div>
</div>

<div id="dq">
	<div class="text-center">
		<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Student Name</th>
					<th>Form no</th>
					<th>Course </th>
					<th>Class</th>
					<th>Department</th>
                    <th>Type</th>
					<th>Detail</th>
					<th>Date</th>
					<th>Status</th>
					<th>Remark</th>
					<th>Reply</th>
					<th>Attachment</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
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
			"url": BASE_URL+'center/center/getsupportComplaint',
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

		$(document).on("click","#search_btn",function(){
			$('#dt').html('');
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var data = {
				form_no : $("#form_no").val(),
				[csrfName]:csrfHash
			};
			var url = BASE_URL+"center/Center/get_student_support_system"; 
			var response = call_ajax(data,url);
			console.log(response);
			$('#dt').html(response.data);
			myTable.draw();
		});

		$(document).on('click','#submit',function(e){
			// alert($('#photo').val())
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val(); 
			var student_id = $('#student_id').val(); 
			detail = $('#detail').val(); 
			complaint_type = $('#Complaint').val();
			department = $('#complaint_department').val();
			photo = $('#photo').val();
			if(complaint_type == 'N'){
				toastr.error("Please Select Complaint Type");
				return false;
			}
			if(detail)
			{
				//var frm = $('.ajaxForm').serialize();
				var form = $('form');
				var formData = new FormData(form[0]);
					
				$.ajax({
					url: BASE_URL + 'center/center/student_support_system_sub/' + student_id,
					type: 'POST',
					dataType : 'json',
					data: formData,
					cache:false,
					contentType: false,
					processData: false,
					success: function (data) 
					{
						console.log(data.msg);
						if(data.msg){
							$('#formData').fadeOut('slow');
							toastr.success(data.msg);		
						}
						else if(data.err_msg){
							$('#formData').fadeOut('slow');
							toastr.error(data.err_msg);
						}
						else{
							$('#formData').fadeOut('slow');
							toastr.error("Something wrong");
						}
						myTable.draw();
					},
				});
			}else{
				toastr.error("Please Enter detail");
			}
		});

	});

</script>