
<style>
    .table1{
       margin:auto ;
  font-family: "Times New Roman"

    }
    .table2{
  font-family: "Times New Roman";
   
    }
   
   .child td {
   padding-top: 0; 
   padding-bottom: 0; 
   margin-top: 0;
   margin-bottom: 0;
}
.marks_head {
 font-family: Arial;
 font-size: 12px;
 width: 238px;
}
.table td {
    vertical-align: middle;
}
.paper_code{
    width: 150px;
}
.paper_name{
    width: 430px;
}
.borderless td, .borderless th {
    border: none;
}
.whole_width{
    width: 95%;
    margin:auto;
}
.font-weight {
  font-weight: 490;
  font-family: "Times New Roman"
}
.head-margin{
  margin-top : 100px ;
}
.whole-margin{
  margin-left:76px;
  font-family: "Times New Roman"
}
p {
    margin-bottom: 0.9rem;
    color:black ;
    height : 12px
  }
  .last_div {
    width:90% ;
    margin:auto ;
  font-family: "Times New Roman";

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
<?php 
foreach($students as $student)
{

  $this->db->select("*");
  $this->db->from('paper_master');
  $this->db->join("new_exam_form",'paper_master.id = new_exam_form.paper_id');
  $this->db->where('new_exam_form.student_id',$student->student_id);
  $papers = $this->db->get()->result();


?>
<div class="" id="printarea" style=" margin-top: 6rem!important; width:1150px ; margin:auto">

<div class="whole_width">
<div class=" font-weight whole-margin mt-3">
    <div class="row">
        <div class="col-12 text-left" >
          <p><?php  echo $student->marksheet_no; ?></p>
        </div>
    </div>
    <div class="row head-margin">
        <div class="col-12 text-center" >
         <h6 class='font-weight:500'><strong>Bachelor of Social Work First Year Examination June 2021</strong></h6>
        </div>
    </div>
    <div class="row mt-3">
       <div class="col-3">
          <p>Roll No</p>
          <p>Enrolment / Registration No.</p>
          <p>Name of the Candidate</p>
          <p>Father's / Husband's Name</p>
       </div>
       <div class="col-3">
          <p><?php echo $student->roll_no ; ?></p>
          <p><?php echo $student->enrollment_no  ?></p>
          <p><?php echo $student->name; ?></p>
          <p><?php echo $student->f_h_name ; ?></p>
       </div>
       <div class="col-3">
          <p class='text-right'> Mode - <?php echo $student->university_mode ;  ?>	</p>
          <p></p>
          <p></p>
          <p></p>
       </div>
       <div class="col-3">
        <img src="" alt="" width="90px" height="105px" border="1">
       </div>
    </div>
</div>

        <table class="table vertical table1 borderless" style="width:100%">
        <tr>
            <td class="text-center  vertical paper_code" ><strong>Paper Code</strong></td>
            <td class="text-left  vertical paper_name"  ><strong>Paper name </strong></td>
            <td class="text-center " >
           
                
                        <table class="child marks_head borderless table1">
                            <tr>
                                <td class="text-center" colspan=4><strong><u>Examination Scheme</u></strong></td>
                                <td class="text-center" colspan=4><strong><u>Obtained Marks</u></strong></td>
                            
                            </tr>
                            <tr>
                                <td class="text-center" colspan=2><strong><u>Th/Pr/Pj</u> </strong></td> 
                                <td class="text-center" colspan=2> <strong><u>Assignment</u></strong></td>
                                <td class="text-center" colspan=2><strong><u>Th/Pr/Pj</u> </strong></td> 
                                <td class="text-center" colspan=2> <strong><u>Assignment</u></strong></td>
                            </tr>
                            <tr>
                                <td class="text-center "><strong><u>Max</u></strong>  </td>
                                <td class="text-center "><strong><u>Min</u></strong>  </td>
                                <td class="text-center "><strong><u>Max</u></strong>  </td>
                                <td class="text-center "><strong><u>Min</u></strong>  </td>
                            </tr>
                        </table>
               
             
                        <!-- <table class="child marks_head borderless table1">
                            <tr>
                                <td class="text-center" colspan=4><strong><u>Obtained Marks</u></strong></td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan=2><strong><u>Th/Pr/Pj</u> </strong></td> 
                                <td class="text-center" colspan=2> <strong><u>Assignment</u></strong></td>
                                <td class="text-center" ><strong><u>Total</u></strong></td>
                            </tr>
                        </table> -->
              
          
            </td>
            
        </tr>
 
        </table>
</div>
<div class="whole_width">


<table class="text-center table2">
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


foreach($papers as $paper)
{
  ?>
    <tr>
         <td class="text-center  vertical paper_code" ><strong><?php echo $paper->paper_code ;  ?></strong></td>
         <td class="text-left  vertical paper_name"  ><strong><?php echo $paper->paper_name ; ?></strong></td>
       
         <td class="text-center  vertical " style="width: 60px; "><strong><?php echo $paper->max_theory_marks ;  ?></strong></td>
         <td class="text-center  vertical "   style="width: 60px; "><strong><?php echo $paper->min_theory_marks ; ?></strong></td>
         <td class="text-center  vertical "  style="width: 60px; "> <strong><?php  echo $paper->max_internal_marks ; ?></strong></td>
         <td class="text-center  vertical "  style="width: 60px; "><strong><?php echo $paper->min_internal_marks ?></strong></td>
         <td class="text-center  vertical "  style="width: 60px; "><strong><?php  echo $paper->theory_marks ;  ?></strong></td>
         <td class="text-center  vertical "  style="width: 60px; " ><strong><?php  echo $paper->int_marks ; ?></strong></td>
         <td class="text-center  vertical "  style="width: 60px; " ><strong><?php  echo $paper->theory_marks + $paper->int_marks ;  ?></strong></td>
          
    </tr>
    <?php
}
?>
</table>

</div>
<?php
?>
               <div class=" last_div">
               <div class="col-12 " style="margin-top:120px ">
                     <p class='text-right'><strong><u>Total</u> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>666</u></strong>	</p>
                </div>
                <div class="col-12" >
                     <p class='text-right'><strong>Result&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Pass</strong></p>
                </div>
                <div class="row">
                    <div class="col-3">
                        <p class='text-left'><strong>Obtained Marks</strong></p>
                    </div>
                    <div class="col-9">
                        <p class='text-left'><strong>494</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <p class='text-left'><strong>Maximum Marks</strong></p>
                    </div>
                    <div class="col-9">
                        <p class='text-left'><strong>700</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p class='text-left'><strong>Total Marks Obtained (in words)  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php  numberTowords("$total") ;   ?></strong></p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <p class='text-left mt-5'><strong>Date</strong> : 16/09/2021</p>
                    </div>
                </div>
               </div>
 <hr>       
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
