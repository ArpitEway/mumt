<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/Admins/paper/create'); ?>">
    <div class="form-row" id="addedRows">
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
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
            <select name="class_id" id="class_id" class="form-control" required >  
			</select>
		</div>
		
		<div class="form-group col-md-6">
            <label for="name">Paper name</label>
            <input type="text" class="form-control" id="paper_name" name="paper_name[]" placeholder="Enter paper name">        
        </div>
		
		<div class="form-group col-md-6">
            <label for="code">Paper code</label>
            <input type="text" class="form-control" id="paper_code" name="paper_code[]" placeholder="Enter paper code">        
        </div>
		<div class="form-group col-md-4">
            <label for="type">Type</label>
            <select name="type[]" id="type" class="form-control" required >
                <option value="theory">Theory</option>
				<option value="project">Project</option>
				<option value="practical">Practical</option>
            </select>
        </div>
		<div class="form-group col-md-4" id="ce_div">
		<label for="ce">CE</label>
		<select name="ce[]" id="ce" class="form-control ce" required data-target="#group" >
		<option value="compulsory">Compulsory</option>
		<option value="elective">Elective</option>
		</select>
        </div>
		<div class="form-group col-md-4" id="group">
			
		</div>
        
		
		<div class="form-group col-md-6">
        </div>
		<div class="form-group col-md-6" style="text-align: right;" >
                 <button  type="button" class="plus_btn" onclick="addMoreRows(this.form);" >Add more</button>
        </div>
		
		
        </div>
		
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" type="submit">Submit</button>
	</div>
		
</div>
</form>



<script>
//$(".ajaxForm").validate({}); // Jquery form validation initialization

$(document).on("change", "#class_id", function() {
	
		var type = $('option:selected', this).attr('data-type');
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var data = {
			id: $(this).val(),
			course_group_id: $("#course_group_id").val(),
			[csrfName]:csrfHash
		};
		var target = $(this).attr("data-target");
		var url = BASE_URL + "admin/Admins/get_paper_code";
		var response = call_ajax(data, url);
		if(response) {
			console.log(response);
			console.log(response.data);
			$("#paper_code").val(response.data);
			
		} 
});
$(document).on("change", "#course_group_id", function() {
	var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var type = $('option:selected', this).attr('data-type');
		var data = {
			id: $(this).val(),
			[csrfName]:csrfHash
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
$(document).on("change", ".ce", function() {
		
		var opt = $(this).val();
		var target = $(this).attr("data-target");
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		if(opt == "elective")
		{
			var data = {
				id: $("#class_id").val(),
				[csrfName]:csrfHash
			};
			$i = 1;
			var url = BASE_URL + "admin/Admins/get_group_by_class";
			var response = call_ajax(data, url);
			if(response.status == true) {
				$(target).html('<label for="course" id="group_label">Group</label><select name="group_name[]" id="groups" class="form-control groups" ><option value="">Select group</option>');
				for(var i = 0; i < response.data.length; i++) 
				{
					$(".groups").append('<option value="' + response.data[i].id + '">' + response.data[i].group_name + '</option></select>');
				}
			} 
			
		}else{
			$('#group_label').remove();
			$('#groups').remove();
		}
});

$(document).on("change", ".add_ce", function() {
		
		var opt = $(this).val();
		var target = $(this).attr("data-target");
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		if(opt == "elective")
		{
			var v = $(this).closest('div').siblings('.add_div').html()
			
			var data = {
			id: $("#class_id").val(),
			[csrfName]:csrfHash
			};
			$i = 1;
			var url = BASE_URL + "admin/Admins/get_group_by_class";
			var response = call_ajax(data, url);
			if(response.status == true) {
			$(this).closest('div').siblings('.add_div').html('<label for="course" id="group_label">Group</label><select name="group_name[]" id="groups" class="form-control add_group" ><option value="">Select group</option>');
			for(var i = 0; i < response.data.length; i++) 
				{
				  $(".add_group").append('<option value="' + response.data[i].id + '">' + response.data[i].group_name + '</option></select>');
				}
			} 
			
		}else{
			
			$(this).closest('div').siblings('.add_div').html("")
		}
});

// $(document).on("change", "#class_id", function() {
	// $('#ce_div').html('');		
// });

$(".ajaxForm").submit(function(e) {
    var form = $(this);
    ajaxSubmit(e, form, showAlldepartment);
});

$('#class_group').on('change', function() {
  if(this.value == "Y"){
	
	  addRow(this.form);
  }
});

function addMoreRows(frm){
	var rowCount = $("#rowcount").val();
	var field = '<div class=" form-group row paper_'+rowCount+' "><div class="form-group col-md-6"><label for="name">Paper name</label><input type="text" class="form-control" id="paper_name" name="paper_name[]" placeholder="Enter paper name"></div><div class="form-group col-md-6"><label for="code">Paper code</label><input type="text" class="form-control" id="paper_code" name="paper_code[]" placeholder="Enter paper code"></div><div class="form-group col-md-4"><label for="type">Type</label><select name="type[]" id="type" class="form-control" required ><option value="theory">Theory</option><option value="project">Project</option><option value="practical">Practical</option></select></div><div class="form-group col-md-4"><label for="ce">CE</label><select name="ce[]" id="ce" class="form-control add_ce" required ><option value="compulsory">Compulsory</option><option value="elective">Elective</option></select></div><div class="form-group col-md-4 add_div"></div><div class="form-group col-md-12" style="text-align:right;" ><br><button type="button" class="minus_btn" onclick="RemoveRow('+rowCount+');">Remove </button><input type="hidden" id="rowcount" value="1"><button  type="button" style="margin-left: 10px;"  class="plus_btn" onclick="addMoreRows(this.form);" >Add more</button></div></div></div>';
	rowCount++;
	$("#rowcount").val(rowCount);
	$('#addedRows').append(field);
}

function RemoveRow(id){
	$('.paper_'+id).remove();
}
	
</script>

