<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/admit_card.css')?>">
    <style>
		 .break{
        page-break-before: always;
    }
    @page {
    	size: auto;
    }
    table img {
    	max-width: 150px;
    	width: 100%;
    }
    .papertable tr th,td{
    	vertical-align: middle !important;
    }
    .papertable tr td{
    	padding: 10.2px !important;
    }
    .table thead th, .table thead td {
    	font-size: 16px;
    }
    .admit-card {
    	margin: 10px auto;
    }
    .padding{
    	padding: 10px;
    }
          @media print {
		.offcanvas-footer.text-center.p-3{
			display: none;
		}
		}
	</style>
 <?php 
 foreach($exam_center_students as $student)  {
 	$classIdsRegOnly = array(104, 107, 134);
	if($student['university_mode'] =="PVT" && in_array($student['class_id'], $classIdsRegOnly)){
		 $wherePaper = array('student_id' => $student['student_id'],'paper_master.type'=>'theory','pvt_exam_date!='=>'0000-00-00','paper_master.course_group_id'=> $student['course_group_id'],'paper_master.class_id'=> $student['class_id']);
	 
	}else{
		 $wherePaper = array('student_id' => $student['student_id'],'paper_master.type'=>'theory','exam_date!='=>'0000-00-00' ,'paper_master.course_group_id'=> $student['course_group_id'],'paper_master.class_id'=> $student['class_id']);
	 
	}
    
     $this->db->select('*');
     $this->db->from('paper_master');
     $this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
     //$this->db->join('time_table', 'paper_master.class_id = time_table.class_id');
     $this->db->where($wherePaper);
	 if($student['university_mode'] =="PVT" && in_array($student['class_id'], $classIdsRegOnly)){
		 $this->db->order_by("pvt_exam_date", "asc");
     	 $this->db->order_by("pvt_exam_shift", "desc");
	 }else{
		 $this->db->order_by("exam_date", "asc");
     	 $this->db->order_by("exam_shift", "desc");
	 }
    
     $papers = $this->db->get()->result();
	 $paper_count = count($papers);
	 if($paper_count){

		  $newstring = "252".substr($student['center_code'], -4); 
     ?>  
<!-- <div id="ss">       -->
<section class="break" style="font-size: 16px;" >
		<div id="container_content" class="admit-card" style="width:1030px !important; ">
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
			
			<div class="BoxC border- padding">
				<div class="row">
					<div class="col-12 text-center">
						<h5>
							ATTENDANCE SHEET FOR ANNUAL/SEMESTER EXAMINATION OF JANUARY 2026
						</h5>

					</div>
				</div>
			</div>
			<div style="height: 8px;text-align: right;">
				<span style="font-size:8px;margin:2px;"><?=$newstring?></span>
			</div>
			<?php 
			$where = array('id' => $student['exam_center_id']);
				$exam = $this->Common_model->getRecordByWhere('exam_center',$where); ?>
			<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							<input type="hidden" value="<?php echo $student['student_id'] ; ?>" id="student_id">
						  <tbody>
						  <?php 
							  // 169 MDE165 MMYVV UTD KAROUNDI
							  // 178 MDE172 Nachiketa Collage of computer science commerce & Advanced Technology
							  if($student['course_group_id']==75 || $student['course_group_id']==76 || $student['course_group_id']==77 || $student['exam_center_id']==169 || $student['exam_center_id']==167){ ?>
							<tr>
							  <td colspan="4"><b>Exam Center: </b>
							  <?php echo $exam[0]->schoolcollegename.', '.$exam[0]->examcenteraddress;
							  //.', '.$exam[0]->city;
							  //$this->Common_model->getExamCenterNameById($student[0]->exam_center_id); ?></td>
							</tr>
							<?php }  ?>
							<!-- <tr>
							  <td colspan="4"><b>Exam Center: </b><?= $this->Common_model->getCenterNameById($student['id']); ?></td>
							</tr>
								<tr>
							  <td colspan="4"><b>College: </b><?=$student['center_name']; ?></td>
							    </tr> -->
								<tr>	<th class="td" colspan="4">Student Details</th></tr>
							<tr>
								<td><b>Roll No: </b> <?=$student['roll_no'];?></td>
								<td colspan="2"><b>Enrollment No: </b><?=$student['enrollment_no'];?></td>
								<?php $img_url = (file_exists(FCPATH.'assets/student_image/'.$student['session'].'/'.$student['photo'])) ? base_url('assets/student_image/'.$student['session'].'/'.$student['photo']) : base_url('assets/images/center/student.bmp'); ?>
								 <td rowspan="4" class="text-center"><img src="<?=$img_url;?>"  width="115px" height="166px" /></td> 
							</tr>
							<tr>
							  <td><b>Course: </b> <?=$student['course_name'];?> (<?=$student['class_name'];?>) </td>
							  <td colspan="2"><b>EC Code: </b> <?=$exam[0]->exam_center_user;?></td>
							</tr>
							<tr>
							  <td colspan="3"><b>Student Name: </b> <?=$student['name'];?></td>
							</tr>
							<tr>  
							  <td colspan="3"><b>Father/Husband Name: </b> <?=$student['f_h_name'];?></td>
							</tr>
							
						  </tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="BoxF border- padding mar-bot txt-center">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered papertable nowrap" >
							<thead>
								<tr >
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
					$class_ids = array(193,197,199,201,203,205,207,209,211,213,221,223,225,227,261,263,267,269,275,279,302,460,476);	
			foreach($papers as $paper){
				?>
				<tr>
					<td><?php echo $i ; ?></td>
					<?php if($student['university_mode'] =="PVT" && in_array($student['class_id'], $classIdsRegOnly)){
						?>
						<td><?php echo date("d-m-Y", strtotime($paper->pvt_exam_date)); ?></td>
					<td>
					<?php 
					if($paper->pvt_exam_shift=='Afternoon' && in_array($student['class_id'],$class_ids) ){
						echo '2:00 PM To 5:00 PM';
					}
					elseif($paper->pvt_exam_shift=='Afternoon'){
						echo '2:00 PM To 5:00 PM';
					}
					elseif($paper->pvt_exam_shift=='Morning'){
						echo '10:00 AM To 1:00 PM';
					}?>
					</td>
						<?php
					}else{
						?>
						<td><?php echo date("d-m-Y", strtotime($paper->exam_date)); ?></td>
					<td>
					<?php 
					if($paper->exam_shift=='Afternoon' && in_array($student['class_id'],$class_ids) ){
						echo '12:00 PM To 03:00 PM';
					}
					elseif($paper->exam_shift=='Afternoon'){
						echo '2:00 PM To 5:00 PM';
					}
					elseif($paper->exam_shift=='Morning'){
						echo '10:00 AM To 1:00 PM';
					}?>
					</td>
						<?php
					}
					?>
					<td style="text-align:left;"><?php echo $paper->paper_name; ?></td>
					<td ></td>
					<td ></td>
					<td ></td>
					
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


			<div style="style:100%;margin-top:30px; height:auto;min-height: 150px;">
				<div style="float:left;width:50%; height:auto;" align="left">
					<p><strong></strong></p>
					<p><strong></strong></p>
					<p><strong></strong></p>
				</div>
				<div style="float:left;width:50%; height:auto;" align="left">
					<p><strong></strong></p>
					<p><strong></strong></p>
					<p><strong>Seal &amp; Signature</strong></p>
				</div>
			</div>
		</section>
    
 <?php }  }  ?>

 <div class="text-center">
    <input type="button" id="rep" value="Download" class="btn btn-primary btn_print mb-5">
</div>

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
             
              filename:     'attendance_sheet_'+student_id+'.pdf',
              image:        { type: 'jpeg', quality: 0.98 },
              html2canvas:  { scale: 2, width: 1150,height: 1600 },
              jsPDF:        { unit: 'in', format: 'A4', orientation: 'portrait' }
            };
            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();  
        });
	});
	</script>