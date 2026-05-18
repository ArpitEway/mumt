<div class="card card-custom my-10 details-bg" id="profile">	
	<div class="container-fluid profile mt-5">
		<h4 class="card-title">Student Details</h4>
		<div class="row">
			<div class="col-sm-10 row">
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Session</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->session; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Center Code</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->center_code; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Enrollment No</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->enrollment_no; ?>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Form No</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->student_id; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Student</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->name; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Father</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->f_h_name; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Course</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->course_name;  ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Class</label>
						<div class="col-sm-8 text-value">
							<?php echo $student->class_name; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Email</label>
						<div class="col-sm-8 text-value">
							<?php echo $studentContactData->p_email;  ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row py-2">
						<label class="col-sm-4 text-heading">Mobile Number</label>
						<div class="col-sm-8 text-value">
							<?php echo $studentContactData->p_mobile_no; ?>
						</div>
					</div>
				</div>

			</div>
			<div class="col-sm-2">
				<img src="<?=base_url('assets/student_image/'.$student->session.'/'.$student->photo)?>" class="img-thumbnail" width="120">
			</div>
		</div>
	</div>
</div>
<div id="txnDetails">
	<div class="card card-custom my-10 details-bg">	
		<div class="container-fluid profile mt-5">
			<h4 class="card-title">Documents Details</h4>
            <?php            if($documentDetails){
            ?>
			<div class="row">
				<div class="col-md-4">
					<label class="text-heading">Document Name</label>
				</div>
				<div class="col-md-4">
					<label class="text-heading">Document Image</label>
				</div>
				<div class="col-md-4">
					<label class="text-heading">Upload Date</label>
				</div>
				
			</div>
			<?php
           
            foreach ($documentDetails as $document) { ?>
				<div class="row mt-3">

					<div class="col-md-4">
						<label class="text-heading mt-3"><?=$document->document_name; ?></label>
					</div>
					<div class="col-md-4">
						<label class="text-heading mt-3"><a  target="_blank" href="<?php echo ($student->enrolled !="Y")?BASE_URL('assets/documents/'.$document->document_image):BASE_URL('assets/enrolled_documents/'.$student->session.'/'.$document->document_image); ?>"><?php echo $document->document_image; ?></a></label>
					</div>
					<div class="col-md-4">
						<label class="text-heading mt-3"><?= date('d-m-Y', strtotime($document->date_time)); ?></label>
					</div>
                </div>
			<?php }
            }else{
                ?> <div class="d-flex justify-content-center text-heading">This Student Does Not Have Any Documents Uploaded !</div>
                <?php
            }
            ?>
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
                  <div class="col-md-1">
                      <label class="text-heading">View </label>
                  </div>
                     
                </div>
              <?php 
              $i=1;
             // $grade_classes=array(101,104,110,125,128,131);
              foreach ($result as $res) {
               // $backlogflag=true;
                $whCount = $this->Common_model->getCountByWhere('old_result_data',array('exam_data_id' =>$res->id,'theory_marks'=>'','type'=>"Theory"));
              
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
                    <?php 
                     $fail_count =0;
                      if($res->course_group_id == '76'){
                            $fail_count = $this->Common_model->getCountByWhere('old_result_data',array('exam_data_id' =>$res->id,'result'=>'FAIL','type'=>"Theory"));
                        }
                    
                ?>    
                 </div> 
              <div class="col-md-2 text-heading mt-3">
                    <?php 
                    
                    if($res->exam_status == "B"){
                        // if(($res->exam_year=="July 2023") && $res->marks_pattern=="GRADE" && (in_array($res->class_id, $grade_classes))){
                        //     $backlogflag=true;
                           
                        // }
                        echo "Backlog";
                      }else{
                         echo "Main";
                      }
                   
                    ?>
                </div>
                      <?php 
                      $flag=true;
                       if($this->session->account_type == 'MsPrint' && $student->exam_pattern=='GRADE'){
                        $flag= $this->Common_model->checkGradePreviousResult($student->student_id,$res->class_id);
                        // if($student->course_group_id=='11'){
                        //     $flag=false;
                        // }
                       }
                      ?>
                      <div class="col-md-1">
                        <?php $id = $this->Common_model->encrypt_decrypt($res->id,'encrypt');?>
                         <!-- <label class="text-heading mt-3"><a class="  btn-sm" href="<?= base_url('MsPrint/marksheet/'.$id.'')?>" target="_blank"><i class="fa fa-eye"></i></a></label> -->
                         <label class="text-heading mt-3">
                         <?php if($flag){  
                             if($whCount){ echo "WithHeld";}
                            // elseif($backlogflag) {
                             ?>
                         <a href="<?= base_url( $this->session->account_type.'/marksheet/'.$id.'')?>" target="_blank"><i class="fa fa-eye"></i></a>
                        <?php } // } ?>
                        </label>
                    </div>
                  </div>
             <?php } ?>
        </div>
 	</div>
</div>