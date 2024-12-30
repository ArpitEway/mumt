<div class="row mt-2">
	<div class="col-12 col-md-3 col-sm-12 menu-background p-3" >
		<ul class="nav flex-column nav-pills">
            <li class="nav-item mb-2">
				<a class="nav-link active show border" id="Enrollment-tab-Private" data-toggle="tab" href="#Enrollment_Private">
					<span class="nav-text">Enrollment Private</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
			<li class="nav-item mb-2">
				<a class="nav-link  border" id="Enrollment-tab-Regular" data-toggle="tab" href="#Enrollment_Regular">
					<span class="nav-text">Enrollment Regular</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
			
		 	<li class="nav-item mb-2">
				<a class="nav-link border" id="payment-tab" data-toggle="tab" href="#payment">
					<span class="nav-text">Account</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
			<li class="nav-item mb-2">
				<a class="nav-link border" id="exam-tab" data-toggle="tab" href="#exam">
					<span class="nav-text">Examination</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
			<li class="nav-item mb-2">
				<a class="nav-link border" id="form-tab" data-toggle="tab" href="#download_form">
					<span class="nav-text">Degree/Diploma/NOC</span>
					<span class="nav-icon flot-right" >
						<i class="flaticon2-fast-next"></i>
					</span>
				</a>
			</li>
		</ul>
	</div>
	<div class="col-md-8 col-12 col-sm-12 menu-background p-3">
		<div class="tab-content">
			<div class="tab-pane fade active show" id="Enrollment_Private" role="tabpanel" aria-labelledby="Enrollment-tab-Private">
				<div class="row">

					
					<a class="border-0 custom-menu-item" href="<?=base_url('instruction_private');?>">
						<div>
							<span class="nav-text">Private Course Details</span>
						</div>
					</a>
				 
				 <?php
				$center_id =  $this->session->center_id;	
				$center_ids_dep = array(21,22,23,24,25,26,27,28,29);
				  if($center->admission_permission_private=='Y' ) // && !in_array($center_id, $center_ids_dep)
				   {
					
				 	$pending = $this->Common_model->getCountByWhere('online_payment_transaction','center_id='.$this->session->center_id.' and  fees_head="Admission Fees"  and payment="N"  and payment_status="pending" and created_at > "2024-08-11"');
					// and remark="With Late Fees"
				 	$failureCount = $this->Common_model->getCountByWhere('online_payment_transaction','center_id='.$this->session->center_id.' and  fees_head="Admission Fees"  and payment="N" and payment_status!="pending" and created_at > "2024-08-11"');
                    
				 	if($pending!=0 || ($failureCount!=0 && $failureCount>1)){
				 		?>
				 	<a class="border-0 custom-menu-item " data-toggle="modal" data-target="#exampleModalCenter1">
					<div>
						<span class="nav-text">Admission Form Private</span>
						</div>
			 	</a> 
				 <?php
				 	}else{
					?>
					<a class="border-0 custom-menu-item kt_popup_private" >
						<div>
							<span class="nav-text">Admission Form Private</span>
						</div>
					</a>
				<?php
				 	}//pending end
				   }
				  ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('all_student/PVT');?>">
						<div>
							<span class="nav-text">Student Report</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student_list/unpaid/PVT');?>">
						<div>
							<span class="nav-text">Unpaid Student</span>
						</div>
					</a>
					<?php if($center->temp_admission_payment=='Y' ) { ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('unpaid_student_list');?>">
						<div>
							<span class="nav-text">Old - Private Unpaid Student List</span>
						</div>
					</a>
					<?php } ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('Document/index/PVT');?>">
						<div>
							<span class="nav-text">Upload Admission Document</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('not_approve_student_list/PVT');?>">
						<div>
							<span class="nav-text">Unapproved Student List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('form_edit_request/PVT');?>">
						<div>
							<span class="nav-text">Form Edit Request</span>
						</div>
					</a>
					
					<!-- <a class="border-0 custom-menu-item" href="<?=base_url('admission_mode_edit_request/PVT');?>">
						<div>
							<span class="nav-text">Mode Change Request (Private to Regular)</span>
						</div>
					</a> -->
					
					<a class="border-0 custom-menu-item" href="<?=base_url('paper_missing_list/private');?>">
						<div>
							<span class="nav-text">Paper Missing List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('photo_missing_list/PVT');?>">
						<div>
							<span class="nav-text">Photo Missing List</span>
						</div>
					</a>	
				</div>
			</div>
			<div class="tab-pane fade" id="Enrollment_Regular" role="tabpanel" aria-labelledby="Enrollment-tab-Regular">
				<div class="row">

					<a class="border-0 custom-menu-item" href="<?=base_url('instruction');?>">
						<div>
							<span class="nav-text">Regular Course Details</span>
						</div>
					</a>
					<!-- <a class="border-0 custom-menu-item" href="<?=base_url('instruction_private');?>">
						<div>
							<span class="nav-text">Private Course Details</span>
						</div>
					</a> -->
				  <?php
				  $center_id =  $this->session->center_id;	
				//  $center_ids_dep = array(10,11,12,21,22,23,24,25,26,27,28,29,13);
				//  if($center->admission_permission=='Y' || in_array($center_id, $center_ids_dep)){
					if($center->admission_permission=='Y' ){
					  ?>
	        <a class="border-0 custom-menu-item kt_popup" >
						<div>
							<span class="nav-text">Admission Form Regular for Karoundi</span>
						</div>
					</a>
				<?php
				  }
				  ?>
				 <?php
				/* $center_id =  $this->session->center_id;	
				$center_ids_dep = array(21,22,23,24,25,26,27,28,29);
				  if($center->admission_permission_private=='Y' && !in_array($center_id, $center_ids_dep)){
					?>
					<a class="border-0 custom-menu-item" href="<?=base_url('admission_form/private');?>">
						<div>
							<span class="nav-text">Admission Form Private</span>
						</div>
					</a>
				<?php
				  } */
				  ?>
					<a class="border-0 custom-menu-item" href="<?=base_url('all_student/REG');?>">
						<div>
							<span class="nav-text">Student Report</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('student_list/unpaid/REG');?>">
						<div>
							<span class="nav-text">Unpaid Student</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('Document/index/REG');?>">
						<div>
							<span class="nav-text">Upload Admission Document</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('not_approve_student_list/REG');?>">
						<div>
							<span class="nav-text">Unapproved Student List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('form_edit_request/REG');?>">
						<div>
							<span class="nav-text">Form Edit Request</span>
						</div>
					</a>
					
					<a class="border-0 custom-menu-item" href="<?=base_url('admission_mode_edit_request/REG');?>">
						<div>
							<span class="nav-text">Mode Change Request (Regular to Private)</span>
						</div>
					</a>
					
					<a class="border-0 custom-menu-item" href="<?=base_url('paper_missing_list/regular');?>">
						<div>
							<span class="nav-text">Paper Missing List</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('photo_missing_list/REG');?>">
						<div>
							<span class="nav-text">Photo Missing List</span>
						</div>
					</a>
				</div>
			</div>
			<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
				<div class="row">
					<a class="border-0 custom-menu-item" href="<?=base_url('student_list/paid');?>">
						<div>
							<span class="nav-text">Paid Student</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('payment_complaint');?>">
						<div>
							<span class="nav-text">Payment Complaint </span>
						</div>
					</a>
				</div>
			</div> 
			<div class="tab-pane fade" id="exam" role="tabpanel" aria-labelledby="exam-tab">
				<div class="row">
				
				<?php 
              //  $count = $this->Common_model->getCountByWhere('student',array('center_id'=>$center->id,'new_exam_form !='=>'D'));
                //&& $count>0href="<?=base_url('exam_form_students');"
                if ($center->exam_form_permission=='Y' ): ?>
				 <a class="border-0 custom-menu-item" id="main-exam" >
							<div>
								<span class="nav-text">Exam Form January 2025</span>
							</div>
					</a> 
					<a class="border-0 custom-menu-item" href="<?=base_url('backlog_exam_form_students');?>">
							<div>
								<span class="nav-text">Backlog Exam Form January 2025</span>
							</div>
					</a>  
					<?php endif ?>
					
					<?php 
                   // if($this->session->center_id==12 || $this->session->center_id==28){ ?>
                   	<!--
					 <a class="border-0 custom-menu-item" href="<?=base_url('practical_marks_list');?>">
						<div>
							<span class="nav-text">Practical Marks Submission </span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('internal_marks_list');?>">
						<div>
							<span class="nav-text">Internal Marks Submission (Regular)</span>
						</div>
					</a>  
					<?php // } ?>
				-->
					<?php if ($center->result_permission=='Y'): ?>
						<a class="border-0 custom-menu-item" href="<?=base_url('result');?>">
							<div>
								<span class="nav-text">Result(June 2024)</span>
							</div>
						</a>
						 <a class="border-0 custom-menu-item" href="<?=base_url('backlog_result');?>">
							<div>
								<span class="nav-text">Backlog Result (June 2024)</span>
							</div>
						</a> 
						
						<?php endif ?>

						<!-- <a class="border-0 custom-menu-item" href="<?=base_url('support_system_complaint');?>">
							<div>
								<span class="nav-text">Exam Form Complaint</span>
							</div>
						</a> 
 -->
						<!-- <a class="border-0 custom-menu-item" href="<?=base_url('search_exam_by_course');?>">
					 		<div>
								<span class="nav-text">Time Table June 2024</span>
					 		</div>
						</a>   -->
					
					

				<!--	<a class="border-0 custom-menu-item" href="<?=base_url('student_roll_no_list');?>">
						<div>
							<span class="nav-text">Roll No List</span>
						</div>
					</a> -->
					
					<?php if ($center->admit_card_permission=='Y'): ?>
						<a class="border-0 custom-menu-item" href="<?=base_url('admit_card_list');?>">
						<div>
							<span class="nav-text">Admit Card</span>
						</div>
					</a>
					 <a class="border-0 custom-menu-item" href="<?=base_url('admit_card_backlog_student_list');?>">
						<div>
							<span class="nav-text">Backlog Admit Card</span>
						</div>
					</a>  
					<?php endif  ?>

				</div>
			</div> 

			<!-------Tab START---->		
			<div class="tab-pane fade" id="download_form" role="tabpanel" aria-labelledby="form-tab">
				<div class="row">
					<!-------Regular Form---->	
					<a class="border-0 custom-menu-item" href="<?=base_url('assets/images/center/Regular/')?>Regular Course Migration 2024.pdf" download>
						<div>
							<span class="nav-text">Regular Course Migration</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('assets/images/center/Regular/')?>Regular Degree Form 2024.pdf" download>
						<div>
							<span class="nav-text">Regular Degree Form </span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('assets/images/center/Regular/')?>Regular Diploma Form 2024.pdf" download>
						<div>
							<span class="nav-text">Regular Diploma Form </span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('assets/images/center/Regular/')?>Regular Prov. Degree  2024.pdf" download>
						<div>
							<span class="nav-text">Regular Prov. Degree</span>
						</div>
					</a>
					<!-------Private Form---->	
					<a class="border-0 custom-menu-item" href="<?=base_url('assets/images/center/Private/')?>Degree Form 2024.pdf" download>
						<div>
							<span class="nav-text">Private Degree Form</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('assets/images/center/Private/')?>Private NOC 2024.pdf" download>
						<div>
							<span class="nav-text">Private NOC Form</span>
						</div>
					</a>
					<a class="border-0 custom-menu-item" href="<?=base_url('assets/images/center/Private/')?>Private Prov. Degree  2024.pdf" download>
						<div>
							<span class="nav-text">Private Prov. Degree Form </span>
						</div>
					</a>
					
				</div>
			</div> 
			<!-------Tab End---->		

		</div>
	</div>
