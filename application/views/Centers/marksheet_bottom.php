<?php 
$withheld = false;
$check_grace_marks = false;
$fail_count = 0;
$fali_tot_marks = 0;
$require_tot_marks = 0;
$tot_marks = 0;
$abs_count = 0;
$int_fail_count = 0;
foreach($new_exam_form as $marks){
  $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code,"class_id"=>$marks->class_id));

  if($marks->paper_type=='theory'){
     if($student->university_mode != 'PVT'){
    $tot_marks +=  $paper_master[0]->max_theory_marks;
    if($marks->theory_marks>=$paper_master[0]->min_theory_marks){
      $result = "PASS";
    }
    if($marks->theory_marks==''){
      $withheld = true;
    }
    if($marks->theory_marks<$paper_master[0]->min_theory_marks){
      $result = "Fail";
      $fail_count++;
      $fali_tot_marks += $marks->theory_marks;
      $require_tot_marks +=$paper_master[0]->min_theory_marks;
    }
    if($marks->theory_marks=='ABS'){
      $abs_count++;
      $result = "Fail";
      $fail_count++;
    }
    if($marks->int_marks=='ABS'){
      $abs_count++;
      $result = "Fail";
      $fail_count++;
    }
    if($paper_master[0]->max_internal_marks != 0){
    if($marks->int_marks<$paper_master[0]->min_internal_marks){
      
      $result = "Fail";
      $fail_count++;
      $int_fail_count++;
    }
  }
    if(($marks->int_marks=='N' || $marks->int_marks=='') && $marks->max_internal_marks !=0) {
     $withheld = true;
   }
  }else{
    $tot_marks +=  $paper_master[0]->private_max_theory_marks;
    if($marks->theory_marks>=$paper_master[0]->private_min_theory_marks){
      $result = "PASS";
    }
    if($marks->theory_marks==''){
      $withheld = true;
    }
    if($marks->theory_marks<$paper_master[0]->private_min_theory_marks){
      $result = "Fail";
      $fail_count++;
      $fali_tot_marks += $marks->theory_marks;
      $require_tot_marks +=$paper_master[0]->private_min_theory_marks;
    }
    if($marks->theory_marks=='ABS'){
      $abs_count++;
      $result = "Fail";
      $fail_count++;
    }

  }
  }else{
    if($student->university_mode != 'PVT'){
    $tot_std_marks += $marks->p_marks;
    $tot_marks += $paper_master[0]->max_theory_marks;

    if($marks->p_marks>=$paper_master[0]->min_theory_marks){
      $result = "PASS";
    }
    if($marks->p_marks=='' || $marks->p_marks=='N'){
      $withheld = true;
    }
    if($marks->p_marks<$paper_master[0]->min_theory_marks){
      $result = "FAIL";
      $fail_count++;
      $fali_tot_marks += $marks->p_marks;
      $require_tot_marks += $paper_master[0]->min_theory_marks;
    }
    if($paper_master[0]->max_internal_marks != 0){
      if($marks->int_marks<$paper_master[0]->min_internal_marks){
        $result = "Fail";
        $fail_count++;
        $int_fail_count++;
      }
    }
    if ($marks->p_marks=='ABS') {
      $abs_count++;
      $result = "FAIL";
      $fail_count++;
    }
  }
}
}


$require_grace_marks = $require_tot_marks-$fali_tot_marks;

if($fail_count<3 && $require_grace_marks<4 && $abs_count==0 && $fail_count!=0 &&  $int_fail_count == 0){
      $check_grace_marks = true;
}

