<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
				
					<th>Sno.</th>
					<th>Form No</th>
					<th>Txn Image</th>
					<th>Course</th>	
					<th>Class</th>	
					<th>Name</th>
					<th>Father Name</th>
					<th>Payment Type</th>
					<th>Amount</th>
					<th>Subpaisa Txnid</th>
					<th>Txn Id</th>
					<th>Payment Date</th>
					<th>Remark Detail</th>
				</tr>
			</thead>
    		<tbody>
    		<?php 
			
    		$i = 1;

			foreach($accData as $acc){
				
			
			?>
			
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $acc["student_id"]; ?></td>
				<td>
				<?php if($acc["image"]) { ?>
					<a target="_blank" href="<?php echo BASE_URL('assets/transactionImgaes/'.$acc["image"]) ?>">
					<i class="mdi mdi-eye view-icon"></i> </a> &nbsp;&nbsp;&nbsp;
					<a href="<?php echo BASE_URL('assets/transactionImgaes/'.$acc["image"]) ?>" download ><i class="mdi mdi-download"></i>
				</a>
				<?php }else{
					echo "N/A";
				} ?>
				</td>
				<td>
					<?php 
					$course_name = $this->Common_model->getCourseNameByCourseId($acc["course_group_id"]);
					if(isset($course_name)){
						echo $course_name;
					}
					?>
				</td>
				<td><?php echo $this->Common_model->getClassNameByClassId($acc["class_id"]); ?></td>
				<td><?php echo $acc["student_name"]; ?></td>
				<td><?php echo $this->Common_model->getSinglefield('student','f_h_name','student_id='.$acc["student_id"]); ?></td>
				<td><?php echo $acc["fees_head"]; ?></td>
				<td><?php echo $acc["amount"]; ?></td>
				<td><?php echo ($acc["SabPaisaTxId"]=='') ? $acc["payment_status"] : $acc["SabPaisaTxId"]; ?></td>
				<td><?php echo $acc["clientTxnId"]; ?></td>
				<td><?php echo $this->Common_model->viewDate($acc["payment_date"]); ?></td>
				<td><?php echo $acc["remark"]; ?></td>
			</tr>
			<?php
			
			$i++;
			} 
			?>
			</tbody>
</table>