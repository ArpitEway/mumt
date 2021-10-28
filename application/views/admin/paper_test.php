<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
<div class="container" style="margin-top:30px;">
<div style="text-align:right;margin:10px">
<a type="button" style="margin-left: 10px;" class="btn btn-outline-primary btn-rounded alignToTitle" onclick="rightModal('<?php echo site_url('admin/modal/popup/admin/paper/create'); ?>', 'Create paper')"  >Create paper</a>
</div>
<div class="row">
<div class="form-group col-md-6">
            <label for="course">Course</label>
            <select name="course_group_id" id="course_group_id" class="form-control course_group_id" data-target="#class_id" required >
                <option value="">Select course</option>
                    <?php 
                    $courses = $this->db->get_where('course', array())->result_array();
                    foreach($courses as $course)
                    {
                    ?>
					
                    <option value="<?php echo $course['course_group_id']; ?>"><?php echo $course['course_name']; ?></option>
                    
					<?php
                    } 
                    ?>
            </select>       
</div>
<div class="form-group col-md-6">
	<label for="class">Class</label>
    <select name="class_id" id="class_id" class="form-control"  > 
	</select>
</div>
</div>


	<div id="dt">
	</div>

</div>
<script>

$(document).on("change", "#course_group_id", function() {
	
		var type = $('option:selected', this).attr('data-type');
		var data = {
			id: $(this).val()
		};
		var target = $(this).attr("data-target");
		var url = BASE_URL + "admin/Admins/get_class_list_by_course";
		var response = call_ajax(data, url);
		if(response.status == true) {
			$(target).html('<option value="">Select class</option>');
			for(var i = 0; i < response.data.length; i++) {
				$(target).append('<option value="' + response.data[i].id + '">' + response.data[i].class_name + '</option>');
			}
		} 
});

$(document).on("change","#class_id",function(){
	if($("#class_id").val()){
		
		var data = {
			class_id : $("#class_id").val()
			};
			
		var url = BASE_URL+"admin/Admins/get_papers_by_class"; 
		var response = call_ajax(data,url);
		console.log(response.data);
		
	     $('#dt').html(response.data);
		 
	} else {
			$('#dt').html("");
	}
});



var showAllpaper = function () 
    {
        var url = '<?php echo site_url('admin/Admins/paper'); ?>';
        $.ajax({
            type : 'GET',
            url: url,
            success : function(response) {
                
                initDataTable('basic-datatable');
            }
        });
    }
</script>