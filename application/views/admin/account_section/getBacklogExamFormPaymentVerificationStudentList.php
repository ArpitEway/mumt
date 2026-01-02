<div class="container-fluid mt-5" > 
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<input type="hidden"  class="course_type" value="<?= $course_type; ?>" >
	<?php if(@$centerData->center_code){ ?>
	<h3 class="text-primary text-center h2"><?= ' ( '.$centerData->center_code.' ) ( '.$centerData->center_name.' )  '; ?></h3>
	<?php } ?>
	<table id="memListTable" class="table table-striped dt-responsive" style="width:100%">
		<thead>
			<tr>
				<th>Form No.</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Class</th>
				<th>Fees Amount</th>
				<th>Pay</th>
			</tr>
		</thead>
		<tbody>
		<?php //print_r($complaints);die;
		foreach($complaints as $complaint){ 
			?>

		<tr id="row_<?=$complaint->backlog_id ?>">

        
			<td><?php echo $complaint->student_id; ?></td>
			<td><?php echo $complaint->name; ?></td>
			<td><?php echo $complaint->fathername; ?></td>
			<td><?php echo $complaint->course_name; ?></td>
			<td><?php echo $this->Common_model->getClassNameByClassId($complaint->class_id); ?></td>
			<td><?php echo $complaint->amount; ?></td>
			<td><?php echo '<a href="#" data-id="'.$complaint->backlog_id.'" data-pid="'.$complaint->payment_id.'" data-pdate="'.DateTime::createFromFormat('Y-m-d', $complaint->payment_date)->format('d-m-Y').'" data-txnId="'.$complaint->txnId.'" data-paymentMode="'.$complaint->payment_mode.'"  data-receipt_number="'.$complaint->receipt_number.'" data-student_name = "'.$complaint->name.'"  data-idstudent="'.$complaint->student_id.'" data-student_id="'.$this->Common_model->encrypt_decrypt($complaint->student_id).'" class="btn btn-primary btn-sm font-weight-bold pay1" data-toggle="modal" data-target="#kt_datepicker_modal" "  data-amount= "'.$complaint->amount.'">Receive</a>';?></td>
		</tr>
		<?php	
		}?>
		</tbody>
	</table>
</div> 
<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Student Payment Detail</h5>
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
    <input type="hidden" value="" name="student_id" id="student_id">
    <input type="hidden" value="" name="idstudent" id="idstudent">
    <input type="hidden" value="" name="payment_id" id="payment_id">
	<input type="hidden" value="" name="backlog_id" id="backlog_id">
	</div>
	
   </div>
   <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Receive Payment Date</label>
    <div class="col-7">
		
    <label for="example-date-input" class="col-form-label"><span id="payment_date"></span></label>
    </div>
   </div>
   <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Amount</label>
    <div class="col-7">
    <label for="example-date-input" class="col-form-label"><span id="amount"></span></label>
    </div>
   </div>
  <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Receipt No.</label>
    <div class="col-7">
    <label for="example-date-input" class="col-form-label"><span id="receipt_number"></span></label>
	</div>
   </div>
   
    
  <div class="card-footer pb-0">
   <div class="row justify-content-center">
  
 
     <button type="reset" class="btn btn-success mr-4" id="payment_submit">Verify</button>
     <button type="reset" class="btn btn-danger mr-2" id="payment_pending">Non-Verify</button>
     
   
   </div>
  </div>
 </form>
</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		
		$('#memListTable').DataTable({});
	
	});

   
  $(document).on('click','.pay1',function(){
	    var name_csrf = $(this).attr('data-name_csrf');
	    var hash_csrf = $(this).attr('data-hash_csrf');
	    var student_id = $(this).attr('data-student_id');
		var idstudent = $(this).attr('data-idstudent');
		var student_name = $(this).attr('data-student_name');
		var payment_date = $(this).attr('data-pdate');
        var amount = $(this).attr('data-amount');
        var receipt_number = $(this).attr('data-receipt_number');
        var payment_id = $(this).attr('data-pid');
		var backlog_id = $(this).attr('data-id');
		$('#student_id').val(student_id);
		$('#idstudent').val(idstudent);
		$('#student_name').html(student_name);
		$('#amount').html(amount);
        $('#payment_date').html(payment_date);
        $('#receipt_number').html(receipt_number);
        $('#payment_id').val(payment_id);
		$('#backlog_id').val(backlog_id);
		
	});
	
	$("#payment_submit").on('click',function (e){
	var formimage = $('#ajaxForm');
	var frm = new FormData(formimage[0]);
		
        var student_id = $('#student_id').val();
		var idstudent = $('#idstudent').val();
		var backlog_id = $('#backlog_id').val();
		
		
		$.ajax({
		url: '<?php echo site_url('admin/account/update_backlog_student_exam_form_payment_status'); ?>',
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
			var rowid="#row_"+backlog_id;
			console.log("rowid "+rowid);
			$(rowid).remove();
			//location.reload();
			
		
			toastr.success("Verified");
			
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

$("#payment_pending").on('click',function (e){
	var formimage = $('#ajaxForm');
	var frm = new FormData(formimage[0]);
		
        var student_id = $('#student_id').val();
		var idstudent = $('#idstudent').val();
		let payment_id = $('#payment_id').val();
		let backlog_id = $('#backlog_id').val();
		
		
		$.ajax({
		url: '<?php echo site_url('admin/account/update_backlog_student_online_payment_status'); ?>',
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
			var rowid="#row_"+backlog_id;
			console.log("rowid "+rowid);
			$(rowid).remove();
			//location.reload();
			
		
			toastr.success("Non Verified");
			
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
    

		// $(document).on('click','.pay',function(){
		// 		var student_id = $(this).data('student_id');
		// 		var id = $(this).data('id');
		// 		Swal.fire({
		// 				title: "Are you sure?",
		// 				text: "Want To Pay Fees ?",
		// 				icon: "info",
		// 				showCancelButton: true,
		// 				confirmButtonText: "Yes"
		// 			}).then(function(result) {
		// 				if(result.isConfirmed){
		// 				window.location.href = BASE_URL+"center/payment/admission/"+student_id;
		// 				}else{
		// 					return false;
		// 				}
		// 		});
		// });
</script>