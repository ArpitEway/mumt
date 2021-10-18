<style>
.doc-notification	li{
	line-height: 2.5;
}
.doc-notification {
	background: beige;
	padding: 15px 15px 0px;
	border-radius: 20px;
}
</style>
<div class="col-md-12">
	<div class="card card-custom bgi-no-repeat gutter-b card-stretch mt-3">
		<div class="card-body">
			<div class="doc-notification">
				<p><strong>			अतिरिक्त पाठ्यक्रम में प्रवेश </strong></p>
				<p>महर्षि पाणिनि संस्कृत एवं वैदिक विश्वविद्यालय, उज्जैन के विद्यार्थी वर्तमान पाठ्यक्रम के साथ साथ अन्य किसी पाठ्यक्रम में प्रवेश ले सकते हैं. इसके लिए निम्न नियम लागु होंगे-</p>
				<ul>
					<li>
						एक वर्ष में एक डिग्री पाठ्यक्रम के साथ एक डिप्लोमा अथवा एक डिप्लोमा पाठ्यक्रम के साथ एक डिग्री पाठ्यक्रम में प्रवेश हेतु आवेदन किया जा सकता है.
					</li>
					<li>
						किसी भी डिग्री अथवा डिप्लोमा पाठ्यक्रम के साथ एक प्रमाणपत्र पाठ्यक्रम में प्रवेश हेतु आवेदन किया जा सकता है.
					</li>
					<li>

						आवश्यकता होने पर विद्यार्थी को अर्हता सम्बन्धी डाक्यूमेंट्स पोर्टल पर अपलोड करना होंगे. 
					</li>
					<li>
						विवि प्रशासन द्वारा विद्यार्थी के आवेदन की जाँच की जाएगी. आवेदन के सही और नियमानुसार पाए जाने पर ही सम्बंधित पाठ्यक्रम में प्रवेश दिया जा सकेगा.
					</li>
				</ul>	
			</div>
			<form class="form" id="newadmission">
				<div class="form-group row">
					<div class="col-lg-6">
						<label>Course:</label>
						<select class="form-control" required="required" name="course_group_id" id="course_group_id">
							<option value="">Select Course</option>
							<?php foreach ($course_list as $course) {
								echo '<option value="'.$course->id.'">'.$course->course_name.'</option>';	
							} ?>
						</select>
						<span id="errCourse" class="form-text text-danger"></span>
					</div>
					<div class="col-lg-6">
						<label>Class:</label>
						<select class="form-control" required="required" name="class_id" id="class">
							<option value="">Select class</option>
						</select>
						<span id="errCourse" class="form-text text-danger"></span>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-lg-12">
							<button type="button" id="kt_sweetalert_admission" class="btn btn-primary mr-2">Submit</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#course_group_id").on('change', function(){
		var course = $(this).val();
		$.ajax({
			method: "POST",
			url: BASE_URL+"student/Student/getAdmissionClassByCourse",
			data: {course : course},
		})
		.done(function( msg ) {
			$('#class').html(msg);
		});
	});


	$("#kt_sweetalert_admission").click(function(e) {
		var class_id = $('#class').val();
		var course_group_id = $('#course_group_id').val();
		var submit = true;
		if(class_id==''){
			$('#class').next().text('Class is Required');
			submit = false;
		}else{
			$('#class').next().text('');
		}
		if(course_group_id==''){
			$('#course_group_id').next().text('Course is Required');
			submit = false;
		}else{
			$('#course_group_id').next().text('');
		}
		if(submit){
			Swal.fire({
				title: "Are you sure?",
				text: "want to get admission",
				icon: "info",
				showCancelButton: true,
				confirmButtonText: "Yes"
			}).then(function(result) {
				if (result.value) {
					$.ajax({
						url: BASE_URL+'student/student/newAdmissionSub',
						data: $('#newadmission').serialize(),
						method: 'POST',
						dataType: "json",
					}).done(function(response){
						if (response.success) {
							Swal.fire(
								"",
								"Your Form Submited Successfully.",
								"success"
								)
							window.location = BASE_URL+'student';
						}else{
							Swal.fire(
								"",
								"an error occurred",
								"danger"
								)
						}
					});
				}
			});
		}else{
			return false;
		}
	});
</script>