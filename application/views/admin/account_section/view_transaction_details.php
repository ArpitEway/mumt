<div class="card card-custom my-10 details-bg" id="txnDetails">	
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
			<div class="col-md-2 text-center">
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
						<label class="text-heading mt-3"><?= $this->Common_model->viewDate($payment->payment_date); ?></label>
					</div>
					<div class="col-md-2">
						<label class="text-heading mt-3"><?=$payment->txnId;?></label>
					</div>
					<div class="col-md-2 text-center">
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