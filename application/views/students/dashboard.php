<?php 
if($this->session->admission_by=='web'){
  $st=$this->Common_model->encrypt_decrypt($this->session->student_id,'encrypt');
    $step1=$step2=$step3=$step4=$step5=$step6="";
    $step1Url= $step2Url=$step3Url=$step4Url="#";
    $step1="active";
    $step1Url=base_url('admission_form');
    $verification="Verification";
  //   if(($studentData->form_fees=='N') && ($studentData->temp_exam_form=='N')){
  //       $step1="active";
  //       $step1Url=base_url('admission_form');
  //   }
  // else 
  if(($studentData->form_fees=='N') && ($studentData->temp_exam_form=='N') && ($studentData->mother_name!="")){
        $step1=$step2="active";
        $step1Url= base_url('profile');
       
        $step2Url=base_url('showPapers').'/'.$st;
    }
    elseif( $studentData->form_fees=='N' && ($studentData->temp_exam_form=='Y')  && $studentData->document_uploaded!='Y' && $studentData->approved!='Y'){ 
        $step1=$step2=$step3="active";
        $step1Url= $step2Url="#";
        $step1Url= base_url('profile');
        $step2Url=base_url('showPapers').'/'.$st;
        $step3Url=base_url('Payment/formfess/').'/'.$st;
        
    }
    elseif( $studentData->form_fees=='Y'  && $studentData->document_uploaded!='Y' && $studentData->approved==''){ 
        $step1=$step2=$step3=$step4="active";
        $step1Url= $step2Url= $step3Url="#";
       
        $step1Url= base_url('profile');
        $step2Url=base_url('showPapers').'/'.$st;
        $recipt= $this->Common_model->getAllRow("online_payment_transaction", "", array(
          "student_id" => $this->session->student_id,"fees_head"=>"Form Fees","class_id"=>$studentData->class_id));
        $step3Url=base_url('show_fees/'.$this->Common_model->encrypt_decrypt($recipt[0]['id'],'encrypt'));
        $step4Url=base_url('document_upload').'/'.$st;
        
        
    }
    elseif( $studentData->form_fees=='Y'  && $studentData->document_uploaded=='Y' && $studentData->approved=='N'){ 
      
      
      $docData = $this->Common_model->getRecordByWhere('admission_document',array('student_id'=>$this->session->student_id,'status'=>'N'));
     
		 // $docData_remain = $docData[0]->status;
     
     if( $docData[0]->status=='N'){
      $step1=$step2=$step3=$step4=$step5="active";
      $step1Url= $step2Url= $step3Url="#";
      $step1Url= base_url('profile');
      $step2Url=base_url('showPapers').'/'.$st;
      $recipt= $this->Common_model->getAllRow("online_payment_transaction", "", array(
        "student_id" => $this->session->student_id,"fees_head"=>"Form Fees","class_id"=>$studentData->class_id));
      $step3Url=base_url('show_fees/'.$this->Common_model->encrypt_decrypt($recipt[0]['id'],'encrypt'));
      $step4Url=base_url('document_upload').'/'.$st;;
       $verification="Verification Pending";
     }else{
      $step1=$step2=$step3=$step4="active";
      $step1Url= $step2Url= $step3Url="#";
      $step1Url= base_url('profile');
      $step2Url=base_url('showPapers').'/'.$st;
      $recipt= $this->Common_model->getAllRow("online_payment_transaction", "", array(
        "student_id" => $this->session->student_id,"fees_head"=>"Form Fees","class_id"=>$studentData->class_id));
      $step3Url=base_url('show_fees/'.$this->Common_model->encrypt_decrypt($recipt[0]['id'],'encrypt'));
      $step4Url=base_url('remaining_documents').'/'.$st;
      $verification="Verification Failed";
     }
      
      
      
  }
    elseif( $studentData->form_fees=='Y' && $studentData->document_uploaded=='Y' && $studentData->approved=='' && $studentData->payment_status!='Y'){ 
        $step1=$step2=$step3=$step4=$step5="active";
        $step1Url= $step2Url= $step3Url=$step4Url="#";
        $step1Url= base_url('profile');
        $step2Url=base_url('showPapers').'/'.$st;
        $recipt= $this->Common_model->getAllRow("online_payment_transaction", "", array(
          "student_id" => $this->session->student_id,"fees_head"=>"Form Fees","class_id"=>$studentData->class_id));
         
        $step3Url=base_url('show_fees/'.$this->Common_model->encrypt_decrypt($recipt[0]['id'],'encrypt'));
        $step4Url=base_url('document_upload').'/'.$st;
        $step5Url="#";
        $verification="Verification Pending";
       
    }
    elseif($studentData->approved=='Y' && $studentData->payment_status!='Y'){
        $step1=$step2=$step3=$step4=$step5=$step6="active";
        $step1Url= $step2Url= $step3Url=$step4Url=$step5Url="#";
        $step1Url= base_url('profile');
        $step2Url=base_url('showPapers').'/'.$st;
        $recipt= $this->Common_model->getAllRow("online_payment_transaction", "", array(
          "student_id" => $this->session->student_id,"fees_head"=>"Form Fees","class_id"=>$studentData->class_id));
        $step3Url=base_url('show_fees/'.$this->Common_model->encrypt_decrypt($recipt[0]['id'],'encrypt'));
        $step4Url=base_url('showDocuments/'.$st);
        $verification="Document Verified";
        $step6Url=base_url('Payment/admission').'/'.$st;
    }
?>
<div>
        <div class="container">
        <section class="step-indicator">
            <div class="step step1 <?=$step1?> ">
                <div class="step-icon">1</div>
                <p><a href="<?=$step1Url?>">Admission </a> </p>
            </div>
            <div class="indicator-line <?=$step2?>"></div>
            <div class="step step2 <?=$step2?>">
                <div class="step-icon">2</div>
            <p><a href="<?=$step2Url?>">Paper </a></p>
            </div>
            <div class="indicator-line <?=$step3?> "></div>
            <div class="step step3 <?=$step3?>">
                <div class="step-icon">3</div>
            <p><a href="<?=$step3Url?>">FormFees</a></p>
            </div>
            <div class="indicator-line <?=$step4?>"></div>
            <div class="step step4 <?=$step4?>">
                <div class="step-icon">4</div>
            <p><a href="<?=$step4Url?>">Document</a></p>
            </div>
            <div class="indicator-line <?=$step5?>"></div>
            <div class="step step5 <?=$step5?>">
                <div class="step-icon">5</div>
            <p <?php if($verification!="Verification"){ echo 'style="bottom: -59px;"';}  ?>><a href="<?=$step5Url?>"><?=$verification?></a></p>
            </div>
            <div class="indicator-line <?=$step6?>"></div>
            <div class="step step6 <?=$step6?>">
                <div class="step-icon">6</div>
            <p style="bottom: -59px;"><a href="<?=$step6Url?>">Admmission Fees</a></p>
            </div>
        </section>
        </div>
 </div> 

<br><br><br>
<br>
<?php } ?>
 <style>
    .container {
  max-width: 1200px;
  margin: 0 auto;
}

