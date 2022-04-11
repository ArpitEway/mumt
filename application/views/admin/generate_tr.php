<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<style>
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
</style>
<?php

?>
<?php 

 $isFinalClass = $this->Common_model->isFinalClass($course_group_id);
$page_break_count = -1 ;
$br_code_id = 0 ;
$roll_no = array(); 
$page_no = 0 ;
foreach($students as $student)
{  
  array_push( $roll_no ,$student->roll_no );


  $page_break_count++;
  
    $class_details = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id'=>$class_id));
    // echo "<pre>";
    // print_r($class_details[0]->bar_code_no);

   
    $data['class_id']= $this->Common_model->getRecordByWhere('class_master',array('id' => $class_id));

    ($data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N') ?  $rowspanhead = "4" :  $rowspanhead = "3" ;
          
    ($data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N') ?  $rowspandata = "5" :  $rowspandata = "4" ;
      
    $marks= $this->Common_model->student_info_for_result($student->student_id);

    $show_precent_for_last_class= $this->Common_model->getRecordByWhere('class_master',array('course_group_id' =>$student->course_group_id));

 
        $total_theory_marks_obt = 0 ;
        $total_asmn_marks_obj=0 ;
        $total_marks_obt=0 ;
        $total_paper_marks = 0 ;
        $check_grace_marks = false;
        $fail_count = 0;
        $fali_tot_marks = 0;
        $require_tot_marks = 0;
        $tot_std_marks = 0;
        $tot_marks = 0;
        $final_result_fail_count = 0 ;
        $RW_Count = 0 ;
        $total_theory_abs_count = 0 ;
        $total_int_abs_count = 0 ;
        $ATKT_paper_codes_array = array(); 
        foreach($marks as $new_exam_form)
        {
          // echo "<pre>";
          // print_r($new_exam_form);

            $total_theory_marks_obt += $new_exam_form->theory_marks;
            $total_asmn_marks_obj += $new_exam_form->int_marks;
            $total_theory_asm_marks = $new_exam_form->theory_marks+ $new_exam_form->int_marks;
            $total_marks_obt  += $new_exam_form->theory_marks+ $new_exam_form->int_marks;
            $total_paper_marks += $new_exam_form->max_theory_marks + $new_exam_form->max_internal_marks ;

             if($new_exam_form->type=='theory'){
                    $tot_std_marks += $new_exam_form->theory_marks;
                    $tot_marks += $new_exam_form->max_theory_marks;
                if($new_exam_form->theory_marks>=$new_exam_form->min_theory_marks){
                    $result = "उत्तीर्ण";
                }else if($new_exam_form->theory_marks==''){
                 
                  array_push( $ATKT_paper_codes_array ,$new_exam_form->paper_code );
                  $total_theory_abs_count++ ;
                  $RW_Count++;
                }else{
                    array_push( $ATKT_paper_codes_array ,$new_exam_form->paper_code );
                  
                    $fail_count++;
                    $fali_tot_marks += $new_exam_form->theory_marks;
                    $require_tot_marks += $new_exam_form->min_theory_marks;
                }
        }elseif (($data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N') && $new_exam_form->type!='theory' && $new_exam_form->p_marks!="N" &&  $new_exam_form->p_marks<$new_exam_form->min_theory_marks){
            $fail_count = $fail_count + 5;
        }

         if($new_exam_form->type=='practical'){
            if($new_exam_form->p_marks=='' || $new_exam_form->p_marks=='N'){
                $RW_Count++;
                array_push( $ATKT_paper_codes_array ,$new_exam_form->paper_code );
            }
            if($new_exam_form->p_marks==''){
              $total_int_abs_count++ ;
            }
        }

                
        }


     

        $aggregate_per =   ($tot_std_marks/$tot_marks) * 100;
        $require_grace_marks = $require_tot_marks-$fali_tot_marks;
        if ($fail_count<3 && $require_grace_marks<4 && $aggregate_per>36 ) {
            $check_grace_marks = true;
        }

            foreach($marks as $new_exam_form)
            {
                // final result code 
                if($new_exam_form->paper_type=="theory" )
                {
                    if($new_exam_form->theory_marks<$new_exam_form->min_theory_marks && $check_grace_marks==false ){
                        ++$final_result_fail_count ;
                      
                    }
                }
                else{
                if($new_exam_form->p_marks<$new_exam_form->min_theory_marks)
                    {
                        ++$final_result_fail_count ;
                    }
                }
                //    final result code end

            }


            // echo   $total_theory_abs_count ;
            // echo   $total_theory_int_count ;
            ($total_theory_abs_count==5) ? $remark = "Abs in theory" : "";
            ($total_int_abs_count>0) ? $remark = "Abs in <br> Practical" : "" ;
            ($total_int_abs_count>0 &&  $total_theory_abs_count==5) ? $remark = "Abs in All" : "" ;
            
              
        if($final_result_fail_count>0 && $RW_Count==0)
       {
         $final_result = "Fail" ;
       }
       elseif($RW_Count>0){
        $final_result = "RW" ;
       }else
       {
        $final_result = "Pass" ;
       } 

       $percentage = round(($total_marks_obt/$total_paper_marks)*100,2);
       if($percentage>=60)
       {
          $division = "First";
       }elseif($percentage<60 && $percentage>=40){
         $division  = "Second";
       }else{
         $division = "Third";
       }
?>
<!-- <hr  class="mt-15">
<div class="mt-25"> -->

<?php 
if($page_break_count%2==0 || $page_break_count==0){
  $page_no++ ;
?>
<p align="center"  class="h4" ><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya, Madhya Pradesh</b></p>
<p align="center" class="line-height">Tabulation Register for <strong><?php echo $student->course_name ; echo '&nbsp'. $class_details[0]->class_name; ?></strong>  Examination <?php echo $class_details[0]->exam_session;?>
</p>
<p align="center" class="line-height">Directorate of Distance Education</p>
<div class="row">
    <div class="col-6">
    DATE: <?php echo $class_details[0]->result_date;?>
    </div>
    <div class="col-6 text-right">
   Page : <?php  echo $page_no ; ?>
    </div>
</div>
<input type="hidden" id="bar_code_no" name="bar_code_no" value="<?php echo  $class_details[0]->bar_code_no ; ?>">
<table class="table table1">
  <tbody>
    <tr>
      <th  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspanhead ?>">Roll.No. <br> Reg.No.</th>
      <th  class="align-middle text-center" rowspan="<?php echo $rowspanhead ?>">M.S. <br> No.</th>
      <th  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspanhead ?>">Photo</th>
      <td  class="align-middle text-center  custom_width" rowspan="<?php echo $rowspanhead ?>">Name of the <br> Student and <br> F/H Name</td>
      <td  class="align-middle text-right">Paper-></td>
      <?php
      foreach($marks as $paper_master)
      {
          ?>
      <td  class="align-middle text-center "><?php echo  $paper_master->paper_code ;  ?></td>
      <?php
      }
      ?>
  
      <td  class="align-middle text-center  pl-5 pr-5">Total</td>
      <td  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspanhead ?>" >Marks <br> Obtained</td>
      <td  class="align-middle text-center" rowspan="<?php echo $rowspanhead ?>">Result</td>
      <td  class="align-middle text-center" rowspan="<?php  echo $rowspanhead ?>">Remarks</td>
    </tr>

    <tr>
   
    
      <td class="align-middle text-right " >Theory Marks Max/Min -></td>
      <?php
      foreach($marks as $paper_master)
      {
    
          ?>
      <td  class="align-middle text-center "><?php
       if($paper_master->paper_type=='theory')
       { 
         echo  $paper_master->max_theory_marks .'/'.  $paper_master->min_theory_marks; 
       } ;?></td>
      <?php
      }
      ?>
      <td class=""></td>
     
    
    </tr>
    <tr>
    
   
      <td class="align-middle text-right">Asmn Marks Max/Min -></td>
       <?php
      foreach($marks as $paper_master)
      {
          ?>
      <td  class="align-middle text-center "><?php if($paper_master->paper_type=="theory"){ echo  $paper_master->max_internal_marks .'/'. $paper_master->min_internal_marks ;};  ?></td>
      <?php
      }
      ?>
      <td class="align-middle text-center"></td>
    </tr>
     <?php 
      if( $data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N'){
          ?>
        <tr>
    
   
        <td class="align-middle text-right">Practical Marks Max/Min-></td>
        
        <?php
          foreach($marks as $paper_master)
          {
              ?>
          <td  class="align-middle text-center"><?php if($paper_master->p_marks=="N"){ echo  "" ;}else{echo  $paper_master->max_theory_marks .'/'.$paper_master->min_theory_marks ;};  ?></td>
          <?php
          }
          ?>
        <td class="align-middle text-center"></td>
       
      
      </tr>
      <?php 
      }  
     ?>

  </tbody>
</table>

      <?php
      $center_code = substr($student->center_code, -4);
      echo '<span >'.$center_code. ' </span>' ;
       ?>

<?php
}
?>


 
 

<table class="table table1">
  <tbody>
    <tr>
      <th  class="align-middle text-center " style="width: 85px;" rowspan="<?php echo $rowspandata ?>"><?php  echo $student->roll_no ?> <br> <?php echo $student->enrollment_no  ?></th>
      <th  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspandata ?>"></th>
      <th  class="align-middle text-center pl-4 pr-4" rowspan="<?php echo $rowspandata ?>">Photo</th>
      <td  class="align-middle text-center  pl-5 pr-5 custom_width"  rowspan="<?php  echo $rowspandata ?>"><?php  echo $student->name ?>/ <br><?php  echo $student->f_h_name ?></td>
      <td  class="align-middle text-right" style="width: 187px;">Paper-></td>
      <?php
      foreach($marks as $paper_master)
      {    
          ?>
      <td  class="align-middle text-center"><?php echo  $paper_master->paper_code ;  ?></td>
      <?php
      }
      ?>
  
      <td  class="align-middle text-center  width_total">Total</td>
      <td  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspandata ?>"><?php if($total_theory_abs_count>0){echo "-" ;}else{echo $total_marks_obt .'/'. $total_paper_marks ;}?></td>
      <td  class="align-middle text-center" style="width: 48px;" rowspan="<?php echo $rowspandata ?>"><?php echo $final_result ; ?></td>
      <td  class="align-middle text-center width_total"  rowspan="<?php echo $rowspandata ?>"><?php 
        if($check_grace_marks==false){
          if(($total_theory_abs_count==5)|| ($total_int_abs_count>0 &&  $total_theory_abs_count==5))
          {
           echo $remark ;  
          }else{
            if(sizeof($ATKT_paper_codes_array)>0){
             echo "ATKT in";
            }
           foreach($ATKT_paper_codes_array as $paper_code){
             echo  "<br>". $paper_code ;
           }
          }
        }
     
       ?></td>
    </tr>
    <tr>
   
    
      <td class="align-middle text-right">Theory Marks-></td>
      <?php
      foreach($marks as $new_exam_form)
      {
          ?>
      <td  class="align-middle text-center">
      <?php 
      if($new_exam_form->paper_type=="theory")
      {
        if($new_exam_form->theory_marks==''){
          echo '-';
      }elseif($new_exam_form->theory_marks>=$new_exam_form->min_theory_marks){
          echo $new_exam_form->theory_marks;
      }else{
          echo $new_exam_form->theory_marks;
          echo ($check_grace_marks) ? ' G' : ' F';
      }
      }
      ?>
       </td>
      <?php
      }
      ?>
      <td class="align-middle text-center"><?php if($total_theory_abs_count>0){echo "-" ;}else{echo  $total_theory_marks_obt ;}  ; ?></td>
     
    
    </tr>
    <tr>
    
   
      <td class="align-middle text-right">Asmn Marks-></td>
      <?php
      foreach($marks as $paper_master)
      {
          ?>
      <td  class="align-middle text-center "><?php echo  $paper_master->int_marks;  ?></td>
      <?php
      }
      ?>
      <td class="align-middle text-center"><?php if($total_theory_abs_count>0){echo "-" ;}else{ echo $total_asmn_marks_obj ;};  ?></td>
     
    
    </tr>
    <?php 
      if( $data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N'){
    ?>
    <tr>
    
   
    <td class="align-middle text-right ">Practical Marks.</td>
    <?php
    foreach($marks as $new_exam_form)
    {
        ?>
    <td  class="align-middle text-center"><?php 
    if($new_exam_form->p_marks=="N")
    {echo " ";}
    else{
        if($new_exam_form->p_marks < $new_exam_form->min_theory_marks && $new_exam_form->p_marks!=''){
            echo  $new_exam_form->p_marks .'*' ;
        }elseif($new_exam_form->p_marks ==''){
            echo "RWPR";
        }
        else{
            echo  $new_exam_form->p_marks ;
        }
    }
    ?></td>
    <?php
    }
    ?>
    <td class="align-middle text-center"></td>
   
  
  </tr>

  <?php 
      }
  ?>
    <tr>
    
   
    <td class="align-middle text-right ">Total Marks Obt.</td>
    <?php
    foreach($marks as $paper_master)
    {
        ?>
    <td  class="align-middle text-center pl-5 pr-5"><?php
     if($paper_master->paper_type=="theory"){
       if($check_grace_marks==true){
        echo $paper_master->theory_marks+ $paper_master->int_marks;
       } elseif($paper_master->theory_marks<$paper_master->min_theory_marks){
        echo $paper_master->theory_marks+ $paper_master->int_marks . "F";
       }else{
        echo $paper_master->theory_marks+ $paper_master->int_marks ;
       }
      }else{ 
        echo $paper_master->p_marks;
      }  
      ?></td>
    <?php
    }
    ?>
    <td class="align-middle text-center"><?php if($total_theory_abs_count>0){echo "-" ;}else{ echo $total_marks_obt ;} ?></td>
    </tr>
  <?php 
  if($isFinalClass==false){
  ?>
 
  <?php  
  if( $total_theory_abs_count>0)
  {
    ?>
    <tr>
       <td class="text-center align-middle" colspan="15">
        -
       </td>
    </tr>
  <?php
  }else{
    ?>
        <tr class="">
          <td  class="align-middle text-center " colspan="4"><?php  echo 'Tot:'. $total_marks_obt .'/'. $total_paper_marks ; ?></td>
          <td class="align-middle text-center "  colspan="3"><?php  echo  'Per : '.$percentage .'%' ; ?></td>
          <td class="align-middle text-center "  colspan="3"><?php  echo $final_result ;?></td>
          <td class="align-middle text-center " colspan="5"> <?php  echo $division ;  ?></td>
        </tr>
  <?php
  }

}

  ?>
    <tr class="">
          <td  class="align-middle text-left " colspan="15"><svg class="barcode<?php echo $student->roll_no.$class_details[0]->bar_code_no ;  ?>"></svg></td>
    </tr>
  </tbody>

</table>
<?php  
?>


<?php
}
?>
<?php

?>
</div>
<script>

    

$( document ).ready(function() {
 // Access the array elements
var roll_no = <?php echo json_encode($roll_no); ?>;
       var bar_code_no = document.getElementById("bar_code_no").value ;
// Display the array elements
for(var i = 0; i < roll_no.length; i++){
    bar_code(roll_no[i]);
}
   function bar_code(roll_no)  {
    var classs = ".barcode"+roll_no +bar_code_no ;
    var val = roll_no +bar_code_no ;
     console.log(classs);
    barcodetype = "code128";
    showText = false ;
    
    JsBarcode(classs , val, {
        format: barcodetype,
        lineColor: "#2429e",
        width: 2,
        height: 40,
        displayValue: showText,
        fontSize:20,
        textMargin	:1,
        marginBottom	: 0,
    });
   }
});
  
</script>
     

