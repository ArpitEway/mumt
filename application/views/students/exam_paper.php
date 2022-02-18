
			<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-stripped" border='0'>
							<input type="hidden" value="<?php echo $student['student_id'] ; ?>" id="student_id">
						  <tbody>
						
							<tr>
								<td><b>Roll No: </b> <?=$student['roll_no'];?></td>
								<td colspan="2"><b>Enrollment No: </b><?=$student['enrollment_no'];?></td>
							</tr>
							<tr>
							  <td><b>Student Name: </b> <?=$student['name'];?></td>
							  <td colspan="2"><b>Father/Husband Name: </b> <?=$student['f_h_name'];?></td>
							</tr>
							<tr>
							  <td><b>Course: </b> <?=$student['course_name'];?></td>
							  <td colspan="2"><b>Class: </b> <?=$student['class_name'];?></td>
							</tr>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
            <h4 class="text-center mt-4 mb-4">Answer Sheet First Page</h4>
            <div class="BoxF border- padding mar-bot txt-center">
				<div class="row">
					<div class="col-12">
						<table class="table ">
							<thead>
								<tr>
									<th>Paper Name</th>
									<th>Paper Code</th>
									<th>View</th>
									<th>Answersheet</th>
								

								</tr>
							</thead>
						  <tbody>
						  <?php
			foreach($papers as $paper){
				
				$pdf= FCPATH."examPdf/".$paper->test_id;
				?>
				<tr>
					<td><?php  echo $paper->paper_name ; ?></td>
					<td><?php echo $paper->paper_code; ?></td>
					<td><a target="_blank" href="<?php echo $pdf ?>">show</a></td>
				
					<?php 
					$where = array(
						 'class_id' => $student["class_id"],
						'student_id' => $student["student_id"],
						 'paper_code' =>$paper->paper_code
					);
					$data = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',$where);
					$count = count($data);
					 $data[0]->answer_sheet;
					 $path = base_url('applications/assets/exam_answersheet'.$data[0]->upload_date.'/'.$data[0]->answer_sheet);
				// 	 echo "<pre>";
				//    echo FCPATH.'applications/assets/exam_answersheet/'.$data[0]->upload_date.'/'.$data[0]->answer_sheet ;
					
					if($count>=1 &&  file_exists($path)){
					?>
                     <td> <button disable type="button" class="btn btn-success">submitted</button></td> 
					<?php
					}
					else{
						  $paper_code = $this->Common_model->encrypt_decrypt($paper->paper_code,'encrypt'); 
						?>
					<td><a  href="<?php echo  base_url('student/Student/upload_anwser_sheet/').$paper_code ;?>" class="btn btn-dark">Upload</a></td>
					<?php
					}
					?>
			    </tr>
			<?php 
		}
		?>
						</tbody>
						</table>
					</div>
				</div>
			</div>

            