// if ($fail_count>0 && !$check_grace_marks) {
if ($fail_count>0 && !$check_grace_marks && $marks->student_id!=684208 && $classData->final_result_permission!='Y') {  
    ?>
  <div class="text-center text-primary border-right border-left border-bottom border-dark py-3">
    <h1 class=" text-center mb-0">Statement Of Marks</h1>
    <h3 class="text-center">WH</h3>
  </div>
  <?php
}else{

?>
<?php 
if ($withheld) { 
  ?>
  <div class="text-center text-primary border-right border-left border-bottom border-dark py-3">
    <h1 class=" text-center mb-0">Statement Of Marks</h1>
    <h3 class="text-center">WH</h3>
  </div>
  <?php
}else{
  ?>
  <table class="table">
   <thead>
    <tr class="border-top-0">
      <th colspan="8" class="text-center">Statement Of Marks</th>
    </tr>
    <tr class=" text-center" >
      <th class="border-dark text-center" rowspan="2">Subject</th>
      <th class="border-dark text-center" colspan="2">Theory <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ ?> / Practical<?php } ?> Marks </th>
      <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ 
        if($student->university_mode != "PVT"){?>
            
            <th class="border-dark text-center" colspan="2">Internal Marks</th>
       <?php }
       ?>
            <th class="border-dark text-center" colspan="2">Total</th>
      <?php } ?>
      <th class="border-dark text-center" rowspan="2">Result</th>
    </tr>
    <tr>
      <th class="border-dark text-center" scope="row">Max Marks</th>
      <th class="border-dark text-center" scope="row">Obtained</th>
      <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ 
         if($student->university_mode != "PVT"){?>
        
            <th class="border-dark text-center" scope="row">Max Marks</th>
            <th class="border-dark text-center" scope="row">Obtained</th>
<?php }?>
            <th class="border-dark text-center" scope="row">Max Marks</th>
            <th class="border-dark text-center" scope="row">Obtained</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody>
    <?php 
    $total_paper_marks = 0;
    $total_student_marks = 0 ;
    $result = "";
    $total_max_marks = 0 ;
    $total_obtained_marks = 0;    
    foreach($new_exam_form as  $marks){
      $result_1_paper = '';
      $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code));
    ?>
    <tr>
      <th><?php echo $this->Common_model->getPaperNameById($marks->paper_id); ?></th>
      <th class="text-center"><?php echo ($student->university_mode != 'PVT')?  $paper_master[0]->max_theory_marks : $paper_master[0]->private_max_theory_marks; ?></th>
      <th class="text-center">
        <?php
          if($marks->paper_type=='theory'){
            if($student->university_mode != 'PVT'){
            if($paper_master[0]->max_internal_marks !=0){
            $total_max_marks += $paper_master[0]->max_theory_marks+ $paper_master[0]->max_internal_marks;
            $total_obtained_marks += $marks->theory_marks+$marks->int_marks;
            }else{
              $total_max_marks += $paper_master[0]->max_theory_marks;
            $total_obtained_marks += $marks->theory_marks;
            }
            if($marks->theory_marks<$paper_master[0]->min_theory_marks || $marks->theory_marks=="ABS"){
              echo $marks->theory_marks;
              if($check_grace_marks){
                echo ' G';
                $result_1_paper = 'PASS BY GRACE';
              }else{
                echo '<span style="color:red">*</span>';
                $result_1_paper = 'FAIL';
              }  
            }else{
              echo $marks->theory_marks;
              $result_1_paper = 'PASS';
            }
          }else{

           
                $total_max_marks += $paper_master[0]->private_max_theory_marks;
              $total_obtained_marks += $marks->theory_marks;
              
              if($marks->theory_marks<$paper_master[0]->private_min_theory_marks || $marks->theory_marks=="ABS"){
                echo $marks->theory_marks;
                if($check_grace_marks){
                  echo ' G';
                  $result_1_paper = 'PASS BY GRACE';
                }else{
                  echo '<span style="color:red">*</span>';
                  $result_1_paper = 'FAIL';
                }  
              }else{
                echo $marks->theory_marks;
                $result_1_paper = 'PASS';
              }

          }
          }else{
            if($student->university_mode != 'PVT'){
            $total_obtained_marks += $marks->p_marks;
            if($marks->paper_type!="theory" && $practical_internal_marks=='Y' )
              {
                $total_obtained_marks+=(int) $marks->int_marks;
                $total_max_marks += $paper_master[0]->max_internal_marks;
              }  
            $total_max_marks += $paper_master[0]->max_theory_marks;
            if($marks->p_marks < $paper_master[0]->min_theory_marks || $marks->p_marks=="ABS"){
              echo $marks->p_marks;
              $result_1_paper = 'FAIL';
              ?>
              <span style="color:red">*</span> 
              <?php
            }else{
              echo $marks->p_marks;
              $result_1_paper = 'PASS';
            }
          }
          }
        ?>
      </th>
      <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ 
         if($student->university_mode != "PVT"){ ?>
      <th class="text-center">
        
        <?php 
        
       
          if( $marks->paper_type !='theory' && $practical_internal_marks=='N'){
            echo '-';
          }else if($marks->paper_type !='theory' && $practical_internal_marks=='Y' && $paper_master[0]->max_internal_marks !=0){
            echo $paper_master[0]->max_internal_marks;
          }else if($paper_master[0]->max_internal_marks == 0){
            echo '-';
          }
          else{
            echo $paper_master[0]->max_internal_marks;
          } ?>
        </th>
      <th class="text-center"><?php
        if( $marks->paper_type !='theory' && $practical_internal_marks=='N'){
          echo '-';
        }elseif($marks->paper_type !='theory' && $practical_internal_marks=='Y') {
          if(($marks->int_marks<$paper_master[0]->min_internal_marks || $marks->int_marks=='ABS') && $paper_master[0]->max_internal_marks != 0){
            echo $marks->int_marks;
            $result_1_paper = 'FAIL';
            ?><span style="color:red">*</span> <?php
          } else if ($paper_master[0]->max_internal_marks == 0){
            echo '-';
          }
          else{
            echo $marks->int_marks;
          }
        }else{
          if(($marks->int_marks<$paper_master[0]->min_internal_marks || $marks->int_marks=='ABS') && $paper_master[0]->max_internal_marks != 0){
          echo $marks->int_marks;
          $result_1_paper = 'FAIL';
          ?><span style="color:red">*</span> <?php
          }else if ($paper_master[0]->max_internal_marks == 0){
            echo '-';}
          else{
            echo $marks->int_marks;
          }
        }
        
        ?>
      </th>
      <?php } ?>
      <th class="text-center">
        <?php 
        if($student->university_mode != 'PVT'){ 
        if($marks->paper_type!="theory" && $practical_internal_marks=='N' ){
                       echo (int)$paper_master[0]->max_theory_marks;
              }
              elseif($marks->paper_type!="theory" && $practical_internal_marks=='Y' ){ 
                if($paper_master[0]->max_internal_marks !=0){ 
                echo (int) $paper_master[0]->max_theory_marks + (int) $paper_master[0]->max_internal_marks;
                }else{
                  echo (int) $paper_master[0]->max_theory_marks;
                }
                
              }
              else{ 
                if($paper_master[0]->max_internal_marks !=0){
                echo (int) $paper_master[0]->max_theory_marks + (int) $paper_master[0]->max_internal_marks;
                }else{
                  echo (int) $paper_master[0]->max_theory_marks;
                }
              }
             }else{
              if($marks->paper_type!="theory" && $practical_internal_marks=='N' ){
                echo (int)$paper_master[0]->private_max_theory_marks;
              }
              elseif($marks->paper_type!="theory" && $practical_internal_marks=='Y' ){ 
               
                  echo (int) $paper_master[0]->private_max_theory_marks;
                
                
              }
              else{ 
              
                  echo (int) $paper_master[0]->private_max_theory_marks;
                
              }    

             } ?>
      </th>
      <th class="text-center">
        <?php
         if($student->university_mode != "PVT"){
        if($marks->paper_type!="theory" && $practical_internal_marks=='N' ){
                       echo (int)$marks->p_marks ;
              }
              elseif($marks->paper_type!="theory" && $practical_internal_marks=='Y' ){ 
                if($paper_master[0]->max_internal_marks !=0) {
                echo (int) $marks->p_marks + (int) $marks->int_marks;
                }else{
                  echo (int) $marks->p_marks; 
                }
              }else{ 
                if($paper_master[0]->max_internal_marks !=0) {
                echo (int) $marks->theory_marks + (int) $marks->int_marks;
                }else{
                  echo (int) $marks->theory_marks;
                }
              } 
             }else{
              if($marks->paper_type!="theory" && $practical_internal_marks=='N' ){
                echo (int)$marks->p_marks ;
       }
       elseif($marks->paper_type!="theory" && $practical_internal_marks=='Y' ){ 
         if($paper_master[0]->max_internal_marks !=0) {
         echo (int) $marks->p_marks + (int) $marks->int_marks;
         }else{
           echo (int) $marks->p_marks; 
         }
       }else{ 
        
         echo (int) $marks->theory_marks;
       
             } 
            }?>
      </th>
      <?php } ?>
      <th><?php echo $result_1_paper;?></th>
    </tr>
  <?php } ?>
  <tr>
    <th>Total</th>
    <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ 
      if($student->university_mode !='PVT'){?>
    <th></th>
    <th></th>
    <?php }?>
    <th></th>
    <th></th>
    <?php } ?>
    <th class="text-center"><?php echo  $total_max_marks?></th>
    <th class="text-center"><?php  echo  $total_obtained_marks ?></th>
    <th>
    </th>
  </tr>
