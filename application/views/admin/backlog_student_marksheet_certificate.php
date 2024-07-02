<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <link href="css/print_Marksheet.css" rel="stylesheet" type="text/css">
  <title><?php echo (isset($title)) ? $title : ''; ?></title>
  <style type="text/css">
    tr.rowHeight td {
        padding: 0px;
    }
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
    .tdFont{
 font-size:13px;
 font-weight:bold;
    }
    .student_image{
      /* image-orientation: none; */
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
   // $border = ($classData->admission_permission == 'Y')?'border: 1px solid black;margin-top:20px;':'border: 0px solid #22316C;';
   $border ='border: 0px solid #22316C;';
    $isOneClass = $isFinalClass = $this->Common_model->hasOneClass($course_group_id);
     
    if($isFinalClass){
      $course_duration = '(One Year Course)';
    }else if($course_group_id == 36 || $course_group_id == 37){
      $course_duration = 'Six Months Course';
    }else{
      $course_duration = $this->Common_model->romanClassName($classData->class_name);
    }
    foreach($students as $student)
    {
      $papers = $this->Common_model->student_info_for_backlog_result($student->student_id,$student->class_id,$student->id);
      ?>
      <fieldset id="printarea" class="breakhere" style="width:90%;border: 0px solid #22316C;"> 
        <div align="left"> MS No. <?php echo $student->back_marksheet_no; ?> </div>
        <table align="center" border="0" width="100%">
          <tbody>
            <tr>
              <td height="130" colspan="2" valign="bottom">
                <center>
                  
                  <strong><?php echo $student->course_name .' '.$course_duration.' Backlog '.$marksheet_variables->exam_session ?></strong>
                </center>
              </td>
            </tr>
            <tr>
              <td align="center" height="120" colspan="2">
                <table class="mytable" border="0" cellpadding="2" cellspacing="2" width="100%">
                  <tbody>
                    <?php
                  if($student->mode=='REG'){
                    ?>
                 
                    <tr>
                      <td class="Normaltext" colspan="2">
                        <div align="center"><font size="4">  &nbsp; </font></div>
                      </td>
                      <td class="Normaltext">
                        <div align="center"><font size="4">Regular </font></div>
                      </td>
                    </tr>
                    <?php
                     }
                    ?>
                    <tr class="rowHeight">
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
                    <tr class="rowHeight">
                      <td class="Normaltext" align="left">
                        <div align="left">Enrolment / Registration No.</div>
                      </td>
                      <td class="resultText">
                        <div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo  $student->enrollment_no;; ?></span></div>
                      </td>
                    </tr>
                    <?php if($student->mode=='PVT'){ ?>
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
                        <span id="lblSemesterGrading" style="color:Black;"><?php echo  $student->name; ?></span></div>
                      </td>
                    </tr>
                    <tr class="rowHeight">
                      <td class="Normaltext" align="left" width="29%"><div align="left">Father's / Husband's Name</div></td>
                      <td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo strtoupper( $student->f_h_name); ?></span></div></td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td height="72" colspan="2">
                <fieldset style="<?= $border?>">       
                  <div style="min-height:420px;margin-top: 40px;">
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
                          if($student->mode=='REG'){
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
                        }else{

                          if($marks->type=='theory'){
                            $tot_std_marks += $marks->theory_marks;
                            $tot_marks += $marks->private_max_theory_marks;
                            if($marks->theory_marks<$marks->private_min_theory_marks){
                              $result = "FAIL";
                              $fail_count++;
                              $fail_tot_marks += $marks->theory_marks;
                              $require_tot_marks += $marks->private_min_theory_marks;
                            }
                            if($marks->theory_marks == 'ABS'){
                              $result = 'FAIL';
                              $abs_count++ ;
                            }
                          }else{
                            $tot_std_marks += $marks->p_marks;
                            $tot_marks += $marks->private_max_theory_marks;
                            if($marks->p_marks<$marks->private_min_theory_marks){
                              $result = "FAIL";
                              $fail_count++;
                              $fali_tot_marks += $marks->p_marks;
                              $require_tot_marks += $marks->private_min_theory_marks;
                            }
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
                        $flag = 1;
                        $tflag = 1;
                        foreach($papers as $paper)
                        {
                          $paper_name = explode(' - ',$paper->paper_name);
                         
                          ?>
                          <tr>
                            <td colspan="9">&nbsp;</td>
                          </tr>
                          <?php 
                          if($class_id == 169 && $student->mode=='REG' ){
                          if($tflag == 1) { echo '<tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" valign="middle" align="center">'.
                        '<td style="margin-top:2px;" align="left">'.'</td>'.
                            '<td colspan="8" align="left">'.'<strong>'.
                          '<u>'.'Theory'.'</u>' .':'.'</strong>'.'</td>'.
                          '</tr>'.'<tr>'
                          .'<td colspan="9">'.'&nbsp;'.'</td>'.
                        '</tr>';}elseif
                          ($paper_name[0] == 'Moukhiki' && $flag == 1){ echo ' <tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" valign="middle" align="center">'.'<td style="margin-top:2px;" align="left">'.'</td>'.
                          '<td colspan="8" align="left">'.'<strong>'.
                        '<u>'.'Viva-Voce'.'</u>'.':'.'</strong>'.'</td>'.'</tr>'.'<tr>'
                        .'<td colspan="9">'.'&nbsp;'.'</td>'.
                      '</tr>';}else{ echo'';};
                          }
                          ?>
                          <tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="middle">
                         
                            <td style="margin-top:2px;" align="left"><strong class="tdFont"><?php echo  $paper->paper_code; ?></strong></td>
                           
                            <td align="left"><strong class="tdFont"><?php if($paper_name[0] == 'Moukhiki'){ 
                                echo (is_null($paper_name[1]))?$paper_name[0]:$paper_name[1];
                              }else {
                                 echo $paper->paper_name;
                               } ?></strong></td>
                            <td align="center" ><span class="style4 tdFont">
                              <?php echo  ($student->mode=='REG')?$paper->max_theory_marks:$paper->private_max_theory_marks;?></span>
                            </td>
                            <td align="center" ><span class="style4 tdFont">
                              <?php echo  ($student->mode=='REG')?$paper->min_theory_marks:$paper->private_min_theory_marks; ?></span>
                            </td>
                            
                            <td align="center" ><span class="style4 tdFont" >
                              <?php
                              if($student->mode=='REG'){
                              if ($paper->type=='theory') {
                                $status = ($paper->status == "C")?' C':'';
                                if(($paper->theory_marks <  $paper->min_theory_marks) && $check_grace_marks==false){
                                  echo $paper->theory_marks . ' F' ;
                                }elseif($paper->theory_marks<$paper->min_theory_marks){
                                  echo $paper->theory_marks; 
                                  echo ($check_grace_marks) ? ' G' : '';
                                }elseif($paper->theory_marks=='ABS'){
                                  echo 'ABS F';
                                }else{
                                  echo $paper->theory_marks.$status;
                                }
                              }else{
                                  if($paper->p_marks<$paper->min_theory_marks){
                                    echo $paper->p_marks.' F';
                                  }elseif($paper->p_marks=='ABS'){
                                    echo 'ABS F';
                                  }else{
                                    echo $paper->p_marks.$status;
                                  }
                              }
                            }else{
                                $status = ($paper->status == "C")?' C':'';
                              if ($paper->type=='theory') {
                                if(($paper->theory_marks <  $paper->private_min_theory_marks) && $check_grace_marks==false){
                                  echo $paper->theory_marks . ' F' ;
                                }elseif($paper->theory_marks<$paper->private_min_theory_marks){
                                  echo $paper->theory_marks; 
                                  echo ($check_grace_marks) ? ' G' : '';
                                }elseif($paper->theory_marks=='ABS'){
                                  echo 'ABS F';
                                }else{
                                  echo $paper->theory_marks.$status;
                                }
                              }else{
                                  if($paper->p_marks<$paper->private_min_theory_marks){
                                    echo $paper->p_marks.' F';
                                  }elseif($paper->p_marks=='ABS'){
                                    echo 'ABS F';
                                  }else{
                                    echo $paper->p_marks.$status;
                                  }
                              }
                            }
                              ?>
                            </span></td>
                            <td align="center" class="style2"><span class="style4 tdFont">
                              <?php 
                               if($student->mode=='REG'){
                                $status = ($paper->status == "C")?' C':'';
                                  if ($paper->type=='theory') {
                                    if($paper->theory_marks<$paper->min_theory_marks){
                                      echo  $paper->theory_marks . '' ;
                                      echo ($check_grace_marks) ? ' G' : ' F';
                                    }elseif($paper->theory_marks=="ABS"){
                                      echo 'ABS'. ' F' ;
                                    }else{
                                      echo $paper->theory_marks.$status;
                                    }
                                  }else{
                                    if($paper->p_marks<$paper->min_theory_marks && $check_grace_marks==false){
                                      echo  $paper->p_marks . ' F' ; 
                                    }elseif($paper->p_marks=="ABS"){
                                      echo 'ABS'. ' F';
                                    }else{
                                      echo $paper->p_marks.$status;
                                    }
                                  }
                               }else{
                                if ($paper->type=='theory') {
                                  if($paper->theory_marks<$paper->private_min_theory_marks){
                                    echo  $paper->theory_marks . '' ;
                                    echo ($check_grace_marks) ? ' G' : ' F';
                                  }elseif($paper->theory_marks=="ABS"){
                                    echo 'ABS'. ' F' ;
                                  }else{
                                    echo $paper->theory_marks.$status;
                                  }
                                }else{
                                  if($paper->p_marks<$paper->private_min_theory_marks && $check_grace_marks==false){
                                    echo  $paper->p_marks . ' F' ; 
                                  }elseif($paper->p_marks=="ABS"){
                                    echo 'ABS'. ' F';
                                  }else{
                                    echo $paper->p_marks.$status;
                                  }
                                }
                               }
                            ?></span>
                          </td>
                        </tr>
                      <?php
                      if($paper_name[0] == 'Moukhiki') {
                    $flag =0;}
                    $tflag =0;} 
                   
                    ?>
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
                      <!-- <tr>
                        <td colspan="8"></td>
                      </tr> -->
                      <tr>
                        <td width="22%" height="20"><strong><?php //echo $year; //$dt_row['year']; ?></strong></td>
                        <td width="13%" style="text-align: center"><strong><?php //echo $year_num;//$dt_row['sem']; ?></strong></td>
                        <td width="30%">&nbsp;</td>
                        <td width="9%"><!--<div align="center"><strong>Grand-Total</strong></div>--></td>
                        <td width="1%"><div align="center"></div></td>
                        <td width="8%"><div align="center"><strong></strong></div></td>
                        <td width="7%"><div align=""><strong>Result</strong></div></td>
                        <td width="10%">&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $result ; ?></strong>
                      </td>
                    </tr>
                    <?php if ($classData->last_class=="L" && !$isOneClass): ?>
                    </table>
                    <table border="0" cellpadding="0" height="112" width="100%">
                    <?php endif ?>  
                    <?php $i=1; ?>
                    <?php if ($classData->last_class=="L" && !$isOneClass): ?>
                    <tr>
                      <th height="20" width="22%" style="text-align:left" ><strong><?=$classData->mode ?></strong></th>
                      <?php

                        $whereClass = array( 'course_group_id'=> $classData->course_group_id,'class_id !=' => $classData->id,'student_id' =>$student->student_id,'exam_result!='=>"FAIL");
                        $this->db->order_by('old_exam_data.class_order,old_exam_data.class_id');
                       $oldClassResult = $this->Common_model->getRecordByWhere('old_exam_data',$whereClass);
                      $width = (count($oldClassResult)<3)?'20.3%':'12.18%';
                        foreach ($oldClassResult as $row) {
                        $i++;
                        ?>
                         <th  width='<?=$width?>' style="text-align: center;"><?=$this->Common_model->getClassNameByClassId($row->class_id); ?></th>
                        <?php } ?>
                        <th width='<?=$width?>' style="text-align: center"><?=$classData->class_name ?></th>
                        <th width='<?=$width?>' style="text-align: center">Grand Total</th>
                        
                        <?php $j=$i; ?>
                        <?php while ($j<=5): ?>
                          <td><?php $j++; ?></td>
                        <?php endwhile; ?>
                    </tr>
                    <?php endif ?>
                    <?php $j=$i; ?>
                    <tr>
                      <td height="20" ><strong>Obtained Marks</strong></td>
                      <?php if ($classData->last_class=="L" && !$isOneClass): ?>
                      <?php
                        $gtot_obtain_marks = 0;
                        $gtot_total_marks = 0;
                        foreach ($oldClassResult as $row) { 
                        $gtot_obtain_marks += $row->obtain_marks;
                        $gtot_total_marks +=$row->total_marks;
                        ?>
                         <th style="text-align: center"><?=$row->obtain_marks; ?></th>
                      <?php } 
                      $gtot_obtain_marks += $tot_std_marks;
                      $gtot_total_marks +=$tot_marks;
                      ?>
                      <?php endif ?>
                      <th style="text-align: center"><?=$tot_std_marks ; ?></th>
                      <th style="text-align: center"><?=$gtot_obtain_marks; ?></th>
                      <?php while ($j<=3): ?>
                        <td><?php $j++; ?></td>
                      <?php endwhile; ?>
                      <?php if ($classData->last_class=="L") { ?>
                        <td>
                        <?php
                         $percentage = (!$isOneClass)? round(($gtot_obtain_marks/$gtot_total_marks)*100,2) : round(($tot_std_marks/$tot_marks)*100,2);
                          if($percentage>=60){
                            $division = "First";
                          }elseif($percentage<60 && $percentage>=40){
                            $division  = "Second";
                          }else{
                            $division = "Third";
                          }
                          ?><strong>Division</strong><?php
                        ?>
                      </td>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?=$division?></strong>
                      </td>
                      <?php
                        }else{
                          ?>
                          <td></td>
                          <td></td>
                          <?php
                        }
                        ?>
                    </tr>
                    <?php $j=$i; ?>
                    <tr>
                      <td height="20"><strong>Maximum Marks</strong></td>
                      <?php if ($classData->last_class=="L" && !$isOneClass): ?>
                      <?php 
                      foreach ($oldClassResult as $row) {  ?>
                         <th style="text-align: center;width:10%"><?=$row->total_marks; ?></th>
                      <?php } ?>
                      <?php endif ?>
                      <td style="text-align: center"><b><?php echo $tot_marks ; ?></b></td>
                      <td style="text-align: center"><b><?=$gtot_total_marks; ?></b></td>
                      <?php while ($i<=3): ?>
                          <td><?php $i++; ?></td>
                        <?php endwhile; ?>
                        <?php if ($classData->last_class=="L"){ ?>
                        <th style="text-align:left;">Percentage</th>
                        <th style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo (!$isOneClass)? round(($gtot_obtain_marks/$gtot_total_marks)*100,2) :
                           round(($tot_std_marks/$tot_marks)*100,2)
                        ; ?>%</th>
                      <?php }else{ ?>
                        <td></td>
                        <td></td>
                      <?php } ?>
                    </tr>
                    <tr>
                      <td colspan="8">
                        <strong>Total Marks Obtained (in words)</strong> &nbsp;&nbsp;<strong><?php echo ($classData->last_class=="L" && !$isOneClass) ? $this->numbertowordconvertsconver->convert_number("$gtot_obtain_marks") : $this->numbertowordconvertsconver->convert_number("$tot_std_marks") ?></strong>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </fieldset>
              <!-- if starts -->
              <tr>
                <td align="left" colspan="2">
                  <table width="100%" style="margin-top:40px">
                    <tr>
                    </tr>
                  </table>    
                </td>
              </tr>
              <tr>
                <td width="17" align="center">
                  <div align="left">
                    <?php// echo "Date :".$marksheet_variables->result_date; ?>
                    <?php if($university_mode != 'PVT')  echo "Date :".$marksheet_variables->backlog_result_date;else  echo "Date :".$marksheet_variables->backlog_pvt_result_date;?>
                  </div></td>
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