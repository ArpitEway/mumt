<style>
	.form-block {
		background: hsl(228deg 100% 99%);
		border: 1px solid hsl(216deg 84% 83%);
		border-radius: 10px;
		padding: 10px;
	}
	.label_form{
		font-size: 14px !important;
		font-weight: 500 !important;
	}
	.student_form_img {
		width: 115px;
		margin: auto;
		height: 140px;
		object-fit: cover;
	}
	.m-auto{
		margin:auto;
	}
	hr.new2 {
		border-top: 1px dashed gray;
	}
	.text-primary {
		color: #052c68!important;
	}
	.f-heading-1{
		padding: 10px 0px 10px 0px;
		font-size: 28px;
		font-weight: 500;
	}
	.f-heading-2 {
		padding: 0px 0px 12px 0px;
		font-size: 20px;
		font-weight: 400;
	}
	.f-heading-3 {
		padding: 0px 0px 8px 0px;
		font-size: 23px;
		font-weight: 500;
	}
	.form-text-color {
		font-weight: 600;
		color: #635050c2;
	}
	.form-group.col-md-5.text-left.m-auto,
	.form-group.col-md-4.text-left.m-auto ,
	.form-group.col-md-2.text-left.m-auto,
	.form-group.col-md-9.text-left.m-auto,
	.form-group.col-md-3.text-left.m-auto {
		padding: 5px;
		text-transform: uppercase;
		font-size: 14px;
	}


	@media print{
		body * { visibility: hidden; margin: 0.5cm !importent;}
		#printThisDivIdOnButtonClick * {  visibility: visible; }
		#printThisDivIdOnButtonClick * {  margin:0px  !important; font-size:14px; }
		#printThisDivIdOnButtonClick   {  width:100%; position: absolute; left:-20px !important; padding-top: 1.0cm;margin-top:-260px !important; }
		#printHeaderdiv * {
			font-size:20px !important; 
		}
		.label_heading {
			padding: 5px 0px 5px 0px !important;
		}
		.signature{
			padding-top:30px !important;
		}
		.cls {
			padding-left:25px !important;
		}
		.form-text-color{
			font-weight: 500;
			color:#635050c2;
		}
		h1 {page-break-before: always !important;}
		th{
			width:auto !important;
		}
		.label_form{
			font-size: 12px !important;
			font-weight: 400 !important;
		}
		.bg-primary{ background-color: #052c68 !important; color:#000 !important; }
		.print-logo{
			display:block;
		}
		.h2{
			font-size:25px !important;
			padding-top: 0.5cm;
			padding-bottom: 0.5cm;
		}
		.confirmation{
			padding-top: 20px;
		}
		#buttonId{
			visibility: hidden;
		}
		td,th{
			padding: 3px !important;
		}
		table{
			margin-bottom: 0 !important;
		}

	}
