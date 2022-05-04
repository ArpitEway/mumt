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
// echo "<pre>";
// print_r( $new_exam_form);
// die ;
?>
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
            
            ?>
        <tr>
            <td><?php echo $paper->paper_code ;  ?></td>
            <td><?php echo $paper->paper_code ;  ?></td>
            <td>PDF</td>
            <td><?php echo $paper->remark_status ;  ?></td>
            <td><?php echo $paper->paper_code ;  ?></td>
            <td><?php echo $paper->paper_code ;  ?></td>
     

        </tr>
        <?php
        }
        ?>
       
    </tbody>
 </table>