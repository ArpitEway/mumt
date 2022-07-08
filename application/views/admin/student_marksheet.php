<?php
function numberTowords($num)
{

$ones = array(
0 =>"ZERO",
1 => "ONE",
2 => "TWO",
3 => "THREE",
4 => "FOUR",
5 => "FIVE",
6 => "SIX",
7 => "SEVEN",
8 => "EIGHT",
9 => "NINE",
10 => "TEN",
11 => "ELEVEN",
12 => "TWELVE",
13 => "THIRTEEN",
14 => "FOURTEEN",
15 => "FIFTEEN",
16 => "SIXTEEN",
17 => "SEVENTEEN",
18 => "EIGHTEEN",
19 => "NINETEEN",
"014" => "FOURTEEN"
);
$tens = array( 
0 => "ZERO",
1 => "TEN",
2 => "TWENTY",
3 => "THIRTY", 
4 => "FORTY", 
5 => "FIFTY", 
6 => "SIXTY", 
7 => "SEVENTY", 
8 => "EIGHTY", 
9 => "NINETY" 
); 
$hundreds = array( 
"HUNDRED", 
"THOUSAND", 
"MILLION", 
"BILLION", 
"TRILLION", 
"QUARDRILLION" 
); /*limit t quadrillion */
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr,1); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){
	
while(substr($i,0,1)=="0")
		$i=substr($i,1,5);
if($i < 20){ 
/* echo "getting:".$i; */
$rettxt .= $ones[$i]; 
}elseif($i < 100){ 
if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
}else{ 
if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
} 
if($key > 0){ 
$rettxt .= " ".$hundreds[$key]." "; 
}
} 
if($decnum > 0){
$rettxt .= " and ";
if($decnum < 20){
$rettxt .= $ones[$decnum];
}elseif($decnum < 100){
$rettxt .= $tens[substr($decnum,0,1)];
$rettxt .= " ".$ones[substr($decnum,1,1)];
}
}
return $rettxt;
}
?>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<link href="css/print_Marksheet.css" rel="stylesheet" type="text/css">

<style type="text/css">

body {
color:#000000;
font-family: "Times New Roman";

}

.style1 {
	font-size: 13px;
	font-weight: bold;
}
.style4 {font-size: 14px; font-weight: bold; }
.style7 {font-size: 12px; font-weight: bold; }
.barcode img{
    height: 25px;
}
.student_image{
	image-orientation: none;
}
@media print {
	.breakhere { page-break-before:always;  };
    }
    th.border.border-dark {
    	vertical-align: middle;
    }