</style>
<div id="printThisDivIdOnButtonClick" class="mt-10">
	<div id="printablediv">
		<div class="mt-5">
		<?php if($papers[0]->karaundi_center =='Y'){ ?>
				<div class="alert" role="alert" style="color: #771d17;font-weight: bolder;font-size: 15px;background-color: #fcbe4b">
				<strong>आवश्यक सूचना :</strong> सूचित किया जाता है कि मई - जून 2025 में आयोजित होने वाली परीक्षाएं विश्वविद्यालय के मुख्यालय करौंदी जिला कटनी में आयोजित की जाएगी।
				</div>
			<?php }?>
			<label class="label_form label_heading "><b>Student Details</b></label>
			<div class="form-block row text-center">
				<div class="row col-md-10 m-auto">
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Form Number :</label>

					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $papers[0]->student_id; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Enrollment :</label>
					</div>
					<div class=" form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $papers[0]->enrollment_no; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Course Name:</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
				<?php echo $this->Common_model->getCourseNameByCourseId($papers[0]->course_group_id); ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Class Name:</label>
					</div>
					<div class="form-text-color form-group col-md-3 text-left m-auto">
					<?php echo $this->Common_model->getClassNameByClassId($papers[0]->class_id); ?>
					</div>

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Student Name :</label>
					</div>
					<div class="form-group form-text-color col-md-4 text-left m-auto">
				<?php echo $student['name']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">DOB:</label>
					</div>
					<div class="form-text-color form-group col-md-3 text-left m-auto">
						<?php echo date("d-m-Y", strtotime($student['dob']));?>
					</div>

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Email id :</label>
					</div>
					<div class="form-group col-md-4 text-left m-auto form-text-color">
						<?php echo $student['p_email']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Mobile No : </label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['p_mobile_no']; ?>
					</div>
				</div>
				<div class="row col-md-2">
					<img class="student_form_img" src="<?php echo base_url('/assets/student_image/').$student['session'].'/'.$student['photo'];?>">
				</div>
			</div>	
		</div>
	</div>
	<label class="label_form mt-5 label_heading"><b>Paper Details</b></label>
	<div class="form-block row ">
		<div class=" table-responsive">
			<table class="table " style="text-transform: uppercase;">
				<thead>
					<tr>
						<th>#</th>
						<th>Paper Code</th>
						<th>Paper Name</th>
					</tr>
				</thead>
                <tbody>
             <?php
            $i = 1;
            foreach($papers as $paper){
            ?>
            <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $paper->paper_code; ?></td>
            <td><?php 
              $paper_name  = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$paper->paper_code,'class_id'=>$paper->class_id));
             echo $paper_name[0]->paper_name;
            ?></td>
            </tr>
            <?php
        $i++;
        } ?>
           </tbody>
			</table>
		</div>
	    </div>
		<?php 
        if ($papers[0]->exam_form =='N'): ?>
	   <?php $backlog_student_id = $this->Common_model->encrypt_decrypt($papers[0]->backlog_student_id);
	   		$student_id = $this->Common_model->encrypt_decrypt($papers[0]->student_id);
	     $class_id = $this->Common_model->encrypt_decrypt($papers[0]->class_id);
		 $center_id =  $this->session->center_id;
		 $master = $this->Common_model->getSingleRow('master');
		 $remove_class_from_center =explode(',', $master->remove_class_from_center);
		 $center_permission = $this->Common_model->get_record('center','exam_form_permission,temp_exam_form,temp_admission_payment,payment_gateway_permission,balance',array('id'=>$center_id));
		$class_permission = $this->Common_model->get_record('class_master','backlog_exam_form_permission',array('id'=>$papers[0]->class_id));
	    ?>
			<div class="row justify-content-center mt-10">
			<?php 
				 $where = array(
					'course_group_id' => $papers[0]->course_group_id,
					'backlog_student_id' => $papers[0]->backlog_student_id,
					'status' => 'B'
				);
				$fees = $this->Common_model->getCountByWhere('backlog_exam_form',$where);
				if( $fees < 8){
					$exam_fees =$fees * 100;
				 }else{
					$exam_fees = 750; 
				 }

			if(($center_permission[0]['exam_form_permission']=='Y' && $papers[0]->exam_form=='N' ) &&  ($class_permission[0]['backlog_exam_form_permission']=='Y' || $center_permission[0]['temp_exam_form']=='Y') ){ 

                $center_ids = array( 10,11,12,13,21,22,23,24,25,26,27,28,29,1975,2098,2115 );
                if(in_array($this->session->center_id, $center_ids)){
            //         $where = array(
            //             'course_group_id' => $papers[0]->course_group_id,
            //             'backlog_student_id' => $papers[0]->backlog_student_id,
            //             'status' => 'B'
            //         );
            // $fees = $this->Common_model->getCountByWhere('backlog_exam_form',$where);
			?>
       <!-- <a class="btn btn-success" href="<?= base_url('paid_by_university_backlog/'.$backlog_student_id) ?>">Paid By University</a> -->
      
       <a href="#"  data-student_name = "<?=$student['name']?>"  data-idstudent="<?=$papers[0]->student_id?>" data-student_id="<?= $backlog_student_id?>" class="btn btn-primary btn-sm font-weight-bold pay1" data-toggle="modal" data-target="#kt_datepicker_modal" data-amount= "<?= $fees*100 ?>"  data-url="<?=base_url('paid_by_university_backlog/'.$backlog_student_id)?>" data-head='fees'>Paid By University</a>
			<?php
			}
			else if($center_permission[0]['payment_gateway_permission'] == 'N' && $center_permission[0]['balance'] >= $exam_fees){
				?>
				<div class="row d-flex justify-content-center p-3">
					<a href="#"   class="btn btn-primary btn-sm font-weight-bold pay2" data-toggle="modal" data-target="#left-modal" data-amount= "<?=$exam_fees;?>"  data-url="<?=base_url('backlog_paid_by_center/'.$papers[0]->backlog_student_id)?>" data-head='fees'>Paid By Balance</a>
				</div> 
				<?php
			}

			else if((!in_array($papers[0]->class_id,$remove_class_from_center)) || ( $center_permission[0]['temp_exam_form']  !='N') || ($center_permission[0]['payment_gateway_permission'] == 'N')) 
			{	?>	
				<a class="btn btn-success" href="<?= base_url('center/Payment/backlog_exam_form/'.$student_id .'/'. $class_id.'/'.$papers[0]->backlog_student_id.'/BACKLOG') ?>">Process To Payment</a>
			<?php } 
			} ?>
	  </div>
    <?php endif ?>
