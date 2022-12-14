<div>
	<table class="table text-uppercase">
		<thead>
			<tr>
                <th>Sr. No. </th>
                <th>Course Name </th>
                <th>Class Name </th>
                <th>Total</th>
				<th>Uploaded</th>
                <th>Absent</th>
                <th>Theory Remaining</th>
                <th>Ass. Total</th>
                <th>Ass. Uploaded</th>
                <th>Ass. Remaining</th>
                <th>Pr. Total</th>
                <th>Pr. Uploaded</th>
                <th>Pr. Remaining</th>
				
				<!-- <th>Checked</th> -->
			</tr>
		</thead>
		<tbody>
        <!-- <a href="<?php echo base_url("class_wise_result_upload_status_report")."/".$course_detail['id']."/".$class['id']; ?>" target="_blank" > -->
       
                <?php 
                $account_type = ($this->session->account_type=='Admins') ? '' : $this->session->account_type.'/';
                if(!$class_id){
                    $i=1;
                    foreach($class as $k=>$val){
                    ?>
                    <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo  $course_group ; ?></td>
                    <td><?php echo  $val['class_name'] ; ?></td>
                    <td><?php  echo  $val['total_paper_count'] ; ?></td>
                    <td><?php echo $val['uploaded'] -$val['absent']; ?></td>
                    <td><?php echo $val['absent'] ; ?></td>
                    <td>
                        <a href="<?php echo base_url($account_type."class_wise_remaining_report")."/theory/".$course_group_id."/".$val['class_id']."/".$courseType; ?>" target="_blank" >
                            <?php  echo $val['total_paper_count']-$val['uploaded'] ; ?>
                        </a>
                    </td>
                    <td><?php  echo $val['internalcount'] ; ?></td>
                    <td><?php  echo  $val['internal'] ; ?></td>
                    <td>
                        <?php if($courseType!="PVT"){  ?>
                        <a href="<?php echo base_url($account_type."class_wise_remaining_report")."/internal/".$course_group_id."/".$val['class_id']."/".$courseType; ?>" target="_blank" >
                        <?php  echo  $val['internalcount'] -$val['internal'] ; ?>
                        </a>
                        <?php } else { echo "0";}?>
                    </td>
                    <td><?php  echo  $val['practicalTotal'] ; ?></td>
                    <td><?php  echo  $val['practical'] ; ?></td>
                    <td>
                    <?php if($courseType!="PVT"){  ?>
                        <a href="<?php echo base_url($account_type."class_wise_remaining_report")."/practical/".$course_group_id."/".$val['class_id']."/".$courseType; ?>" target="_blank" >    
                            <?php  echo  $val['practicalTotal'] -$val['practical'] ; ?>
                        </a>   
                        <?php } else { echo "0";}?> 
                    </td>
                    </tr>
                <?php $i++;} } else{ ?>
                    <tr>
                    <td>1</td>
                    <td><?php echo  $course_group ; ?></td>
                    <td><?php echo  $class_name ; ?></td>
                    <td><?php echo  $total_paper_count ; ?></td>
                    <td><?php echo $uploaded - $absent; ?></td>
                    <td><?php echo $absent ; ?></td>
                    <td>
                        <a href="<?php echo base_url($account_type."class_wise_remaining_report")."/theory/".$course_group_id."/".$class_id."/".$courseType; ?>" target="_blank" >
                           <?php  echo $total_paper_count-$uploaded ; ?>
                        </a>
                    </td>
                    <td><?php echo  $total_paper_count ; ?></td>
                    <td><?php echo  $internal ; ?></td>
                    <td><?php echo  $total_paper_count-$internal ; ?></td>
                    <td><?php echo  $practicalTotal ; ?></td>
                    <td><?php echo  $practical ; ?></td>
                    <td><?php echo  $practicalTotal-$practical ; ?></td>
                      </tr>
                <?php } ?>   
				<!-- <td><?php echo $checked ; ?></td> -->
			
		</tbody>
	</table>
</div>
