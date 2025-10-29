<style>
	#swal2-content{
		text-align: justify;
	}
</style>
<div class="container-fluid mt-5" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">

	<table id="kt_datatable" class="table table-striped dt-responsive" style="width:100%">
		<thead>
			<tr>
				<th>#</th>
				<th>Form No.s</th>
				<th>Student Name</th>
				<th>Father Name</th>
				<th>Course</th>
				<th>Class</th>
			
                <th>Action</th>
				<?php //if($course_type=='PVT'&& $late_privte_admission_fees=='Y'){ echo '<th>Action</th>';}?>
			</tr>
		</thead>
		<tbody>
            <?php
            foreach($students as $key=>$student){
                ?>
                <tr>
                    <td><?= $key+1; ?></td>
                    <td><?= $student->student_id; ?></td>
                    <td><?= $student->name; ?></td>
                    <td><?= $student->f_h_name; ?></td>
                    <td><?= $student->course_name; ?></td>
                    <td><?= $student->class_name; ?></td>
                    
                    <td>
                       <a href="#" id="<?= $this->Common_model->encrypt_decrypt($student->student_id)?>" data-student_id="<?= $this->Common_model->encrypt_decrypt($student->student_id)?>" data-session="<?= $student->session?>" data-eligibility=<?= $student->eligibility?> data-name="<?= $student->name?>" class="btn btn-info btn-sm pay" >Add</a>
                    </td>
                </tr>
                <?php
            }
            ?>
		</tbody>
	</table>
</div> 
<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Additional Course</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="card card-custom">

 <!--begin::Form-->
<form method="POST" class="d-block" id="" action="<?php echo site_url('center/center/add_additional_course'); ?>">
  <div class="card-body">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<!-- <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Form No.</label>
    <div class="col-7">
    <label for="example-date-input" class="col-form-label"><span id="student_id_show"></span></label>
	</div>
	
   </div> -->
    <div class="form-group row">
    <label for="example-date-input" class="col-4 col-form-label">Student Name</label>
    <div class="col-7">
    <label for="example-date-input" class="col-form-label"><span id="student_name"></span></label>
    <input type="hidden" id="student_id" name="student_id" value="">
	</div>
	
   </div>
    <div class="form-group row">
    <label for="example-date-input" class="col-4 col-form-label">Course<span class="text-danger">*</span></label>
    <div class="col-7">
                                    
        <select name="additional_course_group_id" id="additional_course_group_id" class="form-control " >
        <option value="">Select Course</option>
        </select>
        <div class="fv-plugins-message-container"></div>
                            
	</div>
        </div>
        <div class="form-group row">
             <label for="example-date-input" class="col-4 col-form-label">Class<span class="text-danger">*</span></label>
        <div class="col-7 ">
           
            <select name="additional_class_id" id="additional_class_id" class="form-control ">
                <option value="">Select Class</option>
            </select>
            <div class="fv-plugins-message-container"></div>
        
        </div>
    
				</div>
   
  <div class="card-footer pb-0">
   <div class="row justify-content-center">
  
 
     <button type="submit" class="btn btn-success mr-2" id="payment_submit">Submit</button>
     
   
   </div>
  </div>
 </form>
</div>
		</div>
	</div>
</div>

	<script>
        $('.pay').on('click',function(){
            var student_id = $(this).attr('data-student_id');
            $('#student_id').val(student_id);
            // $('#student_id_show').html(student_id);
            $('#student_name').html($(this).attr('data-name'));
           
            $('#kt_datepicker_modal').modal('show');
             var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val();
	let session = $(this).attr('data-session');
	let eligibility = $(this).attr('data-eligibility');
	
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getAdditionalCourse",
		data: {session : session,eligibility:eligibility,[csrfName]:csrfHash},
	}).done(function( msg ) {
		$('#additional_course_group_id').html(msg);
	});
        });

        $('#additional_course_group_id').on('change', function() {
	let course = $(this).val();
    let mode = 'REG';
	let csrfName = $('.csrfname').attr('name');
	let csrfHash = $('.csrfname').val(); 
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getClassByCourse",
		data: {course : course,[csrfName]:csrfHash , mode : mode},
	})
	.done(function( msg ) {
		$('#additional_class_id').html(msg);
	});
});

    </script>