
<div class="text-center">
    <input type="hidden" class="csrfname" name="<?= $name_csrf ?>" value="<?= $hash_csrf ?>">
	<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="80%" >
		<thead>
			<tr>
				<th>#</th>
                <th>F.No</th>
                <th>Session</th>
                <th>Student Name</th>
                <th>Course</th>
                <th>Class</th>
                <th>Transaction Id</th>
                <th>Prospectus Fees</th>
                <th>Registration Fees</th>
                <th>Program Fees</th>
                <th>Exam Fees</th>
                <th>Backlog Exam Fees</th>
                <th>Late Fees</th>
                <th>Total Fees</th>
                <th>Action</th>
			</tr>
		</thead>
		<tbody>
            
			<?php 
			
			$i = 1;
          
			
			foreach($studentData as $student){
                  $prospectus='-';
            $registration='-';
            $program ='-';
            $exam='-';
            $backlog='-';
            $late='-';
            $total=0;
            $fees =0;
                if($student->fees_head == 'Admission Fees'){
                    $prospectus = $student->form_fees;
                    $registration = ($student->mode =="REG") ?$student->admission_fees:$student->admission_fees;
                    $total = $prospectus + $registration;
                    $late = $student->remark== 'With Late Fees'? $student->amount - $total : '-';
                }elseif($student->fees_head == 'Exam Fees'){
                    $exam = ($student->mode == 'REG')?$student->exam_fees:$student->p_exam_fees;

                if($student->remark == 'Demo Exam Fees' || $student->remark == 'Late Demo Exam Fees'){
                        $program = '-';
                    }else{
                         $program = ($student->mode == 'REG')?$student->program_fees:$student->p_program_fees;
                    }
                  
                    $total = $exam + ($program != '-' ? $program : 0);
                    $late = ($student->remark == 'Late Exam Fees' || $student->remark =='Late Demo Exam Fees') ? $student->amount - $total : '-';
                    
                }elseif($student->fees_head == 'Backlog Exam Fees'){
                    $backlog_id = $this->Common_model->getRecordByWhere('backlog_student', array('student_id' => $student->student_id, 'course_group_id' => $student->course_group_id, 'class_id' => $student->class_id, 'exam_year' => $student->exam_session));
                    $where = array(
                    'course_group_id' => $student->course_group_id,
                    'class_id' => $student->class_id,
                    'backlog_student_id' => $backlog_id[0]->id,
                    'paper_type' =>'Theory',
                    'status' => 'B'
		        );
		
		        $fees = $this->Common_model->getCountByWhere('backlog_exam_form',$where);

                    $backlog = ( $fees < 8)?$fees*100:750;
                    $total = $backlog;
                }elseif($student->fees_head == 'Form Fees'){
                    $prospectus = $student->form_fees;
                   $total = $prospectus;
                }
                   
                ?>
                <tr id="row_<?php echo $student->id; ?>">

                <td><?php echo $i++; ?></td>
                <td><?php echo $student->student_id; ?></td>
                <td><?php echo $student->session; ?></td>
                <td><?php echo $student->name; ?></td>
                <td><?php echo $student->course_name; ?></td>
                <td><?php echo $this->Common_model->getClassNameByClassId($student->class_id); ?></td>
                <td><?php echo $student->txnId; ?></td>
                <td><?= $prospectus?></td>
                <td><?= $registration?></td>
                <td><?=$program?></td>
                <td><?=$exam?></td>
                <td><?= $backlog?></td>
                <td><?=$late?></td>
                <td><?= $student->amount?></td>
                <td><button type="reset" class="btn btn-success mr-4 pay"  data-toggle="modal" data-id="<?=$student->id?>" data-head="<?= $student->fees_head?>" data-date="<?= $student->payment_date?>" data-amount="<?=$student->amount ?>" data-form="<?=$student->student_id?>" data-student="<?= $student->name?>" data-target="#kt_datepicker_modal" >Verify</button></td>
                </tr>
                <?php
            }
            
            ?>
		</tbody>
	</table>
<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Verify Transaction</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="card card-custom">

 <!--begin::Form-->
<form method="POST" class="d-block" id="ajaxForm" action="">
  <div class="card-body">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Form No</label>
    <div class="col-7 mt-2">
       <p id="form_no"></p>
    </div>
   </div>
    <div class="form-group row">
     <label for="example-date-input" class="col-5 col-form-label">Student Name</label>
     <div class="col-7 mt-2">
         <p id="student_name"></p>
     </div>
    </div>
     <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Fees Head</label>
    <div class="col-7 mt-2">
       <p id="fees_head"></p>
    </div>
   </div>
    <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Total Amount</label>
    <div class="col-7 mt-2">
       <p id="total_amount"></p>
    </div>
   </div>
    <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Payment Date</label>
    <div class="col-7 mt-2">
       <p id="payment_date"></p>
    </div>
   </div>
  
   <div class="form-group row">
    <label for="example-date-input" class="col-5 col-form-label">Settle Date</label>
    <div class="col-7">
        <input type="hidden" value="" name="uid" id="uid">
   <input class="form-control" type="date" id="example-date-input" name="settle_date" max="<?php echo date('Y-m-d'); ?>" required>
    </div>
   </div>
  
   
    
  <div class="card-footer pb-0">
   <div class="row justify-content-center">
  
 
     <button type="reset" class="btn btn-success mr-4" id="payment_submit">Submit</button>

   </div>
  </div>
 </form>
</div>
		</div>
	</div>
</div>
