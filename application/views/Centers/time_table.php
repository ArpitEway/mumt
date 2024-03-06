<div id="ss">
    <h3 align="center">&nbsp;</h3>
    <h3 align="center" class="style1" style="text-align:center;font-size:24px;">Maharishi Mahesh Yogi Vedic Vishwavidyalaya  </h3>
    <p style="text-align:center;font-size:12px;"><strong>Programme for Annual/Semester - Main/Backlog Examination January 2024 </strong></p>
    <!-- <p style="text-align:center;font-size:12px;">(Regular/Private)</p> -->
    <!-- <p style="text-align:center;font-size:12px;"><strong>Early Morning Shift Time 07:00 AM To 10:00 AM </strong></p> -->

    <p style="text-align:center;font-size:12px;"><strong> Morning Shift Time 11:00 AM To 02:00 PM </strong></p>
    <?php if($paper_list[0]['course_group_id']!=75 && $paper_list[0]['course_group_id']!=76 && $paper_list[0]['course_group_id']!=77){  ?>
    <p style="text-align:center;font-size:12px;"><strong> Afternoon Shift Time 03:00 PM To 06:00 PM </strong></p>
    <?php }else{  ?>
    <p style="text-align:center;font-size:12px;"><strong> Afternoon Shift Time 12:00 PM To 03:00 PM </strong></p> <?php } ?>
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

                    <td align="center"><?php echo date("d-m-Y", strtotime($rows['exam_date'])); ?></td>
                    <td align="center"><?=$rows['exam_day']?></td>
                    <td align="center"><?=$rows['exam_shift']?></td>
                    <td align="center"><?=$rows['course_name']?></td>
                    <td align="center"><?=$rows['paper_no_for_time_table']?></td>
                    <td align="center"><?=$rows['paper_code']?></td>
                    <td align="center"><?=$class[0]['class_name']?></td>
                    <td><?=$rows['paper_name']?></td>
                </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
</div>
<div class="text-center mt-10">
    
<input type="button" class="btn btn-primary mx-auto" onclick="PrintDiv();" value=" Print ">
</div>