</div>



<!-- Button trigger modal-->
<!--  <button type="button" class="btn btn-primary" id="popupbtn" style="    visibility: hidden;" data-toggle="modal" data-target="#exampleModalCenter">
    Launch demo modal
</button>  -->

<!-- Modal data-backdrop="static"  -->
<div class="modal fade" id="exampleModalCenter"     tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Important Notification </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
				<img src="<?=base_url('assets/images/center/new.gif')?>" alt=""> <b>
				सूचित किया जाता है कि आगामी परीक्षाओं के लिये विलम्ब शुल्क 100 रुपये सहित परीक्षा आवेदन पत्र ऑनलाइन भरने की तिथि में वद्धि कर 23 जून 2024 से 25 जून 2024 तक निर्धारित की गयी है। निर्धारित अंतिम तिथि तक परीक्षा आवेदन पत्र भरने की प्रक्रिया अनिवार्य रूप से पूर्ण करे| उक्त तिथि के पश्चात् किसी भी परीक्षा आवेदन पर विचार नहीं किया जायेगा |
				</b><br><br>
				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
               
            </div>
        </div>
    </div>
</div>

<!-- Modal data-backdrop="static"  -->
<div class="modal fade "  data-backdrop="static" id="exampleModalCenter1"     tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Important Notification </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
				<img src="<?=base_url('assets/images/center/new.gif')?>" alt=""> <b>
				आपके द्वारा पूर्व में भरा गया आवेदन पत्र के शुल्क का भुगतान करने के पश्चात ही अन्य आवेदन पत्र भरे जा सकते हैI कृपया "Unpaid Student" में जाकर शुल्क का भुगतान करें, अन्यथा डिलीट करे, उसके पश्चात् ही नवीन आवेदन पत्र भरे जायेंगे I
				</b><br><br>
				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
               
            </div>
        </div>
    </div>
