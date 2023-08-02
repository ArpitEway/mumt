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
					
					$this->db->where_in('id',array(101,102,104,105,107,108,110,111,116,117,119,120,125,126,128,129,131,132,134,135,137,138,140,143,146,149,152,153,154,155,158,159,160,161,162,164,165,166,167,168,169,170,171,172,173,174,177,178,180,181,182,183,184,185,187,189,191,192,194,196,198,200,202,204,206,208,210,212,214,216,218,222,224,226,228,230,232,234,236,238,240,242,244,246,248,250,252,254,264,273,274,276,280,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299 ));
					$whereclass = array('temp_id!=' => 0);
					 
					 //'exam_form_permission' => 'Y'
					
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
						$students = $this->Common_model->getRecordByWhereByOrder('student',$where,'center_id,name','ASC');
						$whereRollNo = "new_exam_form = 'Y' and roll_no !='0' and class_id = $class->id";
						$countData = $this->db->query("Select max(substr(`roll_no`, 2, 8)) as afterRemove from student WHERE $whereRollNo")->row();
						$count = $countData->afterRemove;
						$last_number = ($count==0) ? $class->temp_id.'10001'  : $count+1;
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
								<td><?=$student->name;?></td>
								<td><?=$student->f_h_name;?></td>
								<td><?=$student->course_name;?></td>
								<td><?=$student->class_name;?></td>
								<td><?=$roll_no;?></td>

							</tr>
							<?php
							if ($j==2000) {
								break;
							}
						}
						if ($j==2000) {
							break;
						}
					}
				}
				?>
			</tbody>
		</table>
	</div>
</form>