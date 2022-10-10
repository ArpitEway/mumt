
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
    .papertable tr th,td{
    	vertical-align: middle !important;
    }
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
  foreach($centers as $center)  {
 
 $this->db->select('*');
 $this->db->from('student');
 $this->db->order_by("roll_no", "asc");
 
 $where = array('center_id'=>$center->id, 'roll_no!=' => 0 ,'new_exam_form'=>'Y');
 $this->db->where($where);	
 $center_students = $this->db->get()->result();
 if($center_students){
     ?>
     <p class="break" style="font-size: 16px;"> </p>
		
        <p align="center" style="line-height:12px;font-size:15px;"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya DDE, Jabalpur</b></p>
        
        <p align="center" style="line-height:12px;font-size:14px;"><b>List of Roll No. Exam <?php //echo $exam_session; ?></b></p>
        
        <p align="center" style="line-height:12px;font-size:12px;"> <b><i> Center : <?php echo $center->center_code;?> </i></b> </p>
        
        <p align="right">Page No :<?php echo $pageno++; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
        
        <table align="center" cellpadding="6" border="1" width="90%">
        <tr>
            
            
              
            <?php
            $sno=0; $count=1; $trow=0;
                foreach($center_students as $student)  { 

                    ?>
   
              
            <?php if((($sno%18==0 && $sno%90!=0) )|| $sno==0) { $count=1; $trow++; ?>
                <td style="width:20%;"  valign="top">
                <table align="center" cellpadding="6" border="1" width="100%">
                    <tr bgcolor="#FFCC99" style="text-align:center;">
                        <th>Roll No. / Enrollment No.</th>
                    </tr>
            <?php } ?>      
                    
            <tr style="text-align:center;"> 
                    <td valign="middle">  &nbsp;&nbsp;&nbsp;<?php	echo $count." num ".$student->roll_no.' / '.$student->enrollment_no ;?>  </td>
                </tr>
            <?php if( $count==18 ) { ?>
                </table>
                </td>
	          
            <?php } ?>      
            <?php if( $trow==4 ) { $trow=0; ?>
                </tr><tr>
            <?php } ?>     
 <?php $sno++; $count++; } }// student loop ?>
            </tr>  </table>
 <?php
 
} //center loop ?>