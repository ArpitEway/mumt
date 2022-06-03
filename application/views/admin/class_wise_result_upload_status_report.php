<div class="container">
	<table class="table text-uppercase">
		<thead>
			<tr>
                <th>Sr. No. </th>
                <th>Course Name </th>
                <th>Class Name </th>
                <th>Total</th>
				<th>Uploaded</th>
                <th>Absent</th>
				<th>Rremaining</th>
				<!-- <th>Checked</th> -->
			</tr>
		</thead>
		<tbody>
			
                <?php if(!$class_id){ $i=1;
                    foreach($class as $k=>$val){
                    ?>
                    <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo  $course_group ; ?></td>
                    <td><?php echo  $val['class_name'] ; ?></td>
                    <td><?php  echo  $val['total_paper_count'] ; ?></td>
                    <td><?php echo $val['uploaded'] -$val['absent']; ?></td>
                    <td><?php echo $val['absent'] ; ?></td>
                    <td><?php  echo $val['total_paper_count']-$val['uploaded'] ; ?></td></tr>
                <?php $i++;} } else{ ?>
                    <tr>
                    <td>1</td>
                    <td><?php echo  $course_group ; ?></td>
                    <td><?php echo  $class_name ; ?></td>
                    <td><?php echo  $total_paper_count ; ?></td>
                    <td><?php echo $uploaded -$absent; ?></td>
                    <td><?php echo $absent ; ?></td>
                    <td><?php  echo $total_paper_count-$uploaded ; ?></td>  </tr>
                <?php } ?>   
				<!-- <td><?php echo $checked ; ?></td> -->
			
		</tbody>
	</table>
</div>