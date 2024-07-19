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


   //  if($count[0]->cnt >0)
    //  {
   $page_break = ($page_break_count%6==0) ? 'break' : '';
   $page_break_count++;
        // $sq="select count(e.student_id) as num from $student_table as s join $exam_form_table as e on s.student_id=e.student_id where s.new_exam_center_id='".$row['id']."' and s.forwarded='Y' and s.cls_id IN($cls'0') and e.paper_code IN($papercode'0') and s.new_exam_form in ('Y','N') and  s.new_exam_center_id!='' and admit_card='Y' and s.pattern='NEW'";		

   ?> 
   <br>

   <br>

   <table width="85%" border="1" align="center" class="<?php echo $page_break; ?>">

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






        <!-- <tr>

        <td colspan="2"> 

        <center><h3>Total Answer Sheet Count <?=$count[0]->cnt?></h3></center>

        </td>

    </tr> -->



</tbody>
</table>

<table style="font-family:Arial, Helvetica, sans-serif;text-align:center;" width="85%" cellspacing="0" cellpadding="8" border="1" align="center">
    <tbody>
        <tr bgcolor="#FFCC99">
            <td><strong>#</strong></td>
            <td><strong>Shift</strong></td>
            <td><strong>Date</strong></td> 
            <td><strong> Main </strong></td>
            <td><strong> Backlog </strong></td>
            <td><strong> Total</strong></td>
            <td><strong> केन्द्राध्यक्ष </strong></td>
            <td><strong> सहायक केन्द्राध्यक्ष  </strong></td>
            <td><strong>वीक्षक </strong></td>
            <td><strong>क्लर्क </strong></td>
            <td><strong>आदेशपाल </strong></td>
            <td><strong>भवन व्यय</strong></td>
            <td><strong>जलपान </strong></td>
            <!--<td><strong>परीक्षा सामग्री (स्टेशनरी आदी )</strong></td>-->
            <td><strong>Postal Charge</strong></td>
            <td><strong>योग</strong></td>
        </tr>
        <?php  $i=1;$prevdate=""; $max_count=array(); foreach($examDate as $edate)
        { 
            $where = array('type' => 'theory','exam_date'=>$edate->exam_date,'exam_shift'=>$edate->exam_shift );
            $this->db->where_not_in('paper_no_for_time_table', array('1B','2B'));
            $papers_all = $this->Common_model->get_record('paper_master','*',$where);
            $papersid = array_column($papers_all, 'id');
            // echo "<br>";
            // echo $this->db->last_query(); 
            // echo "<br>";
            $this->db->select('count(*) as cnt');
            $this->db->from('student as s');
            $this->db->join('new_exam_form  as e', 'e.student_id = s.student_id AND s.class_id = e.class_id');
            $this->db->where('s.new_exam_form',"Y");
            // $this->db->where('s.notification_no',0);	
            $this->db->where('s.examcentercode',$row->examcentercode);	
            $this->db->where('s.exam_center_id',$row->id);	
            $this->db->where_in('paper_id', $papersid );


            $count = $this->db->get()->result();

            $exam_date=$edate->exam_date;

         
            /************/
            $paperscode = array_column($papers_all, 'paper_code');
            $paperscode=implode("','",$paperscode);

             $sql_back="SELECT count(*) as cnt FROM `backlog_exam_form` as `e` JOIN `backlog_student` as `s` ON `e`.`student_id` = `s`.`student_id` AND   `s`.`class_id` = `e`.`class_id` and s.id=e.backlog_student_id WHERE  `s`.`exam_center_code`='".$row->examcentercode."'   AND   `s`.`exam_center_id` = '".$row->id."'  AND exam_form='Y' AND `e`.`status`='B'  AND `e`.`paper_code` in ('".$paperscode."') and s.exam_year='June 2024'  ";  
          
        
            $query_back = $this->db->query($sql_back);
            $count_backlog = $query_back->result_array();
           // echo $this->db->last_query();  die;
            $allStudentCount= $count[0]->cnt+$count_backlog[0]['cnt'];
            /***********/
            array_push($max_count,$allStudentCount);
            if($allStudentCount>0)
            {
                ?>
                <tr>
                    <td> <?=$i?> </td>
                    <td><?=$edate->exam_shift?></td>
                    <td><?=$edate->exam_date?></td>
                    <td><?php   echo $count[0]->cnt ;  ?> </td>
                    <td><?php   echo $count_backlog[0]['cnt'] ;  ?> </td>
                    <td><?php   echo  $t= $allStudentCount; ?> </td>
                    <td>200</td>
                    <td>
                        <?php
                        $sahayak=0; 
                if($t > 300) { $a=($t-300)/300; $b=ceil($a); $sahayak=$b*150; } //else { $sahayak=0; } 
                echo $sahayak;?>
            </td>
            <td><?php $a=$t/25;  $b1=ceil($a); $vik=$b1*100; echo $vik; ?></td>
            <td><?php echo $clerk=50; ?></td>
            <td>
                <?php echo $adesh=50; ?></td>
                <td><?php echo $bhavan=4*$t ;?></td>
                <td><?php echo $jal=10*($b+$b1+3) ;?></td>

                <!--<td></td>-->

                <td>
                  <?php  $portal=0;

                  $prevdate;
                  $exam_date;
                  if( $prevdate != $exam_date){

                    if($t<81)
                    {
                        echo $portal=200;
                    }
                    else
                    {
                        echo $portal=ceil($t*2.5);
                    }
                }
                $prevdate=$exam_date;
                ?>
            </td>


            <td>	
                <?php 
                $tt=$kendra+$sahayak+$vik+$clerk+$adesh+$bhavan+$jal+$other+$portal; echo $tt;
                $tot=$tot+$tt;
            ?></td>

                <!--<td></td>
                    <td></td>-->
                </tr>
                <?php $i++; }} ?>
            </tr>
            <tr>
                <td colspan="5" align="right" ><!-- Max Student Count (in one shift) --> </td>
                <td><?php //echo $mstud=max($max_count); ?></td>
                <td colspan="8" align="right">महायोग</td>
                <td><?php echo $tot; 
                $where = array('examcentercode' => $row->examcentercode,'id'=> $row->id);
                $data=array('billing_amount'=>$tot);
                $papers = $this->Common_model->updateRecordByConditions('exam_center',$where,$data);
            ?></td>
        </tr>
    </tbody>    
<?php //}
}
// echo "<div class='text-center mt-5'>".$total."</div>";
?>
