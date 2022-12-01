<style type="text/css">
	form#ajaxForm td {
    vertical-align: middle;
}
 .fit{
	border: none;
	background-color: transparent;
	outline: none;
	padding: 0;
	width:300px;
	}
p.pagination-new.text-center {
    padding: 20px;
}
p.pagination-new.text-center strong, p.pagination-new.text-center a {
    font-size: 16px;
    padding: 0px 7px;
    box-shadow: 0px 0px 2px 1px;
    margin: 0 10px;
}
.float-right.mt-1{
	display: none !important;
}
</style>

<div class="container">
	<div class="text-center">
		<h3>Maharishi Mahesh Yogi Vedic Vishwavidyalaya</h3>
		<h3>Madhypradesh</h3>
	</div>
	<table class="w-100 mt-10 table-secondary table ">			
		<tr>
			<th>Name of Exam:</th>
			<td><?php echo $papers->course_name; ?></td>
			<th>Subject:</th>
			<td><?php echo $papers->paper_name; ?></td>
		</tr>
		<tr>
			<th>Year/Sem:</th>
			<td><?php echo $this->Common_model->getClassNameByClassId($papers->class_id); ?></td>
			<th>Date of Exam:</th>
			<td><?php echo $papers->exam_date; ?></td>
		</tr>
		<tr>
			<th>Exam Session:</th>
			<td>Feb 2022</td>
				<th>Max.Marks:</th>
				<td>
					<?php 
					if ($university_mode=='PVT'){
					echo $max_marks =$papers->max_theory_marks;
					echo ' ( Theory marks ) ';
					$max_int_marks = 0;
					 }elseif($university_mode=='REG'){ 
					echo $max_marks = $papers->max_theory_marks;
					echo ' ( Theory marks ) ';
					echo ' + ';
					echo $max_int_marks = $papers->max_internal_marks;
					echo ' ( Internal marks ) ';
					} 
					?>
				</td>
			</tr>
		</table>
		<div></div>
		<form id="ajaxForm" class="mt-10" > 
		<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" id="csrf" value="<?= $hash_csrf; ?>">
			<table  class="table table-bordered mx-auto mb-4">
				<thead>
					<tr>
						<th>Enrollment no.</th>
						<th>Roll No.</th>
						<th>Theory Marks</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$count = 1 ;
					foreach($resultData as $dt){
						?>
						<tr>
							<td>
								<?php echo $dt->enrollment_no; ?>
							</td>
							<td><?php echo $dt->roll_no; ?>  
								<input type="hidden" name="student_id[]" value="<?=$dt->student_id?>">
								<input type="hidden" name="paper_code" value="<?=$paper_code?>">
							</td>
							<td class="fit">	
								<select name="marks[]" class="form-control" id="marks<?php echo $count ?>">
									<option value="">Select</option>
									<option>ABS</option>
									<?php
									for ($i=0; $i<=$max_marks; $i++)
									{
										?>
										<option><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
										<?php
									} 
									?>
								</select>	
							</td>
							<?php
							$count++;
							?>
						</tr>
						<?php
				    	}
					?>
					<input type="hidden" id="total_count" value="<?= $count ?>">
				</tbody>
			  </table>
			<div class="row">
			<button type="button"  id="submit" class="btn btn-primary m-auto">submit</button>
			</div>
		</form> 
		<p class="pagination-new text-center"><?php echo $links; ?></p>
	</div>
	<script>
		$(document).ready(function(){	
			var max_marks = <?php echo $max_marks ?>;
			if ($('#int_marks1').length>0) {
				var max_int_marks = <?php echo $max_int_marks; ?>;
			} 
		
			$("#submit").on('click',function (){
				 var marks = $('#marks').val();
				 var submit = true;

				 if(marks==''){
				 	$(marks).next().text('Please Select Marks');
				 	submit = false;
				 }
				 else{
				 	$(marks).next().text('');
				 }
				 if(submit==false){
				 	return false;
				 }
				 var  serialized = $('#ajaxForm').serialize();
				 $.ajax({
				 	url: BASE_URL+"admin/Dataentry/marks_entry_form_sub",
				 	type: 'POST',
				 	dataType : 'json',
				 	data: serialized ,
				 	success: function (data) {
				 		if(data.success){
				 			toastr.success(data.success);
							 window.location.reload();
				 		}else{
				 			toastr.error(data.error);
				 		}
				 	},
				 });
			     });

		var marks_array = [];
		var int_marks_array = [ ];
		for(var i = 0; i <= max_marks; i++){
			marks_array.push(i);
		}
		marks_array.push('ABS');
		
		// var total_count = document.getElementById('total_count').value;
		// 	for(var i = 1; i <= total_count; i++){
		// 		$('#marks'+i).select2();
		// 	}

		// 	// on first focus (bubbles up to document), open the menu
		// 	$(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
		// 	  $(this).closest(".select2-container").siblings('select:enabled').select2('open');
		// 	});

		// 	// steal focus during close - only capture once and stop propogation
		// 	$('select.select2').on('select2:closing', function (e) {
		// 	  $(e.target).data("select2").$selection.one('focus focusin', function (e) {
		// 	    e.stopPropagation();
		// 	  });
		// 	});
		// });
	</script>