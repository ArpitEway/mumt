<div class="container mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
		<thead>
			<tr>
				<th>S No</th>
				<th>Form No</th>
				<th>Course</th>
				<th>Class</th>
				<th>Name</th>
				<th>Father Name</th>
				<th>Status</th>
				<th>Mobile</th>
				<th>DOB</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 1;
				foreach($students as $student){
					
					$courses = $this->db->get_where('course_group', array('id' => $student['course_group_id']))->row_array();
					if(isset($courses['course_name'])){
						$course_name = $courses['course_name'];
					}
				?>

				<tr id="student_tr_<?php echo $student['student_id']; ?>">
				<td><?php echo $i; ?></td>
				<td><?php echo $student['student_id']; ?></td>
				<td><?php echo $course_name; ?></td>
				<td><?php echo $student['class_name']; ?></td>
				<td><?php echo $student['name']; ?></td>
				<td><?php echo $student['f_h_name']; ?></td>
				<td>
				<div style="display: inline-flex;">
					
				<!-- <a href="javascript:void(0);" class="dropdown-item" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/enrollment/edit_unpaid_student/'.$student['student_id']); ?>', '<?php echo 'Payment by university' ?>')"> <i class="fas fa-pencil-alt text-primary"></i></a> -->
						
				<a href="#" class="btn btn-primary btn-sm font-weight-bold student" data-toggle="modal" data-target="#kt_datepicker_modal" data-id="<?=$student['student_id']?>" data-name="<?= $student['name']; ?>" data-amount="<?= ($student['session']<2021) ? 272 : 472; ?>" >Receive</a>	
						
				</div>	
					</td><td><?php
					echo $mobile_no = $this->Common_model->getMobileNoByStudentID($student['student_id']); 
					?></td>
					<td><?php
					echo ($student['dob'] != "") ? $this->Common_model->viewDate($student['dob']) :  "" ; 
					
					?></td>
					
					
				</tr>
				<?php 
					$i++;
				}
			?>
		</tbody>
	</table>
</div>



<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Student Payment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="card card-custom">

 <!--begin::Form-->
<form method="POST" class="d-block" id="ajaxForm" action="<?php echo site_url('admin/enrollment/update_unpaid_student'); ?>">
  <div class="card-body">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Student Name</label>
    <div class="col-7">
    <label for="example-date-input" class="col-form-label"><span id="student_name"></span></label>
	</div>
	
   </div>
   <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Receive Payment Date</label>
    <div class="col-7">
     <input class="form-control" type="date" name="payment_date"   id="payment_date"  placeholder="dd-mm-yyyy"/>
	 <div class="text-danger" id="error"></div>
	 <input type="hidden" value="" name="student_id" id="student_id">
    </div>
   </div>
   <div class="form-group row">
    <label for="amount" class="col-5 col-form-label">Amount</label>
    <div class="col-7">
     <input class="form-control" type="number" name="amount" id="amount" required />
	 <div class="text-danger" id="error3"></div>
    </div>
   </div>
   <div class="form-group row">
   	<label for="images" class="col-5 col-form-label">Image</label>
   	<div class="col-7">
   		<input class="form-control" type="file" name="images" id="images" required />
   		<div class="text-danger" id="errimg"></div>
   	</div>
   </div>
   <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Payment Mode</label>
    <div class="col-7">
     <select class="form-control" name="payment_mode" id="payment_mode" required>
	 <option value="">Select</option>
		<option>Credit Card</option>
		<option>Debit Card</option>
		<option>Net Banking</option>
		<option>IMPS</option>
		<option>Wallets</option>
		<option>UPI</option>
		<option>NEFT/RTGS</option>
		<option>SabPaisa Payment by Link</option>
	 </select>
	 <div class="text-danger" id="error2"></div>
	</div>
   </div>
     <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Remark</label>
    <div class="col-7">
	<textarea class="form-control remark" placeholder="Remark detail" id="kt_autosize_2" rows="4" name="remark" id="remark"  ></textarea>
    </div>
   </div>
  
  <div class="card-footer pb-0">
   <div class="row justify-content-center">
  
 
     <button type="reset" class="btn btn-success mr-2" id="payment_submit">Submit</button>
     
   
   </div>
  </div>
 </form>
</div>
		</div>
	</div>
</div>
</div>
<script>

$('.student').on('click', function (e) {
  var student_id = $(this).attr('data-id');
	var student_name = $(this).attr('data-name');
	var amount = $(this).attr('data-amount');
	$('#student_id').val(student_id);
	$('#student_name').html(student_name);
	$('#amount').val(amount);
});

 $("#payment_submit").on('click',function (e){
	var formimage = $('#ajaxForm');
	var frm = new FormData(formimage[0]);
		
        var student_id = $('#student_id').val();
		var payment_date = $('#payment_date').val();
		var payment_mode = $('#payment_mode').val();
		var amount = $('#amount').val();
		if(payment_date==''){
			$('#error').text('Please Select Date');
			return false;
		}
		if(amount==''){
			$('#error3').text('Please Enter Amount');
			return false;
		}
		if(payment_mode==''){
			$('#error2').text('Please Select Payment Mode');
			return false;
		}
		
		$.ajax({
		url: '<?php echo site_url('admin/enrollment/update_unpaid_student'); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		cache:false,
		contentType: false,
		processData: false,
		success: function (data) {
		if(data){
			console.log(data);
			$('#kt_datepicker_modal').modal('toggle');
			//$('#student_tr_'+student_id).remove();
			location.reload();
			toastr.success("Submitted");
		}else{
			toastr.error("Something wrong");
		}
			},
		});	
	
});	
    
</script>