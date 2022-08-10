<br>
<br> 
<br>
<style>
    .table th, .table td {
     /* border-top: 1px solid #3F4254;*/
     border-top:none;
}
</style>    
<?php

$total=0;
foreach($elist as $row)
{
     
      $sql="SELECT count(*) as cnt FROM `new_exam_form_report` as `e` JOIN `student_report` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE  `s`.`examcentercode`='".$row->examcentercode."'   AND  `e`.`paper_code` = '".$paperData[0]['paper_code']."' AND `s`.`class_id` = '".$paperData[0]['class_id']."' AND   `s`.`exam_center_id` = '".$row->id."'  AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2021' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2022' AND `s`.`class_name` = 'I SEM' ));";    
    $query = $this->db->query($sql);
    $count = $query->result_array();
   
        $qu="SELECT count(*) as num FROM `student_report` as s join paper_master as p on s.class_id=p.class_id WHERE  `p`.`paper_code` = '".$paperData[0]['paper_code']."' AND `p`.`class_id` = '".$paperData[0]['class_id']."' AND `s`.`examcentercode`='".$row->examcentercode."' AND   `s`.`exam_center_id` = '".$row->id."'  AND temp_exam_form='N' and `session` = 'July 2021' AND `s`.`class_name` = 'I Year'";
    $query = $this->db->query($qu);
    $all = $query->result_array();
   
     $allElective= $all[0]['num'];
   
    
   
    if($paperData[0]['class_id']==104 && $paperData[0]['ce']=='elective' && ( $allElective>0)){
       $allElective=round(($all[0]['num']*60)/100); 
       
    }
   
     //$total+=$count[0]->cnt;
     if(($count[0]['cnt'] >0) || ($allElective >0) )
     {  
?>

<table style="width:90%;text-align:left;font-size:15px" align="center" class="table table-hover table-striped" border="1">
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
            <?php //echo "(All=> ".$allElective.") Fill=> ".$count[0]['cnt']; 
            echo $allElective +$count[0]['cnt'];
            ?>
           
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
                   // if($paperData[0]["exam_shift"]=='Early Morning'){echo "07:00 AM To 10:00 AM";} 
                    if($paperData[0]["exam_shift"]=='Morning'){echo "11:00 AM To 02:00 PM";}
                    if($paperData[0]["exam_shift"]=='Evening'){echo "03:00 PM To 06:00 PM"; }  ?> 
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

}  ?>