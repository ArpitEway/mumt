<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/admit_card.css?token=?token='.date('dmyhis'))?>">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" />
    <title>Admit Card</title>
	    <!--[CSS/JS Files - Start]-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
    <script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
  
    <style>
		.table-bordered td, .table-bordered th, .table thead th {
    	font-size: 16px;
		}
	</style>
  </head>
  <body>
<section>
	<div class="">
	<div  id="container_content"  style="margin: auto; width:1150px;">
		<div class="admit-card">
			<div class="BoxA border- padding mar-bot"> 
				<div class="row justify-content-center align-items-center">
					<div class="col-3 txt-center">
						<img src="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" width="100px;" />
					</div>
					<div class="col-9">
						<img src="<?=base_url()?>assets/images/maskgroup/Group1.png" class="img2" alt="">
					</div>
				</div>
			</div>
			
			<div class="BoxC border- padding mar-bot">
				<div class="row">
					<div class="col-12 text-center">
						<h5>Schedule of Exam for Annual/Semester Backlog Examination of December 2025<?php 

						// <?= (in_array($student[0]->class_id,array(137,149,183,185,191,138,184,192,187,143,146,139,144,188,145,147,148,150,186,141,151,142,190,264,140,189,262,268,270,256,258,260,317,173,174,175,177,180,300,301)))?'July':'June'?>
						// // echo (in_array($student[0]->class_id, array(300,301,255,257,259)))?'February':'January'?> 
								<?php
								/*
								if($student[0]->course_group_id==75 || $student[0]->course_group_id==76 || $student[0]->course_group_id==77)
								{
									if($student[0]->class_id==256 || $student[0]->class_id==258 ||  $student[0]->class_id==260 || $student[0]->class_id==262 )
									echo 'July 2024';
									else
									echo '2024';
								}elseif($student[0]->course_group_id==80 || $student[0]->course_group_id==45 ){
									echo 'August 2024';
								}else{
								echo 'June 2024';
							}
							*/
							?> 
						</h5>
					</div>
				</div>
			</div>
			<?php 
			$where = array('id' => $student[0]->exam_center_id);
				$exam = $this->Common_model->getRecordByWhere('exam_center',$where); ?>
			<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							<input type="hidden" value="<?php echo $student[0]->student_id ; ?>" id="student_id">
						  <tbody>
						  <?php 
							  // 169 MDE165 MMYVV UTD KAROUNDI
							  // 167 MDE163 Nachiketa Collage of computer science commerce & Advanced Technology
							  if($student[0]->course_group_id==75 || $student[0]->course_group_id==76 || $student[0]->course_group_id==77 || $student[0]->exam_center_id==169  || $student[0]->exam_center_id==167 ){ ?>
							<tr>
							  <td colspan="4"><b>Exam Center: </b>
							  <?php echo $exam[0]->schoolcollegename.', '.$exam[0]->examcenteraddress;
							  //.', '.$exam[0]->city;
							  //$this->Common_model->getExamCenterNameById($student[0]->exam_center_id); ?></td>
							</tr>
							<?php }  ?>
							<!-- <tr>
							  <td colspan="4"><b>Exam Center: </b><?php // $this->Common_model->getExamCenterNameById($student[0]->exam_center_id); ?></td>
							</tr> -->
							<!-- 	<tr>
							  <td colspan="4"><b>College: </b><?=$student[0]->center_name; ?></td>
							    </tr> -->
							<tr>
								<td><b>Roll No: </b> <?=$student[0]->roll_no;?></td>
								<td colspan="2"><b>Enrollment No: </b><?=$student[0]->enrollment_no;?></td>
								 <td rowspan="4"><img src="<?=base_url('assets/student_image/'.$student[0]->session.'/'.$student[0]->photo);?>"  width="115px" height="166px" /></td> 
							</tr>
							<tr>
							  <td><b>Course: </b> <?= $this->Common_model->getCourseNameByCourseId($student[0]->course_group_id);?> ( <?= $this->Common_model->getClassNameByClassId($student[0]->class_id);?> )</td>
							  <td colspan="2"><b>EC Code: </b><?=$exam[0]->exam_center_user;?></td>
							</tr>
							<tr>
							  <td colspan="3"><b>Student Name: </b> <?=$student[0]->name;?></td>
							</tr>
							<tr>
								<td colspan="3"><b>Father/Husband Name: </b> <?=$student[0]->f_h_name;?></td>
							</tr>
							
						  </tbody>
						</table>
					</div>
				</div>
			</div>
