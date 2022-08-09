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
				
<div class="notification"> 
	<ul>
		 <li>July 2022 परीक्षा कार्यक्रम - <strong>Coming Soon !!<!--<a href="#" id="getTimeTable">Click Here</a>	 -->
			</strong>
		</li>
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
       
            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $("#myLoader").show();
            $.ajax({
                method: "POST",
                url: '<?php echo site_url('center/center/getExamTimeTable'); ?>',
               
                data: {class_id : class_id,course : course,[csrfName]:csrfHash },
            })
            .done(function( msg ) {
                $('#myLoader').hide();
                $('#timeTable').html(msg);
            });
        });   
function PrintDiv() {
    var title="Time Table 2022";
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