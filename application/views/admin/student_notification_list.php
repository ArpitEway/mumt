
<?php

$page=1;
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<style>

.break { page-break-before: always; }
.flex-container {
  display: inline-flex;
  flex-wrap: wrap;
}

.flex  {
     padding-left: 1000px; /* HERE WE ADD THE SPACE */
}
.size  {
    font-size: 15px; /* HERE WE ADD THE SPACE */
}

</style>
<body>

	<p align="right"><?php echo "Page : ". $page; ?></p>
<div style="width:75px;float:left"><img src="<?=base_url('assets/logo.png')?>" ></div>
<h3 class="text-center" ><strong> Maharishi Mahesh Yogi Vedic Vishwavidyalaya </strong> </h3>
<p align="center" style="line-height:0px">Head Office: Karaundi, Post-Mahner ,Distt- Katni(MP) Website www.mmyvvdde.com </p>
<h3 align="center"><strong>Result Notification of</strong> <br><h3>
	<h3 align="center">	<strong><?php echo $students[0]->course_name.' - '. $students[0]->class_name .' Examination '. $students[0]->session ?></strong><br><h3>
 <title>Notification <?php echo $students[0]->course_name?></title> 

 

<div class="flex-container">
  <div style="font-size:15px;" >Notification No : <?php echo $notification_no;?></div>
  
  <div style="font-size:15px;" class="flex">Date : <?php echo $notification_no;?></div>  
</div>
<div style="font-size:15px;" align="center">The Result of the following examinees of the above exam is hereby declared as under : <?php echo $notification_no;?></div>
<hr>


<table width="100%"  border="1">

<tr bgcolor="#FFFF00">
   <th scope="row" width="5%"> S.No. </th>
    <th scope="row" width="20%"><span class="style5">Roll No.</span></th>
   <!-- <th scope="row"><p class="style5">MS No.</p></th>
   --> <th style="text-align:left" scope="row"  width="30%"><span class="style5" >Name and F/H Name</span></th>
     <th scope="row"  width="15%">Result</span></th>
    <th scope="row"  width="10%"><span class="style5">Total</span></th>
     <th scope="row"><span class="style5">Remark</span></th>
  
</tr>
</table>
<center style="font-size:15px;">Directorate of Distance Education</center>

<table width="100%"  border="1">
<tbody>
					<?php
					$i=1;
					foreach($students as $student){
						?>
						<tr>

							<td >
								<?php echo $i++; ?>
							</td>
							<td class="style6" scope="row" width="20%">
								<?php echo $student->roll_number; ?>
							</td>
							<td width="30%" scope="row" class="style6" >
								<?php echo $student->name  .' / '.  $student->f_h_name; ?>
							</td>
							<td align="center" width="15%" >Pass</td>
							<td align="center" width="10%">111</td>
							<td>Remarks</td>		 	
						
							
					
						</tr>
						<?php
					}
					?>
					
				</tbody>

</table>


<table width="100%">
<hr>
<tr>
<td>&nbsp;
</td>
<td class="size" colspan="2" align="right" >
Order for Declaration of Result & Publication
</td>
</tr>
<tr>
<td colspan="3">
<p class="size" style="line-height:2px">RW-Result Withheld</p>
<p class="size" style="line-height:2px">RWE-Want of Enrolment</p>
<p class="size"style="line-height:2px">RWPM-Want of Prev. Sem/Year Marks</p>
<p class="size"style="line-height:2px">RWPR-Practical Marks Not Received</p>
<p class="size" style="line-height:2px">RWAS-Assignment Marks Not Received</p>
<p class="size"style="line-height:2px">RWPJ-Project Marks Not Received</p>
<!--<p style="line-height:2px">RWPM-Project Marks Not Received</p>-->
<p class="size" style="line-height:2px">UFM-Unfair Means</p>
<p class="size" style="line-height:2px">GR-Grace Mark In One Theory Paper For Passing</p>
<p class="size" style="line-height:2px">VCG-Vice-Chancellor's One Grace Mark In Division</p>
</td>
</tr>
<tr><td>&nbsp;</td> <td class="size" align="right">Asst. Registrar</td><td class="size"align="center">Registrar/Controller Of Examination</td></tr>
<tr><td colspan="2" class="size">Copy of Result Notification is forwarded for information to

<p style="padding-top: 15px;" class="size">1.Notice Board of the University</p>
<p class="size">2.Directorate of Distance Education</p></td></tr>
</table>

</body>
</html>
