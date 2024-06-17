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
							'exam_year'=>'June 2024'
							
						);
						// $this->db->where_in('class_id',array(101,104,107,110,119,125,128,131,134,153,154,155,159,161,168,171,172,181,182,194,198,202,204,214,216,224,226,228,273,283,285,287,289,291,293,295,297,299));
						//  $this->db->where_in('class_id',array(152,154,155,158,160,162,163,164,165,166,167,172,173,174,175,177,178,180,181,182,193,195,197,199,201,203,205,207,209,211,213,215,217,221,223,225,227,229,231,233,235,237,239,241,243,245,247,249,251,253,255,257,261,263,267,269,275,277,279,281,299,302));
                        //$this->db->where_in('class_id',array(166,167,267,164,165,162,163,169,170,171,174,175,177,178,168,180,173,269));
						$students = $this->Common_model->getRecordByWhereByOrder('backlog_student',$where,'center_id,center_code,class_id,course_group_id','ASC');
						$whereRollNo = "exam_form = 'Y' and roll_no !='0' and exam_year = 'June 2024'";
						$countData = $this->db->query("Select max(substr(`roll_no`, 3, 6)) as afterRemove from backlog_student WHERE $whereRollNo")->row();
						$count = $countData->afterRemove;
						$last_number = ($count==0) ? '1001'  : $count+1;
						foreach ($students as $student) {
								$roll_no = ($student->mode=='REG') ? '91'.$last_number : '92'.$last_number;
							if($action=='generate'){
								$whereUpdate = array('student_id' => $student->student_id,'exam_year'=>'June 2024','class_id' => $student->class_id);
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