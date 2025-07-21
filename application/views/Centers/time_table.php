<div id="ss">
    <h3 align="center">&nbsp;</h3>
    <h3 align="center" class="style1" style="text-align:center;font-size:24px;">Maharishi Mahesh Yogi Vedic Vishwavidyalaya  </h3>
    
    <!-- <p style="text-align:center;font-size:12px;">(Regular/Private)</p> -->
    <!-- <p style="text-align:center;font-size:12px;"><strong>Early Morning Shift Time 07:00 AM To 10:00 AM </strong></p> -->
    <p style="text-align:center;font-size:12px;"><strong>Programme for Annual/Semester - Main/Backlog Examination <?= (in_array($paper_list[0]['class_id'], array(137,149,183,185,191,138,184,192,187,143,146,139,144,188,145,147,148,150,186,141,151,142,190,264,140,189,262,268,270,256,258,260,317,173,174,175,177,180,300,301))) ? 'July' : 'June' ?> 2025 </strong></p>
    <p style="text-align:center;font-size:12px;"><strong> Morning Shift Time 10:00 AM To 01:00 PM </strong></p>

    <!-- <p style="text-align:center;font-size:12px;"><strong> Afternoon Shift Time 02:00 PM To 05:00 PM </strong></p> -->
<?php
$class_ids = array(104,101,107,110,116,119,273,125,128,131,134,162,163,164,165,283,285,287,289,310,291,293,295,274,297,168,169,170,171,214,106,103,109,112,118,121,127,130,133,136,173,174,175,177,180,264,137,149,183,185,191,138,184,192,187,143,146,139,144,188,145,147,148,150,186,141,151,142,190,140,189);
     if(in_array($paper_list[0]['class_id'],$class_ids)){  ?>
     <p style="text-align:center;font-size:12px;"><strong> Afternoon Shift Time 03:00 PM To 06:00 PM </strong></p>
    <?php }else{  ?>
     <p style="text-align:center;font-size:12px;"><strong> Afternoon Shift Time 02:00 PM To 05:00 PM </strong></p> <?php } ?>

        <?php /*if($paper_list[0]['course_group_id']==45){ ?> 
        <p style="text-align:center;font-size:12px;"><strong>Programme for Annual/Semester - Main/Backlog Examination June 2025 </strong></p>
        <p style="text-align:center;font-size:12px;"><strong> Morning Shift Time 10:00 AM To 01:00 PM </strong></p>
    <?php }else{  ?> 
        <p style="text-align:center;font-size:12px;"><strong>Programme for Annual/Semester - Main/Backlog Examination June 2025 </strong></p>
    <p style="text-align:center;font-size:12px;"><strong> Morning Shift Time 11:00 AM To 02:00 PM </strong></p>
    <?php }
    ?>
   */  
?>

    <table align="center" cellpadding="5">
        <tbody>
            <tr bgcolor="#FFCC99">
                <th align="center">S.No.</th>
                <th align="center">Exam Date</th>
                <th align="center">Exam Day</th>
                <th align="center">Shift</th>
                <th align="center">Course Name</th>
                <th align="center">Paper No.</th>
                <th align="center">Paper Code</th>
                <th align="center">Class</th>
                <th align="center">Paper Name</th>

            </tr>
            <?php $i=1; foreach($paper_list as $rows){  ?>
                <tr bgcolor="#E8F6FF">
                    <td align="center"><?=$i?></td>
                    <td align="center"><?php echo date("d-m-Y", strtotime(($mode =="PVT" && in_array($rows['class_id'],[104,107,134]))?$rows['pvt_exam_date']:$rows['exam_date'])); ?></td>
                    <td align="center"><?= ($mode =="PVT" && in_array($rows['class_id'],[104,107,134]))?$rows['pvt_exam_day']:$rows['exam_day']?></td>
                    <td align="center"><?= ($mode =="PVT" && in_array($rows['class_id'],[104,107,134]))?$rows['pvt_exam_shift']:$rows['exam_shift']?></td>
                    <td align="center"><?=$rows['course_name']?></td>
                    <td align="center"><?=$rows['paper_no_for_time_table']?></td>
                    <td align="center"><?=$rows['paper_code']?></td>
                    <td align="center"><?=$class[0]['class_name']?></td>
                    <td><?=$rows['paper_name']?></td>
                </tr>
            <?php $i++; }  
            ?>
        </tbody>
    </table>
</div>
<div class="text-center mt-10">
    
<input type="button" class="btn btn-primary mx-auto" onclick="PrintDiv();" value=" Print ">
</div>


