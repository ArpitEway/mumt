<?php 
$withheld = false;
$check_grace_marks = false;
$fail_count = 0;
$sessional_fail_count = 0;
$fali_tot_marks = 0;
$require_tot_marks = 0;
$tot_marks = 0;
$abs_count = 0;
$old_fail = false;
$isFinalClass = $this->Common_model->hasOneClass($student->course_group_id);
if($classData->last_class == 'L' && $isFinalClass == false){
  $classes = $this->Common_model->getRecordByWhere('class_master',array('id !='=>$student->class_id,'course_group_id'=>$student->course_group_id));
  foreach($classes as $old){
 $this->db->where_in('exam_result',array('PASS','PASS BY GRACE'));
$old_result = $this->Common_model->getRecordByWhere('old_exam_data',array('student_id'=>$student->student_id,'class_id'=>$old->id));

  if($old_result){
    $old_fail = false;
  
  }else{
    $old_fail = true;
  }
}
}
foreach($backlog_exam_form as $marks){
  $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code,"class_id"=>$marks->class_id));

  if($marks->paper_type=='theory'){
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
    if($marks->int_marks<$marks->min_internal_marks){
      $result = "Fail";
      $fail_count++;
    }
    if($marks->int_marks=='N' || $marks->int_marks=='') {
     $withheld = true;
   }

  }else if($marks->paper_type=='Sessional'){

    $tot_marks +=  $paper_master[0]->max_internal_marks;
   
    if($marks->int_marks=='ABS'){
      $abs_count++;
      $result = "Fail";
      $sessional_fail_count++;
    }
    if($marks->int_marks<$paper_master[0]->min_internal_marks){
      $result = "Fail";
      $sessional_fail_count++;
    }
    if($marks->int_marks=='N' || $marks->int_marks=='') {
     $withheld = true;
   }

  }else{
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
    if ($marks->p_marks=='ABS') {
      $abs_count++;
      $result = "FAIL";
      $fail_count++;
    }
  }
}


// $require_grace_marks = $require_tot_marks-$fali_tot_marks;
// if($fail_count<3 && $require_grace_marks<4 && $abs_count==0 && $fail_count!=0 && $sessional_fail_count==0){
//       $check_grace_marks = true;
// }


