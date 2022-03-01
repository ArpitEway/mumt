<div class="card card-custom my-10 details-bg" id="profile">	
	<div class="container-fluid profile mt-5">
		<h4 class="card-title">Student Details</h4>
		<div class="row">
			<div class="col-sm-10 row">
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Session</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->session; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Center Code</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->center_code; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Enrollment No</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->enrollment_no; ?>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Form No</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->student_id; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Student</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->name; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Father</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->f_h_name; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Course</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->course_name;  ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Class</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->class_name; ?>
						</div>
					</div>
				</div>

			</div>
			<div class="col-sm-2">
				<img src="<?=base_url('assets/student_image/'.$student->session.'/'.$student->photo)?>" class="img-thumbnail" width="120">
			</div>
		</div>
	</div>
</div>
<div id="txnDetails">
	
<div class="card card-custom my-10 details-bg">	
	<div class="container-fluid profile mt-5">
		<h4 class="card-title">Transaction Details</h4>
		<div class="row">
			<div class="col-md-1">
				<label class="text-heading">Class</label>
			</div>
			<div class="col-md-1">
				<label class="text-heading">Amount</label>
			</div>
			<div class="col-md-2">
				<label class="text-heading">Fees Head</label>
			</div>
			<div class="col-md-2">
				<label class="text-heading">Payment Status</label>
			</div>
			<div class="col-md-2">
				<label class="text-heading">Payment Date</label>
			</div>
			<div class="col-md-2">
				<label class="text-heading">Transaction no</label>
			</div>
			<div class="col-md-1 text-center">
				<label class="text-heading">Action</label>
			</div>
			
		</div>
			<?php foreach ($paymentDetails as $payment) { ?>
				<div class="row mt-3">
					<div class="col-md-1">
						<label class="text-heading mt-3"><?=$this->Common_model->getClassNameByClassId($payment->class_id); ?></label>
					</div>
					<div class="col-md-1">
						<label class="text-heading mt-3"><?=$payment->amount; ?></label>
					</div>
					<div class="col-md-2">
						<label class="text-heading mt-3"><?=$payment->fees_head;?></label>
					</div>
					<div class="col-md-2">
						<label class="text-heading mt-3"><?=$payment->payment_status;?></label>
					</div>
					<div class="col-md-2">
						<label class="text-heading mt-3"><?= ($payment->payment_date=='') ? '' : $this->Common_model->viewDate($payment->payment_date); ?></label>
					</div>
					<div class="col-md-2">
						<label class="text-heading mt-3"><?=$payment->txnId;?></label>
					</div>
					<div class="col-md-1 text-center">
						<?php if($payment->payment!="Y"){ ?>
							<label class="text-heading mt-3"><button type="button" class="btn btn-primary modalOpen" data-toggle="modal" data-paymentId="<?=$payment->id;?>" data-student_id="<?=$payment->student_id;?>" data-target="#exampleModalCenter">Update</button></label>

						<?php }else{
							echo '<label class="text-heading mt-3"> Paid </label>';
						} ?>
					</div>




				</div>
			<?php } ?>
		</div>



	</div>

                <div class=" text-center"> 
				
							<label class="text-heading mt-3"><button type="button" class="btn btn-primary modalOpen1" data-toggle="modal" id="showbutton" data-student_id="<?=$student->student_id;?>" data-target="#exampleModalCenter1"> Add Transaction</button></label>
					</div>              

</div>




<div class="modal fade" id="exampleModalCenter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form >
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body row">
				<fieldset class="form-group col-md-6">
					<label for="transaction">Transaction Id</label>
					<input  type="hidden" name="id" id="paymentId">
					<input  type="hidden" name="student_id" id="student_id">
					<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
					<input type="text" required name="TxnId" class="form-control" id="transaction" placeholder="">
				</fieldset>
				<fieldset class="form-group col-md-6">
					<label for="date">Date</label>
					<input type="text" required class="form-control" id="dateTime" name="dateTime">
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
				<button type="submit" id="submit"  class="btn btn-primary font-weight-bold">Submit</button>
			</div>
		</form>
		</div>
	</div>
</div>


<div class="modal fade" id="exampleModalCenter1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form >
				

			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>

			<div class="modal-body row">
           <fieldset class="form-group col-md-6">
           	<label for="fees">Fess</label>	
                
						<select name="fess" id="fess" class="form-control" >
							<option value="">Select All</option>
						<option selected value="Exam Fees">Exam fees</option>
							<option value="Admission Fees">Admission fees</option>
									
						</select>
						
              </fieldset>

				<fieldset class="form-group col-md-6">
					<label for="transaction">Transaction Id</label>	>
		
					<input type="text" required name="TxnIdnew" class="form-control" id="transaction1" placeholder="">
				</fieldset>
				<fieldset class="form-group col-md-6">
					<label for="date"> Payment Date</label>
					<input type="text" required class="form-control" id="dateTime1" name="dateTimenew">

				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
				<button type="submit" id="submit1"  class="btn btn-primary font-weight-bold">Submit</button>
			</div>
		</form>
		</div>
	</div>
</div>



<script type="text/javascript">
	$( ".modalOpen" ).on('click', function(){
		$('#paymentId').val($(this).attr("data-paymentId"));
		$('#student_id').val($(this).attr("data-student_id"));
	});

	$('#dateTime').inputmask("yyyy-mm-dd hh:mm:ss", {
        placeholder: "yyyy-mm-dd hh:mm:ss", 
        insertMode: false, 
        showMaskOnHover: false,
        hourFormat: '24'
      });

	$('#submit').on('click',function (e) {
		e.preventDefault();

		 
		let formData = $('form').serialize();
		$.ajax({
			url: BASE_URL+ 'admin/'+account_type+'/updatePaymentTransaction',
			method: 'post',
			data: formData,
			dataType: 'JSON',
			success: function (response) {
				if(response.success){
					toastr.success(response.success);
					$('#txnDetails').html(response.data);

					$('.modalOpen').remove();
					$('#exampleModalCenter').toggle();
					$('.modal-backdrop').remove();
					//location.reload();
				}else if(response.error){
					toastr.error(response.error);
				}
			}
		})
	})
</script>



<script type="text/javascript">
	$( ".modalOpen1" ).on('click', function(){
		
		$('#student_id').val($(this).attr("data-student_id"));
	});

	$('#dateTime1').inputmask("yyyy-mm-dd hh:mm:ss", {
        placeholder: "yyyy-mm-dd hh:mm:ss", 
        insertMode: false, 
        showMaskOnHover: false,
        hourFormat: '24'
      });

	$('#submit1').on('click',function (e) {
		e.preventDefault();
		
		let formData = $('form').serialize();
		
		$.ajax({
			url: BASE_URL+ 'admin/'+account_type+'/updatePaymentTransactiodetails',
			method: 'post',
			data: formData,
			dataType: 'JSON',
			success: function (response) {
				if(response.success){
					toastr.success(response.success);
					$('#txnDetails').html(response.data);
					$('.modalOpen1').remove();
					$('#exampleModalCenter1').toggle();
					$('.modal-backdrop').remove();
					$('#showbutton').show();
					
					//location.reload();
				}else if(response.error){
					toastr.error(response.error);
				}
			}
		})
	})
</script>