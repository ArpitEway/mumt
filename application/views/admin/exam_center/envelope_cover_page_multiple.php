<style>
    .table th, .table td {
     /* border-top: 1px solid #3F4254;*/
     border-top:none;
}

    .break{
        page-break-before: always;
    }
    @page {
      size: auto;
  }
</style>
<?php 

$page_break_count = 0;  
$page_break = 'break';

foreach($elist as $row)
{
    $arr=array();$tcheck=0;
    foreach($paperData as $paper){ 
        $allElective=0;
       /* $where= array(
            'e.paper_code'=>$paper['paper_code'],
            's.new_exam_form!='=>'D' ,
            's.examcentercode'=>$row->examcentercode,
            's.exam_center_id'=>$row->id,
            's.class_id'=>$paper['class_id'],
            's.course_group_id'=>$paper['course_group_id'],
           
        );
        $tag='count(*) as cnt';
        $table="new_exam_form  as e";
        $join_table='student as s';
        $join_on='e.student_id = s.student_id AND s.class_id = e.class_id';
          $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);*/

        //  echo "<br>1 ".$count[0]->cnt;
         // echo $this->db->last_query();

          $sql="SELECT count(*) as cnt FROM `new_exam_form_report` as `e` JOIN `student_report` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE  `s`.`examcentercode`='".$row->examcentercode."'   AND  `e`.`paper_code` = '".$paper['paper_code']."' AND `s`.`class_id` = '".$paper['class_id']."' AND s.course_group_id= '".$paper['course_group_id']."' AND   `s`.`exam_center_id` = '".$row->id."'  AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2021' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2022' AND `s`.`class_name` = 'I SEM' ));";    
         $query = $this->db->query($sql);
         $count = $query->result_array();
        
               $qu="SELECT count(*) as num FROM `student_report` as s join paper_master as p on s.class_id=p.class_id WHERE  `p`.`paper_code` = '".$paper['paper_code']."' AND `p`.`class_id` = '".$paper['class_id']."'  AND s.course_group_id= '".$paper['course_group_id']."'  AND `s`.`examcentercode`='".$row->examcentercode."' AND   `s`.`exam_center_id` = '".$row->id."'  AND temp_exam_form='N' and `session` = 'July 2021' AND `s`.`class_name` = 'I Year'";
         $query = $this->db->query($qu);
         $all = $query->result_array();
        
         $allElective= $all[0]['num'];
          
          if($paper['class_id']==104 && $paper['ce']=='elective' && ( $allElective>0)){
            $allElective=round(($all[0]['num']*60)/100); 
            
         }
        $count[0]['cnt'];
        $tcheck+= $allElective + $count[0]['cnt'];
         $countData=array("fill"=>$count[0]['cnt'],"all"=>$allElective,"class_id"=>$paper['class_id'],"paper_code"=>$paper['paper_code'],"course_name"=>$paper['course_name'] ,"exam_date"=>  date("d-m-Y", strtotime($paper['exam_date'])),"exam_day"=> $paper['exam_day'],"test_id"=>$paper['test_id'],  "exam_shift"=>$paper['exam_shift'],"paper_name"=>$paper['paper_name']);
         //$arr[]=$count[0]['cnt']+ $allElective;
         $arr[]= $countData;
    }
  
    if($tcheck >0)
     {  
    //  echo "<br>Hello". $tcheck;
      
?>

<table style="width:90%;text-align:left;font-size:15px" class="table  table-hover table-striped my-10 <?php echo $page_break; ?>" border="1" align="center">
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
                <?php $total=0;$i=0;
               $examShift=$examDay=$examDate="";
                foreach($arr as $paper){ $i++;    
                    $this->db->select('*');
                    $this->db->from('class_master');
                    $this->db->where('class_master.id',$paper['class_id']);
                    $classMaster = $this->db->get()->result();
                    
                    if(($paper['fill'] >0) || ($paper['all'] >0) )
                    { 
                    ?>
                <tr><td ><?=$paper['course_name'] ?></td><td><?= $classMaster[0]->class_name ?></td><td><?=$paper['paper_name'] ?>&nbsp; (<?=$paper['paper_code'] ?>)</td><td style="text-align:center"><?php echo $paper['all']+ $paper['fill']; ?></td><td>&nbsp;</td><td></td></tr>
               
                <?php 
              $examDate=  $paper['exam_date'];
              $examDay= $paper['exam_day']; 
              $test_id=$paper['test_id'];
              $examShift=$paper['exam_shift'];
              $total+= $paper['all'] + $paper['fill'];
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
                   // if($examShift=='Early Morning'){echo "07:00 AM To 10:00 AM";} 
                    if($examShift=='Morning'){echo "11:00 AM To 02:00 PM";}
                    if($examShift=='Evening'){echo "03:00 PM To 06:00 PM"; }  ?></td> </tr>
        </tbody>
</table>
            <?php  }
            $page_break = ($page_break_count%1==0) ? 'break' : '';
            $page_break_count++;
} ?>