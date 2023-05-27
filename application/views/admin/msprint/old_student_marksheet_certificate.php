<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link href="css/print_Marksheet.css" rel="stylesheet" type="text/css">
  <title><?php echo (isset($title)) ? $title : ''; ?></title>
  <style type="text/css">
    
    @media print {
      body {
        -webkit-print-color-adjust: exact;
        -moz-print-color-adjust: exact;
        -ms-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
    body {
      color:#000000;
      font-family: "Times New Roman";

    }
    .style1 {
      font-size: 13px;
      font-weight: bold;
    }
    .style4 {font-size: 14px; font-weight: bold; }
    .style7 {font-size: 12px; font-weight: bold; }
    .barcode img{
      height: 25px;
    }
    .student_image{
      image-orientation: none;
    }
    @media print {
      .breakhere { page-break-after:always;  };
    }
    th.border.border-dark {
      vertical-align: middle;
    }
    tr.rowHeight td {
        padding: 0px;
    }
  </style>
</head>
<body>
  <center>
    <?php
    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    $marksheet_variables = $this->Common_model->getRecordById('marksheet_variables','class_id',$class_id);
    $classData = $this->Common_model->getRecordById('class_master','id',$class_id);
       $papers = $old_result_data;
       $isFinalClass = $this->Common_model->hasOneClass($classData->course_group_id);
       if($isFinalClass){
         $course_duration = '(One Year Course)';
       }else if($classData->course_group_id == 36 || $classData->course_group_id == 37){
         $course_duration = 'Six Months Course';
       }else{
         $course_duration = $this->Common_model->romanClassName($classData->class_name);
       }
      ?>
      <fieldset id="printarea" class="breakhere" style="width:90%;border: 0px solid #22316C;"> 
        <div align="left"> MS No. <?php echo $exam_data->marksheet_no; ?> </div>
        <table align="center" border="0" width="100%">
          <tbody>
            <tr>
              <td height="100" colspan="2" valign="bottom">
                <center>
                  <strong><?php echo $exam_data->course_name .$course_duration.$exam_data->exam_year ?></strong>
                </center>
              </td>
            </tr>
            <tr>
              <td align="center" height="120" colspan="2">
                <table class="mytable" border="0" cellpadding="2" cellspacing="2" width="100%">
                  <tbody>
                    <!-- <tr>
                      <td class="Normaltext" colspan="2">
                        <div align="center"><font size="4">&nbsp; </font></div>
                      </td>
                      <td class="Normaltext">
                        <div align="center"><font size="4">Regular </font></div>
                      </td>
                    </tr> -->
                    <tr class="rowHeight">
                      <td width="35%" class="Normaltext" align="left"><div align="left">Roll No</div></td>
                      <td width="53%" class="resultText">
                        <div align="left">
                          <span id="lblSemesterGrading" style="color:Black;"><?php echo $exam_data->roll_no; ?></span>
                          <!-- <div style="float:right"> &nbsp;&nbsp;&nbsp; Mode - Distance Education </div> -->
                        </div>
                      </td>
                      <td align="center" width="18%" rowspan="4">
                        <img border="1"  class="student_image" src="<?= base_url('assets/student_image/'.$exam_data->session.'/'.$exam_data->photo) ?>" width="90px" height="105px">
                      </td>
                    </tr>
                    <tr class="rowHeight">
                      <td class="Normaltext" align="left">
                        <div align="left">Enrolment / Registration No.</div>
                      </td>
                      <td class="resultText">
                        <div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo  $exam_data->enrollment_no;; ?></span></div>
                      </td>
                    </tr>
                    <?php if($exam_data->university_mode=='PVT'){ ?>
                    <tr class="rowHeight">
                      <td class="Normaltext" align="left" width="29%">
                        <div align="left">Category</div>
                      </td>
                      <td class="resultText"><div align="left">
                        <span id="lblSemesterGrading" style="color:Black;">N/C</span></div>
                      </td>
                    </tr>
                    <?php } ?>
                    <tr class="rowHeight">
                      <td class="Normaltext" align="left" width="29%">
                        <div align="left">Name of the Candidate</div>
                      </td>
                      <td class="resultText"><div align="left">
                        <span id="lblSemesterGrading" style="color:Black;"><?php echo  $exam_data->name; ?></span></div>
                      </td>
                    </tr>
                    <tr class="rowHeight">
                      <td class="Normaltext" align="left" width="29%"><div align="left">Father's / Husband's Name</div></td>
                      <td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo strtoupper( $exam_data->f_h_name); ?></span></div></td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td height="72" colspan="2">
                <fieldset style="border: 0px solid #22316C;">       
                  <div style="min-height:420px;margin-top: 20px;">
                    <table id="" style="width:100%;" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tbody>
                        <tr style="font-family:Arial, Helvetica, sans-serif; font-size:11px" align="center">
                          <td width="11%" rowspan="3" align="left" style="font-size:11px" scope="col"><span class="style7">Paper Code</span></td>
                          <td width="42%" rowspan="3" style="font-size:11px" scope="col"><div align="left" class="style7">Paper Name</div></td>    
                          <td  colspan="2"><strong><u> Examination Scheme</u></strong></td>              
                          <td colspan="2" class="text-center"><strong><u>Obtained Marks</u></strong></td>
                          
                        </tr>
                        <tr style="font-family:Verdana;font-size:9px;" align="center">
                          <td scope="col" colspan="2"><strong><u>Th/Pr/Pj</u></strong></td>
                          <td width="8%" rowspan="2" scope="col" class="text-center"><strong><u>Th/Pr/Pj</u></strong></td>
                          <td width="8%" rowspan="2" colspan="2" scope="col"><strong><u>Total</u></strong></td>
                        </tr>
                        <tr style="font-family:Verdana;font-size:9px;" align="center">
                          <td width="6%" scope="col"><strong><u>Max</u></strong></td>
                          <td width="6%" scope="col"><strong><u>Min</u></strong></td>
                        </tr>
                        <tr>
                          <td colspan="9">&nbsp;</td>
                        </tr>
                        <?php
                        $check_grace_marks = false;
                        $fail_count = 0;
                        $fail_tot_marks = 0;
                        $require_tot_marks = 0;
                        $tot_marks = 0;
                        $result = "PASS";
                        $abs_count = 0 ;
                        $tot_std_marks = 0;
                        foreach($papers as $marks){
                          if($marks->type=='theory'){
                            $tot_std_marks += $marks->theory_marks;
                            $tot_marks += $marks->max_theory_marks;
                            if($marks->theory_marks<$marks->min_theory_marks){
                              $result = "FAIL";
                              $fail_count++;
                              $fail_tot_marks += $marks->theory_marks;
                              $require_tot_marks += $marks->min_theory_marks;
                            }
                            if($marks->theory_marks == 'ABS'){
                              $result = 'FAIL';
                              $abs_count++ ;
                            }
                          }else{
                            $tot_std_marks += $marks->p_marks;
                            $tot_marks += $marks->max_theory_marks;
                            if($marks->p_marks<$marks->min_theory_marks){
                              $result = "FAIL";
                              $fail_count++;
                              $fali_tot_marks += $marks->p_marks;
                              $require_tot_marks += $marks->min_theory_marks;
                            }
                          }
                        }

// $aggregate_per =   ($tot_std_marks/$tot_marks) * 100;     
                        $require_grace_marks = $require_tot_marks-$fail_tot_marks;
                        if ($fail_count<2 && $require_grace_marks<4 && $fail_count!=0 && $require_grace_marks!=0 && $abs_count==0) {
// echo $require_grace_marks ;
                          $check_grace_marks = true;
                          $result = "PASS BY GRACE";
                        }
                        foreach($papers as $paper)
                        {
                          ?>
                          <tr>
                            <td colspan="9">&nbsp;</td>
                          </tr>
                          <tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="middle">
                            <td style="margin-top:2px;" align="left"><strong><?php echo  $paper->paper_code; ?></strong></td>
                            <td align="left"><strong><?php  echo $paper->paper_name ;  ?></strong></td>
                            <td align="center" ><span class="style4">
                              <?php echo  $paper->max_theory_marks;?></span>
                            </td>
                            <td align="center" ><span class="style4">
                              <?php echo  $paper->min_theory_marks; ?></span>
                            </td>
                            
                            <td align="center" ><span class="style4" >
                              <?php
                              if ($paper->type=='theory') {
                                if(($paper->theory_marks <  $paper->min_theory_marks) && $check_grace_marks==false){
                                  echo $paper->theory_marks . ' F' ;
                                }elseif($paper->theory_marks<$paper->min_theory_marks){
                                  echo $paper->theory_marks; 
                                  echo ($check_grace_marks) ? ' G' : '';
                                }elseif($paper->theory_marks=='ABS'){
                                  echo 'ABS F';
                                }else{
                                  echo $paper->theory_marks;
                                }
                              }else{
                                  if($paper->p_marks<$paper->min_theory_marks){
                                    echo $paper->p_marks.' F';
                                  }elseif($paper->p_marks=='ABS'){
                                    echo 'ABS F';
                                  }else{
                                    echo $paper->p_marks;
                                  }
                              }

                              ?>
                            </span></td>
                            <td align="center" class="style2"><span class="style4">
                              <?php 
                              if ($paper->type=='theory') {
                                if($paper->theory_marks<$paper->min_theory_marks){
                                  echo  $paper->theory_marks . '' ;
                                  echo ($check_grace_marks) ? ' G' : ' F';
                                }elseif($paper->theory_marks=="ABS"){
                                  echo 'ABS'. ' F' ;
                                }else{
                                  echo $paper->theory_marks;
                                }
                              }else{
                                if($paper->p_marks<$paper->min_theory_marks && $check_grace_marks==false){
                                  echo  $paper->p_marks . ' F' ; 
                                }elseif($paper->p_marks=="ABS"){
                                  echo 'ABS'. ' F';
                                }else{
                                  echo $paper->p_marks;
                                }
                              }
                            ?></span>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody></table>
                  </div>
                  <table border="0" cellpadding="0" height="112" width="100%">
                    <tbody>
                      <tr>
                        <td  colspan="8">  </td>
                      </tr>
                      <tr>
                        <td height="20">&nbsp;</td>
                        <td align="center"></td>
                        <td align="center"> </td>
                        <td> <strong> <?php // echo  $t_max; ?></strong>  </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td align=""><strong><u>Total</u></strong></td>
                        <td align="">&nbsp;&nbsp;&nbsp;&nbsp;<strong><u><?php echo $tot_std_marks; ?></u></strong> </td>
                      </tr>
                      <tr>
                        <td colspan="8"></td>
                      </tr>
                      <tr>
                        <td width="22%" height="20"><strong><?php //echo $year; //$dt_row['year']; ?></strong></td>
                        <td width="13%" style="text-align: center"><strong><?php //echo $year_num;//$dt_row['sem']; ?></strong></td>
                        <td width="30%">&nbsp;</td>
                        <td width="9%"><!--<div align="center"><strong>Grand-Total</strong></div>--></td>
                        <td width="1%"><div align="center"></div></td>
                        <td width="8%"><div align="center"><strong></strong></div></td>
                        <td width="7%"><div align=""><strong>Result</strong></div></td>
                        <td width="10%"><div align="center"><strong><div align="left">&nbsp;&nbsp;&nbsp;<b>
                          <?php echo $result ; ?>
                        </b></div></strong></div>
                      </td>
                    </tr>
                    <tr>
                      <td height="20" ><strong>Obtained Marks</strong></td>
                      <td style="text-align: center"><b><?php echo  $tot_std_marks ; ?></b></td>
                      <td>&nbsp;</td>
                      <td> <div align="center"><b></b></div></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;  </td>
                      <td>
                        <strong>&nbsp; </strong>
                      </td>
                      <td> <div align="center"><b> 
                      </b></div></td>
                    </tr>
                    <tr>
                      <td height="20"><strong>Maximum Marks</strong></td>
                      <td style="text-align: center"><b><?php echo $tot_marks ; ?></b></td>
                      <td>&nbsp;</td>
                      <td><div align="center"><b></b></div></td>
                      <td></td>
                      <td><div align="center"></div></td>
                      <td><strong>&nbsp;</strong></td>
                      <td> <div align="center"><strong></strong></div></td>
                    </tr>
                    <tr>
                      <td colspan="8">
                        <strong>Total Marks Obtained (in words)</strong> &nbsp;&nbsp;<strong><?php echo  $this->numbertowordconvertsconver->convert_number("$tot_std_marks")?></strong>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </fieldset>
              <!-- if starts -->
              <tr>
                <td align="left" colspan="2">
                  <table width="100%" style="margin-top:50px">
                    <tr>
                    </tr>
                  </table>    
                </td>
              </tr>
              <tr>
                <td width="17" align="center">
                  <div align="left">
                    <?php echo "Date :".$marksheet_variables->result_date; ?></div></td>
                  </tr>
                  <tr class="">
                    <td colspan="">
                      <?php 
                       $arr=explode(" ",$exam_data->exam_year);
                       $first=substr($arr[1], -2);
                       $second=date('m',strtotime($arr[0]));
                       $barcode_no=$first.$second.$exam_data->roll_no;

                      echo $generator->getBarcode($barcode_no, $generator::TYPE_CODE_128,2,25); ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                  </td>
                </tr>
              </td>
            </tr>
          </tbody>
        </table>
      </fieldset>
  </center>
</body>
</html>