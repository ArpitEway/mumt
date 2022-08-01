<div class="container-fluid ">
	
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="row mx-auto">	
        <div class="form-group col-md-3 "></div>
        <div class="form-group col-md-3 ">
			<label for="course">Course</label>
			<select name="course_group_id" id="course_group_id_both" class="form-control course_group_id" data-target="#class_id" required >
				<option value="">Select Course</option>
				<?php 
                $this->db->select('*');
                $this->db->from('course');
                $this->db->join('paper_master', 'paper_master.course_group_id = course.course_group_id');
                $this->db->where('exam_date!=',''); 
                $this->db->where('paper_master.type','theory'); 
               
                $this->db->group_by('paper_master.course_group_id');
                
                $courses= $this->db->get()->result_array();
				//$courses = $this->db->get_where('course', array())->result_array();
				foreach($courses as $course)
				{
					?>

					<option value="<?php echo $course['course_group_id']; ?>"><?php echo $course['course_name']; ?></option>

					<?php
				} 
				?> 
			</select>       
		</div>
		<div class="form-group col-md-3 ">
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
	
//     function PrintDiv() 
//    {  
//        var divContents = document.getElementById("dt").innerHTML;  
//        var printWindow = window.open('', '', 'height=400,width=400');  
//        printWindow.document.write('<html><head><title>Print DIV Content</title>');  
//        printWindow.document.write('</head><body >');  
//        printWindow.document.write(divContents);  
//        printWindow.document.write('</body></html>');  
//        printWindow.document.close();  
//        printWindow.print();  
//     }  
</script>