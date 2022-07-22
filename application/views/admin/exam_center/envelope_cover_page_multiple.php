<br>
<br> 
<br><?php

foreach($elist as $row)
{
    $arr=array();
    foreach($paperData as $paper){ 
        $where= array(
            'e.paper_code'=>$paper['paper_code'],
            's.new_exam_form!='=>'D' ,
            's.examcentercode'=>$row->examcentercode,
            's.exam_center_id'=>$row->exam_center_id,
            's.class_id'=>$paper['class_id'],
            's.course_group_id'=>$paper['course_group_id'],
            's.class_id'=>'e.class_id',
        );
        $tag='count(*) as cnt';
        $table="new_exam_form  as e";
        $join_table='student as s';
        $join_on='e.student_id = s.student_id';
          $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);
        //  echo "<br>1 ".$count[0]->cnt;
         // echo $this->db->last_query();
          $arr[]=$count[0]->cnt;
    }//die;
    if($count[0]->cnt >0)
     {  
       // echo "<pre>";
       // print_r($row); /*
?>

<table style="width:90%;text-align:center;font-size:15px" class="table" table-hover="" table-striped="" border="1" align="center">
        <tbody><tr><td colspan="6" align="center"><h4 align="center">TOP SECRET</h4></td></tr>
            <tr>
                <td><strong>Exam Center Code</strong></td>
                <td colspan="6" style="font-size:17px"><strong><?php echo $row->examcentercode; ?></strong></td>
            </tr>
            <tr>
                <td><strong>Examination</strong></td>
                <th colspan="5"><?= $examSession ?></th>
            </tr>
            <tr>
                <td><strong>Course Name</strong></td><td><strong>Class</strong></td><td><strong>Subject</strong></td><td><strong>Quantity</strong></td><td><strong>Paper Used</strong></td><td><strong>Paper Return</strong></td></tr>
                <?php $total=0;$i=0;foreach($paperData as $paper){ $i++;
                    $this->db->select('*');
                    $this->db->from('class_master');
                    $this->db->where('class_master.id',$paper['class_id']);
                    $classMaster = $this->db->get()->result();
                    if($arr[$i]>0){ 
                    ?>
                <tr><td><?=$paper['course_name'] ?></td><td><?= $classMaster[0]->class_name ?></td><td><?=$paper['paper_name'] ?>&nbsp;<?=$paper['paper_code'] ?></td><td style="text-align:center"><?=$arr[$i]?></td><td>&nbsp;</td><td></td></tr>
               
                <?php 
              $examDate=  date("d-m-Y", strtotime($paper['exam_date']))  ;
              $examDay= $paper['exam_day']; 
              $test_id=$paper['test_id'];
              $examShift=$paper['exam_shift'];
              $total+=$arr[$i];
            }
             }?>
                <tr><td>&nbsp;</td>
                <td colspan="2"> <strong>Total Quantity</strong></td>
                <td style="text-align:center" bgcolor="#FFFFFF"><span style="font-size:20px;font-weight:bold;text-decoration:underline;font-style:italic;"><?=$total?></span></td>
                <td colspan="3">&nbsp;</td> 
            </tr>
            <tr>
                <td><strong>Test Id</strong></td>
                <td colspan="6"><strong><?=$test_id?></strong></td>
            </tr>
            <tr>
            </tr><tr>
                <td><strong>Date</strong></td>
                <td><?= $examDate?></td><td><strong>Day</strong></td>
                <td colspan="3"><?= $examDay?></td>
            </tr>
            <tr>
                <td><strong>Shift</strong></td>
                <td><?=$examShift?></td>
                <td><strong>Time</strong></td>
                <td colspan="3"><?php  
                    if($examShift=='Early Morning'){echo "07:00 AM To 10:00 AM";} 
                    if($examShift=='Morning'){echo "11:00 AM To 02:00 PM";}
                    if($examShift=='Afternoon'){echo "03:00 PM To 06:00 PM"; }  ?></td> </tr>
        </tbody>
</table>
<br> 
<br>
<br> 
<br>
            <?php  } 
} ?>