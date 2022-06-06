<?php 
  $withheld = false;
  $check_grace_marks = false;
  $fail_count = 0;
  $fali_tot_marks = 0;
  $require_tot_marks = 0;
  $tot_marks = 0;
  foreach($new_exam_form as $marks){
    $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code,"class_id"=>$marks->class_id));

      if($marks->paper_type=='theory'){
              $tot_marks +=  $paper_master[0]->max_theory_marks;
          if($marks->theory_marks>=$paper_master[0]->min_theory_marks){
              $result = "उत्तीर्ण";
          }elseif($marks->theory_marks==''){
            $withheld = true;
          }else{
              $result = "अनुत्तीर्ण";
              $fail_count++;
              $fali_tot_marks += $marks->theory_marks;
              $require_tot_marks +=$paper_master[0]->min_theory_marks;
          }
          if ($marks->int_marks=='') {
               $withheld = true;
          }
      }else if($marks->paper_type=='practical'){
          $tot_std_marks += $marks->p_marks;
          $tot_marks += $paper_master[0]->max_theory_marks;
          if($marks->p_marks>=$paper_master[0]->min_theory_marks){
              $result = "उत्तीर्ण";
          }elseif($marks->p_marks==''){
             $withheld = true;
          }else{
              $result = "अनुत्तीर्ण";
              $fail_count++;
              $fali_tot_marks += $marks->p_marks;
              $require_tot_marks += $paper_master[0]->min_theory_marks;
          }
      }
  }

  $require_grace_marks = $require_tot_marks-$fali_tot_marks;
  if ($fail_count<3 && $require_grace_marks<4) {
      $check_grace_marks = true;
  }

 
 ?>
<style>
    table, th, td {
      border: 1px solid black;
      padding: 5px;
      font-size: 18px;
  }
.table-bordered td{
  border:0;
}
{
    height: 180px;
    width: auto;
    object-fit: cover;
}

.text-primary{
    color: #16447e !important;
}

.table thead th, .table thead td {
    font-size: 18px;
    vertical-align: middle;
    border: 1px solid #000;
}
</style>
<style>
    @media print {
       .breakhere { page-break-before:always;  };
   }
   th.border.border-dark {
       vertical-align: middle;
   }
</style>

<script type="text/javascript"> 
//below javascript is used for Disabling right-click on HTML page
// document.oncontextmenu=new Function("return false");//Disabling right-click


// //below javascript is used for Disabling text selection in web page
// document.onselectstart=new Function ("return false"); //Disabling text selection in web page
// if (window.sidebar){
//     document.onmousedown=new Function("return false"); 
//     document.onclick=new Function("return true") ; 


// //Disable Cut into HTML form using Javascript 
// document.oncut=new Function("return false"); 


// //Disable Copy into HTML form using Javascript 
// document.oncopy=new Function("return false"); 


// //Disable Paste into HTML form using Javascript  
// document.onpaste=new Function("return false"); 
// }
</script>
<div id="printarea" style="width:1000px; margin:auto">
 <div class="border border-dark border-bottom-0">
   <div class="text-center py-3">
      <img src="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png"  width="100px;" />
      <img src="<?=base_url()?>assets/images/maskgroup/Group1.png" class="img2" alt="">
      <h4 class="text-primary text-center mb-0">परीक्षा सत्र - जनवरी 2022</h4>
  </div>
</div>
<table class="table mb-0">
    <tbody>
        <tr>
            <th class="border-top-0 text-primary pl-3">Enrollment No.</th>
            <th class="border-top-0"><?php  echo $student->enrollment_no ?></th>
            <th class="border-top-0 text-primary pl-3">Roll No.</th>
            <th class="border-top-0"><?php echo  $student->roll_no; ?></th>
            <th rowspan="4" class="border-top-0 text-center" width="170px"><img class="img img-thumbnail" src="<?=base_url('assets/student_image/').$student->photo?>" ></th>
        </tr>
        <tr>
            <th class="border-top-0 text-primary pl-3">Name</th>
            <th class="border-top-0"><?php  echo $student->name ?></th>
            <th class="border-top-0 text-primary pl-3">F/H Name</th>
            <th class="border-top-0"><?php  echo $student->f_h_name ?></th>
        </tr>
        <tr>
            <th class="border-top-0 text-primary pl-3">Course</th>
            <th class="border-top-0"><?php  echo $student->course_name ?></th>
            <th class="border-top-0 text-primary pl-3">Class</th>
            <th class="border-top-0"><?php  echo $student->class_name; ?></th>
        </tr>
        <tr>
            <th class="border-top-0 text-primary pl-3">College</th>
            <th class="border-top-0" colspan="3"><?php  echo $this->Common_model->getCenterNameById($student->center_id); ?></th>
        </tr>
    </tbody>