.step-indicator {
  margin-top: 50px;
  display: flex;
  align-items: center;
  padding: 0 40px;
}

.step {
  display: flex;
  align-items: center;
  flex-direction: column;
  position: relative;
  z-index: 1;
}

.step-indicator .step-icon {
  height: 50px;
  width: 50px;
  border-radius: 50%;
  background: #c2c2c2;
  font-size: 10px;
  text-align: center;
  color: #ffffff;
  position: relative;
  line-height: 50px;
  font-size: 20px;
}

.step.active .step-icon {
  background: #771E18;
}

.step p {
  text-align: center;
  position: absolute;
  bottom: -40px;
  color: #c2c2c2;
  font-size: 14px;
  font-weight: bold;
}

.step.active p {
  color: #771E18;
}

.step.step2 p,
.step.step3 p {
  left: 50%;
  transform: translateX(-50%);
}

.indicator-line {
  width: 100%;
  height: 2px;
  background: #c2c2c2;
  flex: 1;
}

.indicator-line.active {
  background: #771E18
  /* crimson; */
}

@media screen and (max-width: 500px) {
  .step p {
    font-size: 11px;
    bottom: -20px;
  }
}
 </style>    

<?php 
$studentData = $this->Common_model->getRecordById('student','student_id',$this->session->student_id);
// $whereClass = array('class_id' => $studentData->class_id,
// 	'exam_permission' => 'Y',
// );
// $timeTableData = $this->Common_model->getRecordByWhere('time_table',$whereClass);
 /* if (count($timeTableData)!=0 && $studentData->new_exam_form=="Y"): ?>
	<div class="row justify-content-center align-items-center" >
		<h5>Click Here to Examination</h5>

	<a href="<?=base_url('exam_paper')?>" class="btn btn-primary float-right ml-2"> Exam Paper</a>
</div>
<?php endif  */?>
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<input type="hidden" name="course"  id="course_group_id_both" value="<?=$studentData->course_group_id ?>">
<input type="hidden" name="class_id" id="class_id" value="<?=$studentData->class_id ?>">
<input type="hidden" name="mode" id="mode" value="<?=$studentData->university_mode ?>">
				
