
    <style>
		 .break{
        page-break-before: always;
    }
    @page {
    	size: auto;
    }
    table img {
    	max-width: 150px;
    	width: 100%;
    }
    /* .papertable tr th,td{
    	vertical-align: middle !important;
    } */
    .papertable tr td{
    	padding: 10.2px !important;
    }
    .table thead th, .table thead td {
    	font-size: 16px;
    }
    .admit-card {
    	margin: 10px auto;
    }
    .padding{
    	padding: 10px;
    }
	</style>
 <?php 
  $totalCenter=count($centers);
  $centerCount=0;$pageno=1;
  foreach($centers as $center)  {
 
 $this->db->select('*');
 $this->db->from('student');
 $this->db->order_by("roll_number", "asc");
 
 $where = array('center_id'=>$center->id, 'roll_number!=' => 0 ,'exam_form'=>'Y');
 $this->db->where($where);	
 $center_students = $this->db->get()->result();
 if($center_students){
    $totalStudent=count($center_students);
    $studentCount=0;
     ?>
     <p class="break" style="font-size: 16px;"> </p>
		
        <p align="center" style="margin-top:10px;line-height:15px;font-size:15px;"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya DDE, Jabalpur</b></p>
        
        <p align="center" style="line-height:12px;font-size:14px;"><b>Regular List of Roll No. Exam <?=$examTitle?> <?php //echo $exam_session; ?></b></p>
        
        <p align="center" style="line-height:12px;font-size:12px;"> <b><i> Center : <?php echo $center->center_code;?> </i></b> </p>
        
        <p align="right">Page No :<?php echo $pageno++; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
        
        <table align="center" cellpadding="6" border="1" width="95%">
        <tr class="rowstart">
            
            
              
            <?php
            $sno=0; $count=1; $trow=0;$flag=true;
                foreach($center_students as $student)  { 
                    $studentCount++;
                    ?>
   
              
                    <?php if((($sno%21==0 /*&& $sno%90!=0*/) )|| $sno==0 ) { $count=1; $trow++; ?>
                        <td style="width:20%;"  valign="top">
                        <table align="center" cellpadding="10" border="1" width="100%">
                            <tr bgcolor="#FFCC99" style="text-align:center;">
                                <th>Roll No. / Enrollment No.</th>
                            </tr>
                    <?php } ?>      
                            
                    <tr style="text-align:center;"> 
                            <td valign="middle">  &nbsp;&nbsp;&nbsp;<?php	echo $student->roll_number.' / '.$student->enrollment_no ;?>  </td>
                        </tr>
                    <?php if( $count==21 ) { $flag=false; ?>
                        </table>
                        </td>
                        <?php if($trow==5 &&  $count==21  ) {  $trow=0;  $sno=-1; if($totalStudent>$studentCount)?>
                           
                        </tr></table>
                             <p class="break" style="font-size: 16px;"> </p>
                            <p align="center" style="margin-top:15px;line-height:15px;font-size:15px;"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya DDE, Jabalpur</b></p>
        
                            <p align="center" style="line-height:12px;font-size:14px;"><b>Regular List of Roll No. Exam <?=$examTitle?> <?php //echo $exam_session; ?></b></p>
                            
                            <p align="center" style="line-height:12px;font-size:12px;"> <b><i> Center : <?php echo $center->center_code;?> </i></b> </p>
                            
                            <p align="right">Page No :<?php echo $pageno++; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
                            
                            <table align="center" cellpadding="6" border="1" width="95%">
                            <tr class="rowstart">
                        <?php } ?>  
                    <?php } ?>      
                      
        <?php  $sno++; $count++; }  echo "</table></td></tr>"; 
        ?>   </table><?php }// student loop ?>
           
 <?php $centerCount++;
 
} //center loop ?>