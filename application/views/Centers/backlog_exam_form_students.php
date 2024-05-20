<div class=" mt-5" >
    <div class="row justify-content-around mb-10">
        <a href="<?=base_url('backlog_exam_form_students/notSubmitted')?>" class="btn btn-primary">Not Submitted</a>
        <a href="<?=base_url('backlog_exam_form_students/submitted')?>" class="btn btn-success">Submitted</a>
        <a href="<?=base_url('backlog_exam_form_students/skipped')?>" class="btn btn-warning">Skipped</a>
    </div>
<div class="container-fluid text-center mb-10">
    <?php if ($exam_form_button=="notSubmitted"): ?>
        <h3 class="text-primary">Backlog Exam Form Student List</h3>
    <?php elseif ($exam_form_button=="submitted"): ?>
        <h3 class="text-primary"> Submitted Backlog Exam Form</h3>
    <?php elseif ($exam_form_button=="skipped"): ?>
        <h3 class="text-primary"> Skipped Backlog Exam Form</h3>
    <?php endif ?>
</div>
<div class="table-responsive">
    <table id="kt_datatable_scroll" class="table table-striped nowrap"  >
        <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <thead>
            <tr>
               <th>Sno</th>
               <!-- <th>Session </th> -->
               <th>Form No.</th>
               <th>Enrollment No </th>
               <th>Student Name</th>
               <th>Course</th>
               <th>Class</th>
               <th>Total Fees</th>
               <th>Action</th>
                <?php if($exam_form_button=="notSubmitted"){ ?>
                    <th>Skip Exam Form</th>
                <?php } ?>
           </tr>
       </thead>
       <tbody>
         <?php
         $i = 1;
         $exam_course_not=array(11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,71,72,73,74,75,76,77,78,79,69,70,68);
         foreach($documents as $student){
            $class_name = $this->Common_model->getClassNameByClassId($student->class_id);
            if($student->mode=="REG" && $class_name== 'I Year' && in_array($student->course_group_id,$exam_course_not) ){
                continue;
            }

         $failCount = $this->Common_model->getCountByWhere('backlog_exam_form',array('student_id' => $student->student_id,'class_id'=>$student->class_id,'paper_type'=>'Theory' ,'status'=>'B','backlog_student_id'=>$student->id));
         if( $failCount < 8){
            $exam_fees =$failCount * 100;
         }else{
            $exam_fees = 750; 
         }
            ?>
            <tr class="remove">
               <td><?php echo $i; ?></td>
               <!-- <td><?php echo $student->session; ?> </td> -->
               <td><?php echo $student->student_id; ?> </td>
               <td><?php echo $student->enrollment_no; ?> </td>
               <td><?php echo $this->Common_model->getSinglefield('student','name',array('student_id'=>$student->student_id));  ?> </td>
               <td><?php echo $this->Common_model->getCourseNameByCourseId($student->course_group_id); ?> </td>
               <td><?php  echo $class_name; ?> </td>
               <td><?php echo $exam_fees ;    
             ?> 
            </td>
               <?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id);
               $class_id = $this->Common_model->encrypt_decrypt($student->class_id);
                ?>
               <?php if($exam_form_button=="skipped"){ ?>
                   <td>
                  <input type="button" data-id = "<?=$student->student_id;?>" data-class= "<?=$student->class_id;?>" class="btn btn-success check_skipped" value="unskippped">
                    </td>
                <?php } ?>    
             <?php if($exam_form_button=="notSubmitted"){ ?>
               <td>     
            <a class="btn btn-primary" href="<?=base_url('backlog_showPapers/'.$student_id .'/'. $class_id)?>">View Paper</a>     
            </td>
             <td>
                <input type="button" data-id = "<?=$student->student_id;?> " class="btn btn-danger check_skipped" data-class= "<?=$student->class_id;?>" value="skipped">
               </td>
            <?php
          }        
        if($exam_form_button=="submitted")
          { 
         echo ' <td><a class="btn btn-primary" href="'.base_url('backlog_showPapers/'.$student_id.'/'.$class_id).'">View Paper</a></td>';   
        }
    $i++;
        }
    ?>
</tbody>
</table>
</div>
</div>

<script type="text/javascript">
 $(document).on('click', '.check_skipped', function() {
    var c= confirm('Are you sure?');
    if(c==true){
        var self = $(this);
        $(this).parent().parent().fadeOut(1500, function(){});
           // $(this).closest('tr').remove();
           var csrfName = $('.csrfname').attr('name');
           var csrfHash = $('.csrfname').val(); 
           var val = $(this).val();
           var self =this;
           var check_skipped = val;

           var data = {
            id: $(this).attr('data-id'),
            class_id: $(this).attr('data-class'),
            [csrfName]:csrfHash,
            check_skipped:check_skipped
        };
        $.ajax({
            url: BASE_URL + 'center/center/change_backlog_new_exam_form_status',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (data) {
                $(self).parent().html(data.data);
            }
        });
    }
});
</script>