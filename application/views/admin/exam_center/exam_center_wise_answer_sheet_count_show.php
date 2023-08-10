<style type="text/css">
    .break{
        page-break-before: always;
    }
    @page {
      size: auto;
  }
  @media print {
      .offcanvas-footer.text-center.p-3{
         display: none;
      }
   }
</style>
<?php 

$page_break_count = 0;
$total = 0;
    foreach($exam_centers as $row)
    {
        /*****************/
		$sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE     `s`.`exam_center_id` = '".$row->id."' AND   course_complete='N'  AND  paper_type='theory' AND new_exam_form in ('Y','N')  ";

        //(new_exam_form!='D' OR ( `s`.`session` = 'July 2022' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2023' AND `s`.`class_name` = 'I SEM' )  OR ( `s`.`exam_form` = 'Y' AND `s`.`class_name` = 'III SEM' ) OR ( `s`.`exam_form` = 'Y' AND `s`.`class_name` = 'I SEM' ))

		$query = $this->db->query($sql);
		$count = $query->result_array();
       /* $sql_rem="SELECT count(*) as cnt FROM `paper_master` as p join `student` as s on s.class_id=p.class_id and s.`course_group_id`=p.`course_group_id` WHERE s.new_exam_form!='D' and s.temp_exam_form='N' and p.type='theory'  AND `s`.`exam_center_id` = '".$row->id."' AND   course_complete='N'";
        $query_rem = $this->db->query($sql_rem);
		$count_rem = $query_rem->result_array();*/
        $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` AND s.id=e.backlog_student_id  WHERE  `s`.`exam_center_code`='".$row->examcentercode."'   AND   `s`.`exam_center_id` = '".$row->id."'  AND exam_form in ('Y','N') and s.exam_year='June 2023' AND `e`.`status`='B'";    
        $query_back = $this->db->query($sql_back);
        $count_backlog = $query_back->result_array();
      
		/****************/
        
     
         $resultCount=$count[0]['cnt']+$count_backlog[0]['cnt'];//$count_rem[0]['cnt'];
         $total+=$resultCount;//$count[0]->cnt;
         
   //  if($count[0]->cnt >0)
    //  {
         $page_break = ($page_break_count%6==0) ? 'break' : '';
         $page_break_count++;
     
?> 
<table width="80%" border="1" align="center" cellpadding="3" style="margin-top: 15px;margin-bottom: 15px;" class="<?php echo $page_break; ?>">
   <tbody>
       <tr>
            <td width="239"><div class="style1" align="left">Exam Center Code</div></td>
            <td width="511"><div class="style2" align="left"><?= $row->examcentercode?></div></td>
        </tr>
        <tr>
            <td><div class="style1" align="left">Exam Center Name</div></td>
            <td><div class="style2" align="left"><?= $row->schoolcollegename?></div></td>
        </tr>
        <tr>
            <td><div class="style1" align="left">Address</div></td>
            <td><div class="style2" align="left"><?= $row->examcenteraddress?></div></td>
        </tr>
        <tr>
            <td><div class="style1" align="left">City</div></td>
            <td><div class="style2" align="left"><?= $row->city?></div></td>
        </tr>
        <tr>
            <td><div class="style1" align="left">Exam Superintandent</div></td>
            <td><div class="style2" align="left"><?= $row->superintendent?></div></td>
        </tr>
        <tr>
            <td><div class="style1" align="left">Mob No.</div></td>
            <td><div class="style2" align="left"><?= $row->phonenumber?></div></td>
        </tr>
        <tr>
        <td colspan="2"> 
        <center><h3>Total Answer Sheet Count <?=$resultCount?></h3></center>
        </td>
        </tr>
    </tbody>
</table>
<?php //}
$data['study_center_id'] = $resultCount;
$update = $this->Common_model->updateRecordByConditions('exam_center', array('id'=>$row->id,'examcentercode'=>$row->examcentercode) ,$data);
 }
echo "<div class='text-center mt-5'>".$total."</div>";

?>