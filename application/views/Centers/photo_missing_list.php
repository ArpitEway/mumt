<div class="mt-5 dt-responsive " >
	<table id="kt_datatable" class="table table-striped" >
		<thead>
			<tr>
				<th>Sno</th>
				<th>Form no</th>
				<th>Session</th>
				<th>Name</th>
				<th>F/H Name</th>
				<th>Course</th>
				<th>Class</th>
				
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$i = 1;
			foreach($students as $student){
				
				if (!file_exists(FCPATH.'assets/student_image/'.$student->session.'/'.$student->photo) || $student->photo=="") {  
				?>
				<tr id="row_<?php echo $student->student_id; ?>">
					<td><?php echo $i; ?></td>
					<td><?php echo $student->student_id; ?> </td>
					<td><?php echo $student->session; ?> </td>
					<td><?php echo $student->name; ?> </td>
					<td><?php echo $student->f_h_name; ?> </td>
					<td><?php echo $student->course_name; ?> </td>

					<td><?php echo $student->class_name; ?> </td>
					
					<td>
						<?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id); ?>
					
						<a href="javascript:void(0);" style="margin:5px;" class="btn btn-success" id="<?php echo  $std  ?>"   onclick="rightModal('<?php echo site_url('admin/modal/student_popup/admin/student/update/update_photo/'.$student_id); ?>', '<?php echo 'Select Photo' ?>')">Upload</a>
					</td>
				</tr>
				<?php
				$i++; 
				}
			} 
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	

	$(document).on('click', '#remark_submit', function(e) {

	$('#remark_submit').prop('disabled', true);
	
	    e.preventDefault();
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val();
		var frm = $('.ajaxForm').serialize();
		var image_form = $("#photo").prop("files")[0];
		var fdata = new FormData(); // Creating object of FormData class
		fdata.append("form", frm);
		fdata.append('session', $("#session").val());
		fdata.append('photo', $("#photo").prop("files")[0]);
		fdata.append('csrf_token_akshay',csrfHash);
		var rem = $('.student_id_model').val();
        
		var imgname  =  $('input[type=file]').val();
	   
        var ext =  imgname.substr( (imgname.lastIndexOf('.') +1) );
		fdata.append('ext', ext);
    if(ext=='jpg' || ext=='jpeg' || ext=='png' || ext=='gif' || ext=='PNG' || ext=='JPG' || ext=='JPEG')
    {	   
	  
	$('#loader').addClass('loading');
		$.ajax({
		url: '<?php echo site_url('update_student_photo/'); ?>'+ rem,
		type: 'POST',
		data: fdata,
		processData: false,
		contentType: false,
		cache: false,
		async:true,
		success: function (data) {
		if(data){
			$('#loader').removeClass('loading');
			
				$('#right-modal').modal('toggle');
				$('#row_'+rem).remove();
				$('.student_id_model').val("");
				$("#session").val("");
				$("#photo").val("");
				toastr.success("Image uploaded !");
				}
			},
		});	
	 }else{
	 	toastr.error("Please select Image !");
	 }

});	
</script>
