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
                            <?php echo $student->roll_number; ?>
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
 						<label class="col-sm-4 text-heading">Center Code</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->center_code; ?>
 						</div>
 					</div>
 				</div>
                 <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Session</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->session; ?>
 						</div>
 					</div>
 				</div>
<!--            
    <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Exam form</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->exam_form;  ?>
                        </div>
                    </div>
                </div>
     <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading"> Course Complete</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->course_complete; ?>
 						</div>
 					</div>
 				</div>
                  <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Max Course Duration</label>
 						<div class="col-sm-8 text-value">
                         <?php $course_d = $this->Common_model->getRecordById('course','course_name',$student->course_name);
                          $due = explode(" ",$student->session);
                        //    $year = $due[1]+$course_d->max_duration;
                           if($due[0]=="July"){
                            $month = "June";
                            $year = $due[1]+$course_d->max_duration;
                           }else{
                            $month = "Dec";
                            $year = $due[1]+$course_d->max_duration-1;
                           }
                          echo $course_d->max_duration." Years".'('.$month." ".$year.')';
                          
                           ?>
 						</div>
 					</div>
 				</div>
 -->                <div class="col-md-12">
 					<div class="row py-4">
 						<label class="col-sm-2 text-heading">Course/Class</label>
 						<div class="col-sm-8 text-value">

 						<?php echo $student->course_name;  ?> (	<?php echo  $this->Common_model->getClassNameByClassId($student->class_id); ?>)
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
                   <div class="col-md-1">
                      <label class="text-heading">S.NO.</label>
                  </div>
                  <div class="col-md-1">
                      <label class="text-heading">Class</label>
                  </div>
                  <div class="col-md-2">
                      <label class="text-heading">Roll Number</label>
                  </div>

                   <div class="col-md-2">
                      <label class="text-heading">Exam Year</label>
                  </div>
                   <div class="col-md-2">
                      <label class="text-heading">Exam Result</label>
                  </div> 
                  <div class="col-md-2">
                      <label class="text-heading">Exam Status</label>
                  </div> 
                  <div class="col-md-2">
                      <label class="text-heading">View Marksheet</label>
                  </div>
              </div>
              <?php 
              $i=1;
              foreach ($result as $res) {
               ?>
               <div class="row mt-3">
                  <div class="col-md-1">
                     <label class="text-heading mt-3"><?= $i++; ?></label>
                 </div> 
                 <div class="col-md-1">
                     <label class="text-heading mt-3"><?php echo  $this->Common_model->getClassNameByClassId($res->class_id); ?></label>
                 </div>
                 <div class="col-md-2">
                 <label class="text-heading mt-3"><?= $res->roll_no; ?></label>
                </div>
                 <div class="col-md-2">
                    <label class="text-heading mt-3"><?= $res->exam_year;?></label>
                 </div> 
                 <div class="col-md-2">
                    <label class="text-heading mt-3"><?= $res->exam_result;?></label>
                 </div> 
              <div class="col-md-2 text-heading mt-3">
                    <?php 
                    if($res->exam_status == "B"){
                        echo "Backlog";
                      }else{
                        echo "Regular";
                      }
                   
                    ?>
                </div>

                      <div class="col-md-2">
                        <?php $id = $this->Common_model->encrypt_decrypt($res->id,'encrypt');?>
                         <label class="text-heading mt-3"><a href="<?= base_url('MsPrint/marksheet/'.$id.'')?>" target="_blank"><i class="fa fa-eye"></i></a></label>
                     </div>
                 </div>
             <?php } ?>
        </div>
 	</div>
</div>
