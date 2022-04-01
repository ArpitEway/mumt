
<style>
	.hide{
padding-left: 230px;
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
						if($complaint['status'] == 'D')
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
						if( $complaint['remark'] == 'Invalid')
						{
							?>

							<input type="button" name="update_req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-danger remark_check" value="Invalid">

						<?php }else{ ?>

							<input type="button" name="req_remark" data-id = "<?=$complaint["id"];?>" class="btn btn-success remark_check" value="Set">

							<?php 
						}
						?>
					</td>


                          <td>   
                          <!-- 	<div class="type" style="display: none"  data-id= "<?php echo $complaint["type"]; ?>">
                         </div> -->
       		       <div  id="type" >
    	       </div> 
						<button type="button" class="btn btn-info forward " data-id="<?=$complaint["id"];?>"   >Forward</button>

						 <form class="hide" style="display: none">

						     	<span><strong>Select Any Department</strong></span><br><br>
						 		<input type="hidden" name="complain"  value="<?=$complaint["id"];?>">	
						 		   <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
						 		 <input type="radio" value="Exam Form" name="redy">Exam Form <br><br>
						 		 <input type="radio" value="Enrollment" name="redy">Enrollment<br><br>
						 		  <input type="radio" value="Technical Support" name="redy">Technical Support<br><br>
						 		 <input type="radio" value="Marksheet" name="redy">Marksheet<br><br>
						 		<input type="radio" value="Result" name="redy">Result <br><br>
						 		<button type="button" class="btn btn-success forwardcomplain"   >Submit</button>

						 </form>

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

			var status = (val=='Done') ? 'P' : 'D';

			var data = {
				id: $(this).attr('data-id'),
				status: status,
				[csrfName]: csrfHash,
			}; 

	
			$.ajax({
				url: BASE_URL+ 'admin/'+account_type+'/update_complaint_status',
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

			var remark = (val=='set') ? 'Invalid' : '';

			var data = {
				id: $(this).attr('data-id'),
				remark: remark,
				[csrfName]: csrfHash,
			};
			

			$.ajax({
				url: BASE_URL+ 'admin/'+account_type+'/update_complaint_remark',
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
	// $(document).on('click', '.forwardcomplain', function(e) {
  $(".forwardcomplain").on('click',function (e){

  // var id = $('#student_tr').val();
  	var val = $(this).val();
  	var self = this;
   e.preventDefault();
var frm = $(this).parent().serialize();

  $.ajax({

     url: BASE_URL+ 'admin/'+account_type+'/complaint_form_sub',
    method: 'post',
     data: frm,
   dataType: 'JSON',   
    success: function (data) {
      if(data.success){
       toastr.success(data.success);
      $(self).parent().toggle().html(data.data); 
     console.log(data);

 $('#type').parent().html(data.data);
          
         
}else{
  toastr.error(data.error);
}
},
});
});</script>


      <script>
		
	$('.forward').click(function() {

var val = $(this).val();
  	var self = this;

		 $(self).hide();
		
  $(this).next().show();


});
</script>   