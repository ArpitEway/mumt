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
    padding: 0.5rem !important;
    
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
      
        $gradesheetData = $this->Gradesheet_old_model->view_result($exam_data->student_id,$exam_data->course_group_id,$exam_data->class_id,$exam_data->university_mode,$exam_data_id);
       
         ?>
      </tbody>
    </table>
    <?php if($gradesheetData['result'] != ''){ 
       $dept_ids = array(10,11,12,13,20,21,22,23,24,25,26,27,28,29,30);
       if($class->last_class == 'L' && !in_array($exam_data->center_id,$dept_ids)){
        $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$exam_data->course_group_id,'mode'=>$class->mode,'id!='=>$exam_data->class_id));
        $total_grade_point = 0;
        $total_course_credit = 0;
        $final_fail=0;
        $romanNumerals = [1 => 'I',2 => 'II',3 => 'III',4 => 'IV',5 => 'V',6 => 'VI',7 => 'VII',8 => 'VIII'
        ];
        $wordNumerals = [1 => 'One',2 => 'Two',3 => 'Three',4 => 'Four',5 => 'Five',6 => 'Six',7 => 'Seven',8 => 'Eight'
        ];
        ?>
        * Grade In Repeat Examination.<br><br>
         <table class="border border-dark m-auto w-100" >
        <tr>
          <td style="vertical-align: middle; text-align: center">YEAR/SEMESTER</td>
          <td style="vertical-align: middle; text-align: center">TOTAL CREDIT</td>
          <td style="vertical-align: middle; text-align: center">OBTAINED CREDIT</td>
          <td style="vertical-align: middle; text-align: center">AGPA</td>
          <td style="vertical-align: middle; text-align: center">ATTEMPT</td>
         
        </tr>
        <?php
        foreach($classes as $cls){
            $this->db->order_by('id','desc');
            $this->db->limit(1);
            $old_result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$exam_data->student_id,'class_id'=>$cls->id));
            $old_count = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$exam_data->student_id,'class_id'=>$cls->id));
            $gradeData = $this->Gradesheet_model->view_old_results($exam_data->student_id,$exam_data->course_group_id,$old_result[0]->class_id,$exam_data->university_mode, $old_result[0]->id, $old_result[0]->exam_status);
            
            $total_grade_point += number_format((float)$gradeData['agpa'], 2, '.', '') * $gradeData['obt_credit']; 
            $total_course_credit +=$gradeData['tot_credit'];
            ?>
             <tr>
          <!-- <td>TOTAL CREDIT</td> -->
          <td class="text-center" style="vertical-align: middle;"><?=$romanNumerals[$cls->class_order] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradeData['tot_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradeData['obt_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?= number_format((float)$gradeData['agpa'], 2, '.', '') ?></td>
          <td class="text-center" style="vertical-align: middle;"><?= $wordNumerals[count($old_count)]?></td>
          
        </tr>
        <?php
        }
        if($gradesheetData['result'] == 'Fail'){
            $final_fail++;
        }
        $total_grade_point += number_format((float)$gradesheetData['agpa'], 2, '.', '') * $gradesheetData['obt_credit']; 
        $total_course_credit +=$gradesheetData['tot_credit'];
        if($final_fail > 0){
            $cgpa = ' - ';
            $div = " - ";
        }else{
            $cgpa = number_format((float)($total_grade_point/$total_course_credit), 2, '.', '');
            if($cgpa>=8.0){
                $div = "First Division with Distinction";
                }elseif($cgpa<8.0 && $cgpa>=6.50){
                $div  = "First Division";
                }elseif($cgpa<6.50 && $cgpa>=5.00){
                $div  = "Second Division";
                }else{
                $div = "Pass";
                }
        }
       
        ?>
        <tr>
            <?php
            $attemp_count = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$exam_data->student_id,'class_id'=>$exam_data->class_id,'exam_status'=>'B'));
            ?>
          <!-- <td>TOTAL CREDIT</td> -->
          <td class="text-center" style="vertical-align: middle;"><?= $romanNumerals[$class->class_order]?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['tot_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['obt_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?= number_format((float)$gradesheetData['agpa'], 2, '.', '') ?></td>
          <td class="text-center" style="vertical-align: middle;"><?= $wordNumerals[1]?></td>
        </tr>
        </table><br>
        <table class="border border-dark m-auto w-100" >
        <tr>
            <td colspan="4" align="center">
                Final Result - <strong><?=$gradesheetData['result']?></strong>
            </td>
        </tr>
        <tr>
          <td style="vertical-align: middle; text-align: center">Total Credits</td>
          <td style="vertical-align: middle; text-align: center">CGPA</td>
          <td style="vertical-align: middle; text-align: center">Equivalent Percentage</td>
          <td style="vertical-align: middle; text-align: center">Division</td>  
        </tr>
        <tr>
            <td class="text-center" style="vertical-align: middle;"><?= $total_course_credit?></td>
            <td class="text-center" style="vertical-align: middle;"><?= ($gradesheetData['result'] =='FAIL')?' - ':$cgpa ?></td>
            <td class="text-center" style="vertical-align: middle;"><?= ($gradesheetData['result'] =='FAIL')?' - ':($cgpa*10).'%' ?></td>
            <td class="text-center" style="vertical-align: middle;"><?= ($gradesheetData['result'] =='FAIL')?' - ':$div ?></td>
        </tr>
    </table>
        <?php
       }else{
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
        <?php
           $class_name = explode(' ', $this->Common_model->getClassNameByClassId($exam_data->class_id));
           $attemp_count = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$exam_data->student_id,'class_id'=>$exam_data->class_id,'exam_status'=>'B'));
          ?>
          <!-- <td>TOTAL CREDIT</td> -->
          <td class="text-center" style="vertical-align: middle;"><?= $class_name[0]?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['tot_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=$gradesheetData['obt_credit'] ?></td>
          <td class="text-center" style="vertical-align: middle;">-</td>
          <td class="text-center" style="vertical-align: middle;"><?= number_format((float)$gradesheetData['agpa'], 2, '.', '') ?></td>
          <td class="text-center" style="vertical-align: middle;"><?=($exam_data->exam_status == 'R')?1:(count($attemp_count)+1)?></td>
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
