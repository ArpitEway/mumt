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
  </style>
</head>
<body>
  <center>
    <?php 

    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
    $marksheet_variables = $this->Common_model->getRecordById('marksheet_variables','class_id',$class_id);
    $classData = $this->Common_model->getRecordById('class_master','id',$class_id);
    foreach($students as $student)
    {
      $papers = $this->Common_model->student_info_for_result($student->student_id,$student->class_id);
      ?>
      <fieldset id="printarea" class="breakhere" style="width:90%;border: 0px solid #22316C;"> 
        <div align="left"> MS No. <?php echo $student->marksheet_no; ?> </div>
        <table align="center" border="0" width="100%">
          <tbody>
            <tr>
              <td height="100" colspan="2" valign="bottom">
                <center>
                  <strong><?php echo $student->course_name .' '. $this->Common_model->romanClassName($this->Common_model->getClassNameByClassId($student->class_id)); ?> <?=$marksheet_variables->exam_session ?></strong>
                </center>
              </td>
            </tr>
            <tr>
              <td align="center" height="120" colspan="2">
                <table class="mytable" border="0" cellpadding="2" cellspacing="2" width="100%">
                  <tbody>
                    <tr>
                      <td class="Normaltext" colspan="2">
                        <div align="center"><font size="4">  &nbsp; </font></div>
                      </td>
                      <td class="Normaltext">
                        <div align="center"><font size="4">  Regular </font></div>
                      </td>
                    </tr>
                    <tr>
                      <td width="35%" class="Normaltext" align="left"><div align="left">Roll No</div></td>
                      <td width="53%" class="resultText">
                        <div align="left">
                          <span id="lblSemesterGrading" style="color:Black;"><?php echo $student->roll_no; ?></span>
                          <!-- <div style="float:right"> &nbsp;&nbsp;&nbsp; Mode - Distance Education </div> -->
                        </div>
                      </td>
                      <td align="center" width="18%" rowspan="4">
                        <img border="1"  class="student_image" src="<?= base_url('assets/student_image/'.$student->session.'/'.$student->photo) ?>" width="90px" height="105px">
                      </td>
                    </tr>
                    <tr>
                      <td class="Normaltext" align="left">
                        <div align="left">Enrolment / Registration No.</div>
                      </td>
                      <td class="resultText">
                        <div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo  $student->enrollment_no;; ?></span></div>
                      </td>
                    </tr>
                    <tr>
                      <td class="Normaltext" align="left" width="29%">
                        <div align="left">Name of the Candidate</div>
                      </td>
                      <td class="resultText"><div align="left">
                        <span id="lblSemesterGrading" style="color:Black;"><?php echo  $student->name; ?></span></div>
                      </td>
                    </tr>
                    <tr>
                      <td class="Normaltext" align="left" width="29%"><div align="left">Father's / Husband's Name</div></td>
                      <td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo strtoupper( $student->f_h_name); ?></span></div></td>
                    </tr>
                    <tr>
                      <td class="Normaltext" align="left" width="29%"><div align="left">Department</div></td>
                      <td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;">
                        <?php if ($student->center_id==10) {
                          echo "University Teaching Department Karaundi";
                        }else if ($student->center_id==12) {
                          echo "Shiksha Vibhag, Lamti Jabalpur";
                        } ?>
                      </span></div></td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td height="72" colspan="2">
                <fieldset style="border: 0px solid #22316C;">       
                  <div style="min-height:450px;margin-top: 20px;">
                    <table id="" style="width:100%;" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tbody>
                        <tr style="font-family:Arial, Helvetica, sans-serif; font-size:11px" align="center">
                          <td width="11%" rowspan="3" align="left" style="font-size:11px" scope="col"><span class="style7">Paper Code</span></td>
                          <td width="42%" rowspan="3" style="font-size:11px" scope="col"><div align="left" class="style7">Paper Name</div></td>    
                          <td  colspan="4"><strong><u> Examination Scheme</u></strong></td>              
                          <td colspan="2"><strong><u>Obtained Marks</u></strong></td>
                          <td width="8%" rowspan="3" scope="col"><strong><u>Total</u></strong></td>
                        </tr>
                        <tr style="font-family:Verdana;font-size:9px;" align="center">
                          <td scope="col" colspan="2"><strong><u>Th/Pr/Pj</u></strong></td>
                          <td scope="col" colspan="2"><strong><u>Internal</u></strong></td>

                          <td width="8%" rowspan="2" scope="col" style="text-align:left;padding-left:10px;"><strong><u>Th/Pr/Pj</u></strong></td>
                          <td width="6%" rowspan="2" scope="col"><strong><u>Internal</u></strong></td>
                        </tr>
                        <tr style="font-family:Verdana;font-size:9px;" align="center">
                          <td width="6%" scope="col"><strong><u>Max</u></strong></td>
                          <td width="6%" scope="col"><strong><u>Min</u></strong></td>
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
                        $int_fail_count = 0 ;
                        $abs_count = 0 ;
                        $tot_std_marks = 0;
                        foreach($papers as $marks){
                          if($marks->type=='theory'){
                            $tot_std_marks += $marks->theory_marks+$marks->int_marks;
                            $tot_marks += $marks->max_theory_marks+$marks->max_internal_marks;
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
                            if($marks->int_marks<$marks->min_internal_marks)
                            {
                              $result ="FAIL";
                              $int_fail_count++ ;
                            }
                            if($marks->int_marks=="N")
                            {
                              $result='FAIL';
                            }
                          }else{
                            if ($classData->practical_internal_marks=='Y') {
                              $tot_std_marks += $marks->p_marks+$marks->int_marks;
                              $tot_marks += $marks->max_theory_marks+$marks->max_internal_marks;
                              if($marks->p_marks<$marks->min_theory_marks){
                                $result = "FAIL";
                                $fail_count++;
                                $fali_tot_marks += $marks->p_marks;
                                $require_tot_marks += $marks->min_theory_marks;
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
                        }

// $aggregate_per =   ($tot_std_marks/$tot_marks) * 100;     
                        $require_grace_marks = $require_tot_marks-$fail_tot_marks;
                        if ($fail_count<2 && $require_grace_marks<4 && $int_fail_count==0 && $fail_count!=0 && $require_grace_marks!=0 && $abs_count==0) {
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
                            <td align="center" ><span class="style4"><?=($paper->type=='theory' || $classData->practical_internal_marks=='Y') ? $paper->max_internal_marks : '-'; ?></span>
                            </td>
                            <td align="center" ><span class="style4">
                              <?=($paper->type=='theory' || $classData->practical_internal_marks=='Y') ? $paper->min_internal_marks : '-'; ?></span>
                            </td>
                            <td align="left" ><span class="style4" style="padding-left:10px;">
                              <?php
                              if ($paper->type=='theory') {
                                if(($paper->theory_marks <  $paper->min_theory_marks || $paper->int_marks <  $paper->min_internal_marks) && $check_grace_marks==false){
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
                                if ($classData->practical_internal_marks=='Y') {
                                  if($paper->p_marks<$paper->min_theory_marks || $paper->int_marks<$paper->min_internal_marks){
                                    echo $paper->p_marks.' F';
                                  }elseif($paper->p_marks=='ABS'){
                                    echo 'ABS F';
                                  }else{
                                    echo $paper->p_marks;
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
                              }

                              ?>
                            </span></td>
                            <td align="left" class="style4"><span class="style2" style="padding-left:10px;">
                              <?=($paper->type=='theory' || $classData->practical_internal_marks=='Y') ? $paper->int_marks : '-'; ?></span>
                            </td>
                            <td align="left" class="style2"><span class="style4" style="padding-left:10px;">
                              <?php 
                              if ($paper->type=='theory') {
                                if($paper->int_marks<$paper->min_internal_marks || $paper->theory_marks<$paper->min_theory_marks){
                                  echo  $paper->theory_marks +  $paper->int_marks . '' ;
                                  echo ($check_grace_marks) ? ' G' : ' F';
                                }elseif($paper->theory_marks=="ABS"){
                                  echo 'ABS'. ' F' ;
                                }else{
                                  echo $paper->theory_marks + $paper->int_marks;
                                }
                              }else{
                                if($classData->practical_internal_marks=='Y') {
                                  if($paper->int_marks<$paper->min_internal_marks || $paper->p_marks<$paper->min_theory_marks && $check_grace_marks==false){
                                    echo  $paper->p_marks +  $paper->int_marks . ' F' ; 
                                  }elseif($paper->p_marks=="ABS" || $paper->int_marks=="ABS"){
                                    echo 'ABS'. ' F' ;
                                  }else{
                                    echo $paper->p_marks + $paper->int_marks ;
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
                      <?php  echo $generator->getBarcode($marksheet_variables->bar_code_no.$student->roll_no, $generator::TYPE_CODE_128,2,25); ?>
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
    <?php } ?>
  </center>
</body>
</html>
