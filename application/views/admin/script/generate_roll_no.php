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
					$whereclass = array('exam_form_permission' => 'Y','temp_id!=' => 0);
					$classData = $this->Common_model->getRecordByWhere('class_master',$whereclass);
					foreach ($classData as $class) {
						$where = array(
							'new_exam_form' => 'Y',
							'roll_no' =>'0',
							'class_id' => $class->id,
						);
						
						$students = $this->Common_model->getRecordByWhereByOrder('student',$where,'center_id,name','ASC');
						$whereRollNo = array(
							'new_exam_form' => 'Y',
							'roll_no !=' =>'0',
							'class_id' => $class->id,
						);
						$count = $this->Common_model->getCountByWhere('student',$whereRollNo);
						$last_number = ($count==0) ? '10001'  : $count+10001;	
						foreach ($students as $student) {
								$roll_no = ($student->university_mode=='REG') ? '1'.$class->temp_id.$last_number : '2'.$class->temp_id.$last_number;
							if($action=='generate'){
								$whereUpdate = array('student_id' => $student->student_id);
								$updateData = array('roll_no' =>$roll_no);
								$this->Common_model->updateRecordByConditions('student',$whereUpdate,$updateData);
							}
							$last_number++ ;
							?>
							<tr>
								<td><?=$i++;?></td>
								<td><?=$student->name;?></td>
								<td><?=$student->f_h_name;?></td>
								<td><?=$student->course_name;?></td>
								<td><?=$student->class_name;?></td>
								<td><?=$roll_no;?></td>

							</tr>
							<?php
						}
					}
				}
				?>
			</tbody>
		</table>
	</div>
</form>