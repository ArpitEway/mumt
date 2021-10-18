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
		<div class="form-block row text-center d-block" id="printHeaderdiv">
			<div class="f-heading-1 text-primary">
				Maharshi Panini Sanskrit Evam Vedic Vishwavidyalaya Ujjain
			</div>
			<div class="f-heading-3 text-primary">
				Application Form For Admission <span class="text-dark"><?= $student['session']; ?></span>
			</div>
		</div>


		<div class="mt-5">
			<label class="label_form label_heading "><b>Student details</b></label>
			<div class="form-block row text-center">

				<div class="row col-md-10 m-auto">

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Form Number :</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $student['form_no']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Enrollment :</label>
					</div>
					<div class=" form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $student['enrollment_no']; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Course Name:</label>
					</div>
					<div class="form-text-color form-group col-md-4 text-left m-auto">
						<?php echo $student['course_name']; ?>
					</div>

					<div class="form-group col-md-2 text-left m-auto">
						<label class="label_form">Class Name:</label>
					</div>
					<div class="form-text-color form-group col-md-3 text-left m-auto">
						<?php echo $student['class_name']; ?>
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
					<img class="student_form_img" src="<?php echo base_url('/assets/student_image/').$student['photo'];?>"></img>
				</div>	

			</div>

			<label class="label_form mt-5 label_heading"><b>Personal Information</b></label>
			<div class="form-block row text-center">

				<div class="row col-md-12 m-auto">


					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Father/Husband Name :</label>
					</div>
					<div class="form-group col-md-3 form-text-color text-left m-auto">
						<?php echo $student['f_h_name']; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Mother Name :</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['mother_name']; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Gender :</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['gender']; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Category :</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['category']; ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Aadhar number:</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['adhar_no']; ?>
					</div>

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Samagra ID:</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['sm_id']; ?>
					</div>

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Handicaped : </label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo ($student['p_handicapped']=='') ? 'N0' : 'YES' ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Other Mobile No : </label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['f_h_mobile_no'] ?>
					</div>
					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">Address :</label>
					</div>
					<div class="form-group col-md-9 text-left m-auto form-text-color">
						<?php 
						echo $student['p_address'] .", ".$student['p_city'].", (".$student['p_pin_code'].")" ; ?>
					</div>

					<div class="form-group col-md-3 text-left m-auto"> 
						<label class="label_form">State :</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php 
						echo $this->Common_model->getState($student['p_state']); ?>
					</div>

					<div class="form-group col-md-3 text-left m-auto">
						<label class="label_form">District :</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php 
						echo $this->Common_model->getDistrict($student['p_district']);
						?>
					</div>


				</div>

			</div>

