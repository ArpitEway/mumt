<?php 
$student_details = $this->db->get_where('student', array('student_id' => $param1))->result_array();
$new_exam_form = $this->Common_model->getRecordByWhere("new_exam_form",array('student_id'=>$param1));
?>
<form method="POST" class="mt-3"  id="update_remark_status"  >
    <input type="hidden"  class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <div class="container-fluid">
        <table class="table table-striped nowrap">
            <tbody>
                <tr>
                    <th scope="row">Enrollment No.</th>
                    <td><?php echo $student_details[0]['enrollment_no'] ?></td>
                    <th>Roll No.</th>
                    <td><?php echo $student_details[0]['roll_no'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Name</th>
                    <td><?php echo $student_details[0]['name'] ?></td>
                    <th>Father Name</th>
                    <td><?php echo $student_details[0]['f_h_name'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Course</th>
                    <td><?php echo $student_details[0]['course_name'] ?></td>
                    <th>Class</th>
                    <td><?php echo $student_details[0]['class_name'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <table class="table table-bordered table-hover">
        <thead class="table-secondary">
            <tr>
              <th scope="">Paper Name</th>
              <th scope="">Paper Code</th>
              <th scope="">PDF</th>
              <th scope="">Remark</th>
              <th scope="">Teacher</th>
          </tr>
      </thead>
      <tbody>
        <?php 
        foreach($new_exam_form as $paper){
            $upload_exam_answersheet = $this->Common_model->getRecordByWhere("upload_exam_ans_sheet",array('student_id'=>$student_details[0]['student_id'] , 'paper_code'=>$paper->paper_code , 'class_id'=>$paper->class_id));
            ?>
            <tr>
                <td><?php echo $this->Common_model->getPaperNameById($paper->paper_id) ;  ?></td>
                <td><?php echo $paper->paper_code ;  ?></td>
                <td><?php if($upload_exam_answersheet[0]->answer_sheet!=''){
                 $id=$this->Common_model->encrypt_decrypt($upload_exam_answersheet[0]->id,'encrypt');
                 ?>
                 <a target="_blank" href="<?php echo base_url('/admin/ExamController/view_answersheet_pdf/'.$id) ?>">View </a>
                 <?php
             }else{echo 'NA' ;} ?></td>
             <td><?php  echo $upload_exam_answersheet[0]->remark ;  ?> </td>
             <?php
             if($upload_exam_answersheet[0]->teacher_id!='' && $upload_exam_answersheet[0]->total_marks==0){
                 ?>
                 <td>
                    <input type="hidden" name="paper_code" value="<?php echo $paper->paper_code; ?>">
                    <input type="hidden" name="class_id" value="<?php echo $paper->class_id; ?>">
                    <input type="hidden" name="student_id" value="<?php echo$student_details[0]['student_id']; ?>">

                    <select name="remark_status" id="status_remark" class="form-control"  required>
                       <option value="">Select</option>
                       <option value="Delete">Delete</option>
                       <option value="Average Marks">Average Marks</option>
                   </select>    
               </td> 
               <?php
           }elseif($upload_exam_answersheet[0]->teacher_id!=''  && $upload_exam_answersheet[0]->total_marks!=0){
             ?>
             <td><?php echo $upload_exam_answersheet[0]->total_marks; ?></td>
             <?php
         }else{
            ?>
            <td>Not Checked</td>
            <?php
        }
        ?>
    </tr>
    <?php
    }
?>
</tbody>
</table>
    <div class="form-group text-center">
       <button class="btn btn-success " type="submit" id="submit" >Submit</button>
    </div>
</form>
<script>
	$('#submit').on('click',function (e) {
        $remark_value = $('#status_remark').val();
        if($remark_value==''){
            toastr.error('PLease select remark');
            return false ;
        }
        e.preventDefault();
        let formData = $('#update_remark_status').serialize();
        $.ajax({
            url: BASE_URL+ 'admin/ExamController/update_remark_status',
            method: 'post',
            data: formData,
            dataType: 'JSON',
            success: function (response) {
               if(response.status==true){
                  getStudentForRemark();
                  $('#right-modal').modal('hide');
                  toastr.success('update status remark successfully');
              }else{
                  toastr.error('something error');
              }
          }
      })
    })
</script>