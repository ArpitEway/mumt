<div class="mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Form No</th>
            <th>Course</th>
            <th>Class</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>Mobile</th>
            <th>Date of birth</th>
            <th>Status</th>
			<th>Installment Status</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
			<th>Form No</th>
			<th>Course</th>
            <th>Class</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>Mobile</th>
            <th>Date of birth</th>
            <th>Status</th>
			<th>Installment Status</th>
        </tr>
    </tfoot>
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
<div class="card-body">
 <!--begin::Form-->
<form method="POST" class="d-block" id="ajaxForm" action="<?php echo site_url('admin/enrollment/update_unpaid_student'); ?>">	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Student Name</label>
    <div class="col-7">
    <label for="example-date-input" class="col-form-label"><span id="student_name"></span></label>
	</div>
	
   </div>
   <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Receive Payment Date</label>
    <div class="col-7">
     <input class="form-control" type="date" name="payment_date"   id="payment_date" required  placeholder="dd-mm-yyyy"/>
	 <div class="text-danger" id="error"></div>
	 <input type="hidden" value="" name="student_id" id="student_id">
    </div>
   </div>
   <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Amount</label>
    <div class="col-7">
     <input class="form-control" type="number" name="amount" id="amount" required />
	 <div class="text-danger" id="error3"></div>
    </div>
   </div>
      <div class="form-group row">
   	<label for="images" class="col-5 col-form-label">Image</label>
   	<div class="col-7">
   		<input class="form-control" type="file" name="images" id="images" />
   		<div class="text-danger" id="errimg"></div>
   	</div>
   </div>
    <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Payment Mode</label>
    <div class="col-7">
     <select class="form-control" name="payment_mode" id="payment_mode" required>
	 <option value="">Select</option>
	 	<option>Credit Card</option>
		<option>Debit Card</option>
		<option>Net Banking</option>
		<option>IMPS</option>
		<option>Wallets</option>
		<option>UPI</option>
		<option>NEFT/RTGS</option>
		<option>SabPaisa Payment by Link</option>
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
</div>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": BASE_URL+'admin/enrollment/getUnpaidStudentProgramFee',
            "type": "POST",
            "data": {[csrfName]:csrfHash}
        },
         "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false
        }],
        dom: '<"row" <"col-md-4" l><"col-md-4 text-center" B> <"col-md-4" f>>rt<"bottom"ip>',
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


$('table').on('click', '.student', function (e) {
    var student_id = $(this).attr('data-id');
	var student_name = $(this).attr('data-name');
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
		if(payment_date==''){
			$('#error').text('Please Select Date');
			return false;
		}
		if(payment_mode==''){
			$('#error2').text('Please Select Mode');
			return false;
		}
		
		$.ajax({
		url: '<?php echo site_url('admin/enrollment/update_unpaid_program_fees'); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		cache:false,
		contentType: false,
		processData: false,
		success: function (data) {
			$('#memListTable').DataTable().row('#student_'+student_id).remove().draw();
		if(data){
			$('#kt_datepicker_modal').modal('toggle');
			toastr.success("Submitted");
		}else{
			toastr.error("Something wrong");
		}
			},
		});	
	
});

});





$(document).on('click', '.status_checks', function() {
  var val = $(this).val();
    var status = (val=='Yes') ? 'N' : 'Y';
	var data = {
				id: $(this).attr('data-id'),
				status: status
			}; 
			
			var url = BASE_URL + "admin/enrollment/update_student_installment_permission_status";
			
		$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: function (data) {
			$('#memListTable').DataTable().draw();
		}
		});
			
		});

</script>