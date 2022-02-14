<?php
$docCatId = array();
$d=0;
foreach($documentData as $document){
	if($document->status=='Y'){
		continue;
	}
	$docCatId[$d] = 'docCatId_'.$document->id;
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
						<?php echo $student->name; ?>
					</div>
				</div>
			</div>
			<div class="col-md-4 border-right-dashed">
				<div class="row py-2">
					<label class="col-sm-3 text-heading">Session</label>
					<div class="col-sm-9 text-value">
						<?php echo $student->session; ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="row py-2">
					<label class="col-sm-3 text-heading">Course</label>
					<div class="col-sm-9 text-value">
						<?php echo $student->course_name;  ?>
					</div>
				</div>
			</div>
			<div class="col-md-4 border-right-dashed">
				<div class="row py-2">
					<label class="col-sm-3 text-heading">Father</label>
					<div class="col-sm-9 text-value">
						<?php echo $student->f_h_name; ?>
					</div>
				</div>
			</div>
			<div class="col-md-4 border-right-dashed">
				<div class="row py-2">
					<label class="col-sm-3 text-heading">Form No</label>
					<div class="col-sm-9 text-value">
						<?php echo $student->student_id; ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="row py-2">
					<label class="col-sm-3 text-heading">Class</label>
					<div class="col-sm-9 text-value">
						<?php echo $student->class_name; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($student->remark_detail!=''): ?>
	<div class="col-sm-12 bg-secondary py-4">
		<h3><?=$student->remark_detail ?></h3>
	</div>
<?php endif ?>

<div class="input-div">
	<?php if($student->remark!=''){ ?>
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<form method="post" action="" enctype='multipart/form-data' id="target" >
	<div id="loader">
	</div>
		<input type="hidden" name="course_group_id" id="course_group_id" value="<?=$student->course_group_id; ?>">
		<input type="hidden" name="student_id" id="student_id" value="<?=$student->student_id; ?>">
		<div class="row">
		<?php	foreach($documentData as $document){ ?>
			<div class="col-md-5">
				<div class="form-group">
					<label class="w-100"><?=$document->document;?><strong class="text-danger"><?= ($document->status=='Y') ? '' : '*'; ?></strong>	
					</label>

					<input type="hidden" name="document_name<?=$document->id?>" id="document_name<?=$document->id?>" value="<?=$document->document?>" >
					<input type="hidden" name="document_category_id<?=$document->id?>" id="document_category_id<?=$document->id?>" value="<?=$document->id?>" >
					<div></div>
					<div class="custom-file">
						<input type="file" accept="image/*,application/pdf" class="custom-file-input" id="<?='docCatId_'.$document->id?>" data-id="<?=$document->id?>" name="document[]" >
						<label class="custom-file-label" for="<?='docCatId_'.$document->id?>"></label>
					</div>
					<div class="fv-plugins-message-container"></div>
				</div>
			</div>
			<div class="col-1 m-auto">
				<div id="<?='downloadBtnId_'.$document->id;?>">
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="row justify-content-center my-3">
		<a href="<?=base_url('center/not_approve_student_list')?>" class="btn btn-primary">Submit</a>
	</div>
</form>
<?php }
?>
	</div>
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
					url: "<?=base_url('center/Document/uploadRemainingDocument');?>",
					type: 'POST',
					processData: false, 
					contentType: false, 
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