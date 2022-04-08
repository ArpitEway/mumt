<style>
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
</style>
<?php 
       

foreach($students as $student)
{


    $data['class_id']= $this->Common_model->getRecordByWhere('class_master',array('id' => $class_id));

	

	
      if( $data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N'){
          $rowspanhead = "4" ;
        
      }else{
        $rowspanhead = "3" ;
      }
      if( $data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N'){
        $rowspandata = "5" ;
      
    }else{
      $rowspandata = "4" ;
    }
	  
        $this->db->select('*');
        $this->db->from('paper_master');
        $this->db->join('new_exam_form', 'paper_master.id = new_exam_form.paper_id');
        $this->db->where('new_exam_form.student_id',$student->student_id);

        $marks = $this->db->get()->result();
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
   
        foreach($marks as $new_exam_form)
        {
        //   echo "<pre>";
        //   print_r($new_exam_form);

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
            }else{
                $result = "अनुत्तीर्ण";
                $fail_count++;
                $fali_tot_marks += $new_exam_form->theory_marks;
                $require_tot_marks += $new_exam_form->min_theory_marks;
            }
        }elseif (($data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N') && $new_exam_form->type!='theory' && $new_exam_form->p_marks!="N" &&  $new_exam_form->p_marks<$new_exam_form->min_theory_marks){
            $fail_count = $fail_count + 5;
        }
        // else if($paper_master[0]->type=='practical'){
        //     $tot_std_marks += $new_exam_form->p_marks;
        //     $tot_marks += $paper_master[0]->pvt_max_marks;
        //     if($new_exam_form->p_marks>=$paper_master[0]->pvt_min_marks){
        //         $result = "उत्तीर्ण";
        //     }else if($new_exam_form->p_marks=='' && $new_exam_form->p_marks=='N'){
        //         $result = 'रुद्धः';
        //         $whCount++;
        //     }else{
        //         $result = "अनुत्तीर्ण";
        //         $fail_count++;
        //         $fali_tot_marks += $new_exam_form->p_marks;
        //         $require_tot_marks += $paper_master[0]->pvt_min_marks;
        //     }
        // }

                
       
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
            //  echo $final_result_fail_count ;
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
?>
<hr  class="mt-15">
<div class="mt-25">


<p align="center" style="line-height:15px;font-size:15px;"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya, Madhya Pradesh</b></p>
<p align="center" style="line-height:5px;">Tabulation Register for <strong><?php echo $head_data; ?></strong>  Examination <?php echo $examyear;?>
</p>
<p align="center" style="line-height:5px;">Directorate of Distance Education</p>
<p align="left" style="line-height:5px;">DATE: <?php echo $trdate;?></p>
<table class="table table1">
  <tbody>
    <tr>
      <th  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspanhead ?>">Roll.No. <br> Reg.No.</th>
      <th  class="align-middle text-center" rowspan="<?php echo $rowspanhead ?>">M.S. <br> No.</th>
      <th  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspanhead ?>">Photo</th>
      <td  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspanhead ?>">Name of the <br> Student and <br> F/H Name</td>
      <td  class="align-middle text-right">Paper-></td>
      <?php
      foreach($marks as $paper_master)
      {
          ?>
      <td  class="align-middle text-center pl-5 pr-5"><?php echo  $paper_master->paper_code ;  ?></td>
      <?php
      }
      ?>
  
      <td  class="align-middle text-center  pl-5 pr-5">Total</td>
      <td  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspanhead ?>" >Marks <br> Obtained</td>
      <td  class="align-middle text-center" rowspan="<?php echo $rowspanhead ?>">Result</td>
      <td  class="align-middle text-center" rowspan="<?php  echo $rowspanhead ?>">Remarks</td>
    </tr>
    <tr>
   
    
      <td class="align-middle text-right pl-5 ">Theory Marks Max/Min -></td>
      <?php
      foreach($marks as $paper_master)
      {
    
          ?>
      <td  class="align-middle text-center pl-5 pr-5"><?php if($paper_master->paper_type=='theory'){ echo  $paper_master->max_theory_marks .'/'.  $paper_master->min_theory_marks; } ;?></td>
      <?php
      }
      ?>
      <td class="align-middle text-center"></td>
     
    
    </tr>
    <tr>
    
   
      <td class="align-middle text-right">Asmn Marks Max/Min -></td>
       <?php
      foreach($marks as $paper_master)
      {
          ?>
      <td  class="align-middle text-center pl-5 pr-5"><?php if($paper_master->paper_type=="theory"){ echo  $paper_master->max_internal_marks .'/'. $paper_master->min_internal_marks ;};  ?></td>
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
          <td  class="align-middle text-center pl-5 pr-5"><?php if($paper_master->p_marks=="N"){ echo  "" ;}else{echo  $paper_master->max_theory_marks .'/'.$paper_master->min_theory_marks ;};  ?></td>
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
<table class="table table1">
  <tbody>
    <tr>
      <th  class="align-middle text-center " rowspan="<?php echo $rowspandata ?>"><?php  echo $student->roll_no ?> <br> <?php echo $student->enrollment_no  ?></th>
      <th  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspandata ?>"></th>
      <th  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspandata ?>">Photo</th>
      <td  class="align-middle text-center " rowspan="<?php  echo $rowspandata ?>"><?php  echo $student->name ?>/ <br><?php  echo $student->f_h_name ?></td>
      <td  class="align-middle text-right">Paper-></td>
      <?php
      foreach($marks as $paper_master)
      {    
          ?>
      <td  class="align-middle text-center pl-5 pr-5"><?php echo  $paper_master->paper_code ;  ?></td>
      <?php
      }
      ?>
  
      <td  class="align-middle text-center  pl-5 pr-5">Total</td>
      <td  class="align-middle text-center pl-5 pr-5" rowspan="<?php echo $rowspandata ?>"><?php   echo $total_marks_obt .'/'. $total_paper_marks ;  ?></td>
      <td  class="align-middle text-center" rowspan="<?php echo $rowspandata ?>"><?php if($final_result_fail_count>0){echo "Fail";}else{echo "Pass" ;} ?></td>
      <td  class="align-middle text-center" rowspan="<?php echo $rowspandata ?>">Remarks</td>
    </tr>
    <tr>
   
    
      <td class="align-middle text-right" style="width:190px">Theory Marks-></td>
      <?php
      foreach($marks as $new_exam_form)
      {
          ?>
      <td  class="align-middle text-center pl-5 pr-5">
      <?php if($new_exam_form->theory_marks==''){
                        echo '-';
                    }elseif($new_exam_form->theory_marks>=$new_exam_form->min_theory_marks){
                        echo $new_exam_form->theory_marks;
                    }else{
                        echo $new_exam_form->theory_marks;
                        echo ($check_grace_marks) ? ' G' : ' F';
                    }
                     ?>
       </td>
      <?php
      }
      ?>
      <td class="align-middle text-center"><?php  echo  $total_theory_marks_obt ; ?></td>
     
    
    </tr>
    <tr>
    
   
      <td class="align-middle text-right pl-10 pr-10">Asmn Marks-></td>
      <?php
      foreach($marks as $paper_master)
      {
          ?>
      <td  class="align-middle text-center pl-5 pr-5"><?php echo  $paper_master->int_marks;  ?></td>
      <?php
      }
      ?>
      <td class="align-middle text-center"><?php echo $total_asmn_marks_obj ;  ?></td>
     
    
    </tr>
    <?php 
      if( $data['class_id'][0]->project!='N' || $data['class_id'][0]->practical!='N'){
    ?>
    <tr>
    
   
    <td class="align-middle text-right pl-10 pr-10">Practical Marks.</td>
    <?php
    foreach($marks as $new_exam_form)
    {
        ?>
    <td  class="align-middle text-center pl-5 pr-5"><?php 
    if($new_exam_form->p_marks=="N")
    {echo " ";}
    else{
        if($new_exam_form->p_marks < $new_exam_form->min_theory_marks){
            echo  $new_exam_form->p_marks .'*' ;
        }else{
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
    
   
    <td class="align-middle text-right pl-10 pr-10">Total Marks Obt.</td>
    <?php
    foreach($marks as $paper_master)
    {
        ?>
    <td  class="align-middle text-center pl-5 pr-5"><?php if($paper_master->paper_type=="theory"){ echo $paper_master->theory_marks+ $paper_master->int_marks;}else{ echo $paper_master->p_marks;}  ?></td>
    <?php
    }
    ?>
    <td class="align-middle text-center"><?php  echo $total_marks_obt ; ?></td>
   
  
  </tr>
  </tbody>
</table>

<?php
}
?>
</div>