<title><?php echo (isset($title)) ? $title : ''; ?></title>
<style>
  @media print {
    body {
      -webkit-print-color-adjust: exact;
      -moz-print-color-adjust: exact;
      -ms-print-color-adjust: exact;
      print-color-adjust: exact;
    }
  }
  svg {
    width: 101px;
    height: 35px;
  }
  .table1 th {
    border:1px solid black;
    padding: 0rem;
  }
  .table1 td {
    border:1px solid black;
    padding: 0.25rem;
  }
  .table2 th{
    border:1px solid black;
    padding-left: 0.25rem;
    padding-right: 0.25rem;

  }
  .table2 td{
    border:1px solid black;
    padding-left: 0.25rem;
    padding-right: 0.25rem;

  }
  .line-height{
    line-height:5px;
  }
  .custom_width {
    width: 160px;
  }
  .width_total{
    width : 68px;
  }
   td, th, table {
    font-size: 11.8px;
    border: 1px solid #000;
  }
  table {
    border-spacing: 2px;
    width: 100%;
    margin-bottom: 6px;
  }
  .text-center {
    text-align: center;
}
table.last_table, .last_table td, .last_table th{
  border: none;
}
.align-middle{
  vertical-align: middle;
}

/*table marks css*/
  .roll_no {
    width: 7%;
  }
  .ms_no {
    width: 7%;
  }
  .photo {
    width: 6%;
  }
  .name {
    width: 10%;
  }
  .paper {
    width: 15%;
    min-width: 85px;
  }
  .paper_code {
    width: auto;
  }
  .total {
    width: 4%;
  }
  .obtained {
    width: 5%;
    min-width: 50px;
  }
  .result {
    width: 5%;
  }
  .remarks {
    width: 5%;
    min-width: 50px;
  }


   @media screen 
  {
    div#footer_wrapper 
  {
      display: none;
    }
  }

  @media print {
    .break {page-break-before: always !important;}
        @page {
          size: auto;
      }
    tfoot { visibility: hidden; }

    div#footer_wrapper {
      margin: 0px 1px 0px 7px;
      position: fixed;
      bottom: 0;
    }

    div#footer_content {
      font-weight: bold;
    }
  
</style>
<div id="footer_wrapper">
  <div id="footer_content">
   RW-Result Withheld, RWE-Want of Enrolment, RWPM-Want of Prev. Sem/Year Marks, RWPR-Practical Marks Not Received, RWAS-Assignment Marks Not Received, RWPJ-Project Marks Not Received, UFM-Unfair Means,GR-Grace Mark In One Theory Paper For Passing, VCG-Vice-Chancellor's One Grace Mark In Division
  </div>
