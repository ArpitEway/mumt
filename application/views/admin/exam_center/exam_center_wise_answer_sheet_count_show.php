<?php 
    foreach($exam_centers as $row)
    {
?> 
 <br>

  <br>

<table width="80%" border="1" align="center">

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

        <center><h3>Total Answer Sheet Count 8880</h3></center>

        </td>

        </tr>



    </tbody>
</table>
<?php } ?>