</div>
<style>
		#swal2-content{
		text-align:justify !important;
	}
</style>
	<script type="text/javascript">
		
		

$(".kt_popup").click(function(e) {
 
Swal.fire({
	 text:  "सत्र २०२३-२४ के नियमित पाठ्यक्रम - पी ज़ी डिप्लोमा / डिप्लोमा / प्रमाणपत्र को छोड़कर स्नातक एवं स्नातकोत्तर पाठ्यक्रमों में प्रवेशित समस्त छात्रों का अध्यापन कार्य एवं उनकी परीक्षा विश्वविद्यालय मुख्यालय करौंदी में सम्पन्न होगी| ",
	 icon: "info",
	showCancelButton: true,
	confirmButtonText: "I Agree"
}).then((result) => {
  if (result.isConfirmed) {	
		window.location.href =  "<?=base_url('admission_form/regular');?>";
	}else{
			return false;
	}
})

});

$(".kt_popup_private").click(function(e) {
 
Swal.fire({
	 html:  `<b>महत्त्वपूर्ण सूचना - </b>सत्र 2024-25 में विश्वविद्यालय के स्वाध्यायी (प्राइवेट) पाठ्यक्रमों में पंजीयन के इच्छुक विद्यार्थियों को सूचित किया जाता है कि, उनकी परीक्षाएं विश्वविद्यालय के मुख्यालय करौंदी जिला कटनी में आयोजित की जाएगी| इस सम्बन्ध में अधिक जानकारी के लिए मोबा नं 9755590031 और 9752042834 पर संपर्क कर सकते है|`
}).then((result) => {
  if (result.isConfirmed) {	
  	window.location.href =  "<?=base_url('admission_form/private');?>";
      }
})

});

