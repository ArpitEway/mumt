<table id="" class="table table-striped dt-responsive" width="100%" >
<thead>
		<tr>
					<th>Sno.</th>
					<th>Total Exam Form</th>
					<th>Total Exam Form Fill</th>
					<th>Total Exam Form Skip</th>
					<th>Total Exam Form Remaining </th>
		
		</tr>
</thead>
<tbody>      
 <?php
                 $i=1;
                 {?>
		
				<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $permitted_student;?></td>
				<td><?php echo $filled_student; ?></td>
				<td><?php echo $skipped_student; ?></td>
				<td> <a target="_blank" href="<?= base_url('admin/admins/center_wise_remains_count/');?>" ><?php echo $not_filled_student; ?></a>
			</td>
			</tr>
			<?php 
		}?>
		
</tbody>
</table>