</table>
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
 <thead >
  <tr class=" text-center" >
      <th class="border-dark text-center" rowspan="2">Subject</th>
      <th class="border-dark text-center" colspan="2">Examination Marks</th>
      <th class="border-dark text-center" colspan="2">Internal Marks</th>
      <th class="border-dark text-center" colspan="2">Total</th>
      <th class="border-dark text-center" rowspan="2">Result</th>
    </tr>
    <tr>
      <th class="border-dark text-center" scope="row">Max Marks</th>
      <th class="border-dark text-center" scope="row">Obtainded</th>
      <th class="border-dark text-center" scope="row">Max Marks</th>
      <th class="border-dark text-center" scope="row">Obtainded</th>
      <th class="border-dark text-center" scope="row">Max Marks</th>
      <th class="border-dark text-center" scope="row">Obtainded</th>
    </tr>
    </thead>
     <tbody>
    <?php 
    $total_paper_marks = 0;
    $total_student_marks = 0 ;
    $result = "";
    $fail_count = 0;
    $total_max_marks = 0 ;
    $total_obtained_marks = 0;
    $fail_count=0;
    
        foreach($new_exam_form as  $marks){
           

            $paper_master = $this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$marks->paper_code));
        
            if($marks->paper_type=="theory"){
                $mx_marks=  $paper_master[0]->max_theory_marks + $paper_master[0]->max_internal_marks;
            }else{
              $mx_marks=$paper_master[0]->max_theory_marks;
            }
            $total_max_marks = $total_max_marks +$mx_marks;
            if($marks->paper_type=="theory")
            {
             $obtain_marks= $marks->theory_marks + $marks->int_marks;
            }else{
             $obtain_marks= $marks->p_marks;
            }
            $total_obtained_marks = $total_obtained_marks +$obtain_marks;
      
           // final result code 
           if($marks->paper_type=="theory" )
           {
                if(($marks->theory_marks<$paper_master[0]->min_theory_marks || $marks->int_marks<$paper_master[0]->min_internal_marks) && $check_grace_marks==false ){
                  ++$fail_count ;
              }
           }else{
             if($marks->p_marks<$paper_master[0]->min_theory_marks)
               {
                ++$fail_count ;
               }
           }

           // final result code end
             if($marks->paper_type=="theory"){
              if($marks->theory_marks<$paper_master[0]->min_theory_marks || $marks->int_marks<$paper_master[0]->min_internal_marks){
               $res =  ($check_grace_marks) ? "Pass" : "ATKT";
              }else{
                $res = "Pass";
              } 
             }else{
            
               if($marks->p_marks<$paper_master[0]->min_theory_marks){
               $res = "ATKT";
               }else{
               $res = "Pass";
               }
             }
          ?>
           <tr>
            <th><?php echo $this->Common_model->getPaperNameById($marks->paper_id); ?></th>
            <th class="text-center"><?php  echo $paper_master[0]->max_theory_marks; ?></th>
            <th class="text-center"><?php  
            if( $marks->paper_type =='Practical'){
              if($marks->p_marks < $paper_master[0]->min_theory_marks)
              {
                echo $marks->p_marks;?><span style="color:red">*</span> <?php
              }else{
                echo $marks->p_marks;
              }
              }else
              {
                if($marks->theory_marks<$paper_master[0]->min_theory_marks)
                {
                  echo $marks->theory_marks;echo ($check_grace_marks) ? ' G' : '<span style="color:red">*</span>';?> <?php
                }else{
                  echo $marks->theory_marks;
                  }
              };  
              ?></th>
            <th class="text-center"><?php  if( $marks->type =='Practical'){echo '-';}else{echo $paper_master[0]->max_internal_marks; }; ?></th>
            <th class="text-center"><?php
              if( $marks->paper_type =='Practical')
              {
              {echo '-';}}
              else{
                if($marks->int_marks<$paper_master[0]->min_internal_marks)
                {
                  echo $marks->int_marks;?><span style="color:red">*</span> <?php
                }else{
                  echo $marks->int_marks;
                  }
                };  ?>
                </th>
            <th class="text-center"><?php  if($marks->paper_type=="Practical"){echo $paper_master[0]->max_theory_marks;}else{echo $paper_master[0]->max_theory_marks +$paper_master[0]->max_internal_marks;} ; ?></th>

            <th class="text-center"><?php if($marks->paper_type=="Practical"){echo $marks->p_marks;}else{ echo $marks->theory_marks + $marks->int_marks;} ?></th>
            <th><?php echo $res ;?></th>
          </tr>
          <?php 
        }

        ?>
             <tr>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="text-center"><?php echo  $total_max_marks?></th>
            <th class="text-center"><?php  echo  $total_obtained_marks ?></th>
            <th></th>
          </tr>
  </tbody>
</table>
<div class="row mb-5">
    <div class="col-12 text-center">
    <button type="button" style="background-color:#fdf8d2; opacity: 1;" width="100%" class="btn   btn-block" disabled><h6 style="color:#000000; font-weight: bold;">Result</h6></button>
     <button type="button" style="opacity: 1"  width="100%" class="btn btn-light  btn-block text-dark" disabled><h6 style=" opacity: 1;color:   000000;font-weight: bold;"><?php if($fail_count>0){echo "ATKT";}else{echo "Pass";}  ?></h6></button>
    </div>
</div>

<div class="row" style=" margin-top: -13px;font-size: 18px;">
    <div class="col-12">
     <span style="font-weight : 900"> Disclaimer :</span>
     <span style="font-weight : bold">This information should not be treated as Marksheet.<br> 
      MPSVV, Ujjain is not responsible for any inadvertent error that may have crept in the results being published on INTERNET. The results published on internet are for immediate information to the examinee
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
<?php } ?>