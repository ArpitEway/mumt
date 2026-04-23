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
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Email</label>
						<div class="col-sm-8 text-value">
							<?php echo $studentContactData->p_email;  ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Mobile Number</label>
						<div class="col-sm-8 text-value">
							<?php echo $studentContactData->p_mobile_no; ?>
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
			<h4 class="card-title">Documents Details</h4>
            <?php            if($documentDetails){
            ?>
			<div class="row">
				<div class="col-md-4">
					<label class="text-heading">Document Name</label>
				</div>
				<div class="col-md-4">
					<label class="text-heading">Document Image</label>
				</div>
				<div class="col-md-4">
					<label class="text-heading">Upload Date</label>
				</div>
				
			</div>
			<?php
           
            foreach ($documentDetails as $document) { ?>
				<div class="row mt-3">

					<div class="col-md-4">
						<label class="text-heading mt-3"><?=$document->document_name; ?></label>
					</div>
					<div class="col-md-4">
						<label class="text-heading mt-3"><a  target="_blank" href="<?php echo ($student->enrolled !="Y")?BASE_URL('assets/documents/'.$document->document_image):BASE_URL('assets/enrolled_documents/'.$student->session.'/'.$document->document_image); ?>"><?php echo $document->document_image; ?></a></label>
					</div>
					<div class="col-md-4">
						<label class="text-heading mt-3"><?= date('d-m-Y', strtotime($document->date_time)); ?></label>
					</div>
                </div>
			<?php }
            }else{
                ?> <div class="d-flex justify-content-center text-heading">This Student Does Not Have Any Documents Uploaded !</div>
                <?php
            }
            ?>
		</div>
	</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>

<script>
$(document).ready(function () {
    $('.dateTime').inputmask("datetime", {
        inputFormat: "yyyy-mm-dd HH:MM:ss",
        placeholder: "yyyy-mm-dd hh:mm:ss",
        hourFormat: "24"
    });
});
</script>
<script type="text/javascript">
	$( ".modalOpen" ).on('click', function(){
		$('#paymentId').val($(this).attr("data-paymentId"));
	});
	$j=jQuery.noConflict();
	// $('.dateTime').inputmask("yyyy-mm-dd hh:mm:ss", {
	// 	placeholder: "yyyy-mm-dd hh:mm:ss", 
	// 	insertMode: false, 
	// 	showMaskOnHover: false,
	// 	hourFormat: '24'
	// });

	$('#submit').on('click',function (e) {
		e.preventDefault();
		
		
		let formData = $('#updateTxnForm').serialize();
		$.ajax({
			url: BASE_URL+ 'admin/'+account_type+'/updatePaymentTransaction',
			method: 'post',
			data: formData,
			dataType: 'JSON',
			success: function (response) {
				if(response.success){
					toastr.success(response.success);
					$('#txnDetails').html(response.data);
					$j=jQuery.noConflict();
					$('#updateTxn').toggle();
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
	$j=jQuery.noConflict();
	$('#submit1').on('click',function (e) {
		e.preventDefault();
		
		let formData = $('#addTxnForm').serialize();
		$.ajax({
			url: BASE_URL+ 'admin/'+account_type+'/add_new_txn',
			method: 'post',
			data: formData,
			dataType: 'JSON',
			success: function (response) {
				$('#addTxnForm')[0].reset();
				if(response.success){
					toastr.success(response.success);
					$('#txnDetails').html(response.data);
					$('#addNewTxn').toggle();
					$('.modal-backdrop').remove();
				}else if(response.error){
					toastr.error(response.error);
					$('#addNewTxn').toggle();
					$('.modal-backdrop').remove();

				}
			}
		})
	})
</script>