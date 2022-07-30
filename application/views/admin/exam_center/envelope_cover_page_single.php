<br>
<br> 
<br><?php
//print_r($paperData);
foreach($elist as $row)
{
   
    $where= array(
        'e.paper_code'=>$paperData[0]['paper_code'],
        's.new_exam_form!='=>'D' ,
        's.examcentercode'=>$row->examcentercode,
        's.exam_center_id'=>$row->exam_center_id,
        's.class_id'=>'e.class_id',
     );
     $tag='count(*) as cnt';
     $table="new_exam_form  as e";
     $join_table='student as s';
     $join_on='e.student_id = s.student_id';
     $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);
    
    // if($count[0]->cnt >0)
     {  //echo $this->db->last_query();
?>

<table style="width:90%;text-align:center;font-size:15px" align="center" class="table table-hover table-striped" border="1">
    <tbody><tr><td colspan="4" align="center"><h4 align="center">TOP SECRET</h4></td></tr>
        <tr>
            <td><strong>Exam Center Code</strong></td>
            <td style="font-size:17px"><strong><?php echo $row->examcentercode; ?></strong></td>
            <td>Examination</td>
            <th><?= $examSession ?></th>
        </tr>
        <tr>
        
            <td><strong>Course</strong></td>
            <td colspan="3"><?= $paperData[0]['course_name'] ?> &nbsp;&nbsp;(&nbsp;<?= $classMaster[0]->class_name ?>&nbsp;)</td> 
        </tr>
        <tr>
            <td><strong>Subject</strong></td>
            <td colspan="3"><?= $paperData[0]['paper_name'] ?>(&nbsp;<?= $paperData[0]['paper_code'] ?>&nbsp;)</td>
        </tr> <tr>
            <td><strong>Paper</strong></td>
            <td colspan="3"><?= $paperData[0]['paper_no'] ?></td>
        </tr>
        <tr>
            <td style="vertical-align:middle"><strong>Test Id</strong></td>
            <td style="text-align:center;vertical-align:middle"><h4><?= $paperData[0]['test_id'] ?></h4></td>
            <td style="vertical-align:middle"><strong>Quantity</strong></td>
            <td colspan="" bgcolor="#FFFFFF" style="text-align:center; vertical-align:middle"><span style="font-size:20px;font-weight:bold;text-decoration:underline;font-style:italic;">
            <?= $count[0]->cnt; ?>
            </span></td>
        </tr>
        <tr>
            <td><strong>Date</strong></td>
            <td><?php echo  date("d-m-Y", strtotime($paperData[0]['exam_date']))   ?></td><td><strong>Day</strong></td>
            <td><?= $paperData[0]['exam_day'] ?></td>
        </tr>
        <tr>
            <td><strong>Shift</strong></td> 
            <td><?= $paperData[0]['exam_shift'] ?></td>
            <td><strong>Time</strong></td>
            <td><?php  
                    if($paperData[0]["exam_shift"]=='Early Morning'){echo "07:00 AM To 10:00 AM";} 
                    if($paperData[0]["exam_shift"]=='Morning'){echo "11:00 AM To 02:00 PM";}
                    if($paperData[0]["exam_shift"]=='Afternoon'){echo "03:00 PM To 06:00 PM"; }  ?> 
            </td> </tr> <tr>
            <td><strong>Paper Used</strong></td>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr> 
            <td><strong>Paper Return</strong></td>
            <td colspan="3">&nbsp;</td> 
        </tr>
    </tbody>
</table>
<br>
<br> 
<br>
<?php }
} ?>