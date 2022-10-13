
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
     <p class="break" style="font-size: 16px;"></p>
	 <p align="center" style="margin-top:10px;line-height:15px;font-size:15px;"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya DDE, Jabalpur</b></p>
     <p align="center" style="line-height:12px;font-size:14px;"><b>Regular List of Roll No. Exam <?=$examTitle?></b></p>
     <p align="center" style="line-height:12px;font-size:12px;"><b><i> Center : <?php echo $center->center_code;?> </i></b> </p>
     <p align="right">Page No :<?php echo $pageno++; ?> </p>
     <?php  $sno=0; $count=1; $trow=0;$flag=true;
                foreach($center_students as $student)  { 
                    $studentCount++;
                    if((($sno%30==0 /*&& $sno%90!=0*/ ) )|| $sno==0 ) { $count=1; $trow++; ?>
                           <table align="center" cellpadding="6" border="1" width="100%">
                            <tr bgcolor="#FFCC99" style="text-align:center;">
                                <th>#</th>
                                <th>Roll No.</th>
                                <th>Enrollment No.</th>
                                <th>Student Name</th>
                                <th>Course</th>
                                <th>Class</th>
                                <th>Remark</th>
                            </tr>
                    <?php } ?><tr style="text-align:center;">
                            <td valign="middle"><?php	echo $studentCount ;?></td>
                            <td valign="middle"><?php	echo $student->roll_number ;?></td>
                            <td valign="middle"><?php	echo $student->enrollment_no ;?></td>
                            <td valign="middle"  align="left"><?php	echo $student->name ;?></td>
                            <td valign="middle" align="left"><?php	echo $student->course_name ;?></td>
                            <td valign="middle"><?php	echo $student->class_name ;?></td>
                            <td valign="middle" style="width: 20%;"></td>
                    </tr>
                     <?php  if( $count==30 ) { $flag=false;  $trow=0;  $sno=-1; if($totalStudent>$studentCount)?>
                     </table>
                            <p class="break" style="font-size: 16px;"></p>
                            <p align="center" style="margin-top:15px;line-height:15px;font-size:15px;"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya DDE, Jabalpur</b></p>
                            <p align="center" style="line-height:12px;font-size:14px;"><b>Regular List of Roll No. Exam <?=$examTitle?> </b></p>
                            <p align="center" style="line-height:12px;font-size:12px;"> <b><i> Center : <?php echo $center->center_code;?> </i></b> </p>
                            <p align="right">Page No :<?php echo $pageno++; ?></p>
                            <table align="center" cellpadding="6" border="1" width="90%">
                        <?php }  $sno++; $count++; }     ?><tr>
        <td valign="middle" colspan="3">
           <p style="margin-top: 1rem;">Total Marksheet:</p>
           <p>Dispatch No:</p>
           <p>Dispatch Dt:</p>
        </td>
        <td valign="bottom" colspan="4">
             <p align="center">Assistant Registrar</p>
             <p align="center"><b>Maharishi Mahesh Yogi Vedic Vishvavidyalaya DDE, Jabalpur</b></p>
        </td>
    </tr></table><?php }// student loop ?>         
 <?php $centerCount++; 
} //center loop ?>