</tbody>
</table>
<div class="row mb-5">
  <div class="col-12 text-center">
    <button type="button" style="background-color:#fdf8d2; opacity: 1;" width="100%" class="btn   btn-block" disabled><h6 style="color:#000000; font-weight: bold;">Result</h6></button>
    <button type="button" style="opacity: 1"  width="100%" class="btn btn-light  btn-block text-dark" disabled><h6 style=" opacity: 1;color:   000000;font-weight: bold;">
      <?php 
            if($check_grace_marks){
              echo "PASS BY GRACE";
            }elseif($fail_count>0){
              echo "ATKT";
            }else{
              echo "PASS";
            }  ?>
    </h6></button>
  </div>
</div>

<div class="row" style=" margin-top: -13px;font-size: 18px;">
  <div class="col-12">
   <span style="font-weight : 900"> Disclaimer :</span>
   <span style="font-weight : bold">This information should not be treated as Marksheet.<br> 
    MMYVV is not responsible for any inadvertent error that may have crept in the results being published on INTERNET. The results published on internet are for immediate information to the examinee
  </span>
</div>
</div>
</div>
<div class="form-group col-md-12 text-center mt-3"  id="print_btn">
	<button  type="button" onclick="printhiv('printarea')"  class="btn btn-primary font-weight-bold mr-2" >Print</button>
</div>
<script type="text/javascript">
	function printhiv(divName) {
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
<?php } 
  }
?>