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
      /* image-orientation: none; */
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
    $marksheet_variables = $this->Common_model->getRecordById('marksheet_variables','class_id',$exam_data->class_id);
    $classData = $this->Common_model->getRecordById('class_master','id',$exam_data->class_id);
    $isOneClass = $this->Common_model->hasOneClass($exam_data->course_group_id);
    // var_dump($isOneClass);
    // foreach($students as $student)
    // {
      // $papers = $this->Common_model->student_info_for_result($student->student_id,$student->old_class_id);
      // print_r($papers);
      ?>
      <fieldset id="printarea" class="breakhere" style="width:90%;border: 0px solid #22316C;"> 
        <div align="left" class="margin-top-marksheet"> MS No. <?php echo $exam_data->marksheet_no; ?> </div>
        <table align="center" border="0" width="100%">
          <tbody>
            <tr>
              <td height="100" colspan="2" valign='bottom'>
                <center>
                <?php  $course_name = explode('(',$exam_data->course_name);?>
                  <strong><?php echo  ($isOneClass) ? $course_name[0] .' '."(One Year Course)" :$course_name[0] .' '.$this->Common_model->romanClassName($this->Common_model->getClassNameByClassId($exam_data->class_id)); ?> <?php echo ' Examination '.$exam_data->exam_year;
                  ?></strong>
                </center>
              </td>
            </tr>
            <tr>
              <td align="center" height="120" colspan="2" >
                <table class="mytable" border="0" cellpadding="2" cellspacing="2" width="100%" style="margin-left:9px;">
                  <tbody>
                    <?php if($exam_data->university_mode == 'REG'){?>
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
                          <span id="lblSemesterGrading" style="color:Black;"><?php echo $exam_data->roll_no; ?></span>
                          <!-- <div style="float:right"> &nbsp;&nbsp;&nbsp; Mode - Distance Education </div> -->
                        </div>
                      </td>
                      <td align="center" width="18%" rowspan="4">
                        
                       <?php  
                       if(file_exists('assets/student_image/'.$exam_data->session.'/'.$exam_data->photo)){
                        $src = 'assets/student_image/'.$exam_data->session.'/'.$exam_data->photo;
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
                        <div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo  $exam_data->enrollment_no;; ?></span></div>
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
       
                          $gradesheetData = $this->Gradesheet_old_model->view_result_grade($exam_data->student_id,$exam_data->course_group_id,$exam_data->class_id,$exam_data->university_mode);
                          
                            ?>
                    </tbody></table>
                  </div>
                  <h4 style="text-align:center;margin:10px;">Result Year Wise</h4>
                  <table border='1' cellpadding="2"  width="103%">
                    <tbody>
                     <tr align="center"><th width='12.5%'>Year</th><th width='12.5%'>Total Credits</th><th width='12.5%'>Credits Earned</th><th width='12.5%'>Credit Points</th><th width='12.5%'>AGPA</th></tr>
                     <tr align="center"><th>First</th><td><?= $gradesheetData['tot_credit']?></td><td><?= $gradesheetData['obt_credit']?></td><td><?= $gradesheetData['credit_point']?></td><td><?= ($gradesheetData['result']== 'FAIL' || $gradesheetData['result']== 'SUPP')?'0.00':number_format((float)$gradesheetData['agpa'], 2, '.', '')?></td></tr>
                     <tr align="center"><th>Second</th><td></td><td></td><td></td><td></td></tr>
                     <tr align="center"><th>Third</th><td></td><td></td><td></td><td></td></tr>
                   
                    </tbody>
                 </table>
              </fieldset>
              <!-- if starts -->
              <tr>
                <td align="left" colspan="2" style='border:none;'>
                  <table width="100%" style="margin-top:50px;">
                    <tr>
                    <td width="17" align="center">
                  <div align="left">
                    <?php 
                    $date = ($exam_data->marksheet_date !='')?date('d/m/Y',strtotime($exam_data->marksheet_date)):'';
                    echo "Date :".$date; ?></div></td>
                    </tr>
                  </table>    
                </td>
              </tr>
             
                  <tr class="" >
                    <td colspan="" style="padding:2px;border:none">
                      <?php   $arr=explode(" ",$exam_data->exam_year);
                     $first=substr($arr[1], -2);
                     $second=date('m',strtotime($arr[0]));
                     $barcode_no=$first.$second.$exam_data->roll_no;
                     echo $generator->getBarcode($barcode_no, $generator::TYPE_CODE_128,2,25); ?>
                    </td>
                  </tr>
                  <tr><td> <?=$exam_data->remark_date?></td></tr>
              </td>
            </tr>
          </tbody>
        </table>
      </fieldset>
    <!-- <?php //} ?> -->
  </center>
</body>
</html>
