<style type="text/css">
    .break{
        page-break-before: always;
    }
    @page {
      size: auto;
  }
</style>
<?php 

$page_break_count = 0;
$total = 0;
    foreach($exam_centers as $row)
    {
        
        $where= array(
        
            's.new_exam_form!='=>'D' ,
            's.examcentercode'=>$row->examcentercode,
            's.exam_center_id'=>$row->id,
           
         );
         $tag='count(*) as cnt';
         $table="new_exam_form  as e";
         $join_table='student as s';
         $join_on='e.student_id = s.student_id AND s.class_id = e.class_id';
         $count= $this->Common_model->get_count_join_table($tag,$table,$where,$join_table,$join_on);
         $total+=$count[0]->cnt;
   //  if($count[0]->cnt >0)
    //  {
         $page_break = ($page_break_count%6==0) ? 'break' : '';
         $page_break_count++;
        // $sq="select count(e.student_id) as num from $student_table as s join $exam_form_table as e on s.student_id=e.student_id where s.new_exam_center_id='".$row['id']."' and s.forwarded='Y' and s.cls_id IN($cls'0') and e.paper_code IN($papercode'0') and s.new_exam_form in ('Y','N') and  s.new_exam_center_id!='' and admit_card='Y' and s.pattern='NEW'";		
     
?> 
 <br>

  <br>

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






        <!-- <tr>

        <td colspan="2"> 

        <center><h3>Total Answer Sheet Count <?=$count[0]->cnt?></h3></center>

        </td>

        </tr> -->



    </tbody>
</table>

<table style="font-family:Arial, Helvetica, sans-serif;" width="80%" cellspacing="0" cellpadding="8" border="1" align="center">
        <tbody>
            <tr bgcolor="#FFCC99">
                <td><strong>#</strong></td>
                <td><strong>Shift</strong></td>
                <td><strong>Date</strong></td> 
                <td><strong>Main</strong></td>
               
                <td><strong>Total</strong></td>
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
        <?php  $i=1;  foreach($examDate as $edate)
                { 
                    ?>
            <tr>
                <td> <?=$i?> </td>
                <td><?=$edate->exam_shift?></td>
                <td>11-07-2019</td>
                <td>204 </td>
                
                <td>207</td>
                <td>200</td>
                <td>
                0</td>
                <td>900</td>
                <td>50</td>
                <td>
                40</td>
                <td>828</td>
                <td>120</td>

                <!--<td></td>-->

                <td>
                518
                </td>


                <td>	
                2656</td>

                <!--<td></td>
                <td></td>-->
            </tr>
            <?php $i++;} ?>
    </tbody>    
<?php //}
 }
// echo "<div class='text-center mt-5'>".$total."</div>";
?>
