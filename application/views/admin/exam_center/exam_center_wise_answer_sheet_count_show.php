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
		$sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE     `s`.`exam_center_id` = '".$row->id."' AND   course_complete='N'  AND new_exam_form='Y'";
        //((exam_form='Y' AND class_name!='I Year')  OR ( `s`.`session` = 'July 2022' AND `s`.`class_name` = 'I SEM' ) )
         
		//AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2021' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2022' AND `s`.`class_name` = 'I SEM' ))

		$query = $this->db->query($sql);
		$count = $query->result_array();

        $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE  `s`.`exam_center_code`='".$row->examcentercode."'   AND   `s`.`exam_center_id` = '".$row->id."'  AND exam_form='Y' AND `e`.`status`='B'";    
        $query_back = $this->db->query($sql_back);
        $count_backlog = $query_back->result_array();
      
		/****************/
        
      /*  $where= array(
            's.new_exam_form'=>'Y' ,
            's.examcentercode'=>$row->examcentercode,
            's.exam_center_id'=>$row->id,
         );
         $tag='count(*) as cnt';
         $table="new_exam_form  as e";
         $join_table='student as s';
         $join_on='e.student_id = s.student_id AND s.class_id = e.class_id';
         $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);*/
         $resultCount=$count[0]['cnt']+$count_backlog[0]['cnt'];
         $total+=$resultCount;//$count[0]->cnt;
         
   //  if($count[0]->cnt >0)
    //  {
         $page_break = ($page_break_count%6==0) ? 'break' : '';
         $page_break_count++;
     
?> 
<table width="80%" border="1" align="center" class="<?php echo $page_break; ?>">
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