$("#main-exam").click(function (e) {
    Swal.fire({
        html: `<b> आवश्यक सुचना :- </b>सर्व संबंधितों को सूचित किया जाता है कि विश्वविद्यालय द्वारा संचालित नियमित पाठ्यक्रमों - एम.ए., एम.कॉम, एम.एस.डब्लू , एम.एससी, एम.बी.ए., (प्रथम / तृतीय सेमेस्टर), पी.जी.डी.सी.ए. एवं डी.सी.ए (प्रथम सेमेस्टर) के छात्रों की मुख्य / पूरक परीक्षा जनवरी २०२५ में मुख्यालय करौंदी में आयोजित होगी| परीक्षा फॉर्म भरने की अंतिम तिथि 7 जनवरी 2025 निर्धारित की गई है| अनुरोध है कि उक्त दिनांक के पूर्व परीक्षा फॉर्म भरने की प्रक्रिया पूर्ण करे| इसके पश्चात् किसी भी आवेदन पर विचार नहीं किया जावेगा|`,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "I Agree",
        didOpen: () => {
            // Adjust the width after the modal has opened
            $('.swal2-popup').css('width', '38%');
        },
    }).then((result) => {
        // Handle confirmation result
        if (result.isConfirmed) {
            window.location.href =  "<?=base_url('exam_form_students');?>";
           // console.log("User agreed.");
        }
    });
});


// नवीन आवेदन पत्र भरने से पूर्व छात्र के समस्त शेक्षणिक डॉक्युमनेट से छात्र का नाम, पिता का नाम, फोटो, कोर्स का नाम एवं  उसकी योग्यता  का सूक्ष्मता से अध्ययन कर उसका मिलान कर ले I आवेदन पत्र भरने के पश्चात् उसमे किसी भी प्रकार के संसोधन हेतु  निवेदन पर विचार नहीं किया जायेगा I
</script>