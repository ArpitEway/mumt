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

</style>

<style>
  table, th, td {
    border: 1px solid black;
    padding: -10px;
    font-size: 18px;
  }
/* table {
  border-spacing: 15px;
} */
.table-bordered td{
  border:0;
}
.table td  {
  padding :2px ;
}
.table th  {
  padding :2px ;
}
{
  height: 180px;
  width: auto;
  object-fit: cover;
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
      <h4 class="text-primary text-center mb-0">Examination Held In Aug 2022</h4>
  </div>
</div>
<table class="table mb-0">
    <tbody>
        <tr>
            <th class="border-top-0 text-primary pl-3">Enrollment No.</th>
            <th class="border-top-0"><?php  echo $student->enrollment_no ?></th>
            <th class="border-top-0 text-primary pl-3">Roll No.</th>
            <th class="border-top-0"><?php echo  $student->roll_number; ?></th>
            <th rowspan="4" class="border-top-0 text-center" width="170px" height="180px"><img class="img img-thumbnail" src="<?=base_url('assets/student_image/').$student->photo?>" ></th>
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
            <th class="border-top-0"><?php  echo $this->Common_model->getClassNameByClassId($student->old_class_id); ?></th>
        </tr>
        <!-- <tr>
            <th class="border-top-0 text-primary pl-3">College</th>
            <th class="border-top-0" colspan="3"><?php  echo $student->center_name.' ( '.$student->center_code.' ) '; ?></th>
        </tr> -->
    </tbody>
</table>
 <table class="table mb-0">
      <tbody>
        <tr>
          <td style="border-bottom: 1px solid; border-left: 1px solid; border-top: 1px solid; text-align: center">संकेतांक <br> Paper Code</td>
          <td style="border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; vertical-align: middle;"><span class="ml-3">विषय:</span> <br> <span class="ml-3">SUBJECT</span></td>
          <td style="border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; text-align: center ;vertical-align: middle">Credit</td>
          <td style="border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; text-align: center ;vertical-align: middle">Grade</td>
          <td style="border-bottom: 1px solid;border-right: 1px solid;border-top: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">Grade Point</td>
          <td style="border-bottom: 1px solid;border-right: 1px solid;border-top: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">Credit Point <br> (Credit x Grade Point)</td>
        </tr>
        <?php 
        // echo $student->old_class_id;die;
        $gradesheetData = $this->Gradesheet_model->view_result($student->student_id,$student->course_group_id,$student->old_class_id,$student->mode);
        // print_r($gradesheetData);die;
         ?>
      </tbody>
    </table>
    * Grade In Repat Examination.<br><br>
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
          <td class="text-center" style="vertical-align: middle;">I</td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['tot_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['obt_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;">-</td>
          <td class="text-center" style="vertical-align: middle;"><?= round($gradesheetData['agpa'],2) ?></td>
          <td class="text-center" style="vertical-align: middle;">1</td>
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
		$("#first_div").css("display","none");
		$("#print_btn").css("display", "none");
		$("#submit_btn").css("display", "none");
		$("#title_nm").css("display", "none");
		$("#institute").css("display", "none");
		$("#head_img").css("display", "none");
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
