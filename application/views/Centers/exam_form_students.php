<div class=" mt-5" >
    <div class="row justify-content-around mb-10">
        <a href="<?=base_url('exam_form_students/notSubmitted/'.$center)?>" class="btn btn-primary">Not Submitted</a>
        <a href="<?=base_url('exam_form_students/submitted/'.$center)?>" class="btn btn-success">Submitted</a>
        <a href="<?=base_url('exam_form_students/skipped/'.$center)?>" class="btn btn-warning">Skipped</a>
    </div>
<div class="container-fluid text-center mb-10">
    <?php 
    $center_ids = array( 10,11,12,13,21,22,23,24,25,26,27,28,29,1975,2098,2115 );
    if ($exam_form_button=="notSubmitted"): ?>
        <h3 class="text-primary">Exam Form Student List</h3>
    <?php elseif ($exam_form_button=="submitted"): ?>
        <h3 class="text-primary"> Submitted Exam Form</h3>
    <?php elseif ($exam_form_button=="skipped"): ?>
        <h3 class="text-primary"> Skipped Exam Form</h3>
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
               <th>Total Fees <?php if($master->late_exam_fee_status=='Y' && !in_array($this->session->center_id, $center_ids)){ echo ' + Late Fees';}?></th>
               <th>Action</th>
                <?php if($exam_form_button=="notSubmitted"){ ?>
                    <th>Skip Exam Form</th>
                <?php } ?>
           </tr>
       </thead>
       <tbody>
         <?php
         $i = 1;
         
         //$exam_course_not=array(11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,71,72,73,74,75,76,77,78,79,80,69,70,68);
         foreach($documents as $student){
           // echo "test".$student->class_id;  $student->session=="July 2023" &&
           //June 2024
            // if($student->university_mode=="REG" && $student->regular_exam_form_permission=='N' && $student->class_name== 'I Year' && in_array($student->course_group_id,$exam_course_not )  ){
            //     continue;
            // }
           
             //June 2025 //&& $student->regular_exam_form_permission=='N'
            // if($student->university_mode=="REG"  && $student->class_name== 'II Year' && in_array($student->course_group_id,$exam_course_not )  ){
            //     continue;
            // }
            $center_ids = array( 10,11,12,13,21,22,23,24,25,26,27,28,29,1975,2098,2115 );
            $pending="";
				if(in_array($this->session->center_id, $center_ids) ){
                     $where=array('center_id'=>$student->center_id,'exam_session'=>'June 2025','fees_head'=>'Exam Fees','payment'=>'Y','student_id'=>$student->student_id);
                   $paid= $this->Common_model->getRecordByWhere('online_payment_transaction',$where);
                   if($paid){
                    //continue;
                    $pending="Verfication Pending";
                   }
                }
            $where = array(
                'session' =>$student->session,
                'course_group_id' => $student->course_group_id,
            );

            $fees = $this->Common_model->getRecordByWhere('course',$where);
              if($student->university_mode=="REG"){
                $program_fees =  $fees[0]->program_fees;    
                $exam_fees =  $fees[0]->exam_fees;    
              }else{
                $program_fees =  $fees[0]->p_program_fees;    
                $exam_fees =  $fees[0]->p_exam_fees;    
              }
               if($master->late_exam_fee_status=='Y' && !in_array($this->session->center_id, $center_ids)){ 
                $exam_fees =$exam_fees + $master->p_late_fees;
              }
                
            ?>
            <tr class="remove">
               <td><?php echo $i; ?></td>
               <!-- <td><?php echo $student->session; ?> </td> -->
               <td><?php echo $student->student_id; ?> </td>
               <td><?php echo $student->enrollment_no; ?> </td>
               <td><?php echo $student->name; ?> </td>
               <td><?php echo $student->course_name; ?> </td>
               <td><?php echo $student->class_name; ?> </td>
               <td><?php 
               if($student->demo=='Y'){
                echo   $exam_fees ;
               }else{
                echo   $program_fees+$exam_fees; 
                }
           ?> </td>
               <?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id); ?>
               <?php if($exam_form_button=="skipped"){ ?>
                   <td>
                        <input type="button" data-id = "<?=$student->student_id;?>" class="btn btn-success check_skipped" value="unskippped">
                    </td>
                <?php } ?>
                
             <?php if($exam_form_button=="notSubmitted"){ ?>
               <td>
                <?php if ($student->temp_exam_form=='N'){ ?>
                    <a class="btn btn-primary" href="<?=base_url('select_papers/'.$student_id)?>">Select Papers</a>
                <?php }else if ($student->temp_exam_form=='Y' && $pending=="") { ?>
                    <a class="btn btn-primary karaundi-exam"  data-student = "<?=$student_id;?>" >Fill Form</a>
                <?php } else{ ?>
                    <a class="btn btn-primary" href="#"><?=$pending?></a>
                <?php } ?>    
            </td>
             <td>
                   <input type="button" data-id = "<?=$student->student_id;?> " class="btn btn-danger check_skipped" value="skipped">
               </td>
            <?php
          }
         
              
            if($exam_form_button=="submitted" && $student->temp_exam_form=='Y')
          { 
           echo ' <td><a class="btn btn-primary" href="'.base_url('showPapers/'.$student_id).'">View Paper</a></td>';
           
        }elseif($exam_form_button=="submitted" && $student->temp_exam_form=='N'){ 
            echo '<td> <a class="btn btn-primary" href="'.base_url('select_papers/'.$student_id) .'">Select Papers</a></td>';
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
<?php if($center =='karaundi') { ?>
    <script>
        
$(".karaundi-exam").click(function (e) {
   
    Swal.fire({
        html: '<b> आवश्यक सुचना :- </b>सूचित किया जाता है कि दिसम्बर 2025 में आयोजित होने वाली परीक्षाएं विश्वविद्यालय के मुख्यालय करौंदी जिला कटनी में आयोजित की जाएगी |',
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "I Agree",
        didOpen: () => {
            // Adjust the width after the modal has opened
            $('.swal2-popup').css('width', '38%');
        },
    }).then((result) => {
        // Handle confirmation result
        if (result.isConfirmed) {
            window.location.href =  "<?=base_url('showPapers/');?>"+ $(this).data('student');
           // console.log("User agreed.");
        }
    });
});
    </script>
    <?php }else{?>
    <script>
        $(".karaundi-exam").click(function (e) {
            window.location.href =  "<?=base_url('showPapers/');?>"+ $(this).data('student');
        });    
        </script>
    <?php }?>
  