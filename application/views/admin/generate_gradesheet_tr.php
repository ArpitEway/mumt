<title><?php echo (isset($title)) ? $title : ''; ?></title>
<style>
  @media print {
    body {
      -webkit-print-color-adjust: exact;
      -moz-print-color-adjust: exact;
      -ms-print-color-adjust: exact;
      -ms-print-color-adjust: exact;
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
  }
</style>
<div id="footer_wrapper">
  <div id="footer_content">
  AGPA-Annual Grade Point Average, SGPA-Semester Grade Point Average, RW-Result Withheld, RWE-Want of Enrolment, RWPM-Want of Prev. Sem/Year Marks, RWPR-Practical Marks Not Received, RWAS-Assignment Marks Not Received, RWPJ-Project Marks Not Received, UFM-Unfair Means,GR-Grace Mark In One Theory Paper For Passing, VCG-Vice-Chancellor's One Grace Mark In Division
  </div>
</div>
<?php

  $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
  $marksheetData = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id'=>$class_id));
  $classData= $this->Common_model->getRecordById('class_master','id', $class_id);
  $isFinalClass = $this->Common_model->hasOneClass($course_group_id);
  // $final_class = $this->Common_model->hasFinalClass($course_group_id);
  if($classData->last_class == 'L'){
    $final_class = true;
  }
  $course_duration = ($isFinalClass) ? "(One Year Course)" : $classData->class_name;
  $rowspanhead = ($classData->project!='N' || $classData->practical!='N') ? "7" : "4";
  $rowspandata = 10;
  //($classData->project!='N' || $classData->practical!='N') ? "6" : "6";
  if($classData->internal=='N'){
    $rowspandata--;  
  }
  $page_break_count = -1;
  $br_code_id = 0;
  // $roll_no = array(); 
  //$page_no = 0;
  $page_no=$pagenumber;
  $previous_center=$current_center="";
  $GLOBALS['foundation_1'] =0;
  foreach($students as $student)
  {
    
   
    
 
    $current_center=$student->center_id;
    $page_break_count++;
    $marks = $this->Common_model->student_info_for_result($student->student_id,$student->old_class_id);
    $BarCodecolspan = 10 + count($marks); 
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
    $count_theory =0;
    $count_practical =0;
    $count_int =0;
    $fc1 =0;
    $fc2=0;
    $fc1_abs ='';
    $fc2_abs='';
    $fc1_max =0;
    $fc2_max =0;
    $fc1_min =0;
    $fc2_min =0;
    $final_result = '';

    if($student->university_mode == 'REG'){
      $rowspanhead = ($classData->project!='N' || $classData->practical!='N') ? "6" : "3";
      // $rowspandata = (($classData->project!='N' || $classData->practical!='N') && $classData->internal!='N')? "5" : "4";
      if($classData->project!='N' || $classData->practical!='N' && $classData->internal!='N'){
        $rowspandata = "10";
      }else if($classData->project!='N' || $classData->practical!='N' && $classData->internal =='Y'){
        $rowspandata = "4";
      }else if($classData->project =='N' &&  $classData->practical=='N' && $classData->internal=='N'){
        $rowspandata = "3";
      }

      
    }else{
      $rowspanhead = "3";
      $rowspandata = "4";
    }
    foreach($marks as $new_exam_form)
    {
      
      if($new_exam_form->type=='theory'){
        if($new_exam_form->sub_group_id == 1){
          $count_theory++;
          if($new_exam_form->group_paper_name == 'FC1' ){
            if($new_exam_form->theory_marks==''){
              $rw_count++;
           
            }
            if($new_exam_form->theory_marks=='ABS'){
              $fc1_abs .= $new_exam_form->theory_marks;
           
            }
          $fc1 += (int) $new_exam_form->theory_marks;
          $fc1_max += (int) $new_exam_form->max_theory_marks;
          $fc1_min += (int) $new_exam_form->min_theory_marks;
          }else{
            if($new_exam_form->theory_marks==''){
              $rw_count++;
             
            }
            if($new_exam_form->theory_marks=='ABS'){
              $fc2_abs .= $new_exam_form->theory_marks;
           
            }
            $fc2 += (int) $new_exam_form->theory_marks;
            $fc2_max += (int) $new_exam_form->max_theory_marks;
            $fc2_min += (int) $new_exam_form->min_theory_marks;
          }
        }else{
       

        $total_theory_marks_obt +=(int) $new_exam_form->theory_marks;
        $total_int_marks_obt += (int) $new_exam_form->int_marks;
        $total_theory_asm_marks = (int) $new_exam_form->theory_marks+ (int) $new_exam_form->int_marks;
        $total_marks_obt  += (int) $new_exam_form->theory_marks+ (int) $new_exam_form->int_marks;
        if($student->university_mode != 'PVT'){
        $total_paper_marks += (int) $new_exam_form->max_theory_marks + (int) $new_exam_form->max_internal_marks;
        $tot_marks += (int) $new_exam_form->max_theory_marks;
        }else{
          $total_paper_marks += (int) $new_exam_form->private_max_theory_marks ;
          $tot_marks += (int) $new_exam_form->private_max_theory_marks;
        }
        $tot_std_marks += (int) $new_exam_form->theory_marks;
        $count_theory++;

        if($new_exam_form->theory_marks=='ABS'){
          array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
          $theory_abs_count++;
        }

        if($new_exam_form->theory_marks==''){
          $rw_count++;
        }

        if($new_exam_form->int_marks=='N' && $classData->internal=="Y" && $student->university_mode != 'PVT' && $new_exam_form->max_internal_marks !=0){
          $rw_count++;
        }
        if($student->university_mode != 'PVT'){
            if($new_exam_form->theory_marks+$new_exam_form->int_marks<$new_exam_form->min_theory_marks+$new_exam_form->min_internal_marks  && $new_exam_form->theory_marks!=''){
              array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
              $fail_count++;
              $fail_tot_marks += $new_exam_form->theory_marks+$new_exam_form->int_marks;
              $require_tot_marks += $new_exam_form->min_theory_marks + $new_exam_form->min_internal_marks;
            }

            if($new_exam_form->int_marks<$new_exam_form->min_internal_marks && $student->university_mode != 'PVT'){
              $int_fail_count++;
              array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
            }else{
              $count_int++;
            }
        }else{
            if($new_exam_form->theory_marks<$new_exam_form->private_min_theory_marks  && $new_exam_form->theory_marks!=''){
              array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
              $fail_count++;
              $fail_tot_marks += $new_exam_form->theory_marks;
              $require_tot_marks += $new_exam_form->private_min_theory_marks;
            }

        }
            if($new_exam_form->int_marks=="ABS" && $student->university_mode != 'PVT'){
              array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
              $int_abs_count++;
              $int_fail_count++;
            }
            else{
              $count_int++;
            }
      }

     
    }
    if($new_exam_form->type!='theory' && $student->university_mode != 'PVT'){
      // echo $new_exam_form->p_marks;die;
      $total_paper_marks += (int) $new_exam_form->max_theory_marks ;
      $total_marks_obt += (int) $new_exam_form->p_marks ;
      $count_practical++;

      if($new_exam_form->p_marks=='' || $new_exam_form->p_marks=='N'){
        $rw_count++;
      }
      if($new_exam_form->p_marks=='ABS'){
        $p_abs_count++;
        array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
      }
      if($new_exam_form->p_marks<$new_exam_form->min_theory_marks){
        $p_fail_count++;
        array_push( $atkt_paper_codes_array ,$new_exam_form->paper_code );
      }
    }

    }
    // echo $fc1.'sss'.$fc2.'<br>';
    $total_theory_marks_obt +=$fc1+$fc2;
    $total_marks_obt  +=$fc1+$fc2;
    $total_paper_marks +=$fc1_max +$fc2_max;
    $tot_marks += $fc1_max +$fc2_max;
    $tot_std_marks += $fc1+$fc2;
    // $count_theory +=  $fc1+$fc2;
  
   if($fc1_abs === 'ABSABS'){
    $theory_abs_count++;
   }
   if($fc2_abs === 'ABSABS'){
    $theory_abs_count++;
   }
    if($fc1 < $fc1_min ){
      array_push( $atkt_paper_codes_array ,'FC1' );
          $fail_count++;
          $GLOBALS['foundation_1']++;
          $fail_tot_marks += $fc1;
          $require_tot_marks += $fc1_min;

    }
    if($fc2 < $fc2_min){
      array_push( $atkt_paper_codes_array ,'FC2' );
      $fail_count++;
      $GLOBALS['foundation_1']++;
      $fail_tot_marks += $fc2;
      $require_tot_marks += $fc2_min;

    }
    // echo $fail_count.'rw'.$rw_count.'tabs'.$theory_abs_count.'pabs'.$p_fail_count.'intabs'.$int_fail_count;
    if ($fail_count==0 && $rw_count==0 && $p_fail_count==0 && $int_fail_count==0 && $theory_abs_count==0 && $p_abs_count==0) {
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
      <p align="center" class="line-height">Tabulation Register for <strong><?php echo $student->course_name; echo '&nbsp'. $course_duration; ?></strong> <?php echo $marksheetData[0]->exam_session.' (CBCS)';?>
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
            <td class="align-middle text-center result" rowspan="<?php echo $rowspanhead ?>">AGPA/SGPA</td>
            <td class="align-middle text-center remarks" rowspan="<?php  echo $rowspanhead ?>">Remarks</td>
          </tr>
          <tr>
            <td class="align-middle text-right paper">Theory Marks Max -></td>
            <?php foreach($marks as $paper_master){ ?>
              <td  class="align-middle text-center paper_code"><?php
              if($paper_master->paper_type=='theory' ){ 
                if($student->university_mode != 'PVT'){
                echo  $paper_master->max_theory_marks;
              }else{
                echo  $paper_master->private_max_theory_marks;
              }
            }
            ?>
            </td>
            <?php } ?>
          <td class=""></td>
        </tr>
        <?php if($classData->internal=="Y" && $student->university_mode != 'PVT'){ ?>
        <tr>
          <td class="align-middle text-right paper">Internal Marks Max -></td>
          <?php  foreach($marks as $paper_master){     ?>
            <td  class="align-middle text-center internal_mark">
              <?php if($paper_master->paper_type=="theory"){ if($paper_master->max_internal_marks !=0){echo  $paper_master->max_internal_marks;}else{ echo '';}};  ?></td>
          <?php }  ?>
          <td class="align-middle text-center"></td>
        </tr>
        <?php 
        }
        if(($classData->project!='N' || $classData->practical!='N') && $student->university_mode != 'PVT'){
          // echo $student->university_mode;die;
        ?>
        <tr>
          <td class="align-middle text-right paper"> <?=($classData->project=='Y') ? 'Project' : 'Practical' ?> Marks Max-></td>
          <?php foreach($marks as $paper_master){   ?>
          <td  class="align-middle text-center practical_marks">
            <?php if($paper_master->paper_type!="theory"){echo  $paper_master->max_theory_marks ;};  ?>
          </td>
          <?php } ?>
          <td class="align-middle text-center"></td>
        </tr>
        <?php }  ?>
        <tr>
          <td class="align-middle text-right paper"> Total Marks Max/Min-></td>
          <?php 
          $x =1;
          foreach($marks as $paper_master){ 
            $count = count($marks)-2;  
            $paper_codes=array('1RBBA1','1RBBA2','1RBA1','1RBA2','1RBCOM1','1RBCOM2','1RBCOMCA1','1RBCOMCA2','1RBCOMT1','1RBCOMT2','1RBCA1','1RBCA2','1RBSCCBC1','1RBSCCBC2','1RBSCCS1','1RBSCCS2','1RBSCPCM1','1RBSCPCM2','1RBSW1','1RBSW2');
            if(in_array($paper_master->paper_code,$paper_codes))	
            {
            echo "<td colspan= '2' class='align-middle text-center practical_marks'>".'100/35'."</td>";
            }else{
              
              echo "<td class='align-middle text-center practical_marks'>".'100/35'."</td>";
            }
           if($count == $x){
            break;
           }
           $x++; 
            ?>
        
          <?php } ?>
          <td class="align-middle text-center"></td>
        </tr>
        <tr>
		<td class="align-middle text-right">Course Credit</td>
    <?php
    $credit = 0;
    $std  = $this->Common_model->getRecordByWhere('new_exam_form',array('class_id'=> $student->old_class_id,'student_id'=>$student->student_id));
		$this->classData = $this->Common_model->getRecordById('class_master','id',$student->old_class_id);
   
    if($std[0]->sub_group_id == 1){
			$papers = $this->Common_model->get_all_papers($student->student_id,$student->old_class_id);
     
		}

		if($this->classData->class_group == 'Y'){
			$papers_list = $this->Common_model->get_all_group_papers($student->student_id,$student->old_class_id);
		}
		
		foreach ($papers as $paper_master) {
      
      $credit += $paper_master['credit_point'];
      $paper_codes=array('1RBBA1','1RBBA2','1RBA1','1RBA2','1RBCOM1','1RBCOM2','1RBCOMCA1','1RBCOMCA2','1RBCOMT1','1RBCOMT2','1RBCA1','1RBCA2','1RBSCCBC1','1RBSCCBC2','1RBSCCS1','1RBSCCS2','1RBSCPCM1','1RBSCPCM2','1RBSW1','1RBSW2');
			// $paper_codes=array('1RBBA2','1RBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4');
				if(in_array($paper_master['paper_code'],$paper_codes) && $paper_master['sub_group_id'] == 1)	
				{
				?><td colspan= '2' class='text-center'>4</td><?php
				}else if ($paper_master['sub_group_id'] != 1){
					?>
					<td class='text-center'><?= $paper_master['credit_point'] ?></td>
          <?php
				}
		}
    foreach ($papers_list as $paper_master) {
    
      $credit += $paper_master['credit_point'];
      $paper_codes=array('1RBBA1','1RBBA2','1RBA1','1RBA2','1RBCOM1','1RBCOM2','1RBCOMCA1','1RBCOMCA2','1RBCOMT1','1RBCOMT2','1RBCA1','1RBCA2','1RBSCCBC1','1RBSCCBC2','1RBSCCS1','1RBSCCS2','1RBSCPCM1','1RBSCPCM2','1RBSW1','1RBSW2');
			// $paper_codes=array('1RBBA2','1RBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4');
				if(in_array($paper_master['paper_code'],$paper_codes))	
				{
				?><td colspan= '2' class='text-center'>4</td><?php
				}else if ($paper_master['sub_group_id'] != 1){
					?>
					<td class='text-center'><?= $paper_master['credit_point'] ?></td>
          <?php
				}
		}
    ?>
		<td class="text-center"><?= $credit?></td>
		</tr>
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
    
<span style="display:none;">
<?php
   $gradesheetData = $this->Gradesheet_tr_model->view_result($student->student_id,$student->course_group_id,$student->old_class_id,$student->university_mode);
  
  ?>
</span>
    <table class="table table1">
      <tbody>
        <tr>
          <th  class="align-middle text-center roll_no" rowspan="<?php echo $rowspandata ?>"><?php  echo $student->roll_number  ?> <br> <?php echo $student->enrollment_no  ?></th>
          <th class="align-middle text-center ms_no" rowspan="<?php echo $rowspandata ?>">
            <?php  echo $student->marksheet_no  ?>
          </th>
          <th  class="align-middle text-center photo" rowspan="<?php echo $rowspandata ?>"><img alt="N/A" src="<?= base_url('assets/student_image/'.$student->session.'/'.$student->photo) ?>" width="65px" height="90px"></th>
          <td  class="align-middle text-center name"  rowspan="<?php  echo $rowspandata ?>"><?php  echo $student->name ?>/ <br><?php  echo $student->f_h_name ?></td>
          <td  class="align-middle text-right paper" rowspan='2'> Paper Code</td>
         <?php foreach ($papers as $paper_master) {
      
     
      // $paper_codes=array('1RBBA1','1RBBA2','1RBA1','1RBA2','1RBCOM1','1RBCOM2','1RBCOMCA1','1RBCOMCA2','1RBCOMT1','1RBCOMT2','1RBCA1','1RBCA2','1RBSCCBC1','1RBSCCBC2','1RBSCCS1','1RBSCCS2','1RBSCPCM1','1RBSCPCM2','1RBSW1','1RBSW2');
			 $paper_codes=array('1RBBA2','1RBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4');
				if(in_array($paper_master['paper_code'],$paper_codes) && $paper_master['sub_group_id'] == 1)	
				{
				?><td colspan= '2' class='text-center'><?=$paper_master['group_paper_name'] ?></td><?php
        }else if ($paper_master['sub_group_id'] != 1){
         ?> <td  class='text-center'><?=$paper_master['group_paper_name'] ?></td> <?php
        }
		}
    foreach ($papers_list as $paper_master) {
    
     
      // $paper_codes=array('1RBBA1','1RBBA2','1RBA1','1RBA2','1RBCOM1','1RBCOM2','1RBCOMCA1','1RBCOMCA2','1RBCOMT1','1RBCOMT2','1RBCA1','1RBCA2','1RBSCCBC1','1RBSCCBC2','1RBSCCS1','1RBSCCS2','1RBSCPCM1','1RBSCPCM2','1RBSW1','1RBSW2');
			 $paper_codes=array('1RBBA2','1RBBA4','1RBA2','1RBA4','1RBCOM2','1RBCOM4','1RBCOMCA2','1RBCOMCA4','1RBCOMT2','1RBCOMT4','1RBCA2','1RBCA4','1RBSCCBC2','1RBSCCBC4','1RBSCCS2','1RBSCCS4','1RBSCPCM2','1RBSCPCM4','1RBSW2','1RBSW4');
				if(in_array($paper_master['paper_code'],$paper_codes))	
				{
				?><td colspan= '2' class='text-center'><?=$paper_master['group_paper_name'] ?></td><?php
				}else if ($paper_master['sub_group_id'] != 1){
          ?><td  class='text-center'><?=$paper_master['group_paper_name'] ?></td><?php 
        }
		}
    ?>
     <td  class="align-middle text-center total" rowspan='2'>Total</td>
          
          <td  class="align-middle text-center obtained" rowspan="<?php echo $rowspandata ?>"><?php 
                  echo $total_marks_obt .'/'. $total_paper_marks;
          ?></td>
          <td  class="align-middle text-center result" rowspan="<?php echo $rowspandata ?>"><?php echo $gradesheetData['result']; //$final_result?></td>
          <td  class="align-middle text-center result" rowspan="<?php echo $rowspandata ?>"><?php  echo ($gradesheetData['result'] == 'FAIL' || $gradesheetData['result'] == 'SUPP')?'0.00':number_format((float)$gradesheetData['agpa'], 2, '.', ''); ?></td>
          <td  class="align-middle text-cente remarks"  rowspan="<?php echo $rowspandata ?>"><?php 
         
          if($check_grace_marks){
            echo "-";
          }else{
            
            if($final_result == "RW"){
              echo "";
            }
            elseif($int_abs_count>0 &&  $theory_abs_count>0 && $p_abs_count>0){
              echo 'Year Break';
            }
            elseif($int_abs_count == $count_int ||  $theory_abs_count == ($count_theory - 2) || ($p_abs_count == $count_practical)){
             
              if($theory_abs_count == ($count_theory-2)){
                echo 'Year Break';
              }elseif($int_abs_count == $count_int){
                echo ' Absent In Internal'; 
              }elseif($p_abs_count == $count_practical){
                echo ' Absent In Practical';
              }
            }else{
              if($fail_count == ($count_theory - 2)){
                echo 'Year Break';
              }else{
              if(sizeof($atkt_paper_codes_array)>0){
                echo "SUPP in";
              }
              $atkt_paper_codes_array =  array_unique($atkt_paper_codes_array);
              foreach($atkt_paper_codes_array as $paper_code){
                echo  "<br>". $paper_code;
              }
            }
            }
          }
          ?>
        </td>
      </tr>
      <tr>
     
          <?php  foreach($marks as $paper_master){  ?>
            <td  class="align-middle text-center paper_code"><?php echo  $paper_master->paper_code;  ?></td>
          <?php   }  ?>
         
        </tr>
      <tr>
        <td class="align-middle text-right paper" >Theory Marks-></td>
        <?php foreach($marks as $new_exam_form){ ?>
          <td  class="align-middle text-center">
          <?php if($new_exam_form->paper_type=="theory" && $student->university_mode != 'PVT'){
          
              if($new_exam_form->theory_marks==''){
                echo '-';
              }elseif($new_exam_form->theory_marks+$new_exam_form->int_marks>=$new_exam_form->min_theory_marks+$new_exam_form->min_internal_marks && $new_exam_form->theory_marks!="ABS"){
                echo $new_exam_form->theory_marks;
              }else{
                echo $new_exam_form->theory_marks;
                if($new_exam_form->sub_group_id == 1){
                  if($GLOBALS['foundation_1']>0){
                    echo ($check_grace_marks) ? ' G' : ' F';
                  }
                }else{
                  echo ($check_grace_marks) ? ' G' : ' F';
                }
                
              }
            }else{
              if($new_exam_form->theory_marks==''){
                echo '-';
              }elseif($new_exam_form->theory_marks>=$new_exam_form->private_min_theory_marks && $new_exam_form->theory_marks!="ABS"){
                echo $new_exam_form->theory_marks;
              }else{
                echo $new_exam_form->theory_marks;
                echo ($check_grace_marks) ? ' G' : ' F';
              }

            }
          ?>
          </td>
        <?php
       }  ?>
        <td class="align-middle text-center result"><?php echo  $total_theory_marks_obt;  ?></td>
      </tr>
      <?php if($classData->internal=="Y" && $student->university_mode != 'PVT'){ ?>
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
  <?php if( ($classData->project!='N' || $classData->practical!='N') && $student->university_mode != 'PVT'){ ?>
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
      else if($new_exam_form->p_marks=="ABS"){
        echo "ABS F";
      }
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
      if($paper_master->paper_type=="theory" ){
        if($student->university_mode != 'PVT'){
       if($check_grace_marks==true){
        echo $paper_master->theory_marks+ $paper_master->int_marks;
      } elseif(($paper_master->theory_marks+$paper_master->int_marks<$paper_master->min_theory_marks+$paper_master->min_internal_marks) || $paper_master->theory_marks=='ABS' || $paper_master->int_marks=='ABS'){

        if($paper_master->theory_marks==''){
          echo "-";
        }else{
          
         
          if($paper_master->sub_group_id == 1){
            if($GLOBALS['foundation_1']>0){
              echo ($paper_master->theory_marks=='ABS') ? 'ABS F' :(int) $paper_master->theory_marks ." F";
            }else{
              echo (int) $paper_master->theory_marks+ (int) $paper_master->int_marks;
            }
          }else{
            echo ($paper_master->theory_marks=='ABS' && $paper_master->int_marks=='ABS') ? 'ABS F' : (int) $paper_master->theory_marks + (int) $paper_master->int_marks." F";
          }
        }
      }else{
        echo (int) $paper_master->theory_marks+ (int) $paper_master->int_marks;
      }
    }else{
      if($check_grace_marks==true){
        echo $paper_master->theory_marks;
      } elseif(($paper_master->theory_marks<$paper_master->private_min_theory_marks) ||  $paper_master->theory_marks=='ABS'){

        if($paper_master->theory_marks==''){
          echo "-";
        }else{
          echo (int) $paper_master->theory_marks ." F";
        }
      }else{
        echo (int) $paper_master->theory_marks;
      }

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
  
  <?php
   $this->Gradesheet_tr_model->view_result($student->student_id,$student->course_group_id,$student->old_class_id,$student->university_mode);
  
  ?>
  
 
  <?php  
  if($final_class && $isFinalClass == false){
    $final_rw = 0;
    $final_fail =0;
    
      $final_remark = "-"; 
    
  
  $old_result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id<'=>$student->old_class_id));
  ?> <tr>
  <td class="align-middle text-center "  colspan="2"><strong>
  <?= 'Session'.'<br>'.'Sem/Year'.'<br>'.'Roll no'.'<br>'.'Marks'?></strong>
 
</td> <?php
 foreach($old_result as $old){
  if($old->exam_result == "FAIL"){
 $final_fail++;
 $old->obtain_marks ='-';
 $old->total_marks = '-';
 
  }

  $total_ob = $total_marks_obt + $old->obtain_marks;
  $total_mar =  $total_paper_marks + $old->total_marks;
  $percent = round(($total_ob/$total_mar)*100,2);    
    if($percent>=60){
      $div = "First";
    }elseif($percent<60 && $percent>=40){
      $div  = "Second";
    }else{
      $div = "Third";
    }
  ?> 
  
  
 
<td class="align-middle text-center "  colspan="2">
  <?= $old->exam_year.'<br>'.$this->Common_model->getClassNameByClassId($old->class_id).'<br>'.$old->roll_no.'<br>'.$old->obtain_marks.'/'.$old->total_marks?>
 
</td>  
 <?php }
 if($final_result == "FAIL" || $final_result == "RW" || $final_fail !=0 ){
  $total_ob = '-';
  $total_mar = '-';
  $percent = '-';
  $div = '-';
  if($final_fail !=0){
    $final_result ='RWPM';
    $final_remark ="RWPM";
  }
 }
 
 ?>
  
<td class="align-middle text-center " ><strong>Result</strong><br><?= $final_result?></td>
<td class="align-middle text-center "  colspan="2"><strong>Grand Total</strong><br><?= $total_ob.'/'.$total_mar?></td>
<td class="align-middle text-center "  colspan="2"><strong>%</strong><br><?= $percent?></td>
<td class="align-middle text-center "  colspan="2"><strong>Division</strong><br><?= $div?></td>
<td class="align-middle text-center "  colspan="3"><strong>Degree No. And Date</strong><br>-</td>
<td class="align-middle text-center "  colspan="2"><strong>Remark</strong><br><?= $final_remark?></td>
  </tr>
  <?php
 
  }
  ?>
<?php if($isFinalClass){ ?>
  <?php if($final_result !="PASS" && !$check_grace_marks){ ?>
    <tr>
      <td class="text-center align-middle" colspan="<?=$BarCodecolspan ?>">    -   </td>
    </tr>
    <?php }else{  ?>
      <tr class="">
        <td  class="align-middle text-center " colspan="4" style="padding: 10px;"><?php  echo 'Tot : '. $total_marks_obt .'/'. $total_paper_marks; ?></td>
        <td class="align-middle text-center "  colspan="3"><?php  echo  'Per : '.$percentage .'%'; ?></td>
        <td class="align-middle text-center "  colspan="3"><?php  echo $final_result;?></td>
        <td class="align-middle text-center " colspan="5"> <?php  echo 'Division : '.$division;  ?></td>
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
  $previous_center=$current_center; 
  //=$student->center_id;
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
<td align="center" width="20%"><!-- Asst. Registrar --> </td>
<td align="center" width="20%">Registrar/Controller Of Examination</td>
</tr><tr><td colspan="5">&nbsp;</td></tr><tr><td colspan="5">&nbsp;</td></tr>
</table>
</td>
</tr>
</table>

</div>
<div class="d-flex justify-content-center break">
 <hr>
 <table class="text-center table-bordered  w-50">
 	<thead>
 		<tr>
 			<th>GRADE</th>
 			<th>GRADE POINT</th>
 			<th>MARKS FROM</th>
 			<th>RANGE TO</th>
 		</tr>
 	</thead>
 	<tbody>
 		<?php $letter_grade = $this->Common_model->get_record('letter_grade','*'); ?>
 		<?php foreach ($letter_grade as $rs): ?>
 		<tr>
 			<td><?=$rs['letter_grade'] ?></td>
 			<td><?=$rs['grade_point'] ?></td>
 			<td><?=$rs['min_marks'] ?></td>
 			<td><?=$rs['max_marks'] ?></td>
 		</tr>
 		<?php endforeach ?>
 	</tbody>
 </table>
 <hr>
</div>
