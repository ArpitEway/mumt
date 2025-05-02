<div class="mt-3"><a href="<?=BASE_URL('admin/Admins/centers')?>" class="btn btn-outline-primary float-right">centers</a></div>
<form method="post" action="" class="mt-3 ajaxForm" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Course Name</th>
					<th><input type="checkbox" id="allEnrolled"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=1;
				$center = $this->db->get_where("center", array("id" => $center_id))->row();
				if($center){
					$allot_course = str_replace(' ', '', $center->allot_course_group_id);
					$allot_course_group_id = explode(",", $allot_course);
					foreach($courses as $course){
						// if (in_array($course['id'], $allot_course_group_id)){
						?>
						<tr>
							<td><?=$i++;?></td>
							<td><?=$course['course_name'];?></td>
							<td><input type="checkbox" name="course_group_id[]" id="course_group_id" value="<?=$course['id'];?>"
								<?php if (in_array($course['id'], $allot_course_group_id)){ ?> checked="checked" <?php } ?> ></td>
							</tr>
							<?php 
						// }
					 }
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="text-center p-3">
			<button type="button" class="btn btn-primary btn-lg" name="submit" id="center_submit"> Allot Courses </button>
		</div>
	</form>
	<script>
		$(document).ready(function() {
		// Check All
		$('#allEnrolled').on('change', function() {
			if($('#allEnrolled').is(":checked")){
				$(":checkbox").attr("checked", true);
			}else{
				$(":checkbox").attr("checked", false);
			}
		});
	});

		$("#center_submit").on('click',function (e){
			var frm = $('.ajaxForm').serialize();
			var center_id = <?php echo $center_id; ?>;
			$.ajax({
				url: '<?php echo site_url('admin/admins/allot_course/allot/'); ?>'+center_id,
				type: 'POST',
				dataType : 'json',
				data: frm,
				success: function (data) {
					if(data){
						console.log(data);
						toastr.success("Alloted Course Successfully");	
					}else{
						toastr.error("Something wrong");
					}
				},
			});
		});
	</script>