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