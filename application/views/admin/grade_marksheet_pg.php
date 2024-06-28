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
      
        $gradesheetData = $this->GradeSheet_old_model_pg->view_result($exam_data->student_id,$exam_data->course_group_id,$exam_data->class_id,$exam_data->university_mode,$exam_data_id);
       
         ?>
      </tbody>
    </table>
    <?php if($gradesheetData['result'] != ''){ 
      
      ?>
    
      
      
    * Grade In Repeat Examination.<br><br>
    <table class="border border-dark m-auto w-100" >
        <tr>
          <td style="vertical-align: middle; text-align: center">YEAR/SEMESTER</td>
          <td style="vertical-align: middle; text-align: center">TOTAL CREDIT</td>
          <td style="vertical-align: middle; text-align: center">OBTAINED CREDIT</td>
          <td style="vertical-align: middle; text-align: center">ADDITIONAL CREDIT</td>
          <td style="vertical-align: middle; text-align: center">AGPA</td>
          <td style="vertical-align: middle; text-align: center">ATTEMPT</td>
          <td style="vertical-align: middle; text-align: center">RESULT</td>
         
        </tr>
        <tr>
          <!-- <td>TOTAL CREDIT</td> -->
          <?php
           $class_name = explode(' ', $this->Common_model->getClassNameByClassId($exam_data->class_id));
           $attemp_count = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$exam_data->student_id,'class_id'=>$exam_data->class_id));
          ?>
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
    
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
