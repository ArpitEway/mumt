<?php  
	
	$docCatId = array();
	$d=0;
	foreach($documentData as $document){
		if($document['status']=='Y'){
			$docCatId[$d] = 'docCatId_'.$document['id'];
			$d++;
		}
	}
?>
<script>
	var docCatIdArray = [<?php echo '"'.implode('","',  $docCatId ).'"' ?>];
</script>
<div class="text-danger my-3">	 डाक्यूमेंट्स अपलोड करते समय सभी की साइज 80 KB से 500 KB के मध्य रखना अनिवार्य है|  </div>
<div class="text-danger my-3">	समस्त डाक्यूमेंट्स अपलोड करने के बाद नीचे दिए गए "Submit" बटन पर अवश्य क्लिक करें| </div>
<form method="post" action="<?=base_url('student/Document/uploadDoc');?>" enctype='multipart/form-data' id="target" >
	<div id="loader">
	</div>
	<div class="row">
		<input type="hidden" name="course_group_id" id="course_group_id" value="<?=$courseData->id; ?>" >
		<input type="hidden" name="student_id" id="student_id" value="<?=$student_id; ?>" >
		
		<?php 		foreach($documentData as $document){ ?>
			<div class="col-md-6">
				<div class="row align-items-center">
					<div class="col-md-8">
						<div class="form-group">
							<label><?=$document['document_name']?><strong class="text-danger"><?=($document['status']=='Y') ? '*' : '';?></strong></label>
							
							<input type="hidden" name="document_name<?=$document['id']?>" id="document_name<?=$document['id']?>" value="<?=$document['document_name']?>" >
							<input type="hidden" name="document_category_id<?=$document['id']?>" id="document_category_id<?=$document['id']?>" value="<?=$document['id']?>" >
							<div></div>
							<div class="custom-file">
								<input type="file" accept="image/*,application/pdf" class="custom-file-input" id="<?='docCatId_'.$document['id']?>" data-id="<?=$document['id']?>" name="document[]" >
								
								<label class="custom-file-label" for="<?='docCatId_'.$document['id']?>"></label>
							</div>
							<div class="fv-plugins-message-container"></div>
						</div>
					</div>
						<div class="col-md-4" id="<?='downloadBtnId_'.$document['id']?>">
					<?php 
						$file = $this->Common_model->GetAdmissonDocFile($student_id,$document['id']);
						if($file){
							$src = 'assets/documents/'.$file;
						?>
							<a href="<?=base_url($src);?>" download>
								Download
							</a>
						<?php }
					?>
						</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="row justify-content-center my-3">
		<button type="submit" class="btn btn-primary btn-lg" id="submit" name="submit">Submit</button>
	</div>
</form>
<script>
	$(".custom-file-input").on('change',function (){
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
				
				
				var myFormData = new FormData();
				var document_category_id = $('#document_category_id'+id).val();
				var document_name = $('#document_name'+id).val();
				var course_group_id = $('#course_group_id').val();
				
				myFormData.append('document', this.files[0]);
				myFormData.append('course_group_id', course_group_id);
				myFormData.append('document_category_id', document_category_id);
				myFormData.append('document_name', document_name);
				$('#loader').addClass('loading');
				$.ajax({
					url: "<?=base_url('student/Document/uploadDoc');?>",
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
		$.ajax({
			url: "<?=base_url('student/Document/checkDocumentStatus');?>",
			type: 'POST',
			dataType : 'json',
			data: {student_id:student_id,course_group_id:course_group_id},
			success: function (data) {
				$('#loader').removeClass('loading');
				if(data.success){
					toastr.success(data.success);
					window.location.href = BASE_URL+"student/dashboard";
					return false;
					}else{
					toastr.error(data.error);
					return false;
				}
			},
		});
	});
</script>