<div class=" mt-5" >
    <div class="row justify-content-around mb-10">
        <a href="<?=base_url('exam_form_students/notSubmitted')?>" class="btn btn-primary">Not Submitted</a>
        <a href="<?=base_url('exam_form_students/submitted')?>" class="btn btn-success">Submitted</a>
        <a href="<?=base_url('exam_form_students/skipped')?>" class="btn btn-warning">Skipped</a>
    </div>
<div class="container-fluid text-center">
    <?php if ($exam_form_button=="notSubmitted"): ?>
        <h3 class="text-primary">Exam Form Student List</h3>
    <?php elseif ($exam_form_button=="submitted"): ?>
        <h3 class="text-primary"> Submitted Exam Form</h3>
    <?php elseif ($exam_form_button=="skipped"): ?>
        <h3 class="text-primary"> Skipped Exam Form</h3>
    <?php endif ?>
</div>
    <table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
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
                <?php if($exam_form_button=="notSubmitted"){ ?>
                    <th>Skip Exam Form</th>
                <?php } ?>
               <th>Action</th>
           </tr>
       </thead>
       <tbody>
         <?php
         $i = 1;
         foreach($documents as $student){

            $where = array(
                'session' =>$student->session,
                'course_group_id' => $student->course_group_id,
            );

            $fees = $this->Common_model->getRecordByWhere('course',$where);

            ?>
            <tr class="remove">
               <td><?php echo $i; ?></td>
               <!-- <td><?php echo $student->session; ?> </td> -->
               <td><?php echo $student->student_id; ?> </td>
               <td><?php echo $student->enrollment_no; ?> </td>
               <td><?php echo $student->name; ?> </td>
               <td><?php echo $student->course_name; ?> </td>
               <td><?php echo $student->class_name; ?> </td>
               <td><?php echo $fees[0]->program_fees+$fees[0]->exam_fees; ?> </td>
               <?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id); ?>
               <?php if($exam_form_button=="skipped"){ ?>
                   <td>
                        <input type="button" data-id = "<?=$student->student_id;?>" class="btn btn-success check_skipped" value="unskippped">
                    </td>
                <?php } ?>
                
             <?php if($exam_form_button=="notSubmitted"){ ?>
                <td>
                   <input type="button" data-id = "<?=$student->student_id;?> " class="btn btn-danger check_skipped" value="skipped">
               </td>
               <td>
                <?php if ($student->temp_exam_form=='N'){ ?>
                    <a class="btn btn-primary" href="<?=base_url('select_papers/'.$student_id)?>">Select Papers</a>
                <?php }else if ($student->temp_exam_form=='Y') { ?>
                    <a class="btn btn-primary" href="<?=base_url('showPapers/'.$student_id)?>">Fill Exam Form</a>
                <?php } ?>
            </td>
            <?php
          }
          if($exam_form_button=="submitted")
          {
           ?>
           <td>
            <a class="btn btn-primary" href="<?=base_url('showPapers/'.$student_id)?>">View Paper</a>
        </td>
        <?php
        }
    ?>
    <?php
    $i++;
        }
    ?>
</tbody>
</table>
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
            [csrfName]:csrfHash,
            check_skipped:check_skipped
        };

        $.ajax({
            url: BASE_URL + 'center/center/change_new_exam_form_status',
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