<div class="notification"> 
	<ul>
		   <li>June 2025 परीक्षा कार्यक्रम - <strong> <a href="#" id="getTimeTable">Click Here</a>	 
			</strong>
		</li> 
        <!-- <li class="mt-5">Model Paper  - <strong><a href="<?php //echo base_url('student_model_paper') ?>">Click Here</a></strong>
        </li> -->
	</ul>
	<div id="timeTable" class="mx-auto"> </div>
	<!-- <ul>
		<li>Dec 2021 परीक्षा कार्यक्रम - <strong><a target="_blank" href="<?=base_url('assets/instruction/') ?>examdate.pdf">Click Here</a></strong></li>
	</ul>
	<h5 class="mt-4 mb-3">आंसरशीट अपलोड करने की प्रक्रिया</h5>
	<ol>
		<li>उत्तर पुस्तिका के लिए फ्रंट पेज <a target="_blank" href="<?=base_url('assets/instruction/')?>Answer sheet Front Page.pdf"> Click Here</a>
		</li>
		<li>प्रत्येक पेपर के आंसरशीट हल करके उसकी PDF अपलोड कर सकते है| PDF फाइल की साइज 5 MB से ज्यादा नहीं होना चाहिए|</li>
	</ol> -->
</div>
   
<script>

$("#getTimeTable").on('click', function(){
            var course = $("#course_group_id_both").val();
            var class_id = $("#class_id").val();
            var mode = $("#mode").val();
       
            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $("#myLoader").show();
            $.ajax({
                method: "POST",
                url: '<?php echo site_url('center/center/getExamTimeTable'); ?>',
               
                data: {class_id : class_id,course : course,mode:mode,[csrfName]:csrfHash },
            })
            .done(function( msg ) {
                $('#myLoader').hide();
                $('#timeTable').html(msg);
            });
        });   
function PrintDiv() {
    var title="Time Table 2025";
    var contents = document.getElementById('ss').innerHTML;
    var frame1 = document.createElement('iframe');
    frame1.name = "frame1";
    frame1.style.position = "absolute";
    frame1.style.top = "-1000000px";
    document.body.appendChild(frame1);
    var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
    frameDoc.document.open();
     frameDoc.document.write(`<style>@page{size:landscape;}@tr{border: solid 1px #000;background-color:#E8F6FF;}</style><html><head><title>${title}</title>`);
    frameDoc.document.write('</head><body>');
    frameDoc.document.write(contents);
    frameDoc.document.write('</body></html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        document.body.removeChild(frame1);
    }, 500);
    return false;
}	 

</script>		