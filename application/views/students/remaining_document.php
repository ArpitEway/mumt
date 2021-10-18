<?php  
	
	$docCatId = array();
	$d=0;
	foreach($documentData as $document){
		if($document->status=='Y'){
			$docCatId[$d] = 'docCatId_'.$document->id;
			$d++;
		}
	}
?>
<script>
	var docCatIdArray = [<?php echo '"'.implode('","',  $docCatId ).'"' ?>];
</script>
<div class="text-danger my-3">	 डाक्यूमेंट्स अपलोड करते समय सभी की साइज 80 KB से 500 KB के मध्य रखना अनिवार्य है|  </div>
<div class="text-danger my-3">	समस्त डाक्यूमेंट्स अपलोड करने के बाद नीचे दिए गए "Submit" बटन पर अवश्य क्लिक करें| </div>
<div class="text-custom bg-primary p-2 my-3 font-weight-bold d-inline-flex"><i class="far fa-hand-point-right text-custom mr-1"></i><?=$student->remark_detail;?></div>
<form method="post" action="<?=base_url('student/Document/uploadDoc');?>" enctype='multipart/form-data' id="target" >
	<div id="loader">
	</div>
	<div class="row">
		<input type="hidden" name="course_group_id" id="student_id" value="<?=$student->student_id; ?>" >
		<input type="hidden" name="course_group_id" id="course_group_id" value="<?=$student->course_group_id; ?>" >
		<input type="hidden" name="student_id" id="student_id" value="<?=$student->student_id; ?>" >
		
		<?php 		foreach($documentData as $document){ ?>
			<div class="col-md-6">
				<div class="row align-items-center">
					<div class="col-md-8">
						<div class="form-group">
							<label><?=$document->document_name; ?><strong class="text-danger"><?=($document->status=='Y') ? '*' : '';?></strong></label>
							
							<input type="hidden" name="document_name<?=$document->id;?>" id="document_name<?=$document->id; ?>" value="<?=$document->document_name;?>" >
							<input type="hidden" name="document_category_id<?=$document->id;?>" id="document_category_id<?=$document->id;?>" value="<?=$document->id;?>" >
							<div></div>
							<div class="custom-file">
								<input type="file" accept="image/*,application/pdf" class="custom-file-input" id="<?='docCatId_'.$document->id;?>" data-id="<?=$document->id;?>" name="document[]" >							
								<label class="custom-file-label" for="<?='docCatId_'.$document->id;?>"></label>
							</div>
							<div class="fv-plugins-message-container"></div>
						</div>
					</div>
					<div class="col-md-4" id="<?='downloadBtnId_'.$document->id;?>">
					<?php 
						$file = $this->Common_model->GetAdmissonDocFile($student->student_id,$document->id);
						if($file){
							$src = 'assets/documents/'.$file;
						?>
						
							<a href="<?=base_url($src);?>" download>
								Download
							</a>
						<?php }	?>
						</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="mt-5 m-auto text-center">
		<a href="<?=base_url('student/dashboard')?>" class="btn btn-primary">Dashboard</a>
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
				var student_id = $('#student_id').val();
				
				myFormData.append('document', this.files[0]);
				myFormData.append('course_group_id', course_group_id);
				myFormData.append('document_category_id', document_category_id);
				myFormData.append('document_name', document_name);
				myFormData.append('student_id', student_id);
				$('#loader').addClass('loading');
				$.ajax({
					url: "<?=base_url('student/Document/uploadRemainingDocument');?>",
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
</script>