<div class="container mb-5">
	<?php $flag = ($centerData->mobile_no_2 == '')?'':',';?>
	<h3 class="text-primary text-center h2"><?= ' ( '.$centerData->center_code.' ) ( '.$centerData->center_name.' ) ( '.$centerData->contactpersonname.' ) ( '.$centerData->mobile_no_1.' '.$flag.' '.$centerData->mobile_no_2.' ) '; ?></h3>
</div>
<div class="text-center">
	<table id="table" class="table table-striped dt-responsive nowrap" width="80%" >
		<thead>
			<tr>
				
				<th>S.No.</th>
				<th>Student Name</th>
				<th>Form No</th>
				<th>Course </th>
				<th>Class</th>
                <th>Type</th>
				<th>Detail</th>
				<th>Date</th>
				<th>Status</th>
				<th>Remark</th>
				<th>Reply</th>
                <th>Forward To</th>
				<th>Attachment</th>
				
			</tr>
		</thead>
		<tbody>
			<?php 
			
			$i = 1;
			
			foreach($complaints as $complaint){

				$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $complaint["student_id"]));
				$student_id = $this->Common_model->encrypt_decrypt($complaint["student_id"]);
				?>

                <tr id="<?= "row_".$i?>">

					<td><?php echo $i; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $complaint["student_id"]; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
                    <td><?php echo $complaint["type"]; ?></td>
					<td><?php echo $complaint["details"]; ?></td>
					<td><?php echo $this->Common_model->viewDate($complaint["date"]); ?></td>

					<td id="sta_<?= $complaint['id']?>">

						<?php
						if($complaint['status'] == 'Done')
						{
							?>

							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?>" class="btn btn-success req_check" value="Done">

						<?php }else{ ?>

							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?> " class="btn btn-danger req_check" value="Pending">

							<?php 
						}	
						?> 

					</td>
					<td id="rem_<?= $complaint['id']?>">

						<?php
						if($session['remark'] == '' || $session['remark'] != 'Invalid')
						{
							?>

							<input type="button" name="update_req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-success remark_check" value="Set">
							

						<?php }else{ ?>

							<input type="button" name="req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-danger remark_check" value="Invalid">

							<?php 
						}
						?>
					</td>
					<?php
					if($session['remark'] == '' || $session['remark'] != 'Invalid'){
					?>
					<td id="rep_<?= $complaint['id']?>">
					<!-- <a type="button"  class="btn btn-outline-primary btn-rounded" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/complaint/reply/'.$complaint['id'].''); ?>', 'Reply')" >Reply</a> -->
					<button class="btn btn-primary modal-button demo" onclick="return getModel(<?=$complaint['id']?>)" data-target="#bd-example-modal" data-id="<?= $complaint['id']?>"> Reply</button>
					</td>
					<?php }?>
                    <td>
					<input type="hidden" name="complain_id" id="<?= 'com_'.$i ?>" value="<?= $complaint['id']?>" >
						<select name="complaint_type" id="<?= "Complaint_".$i ?>" class="form-control" onchange="return forward(<?= $i?>)">
							<option value="">Select</option>
							<?php
							$supports = $this->Common_model->getRecordByWhere('support_system',array('status !='=>'N'));
							foreach($supports as $support){
								if($complaint["type"] == $support->name){
									continue;
								}
								?>
								<option value="<?php echo $support->name; ?>"><?php echo $support->name; ?></option>
								<?php
							} 
							?> 
						</select>    
						</td>
					<td>
						<?php if($complaint["attachment"] != ''){
							?>
							<a target="_blank"  href="<?= base_url().'assets/complaintImages/'.$complaint["attachment"]?>"><i class="fa fa-eye"></i></a>
							<?php
						}?>
					</td>
					
				</tr>
				<?php
				$i++;
			} 

			?>
		</tbody>
	</table>
	
<script>
		function getModel(param){
        
			$('#bd-example-modal').show();
			$('#complaint_id').val(param);
			
		};

		$("#close").on('click',function(){
				$('#bd-example-modal').hide();	
		});
</script>
	