<div class="container-fluid ">
	
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="row mx-auto">	
        <div class="form-group col-md-2 "></div>
        <div class="form-group col-md-4 ">
			<label for="course">Course</label>
			<select name="course_group_id" id="course_group_id_both" class="form-control course_group_id" data-target="#class_id" required >
				<option value="">Select Course</option>
				<?php 
               
				//$courses = $this->db->get_where('course', array())->result_array();
				foreach($courses as $course)
				{
					?>

					<option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>

					<?php
				} 
				?> 
			</select>       
		</div>
		<div class="form-group col-md-4 ">
			<label for="class_id">Class</label>
			<select name="class_id" id="class_id" class="form-control"  required >
				<option value=""></option>
			</select>       
		</div>
                    
	
		
	
	</div>

	
	<div align="center" id="myLoader" class="loader_div" style="display: none;" >
		<svg>
			<circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
			<circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
		</svg>
	</div>
	<div id="dt" align="center" class="mx-auto">
	</div>
</div>
<script>
	
    $("#course_group_id_both").on('change', function(){
            var course = $(this).val();
       
            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $('#class_id').html("");
            $("#myLoader").show();
            $.ajax({
                method: "POST",
                url: '<?php echo site_url('center/center/getClassByCourseForBoth'); ?>',
               
                data: {course : course,[csrfName]:csrfHash },
            })
            .done(function( msg ) {
                $('#class_id').html(msg);
                $('#myLoader').hide();
                $('#dt').html("");
            });
        });
        $("#class_id").on('change', function(){
            var course = $("#course_group_id_both").val();
            var class_id = $(this).val();
       
            var csrfName = $('.csrfname').attr('name');
            var csrfHash = $('.csrfname').val(); 
            $("#myLoader").show();
            $.ajax({
                method: "POST",
                url: '<?php echo site_url('center/center/getExamTimeTable'); ?>',
               
                data: {class_id : class_id,course : course,[csrfName]:csrfHash },
            })
            .done(function( msg ) {
                $('#myLoader').hide();
                $('#dt').html(msg);
            });
        });    
	
function PrintDiv() {
    var title="Time Table 2022";
    var contents = document.getElementById('ss').innerHTML;
    var frame1 = document.createElement('iframe');
    frame1.name = "frame1";
    frame1.style.position = "absolute";
    frame1.style.top = "-1000000px";
    document.body.appendChild(frame1);
    var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
    frameDoc.document.open();
     frameDoc.document.write(`<style>@page{size:landscape;}@tr{border: solid 1px #000;background-color:#E8F6FF;}</style><html><head><title>${title}</title>`);
    frameDoc.document.write('</head><body>');
    frameDoc.document.write(contents);
    frameDoc.document.write('</body></html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        document.body.removeChild(frame1);
    }, 500);
    return false;
}
</script>