</div>
<!---------------------->
<div id="left-modal" class="modal fade" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md modal-right">
    <div class="modal-content modal_height">
      <div class="modal-header border-1">
        <h4 class="modal-title">Student Payment</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body" style="overflow-x:scroll;">
	  <div class=" col-sm-12 m-auto">
	<div class="row border border-primary bg-primary text-custom p-2">
		Billing Details
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Session
		</div>
		<div class="col-sm-8"><?=$student['session']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Form No
		</div>
		<div class="col-sm-8"><?=$student['student_id']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Name
		</div>
		<div class="col-sm-8"><?=$student['name']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Father / Husband Name
		</div>
		<div class="col-sm-8"><?=$student['f_h_name']?>
		</div>
	</div>
	
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Course / Class
		</div>
		<div class="col-sm-8"><?=$student['course_name']?> / <?=$this->Common_model->getClassNameByClassId($papers[0]->class_id)?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Mobile
		</div>
		<div class="col-sm-8"><?=$student['p_mobile_no']?>
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">Email
		</div>
		<div class="col-sm-8"><?=$student['p_email']?>
		</div>
	</div>
	<div class="row border border-primary bg-primary text-custom p-2">
		<div class="col-sm-4 border-right">Payment Details
		</div>
		<div class="col-sm-8">Amount
		</div>
	</div>
	<div class="row py-3 border">
		<div class="col-sm-4 border-right">
		Exam Fees 
		</div>
		<div class="col-sm-8" id="amount-center">
	</div>
		
	</div>
	<div class="row py-3 border justify-content-center">
	
	<a class="btn btn-default text-dark font-weight-bold" id="pay-center">Pay Now</a>
</div>
</div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!------------------------->
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
<form method="POST" class="d-block" id="ajaxForm" action="">
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
		
     <input class="form-control" type="date" name="payment_date"   id="payment_date" min="<?= date('Y-m-d', strtotime('-18 month')); ?>" max="<?= date('Y-m-d'); ?>"   />
	 <div class="text-danger" id="error"></div>
	 <input type="hidden" value="" name="student_id" id="student_id">
	 <input type="hidden" value="" name="idstudent" id="idstudent">
     <input type="hidden" value="" name="idstudent" id="url">
     <input type="hidden" value="" name="head" id="head">
    </div>
   </div>
   <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Required Amount</label>
    <div class="col-7">
     <input class="form-control" type="number" name="amount" id="amount" required readonly />
	 <div class="text-danger" id="error3"></div>
    </div>
   </div>
   <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Paid Amount</label>
    <div class="col-7">
     <input class="form-control" type="number" name="paid_amount" id="paid_amount" required />
	 <!-- <div class="text-danger" id="error3"></div> -->
    </div>
   </div>
   <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Receipt No.</label>
    <div class="col-7">
     <input class="form-control" type="text" name="receipt_number" id="receipt_number" required />
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
$(document).on('click','.pay2',function(){
	    // var name_csrf = $(this).attr('data-name_csrf');
	    // var hash_csrf = $(this).attr('data-hash_csrf');
	  
		var url = $(this).attr('data-url');
		const amount = $(this).attr('data-amount');
		
		$('#pay-center').attr('href',url);
		$('#amount-center').html(amount);
		
	});
$(document).on('click','.pay1',function(){
	    var name_csrf = $(this).attr('data-name_csrf');
	    var hash_csrf = $(this).attr('data-hash_csrf');
	    var student_id = $(this).attr('data-student_id');
	    var student_id = $(this).attr('data-student_id');
		var student_name = $(this).attr('data-student_name');
        var url = $(this).attr('data-url');
		var amount = $(this).attr('data-amount');
        var head = $(this).attr('data-head');
		$('#student_id').val(student_id);
        $('#url').val(url);
        $('#head').val(head);
        // $('#student_id_show').html(student_id);
		$('#student_name').html(student_name);
		$('#amount').val(amount);
		
	});

    $("#payment_submit").on('click',function (e){
        
    //    e.preventDefault();
	let center = "<?= ($papers[0]->karaundi_center =='Y')?'karaundi':'center';?>";
	var formimage = $('#ajaxForm');
	var frm = new FormData(formimage[0]);
		
        var student_id = $('#student_id').val();
		var payment_date = $('#payment_date').val();
		var payment_mode = $('#payment_mode').val();
		var amount = $('#amount').val();
        var paid_amount = $('#paid_amount').val();
        var transaction_number = $('#transaction_number').val();
		var receipt_number = $('#receipt_number').val();
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val(); 
        var uri = $('#url').val();
        var head1 = $('#head').val();
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
		// url: '<?php //echo site_url('center/center/update_unpaid_student'); ?>',
        url:uri,
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
            // if(head1 == 'fees'){
             window.location.href = "<?php echo base_url('backlog_exam_form_students/notSubmitted/'); ?>"+center;
            //     window.location.href
            //     window.location.assign("<?php// echo base_url('exam_form_students'); ?>")
            // }else{
                // location.reload();
            // }
			
			
		
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
    
</script>