<!-- 			<div class="BoxE border- padding mar-bot txt-center">
				<div class="row">
					<div class="col-12">
						<h5>EXAMINATION VENUE</h5>
						<p>NH - 79 Gangrar Chittorgarh - 312901 <br> RAJASTHAN, INDIA</p>
					</div>
				</div>
			</div> -->
			<div class="BoxF border- padding mar-bot txt-center">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th >Paper Name</th>
									<!-- <th>प्रश्न पत्र अपलोड करने की तिथि </th>
									<th>उत्तर पुस्तिका जमा / अपलोड करने की अंतिम तिथि</th> -->
									<th>Time</th>
								</tr>
							</thead>
						  <tbody>
						  <?php
						  $i = 1;
						  $paper_count = count($papers);
						  $pvtClasses = [104,107,134];
			foreach($papers as $paper){
				?>
				<tr>
					<td><?php echo $i ; ?></td>
					<td><?php echo date("d-m-Y", strtotime(($student[0]->mode == 'PVT' && in_array($student[0]->class_id,$pvtClasses))?$paper->pvt_exam_date:$paper->exam_date)); ?></td>
					
					<td style="text-align:left;"><?php 
					$st_arr=array(776229,776268,776212);
					if($paper->paper_code=='1RBPED2' && in_array($student[0]->student_id,$st_arr)){
						echo 'History, Principles and Foundation of Physical Education';
					}else {echo $paper->paper_name; } ?></td>
					<!-- <?php //if ($i==1): ?>
						
					<td rowspan="<?=$paper_count?>" style="vertical-align: middle;"><?php echo  $this->Common_model->viewDate($paper->exam_start_date); ?></td>
					<td rowspan="<?=$paper_count?>" style="vertical-align: middle;"><?php echo $this->Common_model->viewDate($paper->exam_end_date); ?></td>
					
					<?php //endif ?> -->
					<!-- <td><?= ($paper->exam_shift=='मध्याह्न') ? '१२:०० से ०३:०० बजे तक' : ''; ?></td> -->
					<td><?php
					$class_ids = array(104,101,107,110,116,119,273,125,128,131,134,162,163,164,165,283,285,287,289,310,291,293,295,274,297,168,169,170,171,214,106,103,109,112,118,121,127,130,133,136,264,137,149,183,185,191,138,184,192,187,143,146,139,144,188,145,147,148,150,186,141,151,142,190,140,189,173,174,175,177,180);
						if($student[0]->mode == 'PVT' && in_array($student[0]->class_id,$pvtClasses)){
							if($paper->pvt_exam_shift=='Afternoon' && in_array($student[0]->class_id,$class_ids)){
									echo '3:00 PM To 6:00 PM';		
							}
							elseif($paper->pvt_exam_shift=='Afternoon'){
									echo '2:00 PM To 5:00 PM';
							}
							elseif($paper->pvt_exam_shift=='Morning' ){
									echo '10:00 AM To 1:00 PM';
							}
						}else{
							if($paper->exam_shift=='Afternoon' && in_array($student[0]->class_id,$class_ids)){
								echo '3:00 PM To 6:00 PM';		
							}
							elseif($paper->exam_shift=='Afternoon'){
								echo '2:00 PM To 5:00 PM';
							}
							elseif($paper->exam_shift=='Morning' ){
								 echo '10:00 AM To 1:00 PM';
							}
						}	
	
					?></td>
			</tr>
			<?php 
			$i++;
		}
		?>
						</tbody>
						</table>
					</div>
				</div>
			</div>
<!--
<div class="text-right">
						<img src="<?=base_url('assets/images/Signature.png')?>" class="Signature">
						<p class="font-weight-bold mb-4 mr-5">कुलसचिव</p>
					</div>

			<!-- <div class="BoxF border- padding mar-bot txt-center">
				<div class="row">
					<div class="col-12 text-justify">
						<p><strong>परीक्षा हेतु निर्देश</strong><br><br>