</style>
</head>
<body>
<center>
<?php 
foreach($students as $student)
{


  $class_details = $this->Common_model->getRecordByWhere('marksheet_variables',array('class_id'=>'27'));
  $this->db->select("*");
  $this->db->from('paper_master');
  $this->db->join("new_exam_form",'paper_master.id = new_exam_form.paper_id');
  $this->db->where('new_exam_form.student_id',$student->student_id);
  $papers = $this->db->get()->result();


?>
<fieldset id="printarea"  style="width:90%;border: 0px solid #22316C;"> 
<div align="left"> MS No. <?php echo $student->marksheet_no; ?> </div>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td height="100" colspan="2" valign="top">
<p align="center">      
<p align="center"> <font size="2.5"> <b>
</b> 
</font> 
</br> <font size="1"><b>
</b></font>

</br> <font size="1"><b>
</b></font>

</br> <font size="1">
</font>

</br> <font size="2"><b> <U>
</U></b> </font>     </p>
<br>
<center>
<span class="style1"><strong> <font size="4">
<?php
//$c_name=$student['course_name'];
echo $student->course_name .' '. $student->class_name ; ?><strong></span>
    </center></font></td>
    </tr>
    <tr>
    <td align="center" height="120" colspan="2">
    
    <table class="mytable" border="0" cellpadding="2" cellspacing="2" width="100%">
    <tbody>
    <tr>
    <td class="Normaltext" colspan="2">
    <div align="center"><font size="4">  &nbsp; </font></div></td>
    <!--<td align="left" width="17%" rowspan="5">
<img  src="images/<?php // echo $student['session']; ?>/<?php //  echo $student['photo']; ?>" width="90px" height="105px"></td>-->
    </tr>
    
<tr>
<td class="Normaltext" align="left"><div align="left">Roll No</div></td>
<td width="53%" class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo $student->roll_no;; ?></span> <div style="float:right"> &nbsp;&nbsp;&nbsp; Mode - Distance Education </div>
</div></td>
<td align="left" width="18%" rowspan="4">
<img border="1"  class="student_image" src="../admin/en/na.jpg" width="90px" height="105px">
</td>
</tr>


<tr>
<td class="Normaltext" align="left"><div align="left">Enrolment / Registration No.</div></td>
<td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;">DE / <?php echo  $student->enrollment_no;; ?></span></div></td>
</tr>


<tr>
<td class="Normaltext" align="left" width="29%"><div align="left">Name of the Candidate</div></td>
<td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo  $student->name; ?></span></div></td>
</tr>


<tr>
<td class="Normaltext" align="left" width="29%"><div align="left">Father's / Husband's Name</div></td>
<td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php echo strtoupper( $student->f_h_name); ?></span></div></td>
</tr>


<!--<tr>
<td class="Normaltext" align="left" width="28%"><div align="left">Name of Institute</div>
</td>
<td class="resultText"><div align="left"><span id="lblSemesterGrading" style="color:Black;"><?php // echo strtoupper($student['center_name']); ?></span></div>
</td>
</tr>-->
</tbody>
</table></td></tr><tr>

<td height="72" colspan="2">
    
<br>
    
		<fieldset style="border: 0px solid #22316C;">       
        <div style="min-height:250px;">
        <table id="" style="width:100%;" border="0" cellspacing="0" cellpadding="0" align="center">
			<tbody>

          <tr style="font-family:Arial, Helvetica, sans-serif; font-size:11px" align="center">
              <td width="11%" rowspan="3" align="left" style="font-size:11px" scope="col"><span class="style7">Paper Code</span></td>
              <td width="42%" rowspan="3" style="font-size:11px" scope="col"> <div align="left" class="style7">Paper Name</div></td>    
              <td  colspan="4"><strong><u> Examination Scheme</u></strong></td>              
              <td colspan="2"><strong><u>Obtained Marks</u></strong></td>
              <td width="8%" rowspan="3" scope="col"><strong><u>Total</u></strong></td>
          </tr>
                      
            
            
          <tr style="font-family:Verdana;font-size:9px;" align="center">
              <td scope="col" colspan="2"><strong><u>Th/Pr/Pj</u></strong></td>
              <td scope="col" colspan="2"><strong><u>Assignment</u></strong></td>

              <td width="8%" rowspan="2" scope="col" style="text-align:left;padding-left:10px;"><strong><u>Th/Pr/Pj</u></strong></td>
              <td width="6%" rowspan="2" scope="col"><strong><u>Assignment</u></strong></td>
          </tr>

             
            
            

          <tr style="font-family:Verdana;font-size:9px;" align="center">
              <td width="6%" scope="col"><strong><u>Max</u></strong></td>
              <td width="6%" scope="col"><strong><u>Min</u></strong></td>
              <td width="6%" scope="col"><strong><u>Max</u></strong></td>
              <td width="6%" scope="col"><strong><u>Min</u></strong></td>
          </tr>
      

  <tr> 
  
  <td colspan="9">&nbsp;</td> </tr>


  <?php

$this->db->select('*');
$this->db->from('new_exam_form');
$this->db->join('paper_master', 'new_exam_form.paper_id = paper_master.id');
$this->db->where('new_exam_form.student_id',$student->student_id); 
$paper_marks = $this->db->get()->result();
$check_grace_marks = false;
$fail_count = 0;
$fail_tot_marks = 0;
$require_tot_marks = 0;
$tot_marks = 0;
$result = "PASS";
$int_fail_count = 0 ;
$abs_count = 0 ;
foreach($paper_marks as $marks){
    if($marks->type=='theory'){
            $tot_marks += $marks->max_theory_marks;
        if($marks->theory_marks<$marks->min_theory_marks){
            $result = "FAIL";
            $fail_count++;
            $fail_tot_marks += $marks->theory_marks;
            $require_tot_marks += $marks->min_theory_marks;
        }
        if($marks->theory_marks == 'ABS'){
          $result = 'FAIL';
          $abs_count++ ;
        }
        if($marks->int_marks<$marks->min_internal_marks)
        {
          $result ="FAIL";
          $int_fail_count++ ;
        }
        if($marks->int_marks=="N")
        {
          $result='FAIL';
        }
    }else if($marks->type=='practical'){
        $tot_std_marks += $marks->p_marks;
        $tot_marks += $marks->max_theory_marks;
        if($marks->p_marks<$marks->min_theory_marks){
            $result = "FAIL";
            $fail_count++;
            $fali_tot_marks += $marks->p_marks;
            $require_tot_marks += $marks->min_theory_marks;
        }
    }
}

// $aggregate_per =   ($tot_std_marks/$tot_marks) * 100;     
$require_grace_marks = $require_tot_marks-$fail_tot_marks;

if ($fail_count<3 && $require_grace_marks<4 && $int_fail_count==0 && $fail_count!=0 && $require_grace_marks!=0 && $abs_count==0) {
  // echo $require_grace_marks ;

    $check_grace_marks = true;
    $result = "PASS BY GRACE";
}

foreach($papers as $paper)
{

  $total = $total + $paper->theory_marks +  $paper->int_marks ;
  $maximum_marks =   $maximum_marks + $paper->max_theory_marks + $paper->max_internal_marks ;

  ?>
 <tr style="font-family:Arial, Helvetica, sans-serif; font-size:12px;" align="center" valign="middle">
    <td style="margin-top:2px;" align="left"><strong><?php echo  $paper->paper_code; ?></strong></td>
   
   <td align="left"><strong><?php  echo $paper->paper_name ;  ?></strong></td>
   
   
    <td align="center" ><span class="style4">
	<?php echo  $paper->max_theory_marks;?></span></td>
    <td align="center" ><span class="style4">
	<?php echo  $paper->min_theory_marks; ?></span></td>
   <td align="center" ><span class="style4"><?php echo  $paper->max_internal_marks; ?></span></td>
     <td align="center" ><span class="style4"><?php echo  $paper->min_internal_marks; ?></span></td>
      <td align="left" ><span class="style4" style="padding-left:10px;">
        <?php
         if($paper->theory_marks <  $paper->min_theory_marks || $paper->int_marks <  $paper->min_internal_marks && $check_grace_marks==false)
         {
           echo $paper->theory_marks . '*' ;
         }
          elseif($paper->theory_marks<$paper->min_theory_marks)
         {
           echo $paper->theory_marks; echo ($check_grace_marks) ? ' G' : '';
         }
         elseif($paper->theory_marks=='ABS'){
           echo 'ABS F';
         }
         else{
           echo $paper->theory_marks ;
         }
         ?>
      </span></td>
     <td align="left" class="style4"><span class="style2" style="padding-left:10px;">
       <?php echo  $paper->int_marks; ?>
     </span></td>
     
     <td align="left" class="style2"><span class="style4" style="padding-left:10px;">
	<?php 
  if($paper->int_marks<$paper->min_internal_marks || $paper->theory_marks<$paper->min_theory_marks && $check_grace_marks==false)
  {
    echo  $paper->theory_marks +  $paper->int_marks . '*' ; 
  }
  elseif($paper->theory_marks=="ABS")
  {
    echo 'ABS';
  }
  else{
    echo $paper->theory_marks + $paper->int_marks ;
  }
	?>
     </span></td>
  </tr>
  <?php
}

?>

</tbody></table>
</div>




<table border="0" cellpadding="0" height="112" width="100%">
<tbody>

<tr>
  <td  colspan="8">  </td>
  </tr>
<tr>
  <td height="20">&nbsp;</td>
  <td align="center"></td>
  <td align="center"> </td>
  <td> <strong> <?php // echo  $t_max; ?></strong>  </td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align=""><strong><u>Total</u></strong></td>
  <td align="">&nbsp;&nbsp;&nbsp;&nbsp;<strong><u><?php echo $total; ?></u></strong> </td>
</tr>

<tr>
  <td colspan="8"></td>
</tr>



<tr>
<td width="22%" height="20"><strong><?php //echo $year; //$dt_row['year']; ?></strong></td>
<td width="13%" style="text-align: center"><strong><?php //echo $year_num;//$dt_row['sem']; ?></strong></td>
<td width="30%">&nbsp;</td>
<td width="9%"><!--<div align="center"><strong>Grand-Total</strong></div>--></td>
<td width="1%"><div align="center"></div></td>
<td width="8%"><div align="center"><strong></strong></div></td>
<td width="7%"><div align=""><strong>Result</strong></div></td>
<td width="10%"><div align="center"><strong><div align="left">&nbsp;&nbsp;&nbsp;<b>
  <?php echo $result ; ?>
</b></div></strong></div></td>
</tr>


<tr>
<td height="20" ><strong>Obtained Marks</strong></td>
<td style="text-align: center"><b><?php echo  $total ; ?></b></td>
<td>&nbsp;</td>
<td> <div align="center"><b><?php // echo  $tot_obt; ?></b></div></td>
<td>&nbsp;</td>
<td>&nbsp;  </td>
<td>
<strong>&nbsp; </strong><?php /*?><div align="right"><b>
  <?php if($all_result=='FAIL') { echo "FAIL"; } else { echo $all_result; } ?>
</b></div><?php */?>
</td>
<td> <div align="center"><b> <?php
	/*	if($all_result=='FAIL') {}
		else if($t_max!=0)
		{
		$p=($tot_obt/$t_max)*100;
		$p=substr($p,0,5);
		if($p>=60) { echo "First";  }
		else if($p>=46 &&$p<60) { echo "Second";  }
		else { echo "Third";  }
		}*/
		?> 
</b> </div></td>
</tr>
 
 <tr>
<td height="20"><strong>Maximum Marks</strong></td>
<td style="text-align: center"><b><?php echo $maximum_marks ; ?></b></td>
<td>&nbsp;</td>
<td><div align="center"><b><?php // echo  $t_max; ?></b></div></td>
<td></td>
<td><div align="center"></div></td>
<td><strong>&nbsp;</strong></td>
<td> <div align="center">
  <strong>
  <?php
		/*if($all_result=='FAIL') {}
		else if($t_max!=0) 
		{
		$p=($tot_obt/$t_max)*100;
		$p=substr($p,0,5);
		echo $p."%";
		}*/
		?>  </strong></div></td>
</tr>



<tr>
<td colspan="8"><strong>Total Marks Obtained (in words)</strong> &nbsp;&nbsp;
<strong><?php echo  numberTowords("$total")//strtoupper($word);?></strong></td>
</tr>


    </tbody>
    </table>
    </fieldset>
	
<!-- if starts -->
<tr>

<td align="left" colspan="2"> 
           
           <table width="100%" style="margin-top:50px">
           <tr>
           <!--<td>Place : Jabalpur</td>
            <td>Dated : <b> <?php // echo date('d-m-Y'); ?> </b></td>
             <td>Checked by ...............</td>
              <td align="right">Controler of Exam / Asstt Registrar</td>-->
           </tr>
      </table>    </td></tr>
    
    
    <tr>
        <td width="17" align="center">
              <div align="left">
              <?php echo "Date :".$class_details[0]->result_date; ?></div></td>
    </tr>
	<tr>
			  <td>
			  <div style="float:left; margin-top:5px; 
    margin-left: -8px;" class='barcode'> 
        <svg id="barcode"></svg>
         </div>
			  </td>
    </tr>
</table>
<br>
</fieldset>
<?php 
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.11.5/JsBarcode.all.min.js"></script>

<script>
  JsBarcode("#barcode", "<?php  echo $student->roll_no.$class_details[0]->bar_code_no ;  ?>", {
        format: 'code128',
        lineColor: "#2429e",
        width: 2,
        height: 30,
        displayValue: false,
        fontSize:20,
        marginBottom  : 0,
});

</script>
<?php

  }
?>

</body>
<script language="javascript">


</script>
</html>
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
