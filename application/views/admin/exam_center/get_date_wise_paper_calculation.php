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

$page_break_count = 1;


   ?>

   
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
            <td>
               <div align="left"><span style="font-weight: bold">Status</span></div>
            </td>
            <td><span style="font-weight: bold">Student Count</span></td>
         </tr>
         <?php
      $i=1;
      $this->db->select('*');
      $this->db->from('paper_master');
     
       $edate=date("Y-m-d", strtotime($exam_date));
      
      $this->db->where('exam_date',$edate);
      $this->db->where('exam_date!=',""); 
      $this->db->where('exam_date!=',"0000-00-00");   
      $this->db->where('exam_shift',$shift); 
      if($category=='Uniqe'){
        $this->db->group_by('test_id');
      }
      $this->db->order_by('exam_date','Asc');
      $this->db->order_by('exam_shift','Desc');
     
        $this->db->order_by('test_id','Asc');   
     
      

      $paperData = $this->db->get()->result();
      //echo $this->db->last_query();die;
      $total=0;
      foreach($paperData as $paper)
      { 

       $where="";
       if($category=='Uniqe'){
        $where="p.test_id = '".$paper->test_id."'";
       }
       else{
        $where=" `e`.`paper_code` = '".$paper->paper_code."' AND `s`.`class_id` = '".$paper->class_id."'";
       }
       
         // New Query start 
          $sql="SELECT count(*) as cnt FROM `new_exam_form` as `e` JOIN `student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` AND   `s`.`course_group_id` = `e`.`course_group_id`  join paper_master as p on s.class_id=p.class_id and s.course_group_id=p.course_group_id  and `e`.`paper_code` = p.paper_code WHERE   ".$where."  and new_exam_form in ('Y')";
         
        
         $query = $this->db->query($sql);
         $main_count = $query->result_array();

         $sql_backlog="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` AND   `s`.`course_group_id` = `e`.`course_group_id`  join paper_master as p on s.class_id=p.class_id and s.course_group_id=p.course_group_id  and `e`.`paper_code` = p.paper_code WHERE   ".$where." and exam_year='Dec 2023' and exam_form in ('Y') and `e`.status= 'B'";
         
        
         $backlog_query = $this->db->query($sql_backlog);
         $backlog_count = $backlog_query->result_array();
         $student_count = $main_count[0]['cnt'] + $backlog_count[0]['cnt'];

         
         
         $allElective=0;

        
         //New Query end 
       //  if(($student_count >0)  )
        // { 
            ?>
            <tr <?php if($i%2==0) echo 'bgcolor="#F0F0F0"'; ?>>
               <td> <?=$i ?> </td>
               
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
               <td><?= $paper->test_id?></td>
               <td>
                  <div align="left"><?= $paper->paper_code?></div>
               </td>
               <td align="left">
                  <div align="left"><?= $paper->paper_name?></div>
               </td>
               <td><div align="left"><?= $paper->exam_shift?></div></td>
              <td> <?php //http://162.144.38.91/~mmyvvdde/main/examcenter/paper/1050.pdf
              $pdf = 'http://162.144.38.91/~mmyvvdde/main/examcenter/paper/'.$paper->test_id.'.pdf';
              
              

// Initialize cURL
$ch = curl_init($pdf);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check the response code
if($responseCode == 200){
    ?>
     <a href="<?php echo 'http://162.144.38.91/~mmyvvdde/main/examcenter/paper/'.$paper->test_id.'.pdf'?>">Available</a>
     <?php
}else{
    echo "Not Available";
}
               
                ?></td>
               
               <td style="text-align:center;"><?php echo $student_count; ?> </td>
            </tr>
            <?php 
               $i++;  $total+= $student_count;
             //  }  
         } ?>
      </tbody>
   </table>
<h3 class="my-10" style="text-align:center;">Total Student Count <?=$total?></h3>
