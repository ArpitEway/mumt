<?php
//$counter=1;
foreach($papers as $pap)
{ 
   $total=0;
   $where= array(
      'cls.id'=>$pap->class_id,
      'cg.id'=>$pap->course_group_id ,
      
   );
   $tag='*';
   $table="class_master  as cls";
   $join_table='course_group as cg';
   $join_on='cls.course_group_id = cg.id';
   $courseData= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);
         ?>
<p class="break"> &nbsp;&nbsp;&nbsp; </p>
<div style="text-align:center;">
<table width="80%" border="1" align="center">
   <tbody><tr>
      <th width="25%" scope="row">
         <div align="left">Exam Center Code </div>
      </th>
      <td width="75%" align="left"><?=$exam_centers[0]->examcentercode; ?></td>
   </tr>
   <tr>
      <th scope="row">
         <div align="left">Exam Center Name  </div>
      </th>
      <td align="left"><?=$exam_centers[0]->schoolcollegename; ?></td>
   </tr>
   <tr>
      <th scope="row">
         <div align="left">Address</div>
      </th>
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
      <td align="left"><?= $pap->exam_date?></td>
   </tr>
   <tr>
      <th scope="row">
         <div align="left">Day</div>
      </th>
      <td align="left"><?= $pap->exam_day?></td>
   </tr>
</tbody></table>
<span>List of Paper<b></b> </span>
<table width="80%" align="center" cellspacing="0" cellpadding="8" border="1">
   <tbody><tr bgcolor="#FFCC99">
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
	
		$this->db->where('exam_date',$pap->exam_date);
      $this->db->order_by('exam_shift','Desc');
     // $this->db->where('exam_shift',$pap->exam_shift);	
		$paperData = $this->db->get()->result();
      foreach($paperData as $paper)
      {
         $where= array(
            'e.paper_code'=>$paper->paper_code,
            's.new_exam_form!='=>'D' ,
            's.class_id'=>$paper->class_id,
            's.course_group_id'=>$paper->course_group_id,
           
         );
         $tag='count(*) as cnt';
         $table="new_exam_form  as e";
         $join_table='student as s';
         $join_on='e.student_id = s.student_id AND s.class_id = e.class_id';
         $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);

         // New Query start 
         $sql="SELECT count(*) as cnt FROM `new_exam_form_report` as `e` JOIN `student_report` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` WHERE  `s`.`exam_center_id`='".$exam_center."'   AND  `e`.`paper_code` = '".$paper->paper_code."' AND `s`.`class_id` = '".$paper->class_id."' AND   `s`.`exam_center_id` = '".$row->id."'  AND (new_exam_form!='D' OR ( `s`.`session` = 'July 2021' AND `s`.`class_name` = 'I Year' ) OR ( `s`.`session` = 'Jan 2022' AND `s`.`class_name` = 'I SEM' ));";    
         $query = $this->db->query($sql);
         $count = $query->result_array();
        
             $qu="SELECT count(*) as num FROM `student_report` as s join paper_master as p on s.class_id=p.class_id WHERE  `p`.`paper_code` = '".$paper->paper_code."' AND `p`.`class_id` = '".$paper->class_id."' AND `s`.`exam_center_id`='".$exam_center."'   AND temp_exam_form='N' and `session` = 'July 2021' AND `s`.`class_name` = 'I Year'";
         $query = $this->db->query($qu);
         $all = $query->result_array();
        
          $allElective= $all[0]['num'];
          
         
        
         if($paper->class_id==104 && $paper->ce=='elective' && ( $allElective>0)){
            $allElective=round(($all[0]['num']*60)/100); 
            
         }
         //New Query end 
       
         ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#F0F0F0"'; ?>>
      <td> <?=$i ?> </td>
      <!--<td></td>-->
      <!--<td></td>-->
      <td>
         <div align="left"><?=$courseData[0]->course_name?></div>
      </td>
      <td align="center">
         <div align="center">
         <?=$courseData[0]->class_name?>        </div>
      </td>
      <td><?= $paper->test_id?></td>
      <td>
         <div align="left"><?= $paper->paper_code?></div>
      </td>
      <td align="left">
         <div align="left"><?= $paper->paper_name?></div>
      </td>
            <td>
         <div align="left">
         <?= $paper->exam_shift?>      </div></td>
      <td><?php echo $count[0]['cnt']+$allElective; ?> </td>
   </tr>
   <?php $i++; $total+= $count[0]['cnt']+$allElective; } ?>
      
  
   </tbody></table>
<h3>Total Student Count <?=$total?></h3>
<?php } ?>