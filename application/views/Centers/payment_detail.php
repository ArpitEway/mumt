<style type="text/css">
.print-logo{
			display:none;
	}
@media print{
	body * { visibility: hidden; }
	#printThisDivIdOnButtonClick * { visibility: visible; }
	#printThisDivIdOnButtonClick * { width:950px; margin:0!important; font-size:22px; }
	#printThisDivIdOnButtonClick  { position: absolute; left:-20px !important; padding-top: 1.0cm; }
	.bg-primary{ background-color: #052c68 !important; color:#000 !important; }
		.print-logo{
			display:block;
		}
		.h2{
			font-size:50px !important;
			padding-top: 0.5cm;
       padding-bottom: 0.5cm;
		}
	#buttonId{
	visibility: hidden;
	}
}
</style>
<div class="p-10 col-sm-6 m-auto" id="printThisDivIdOnButtonClick">
	<div class="print-logo">
		<img src="<?=base_url()?>assets/images/maskgroup/Group1.png" alt="">
	</div>
	<div class="h2 text-center my-5">Payment Receipt </div>
<div class="row border border-primary bg-primary text-custom p-2">
		Billing Details
	</div>
	<div class="row py-3 border">
		<div class="col-sm-5 border-right">Form NO
		</div>
		<div class="col-sm-7 "><?=$student['student_id']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-5 border-right">Name
		</div>
		<div class="col-sm-7 "><?=$student['name']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-5 border-right">Father / Husband Name
		</div>
		<div class="col-sm-7 "><?=$student['f_h_name']?>
		</div>
	</div>
		<div class="row py-3 border">
		<div class="col-sm-5 border-right">Course Name
		</div>
		<div class="col-sm-7 "><?=$this->Common_model->getCourseNameByCourseId($student['course_group_id'])?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-5 border-right">Class
		</div>
		<div class="col-sm-7 "><?=$this->Common_model->getClassNameByClassId($student['class_id'])?>
		</div>
	</div>
	<div class="row border border-primary bg-primary text-custom p-2">
		<div class="col-sm-5 border-right">Payment Details
		</div>
		<div class="col-sm-7 ">
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-5 border-right">Payment Type
		</div>
		<div class="col-sm-7 "><?=$transaction['fees_head'];?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-5 border-right">Payment Date
		</div>
		<div class="col-sm-7 "><?=date("d-m-Y", strtotime($transaction['payment_date']));?>
		</div>
	</div>
	<?php $text = ($transaction['payment']=='Y') ? 'text-success' : 'text-danger'; ?>
	<div class="row py-3 border">
		<div class="col-sm-5 font-weight-bold border-right">Payment Status
		</div>
		<div class="col-sm-7 font-weight-bold <?=$text?> "><?=$transaction['payment_status'];?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-5 border-right">Amount
		</div>
		<div class="col-sm-7"><?=$transaction['amount']?>
	</div>
		
	</div>
	<div class="row py-3 border justify-content-center">
	<a id="buttonId" class="btn btn-primary font-weight-bold" href="#">Print</a>
</div>
</div>
<script>
   $('#buttonId').on('click', function () {
      window.print();
   });
</script>