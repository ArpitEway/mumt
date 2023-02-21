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
	</style>
 <?php 
 foreach($exam_center_students as $student)  {
 	
 	$wherePaper = array('student_id' => $student->student_id,'paper_master.class_id' => $student->class_id,'paper_master.course_group_id'=> $student->course_group_id,'paper_master.type'=>'theory','exam_date!='=>'0000-00-00','exam_date!='=>'');
	 
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

		  $newstring = date('y')."1".substr($student->center_code, -4); 
     ?>   
<section class="break" style="font-size: 16px;">
		<div class="admit-card" style="width:1030px !important; ">
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
						<h5>Attendance Sheet Examination 
							<?php
							if($student->course_group_id==75 || $student->course_group_id==76){
								echo 'Feb 2023';
							}else{
								echo 'March 2023';
							}
							
							?>
						</h5>
						
					</div>
				</div>
			</div>
			<div style="height: 8px;text-align: right;">
				<span style="font-size:8px;margin:2px;"><?=$newstring?></span>
			</div>
			<?php 
			$where = array('id' => $student->exam_center_id);
				$exam = $this->Common_model->getRecordByWhere('exam_center',$where); ?>
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
								<?php $img_url = (file_exists(FCPATH.'assets/student_image/'.$student->session.'/'.$student->photo)) ? base_url('assets/student_image/'.$student->session.'/'.$student->photo) : base_url('assets/images/center/student.bmp'); ?>
								 <td rowspan="4" class="text-center"><img src="<?=$img_url;?>"  width="115px" height="166px" /></td> 
							</tr>
							<tr>
							  <td><b>Course: </b> <?=$student->course_name;?> (<?=$student->class_name;?>) </td>
							  <td colspan="2"><b>EC Code: </b> <?=$student->examcentercode;?></td>
							</tr>
							<tr>
							  <td colspan="3"><b>Student Name: </b> <?=$student->name;?></td>
							</tr>
							<tr>  
							  <td colspan="3"><b>Father/Husband Name: </b> <?=$student->f_h_name;?></td>
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
						
			foreach($papers as $paper){
				?>
				<tr>
					<td><?php echo $i ; ?></td>
					<td><?php echo date("d-m-Y", strtotime($paper->exam_date)); ?></td>
					<td><?= ($paper->exam_shift=='Morning') ? '11:00 AM To 2:00 PM'   : ($paper->exam_shift=='Evening') ? '03:00 PM To 6:00 PM'  : '12:00 To 3:00 PM'
					; ?></td>
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


			<div style="style:100%;margin-top:30px; height:auto;min-height: 150px;">
				<div style="float:left;width:50%; height:auto;" align="left">
					<p><!-- <strong>Principle</strong> --></p>
					<p><!-- <strong>Name</strong> --></p>
					<p><!-- <strong>Signature</strong> --></p>
				</div>
				<div style="float:left;width:50%; height:auto;" align="left">
					<p><!-- <strong>Exam Center Superintendent</strong> --></p>
					<p><!-- <strong>Name</strong> --></p>
					<p><strong>Seal &amp; Signature</strong></p>
				</div>
			</div>

			
			
		
	
</section>
    
 <?php } } ?>