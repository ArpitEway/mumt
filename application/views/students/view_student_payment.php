<div class="container mt-5" >
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
			<thead>
				<tr>
					<th>Sno</th>
					<th>Class</th>
					<th>Fees head</th>
					<th>Fees amount</th>
					<th>Payment Status</th>
					<th>Payment Date</th>
					<th>Payment Receipt</th>
	
				</tr>
			</thead>
				<tbody>
			<?php

			  $i = 1;
			  $total_amount = 0;
			  foreach($payments as $payment_detail){
				  
			?>
			 <tr>
			 
				<td><?php echo $i; ?></td>
				<td><?php echo $this->Common_model->getClassNameByClassId($payment_detail->class_id); ?></td>
				<td><?php echo $payment_detail->fees_head; ?></td>
				<td><?php echo $payment_detail->amount; ?></td>
				<td><?php echo $payment_detail->payment_status; ?></td>
				<td><?php

				$newDate = date("d-m-Y", strtotime($payment_detail->payment_date));
				if($newDate != '01-01-1970'){
					echo $newDate;
				}
			?>
			</td>
			<td class="text-center" >
				<?php if($payment_detail->payment_status == "SUCCESS" || $payment_detail->payment_status == "Verified By University") { ?>
				<a target="_blank" href="<?=base_url('student/payment/detail/'.$payment_detail->id);?>"><i class="far fa-eye text-info mr-5"></i></a>
				<?php }else{
					$paymentType = ($payment_detail->fees_head=='admission') ? 'admission' : 'program_fees';
				?>
				<a target="_blank" href="<?=base_url('student/payment/'.$paymentType.'/'.$payment_detail->student_id);?>"><p class="btn btn-sm btn-success">Pay</p>
				</a>
				
				<?php } ?>
			</td>
		</tr>
			<?php 
			 $total_amount = $total_amount + $payment_detail->amount;
			 $i++;
			 } 
			?>
			<tr>
			    <td></td>
				<td></td>
				<td><b>Total Fees amount</b></td>
				<td><b><?php echo $total_amount; ?> /-</b></td>
				<td></td>
				<td></td>
				<td></td>
			 </tr>		
		</tbody>
	</table>
</div>