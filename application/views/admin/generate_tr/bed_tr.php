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
  @page {
          size: auto;
      }
  svg {
    width: 101px;
    height: 35px;
  }
  .table1 th {
    border:1px solid black;
    padding: 0.25rem;
  }
  .table1 td {
    border:1px solid black;
    padding: 0.25rem;
  }
  table {
    border-spacing: 30px;
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
    width:167px;
  }
  .width_total{
    width : 68px;
  }
  div.b128{
    border-left: 1px red solid;
    height: 30px;
  } 
   @media print{
        .break {page-break-before: always !important;}
        @page {
          size: auto;
      }
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
    tfoot { visibility: hidden; }

    div#footer_wrapper {
      margin: 0px 1px 0px 7px;
      position: fixed;
      bottom: 0;
    }

    div#footer_content {
      font-weight: bold;
    }
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
  if($classData[0]->last_class == 'L'){
    $final_class = true;
  }
  $rowspanhead = ($classData[0]->project!='N' || $classData[0]->practical!='N') ? "5" : "3";
  $rowspandata = ($classData[0]->project!='N' || $classData[0]->practical!='N') ? "6" : "4";
  $page_break_count = -1;
  $br_code_id = 0;
  // $roll_no = array(); 
  $page_no = 0;
  $previous_center=$current_center="";
  foreach($students as $student)
  {
    $current_center=$student->center_id;
    $page_break_count++;
    $marks = $this->Common_model->student_info_for_BEd_result($student->student_id,$student->class_id);
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
    $theory_paper_count = 0;
    $p_paper_count = 0;
    $rwpr_count = 0;
    $rwas_count =0;
    foreach($marks as $new_exam_form)
    {
      if($new_exam_form->type=='theory'){
        $theory_paper_count++;
        $total_theory_marks_obt += $new_exam_form->theory_marks;
        $total_int_marks_obt += $new_exam_form->int_marks;
        $total_theory_asm_marks = $new_exam_form->theory_marks+ $new_exam_form->int_marks;
        $total_marks_obt  += $new_exam_form->theory_marks+ $new_exam_form->int_marks;
        $total_paper_marks += $new_exam_form->max_theory_marks + $new_exam_form->max_internal_marks;
        $tot_std_marks += $new_exam_form->theory_marks;
        $tot_marks += $new_exam_form->max_theory_marks;

        if($new_exam_form->theory_marks=='ABS'){
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
          $theory_abs_count++;
        }

        if($new_exam_form->theory_marks=='' ){
          $rw_count++;
        }

        if($new_exam_form->theory_marks<$new_exam_form->min_theory_marks  && $new_exam_form->theory_marks!=''){
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
          $fail_count++;
          $fail_tot_marks += $new_exam_form->theory_marks;
          $require_tot_marks += $new_exam_form->min_theory_marks;
        }

        if($new_exam_form->int_marks=='N' && $new_exam_form->max_internal_marks!=0){
          $rwas_count++;
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
        $p_paper_count++;
        $total_marks_obt += $new_exam_form->p_marks+$new_exam_form->int_marks;
        $total_paper_marks +=$new_exam_form->max_theory_marks+$new_exam_form->max_internal_marks;
        if($new_exam_form->p_marks=='' || $new_exam_form->p_marks=='N'){
          $rwpr_count++;
        }
        if($new_exam_form->p_marks=='ABS'){
          $p_abs_count++;
        }
        if($new_exam_form->p_marks<$new_exam_form->min_theory_marks){
          $p_fail_count++;
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
        }
        
        if($new_exam_form->int_marks=='N' && $new_exam_form->max_internal_marks!=0){
            $rwas_count++;
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
    }

    if ($fail_count==0 && $rw_count==0 && $p_fail_count==0 && $int_fail_count==0 && $theory_abs_count==0 && $p_abs_count==0 &&  $int_abs_count==0 && $rwpr_count==0 && $rwas_count==0) {
       $final_result = "PASS";
    }else{
      $require_grace_marks = $require_tot_marks-$fail_tot_marks;
      // tot 3 grace marks in 1 subjects
      if ($fail_count<2 && $require_grace_marks<4 && $int_fail_count==0 && $p_fail_count==0 && $rw_count==0 && $theory_abs_count==0 && $p_abs_count==0 &&  $int_abs_count==0 && $rwpr_count==0 && $rwas_count==0) {
        $check_grace_marks = true;
        $final_result = "PASS BY GRACE";
      }elseif($rw_count>0){
        $final_result = "RW";
      }elseif($rwas_count>0 && $rwpr_count>0){
        $final_result = "RWAS RWPR";
      }elseif($rwpr_count>0){
        $final_result = "RWPR";
      }elseif($rwas_count>0){
        $final_result = "RWAS";
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
    
    if($page_break_count%2==0 || $page_break_count==0 || $previous_center!=$current_center){
      $page_no++;$page_break_count=0;
      ?>
      <p align="center" class="h4 break"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya, Madhya Pradesh</b></p>
      <p align="center" class="line-height">Tabulation Register for <strong><?php echo $student->course_name; echo '&nbsp'. $marksheetData[0]->class_name; ?></strong> <?php echo $marksheetData[0]->exam_session;?>
      </p>
      <!--<p align="center" class="line-height">Directorate of Distance Education</p>-->
      <div>
        <div style="float: left;">DATE: <?php if($student->mode != 'PVT') echo $marksheetData[0]->result_date;else echo $marksheetData[0]->pvt_result_date;?></div>
        <div style="float: right;">Page : <?php  echo $page_no; ?></div>
      </div>
      <table class="table table1 mb-0">
        <tbody>
          <tr>
            <th class="align-middle text-center roll_no" rowspan="<?php echo $rowspanhead ?>">Roll.No. <br> Reg.No.</th>
            <th class="align-middle text-center ms_no" rowspan="<?php echo $rowspanhead ?>">M.S. <br> No.</th>
            <th class="align-middle text-center photo" rowspan="<?php echo $rowspanhead ?>">Photo</th>
            <td class="align-middle text-center name" rowspan="<?php echo $rowspanhead ?>">Name of the <br> Student and <br> F/H Name</td>
            <td class="align-middle text-right paper">Paper-></td>
            <?php
              foreach($marks as $paper_master){
            ?>
              <td class="align-middle text-center paper_code"><?php echo  $paper_master->paper_code;  ?></td>
            <?php } ?>
            <td class="align-middle text-center total">Total</td>
            <td class="align-middle text-center obtained" rowspan="<?php echo $rowspanhead ?>" >Marks <br> Obtained</td>
            <td  class="align-middle text-center result" rowspan="<?php echo $rowspanhead ?>">Result</td>
            <td class="align-middle text-center remarks" rowspan="<?php  echo $rowspanhead ?>">Remarks</td>
          </tr>
          <tr>
            <td class="align-middle text-right paper" >Theory Marks Max/Min -></td>
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
        <tr>
          <td class="align-middle text-right">Internal Marks Max/Min -></td>
          <?php  foreach($marks as $paper_master){     ?>
            <td  class="align-middle text-center ">
              <?php if($paper_master->paper_type=="theory"){ if($paper_master->max_internal_marks !=0){echo  $paper_master->max_internal_marks .'/'. $paper_master->min_internal_marks;}else{ echo ''; }};  ?></td>
          <?php }  ?>
          <td class="align-middle text-center"></td>
        </tr>
        <?php 
        if($classData[0]->project!='N' || $classData[0]->practical!='N'){
        ?>
        <tr>
          <td class="align-middle text-right"><?=($classData[0]->project=='Y') ? 'Project' : 'Practical' ?> External Marks Max/Min-></td>
          <?php foreach($marks as $paper_master){   ?>
          <td  class="align-middle text-center">
            <?php if($paper_master->paper_type!="theory"){echo  $paper_master->max_theory_marks .'/'.$paper_master->min_theory_marks;};  ?>
          </td>
          <?php } ?>
          <td class="align-middle text-center"></td>
        </tr>
        <tr>
          <td class="align-middle text-right"><?=($classData[0]->project=='Y') ? 'Project' : 'Practical' ?> Internal Marks Max/Min-></td>
          <?php foreach($marks as $paper_master){   ?>
          <td  class="align-middle text-center">
            <?php if($paper_master->paper_type!="theory"){ if($paper_master->max_internal_marks !=0){echo  $paper_master->max_internal_marks .'/'.$paper_master->min_internal_marks;}else{ echo '';};}  ?>
          </td>
          <?php } ?>
          <td class="align-middle text-center"></td>
        </tr>
        <?php }  ?>
      </tbody>
    </table>
    <?php 
      if($previous_center!=$current_center){
        $center_code = substr($student->center_code, -4);
        echo '<span>'.$center_code. '</span>';
      }
   
    }
    ?>
    <table class="table table1">
      <tbody>
        <tr>
          <th  class="align-middle text-center roll_no" rowspan="<?php echo $rowspandata ?>"><?php  echo $student->roll_no ?> <br> <?php echo $student->enrollment_no  ?></th>
          <th class="align-middle text-center ms_no" rowspan="<?php echo $rowspandata ?>">
            <?php echo $student->marksheet_no ;?> 
          </th>
          <th  class="align-middle text-center photo" rowspan="<?php echo $rowspandata ?>">
            <img alt="N/A" src="<?= base_url('assets/student_image/'.$student->session.'/'.$student->photo) ?>" width='65px' height="90px"></th>
          <td  class="align-middle text-center name"  rowspan="<?php  echo $rowspandata ?>"><?php  echo $student->name ?>/ <br><?php  echo $student->f_h_name ?></td>
          <td  class="align-middle text-right paper">Paper-></td>
          <?php  foreach($marks as $paper_master){  ?>
            <td  class="align-middle text-center paper_code"><?php echo  $paper_master->paper_code;  ?></td>
          <?php   }  ?>
          <td  class="align-middle text-center total">Total</td>
          <td  class="align-middle text-center obtained" rowspan="<?php echo $rowspandata ?>"><?php echo $total_marks_obt .'/'. $total_paper_marks;
            ?></td>
          <td  class="align-middle text-center result" rowspan="<?php echo $rowspandata ?>"><?php echo $final_result; ?></td>
          <td  class="align-middle text-center remarks"  rowspan="<?php echo $rowspandata ?>"><?php 
         
              if($student->course_group_id == 76){
                if($check_grace_marks){
                  echo "-";
                    }elseif($final_result == "RW"){
                      echo '';
                    }
                    elseif( $theory_abs_count==$theory_paper_count && $p_abs_count==$p_paper_count){
                             echo 'Absent In All';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
                   }
              elseif(sizeof($atkt_paper_codes_array)==1){
                echo "ATKT in";
                $atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
                foreach($atkt_paper_codes_array as $paper_code){
                  echo  "<br>". $paper_code;
                }
              }else{
                echo '';
              }
              }else{
                if($check_grace_marks){
                  echo "-";
                    }elseif($final_result == "RW"){
                      echo '';
                    }
                    elseif( $theory_abs_count==$theory_paper_count && $p_abs_count==$p_paper_count){
                      echo 'Absent In All';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
            }elseif( $theory_abs_count==$theory_paper_count){
              echo 'Absent In Theory';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
            }
            elseif( $p_abs_count==$p_paper_count){
              echo 'Absent In Practical';//$int_abs_count==($theory_paper_count+$p_paper_count )&& 
    }
              elseif(sizeof($atkt_paper_codes_array) > 0){
                echo "ATKT in";
                $atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
                foreach($atkt_paper_codes_array as $paper_code){
                  echo  "<br>". $paper_code;
                }
              }else{
                echo '';
              }

              }
             
              
            //   elseif($int_abs_count==$theory_paper_count &&  $theory_abs_count==$theory_paper_count && $p_abs_count==$p_paper_count){
            //       echo 'ABS In ALL';
            //   }elseif($theory_paper_count==$theory_abs_count){
            //         echo 'ABS In Theory';
            //   }elseif($int_abs_count==$theory_paper_count){
            //         echo 'ABS In Internal'; 
            //   }elseif($p_abs_count==$p_paper_count){
            //         echo 'ABS In Practical';
            //   }else{
            //     if(sizeof($atkt_paper_codes_array)>0){
            //       echo "ATKT in";
            //     }
            //     $atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
            //     foreach($atkt_paper_codes_array as $paper_code){
            //       echo  "<br>". $paper_code;
            //     }
            // }
          ?>
        </td>
      </tr>
      <tr>
        <td class="align-middle text-right paper">Theory Marks-></td>
        <?php foreach($marks as $new_exam_form){ ?>
          <td  class="align-middle text-center paper_code">
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
        <td class="align-middle text-center total"><?php echo  $total_theory_marks_obt; ?></td>
      </tr>
      <tr>
        <td class="align-middle text-right paper">Internal Marks-></td>
      <?php foreach($marks as $paper_master){ ?>
    <td  class="align-middle text-center paper_code">
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
    <td class="align-middle text-center total"><?php echo $total_int_marks_obt;  ?></td>
  </tr>
  <?php if( $classData[0]->project!='N' || $classData[0]->practical!='N'){ ?>
  <tr>
    <td class="align-middle text-right paper">Practical External Marks-></td>
    <?php
    $total_p_marks = 0;
    foreach($marks as $new_exam_form)
    {
      ?>
      <td  class="align-middle text-center paper_code"><?php 
      if ($new_exam_form->paper_type!='theory') {
        if($new_exam_form->p_marks=="N"){
          echo " - ";
        }else{
          if($new_exam_form->p_marks < $new_exam_form->min_theory_marks || $new_exam_form->p_marks=='ABS'){
            echo  $new_exam_form->p_marks .' F';
          }else{
            echo  $new_exam_form->p_marks;
            $total_p_marks += $new_exam_form->p_marks;
          }
        }
      }
    ?></td>
    <?php } ?>
  <td class="align-middle text-center total"><?=$total_p_marks; ?></td>
</tr>
  <tr>
    <td class="align-middle text-right paper">Practical Internal Marks-></td>
    <?php
    $total_p_marks = 0;
    foreach($marks as $new_exam_form)
    {
      if($new_exam_form->paper_type=='theory') {
        ?>
        <td  class="align-middle text-center">
        </td><?php
        continue;
      }
      ?>
      <td  class="align-middle text-center paper_code"><?php 
      if($new_exam_form->int_marks=="N"){
        echo " - ";
      }else{
        if($new_exam_form->int_marks < $new_exam_form->min_internal_marks || $new_exam_form->int_marks=='ABS'){
          echo  $new_exam_form->int_marks .' F';
        }elseif($new_exam_form->int_marks ==''){
          echo "RWPR";
        }else{
          echo  $new_exam_form->int_marks;
          $total_p_marks += $new_exam_form->int_marks;
        }
      }
    ?></td>
    <?php } ?>
  <td class="align-middle text-center total"><?=$total_p_marks; ?></td>
</tr>
<?php } ?>
  <tr>
    <td class="align-middle text-right paper">Total Marks Obt.</td>
    <?php foreach($marks as $paper_master){ ?>
      <td  class="align-middle text-center paper_code"><?php
      if($paper_master->paper_type=="theory"){
       if($check_grace_marks==true){
        echo $paper_master->theory_marks+ $paper_master->int_marks;
      }elseif(($paper_master->theory_marks<$paper_master->min_theory_marks) || ($paper_master->int_marks<$paper_master->min_internal_marks) || $paper_master->theory_marks=='ABS'|| $paper_master->int_marks=='ABS'){
        echo ($paper_master->theory_marks=='ABS' && $paper_master->int_marks=='ABS') ? 'ABS F' : $paper_master->theory_marks+ $paper_master->int_marks." F";

      }else{
        echo $paper_master->theory_marks+ $paper_master->int_marks;
      }
    }else{
      if($paper_master->p_marks=='ABS'){
        echo ($paper_master->int_marks=='ABS') ? 'ABS F' : $paper_master->int_marks.' F';
      }elseif($paper_master->p_marks<$paper_master->min_theory_marks || $paper_master->int_marks<$paper_master->min_internal_marks){
        echo $paper_master->p_marks+$paper_master->int_marks.' F';
      }else{
        echo $paper_master->p_marks+$paper_master->int_marks;
      }
    } ?>
    </td>
    <?php } ?>
    <td class="align-middle text-center total"><?php  echo $total_marks_obt; ?></td>
  </tr>
  <?php  
  if($final_class && $isFinalClass == false){
    $final_rw = 0;
    $final_fail =0;
    
      $final_remark = "-"; 
      $classes = $this->Common_model->getRecordByWhere("class_master",array('course_group_id'=>$course_group_id,'mode'=>$classData[0]->mode,'id!='=>$class_id
					));
          // $this->Common_model->last_query();
          $colspan=2;
          $degreecolspan=3;
          $class_count=count($classes);
          if( $class_count>1){
            $colspan=1;
            $degreecolspan=2;
          }
          $grandTotal_ob=0;
          $grandTotal_mar=0;
       ?>   <tr>
  <td class="align-middle text-center "  colspan="<?=$colspan?>"><strong>
  <?= 'Session'.'<br>'.'Sem/Year'.'<br>'.'Roll no'.'<br>'.'Marks'?></strong> <?php
   foreach($classes as $cls){
  $this->db->order_by('id','desc');
  $this->db->limit(1);
  $old_result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id'=>$cls->id));
  ?> 
 
</td> <?php

 foreach($old_result as $old){
  if($old->exam_result == "FAIL"){
 $final_fail++;
 $old->obtain_marks ='-';
 $old->total_marks = '-';
 
  }
  $grandTotal_ob=$grandTotal_ob+ $old->obtain_marks;
  $grandTotal_mar=$grandTotal_mar+ $old->total_marks;
  
  
  ?> 
  
  
 
<td class="align-middle text-center "  colspan="<?=$colspan?>">
  <?= $old->exam_year.'<br>'.$this->Common_model->getClassNameByClassId($old->class_id).'<br>'.$old->roll_no.'<br>'.$old->obtain_marks.'/'.$old->total_marks?>
 
</td>  
 <?php }

  }
  $grandTotal_ob = $total_marks_obt + $grandTotal_ob;
  $grandTotal_mar =  $total_paper_marks + $grandTotal_mar;
  $percent = round(($grandTotal_ob/$grandTotal_mar)*100,2);    
    if($percent>=60){
      $div = "First";
    }elseif($percent<60 && $percent>=40){
      $div  = "Second";
    }else{
      $div = "Third";
    }
 if($final_result == "FAIL" || $final_result == "RW" || $final_fail !=0 ){
  $grandTotal_ob = '-';
  $grandTotal_mar = '-';
  $percent = '-';
  $div = '-';
  if($final_fail !=0){
    $final_result ='RWPM';
    $final_remark ="RWPM";
  }
 }
 
 ?>
  
<td class="align-middle text-center " ><strong>Result</strong><br><?= $final_result?></td>
<td class="align-middle text-center "  colspan="2"><strong>Grand Total</strong><br><?= $grandTotal_ob.'/'.$grandTotal_mar?></td>
<td class="align-middle text-center "  colspan="<?=$colspan?>"><strong>%</strong><br><?= $percent?></td>
<td class="align-middle text-center "  colspan="2"><strong>Division</strong><br><?= $div?></td>
<td class="align-middle text-center "  colspan="3"><strong>Degree No. And Date</strong><br>-</td>
<td class="align-middle text-center "  colspan="2"><strong>Remark</strong><br><?= $final_remark?></td>
  </tr>
  <?php
 
  }
  ?>
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
          <?php  echo $generator->getBarcode($marksheetData[0]->bar_code_no.$student->roll_no, $generator::TYPE_CODE_128,2,25); ?>
    </td>
  </tr>
</tbody>
</table>
<?php
 $previous_center=$current_center; 
}
?>

<hr>
<table width="100%" class="last_table" border="0">
<tr>
<td colspan="3">&nbsp;</td>
<td colspan="3" align="center">Order for Declaration & Publication of Result</td>
</tr>
<tr style="height:100px; vertical-align: bottom;">
<td align="center" width="25%">Checked By</td>
<td align="center" width="25%">Sign of 1st Tabulator</td>
<td align="center" width="25%">Sign of 2nd Tabulator</td>
<!-- <td align="center" width="20%">Asst. Registrar</td> -->
<td align="center" width="25%">Registrar/Controller Of Examination</td>
</tr><tr><td colspan="5">&nbsp;</td></tr><tr><td colspan="5">&nbsp;</td></tr>
</table>
</td>
</tr>
</table>
</div>
