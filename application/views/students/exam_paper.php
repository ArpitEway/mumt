<style type="text/css">
	.table td, .table th {
		padding: 0.75rem;
		vertical-align: top;
		border-top: none;
		font-size: 14px;
	}
	.table th{
		font-weight: 600;
	}
</style>
<div class="container shadow-sm p-5">
	<div class="table-responsive">
		<table class="table " >
			<input type="hidden"  value="<?php echo $student['student_id'] ; ?>" id="student_id">
			<tbody>
				<tr>
					<th>Roll No:</th>
					<td><?=$student['roll_no'];?></td>
					<th>Enrollment No:</th>
					<td><?=$student['enrollment_no'];?></td>
				</tr>
				<tr>
					<th>Student Name:</th>
					<td><?=$student['name'];?></td>
					<th>Father/Husband Name:</th>
					<td><?=$student['f_h_name'];?></td>
				</tr>
				<tr>
					<th>Course/Class:</th>
					<td colspan="3"><?=$student['course_name'];?> (<?=$student['class_name'];?>) </td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="container shadow-sm p-5 mt-10">
	<h4 class="text-center mt-10"><a href="<?=base_url('assets/instruction/')?>Answer sheet Front Page.pdf">Answer Sheet First Page</a></h4>
	<div class="table-responsive">
		<table class="table ">
			<tbody>
				<tr>
					<th>Paper Name</th>
					<th>Paper Code</th>
					<th>View</th>
					<th>Answersheet</th>
				</tr>
				<?php
				foreach($papers as $paper){

					$pdf = "exam_pdf/".$paper->test_id.'.pdf';
					?>
					<tr>
						<td><?php  echo $paper->paper_name ; ?></td>
						<td><?php echo $paper->paper_code; ?></td>
						<td>
						<?php if (file_exists(FCPATH.$pdf)): ?>
							<a target="_blank" href="<?php echo base_url($pdf); ?>">show</a>
						<?php else: ?>
							Coming Soon
						<?php endif; ?>
						</td>
						<td>
						<?php 
						$where = array(
							'class_id' => $student["class_id"],
							'student_id' => $student["student_id"],
							'paper_code' =>$paper->paper_code
						);
						// $this->Common_model->debug_data($where);
						$data = $this->Common_model->getRecordByWhere('upload_exam_ans_sheet',$where);

						$count = count($data);
						$data[0]->answer_sheet;
						$path = FCPATH.'/assets/exam_answersheet/'.$data[0]->upload_date.'/'.$data[0]->answer_sheet;
						if($count>=1 &&  file_exists($path) && $data[0]->answer_sheet!=''){
							
						?>
							<button disable type="button" class="btn btn-success">Submitted</button>
							
                            <?php
                           if($this->session->has_userdata('studentdata')){
                             ?>
						<a href="javascript:void(0);" onclick="DeleteModal('<?php echo base_url('student/Student/delete_exam_answersheet/').$data[0]->id; ?>')" data-toggle="tooltip" title="Delete Answer-Sheet!"><i class="mdi mdi-delete ml-5"></i></a>
                           <?php  } ?>

							<?php
						}elseif(file_exists(FCPATH.$pdf)){

                 	$paper_id = $this->Common_model->encrypt_decrypt($paper->id,'encrypt'); 
                 	$student_id = $this->Common_model->encrypt_decrypt($student['student_id'],'encrypt');
                 	
                 	if($this->session->has_userdata('centerdata')){
                 		$account_type= 'center/center/upload_anwser_sheet/';
                 	}else{
                 		$account_type= 'student/Student/upload_anwser_sheet/';

                 	}
							?>
							<a  href="<?php echo  base_url($account_type).$paper_id .'/'.$student_id;?>" class="btn btn-dark">Upload</a>
							<?php
						}
						?>
						</td>
					</tr>
					<?php 
				}
				?>
			</tbody>
		</table>
	</div>
</div>