<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>
</head>
<body style="width:1050px;margin:2px;margin:auto;" >
    <style>
        h4{
        font-size:16px;
        margin: 0;
        }
        .break{
        page-break-before: always;
        }
        table {
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        }
        .tdheight{line-height:21px; }
        td ,th{
        line-height:32px;
        }
        .font-19{
        font-size: 15px;
        }
        
    </style>
    <?php 
        $pageCounter=35;
        $totalExamcenterCounter=count($exam_center_id);
        $examcenterCounter=0;
        if($university_mode=="REG"){
            $university_mode_heading='REGULAR ';    
        }else{
            $university_mode_heading='PRIVATE ';
        }
        $marks="";
        if($university_mode=="REG") $marks= $paper[0]->max_theory_marks; if($university_mode=="PVT") $marks= $paper[0]->private_max_theory_marks;
        $edate=(in_array($class_id, array(104,107,134)) && $university_mode =='PVT')?date("d-m-Y", strtotime($paper[0]->pvt_exam_date)):date("d-m-Y", strtotime($paper[0]->exam_date));
        $heading='<table  align="center" style="width:1000px;">
        <tr><td colspan="2" class="tdheight"><h4 align="center">Maharishi Mahesh Yogi Vedic Vishwavidyalaya</h4></td></tr>
        <tr><td colspan="2" class="tdheight"><h4 align="center">  </h4></td></tr>
        <tr><td colspan="2"class="tdheight"><h4 align="center">Madhya Pradesh</h4></td></tr>
        <tr><td colspan="2" class="tdheight"><h4 align="center">  </h4></td></tr>
        <tr><td colspan="2"class="tdheight"><h4 align="center">( '.$university_mode_heading.'CHECKING LIST )</h4></td></tr><tr></tr>
        <tr><td style="text-align:left;"  class="tdheight"><strong>Exam Session:</strong>&nbsp; '.$examSession.'</td></tr>
        <tr><td  class="tdheight" style="width: 75%;"><strong>Course:</strong>&nbsp;'. $coursename.'</td><td  class="tdheight" ><strong>Class :</strong>&nbsp;'. $class_name.'</td></tr>    
        <tr><td  class="tdheight" style="width: 75%;"><strong>Subject : </strong>&nbsp;&nbsp;'. $paper[0]->paper_name.'</td><td  class="tdheight" ><strong>Subject Code: </strong>&nbsp;&nbsp;'.  $paper[0]->paper_code.'</td></tr>
        <tr><td class="tdheight" style="width: 75%;"><strong>Date of Exam :</strong>&nbsp;'. $edate  .'</td><td style="text-align:left;" class="tdheight"><strong>Max.Marks :</strong>&nbsp;'.$marks.'</td></tr>
        </table>';
        echo $heading;
    $rowCounter=0; $position="left"; ?>
    <table   border="1" class="form-group col-md-6" style="width:32%;float:left;margin:2px;">
        <tr style="text-align:center;">
            <th width="20%">EC</th>
            <th width="20%">En.No. </th>
            <th width="15%">Roll No.</th>
            <th width="15%">Marks</th>
            <!-- <th width="27%">Remark</th> -->
        </tr>
        
        <?php 
         $roll = $this->Common_model->getMaster('roll_number_col');
            foreach($students[$examcenter] as $student){ $rowCounter++;
                
            ?> 
            <tr>
                <td  style="text-align:center;"><?php  echo $student->examcentercode; ?></td>
                <td  style="text-align:center;"><?php  echo $student->enrollment_no; ?></td>
                <td style="text-align:center;padding: 0px 3px 0px 3px;"><?php echo $student->roll_no;?></td>
                
                <td style='text-align:center;'><?php echo $student->theory_marks; ?></td>
                
                <!-- <td class="font-19">&nbsp;&nbsp;<?php //echo $result; ?></td> -->
            </tr>
            <?php 
                if($rowCounter%$pageCounter==0){
                    
                ?>
            </table>
            <?php
                 if($rowCounter%105==0)//$position=="right"
                 {  echo '<p class="break"></p>'.$heading;
                // $position="left";
                 }
                // else if($position=="left")
                // $position="right"; 
            ?>
            <!---Header Part--->
            <table   border="1" class="form-group col-md-6" style="float:<?=$position?>;width:32%;margin:2px;">
                <tr style="text-align:center;">
                    <th width="20%">EC </th>
                    <th width="20%">En.No. </th>
                    <th width="15%">Roll No.</th>
                    <th width="15%">Marks</th>
                    <!-- <th width="27%">Remark</th> -->
                </tr>
                <?php
                }
            } ?>
    </table>
</div>  
</body>
</html>
