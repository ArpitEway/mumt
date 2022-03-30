 <style>
#group2 {
  display: none;
  padding-left:220px ;
}
</style> 


<div class="container mb-5">
	<h3 class="text-primary text-center h2"><?= ' ( '.$centerData->center_code.' ) ( '.$centerData->center_name.' ) ( '.$centerData->contactpersonname.' ) ( '.$centerData->mobile_no_1.' , '.$centerData->mobile_no_1.' ) '; ?></h3>
</div>
<div class="text-center">
	<table id="table" class="table table-striped dt-responsive nowrap" width="70%" >
		<thead>
			<tr>
				
				<th>S.No.</th>
				<th>Student Name</th>
				<th>Form no</th>
				<th>Course </th>
				<th>Class</th>
				<th>Detail</th>
				<th>Date</th>
				<th>Status</th>
				<th>Remark</th>
				<th>Forward</th>
				<th>View/Action</th>

			</tr>
		</thead>
		<tbody>
			<?php 
			
			$i = 1;
			
			foreach($complaints as $complaint){

				$student = $this->Common_model->getSingleRow("student",'*',array("student_id" => $complaint["student_id"]));
				$student_id = $this->Common_model->encrypt_decrypt($complaint["student_id"]);
				?>

				<tr>

					<td><?php echo $i; ?></td>
					<td><?php echo $student->name; ?></td>
					<td><?php echo $complaint["student_id"]; ?></td>
					<td><?php echo $student->course_name; ?></td>
					<td><?php echo $student->class_name; ?></td>
					<td><?php echo $complaint["details"]; ?></td>
					<td><?php echo $this->Common_model->viewDate($complaint["date"]); ?></td>

					<td >

						<?php
						if($complaint['status'] == 'P')
						{
							?>

							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?>" class="btn btn-success req_check" value="Done">

						<?php }else{ ?>

							<input type="button" name="update_req_stats" data-id = "<?=$complaint["id"];?> " class="btn btn-danger req_check" value="Pending">

							<?php 
						}	
						?> 

					</td>
					<td>

						<?php
						if($complaint['remark'] == '' || $complaint['remark'] != 'Invalid')
						{
							?>

							<input type="button" name="update_req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-success remark_check" value="Set">

						<?php }else{ ?>

							<input type="button" name="req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-danger remark_check" value="Invalid">

							<?php 
						}
						?>
					</td>


                          <td>
							<button type="button" class="btn btn-info " data-id="<?=$complaint->id;?>" id="forward"  >Forward</button>

						 <form id="ajaxForm">

						 	<div id="group2"  >
						 		<input type="hidden" class="csrfname" name="student_id" value="<?=$complaint->id; ?>">	
						 		<div >
						 		Exam Form  <input type="radio" value="Exam Form" name="redy"><br><br>
						 		Enrollment <input type="radio" value="Enrollment" name="redy"><br><br>
						 		Technical Support  <input type="radio" value="Technical Support" name="redy"><br><br>
						 		Marksheet <input type="radio" value="Marksheet" name="redy"><br><br>
						 		Result <input type="radio" value="Result" name="redy"><br><br></div>
						 		<button type="submit" class="btn btn-success"  id="submit" >Submit</button>

						 	</div>
						 </form>

					</td>


					<td>
						<a href="<?=base_url('admin/admins/view_student_transaction/'.$student_id);?>" target="_blank" >View Details</a>
					</td>
				</tr>


				<?php

				$i++;
			} 

			?>
		</tbody>
	</table>


	<script>

		$(document).on('click', '.req_check', function() {

			var val = $(this).val();
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			var self = this;

			var status = (val=='Done') ? 'D' : 'P';

			var data = {
				id: $(this).attr('data-id'),
				status: status,
				[csrfName]: csrfHash,
			}; 

			var url = BASE_URL + "admin/admins/update_payment_complaint_status";

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (data) {

					$(self).parent().html(data.data);

				}
			});

		});

		$(document).on('click', '.remark_check', function() 
		{

			var val = $(this).val();
			var csrfName = $('.csrfname').attr('name');
			var csrfHash = $('.csrfname').val();
			var self = this;

			var remark = (val=='Set') ? 'Invalid' : 'Set';

			var data = {
				id: $(this).attr('data-id'),
				remark: remark,
				[csrfName]: csrfHash,
			};
			var url = BASE_URL + "admin/admins/update_payment_complaint_remark";

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: data,
				success: function (data) {
					$(self).parent().prev().html(data.statusBtn);
					$(self).parent().html(data.remarkBtn);
				}
			});
		});
	</script>
<script>
 $("#submit").on('click',function (e){
   e.preventDefault();
var frm = $('#ajaxForm').serialize();

  
  $.ajax({
    url: '<?php echo site_url('admin/admins/complaint_form_sub'); ?>',
    method: 'post',
    data: frm,
    dataType: 'JSON',
    success: function (data) {
      if(data.success){
        toastr.success(data.success);
           

  // $('#kt_datatable info_row[id="'+tr_id+'"]').remove();

}else{
  toastr.error(data.error);
}
},
});</script>
});









  <!-- <script>
	$(document).ready(function(){
	$('#forward').click(function() {
  $('#group2').css('display', "block")
  

});});</script>  -->
  


 <!-- <script>
	
	const targetDiv = document.getElementById("group2");
const btn = document.getElementById("forward");
btn.onclick = function () {
  if (targetDiv.style.display !== "none") {
    targetDiv.style.display = "none";
  } else {
    targetDiv.style.display = "block";
  }
};
    </script>   --> 

 
      <script>
	$(document).ready(function(){
		var id = $(this).attr('data-id');
			var targetDiv = document.getElementById("group2");
	$('#forward').click(function() {
		  if (targetDiv.style.display == "none"){
  $('#group2').show();}else{
  	$('#group2').hide();
  }
  

});});</script>   