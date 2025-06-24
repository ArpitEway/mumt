    
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
    <style>
		 .break{
        page-break-before: always;
    }
    @page {
    	size: auto;
    }
   
   
	</style>
 <?php 
 foreach($center_students as $student)  {
 	
 	$wherePaper = array('student_id' => $student->student_id,'paper_master.class_id' => $student->class_id,'paper_master.course_group_id'=> $student->course_group_id,'paper_master.type'=>'theory','exam_date!='=>'');
	 $wherePaperExamDate =array('exam_date!='=>'0000-00-00');
	 $this->db->where($wherePaperExamDate);
     $this->db->select('*');
     $this->db->from('paper_master');
     $this->db->join('new_exam_form', 'new_exam_form.paper_id = paper_master.id');
     //$this->db->join('time_table', 'paper_master.class_id = time_table.class_id');
     $this->db->where($wherePaper);
     $this->db->order_by("exam_date", "asc");
     $this->db->order_by("exam_shift", "desc");
     $papers = $this->db->get()->result();
	 $paper_count = count($papers);
	// $this->Common_model->last_query(); die;
	 if($paper_count){

		  // $newstring = date('y')."1".substr($student->center_code, -4); 
		  $newstring = "232".substr($student->center_code, -4); 
     ?>   
<section class="break" >
	<div class="">
	<div  id="container_content"  style="margin: auto;">
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
						<h5>Schedule of Cancelled Exam for Annual/Semester Examination of
								<?php
								if($student->course_group_id==75 || $student->course_group_id==76 || $student->course_group_id==77)
								{
									if($student->class_id==255 || $student->class_id==257 )
									echo 'March 2024';
									else
									echo '2024';
								}
								else{
									echo 'January 2024';
								} 
								
								
							?> 
							
						</h5>
					</div>
				</div>
			</div>
			<?php 
			$where = array('id' => $student>exam_center_id);
				//$exam = $this->Common_model->getRecordByWhere('exam_center',$where); ?>
			<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							<input type="hidden" value="<?php echo $student->student_id ; ?>" id="student_id">
						  <tbody>
							  <?php 
							  // 169 MDE165 MMYVV UTD KAROUNDI
							  // 178 MDE172 Nachiketa Collage of computer science commerce & Advanced Technology
							  if($student->exam_center_id==169 || $student->exam_center_id==178){ ?>
							<tr>
							  <td colspan="4"><b>Exam Center: </b><?= $this->Common_model->getExamCenterNameById($student->exam_center_id); ?></td>
							</tr>
							<?php }  
							/*$st=array(768558,771451,771494,771507,772866,772882);
							if(in_array($student->student_id,$st)){ ?>
							<tr>
							  <td colspan="4"><b>Exam Center: </b>Maharishi Mahesh Yogi Vedic Vishwavidyalaya, C/O Maharishi Shiksha Sansthan Lamti, Jabalpur, Madhya Pradesh</td>
							</tr>
							<?php }  */ ?>
								<!-- <tr>
							  <td colspan="4"><b>College: </b><?=$student->center_name; ?></td>
							    </tr> -->
							<tr>
								<td><b>Roll No: </b> <?=$student->roll_no;?></td>
								<td colspan="2"><b>Enrollment No: </b><?=$student->enrollment_no;?></td>
								 <td rowspan="4"><img src="<?=base_url('assets/student_image/'.$student->session.'/'.$student->photo);?>"  width="115px" height="166px" /></td> 
							</tr>
							<tr>
							  <td><b>Course: </b> <?=$student->course_name;?>  <?php if($student->class_id!=163 && $student->class_id!=175 ) echo '('.$student->class_name.')';?> </td>
							  <td colspan="2"><b>EC Code: </b><?=$student->examcentercode;?></td>
							</tr>
							<tr>
							  <td colspan="3"><b>Student Name: </b> <?=$student->name;?></td>
							</tr>
							<tr>
								<td colspan="3"><b>Father/Husband Name: </b> <?=$student->f_h_name;?></td>
							</tr>
							
						  </tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="BoxF border- padding mar-bot txt-center">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>Date</th>
									<th >Paper Name</th>
								
									<th>Time</th>
								</tr>
							</thead>
						  <tbody>
						  <?php
						  $i = 1;
						  $paper_count = count($papers);
						  $class_ids = array(104,101,107,110,116,119,273,125,128,131,134,162,163,164,165,283,285,287,289,310,291,293,295,274,297,168,169,170,171,214,106,103,109,112,118,121,127,130,133,136,264);
			foreach($papers as $paper){
				?>
				<tr>
					<td><?php echo $i ; ?></td>
					<td><?php echo date("d-m-Y", strtotime($paper->exam_date)); ?></td>
					<td style="text-align:left;"><?php echo $paper->paper_name; ?></td>
					<!-- <?php //if ($i==1): ?>
						
					<td rowspan="<?=$paper_count?>" style="vertical-align: middle;"><?php echo  $this->Common_model->viewDate($paper->exam_start_date); ?></td>
					<td rowspan="<?=$paper_count?>" style="vertical-align: middle;"><?php echo $this->Common_model->viewDate($paper->exam_end_date); ?></td>
					
					<?php //endif ?> -->
					<td><?php
					
					if($paper->exam_shift=='Afternoon' && in_array($student->class_id,$class_ids)){
						echo '3:00 PM To 6:00 PM';
					}
					elseif($paper->exam_shift=='Morning'){
						echo '10:00 AM To 1:00 PM';
					}elseif($paper->exam_shift=='Afternoon'){
						echo '2:00 PM To 5:00 PM';
					}elseif($paper->exam_shift=="Early Morning"){
						echo '07:00 To 10:00 AM';
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

			<footer class="txt-center">
			
				<strong>This is a computer-generated document. No signature is required</strong>
			</footer>
			
		</div>
	</div>

</section>
    
 <?php } 


} ?>
