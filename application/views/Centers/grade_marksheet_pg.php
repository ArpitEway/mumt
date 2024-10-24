<style>
  table, th, td, table,.table th,.table td {
    border: 1px solid black;
    padding: 5px;
  }
/* table {
  border-spacing: 15px;
} */
.table-bordered td{
  border:0;
}


  table, th, td {
    border: 1px solid black;
    padding: -10px;
    font-size: 18px;
    vertical-align: middle !important;
    padding: 0.75rem !important;
    
  }

.table-bordered td{
  border:0;
}


@media print {
	.breakhere { page-break-before:always;  };
}
th.border.border-dark {
 vertical-align: middle;
}
.text-primary {
    color: #16447e !important;
}
</style>

<div id="printarea" style="width:1150px; margin:auto">
 <div class="border border-dark border-bottom-0">
   <div class="text-center py-3">
      <!-- <img src="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png"  width="100px;" /> -->
      <h1 class="text-center p-5" style="font-size:34px; color: #781e19;">Maharishi Mahesh Yogi Vedic Vishwavidyalaya</h1>
      <!-- <img src="<?=base_url()?>assets/images/maskgroup/Group1.png" class="img2" alt="">
      <h4 class="text-primary text-center mb-0">परीक्षा सत्र - जून 2022</h4> -->
      <h4 class="text-primary text-center mb-0">Examination Held In <?=$exam_session?></h4>
  </div>
</div>
<table class="table mb-0">
    <tbody>
        <tr>
            <th class="border-top-0 text-primary pl-3">Enrollment No.</th>
            <th class="border-top-0"><?php  echo $student->enrollment_no ?></th>
            <th class="border-top-0 text-primary pl-3">Roll No.</th>
            <th class="border-top-0"><?php echo  $student->roll_no; ?></th>
            <th rowspan="4" class="border-top-0 text-center" width="170px" height="180px"><img class="img img-thumbnail" src="<?=base_url('assets/student_image/').$student->session.'/'.$student->photo?>" ></th>
        </tr>
        <tr>
            <th class="border-top-0 text-primary pl-3">Name</th>
            <th class="border-top-0"><?php  echo $student->name ?></th>
            <th class="border-top-0 text-primary pl-3">F/H Name</th>
            <th class="border-top-0"><?php  echo $student->f_h_name ?></th>
        </tr>
        <tr>
            <th class="border-top-0 text-primary pl-3">Course</th>
            <th class="border-top-0"><?php  echo $student->course_name ?></th>
            <th class="border-top-0 text-primary pl-3">Class</th>
            <th class="border-top-0"><?php  echo $this->Common_model->getClassNameByClassId($student->class_id); ?></th>
        </tr>
        <!-- <tr>
            <th class="border-top-0 text-primary pl-3">College</th>
            <th class="border-top-0" colspan="3"><?php  echo $student->center_name.' ( '.$student->center_code.' ) '; ?></th>
        </tr> -->
    </tbody>
</table>
 <table class="table mb-0" >
      <tbody>
        <tr  id="tr_code"> 
          <td style="border-bottom: 1px solid; border-left: 1px solid; border-top: 1px solid; text-align: center"> Paper Code</td>
          <td style="border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; vertical-align: middle;"> <span class="ml-3">SUBJECT</span></td>
          <td style="border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; text-align: center ;vertical-align: middle">Credit</td>
          <td style="border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; text-align: center ;vertical-align: middle">Grade</td>
          <td style="border-bottom: 1px solid;border-right: 1px solid;border-top: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">Grade Point</td>
          <td style="border-bottom: 1px solid;border-right: 1px solid;border-top: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">Credit Point <br> (Credit x Grade Point)</td>
        </tr>
        <?php 
       
        $gradesheetData = $this->Gradesheet_model_pg->view_result($student,$student->student_id,$student->course_group_id,$student->class_id,$student->university_mode);
       
         ?>
      </tbody>
    </table>
    <?php if($gradesheetData['result'] != ''){ 
      
      ?>
    
      
      
    * Grade In Repeat Examination.<br><br>
    <table class="border border-dark m-auto w-100" >
        <tr>
          <td style="vertical-align: middle; text-align: center">SEMESTER</td>
          <td style="vertical-align: middle; text-align: center">TOTAL CREDIT</td>
          <td style="vertical-align: middle; text-align: center">OBTAINED CREDIT</td>
          <td style="vertical-align: middle; text-align: center">ADDITIONAL CREDIT</td>
          <td style="vertical-align: middle; text-align: center">SGPA</td>
          <td style="vertical-align: middle; text-align: center">ATTEMPT</td>
          <td style="vertical-align: middle; text-align: center">RESULT</td>
         
        </tr>
        <tr>
            <?php
            $class_name = explode(' ', $this->Common_model->getClassNameByClassId($student->class_id));
            $attemp_count = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$student->student_id,'class_id'=>$student->class_id,'exam_status'=>'B'));
            ?>
          <!-- <td>TOTAL CREDIT</td> -->
          <td class="text-center" style="vertical-align: middle;"><?= $class_name[0]?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['tot_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['obt_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;">-</td>
          <td class="text-center" style="vertical-align: middle;"><?= number_format((float)$gradesheetData['agpa'], 2, '.', '') ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=(count($attemp_count) == 0)?1:count($attemp_count)?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['result'] ?></td>
        </tr>
        <!-- <tr>
          <td>OBTAINED CREDIT</td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['obt_credit'] ?></td>
        </tr>
        <tr>
          <td>ADDITIONAL CREDIT</td>
          <td class="text-center" style="vertical-align: middle;">-</td>
        </tr>
        <tr>
          <td>AGPA</td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['agpa'] ?></td>
        </tr>
        <tr>
          <td>ATTEMPT</td>
          <td class="text-center" style="vertical-align: middle;">1</td>
        </tr>
        <tr>
          <td>RESULT</td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['result'] ?></td>
        </tr> -->
    </table><br>
    <?php
    }
    else{
      ?>
      <script type="text/javascript">
      $('#tr_code').css("display", "none");
     
  </script>
  <?php
    }
    ?>
    <div class="row" style=" margin-top: 1px;font-size: 18px;">
        <div class="col-12">
        <span style="font-weight : 900"> Disclaimer :</span>
        <span style="font-weight : bold">This information should not be treated as Marksheet.<br> 
          MMYVV is not responsible for any inadvertent error that may have crept in the results being published on INTERNET. The results published on internet are for immediate information to the examinee
      </span>
    </div>
   </div>
 </div>
<div class="form-group col-md-12 text-center mt-3"  id="print_btn">
  <button  type="button" onclick="printDiv('printarea')"  class="btn btn-primary font-weight-bold mr-2" >Print</button>
</div>
<script type="text/javascript">
	function printDiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

		$('#'+divName).css("margin-top"," 20px");
		
		$("#print_btn").css("display", "none");
		$(".offcanvas-footer").css("display", "none");
		$("#radio_btn_select").css("display", "none");
    $("#center").css("display", "none");
    $(".content-head").css("display", "none");
    $("#pro_remark").css("display", "none");
    $("#result_msg").css("display", "none");
    
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