</div>
<?php
  $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
  $marksheetData = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id'=>$class_id));
  $classData= $this->Common_model->getRecordByWhere('class_master',array('id' => $class_id));
  $isFinalClass = $this->Common_model->hasOneClass($course_group_id);
  $rowspanhead = ($classData[0]->project!='N' || $classData[0]->practical!='N') ? "4" : "3";
  $rowspandata = ($classData[0]->project!='N' || $classData[0]->practical!='N') ? "5" : "4";
  $page_break_count = -1;
  $br_code_id = 0;
  // $roll_no = array(); 
  //$page_no = 0;
  $page_no=$pagenumber;
  $previous_center=$current_center="";
  foreach($students as $student)
  {
    $current_center=$student->center_id;
    $page_break_count++;
    $marks = $this->Common_model->student_info_for_result($student->student_id,$student->old_class_id);
    $BarCodecolspan = 9 + count($marks); 
    $total_theory_marks_obt = 0;
    $total_int_marks_obt = 0;
    $total_marks_obt = 0;
    $total_paper_marks = 0;
    $check_grace_marks = false;
    $require_tot_marks = 0;
    $tot_std_marks = 0;
    $tot_marks = 0;
    $rw_count = 0;
    $theory_abs_count = 0;
    $atkt_paper_codes_array = array(); 
    $int_abs_count = 0;
    $int_fail_count = 0;
    $p_abs_count = 0;
    $p_fail_count = 0;
    $fail_count = 0;
    $fail_tot_marks = 0;
    $final_result = '';
    foreach($marks as $new_exam_form)
    {
      if($new_exam_form->type=='theory'){

        $total_theory_marks_obt +=(int) $new_exam_form->theory_marks;
        $total_int_marks_obt += (int) $new_exam_form->int_marks;
        $total_theory_asm_marks = (int) $new_exam_form->theory_marks+ (int) $new_exam_form->int_marks;
        $total_marks_obt  += (int) $new_exam_form->theory_marks+ (int) $new_exam_form->int_marks;
        $total_paper_marks += (int) $new_exam_form->max_theory_marks + (int) $new_exam_form->max_internal_marks;
        $tot_std_marks += (int) $new_exam_form->theory_marks;
        $tot_marks += (int) $new_exam_form->max_theory_marks;

        if($new_exam_form->theory_marks=='ABS'){
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
          $theory_abs_count++;
        }

        if($new_exam_form->theory_marks==''){
          $rw_count++;
        }

        if($new_exam_form->int_marks=='N' && $course_group_id !=36 && $course_group_id !=37){
          $rw_count++;
        }

        if($new_exam_form->theory_marks<$new_exam_form->min_theory_marks  && $new_exam_form->theory_marks!=''){
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
          $fail_count++;
          $fail_tot_marks += $new_exam_form->theory_marks;
          $require_tot_marks += $new_exam_form->min_theory_marks;
        }

        if($new_exam_form->int_marks<$new_exam_form->min_internal_marks){
          $int_fail_count++;
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
        }

        if($new_exam_form->int_marks=="ABS"){
          $int_abs_count++;
          $int_fail_count++;
        }
      }

      if($new_exam_form->type!='theory'){
        $total_paper_marks += (int) $new_exam_form->max_theory_marks;
        $total_marks_obt += (int) $new_exam_form->p_marks;
        if($new_exam_form->p_marks=='' || $new_exam_form->p_marks=='N'){
          $rw_count++;
        }
        if($new_exam_form->p_marks=='ABS'){
          $p_abs_count++;
        }
        if($new_exam_form->p_marks<$new_exam_form->min_theory_marks){
          $p_fail_count++;
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
        }
      }
    }

    if ($fail_count==0 && $rw_count==0 && $p_fail_count==0 && $int_fail_count==0 && $theory_abs_count==0) {
       $final_result = "PASS";
    }else{
      $require_grace_marks = $require_tot_marks-$fail_tot_marks;
      // tot 3 grace marks in 1 subjects
      if ($fail_count<2 && $require_grace_marks<4 && $int_fail_count==0 && $p_fail_count==0 && $rw_count==0 && $theory_abs_count==0 && $p_abs_count==0 &&  $int_abs_count==0) {
        $check_grace_marks = true;
        $final_result = "PASS BY GRACE";
      }elseif($rw_count>0){
        $final_result = "RW";
      }else{
        $final_result = "FAIL";
      }
    }

    $percentage = round(($total_marks_obt/$total_paper_marks)*100,2);    
    if($percentage>=60){
      $division = "First";
    }elseif($percentage<60 && $percentage>=40){
      $division  = "Second";
    }else{
      $division = "Third";
    }

    if($page_break_count%4==0 || $page_break_count==0 || $previous_center!=$current_center){
      $page_no++;$page_break_count=0;
      ?>
      <h3 align="center" class="h4 break"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya, Madhya Pradesh</b></h3>
      <p align="center" class="line-height">Tabulation Register for <strong><?php echo $student->course_name; echo '&nbsp'. $marksheetData[0]->class_name; ?></strong> <?php echo $marksheetData[0]->exam_session;?>
      </p>
      <div>
        <div style="float: left;">DATE: <?php echo $marksheetData[0]->result_date;?></div>
        <div style="float: right;">Page : <?php  echo $page_no; ?></div>
      </div>
      <table class="table table1 mb-0">
        <tbody>
          <tr>
            <th class="align-middle text-center roll_no" rowspan="<?php echo $rowspanhead ?>">Roll.No. <br> Reg.No.</th>
            <th class="align-middle text-center ms_no" rowspan="<?php echo $rowspanhead ?>">M.S. <br> No.</th>
            <th class="text-center photo" rowspan="<?php echo $rowspanhead ?>">Photo</th>
            <td class="align-middle text-center name" rowspan="<?php echo $rowspanhead ?>">Name of the <br> Student and <br> F/H Name</td>
            <td class="paper">Paper-></td>
            <?php
            $p_count = 1;
              foreach($marks as $paper_master){
            ?>
              <td class="align-middle text-center paper_code"><?php echo ($paper_master->ce!='compulsory') ? 'OPTIONAL '.$p_count++ : $paper_master->paper_code;  ?></td>
            <?php } ?>
            <td class="align-middle text-center total">Total</td>
            <td class="align-middle text-center obtained" rowspan="<?php echo $rowspanhead ?>" >Marks <br> Obtained</td>
            <td class="align-middle text-center result" rowspan="<?php echo $rowspanhead ?>">Result</td>
            <td class="align-middle text-center remarks" rowspan="<?php  echo $rowspanhead ?>">Remarks</td>
          </tr>
          <tr>
            <td class="align-middle text-right paper">Theory Marks Max/Min -></td>
            <?php foreach($marks as $paper_master){ ?>
              <td  class="align-middle text-center paper_code"><?php
              if($paper_master->paper_type=='theory'){ 
                echo  $paper_master->max_theory_marks .'/'.$paper_master->min_theory_marks;
              }
            ?>
            </td>
            <?php } ?>
          <td class=""></td>
        </tr>
        <?php if($course_group_id !=36 && $course_group_id !=37 ){ ?>
        <tr>
          <td class="align-middle text-right paper">Internal Marks Max/Min -></td>
          <?php  foreach($marks as $paper_master){     ?>
            <td  class="align-middle text-center internal_mark">
              <?php if($paper_master->paper_type=="theory"){ echo  $paper_master->max_internal_marks .'/'. $paper_master->min_internal_marks;};  ?></td>
          <?php }  ?>
          <td class="align-middle text-center"></td>
        </tr>
        <?php 
        }
        if($classData[0]->project!='N' || $classData[0]->practical!='N'){
        ?>
        <tr>
          <td class="align-middle text-right paper"> <?=($classData[0]->project=='Y') ? 'Project' : 'Practical' ?> Marks Max/Min-></td>
          <?php foreach($marks as $paper_master){   ?>
          <td  class="align-middle text-center practical_marks">
            <?php if($paper_master->paper_type!="theory"){echo  $paper_master->max_theory_marks .'/'.$paper_master->min_theory_marks;};  ?>
          </td>
          <?php } ?>
          <td class="align-middle text-center"></td>
        </tr>
        <?php }  ?>
      </tbody>
    </table>
    <?php  $ccode=substr($student->center_code,0,2);
    if($ccode=="IC"){
      $center_code = substr($student->center_code, -4);
    }else{
      $center_code = $student->center_code;
    }
      echo '<span style="font-size: 11.8px;">'.$center_code. '</span>';
    }
    ?>
    <table class="table table1">
      <tbody>
        <tr>
          <th  class="align-middle text-center roll_no" rowspan="<?php echo $rowspandata ?>"><?php  echo $student->roll_number  ?> <br> <?php echo $student->enrollment_no  ?></th>
          <th class="align-middle text-center ms_no" rowspan="<?php echo $rowspandata ?>">
            <?php  echo $student->marksheet_no  ?>
          </th>
          <th  class="align-middle text-center photo" rowspan="<?php echo $rowspandata ?>"><img alt="N/A" src="<?= base_url('assets/student_image/'.$student->session.'/'.$student->photo) ?>" width="65px" height="90px"></th>
          <td  class="align-middle text-center name"  rowspan="<?php  echo $rowspandata ?>"><?php  echo $student->name ?>/ <br><?php  echo $student->f_h_name ?></td>
          <td  class="align-middle text-right paper">Paper-></td>
          <?php  foreach($marks as $paper_master){  ?>
            <td  class="align-middle text-center paper_code"><?php echo  $paper_master->paper_code;  ?></td>
          <?php   }  ?>
          <td  class="align-middle text-center total">Total</td>
          <td  class="align-middle text-center obtained" rowspan="<?php echo $rowspandata ?>"><?php 
                  echo $total_marks_obt .'/'. $total_paper_marks;
          ?></td>
          <td  class="align-middle text-center result" rowspan="<?php echo $rowspandata ?>"><?php echo $final_result; ?></td>
          <td  class="align-middle text-cente remarks"  rowspan="<?php echo $rowspandata ?>"><?php 
          if($check_grace_marks){
            echo "-";
          }else{
            if($int_abs_count>0 &&  $theory_abs_count>0 && $p_abs_count>0){
              echo 'ABS In ALL';
            }elseif($int_abs_count>0 ||  $theory_abs_count>0 || $p_abs_count>0){
              echo 'ABS In';
              if($theory_abs_count>0){
                echo ' Theory';
              }elseif($int_abs_count>0){
                echo ' Internal'; 
              }elseif($p_abs_count>0){
                echo ' prectical';
              }
            }else{
              if(sizeof($atkt_paper_codes_array)>0){
                echo "ATKT in";
              }
              $atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
              foreach($atkt_paper_codes_array as $paper_code){
                echo  "<br>". $paper_code;
              }
            }
          }
          ?>
        </td>
      </tr>
      <tr>
        <td class="align-middle text-right paper" >Theory Marks-></td>
        <?php foreach($marks as $new_exam_form){ ?>
          <td  class="align-middle text-center">
          <?php if($new_exam_form->paper_type=="theory"){
              if($new_exam_form->theory_marks==''){
                echo '-';
              }elseif($new_exam_form->theory_marks>=$new_exam_form->min_theory_marks && $new_exam_form->theory_marks!="ABS"){
                echo $new_exam_form->theory_marks;
              }else{
                echo $new_exam_form->theory_marks;
                echo ($check_grace_marks) ? ' G' : ' F';
              }
            }
          ?>
          </td>
        <?php }    ?>
        <td class="align-middle text-center result"><?php echo  $total_theory_marks_obt;  ?></td>
      </tr>
      <?php if($course_group_id !=36 && $course_group_id !=37 ){ ?>
      <tr>
        <td class="align-middle text-right paper">Internal Marks-></td>
          <?php foreach($marks as $paper_master){ ?>
        <td  class="align-middle text-center">
          <?php
          if($paper_master->paper_type=="theory")
          {
          if($paper_master->int_marks=='N'){
            echo '-';
          }elseif($paper_master->int_marks>=$paper_master->min_internal_marks && $paper_master->int_marks!="ABS" ){
            echo $paper_master->int_marks;
          }elseif($paper_master->int_marks=="ABS"){
            echo "ABS F";
          }
          else{
            echo $paper_master->int_marks .' F';
          }
        }
        ?>
        </td>
        <?php } ?>
        <td class="align-middle text-center result"><?php echo $total_int_marks_obt;  ?></td>
    </tr> <?php } ?>
  <?php if( $classData[0]->project!='N' || $classData[0]->practical!='N'){ ?>
  <tr>
    <td class="align-middle text-right paper">Practical Marks.</td>
    <?php
    $total_p_marks = 0;
    foreach($marks as $new_exam_form)
    {
      ?>
      <td  class="align-middle text-center"><?php 
      if($new_exam_form->p_marks=="N")
        {echo " ";}
      else{
        if($new_exam_form->p_marks < $new_exam_form->min_theory_marks && $new_exam_form->p_marks!=''){
          echo  $new_exam_form->p_marks .' F';
        }elseif($new_exam_form->p_marks ==''){
          echo "RWPR";
        }
        else{
          echo  $new_exam_form->p_marks;
          $total_p_marks += $new_exam_form->p_marks;
        }
      }
    ?></td>
    <?php } ?>
  <td class="align-middle text-center result"><?=$total_p_marks; ?></td>
</tr>
<?php } ?>
  <tr>
    <td class="align-middle text-right">Total Marks Obt.</td>
    <?php foreach($marks as $paper_master){ ?>
      <td  class="align-middle text-center"><?php
      if($paper_master->paper_type=="theory"){
       if($check_grace_marks==true){
        echo $paper_master->theory_marks+ $paper_master->int_marks;
      } elseif(($paper_master->theory_marks<$paper_master->min_theory_marks) || ($paper_master->int_marks<$paper_master->min_internal_marks) || $theory_abs_count!=0 || $int_abs_count!=0){
        echo (int) $paper_master->theory_marks+ (int) $paper_master->int_marks." F";
      }else{
        echo (int) $paper_master->theory_marks+ (int) $paper_master->int_marks;
      }
    }else{ 
      if($paper_master->p_marks=='ABS'){
        echo '0 F';
      }elseif($paper_master->p_marks<$paper_master->min_theory_marks){
        echo $paper_master->p_marks.' F';
      }else{
        echo $paper_master->p_marks;
      }
    } ?>
    </td>
    <?php } ?>
    <td class="align-middle text-center"><?php echo $total_marks_obt; ?></td>
  </tr>
<?php if($isFinalClass){ ?>
  <?php if($final_result !="PASS" && !$check_grace_marks){  ?>
    <tr>
      <td class="text-center align-middle" colspan="<?=$BarCodecolspan ?>">    -   </td>
    </tr>
    <?php }else{  ?>
      <tr class="">
        <td  class="align-middle text-center " colspan="4"><?php  echo 'Tot:'. $total_marks_obt .'/'. $total_paper_marks; ?></td>
        <td class="align-middle text-center "  colspan="3"><?php  echo  'Per : '.$percentage .'%'; ?></td>
        <td class="align-middle text-center "  colspan="3"><?php  echo $final_result;?></td>
        <td class="align-middle text-center " colspan="5"> <?php  echo $division;  ?></td>
      </tr>
      <?php
    }
  }
  ?>
  <tr class="">
    <td  class="align-middle text-left " colspan="<?=$BarCodecolspan ?>">
          <?php  echo $generator->getBarcode($marksheetData[0]->bar_code_no.$student->roll_number, $generator::TYPE_CODE_128,2,25); ?>
    </td>
  </tr>
</tbody>
</table>
<?php

  $previous_center=$current_center;//=$student->center_id;

}
?>

<hr>
<table width="100%" class="last_table" border="0">
<tr>
<td colspan="3">&nbsp;</td>
<td colspan="3" align="right">Order for Declaration & Publication of Result</td>
</tr>
<tr style="height:100px; vertical-align: bottom;">
<td align="center" width="20%">Checked By</td>
<td align="center" width="20%">Sign of 1st Tabulator</td>
<td align="center" width="20%">Sign of 2nd Tabulator</td>
<td align="center" width="20%">Asst. Registrar </td>
<td align="center" width="20%">Registrar/Controller Of Examination</td>
</tr><tr><td colspan="5">&nbsp;</td></tr><tr><td colspan="5">&nbsp;</td></tr>
</table>
</td>
</tr>
</table>
</div>
