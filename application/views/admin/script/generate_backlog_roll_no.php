<form method='POST'  action="<?=base_url('admin/scripts/Preexam/generate_backlog_roll_no')?>" >
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
					    $where = array(
							'exam_form' => 'Y',
							'roll_no' =>'0',
							'exam_year'=>'June 2025'
							
						);
						// $this->db->where_in('class_id',array(154,172,181,155,182,193,195,197,199,201,203,205,207,209,211,213,302,275,279,221,223,225,227));		

						// $this->db->where_in('class_id',array(217,229,231,233,235,237,239,241,243,245,215,304,277,281,247,249,251,253));				
						$students = $this->Common_model->getRecordByWhereByOrder('backlog_student',$where,'center_id,center_code,class_id,course_group_id','ASC');
						$whereRollNo = "exam_form = 'Y' and roll_no !='0' and exam_year = 'June 2025'";
						$countData = $this->db->query("Select max(substr(`roll_no`, 3, 6)) as afterRemove from backlog_student WHERE $whereRollNo")->row();
						$count = $countData->afterRemove;
						$last_number = ($count==0) ? '1001'  : $count+1;
						foreach ($students as $student) {
								$roll_no = ($student->mode=='REG') ? '91'.$last_number : '92'.$last_number;
							if($action=='generate'){
								$whereUpdate = array('student_id' => $student->student_id,'exam_year'=>'June 2025','class_id' => $student->class_id);
								$updateData = array('roll_no' =>$roll_no);
								$this->Common_model->updateRecordByConditions('backlog_student',$whereUpdate,$updateData);
							}
							$last_number++ ;
							?>
							<tr>
								<td><?=$i++;?></td>
								<td><?=$this->Common_model->getStudentNameById($student->student_id);?></td>
								<td><?=$this->Common_model->getStudentFatherNameById($student->student_id);?></td>
								<td><?=$this->Common_model->getCourseNameByCourseId($student->course_group_id);?></td>
								<td><?=$this->Common_model->getClassNameByClassId($student->class_id);?></td>
								<td><?=$roll_no;?></td>

							</tr>
							<?php
						}
					}
				
				?>
			</tbody>
		</table>
	</div>
</form>