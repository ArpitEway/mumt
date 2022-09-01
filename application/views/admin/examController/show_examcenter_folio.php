<?php 
$student=array(); ?>

<style>
    h4{
        line-height:1px;font-size:16px;
    }
    .break{
        page-break-before: always;
    }
    table {
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
        max-width: 100%;
    }
    .tdheight{
        line-height:21px;
    }
    td{
        line-height:42px;
    }
   .font-19{
        font-size: 19px;
    }
</style>
<?php 
$pageCounter=30;
$totalExamcenterCounter=count($exam_center_id);
$examcenterCounter=0;
$words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
    foreach($exam_center_id as $examcenter){  $examcenterCounter++;?> 
        <div class="row form-group col-md-12" style="padding-top: 10px;" >
            <?php for($two=0;$two<2;$two++){  $rowCounter=0;?>
                <div class=" form-group col-md-6">
                    <table   class="form-group col-md-12 mt-20" >
                        <tr><td colspan="2" class="tdheight"><h4 align="center">Maharishi Mahesh Yogi Vedic Vishwavidyalaya</h4></td></tr>
                        <tr><td colspan="2" class="tdheight"><h4 align="center">  </h4></td></tr>
                        <tr><td colspan="2" class="tdheight"><h4 align="center">Madhya Pradesh</h4></td></tr>
                        <tr><td colspan="2" class="tdheight"><h4 align="center">  </h4></td></tr>
                        <tr><td colspan="2" class="tdheight"><h4 align="center"><?php if($two==0) echo '(FOIL)'; else echo "(COUNTERFOIL)"; ?></h4></td></tr><tr></tr>
                        <tr><td colspan="2" class="tdheight"><strong>Name of Exam:</strong>&nbsp;<?php echo $examname;?></td></tr>
                        <tr><td colspan="2" class="tdheight"><strong>Year / Sem:</strong>&nbsp;<?php echo $class_name;?></td></tr>
                        <tr>
                            <td style="text-align:left;" colspan="2" class="tdheight"><strong>Exam Session:</strong>&nbsp;<?php echo 'August 2022';?></td></tr>
                            <tr><td colspan="2" class="tdheight"><strong>Subject : </strong>&nbsp;&nbsp;<?php  echo $paper[0]->paper_name;?></td></tr>
                            <!-- <tr><td style="text-align:left;" class="tdheight"><strong>Center Code :</strong>&nbsp;<?php echo $exam_center_code;?></td><td class="tdheight"><strong>Q.Paper</strong>&nbsp;<?php echo $paper_code ?></td></tr>-->
                            <tr><td class="tdheight"><strong>Date of Exam :</strong>&nbsp;<?php echo $edate=date("d-m-Y", strtotime($paper[0]->exam_date));  ?></td><td style="text-align:left;" class="tdheight"><strong>Max.Marks :</strong>&nbsp;<?php echo $paper[0]->max_theory_marks;?></td></tr>
                            <tr><td class="tdheight"><strong>Examcenter Code:</strong>&nbsp;<?php echo $detail[$examcenter][0]->examcentercode;  ?></td><td style="text-align:left;" class="tdheight"><strong>Q.Paper :</strong>&nbsp;<?php echo $paper[0]->paper_no;?></td></tr>
                        </table>
                        <table   border="1" class="form-group col-md-12">
                            <tr style="text-align:center;">
                                <td rowspan="2"  width="77"><strong>En.No. </strong></td>
                                <td rowspan="2"  width="50"><strong>Roll No.</strong></td>
                                <td colspan="2"><strong>Marks Awarded</strong></td></tr>
                                <tr style="text-align:center;">
                                    <td width="61"><strong>In Fig.</strong></td><td width="344"><strong>In Words</strong></td>
                                </tr>
                                <?php 
                                foreach($students[$examcenter] as $student){ $rowCounter++;

                                  ?> 
                                  <tr>
                                    <td  style="text-align:center;"><?php  echo $student->enrollment_no; ?></td>
                                    <td style="text-align:center;padding: 0px 3px 0px 3px;"><?php echo $student->roll_no;;?></td>

                                    <td style='text-align:center;'><?php //echo $student->total_marks; ?></td>

                                    <td class="font-19">&nbsp;&nbsp;<?php //echo $result; ?></td>
                                </tr>
                                <?php 
                                if($rowCounter%$pageCounter==0){
                                    ?>
                                </table>
                                <table   class="form-group col-md-12">
                                    <tr><td colspan="2" style="text-align:left"><strong>Date :</strong> ____/_____/_________</td></tr>
                                    <tr><td colspan="2" style="text-align:left"><strong>Examiner's Code No :</strong> </td></tr>
                                    <tr>
                                        <td style="text-align:left">&nbsp;</td> 
                                        <td style="text-align:right">&nbsp;</td></tr>
                                        <tr><td colspan="2" style="text-align:right"><strong>(Examiner's Signature)</strong></td></tr>
                                        <tr><td style="text-align:left;line-height: 20px;"><strong>&nbsp;</strong></td>
                                            <td style="text-align:right;line-height: 20px;"><?php echo $detail[$examcenter][0]->superintendent; ?></td></tr>
                                            <tr><td style="text-align:center" colspan="2"><p style="font-size:12px"><strong>Note:Please see instruction being issued separately</strong></p></td></tr>
                                        </table> 
                                        <p class="break"></p>

                                        <!---Header Part--->
                                        <table   class="form-group col-md-12 mt-20" >
                                            <tr><td colspan="2" class="tdheight"><h4 align="center">Maharishi Mahesh Yogi Vedic Vishwavidyalaya</h4></td></tr>
                                            <tr><td colspan="2" class="tdheight"><h4 align="center">  </h4></td></tr>
                                            <tr><td colspan="2" class="tdheight"><h4 align="center">Madhya Pradesh</h4></td></tr>
                                            <tr><td colspan="2" class="tdheight"><h4 align="center">  </h4></td></tr>
                                            <tr><td colspan="2" class="tdheight"><h4 align="center"><?php if($two==0) echo '(FOIL)'; else echo "(COUNTERFOIL)"; ?></h4></td></tr><tr></tr>
                                            <tr><td colspan="2" class="tdheight"><strong>Name of Exam:</strong>&nbsp;<?php echo $examname;?></td></tr>
                                            <tr><td colspan="2" class="tdheight"><strong>Year / Sem:</strong>&nbsp;<?php echo $class_name;?></td></tr>
                                            <tr>
                                                <td style="text-align:left;" colspan="2" class="tdheight"><strong>Exam Session:</strong>&nbsp;<?php echo 'August 2022';?></td></tr>
                                                <tr><td colspan="2" class="tdheight"><strong>Subject : </strong>&nbsp;&nbsp;<?php  echo $paper[0]->paper_name;?></td></tr>
                                                <!-- <tr><td style="text-align:left;" class="tdheight"><strong>Center Code :</strong>&nbsp;<?php echo $exam_center_code;?></td><td class="tdheight"><strong>Q.Paper</strong>&nbsp;<?php echo $paper_code ?></td></tr>-->
                                                <tr><td class="tdheight"><strong>Date of Exam :</strong>&nbsp;<?php echo $edate=date("d-m-Y", strtotime($paper[0]->exam_date));  ?></td><td style="text-align:left;" class="tdheight"><strong>Max.Marks :</strong>&nbsp;<?php echo $paper[0]->max_theory_marks;?></td></tr>
                                                <tr><td class="tdheight"><strong>Examcenter Code:</strong>&nbsp;<?php echo $detail[$examcenter][0]->examcentercode;  ?></td><td style="text-align:left;" class="tdheight"><strong>Q.Paper :</strong>&nbsp;<?php echo $paper[0]->paper_no;?></td></tr>
                                            </table>
                                            <table   border="1" class="form-group col-md-12">
                                                <tr style="text-align:center;">
                                                    <td rowspan="2"  width="77"><strong>En.No. </strong></td>
                                                    <td rowspan="2"  width="50"><strong>Roll No.</strong></td>
                                                    <td colspan="2"><strong>Marks Awarded</strong></td></tr>
                                                    <tr style="text-align:center;">
                                                        <td width="61"><strong>In Fig.</strong></td><td width="344"><strong>In Words</strong></td>
                                                    </tr>

                                                    <?php
                                                }
                                            } ?>
                                        </table>
                                        <table   class="form-group col-md-12">
                                            <tr><td colspan="2" style="text-align:left"><strong>Date :</strong> ____/_____/_________</td></tr>
                                            <tr><td colspan="2" style="text-align:left"><strong>Examiner's Code No :</strong> </td></tr>
                                            <tr>
                                                <td style="text-align:left">&nbsp;</td> 
                                                <td style="text-align:right">&nbsp;</td></tr>
                                                <tr><td colspan="2" style="text-align:right"><strong>(Examiner's Signature)</strong></td></tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:left;line-height: 20px;"><strong>Full Name :</strong></td></tr>
                                                    <tr><td style="text-align:center" colspan="2"><p style="font-size:12px"><strong>Note:Please see instruction being issued separately</strong></p></td></tr>
                                                </table>

                                                <p class="break"></p>

                                            </div>	
                                        <?php } ?>
                                    </div>	
                                <?php } ?>	
