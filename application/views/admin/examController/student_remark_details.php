<?php 
$student_details = $this->db->get_where('student', array('student_id' => $param1))->result_array();
// // echo "<pre>";
// // print_r($student_details);
// $this->db->select('*');
// $this->db->from('upload_exam_ans_sheet');
// $this->db->join('new_exam_form', 'upload_exam_ans_sheet.student_id = new_exam_form.student_id');
// $this->db->where('new_exam_form.student_id',$param1);
// $this->db->where('upload_exam_ans_sheet.teacher_id!=','');
// // $this->db->limit(10);
// $new_exam_form = $this->db->get()->result();
// //$this->Common_model->last_query();


$new_exam_form = $this->Common_model->getRecordByWhere("new_exam_form",array('student_id'=>$param1));
?>
<form method="POST" class="mt-3 "  id="update_remark_status" >
<input type="hidden"  class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">

<div class="container">
    <table class="table table-striped">
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
            <th>Exam Type</th>
            <td><?php echo $student_details[0]['roll_no'] ?></td>
        </tr>
    </tbody>
    </table>
</div>
<table class="table table-striped">
<thead>
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
// echo "<pre>";
// print_r( $upload_exam_answersheet);

            
            ?>
        <tr>
        <td><?php echo $this->Common_model->getPaperNameById($paper->paper_id) ;  ?></td>
            <td><?php echo $paper->paper_code ;  ?></td>
            <td><?php if($upload_exam_answersheet[0]->answer_sheet!=''){echo $upload_exam_answersheet[0]->answer_sheet ; }else{echo 'NA' ;} ?></td>
            <td><?php  echo $upload_exam_answersheet[0]->remark ;  ?> </td>
            <?php
           if($upload_exam_answersheet[0]->teacher_id!='' && $upload_exam_answersheet[0]->total_marks==0){
               ?>
               <td>
            <input type="hidden" name="paper_code" value="<?php echo $paper->paper_code; ?>">
            <input type="hidden" name="class_id" value="<?php echo $paper->class_id; ?>">
            <input type="hidden" name="student_id" value="<?php echo$student_details[0]['student_id']; ?>">

                <select name="remark_status" id="status_remark" class="form-control"  required >
                     <option value="">All</option>
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
 <button class="btn btn-success" type="submit" id="submit">Submit</button>
 </form>

 <script>
     	$('#submit').on('click',function (e) {
            
		e.preventDefault();
		let formData = $('#update_remark_status').serialize();
		$.ajax({
			url: BASE_URL+ 'admin/ExamController/update_remark_status',
			method: 'post',
			data: formData,
			dataType: 'JSON',
			success: function (response) {
				if(response.status==true){
					toastr.success('update status remark successfully');
				}else{
					toastr.error('something error');
				}
			}
		})
	})
 </script>