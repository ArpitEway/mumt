<style type="text/css">
   .offcanvas-footer{
      display: none;
   }
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

$page_break_count = 1;

foreach($papers as $pap)
{
   ?>
<div id="divid_<?=$pap->id ?>">
   <table width="95%" border="1" align="center" class="break">
      <tbody>
         <tr>
            <th width="25%" scope="row">
               <div align="left">Exam Center Code </div>
            </th>
            <td width="75%" align="left"><?=$exam_centers[0]->examcentercode; ?></td>
         </tr>
         <tr>
            <th scope="row"><div align="left">Exam Center Name  </div></th>
            <td align="left"><?=$exam_centers[0]->schoolcollegename; ?></td>
         </tr>
         <tr>
            <th scope="row"><div align="left">Address</div></th>
            <td align="left"><?=$exam_centers[0]->examcenteraddress; ?></td>
         </tr>
         <tr>
            <th scope="row">
               <div align="left">City</div>
            </th>
            <td align="left"><?=$exam_centers[0]->city; ?></td>
         </tr>
         <tr>
            <th scope="row">
               <div align="left">Exam Superintandent</div>
            </th>
            <td align="left"><?=$exam_centers[0]->superintendent; ?> </td>
         </tr>
         <tr>
            <th scope="row">
               <div align="left">Mobile No. </div>
            </th>
            <td align="left"><?=$exam_centers[0]->phonenumber; ?></td>
         </tr>
         <tr>
            <th scope="row">
               <div align="left">Date </div>
            </th>
            <td align="left"><?php  echo  date("d-m-Y", strtotime($pap->pvt_exam_date))  ?></td>
         </tr>
         <tr>
            <th scope="row">
               <div align="left">Day</div>
            </th>
            <td align="left"><?= $pap->pvt_exam_day?></td>
         </tr>
      </tbody>
   </table>
   <p style="text-align:center;"><b>List of Paper</b> </p>
   <table width="95%" align="center" cellspacing="0" cellpadding="8" border="1">
      <tbody>
         <tr bgcolor="#FFCC99">
            <td><span style="font-weight: bold">#</span></td>
         <!--<td>Exam Center Code</td>
            <td>Exam Center Name</td>-->
            <td>
               <div align="left"><span style="font-weight: bold">Course Name</span></div>
            </td>
            <td>
               <div align="center"><span style="font-weight: bold">Year/Sem</span></div>
            </td>
            <td>
               <div align="center"><span style="font-weight: bold">Test Id</span></div>
            </td>
            <td>
               <div align="left"><span style="font-weight: bold">Paper Code</span></div>
            </td>
            <td>
               <div align="left"><span style="font-weight: bold">Paper Name</span></div>
            </td>
            <td>
               <div align="left"><span style="font-weight: bold">Shift</span></div>
            </td>
         <!--<td>Date</td>
            <td>Day</td>-->
            <td><span style="font-weight: bold">Student Count</span></td>
         </tr>
         <?php
      $i=1;
      $this->db->select('*');
      $this->db->from('paper_master');
	   //if($exam_date!='All')
      $this->db->where('pvt_exam_date',$pap->pvt_exam_date);
      // $this->db->where('exam_date!=',"");	
      $this->db->where('pvt_exam_date!=',"0000-00-00");	
      $this->db->where('pvt_exam_shift',$pap->pvt_exam_shift);	

      // $this->db->where_not_in('class_id',array(101,104,107,110,113,116,119,125,128,131,134,137,140,143,146,149,162,163,164,165,168,169,170,171,173,174,175,176,177,178,179,180,183,185,187,189,191,273,274,283,285,287,289,291,293,295,297,300,310));

      //  $this->db->where_in('class_id',array(102,105,108,111,117,120,126,129,132,135,284,286,288,290,292,294,296,298,311) );  

      $this->db->order_by('pvt_exam_date','Asc');
      $this->db->order_by('pvt_exam_shift','Desc');

      $paperData = $this->db->get()->result();
      //echo $this->db->last_query();die;
      $total=0;
      foreach($paperData as $paper)
      { 

        
         // New Query start 
           $sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE  `s`.`exam_center_id`='".$exam_center."'   AND  `e`.`paper_code` = '".$paper->paper_code."' AND `s`.`class_id` = '".$paper->class_id."' AND   `s`.`exam_center_id` = '".$exam_center."'  AND s.class_id IN (104, 107, 134) AND s.university_mode = 'PVT' and (new_exam_form!='D' OR ( `s`.`session` = 'July 2024' AND `s`.`class_name` = 'I Year' )) ";
         
         // ( `s`.`session` = 'July 2023' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2024' AND `s`.`class_name` = 'I SEM' )

          // AND `s`.payment_status='Y'

         //  $sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE  `s`.`exam_center_id`='".$exam_center."'   AND  `e`.`paper_code` = '".$paper->paper_code."' AND `s`.`class_id` = '".$paper->class_id."' AND   `s`.`exam_center_id` = '".$exam_center."' AND new_exam_form ='Y' AND (
         //     (s.class_id IN (104, 107, 134) AND s.university_mode = 'REG') OR
         //     (s.class_id NOT IN (104, 107, 134) AND s.university_mode IN ('REG', 'PVT'))
         // )";

        
         $query = $this->db->query($sql);
         $count = $query->result_array();
         // $this->Common_model->last_query();

          $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` AND s.id=e.backlog_student_id WHERE  `s`.`exam_center_id`='".$exam_center."'   AND  `e`.`paper_code` = '".$paper->paper_code."' AND `s`.`class_id` = '".$paper->class_id."'  AND exam_form!='D' AND `e`.`status`='B'  AND s.exam_year='June 2025' AND s.class_id IN (104, 107, 134) AND s.mode = 'PVT'";
            
         //AND   `s`.`exam_center_code` = '".$exam_center."'
         
        //   $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` AND s.id=e.backlog_student_id WHERE  `s`.`exam_center_id`='".$exam_center."'   AND  `e`.`paper_code` = '".$paper->paper_code."' AND `s`.`class_id` = '".$paper->class_id."' AND   `s`.`exam_center_id` = '".$exam_center."' AND exam_form='Y' AND `e`.`status`='B'  AND s.exam_year='June 2025' AND (
        //     (s.class_id IN (104, 107, 134) AND s.mode = 'REG') OR
        //     (s.class_id NOT IN (104, 107, 134) AND s.mode IN ('REG', 'PVT'))
        // )";

         $query_back = $this->db->query($sql_back);
         $count_backlog = $query_back->result_array();
         // $this->Common_model->last_query();

        // $qu="SELECT count(*) as num FROM `student` as s join paper_master as p on s.class_id=p.class_id WHERE  `p`.`paper_code` = '".$paper->paper_code."'  AND `p`.`class_id` = '".$paper->class_id."' AND `s`.`exam_center_id`='".$exam_center."'   AND temp_exam_form='N' ";

         //  AND `s`.payment_status='Y'
         //  and `session` = 'July 2022' AND `s`.`class_name` = 'I Year' 
         // new_exam_form in ('Y','N') AND `p`.`id` = '".$paper->id."' and new_exam_form!='D'

        // $query = $this->db->query($qu);
        //  $all = $query->result_array();

        //  $allElective= $all[0]['num'];
         
          $allElective=0;

         if($paper->class_id==104 && $paper->ce=='elective' && ( $allElective>0)){
            $allElective=round(($all[0]['num']*60)/100); 
            
         }
         //New Query end 
         if(($count[0]['cnt'] >0) || ($allElective >0) || $count_backlog[0]['cnt']>0)
         { 
            ?>
            <tr <?php if($i%2==0) echo 'bgcolor="#F0F0F0"'; ?>>
               <td> <?=$i ?> </td>
               <!--<td></td>-->
               <!--<td></td>-->
               <td>
                  <div align="left"><?=$paper->course_name?></div>
               </td>
               <td align="center">
                  <div align="center">
                     <?php 
                     echo $class_name = $this->Common_model->getClassNameByClassId($paper->class_id);
                     ?>
                  </div>
               </td>
               <td><?= $paper->pvt_test_id?></td>
               <td>
                  <div align="left"><?= $paper->paper_code?></div>
               </td>
               <td align="left">
                  <div align="left"><?= $paper->paper_name?></div>
               </td>
               <td><div align="left"><?= $paper->pvt_exam_shift?></div></td>
               <td style="text-align:center;"><?php 
               // echo $count[0]['cnt']." ".$allElective." ".$count_backlog[0]['cnt'];
               echo $count[0]['cnt']+$allElective+$count_backlog[0]['cnt']; 
             // echo "<br>". $sql_back;
              ?> </td>
            </tr>
            <?php 
               $i++;  $total+= $count[0]['cnt']+$allElective+$count_backlog[0]['cnt'];
               }  
         } ?>
      </tbody>
   </table>
<h3 class="my-10" style="text-align:center;">Total Student Count <?=$total?></h3>
</div>
<?php if ($total==0): ?>
<script type="text/javascript">
   $("#divid_<?=$pap->id ?>").empty();
</script>
<?php endif ?>
<?php } ?>
