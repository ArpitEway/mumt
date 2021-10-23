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
					<img class="student_form_img" src="<?php echo base_url('/assets/student_image/').$student['session'].'/'.$student['photo'];?>"></img>
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
						<label class="label_form">Marital Status:</label>
					</div>
					<div class="form-group col-md-3 text-left m-auto form-text-color">
						<?php echo $student['marital_status']; ?>
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

								<td class="form-text-color" ><?php echo $student['eligibility']; ?></td>
								<td class="form-text-color"><?php echo $student['board']; ?></td>
								<td class="form-text-color" ><?php echo $student['passing_year']; ?></td>
								<td class="form-text-color" ><?php echo $student['total_marks']; ?></td>
								<td class="form-text-color"><?php echo $student['marks']; ?></td>

							</tr>
						</tbody>
					</table>
				</div>
			</div>

				


				<hr class="mt-10">

				<div class="text-center mt-10">
					<a id="buttonId" class="btn btn-primary font-weight-bold" href="#">Print</a>
				</div>
		</div>
		</div>
		</div>
<script>
	$('#buttonId').on('click', function () {
		window.print();
	});
</script>