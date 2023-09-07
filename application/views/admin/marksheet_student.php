<?php 
$withheld = false;
$check_grace_marks = false;
$fail_count = 0;
$fali_tot_marks = 0;
$require_tot_marks = 0;
$tot_marks = 0;
$abs_count = 0;

foreach($old_result_data as $marks){
  $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code,"class_id"=>$marks->class_id));

  
}


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
    foreach($old_result_data as  $marks){
      // print_r($marks);die;
      $result_1_paper = '';
      $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code));
    ?>
    <tr>
      <th><?php echo $marks->paper_name; ?></th>
      <th class="text-center"><?php  echo ($marks->type=='Sessional')?' - ':$paper_master[0]->max_theory_marks; ?></th>
      <th class="text-center">
      <?php 
            if($marks->type == 'theory'){
              $total_max_marks += $paper_master[0]->max_theory_marks+ $paper_master[0]->max_internal_marks;
                      $total_obtained_marks += $marks->theory_marks+$marks->int_marks;
                      if($marks->theory_marks<$paper_master[0]->min_theory_marks || $marks->theory_marks=="ABS"){
                      if($marks->carry_theory==""){
                        echo $marks->theory_marks."<span style='color:red'>"."*"."</span>";
                      }else{
                        echo $marks->theory_marks.$marks->carry_theory;
                      }
                    }else{
                      if($marks->carry_theory==""){
                        echo $marks->theory_marks;
                          }else{
                            echo $marks->theory_marks.$marks->carry_theory;
                          }
                    }
            }elseif($marks->type=='Sessional'){
              $total_max_marks += $paper_master[0]->max_internal_marks;
              $total_obtained_marks += $marks->int_marks;
              echo ' - ';
            }else{
              $total_max_marks += $paper_master[0]->max_theory_marks+ $paper_master[0]->max_internal_marks;
            $total_obtained_marks += $marks->p_marks+$marks->int_marks;
            if($marks->p_marks < $paper_master[0]->min_theory_marks || $marks->p_marks=="ABS"){
              echo $marks->p_marks."<span style='color:red'>"."*"."</span>";
            }else{
              echo $marks->p_marks;
            }
            }?>
      </th>
      <?php if($marks->course_group_id!=36 && $marks->course_group_id!=37 ){ ?>
      <th class="text-center">
        <?php  
        if($marks->type=='Sessional'){
          echo $paper_master[0]->max_internal_marks;
        }
          else if( $marks->type !='theory' && $paper_master[0]->max_internal_marks == 0){
            echo '-';
          }else if($marks->type !='theory' &&$class->practical_internal_marks=='Y'){
            echo $paper_master[0]->max_internal_marks;
          }else{
            echo $paper_master[0]->max_internal_marks;
          } ?>
        </th>
      <th class="text-center"><?php
        if($marks->type=='Sessional'){
          if($marks->int_marks<$paper_master[0]->min_internal_marks || $marks->int_marks=='ABS'){
            echo $marks->int_marks;
            $result_1_paper = 'FAIL';
            ?><span style="color:red">*</span> <?php
          }else{
            echo $marks->int_marks;
          }
        }else{
        
        if( $marks->type !='theory' &&  $paper_master[0]->max_internal_marks == 0){
          echo '-';
        }elseif($marks->type !='theory' &&$class->practical_internal_marks=='Y') {
          if($marks->int_marks<$paper_master[0]->min_internal_marks || $marks->int_marks=='ABS'){
            echo $marks->int_marks;
            $result_1_paper = 'FAIL';
            ?><span style="color:red">*</span> <?php
          }else{
            echo $marks->int_marks;
          }
        }else{
          if($marks->carry_int==""){
            echo $marks->int_marks;
          }else{
            echo $marks->int_marks.$marks->carry_int;
          }
          
        }
      }
        ?>
      </th>
      <th class="text-center">
        <?php  
        if($marks->type!="theory" &&$class->practical_internal_marks=='N' ){
                       echo (int)$paper_master[0]->max_theory_marks;
              }
              elseif($marks->type!="theory" &&$class->practical_internal_marks=='Y' ){  
                echo (int) $paper_master[0]->max_theory_marks + (int) $paper_master[0]->max_internal_marks;
                
              }else{ 
                echo (int) $paper_master[0]->max_theory_marks + (int) $paper_master[0]->max_internal_marks;
              } ?>
      </th>
      <th class="text-center">
        <?php 
        if($marks->type=='Sessional'){
          echo  (int) $marks->int_marks; 
        }else{
            
              if($marks->type!="theory" &&$class->practical_internal_marks=='Y' ){  
                echo (int) $marks->p_marks + (int) $marks->int_marks;
                
              }else if($marks->type!="theory"){
                echo (int)$marks->p_marks ;
              }else{ 
                echo (int) $marks->theory_marks + (int) $marks->int_marks;
              } 
         } ?>
      </th>
      <?php } ?>
      <th><?php echo $marks->result;?></th>
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
           $exam = $this->Common_model->getRecordByWhere("old_exam_data",array('id'=>$old_result_data[0]->exam_data_id));
             echo $exam[0]->exam_result; ?>
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
<?php ?>