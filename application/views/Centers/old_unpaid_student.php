<div class="container-fluid mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	
	<input type="hidden"  class="late_privte_admission_fees" value="<?= $late_privte_admission_fees; ?>" >
	<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
		<thead>
			<tr>
				<th>#</th>
				<th>Form No.s</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Fees Amount</th>
				
				<th>Move to Late Fee List</th>
				
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
	"url": BASE_URL+'center/center/getOldUnpaidFeesList/Admission',
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

  $(document).on('click','.pay1',function(){
	    var name_csrf = $(this).attr('data-name_csrf');
	    var hash_csrf = $(this).attr('data-hash_csrf');
	    var student_id = $(this).attr('data-student_id');
	    var student_id = $(this).attr('data-student_id');
		var student_name = $(this).attr('data-student_name');
		var amount = $(this).attr('data-amount');
		$('#student_id').val(student_id);
        // $('#student_id_show').html(student_id);
		$('#student_name').html(student_name);
		$('#amount').val(amount);
		
	});
	
	$("#payment_submit").on('click',function (e){
	var formimage = $('#ajaxForm');
	var frm = new FormData(formimage[0]);
		
        var student_id = $('#student_id').val();
		var payment_date = $('#payment_date').val();
		var payment_mode = $('#payment_mode').val();
		var amount = $('#amount').val();
        var paid_amount = $('#paid_amount').val();
        var transaction_number = $('#transaction_number').val();
		var receipt_number = $('#receipt_number').val();
        if(amount !== paid_amount){
            toastr.warning('Paid Amount Diffrent from Required Amount');
            return false;
        }
		if(payment_date==''){
			$('#error').text('Please Select Date');
			return false;
		}
		if(amount==''){
			$('#error3').text('Please Enter Amount');
			return false;
		}
		if(payment_mode==''){
			$('#error2').text('Please Select Payment Mode');
			return false;
		}
		
		$.ajax({
		url: '<?php echo site_url('center/center/update_unpaid_student'); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		cache:false,
		contentType: false,
		processData: false,
		beforsend: function()
              {
                console.log('loading..');
                $("#myLoader").show();
               },
		success: function (data) {
		if(data){
			console.log(data);
			$('#kt_datepicker_modal').modal('toggle');
			//$('#student_tr_'+student_id).remove();
			location.reload();
			
		
			toastr.success("Submitted");
			
		}else{
			toastr.error("Something wrong");
		}
			},
			complete: function()
              {
                console.log('loading...over');
                $("#myLoader").hide();
               },
		});	
	
});	
    

		$(document).on('click','.pay',function(){
				var student_id = $(this).data('student_id');
				var csrfName = $('.csrfname').attr('name');
				var csrfHash = $('.csrfname').val(); 
				var id = $(this).data('id');
				Swal.fire({
						title: "Are you sure?",
						text: "Want To Move With Late Fees ?",
						icon: "info",
						showCancelButton: true,
						confirmButtonText: "Yes"
					}).then(function(result) {
						if(result.isConfirmed){
							console.log("student_id "+student_id);
							$.ajax({
									type: "POST",
									url: BASE_URL+"center/center/move_with_late_fees",
									dataType:"json",
									data: {student_id: student_id,[csrfName]:csrfHash},
									success: function(response){
									console.log(response);
										if(response.status=='true'){
										toastr.success("Move Student Successfully !");
										//myTable.draw();
										window.location.href = BASE_URL+"/unpaid_student_list";
										}

										else{
										toastr.error("Something wrong");
										}
									}
								});	
						
						}else{
							return false;
						}
				});
		});
		
</script>