// if ($fail_count>0 && !$check_grace_marks) {
if ($fail_count>0  && $marks->student_id!=684208 && $classData->final_result_permission!='Y') {  
    ?>
  <div class="text-center text-primary border-right border-left border-bottom border-dark py-3">
    <h1 class=" text-center mb-0">Statement Of Marks</h1>
    <h3 class="text-center">WH</h3>
  </div>
  <?php
}else{

?>
<?php 
if ($withheld || in_array($student->exam_center_code  ,array('MDE052','MDE081','MDE156') )) { 
  ?>
  <div class="text-center text-primary border-right border-left border-bottom border-dark py-3">
    <h1 class=" text-center mb-0">Statement Of Marks</h1>
    <h3 class="text-center">WH</h3>
  </div>
  <?php
}elseif ($old_fail) {
  ?>
  <div class="text-center text-primary border-right border-left border-bottom border-dark py-3">
    <h1 class=" text-center mb-0">Statement Of Marks</h1>
    <h3 class="text-center">RWPM</h3>
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
      <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ ?>
            <th class="border-dark text-center" colspan="2">Internal Marks</th>
            <th class="border-dark text-center" colspan="2">Total</th>
      <?php } ?>
      <th class="border-dark text-center" rowspan="2">Result</th>
    </tr>
    <tr>
      <th class="border-dark text-center" scope="row">Max Marks</th>
      <th class="border-dark text-center" scope="row">Obtained</th>
      <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ ?>
            <th class="border-dark text-center" scope="row">Max Marks</th>
            <th class="border-dark text-center" scope="row">Obtained</th>
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
    foreach($backlog_exam_form as  $marks){
        $status = ($marks->status == 'C')?' C':'';
      $result_1_paper = '';
      $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code,'class_id'=>$marks->class_id));
    ?>
    <tr>
      <th><?php echo $paper_master[0]->paper_name; ?></th>
      <th class="text-center"><?php  if($marks->paper_type=='Sessional'){ echo '-';}else{
        echo $paper_master[0]->max_theory_marks;
      } ?></th>
      <th class="text-center">
        <?php
          if($marks->paper_type=='theory'){
            $total_max_marks += $paper_master[0]->max_theory_marks+ $paper_master[0]->max_internal_marks;
            $total_obtained_marks += $marks->theory_marks+$marks->int_marks;
            if($marks->theory_marks<$paper_master[0]->min_theory_marks || $marks->theory_marks=="ABS"){
              echo $marks->theory_marks;
            //   if($check_grace_marks){
            //     echo ' G';
            //     $result_1_paper = 'PASS BY GRACE';
            //   }else{
                echo '<span style="color:red">*</span>';
                $result_1_paper = 'FAIL';
             //}  
            }else{
              echo $marks->theory_marks.$status;
              $result_1_paper = 'PASS';
            }
          }else if($marks->paper_type=='Sessional'){
            echo '-';
        //     $total_max_marks +=  $paper_master[0]->max_internal_marks;
        //     $total_obtained_marks += $marks->int_marks;
        //     if($marks->int_marks<$paper_master[0]->min_internal_marks || $marks->int_marks=="ABS"){
        //         echo $marks->int_marks;
        //         echo '<span style="color:red">*</span>';
        //         $result_1_paper = 'FAIL';
           
        //     }else{
        //       echo $marks->int_marks.$status;
        //       $result_1_paper = 'PASS';
        //     }

          }
          else{
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
              echo $marks->p_marks.$status;
              $result_1_paper = 'PASS';
            }
          }
        ?>
      </th>
      <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ ?>
      <th class="text-center">
        <?php
        if($marks->paper_type=='Sessional'){
            echo $paper_master[0]->max_internal_marks;
        }else if( $marks->paper_type !='theory' && $practical_internal_marks=='N'){
            echo '-';
          }else if($marks->paper_type !='theory' && $practical_internal_marks=='Y'){
            echo $paper_master[0]->max_internal_marks;
          }else{
            echo $paper_master[0]->max_internal_marks;
          } ?>
        </th>
      <th class="text-center"><?php
        if($marks->paper_type=='Sessional'){
            $total_max_marks +=  $paper_master[0]->max_internal_marks;
            $total_obtained_marks += $marks->int_marks;
            if($marks->int_marks<$paper_master[0]->min_internal_marks || $marks->int_marks=="ABS"){
                echo $marks->int_marks;
                echo '<span style="color:red">*</span>';
                $result_1_paper = 'FAIL';
           
            }else{
              echo $marks->int_marks.$status;
              $result_1_paper = 'PASS';
            }
        }else if( $marks->paper_type !='theory' && $practical_internal_marks=='N'){
          echo '-';
        }elseif($marks->paper_type !='theory' && $practical_internal_marks=='Y') {
          if($marks->int_marks<$paper_master[0]->min_internal_marks || $marks->int_marks=='ABS'){
            echo $marks->int_marks;
            $result_1_paper = 'FAIL';
            ?><span style="color:red">*</span> <?php
          }else{
            echo $marks->int_marks.' C';
          }
        }else{
          echo $marks->int_marks.' C';
        }
        ?>
      </th>
      <th class="text-center">
        <?php  
        if($marks->paper_type!="theory" && $practical_internal_marks=='N' ){

                if($marks->paper_type =="Sessional"){
                echo (int)$paper_master[0]->max_internal_marks;
                }else{
                echo (int)$paper_master[0]->max_theory_marks;
                }
              }
              elseif($marks->paper_type!="theory" && $practical_internal_marks=='Y' ){  
                echo (int) $paper_master[0]->max_theory_marks + (int) $paper_master[0]->max_internal_marks;
                
              }else{ 
                echo (int) $paper_master[0]->max_theory_marks + (int) $paper_master[0]->max_internal_marks;
              } ?>
      </th>
      <th class="text-center">
        <?php if($marks->paper_type!="theory" && $practical_internal_marks=='N' ){
                
                if($marks->paper_type =="Sessional"){
                echo (int)$marks->int_marks.$status;
                }else{
                echo (int)$marks->p_marks.$status ;
                }
              }
              elseif($marks->paper_type!="theory" && $practical_internal_marks=='Y' ){  
                echo (int) $marks->p_marks + (int) $marks->int_marks.$status;
                
              }else{ 
                echo (int) $marks->theory_marks + (int) $marks->int_marks.$status;
              }  ?>
      </th>
      <?php } ?>
      <th><?php echo $result_1_paper;?></th>
    </tr>
  <?php } ?>
  <tr>
    <th>Total</th>
    <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ ?>
    <th></th>
    <th></th>
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
     
            // if($check_grace_marks){
            //   echo "PASS BY GRACE";
            // }else
            if($fail_count>0 || $sessional_fail_count >0){
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
<script>
function printhiv(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;

    $('#'+divName).css("margin-top"," 20px");
		
		$("#print_btn").css("display", "none");
		$(".offcanvas-footer").css("display", "none");
		$("#radio_btn_select").css("display", "none");
    $("#center").css("display", "none");
    $(".content-head").css("display", "none");
    $("#pro_remark").css("display", "none");
    $("#result_msg").css("display", "none");
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>
<?php } 
  }
?>