<?php if(!$this->session->has_userdata('centerdata')){ ?>
			<label class="label_form mt-5 label_heading"><b>Educational Detail</b></label>

			<div class="form-block row ">

				<div class=" table-responsive">
					<table class="table " style="text-transform: uppercase;">

						<thead>
							<tr>
								<th>Qualifying Exam</th>
								<th>Board / University Name</th>
								<th>Year of Passing</th>
								<th>Maximum Marks</th>
								<th>Obtain Marks</th>
							</tr>
						</thead>

						<tbody>

							<tr>

								<td class="form-text-color" ><?php echo 'HS'; ?></td>
								<td class="form-text-color"><?php echo $student['ten_board']; ?></td>
								<td class="form-text-color" ><?php echo $student['ten_year']; ?></td>
								<td class="form-text-color" ><?php echo $student['ten_total_marks']; ?></td>
								<td class="form-text-color"><?php echo $student['ten_marks']; ?></td>

							</tr>

							<tr>

								<td class="form-text-color"><?php echo 'HSSC'; ?></td>
								<td class="form-text-color"><?php echo $student['twowelth_board']; ?></td>
								<td class="form-text-color"><?php echo $student['twowelth_year']; ?></td>
								<td class="form-text-color"><?php echo $student['twowelth_total_marks']; ?></td>
								<td class="form-text-color"><?php echo $student['twowelth_marks']; ?></td>

							</tr>
							<?php if ($student['graduation_university']!='') {
?>
							<tr>

								<td class="form-text-color"><?php echo 'Graduation'; ?></td>
								<td class="form-text-color"><?php echo $student['graduation_university']; ?></td>
								<td class="form-text-color"><?php echo $student['graduation_year']; ?></td>
								<td class="form-text-color"><?php echo $student['graduation_total_marks']; ?></td>
								<td class="form-text-color"><?php echo $student['graduation_marks']; ?></td>
							</tr>
<?php 
							} ?>
							<?php if ($student['pg_university']!='') {
?>
							<tr>

								<td class="form-text-color"><?php echo 'Post Graduation'; ?></td>
								<td class="form-text-color"><?php echo $student['pg_university']; ?></td>
								<td class="form-text-color"><?php echo $student['pg_year']; ?></td>
								<td class="form-text-color"><?php echo $student['pg_total_marks']; ?></td>
								<td class="form-text-color"><?php echo $student['pg_marks']; ?></td>
							</tr>
<?php 
							} ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php
		}
			if (isset($payment_details) && count($payment_details)>0) {
				?>
			<label class="label_form mt-5 label_heading "><b>Other detail</b></label>
			<div class="form-block row other-detail">
				<div class="row">
					<div class="col-md-12 ">
						<label class="label_form">क्या अध्ययन में अन्तराल रहा है यदि हाँ, तो उसका विवरण (शपथ पत्र) संलग्न करें।  :</label><strong><?php echo $student['gap_certificate']; ?></strong>
					</div>

					<div class="col-md-12"> 
						<label class="label_form">क्या आवेदक सेवारत है यदि हाँ तो नियोक्ता का विवरण दें।  :</label><strong><?php echo $student['retirement']; ?></strong>
					</div>
					<div class="col-md-12 ">
						<label class="label_form">क्या आवेदक के विरुद्ध पूर्व में अनुशासनहीनता/अपराधिक कृत्य के विरुद्ध पुलिस थाने में प्रकरण दर्ज है अथवा न्यायालय / विद्यालय/महाविद्यालय द्वारा कोई कार्यवाही की गई ? यदि हाँ तो विवरण संलग्न करें।  :</label><strong><?php echo $student['complaint']; ?></strong>
					</div>
				</div>

			</div>
			

					<label class="label_form mt-5 label_heading"><b>Payment information</b></label>

					<div class="form-block">
						<?php
						$i=0;
				foreach ($payment_details as $payment) {
					$i++;
					?>
						<div class="row p-0">
							<div class="form-group col-md-1 mb-0">
								<label class="label_form"><?=$i?>.</label>
							</div>
							<div class="form-group col-md-2 mb-0 text-uppercase">
								<label class="label_form"><?=$payment['fees_head']?></label>
							</div>
							<div class="form-group col-md-2 mb-0">
								<label class="label_form">Amount : </label> <?php echo $payment['amount']; ?>
							</div>
							<div class="form-group col-md-2 mb-0">
								<label class="label_form">Date : </label> <?=$this->Common_model->viewDate($payment['payment_date']);?>
							</div>
							<div class="form-group col-md-5 mb-0 ">
								<label class="label_form"><?= ($payment['SabPaisaTxId']=='') ? 'Payment' : 'SabPaisa id' ?> : </label> <?= ($payment['SabPaisaTxId']=='') ? $payment['payment_status'] : $payment['SabPaisaTxId'] ?>
							</div>
						</div>
						<?php } ?>
					</div>
				
			<div class="row  mt-10 " >
				<div class="col-md-12">

					<div class="form-group col-md-12 text-center confirmation">
						<label class="label_form label_heading">आवेदक का घोषणा-पत्र</label>
					</div>
					<div class="form-group col-md-12 text-justify" style="padding: 0px;">
						<label class="label_form label_heading" > मैं प्रवेश के लिए प्रार्थना करता हूँ/करती हूँ तथा यह वचन देता हूँ/देती हूँ कि मैं रैगिंग विरोधी नियमों के साथ विश्वविद्यालय के नियमों का पालन करूंगा/करूँगी तथा कक्षाओं में न्यूनतम 75% उपस्थिति सुनिश्चित करूंगा/करूँगी. मेरे द्वारा दी गयी जानकारी गलत पायी जाती है तो मेरा प्रवेश निरस्त कर दिया जाये।
						</label>
					</div>

				</div>
			</div>

			<div class="text-right label_heading signature" style="margin-top:50px !important;">
				आवेदक के हस्ताक्षर
			</div>
			<hr class="mt-10">


			<div class="row text-center mt-10" style="padding: 0px;">

				<div class="row col-md-12 m-auto">

					<div class="form-group col-md-12 text-center m-auto label_heading">
						<label class="label_form">प्रवेश समिति की अनुशंसा</label>
					</div>

					<div class="form-group col-md-12 text-justify m-auto label_heading" style="padding: 0px;">
						<label class="label_form">आवेदक द्वारा प्रस्तुत आवेदन पत्र का सम्यक परीक्षण कर लिया गया है। आवेदक ने सभी आवश्यक जानकारियों की पूर्ति कर दी है तथा सभी आवश्यक दस्तावेज संलग्न कर दिये हैं। आवेदक को निम्नलिखित पाठ्यक्रम में प्रवेश दिया जाये।
						</label>
					</div>

				</div>


			</div>

			<div class=" row col-md-12  mt-10 label_heading cls" >
				पाठक्रम (कक्षा) का नाम : <?php echo $student['course_name']; ?>
			</div>

			<div class="row ">
				<div class="col-md-4 label_heading cls" style="margin-top:70px !important;">
					दिनांक : 
				</div>
				<div class="col-md-4 label_heading cls" style="margin-top:70px !important;">
					प्रवेश समिति सदस्य/विभाग समन्वयक के हस्ताक्षर
				</div>

				<div class="col-md-4 text-right label_heading" style="margin-top:70px !important;">
					प्रवेश समिति के अध्यक्ष/विभागाध्यक्ष के हस्ताक्षर एवं पदमुद्रा
				</div>

			</div>

			<hr class="mt-10">
	
<div class="text-center mt-10">
	<a id="buttonId" class="btn btn-primary font-weight-bold" href="#">Print</a>
</div>
<?php	}	?>
</div>
</div>
</div>
<script>
	$('#buttonId').on('click', function () {
		window.print();
	});
</script>