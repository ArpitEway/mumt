<div class="container-fluid mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<input type="hidden"  class="course_type" value="<?= $course_type; ?>" >
	<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
		<thead>
			<tr>
				<th>#</th>
				<th>Form No.s</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Fees Amount<?php if($course_type=='PVT'&& $late_privte_admission_fees=='Y'){ echo '(Late Fees)';}?></th>
				<th>Pay</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div> 
<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Student Payment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="card card-custom">

 <!--begin::Form-->
<form method="POST" class="d-block" id="ajaxForm" action="<?php echo site_url('admin/enrollment/update_unpaid_student'); ?>">
  <div class="card-body">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">

    <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Student Name</label>
    <div class="col-7">
    <label for="example-date-input" class="col-form-label"><span id="student_name"></span></label>
	</div>
	
   </div>
   <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Receive Payment Date</label>
    <div class="col-7">
     <input class="form-control" type="date" name="payment_date"   id="payment_date"  placeholder="dd-mm-yyyy"/>
	 <div class="text-danger" id="error"></div>
	 <input type="hidden" value="" name="student_id" id="student_id">
    </div>
   </div>
   <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Amount</label>
    <div class="col-7">
     <input class="form-control" type="number" name="amount" id="amount" required readonly />
	 <div class="text-danger" id="error3"></div>
    </div>
   </div>
   <div class="form-group row">
   	<label for="images" class="col-5 col-form-label">Image</label>
   	<div class="col-7">
   		<input class="form-control" type="file" name="images" id="images" required />
   		<div class="text-danger" id="errimg"></div>
   	</div>
   </div>
   <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Payment Mode</label>
    <div class="col-7">
     <select class="form-control" name="payment_mode" id="payment_mode" required>
	 <option value="">Select</option>
	    <option>Cash</option>
		<option>Bank Deposit</option>
		<option>Credit Card</option>
		<option>Debit Card</option>
		<option>Net Banking</option>
		<option>IMPS</option>
		<option>Wallets</option>
		<option>UPI</option>
		<option>NEFT/RTGS</option>
	 </select>
	 <div class="text-danger" id="error2"></div>
	</div>
   </div>
     <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Remark</label>
    <div class="col-7">
	<textarea class="form-control remark" placeholder="Remark detail" id="kt_autosize_2" rows="4" name="remark" id="remark"  ></textarea>
    </div>
   </div>
  
  <div class="card-footer pb-0">
   <div class="row justify-content-center">
  
 
     <button type="reset" class="btn btn-success mr-2" id="payment_submit">Submit</button>
     
   
   </div>
  </div>
 </form>
</div>
		</div>
	</div>
</div>
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
	"url": BASE_URL+'center/center/getUnpaidFeesList/Admission',
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

  $(document).on('click','.pay1',function(){
	    var name_csrf = $(this).attr('data-name_csrf');
	    var hash_csrf = $(this).attr('data-hash_csrf');
	    var student_id = $(this).attr('data-student_id');
	    var student_id = $(this).attr('data-student_id');
		var student_name = $(this).attr('data-student_name');
		var amount = $(this).attr('data-amount');
		$('#student_id').val(student_id);
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
				var id = $(this).data('id');
				Swal.fire({
						title: "Are you sure?",
						text: "Want To Pay Fees ?",
						icon: "info",
						showCancelButton: true,
						confirmButtonText: "Yes"
					}).then(function(result) {
						if(result.isConfirmed){
						window.location.href = BASE_URL+"center/payment/admission/"+student_id;
						}else{
							return false;
						}
				});
		});
</script>