1. समस्त प्रश्नपत्र उक्त दर्शाई गई तिथि पर प्रातः 11:00 बजे विश्वविद्यालय की बेवसाइट पर एक साथ अपलोड किए जाऐंगे। छात्र  Student Login  के माध्यम से प्रश्न पत्र डाउनलोड कर उनके उत्तर लिखकर प्रश्नपत्र वार उत्तर पुस्तिका को स्कैन कर उसकी  PDF फाईल (अधिकतम साईज 5 MB)  को उक्त निर्धारित अंतिम तिथि तक अपलोड करेंगे| उत्तर पुस्तिका की PDF फाइल को निर्धारित स्थान पर/तिथि तक अपलोड नहीं करने पर छात्र/छात्रा  को अनुपस्थित माना जायेगा।</br>

2. परीक्षार्थी स्वयं के पास उपलब्ध रजिस्टर के कागज/ए-4 आकार के कागज की उत्तर पुस्तिका बना कर उत्तर लिखेंगें। उत्तर पुस्तिका के प्रत्येक पृष्ठ पर ऊपर दांई ओर पृष्ठ क्रमांक अवश्य लिखें। </br>

3. विश्वविद्यालय द्वारा जारी किया गया उत्तर पुस्तिका का प्रथम पृष्ठ के प्रारुप को परीक्षार्थी प्रत्येक उत्तर पुस्तिका के प्रथम पृष्ठ में संलग्न करेंगे अथवा दिये गये प्रारूप अनुसार हस्तलिखित बनायेगें। </br>

4. परीक्षार्थी उत्तर पुस्तिका के प्रथम पृष्ठ में स्वयं का रोल नंबर/नामांकन/पंजीयन-क्रमांक/विषय/प्रश्न पत्र का नाम/प्रश्नपत्र क्रमांक/उत्तर पुस्तिका के हस्तलिखित पृष्ठों की संख्या अनिवार्य रूप से निर्धारित स्थान पर ही लिखेगें। </br>

5. परीक्षार्थी उत्तर लिखने के लिये केवल नीले पेन का ही उपयोग करे। </br>

6. परीक्षार्थियों को ओपन बुक परीक्षा प्रणाली में समस्त विषयों की प्रश्नपत्रवार पृथक-पृथक उत्तर पुस्तिकाऐं लिखना अनिवार्य होगा। उत्तर पुस्तिका स्वलिखित होनी चाहिए। उत्तर पुस्तिका स्वलिखित नहीं होने की स्थिति में परीक्षा निरस्त करने की कार्यवाही की जायेगी। </br>

7. सभी प्रश्नपत्रों में अधिकतम प्रश्नों की सख्या पाँच होगी एवं सभी प्रश्नों के अंक समान होंगे। प्रत्येक प्रश्नों के उत्तर की शब्द सीमा अधिकतम 250 शब्दों की होगी। </br>

8. उक्त परीक्षा में केवल वे ही छात्र समिलित होगे जिनके द्वारा परीक्षा आवेदन पत्र भरा गया है। </br>


					
					</div>
				</div>
			</div> -->
			<footer class="txt-center">
				<!-- <p>*** महर्षि महेश योगी वैदिक विश्वविद्यालय***</p> -->
				<strong>This is a computer-generated document. No signature is required</strong>
			</footer>
			
		</div>
	</div>
<div class="text-center">
    <input type="button" id="rep" value="Download" class="btn btn-primary btn_print mb-5">
</div>
</section>
    
 

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
	<script>
	$(document).ready(function($) 
    { 

        $(document).on('click', '.btn_print', function(event) 
        {
            event.preventDefault();
            var element = document.getElementById('container_content'); 
            var student_id = document.getElementById('student_id').value;
            //more custom settings
            var opt = 
            {
             
              filename:     'admit_card_'+student_id+'.pdf',
              image:        { type: 'jpeg', quality: 0.98 },
              html2canvas:  { scale: 2, width: 1150,height: 1600 },
              jsPDF:        { unit: 'in', format: 'A4', orientation: 'portrait' }
            };
            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();  
        });
	});
	</script>
  </body>
</html>