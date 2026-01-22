<form method='POST'  action="<?=base_url('admin/scripts/Preexam/generate_roll_no')?>" >
	<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
	<div class="col-sm-4 m-auto">
		<fieldset class="form-group">
			<label for="exampleSelect1">Action</label>
			<select class="form-control" name="action" id="action">
				<option value="view" <?php if($action=='view'){echo "selected" ;} ?>>View</option>
				<option value="generate" <?php if($action=='generate'){echo "selected" ;} ?> >Generate</option>
			</select>
		</fieldset>
		<fieldset class="form-group text-center">
			<button type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button>
		</fieldset>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Form No.</th>
					<th>Name</th>
					<th>Father's / Husband's Name</th>
					<th>Course Name</th>
					<th>Class Name</th>
					<th>Roll No.</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($action =='generate' || $action=='view'){


					$i=1;
					
					// $this->db->where_not_in('id',array(255,257));
					$whereclass = array('temp_id!=' => 0,'exam_form_permission' => 'Y');
					 
					 //'exam_form_permission' => 'Y'
					// $this->db->where_not_in('id',array(268,264,270));

					// $this->db->where_in('id',array(154,172,181,155,182,193,195,197,199,201,203,205,207,209,211,213,302,275,279,221,223,225,227));

					// $this->db->where_in('id',array(215,217,229,231,233,235,237,239,241,243,245,247,249,251,253,277,281,304,406,410,414,418,422,426,430,434,438,442,446,450,454,458,462,466,470,474,478,504,508,512,155,182,154,181));

					// $this->db->where_in('id',array(261,263,267,269));

					$classData = $this->Common_model->getRecordByWhere('class_master',$whereclass);
					$j=0;
					foreach ($classData as $class) {
						$where = array(
							'new_exam_form' => 'Y',
							'roll_no' =>'0',
							'class_id' => $class->id,
							'course_group_id' => $class->course_group_id
						);
						// $this->db->where_not_in('center_id',array('261','1252'));
						// $this->db->where_not_in('student_id',array(724546,739541,745428));
						//$this->db->limit(100);
						$students = $this->Common_model->getRecordByWhereByOrder('student',$where,'center_id,name','ASC');
					
						$whereRollNo = "new_exam_form = 'Y' and roll_no !='0' and class_id = $class->id";
						$countData = $this->db->query("Select max(substr(`roll_no`, 2, 8)) as afterRemove from student WHERE $whereRollNo")->row();
						$count = $countData->afterRemove;
						$last_number = ($count==0) ? $class->temp_id.'10001'  : $count+1;
						
					//	echo $this->db->last_query(); die;
						foreach ($students as $student) {
							$j++;
								$roll_no = ($student->university_mode=='REG') ? '1'.$last_number : '2'.$last_number;
							if($action=='generate'){
								$whereUpdate = array('student_id' => $student->student_id);
								$updateData = array('roll_no' =>$roll_no);
								$this->Common_model->updateRecordByConditions('student',$whereUpdate,$updateData);
							
							}
							$last_number++ ;
							?>
							<tr>
								<td><?=$i++;?></td>
								<td><?=$student->student_id;?></td>
								<td><?=$student->name;?></td>
								<td><?=$student->f_h_name;?></td>
								<td><?=$student->course_name;?></td>
								<td><?=$student->class_name;?></td>
								<td><?=$roll_no;?></td>

							</tr>
							<?php
							if ($j==10000) {
								break;
							}
						}
						if ($j==10000) {
							break;
						}
					}
				}
				?>
			</tbody>
		</table>
	</div>
</form>