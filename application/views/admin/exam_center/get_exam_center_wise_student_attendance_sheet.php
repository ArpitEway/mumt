<!doctype html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/admit_card.css?token=?token='.date('dmyhis'))?>">
    <link rel="shortcut icon" href="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png" />
    <title>Admit Card</title>
	   
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
    <script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
  
    <style>
		 .break{
        page-break-before: always;
    }
    @page {
      size: auto;
  }

	</style>
  </head>
  <body>
 <?php foreach($exam_center_students as $student)  { 
     $wherePaper = array('student_id' => $student->student_id,'paper_master.type'=>'theory','exam_date!='=>'0000-00-00','exam_date!='=>'' );
	 
     $this->db->select('*');
     $this->db->from('paper_master');
     $this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
     //$this->db->join('time_table', 'paper_master.class_id = time_table.class_id');
     $this->db->where($wherePaper);
     $this->db->order_by("exam_date", "asc");
     $this->db->order_by("exam_shift", "desc");
     $papers = $this->db->get()->result();
	 $paper_count = count($papers);
	 if($paper_count){
     ?>   
<section>
	<div class="break">
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
						<h5>Attendance Sheet Examination AUGUST 2022</h5>
						
					</div>
				</div>
			</div>
			<?php 
			$where = array(
			
			'id' => $student->exam_center_id,
			);
				$exam = $this->Common_model->getRecordByWhere('exam_center',$where); ?>
			<div class="BoxC border- padding mar-bot">
				<div class="row">
					<div class="col-12 text-center">
						<h5>Exam Center Code: <b><?=$student->examcentercode;?> </b> Exam Center: <?=$exam[0]->schoolcollegename;?> </h5>
					</div>
				</div>
			</div>
			<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							<input type="hidden" value="<?php echo $student->student_id ; ?>" id="student_id">
						  <tbody>
							<!-- <tr>
							  <td colspan="4"><b>Exam Center: </b><?= $this->Common_model->getCenterNameById($student->id); ?></td>
							</tr>
								<tr>
							  <td colspan="4"><b>College: </b><?=$student->center_name; ?></td>
							    </tr> -->
								<tr>	<th class="td" colspan="4">Student Details</th></tr>
							<tr>
								<td><b>Roll No: </b> <?=$student->roll_no;?></td>
								<td colspan="2"><b>Enrollment No: </b><?=$student->enrollment_no;?></td>
								 <td rowspan="4"><img src="<?=base_url('assets/student_image/'.$student->photo);?>"  width="115px" height="166px" /></td> 
							</tr>
							<tr>
							  <td colspan="2"><b>Student Name: </b> <?=$student->name;?></td>
							</tr>
							<tr>  
							  <td colspan="2"><b>Father/Husband Name: </b> <?=$student->f_h_name;?></td>
							</tr>
							<tr>
							  <td colspan="2"><b>Course: </b> <?=$student->course_name;?> (<?=$student->class_name;?>) </td>
							</tr>
							<!-- <tr>
								<td><b>Mode:</b> Regular</td>
								<td class="border border-dark" colspan="2"> <b>Mobile No. :</b> <?php 	$mobile_no =$this->Common_model->getMobileNoByStudentID($student->student_id);	?><?= $mobile_no ?> </td>
							</tr> -->
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
									<th rowspan="02" width="1%" >#</th>
									<th rowspan="02" width="11%">Date</th>
									<th rowspan="02" width="11%">Time</th>
									<th rowspan="02" width="22%">Paper Name</th>
									<th rowspan="02" width="18%">Answer Sheet No.</th>
									<th colspan="02" width="37%">Signature</th>
									<!-- <th>प्रश्न पत्र अपलोड करने की तिथि </th>
									<th>उत्तर पुस्तिका जमा / अपलोड करने की अंतिम तिथि</th> -->
									
								</tr>
								<tr >
									<th width="18%">Student</th>
									<th width="18%">Invigilator</th>
								</tr>
							</thead>
						  <tbody>
						  <?php
						  $i = 1;
						
			foreach($papers as $paper){
				?>
				<tr>
					<td><?php echo $i ; ?></td>
					<td><?php echo date("d-m-Y", strtotime($paper->exam_date)); ?></td>
					<td><?= ($paper->exam_shift=='Morning') ? '11:00 AM To 2:00 PM' : '03:00 PM To 6:00 PM'; ?></td>
					<td style="text-align:left;"><?php echo $paper->paper_name; ?></td>
					<td ></td>
					<td ></td>
					<td ></td>
					<!-- <?php //if ($i==1): ?>
						
					<td rowspan="<?=$paper_count?>" style="vertical-align: middle;"><?php echo  $this->Common_model->viewDate($paper->exam_start_date); ?></td>
					<td rowspan="<?=$paper_count?>" style="vertical-align: middle;"><?php echo $this->Common_model->viewDate($paper->exam_end_date); ?></td>
					
					<?php //endif ?> -->
					<!-- <td><?= ($paper->exam_shift=='मध्याह्न') ? '१२:०० से ०३:०० बजे तक' : ''; ?></td> -->
					
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


			<div style="style:100%;margin-top:5px; height:auto;">
				<div style="float:left;width:50%; height:auto;" align="left">
					<p><strong>Principle</strong></p>
					<p><strong>Name</strong></p>
					<p><strong>Signature</strong></p>
				</div>
				<div style="float:left;width:50%; height:auto;" align="left">
					<p><strong>Exam Center Superintendent</strong></p>
					<p><strong>Name</strong></p>
					<p><strong>Seal &amp; Signature</strong></p>
				</div>
			</div>

			
			
		</div>
	</div>
<!-- <div class="text-center">
    <input type="button" id="rep" value="Download" class="btn btn-primary btn_print mb-5">
</div> -->
</section>
    
 <?php } } ?>
<!-- 
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
	</script> -->
  </body>
</html>