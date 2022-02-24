<style type="text/css">
    .profile .text-heading {
        font-size: 15px;
     }
</style>
 <div class="card card-custom my-10 details-bg" id="profile">	
 	<div class="container-fluid profile mt-5"> 
 		<h4 class="card-title">Student Details</h4>
 			<div class="row ">
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Form No</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->student_id; ?>
                        </div>
                    </div>
                </div>
 				<div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Enrollment No</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->enrollment_no; ?>
 						</div>
 					</div>
 				</div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Roll No</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->roll_no; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading"> Name</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->name; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Father Name</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->f_h_name; ?>
                        </div>
                    </div>
                </div>
 				<div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">DOB</label>
 						<div class="col-sm-8 text-value">
 							<?=$this->Common_model->viewDate($student->dob); ?>

 						</div>
 					</div>
 				</div>
                <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Exam form</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->new_exam_form;  ?>
 						</div>
 					</div>
 				</div>
 				<div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Center Code</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->center_code; ?>
 						</div>
 					</div>
 				</div>
                <div class="col-md-6">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Course/Class</label>
 						<div class="col-sm-8 text-value">
 						<?php echo $student->course_name;  ?> (	<?php echo $student->class_name; ?>)
 						</div>
 					</div>
 				</div>
 		</div>
 	</div>
 </div>  
 <div id="examDetails">

 	<div class="card card-custom my-10 details-bg">	
 		<div class="container-fluid profile mt-5">

 			<div class="row">
 				<div class="col-md-5">
 					<label class="text-heading">Paper Name</label>
 				</div>
 				<div class="col-md-1">
 					<label class="text-heading">Paper Code</label>
 				</div>
 				<div class="col-md-1">
 					<label class="text-heading">Answer Sheet</label>
 				</div>
 				<div class="col-md-1">
 					<label class="text-heading">Delete</label>
 				</div>
 				<div class="col-md-1">
 					<label class="text-heading">Status</label>
 				</div>
 				<div class="col-md-1">
 					<label class="text-heading">Marks</label>
 				</div>

 				<div class="col-md-1 text-center">
 					<label class="text-heading"> Final Marks</label>
 				</div>
 				<div class="col-md-1 text-center">
 					<label class="text-heading">Remarks</label>
 				</div>

 			</div>
 			<?php foreach ($paper as $payment) {

$where = array('student_id'=>$payment->student_id,
                       'paper_code'=>$payment->paper_code );

                    $view = $this->Common_model->get_record("upload_exam_ans_sheet",'*',$where);


             ?>
 				<div class="row mt-3">

 					<div class="col-md-5">
 						<label class="text-heading mt-3"><?=$this->Common_model->getPaperNameById($payment->paper_id); ?></label>
 					</div> 
 					<div class="col-md-1">
 						<label class="text-heading mt-3"><?=$payment->paper_code;?></label>
 					</div>
 
                    <?php
                    
                    if($view){
                      ?>

                      <div class="col-md-1">

                          <?php if(file_exists(FCPATH.'/assets/exam_answersheet/'.$view[0]->upload_date.'/'.$view[0]->answer_sheet)){ ?>
                            <a target="_blank" href="<?php  echo  base_url('/assets/exam_answersheet/'.$view[0]['upload_date'].'/'.$view[0]['answer_sheet'].'.pdf') ?>" >View</a>
                       <?php }else{
                        echo 'N/A';
                    } ?></div>

                    <?php 
                }?>


                <?php  

                  if($view){?>
                    <div class="col-md-1">
                     <?php if(file_exists(FCPATH.'/assets/exam_answersheet/'.$view[0]->upload_date.'/'.$view[0]->answer_sheet)){ ?>

                        <a  class="btn btn-lg " href="<?php  echo  base_url('admin/admins/Delete_answersheet/').$view[0]['id'] ;?>">
                         <i class="mdi mdi-delete delete-icon"></i></a>
                     <?php }else{
                        echo 'N/A';
                    } ?></div>
                    <?php 
                }?>

 					<div class="col-md-1">
 						<label class="text-heading mt-3"></label>
 					</div>
 					<div class="col-md-1">
 						<label class="text-heading mt-3"></label>
 					</div>
 					<div class="col-md-1">
 						<label class="text-heading mt-3"><?=$payment->theory_marks;?></label>
 					</div>
 					<div class="col-md-1">
 						<label class="text-heading mt-3"></label>
 					</div>
 					<div class="col-md-1">
 						<label class="text-heading mt-3"></label>
 					</div>

 				</div>
 			<?php } ?>
 		</div>
 	</div>
 </div>

