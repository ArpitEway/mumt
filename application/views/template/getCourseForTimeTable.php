
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