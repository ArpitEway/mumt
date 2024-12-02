<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
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
      /*.margin-top-marksheet{
        margin-top: -20px;
      }*/
      @page{
        margin: 0;
      }
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
    $isOneClass = $this->Common_model->hasOneClass($course_group_id);
    // var_dump($isOneClass);
    foreach($students as $student)
    {
      // $papers = $this->Common_model->student_info_for_result($student->student_id,$student->old_class_id);
      // print_r($papers);
      ?>
      <fieldset id="printarea" class="breakhere" style="width:90%;border: 0px solid #22316C;"> 
        
        <table align="center" border="0" width="100%">
          <tbody>
            <tr>
              <td height="110" colspan="2" valign='bottom'>
                <center>
                <?php  $course_name = explode('(',$student->course_name);?>
                  <strong style="font-size: 18px;"><?php echo  ($isOneClass) ? $course_name[0] .' '."(One Year Course)" :$course_name[0] .' '.$this->Common_model->romanClassName($this->Common_model->getClassNameByClassId($student->class_id)); ?> <?= ' Backlog '.$marksheet_variables->exam_session ?></strong>
                </center>
              </td>
            </tr>
            <tr>
              <td align="center" height="120" colspan="2" >
                <table class="mytable" border="0" cellpadding="2" cellspacing="2" width="100%" style="margin-left:9px;">
                  <tbody>
                    <?php if($student->mode == 'REG'){?>
                    <tr>
                      <td class="Normaltext" colspan="2">
                        <div align="center"><font size="4">  &nbsp; </font></div>
                      </td>
                      <td class="Normaltext">
                        <div align="center"><font size="4">  Regular </font></div>
                      </td>
                    </tr>
                    <?php }?>
                    <tr>
                      <td width="35%" class="Normaltext" align="left"><div align="left">Roll No</div></td>
                      <td width="53%" class="resultText">
                        <div align="left">
                          <span id="lblSemesterGrading" style="color:Black;"><?php echo $student->roll_no; ?></span>
                          <!-- <div style="float:right"> &nbsp;&nbsp;&nbsp; Mode - Distance Education </div> -->
                        </div>
                      </td>
                      <td align="center" width="18%" rowspan="4">
                        
                       <?php  
                       if(file_exists('assets/student_image/'.$student->session.'/'.$student->photo)){
                        $src = 'assets/student_image/'.$student->session.'/'.$student->photo;
                       }else{
                        $src ='assets/student_image/na.jpg';
                       }
                      
                       ?>
                        <img border="1"  class="student_image" src="<?= base_url($src) ?>" width="90px" height="105px">
                      </td>
                    </tr>
                    <tr>
                      <td class="Normaltext" align="left">
                        <div align="left">Enrolment / Registration No.</div>
                      </td>
                      <td class="resultText">
                        <div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo  $student->enrollment_no; ?></span></div>
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
                    <?php if ($student->course_group_id==76): ?>
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
                    <?php endif ?>
                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td height="72" colspan="2">
                <fieldset style="border: 0px solid #22316C;">       
                  <div style="margin-top: 5px;">
                    <table id="" style="width:103%;" border="1" cellspacing="0" cellpadding="5" align="center">
                      <tbody>
                        <tr style="font-family:Arial, Helvetica, sans-serif; font-size:11px;" align="center" >
                          <td width="11%"  align="center" valign="center" style="font-size:11px;padding:2%;" scope="col"><span class="style7">Paper Code</span></td>
                          <td width="42%" style="font-size:11px" scope="col" valign="center" align="center"><span class="style7">Paper Name</div></td>    
                          <td  colspan="3" style="font-size:11px"><span class="style7"><u> Course Credit</u></span></td>        
                          <td  colspan="3" style="font-size:11px"><span class="style7"><u> Credit Earned</u></span></td>          
                          <td  colspan="2" scope="col" style="font-size:11px"><span class="style7"><u>Grade Point</u></span></td>
                          <td width="8%" colspan="2" scope="col" style="font-size:11px"><span class="style7"><u>Credit Points</u></span></td>
                          <td width="8%" colspan="2" scope="col" style="font-size:11px" ><span class="style7"><u>Grade</u></span></td>
                        </tr>
                       
                       
                        <?php 
    
                          $gradesheetData = $this->Gradesheet_backlog_model->view_result_grade($student->student_id,$student->course_group_id,$student->class_id,$student->mode,$student->id);
                          
                            ?>
                    </tbody></table>
                  </div>
                  <h4 style="text-align:center;margin:10px;">Result Year Wise</h4>
                  <table border='1' cellpadding="2"  width="103%">
                    <tbody>
                     <tr align="center"><th width='12.5%'>Year</th><th width='12.5%'>Total Credits</th><th width='12.5%'>Credits Earned</th><th width='12.5%'>Credit Points</th><th width='12.5%'>AGPA</th></tr>

                     <?php
                     $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$student->course_group_id));
                   
                    $count = 0;
                     foreach($classes as $cls){
                        $count++;
                        if($count == 1){ $sno = 'First';}elseif($count == 2){ $sno = 'Second';}elseif($count == 3){ $sno = 'Third';}elseif($count == 4){
                            $sno= 'Fourth';
                        }
                        if($cls->id <= $student->class_id){
                        $this->db->order_by('id', 'desc');
                        $this->db->limit(1);
                        $old = $this->Common_model->getRecordByWhere('old_exam_data', array('student_id'=>$student->student_id,'class_id'=>$cls->id,'course_group_id'=>$student->course_group_id,'university_mode'=>$student->mode));
                        $gradeData   = $this->Gradesheet_model->view_old_results($student->student_id,$student->course_group_id,$cls->id,$student->university_mode, $old[0]->id, $old[0]->exam_status);
                        ?>
                        <tr align="center"><th><?=$sno?></th><td><?= ($gradeData['tot_credit'] == 0)?'':$gradeData['tot_credit']?></td><td><?php if($gradeData['obt_credit'] == 0 && $gradeData['tot_credit'] !=0) { echo '0'; }elseif($gradeData['obt_credit'] == 0){ echo '';}else{ echo $gradeData['obt_credit'];}?></td><td><?php if ($gradeData['credit_point'] == 0 && $gradeData['tot_credit'] !=0){ echo '0';}elseif($gradeData['credit_point'] == 0){ echo ''; }else { echo $gradeData['credit_point']; }?></td><td>
                        <?php if(is_nan($gradeData['agpa'])){
                            echo '';
                        }else{
                           echo  ($gradeData['result']== 'FAIL' || $gradeData['result']== 'SUPP')?'0.00':number_format((float)$gradeData['agpa'], 2, '.', '');
                           
                        }
                        ?>
                        </td>
                     </tr>
                     <?php
                        }else{
                            ?>
                            <tr align="center"><th><?=$sno?></th><td></td><td></td><td></td><td></td>
                     </tr>
                            
                            <?php
                        }
                        
                        
                     ?>
                     
                     <?php
                     }
                    ?>
                     <!-- <tr align="center"><th>First</th><td><?= $gradesheetData['tot_credit']?></td><td><?= $gradesheetData['obt_credit']?></td><td><?= $gradesheetData['credit_point']?></td><td><?= ($gradesheetData['result']== 'FAIL' || $gradesheetData['result']== 'SUPP')?'0.00':number_format((float)$gradesheetData['agpa'], 2, '.', '')?></td></tr>
                     <tr align="center"><th>Second</th><td></td><td></td><td></td><td></td></tr>
                     <tr align="center"><th>Third</th><td></td><td></td><td></td><td></td></tr> -->
                   
                    </tbody>
                 </table>
              </fieldset>
              <!-- if starts -->
              <tr>
                <td align="left" colspan="2" style='border:none;'>
                  <table width="100%" style="margin-top:20px;">
                    <tr>
                    <td width="17" align="center">
                  <div align="left">
                    <?php echo "Date :".$marksheet_variables->backlog_result_date; ?></div></td>
                    </tr>
                  </table>    
                </td>
              </tr>
             
                  <tr class="" >
                    <td colspan="" style="padding:2px;border:none">
                    <?php  echo $generator->getBarcode($marksheet_variables->bar_code_no.$student->roll_no, $generator::TYPE_CODE_128,2,25);?>
                    </td>
                  </tr>
                 <tr><td><div align="left" class="margin-top-marksheet"> MS No. <?php echo $student->back_marksheet_no; ?> </div></td></tr>
              </td>
            </tr>
          </tbody>
        </table>
      </fieldset>
    <?php } ?>
  </center>
</body>
</html>
