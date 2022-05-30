
<style>
table, th, td {
  border: 1px solid black;
  padding: 5px;
}
/* table {
  border-spacing: 15px;
} */
.table-bordered td{
  border:0;
}

</style>

<style>
table, th, td {
  border: 1px solid black;
  padding: -10px;
  font-size: 18px;
}
/* table {
  border-spacing: 15px;
} */
.table-bordered td{
  border:0;
}
.table td  {
  padding :2px ;
}
.table th  {
  padding :2px ;
}
{
    height: 180px;
    width: auto;
    object-fit: cover;
}
@media print {
	.breakhere { page-break-before:always;  };
    }
    th.border.border-dark {
    	vertical-align: middle;
    }
</style>
      <div class="form-group col-md-12 text-center mt-3"  id="print_btn">
        <button  type="button" onclick="printDiv('printarea')"  class="btn btn-primary font-weight-bold mr-2" >Print</button>
      </div>
 <div class="container" id="printarea" style=" margin-top: 6rem!important;">
              <?php 
              foreach($students  as $student)
              {
                ?>
          

                <div class="breakhere" style="">
                          <div class="row " >
                                <div class="col-12" style="color:white ; text-align:center">
                                    <?php echo   $this->Common_model->getCenterNameById($student->center_id)  ?>
                                </div>
                          </div>
                          <div class="row">
                                      <div class="col-1 text-right">
                                        <img src="<?=base_url()?>assets/images/maskgroup/MaskGroup1.png"  width="100px;" />
                                      </div>
                                      <div class="col-11">
                                              <h3 class="text-center"  style=" font-weight: 500 !important;">महर्षि    पाणिनि    संस्कृत    एवं    वैदिक    विश्वविद्यालय,   उज्जैन  (म .प्र.)</h3>
                                              <h3 class="text-center" style=" font-weight: 400 !important;">MAHARSHI PANINI SANSKRIT EWAM VEDIC VISHWAVIDHYALAYA , UJJAIN (M.P.) </h3>
                                              <p class="text-center" style="margin-bottom: 0rem;">(मध्यप्रदेश साशन द्वारा स्थापित:)</p>
                                              <p class="text-center ; " style="margin-bottom: 0rem;">(ESTABLISHED BY GOVT. OF MADHYA PRADESH) </p>
                                      </div>
                          </div>
                          <div class="row mt-6">
                              <div class="col-1 text-right">  </div>
                                <div class="col-11">
                                        <p class="text-center" style="margin-bottom: 0rem; color : white"><?php echo $student->name  ?></p>
                                        <h4 class="text-center" style=" font-weight: 400 !important;">अंकपत्रम  MARKSHEET</h4>
                                        <p class="text-center font-weight-bold" style="color:black ;margin-bottom: 0.5rem;    font-weight: 600 !important;"><?php echo $student->course_name; echo "&nbsp;&nbsp;" ;echo  $student->class_name  ?></p>
                                       
                                </div>
                          </div>
                </div>

                <div class="row">
                  <?php  $year = substr($student->session, -2);  ?>
                    <div class="col-12  ">
                          <p class="text-left">S. No. : <span class="font-weight-bold"><?php echo  $year.'/'.$student->student_id  ?></span></p>
                    </div>
                </div>
        <table border="0" class="table">
          <tbody>
            <tr>
              <td  style='border:none; padding: 5px;'>छात्रस्य नाम</td>
              <th style='border:none; padding: 5px;' scope="row"><?php  echo $student->name_hindi;  ?></th>
              <td style='border:none; padding: 5px;'>अनुक्रमांक:</td>
              <td style='border:none; padding: 5px;'> </td>
            </tr>
            <tr>
              <td style='border:none; padding: 5px;' >Student's Name</td>
              <th style='border:none; padding: 5px;'><?php  echo  strtoupper($student->name); ?></th>
              <td style='border:none; padding: 5px;'>Roll No.</td>
              <th style='border:none; padding: 5px;'><?php  echo $student->roll_no;  ?> </th>
            </tr>
            
            <tr>
              <td style='border:none; padding: 5px;'>पितुर्नाम </td>
              <th style='border:none; padding: 5px;'><?php  echo  $student->f_h_name_hindi; ?></th>
              <td style='border:none; padding: 5px;'>नामांकन संख्या</td>
              <td style='border:none; padding: 5px;'></td>
            </tr>

            <tr>
              <td style='border:none; padding: 5px;'>Father's Name </td>
              <th style='border:none; padding: 5px;'><?php  echo  strtoupper($student->f_h_name); ?></th>
              <td style='border:none; padding: 5px;'>Enrollment No.</td>
              <th style='border:none; padding: 5px;'><?php  echo  $student->enrollment_no; ?></th>
            </tr>
            <tr>
              <td style='border:none; padding: 5px;'>मातुर्नाम</td>
              <th style='border:none; padding: 5px;'><?php  echo  $student->mother_name_hindi; ?></th>
              <td style='border:none; padding: 5px;'>नियमित:/स्वाधयायी </td>
              <td style='border:none; padding: 5px;'><?php echo ($student->mode=='regular') ? 'नियमितः' : 'स्वाध्यायी' ?></td>
            </tr>

            <tr>
              <td style='border:none; padding: 5px;'>Mother's Name</td>
              <th style='border:none; padding: 5px;'><?php  echo  strtoupper($student->mother_name); ?></th>
              <td style='border:none; padding: 5px;'>Regular/Private</td>
              <th style='border:none; padding: 5px;'><?php  echo  $student->mode; ?></th>
            </tr>
          </tbody>
        </table>
                <div class="container-fluid " style="margin-top: -16px; padding: 14px;    border-left: 1px solid;border-right: 1px solid;">
                      <div class="row " >
                            <div class="col-3">
                              संस्थानाम 
                            </div>
                            <div class="col-9 font-weight-bold">
                                <?php echo   $this->Common_model->getCenterNameById($student->center_id)  ?>
                            </div>
                      </div>
                      <div class="row mt-2">
                          <div class="col-3">
                                Center Name
                          </div>
                          <div class="col-9 font-weight-bold">
                              <?php echo  $this->Common_model->getCenterNameById($student->center_id)  ?>
                          </div>
                      </div>
                </div>
        <table class="table">
          
            <tbody>
            
              <tr>
                <td rowspan="3" style="    border-bottom: 1px solid; border-left: 1px solid; border-top: 1px solid; text-align: center"> प्र. &nbsp; प्र. <br>   संकेतांक <br> Paper <br>Code</td>
                <td rowspan="3" style="   border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; vertical-align: middle;"><span class="ml-3">विषय:</span> <br> <span class="ml-3">SUBJECT</span></td>
                <td colspan="4" style="    border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; text-align: center">अंकयोजना &nbsp; Scheme</td>
                <td rowspan="2"  colspan="2" style="    border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; text-align: center ;vertical-align: middle"> प्राप्तांक <br> Marks <br> Obtained</td>
                <td rowspan="3" style="    border-bottom: 1px solid;border-left: 1px solid;border-top: 1px solid; text-align: center ;vertical-align: middle"> विषययोग: <br> Subject <br> Total</td>
                <td rowspan="3" style="    border-bottom: 1px solid;border-right: 1px solid;border-top: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">टिप्पणी  <br> Remarks</td>
              </tr>
              <tr>
                <td colspan="2" style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center">पूर्णांक <br> Max Marks</td>
                <td colspan="2" style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center">उत्तीर्णांक <br> Pass Marks</td>
              </tr>
              <tr>
                <td style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">सैद्धा. <br> FE</td>
                <td style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">आ. मू. <br>IA</td>
                <td style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">सैद्धा. <br>FE</td>
                <td style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">आ. मू. <br>IA</td>
                <td style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">सैद्धा. <br>FE</td>
                <td style="    border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">आ. मू. <br>IA</td>
              </tr>

              <?php 
                        $this->db->select('*');
                        $this->db->from('new_exam_form');
                        $this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
                        $this->db->where('new_exam_form.student_id',$student->student_id); 
                        $paper_marks = $this->db->get()->result();
                        $check_grace_marks = false;
                        $fail_count = 0;
                        $fali_tot_marks = 0;
                        $require_tot_marks = 0;
                        $tot_marks = 0;
                        foreach($paper_marks as $marks){
                            if($marks->type=='theory'){
                                    $tot_marks += $marks->max_theory_marks;
                                if($marks->theory_marks>=$marks->min_theory_marks){
                                    $result = "उत्तीर्ण";
                                }else{
                                    $result = "अनुत्तीर्ण";
                                    $fail_count++;
                                    $fali_tot_marks += $marks->theory_marks;
                                    $require_tot_marks += $marks->min_theory_marks;
                                }
                            }else if($marks->type=='practical'){
                                $tot_std_marks += $marks->p_marks;
                                $tot_marks += $marks->max_theory_marks;
                                if($marks->p_marks>=$marks->min_theory_marks){
                                    $result = "उत्तीर्ण";
                                }else{
                                    $result = "अनुत्तीर्ण";
                                    $fail_count++;
                                    $fali_tot_marks += $marks->p_marks;
                                    $require_tot_marks += $marks->min_theory_marks;
                                }
                            }
                        }
                        // echo 'tot_marks'.$tot_marks;
                        // echo 'tot_std_marks ='.$tot_std_marks;
                        
                      
                        $aggregate_per =   ($tot_std_marks/$tot_marks) * 100;     
                        $require_grace_marks = $require_tot_marks-$fali_tot_marks;
                      
                        if ($fail_count<3 && $require_grace_marks<4  && $aggregate_per) {
                            $check_grace_marks = true;
                        }
                


                        $total_paper_marks = 0;
                        $total_student_marks = 0 ;
                        $result = "";
                        $fail_count = 0;


                $this->db->select('*');
                $this->db->from('new_exam_form');
                $this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
                $this->db->where('new_exam_form.student_id',$student->student_id); 
                $paper_marks = $this->db->get()->result();
                // echo "<pre>";
                // print_r($paper_marks);
                $total_max_theory_marks = 0 ;
               $total_max_internal_marks = 0 ;
               $total_min_theory_marks= 0 ;
               $total_min_internal_marks = 0 ;
                $total_theory_marks = 0 ;
                $total_int_marks= 0 ;
                $total_get_marks = 0 ;
                foreach($paper_marks as  $marks)
                {
                  $total_max_theory_marks = $total_max_theory_marks +$marks->max_theory_marks;
                 $total_max_internal_marks =$total_max_internal_marks +$marks->max_internal_marks;
                 $total_min_theory_marks=$total_min_theory_marks+$marks->min_theory_marks;
                 $total_min_internal_marks =$total_min_internal_marks +$marks->min_internal_marks;
                  $total_theory_marks = $total_theory_marks +$marks->theory_marks;
                  $total_int_marks = $total_int_marks +$marks->int_marks;
                  $total_get_marks = $total_get_marks +$marks->theory_marks + $marks->int_marks ;




                      // final result code 
                      if($marks->type=="theory" )
                      {
                            if(($marks->theory_marks<$marks->min_theory_marks || $marks->int_marks<$marks->min_internal_marks) && $check_grace_marks==false ){
                              ++$fail_count ;
                          }
                      }else{
                        if($marks->p_marks<$marks->min_theory_marks)
                          {
                            ++$fail_count ;
                          }
                      }
            
            
                      // final result code end


                      if($marks->type=="theory"){
                        if($marks->theory_marks<$marks->min_theory_marks || $marks->int_marks<$marks->min_internal_marks){
                        ($check_grace_marks) ? $res = "Pass" :   $res = "Fail";
          
                      
                        }else{
                          $res = "Pass";
                        } 
                      }else{
                      
                        if($marks->p_marks<$marks->min_theory_marks){
                        $res = "Fail";
                        }else{
                        $res = "Pass";
                        }
                      }
                  ?>
                  <tr>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php echo $marks->paper_code ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle; padding-left: 8px;"><?php echo $marks->paper_name ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php  echo $marks->max_theory_marks ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php  echo "-" ; ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php  echo $marks->min_theory_marks ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php echo "-" ; ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php  
                    
                    if( $marks->type =='practical'){
                      if($marks->p_marks < $marks->min_theory_marks)
                      {
                        echo $marks->p_marks;?><span style="">*</span> <?php
                      }else{
                        echo $marks->p_marks;
                      }
                      }else
                      {
                        if($marks->theory_marks<$marks->min_theory_marks)
                        {
                          echo $marks->theory_marks;echo ($check_grace_marks) ? ' G' : '<span style="">*</span>';?> <?php
                        }else{
                          echo $marks->theory_marks;
                          }
                      };  
                    ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php  echo "-" ?></td>
                    <td style="border-left: 1px solid; vertical-align: middle;text-align: center"><?php  echo $marks->theory_marks + $marks->int_marks ?></td>
                    <td style="border-right: 1px solid;border-left: 1px solid; vertical-align: middle;text-align: center"></td>
                  </tr>
                  <?php 
                }
                ?>
              <tr >
                <td style=" border-top: 1px solid;   border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"></td>
                <td style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle">योग: &nbsp; Total</td>
                <th style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"><?php  echo $total_max_theory_marks ?></th>
                <td style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"><?php  echo "-" ?></td>
                <th style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"><?php  echo$total_min_theory_marks?></th>
                <td style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"><?php  echo "-" ?></td>
                <th style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"><?php  echo $total_theory_marks ?></th>
                <td style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"><?php  echo "-" ?></td>
                <th style="  border-top: 1px solid;  border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"><?php  echo $total_get_marks ?></th>
                <td style="border-top: 1px solid; border-right: 1px solid;   border-bottom: 1px solid;border-left: 1px solid; text-align: center ;vertical-align: middle"></td>
              </tr>
            </tbody>
          </table>
        <table class="table">

          <tbody>
          <tr style="border-top: none;">
              <td style="border-top: none;text-align:center;" scope="">वर्ष/सेमेस्टर<br> Year/Semester</td>
              <td style="border-top: none;text-align:center;vertical-align: middle" scope="col">I</td>
              <td style="border-top: none;text-align:center;vertical-align: middle" scope="col">II</td>
              <td style="border-top: none;text-align:center;vertical-align: middle" scope="col">III</td>
              <td style="border-top: none;text-align:center;vertical-align: middle" scope="col">IV</td>
              <td  style="border-top: none;text-align:center;vertical-align: middle" scope="col">V</td>
              <td  style="border-top: none;text-align:center;vertical-align: middle"scope="col">VI</td>   
              <td style="border-top: none; text-align:center;vertical-align: middle" scope="col">महायोग: <br>Grand Total</td>
              <td style="border-top: none; text-align:center;vertical-align: middle" scope="col">परिणाम: <br>Result</td>
              <td style="border-top: none;text-align:center;vertical-align: middle" scope="col">श्रेणी <br>Division</td>
            </tr>
            <tr>
              <td scope="row" style="text-align:center;">प्राप्तांक:<br>Obtained Marks</td>   
              <th style="text-align:center;vertical-align: middle"><?php  echo $total_get_marks ?></th>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <th style="text-align:center;vertical-align: middle" scope="col"><?php  echo $total_get_marks ?></th>
              <th style="text-align:center;vertical-align: middle" scope="col"><?php if($fail_count>0){echo "Fail";}else{echo "Pass";}  ?></th>
              <th style="text-align:center;vertical-align: middle" scope="col">-</th>

            </tr>
            <tr>
              
              <td scope="row" style="text-align:center;">पूर्णांक:<br>Max. Marks</td>
              <th style="text-align:center;vertical-align: middle"><?php  echo $total_max_theory_marks +$total_max_internal_marks; ?></th>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <td style="text-align:center;vertical-align: middle">-</td>
              <th  style="text-align:center;vertical-align: middle" scope="col"><?php  echo $total_max_theory_marks +$total_max_internal_marks; ?></th>
              <th scope="col"></th>
              <th scope="col"></th>
            </tr>
          
          </tbody>
        </table>
                    <div class="row  mt-20 mb-15" style="margin-top: 70px; margin-bottom:30px">
                            <div class="col-6 ">
                                <div class="text-left  ">Date of Result</div>
                            </div>
                            <div class="col-6 ">
                                <div class="text-right  font-weight-bold">कुलसचिव:/Registrar</div>
                            </div>
                    </div>

        <hr style="margin-bottom:20px">
          <?php
        }

        ?>
</div>
<script type="text/javascript">
	function printDiv(divName) {
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
