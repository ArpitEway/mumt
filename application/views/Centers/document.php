<?php  
	
	$docCatId = array();
	$d=0;
	foreach($documentData as $document){
		if($document['status']=='N'){
			continue;
		}
			$docCatId[$d] = 'docCatId_'.$document['id'];
			$d++;
	}
?>
<script>
	var docCatIdArray = [<?php echo '"'.implode('","',  $docCatId ).'"' ?>];
</script>

<div class="card card-custom card-stretch my-10 details-bg" id="profile">
<div class="container-fluid profile mt-5">
	<div class="row ">
		<div class="col-md-4 border-right-dashed">
			<div class="row py-2">
				<label class="col-sm-3 text-heading">Student</label>
				<div class="col-sm-9 text-value">
					<?php echo $student['name']; ?>
				</div>
			</div>
		</div>
		<div class="col-md-4 border-right-dashed">
			<div class="row py-2">
				<label class="col-sm-3 text-heading">Session</label>
				<div class="col-sm-9 text-value">
					<?php echo $student['session']; ?>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="row py-2">
				<label class="col-sm-3 text-heading">Course</label>
				<div class="col-sm-9 text-value">
					<?php echo $student['course_name'];  ?>
				</div>
			</div>
		</div>
		<div class="col-md-4 border-right-dashed">
			<div class="row py-2">
				<label class="col-sm-3 text-heading">Father</label>
				<div class="col-sm-9 text-value">
					<?php echo $student['f_h_name']; ?>
				</div>
			</div>
		</div>
		<div class="col-md-4 border-right-dashed">
			<div class="row py-2">
				<label class="col-sm-3 text-heading">Form No</label>
				<div class="col-sm-9 text-value">
					<?php echo $student['student_id']; ?>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="row py-2">
				<label class="col-sm-3 text-heading">Class</label>
				<div class="col-sm-9 text-value">
					<?php echo $student['class_name']; ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<form method="post" action="<?=base_url('student/Document/uploadDoc');?>" enctype='multipart/form-data' id="target" >
	<div id="loader">
	</div>
	<div class="row input-div">
		<input type="hidden" name="course_group_id" id="course_group_id" value="<?=$courseData->id; ?>" >
		<input type="hidden" name="student_id" id="student_id" value="<?=$student['student_id']; ?>" >
		
		<?php 		foreach($documentData as $document){ 

			if($document['document']=='Marriage Certificate'){
				if($student['gender']=='Male' || ($student['marital_status']=='Unmarried' && $student['gender']=='Female')){
					continue;
				}
			}
			?>
			<div class="col-md-6">
				<div class="form-group">
					<label class="w-100"><?=$document['document'];?><strong class="text-danger"><?= ($document['status']=='N') ? '' : ' *'; ?></strong>
						<span class="float-right" id="<?='downloadBtnId_'.$document['id']?>">
							<?php 
							$file = $this->Common_model->GetAdmissonDocFile($student['student_id'],$document['id']);
							if($file){
								$src = 'assets/documents/'.$file;
								?>
								<a href="<?=base_url($src);?>" download>
									Download
								</a>
							<?php }
							?>
						</span>
					</label>

					<input type="hidden" name="document_name<?=$document['id']?>" id="document_name<?=$document['id']?>" value="<?=$document['document']?>" >
					<input type="hidden" name="document_category_id<?=$document['id']?>" id="document_category_id<?=$document['id']?>" value="<?=$document['id']?>" >
					<div></div>
					<div class="custom-file">
						<input type="file" accept="image/*,application/pdf" class="custom-file-input" id="<?='docCatId_'.$document['id']?>" data-id="<?=$document['id']?>" name="document[]" >

						<label class="custom-file-label" for="<?='docCatId_'.$document['id']?>"></label>
					</div>
					<div class="fv-plugins-message-container"></div>
				</div>
			</div>
		<?php } ?>
	</div>

<ul class="my-5"><li class="text-danger my-3 font-weight-bold">	 डाक्यूमेंट्स अपलोड करते समय सभी की साइज 80 KB से 250 KB के मध्य रखना अनिवार्य है|  </li>
<li class="text-danger my-3 font-weight-bold"> यदि महिला अभ्यर्थी द्वारा Admission Form में पिता के नाम की जगह पति का नाम एवं उपनाम भरा गया है तो Affidevit अथवा Marriage Certificate  की प्रतिलिपि आवश्यक है। </li>
<li class="text-danger my-3 font-weight-bold">समस्त डाक्यूमेंट्स अपलोड करने के बाद नीचे दिए गए "Submit" बटन पर अवश्य क्लिक करें| </li>
</ul>

	<div class="row justify-content-center my-3">
		<button type="submit" class="btn btn-primary btn-lg" id="submit" name="submit">Submit</button>
	</div>
</form>
<script>
	$(".custom-file-input").on('change',function (){
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = $(this).val();
		var id = $(this).attr("data-id");
		if(data==''){
			return false;
			$(this).parent().next('div').text('Document Required');
			}else {
			var file = $(this);
			
			var filesize = parseFloat(file[0].files[0].size / 1024).toFixed(2);
			if(filesize>500){
				$(this).parent().next('div').text('Document size must be less than 500kb');
				return false;
				}else{
				$(this).parent().next('div').text('');
				
				var student_id = $('#student_id').val();
				var myFormData = new FormData();
				var document_category_id = $('#document_category_id'+id).val();
				var document_name = $('#document_name'+id).val();
				var course_group_id = $('#course_group_id').val();
				
				myFormData.append('document', this.files[0]);
				myFormData.append('student_id', student_id);
				myFormData.append('course_group_id', course_group_id);
				myFormData.append('document_category_id', document_category_id);
				myFormData.append('document_name', document_name);
				myFormData.append([csrfName], csrfHash);
				$('#loader').addClass('loading');
				$.ajax({
					url: "<?=base_url('center/Document/uploadDoc');?>",
					type: 'POST',
					processData: false, // important
					contentType: false, // important
					dataType : 'json',
					data: myFormData,
					success: function (data) {
						$('#loader').removeClass('loading');
						if(data.success){
							$('#downloadBtnId_'+id).html(data.btn);
							toastr.success(data.success);
							}else{
							toastr.error(data.error);
						}
					},
				});
			}
		}
	});
	
	$("#submit").on('click',function (e){
	  e.preventDefault();
	$('#loader').addClass('loading');
	var course_group_id = $('#course_group_id').val();
	var student_id = $('#student_id').val();
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		$.ajax({
			url: "<?=base_url('center/Document/checkDocumentStatus');?>",
			type: 'POST',
			dataType : 'json',
			data: {student_id:student_id,course_group_id:course_group_id, [csrfName]: csrfHash},
			success: function (data) {
				$('#loader').removeClass('loading');
				if(data.success){
					toastr.success(data.success);
					window.location.href = BASE_URL+"center/dashboard";
					return false;
					}else{
					toastr.error(data.error);
					return false;
				}
			},
		});
	});
</script>