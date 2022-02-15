<div class=" mt-5" >
<table id="kt_datatable" class="table table-striped dt-responsive nowrap" width="100%" >
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
			<thead>
				<tr>
					<th>Sno</th>
                   <th>Session </th>
                   <th>Form No.</th>
                   <th>Enrollment No </th>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Class</th>
                    <th>Total Fees</th>
                    <th>Skip Exam Form</th>
                  
                    <th>Fill Exam Form</th>


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
				<tr id="remove">
					<td><?php echo $i; ?></td>
					<td><?php echo $student->session; ?> </td>
					<td><?php echo $student->student_id; ?> </td>
                    <td><?php echo $student->enrollment_no; ?> </td>
					<td><?php echo $student->name; ?> </td>
					<td><?php echo $student->course_name; ?> </td>
					<td><?php echo $student->class_name; ?> </td>
					<td><?php echo $fees[0]->program_fees+$fees[0]->exam_fees; ?> </td>
					<td><?php echo $student->class_name; ?> </td>
					
                    <?php $student_id = $this->Common_model->encrypt_decrypt($student->student_id); ?>


         <?php 
         if($exam_form_button=="skipped")
         {
         ?>
    <td>
     <?php
     if($exam_form_button=="skipped" )
     {
     ?>
     <input type="button" name="" data-id = "<?=$student->student_id;?>" class="btn btn-success check_skipped" value="Unskippped">
 
     <?php }else{ ?>
 
     <input type="button" name="" data-id = "<?=$student->student_id;?> " class="btn btn-danger check_skipped" value="skippped">
 
     <?php 
     }	
     ?>
    </td>
         <?php
         }
         ?>


         <?php 
         if($exam_form_button=="notSubmitted" && $student->temp_exam_form=='N')
         {
         ?>
        <td>
        <a class="btn btn-primary" href="<?=base_url('center/center/select_papers/'.$student_id)?>">Submit</a>
        </td>
         <?php
         }
         else{
        ?>
         <td>
          <a class="btn btn-primary" href="<?=base_url('center/center/showPapers/'.$student_id)?>">Show Papers</a>
        </td>
         <?php
         }
         ?>
        <?php 
         if($exam_form_button=="submitted")
         {
         ?>
        <td>
        <a class="btn btn-primary" href="#">Submit</a>
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
            $('#remove').fadeOut(600, function(){ $(this).remove();});
           // $(this).closest('tr').remove();
        var csrfName = $('.csrfname').attr('name');
	    var csrfHash = $('.csrfname').val(); 
		var val = $(this).val();
		var self =this;
		var check_skipped = (val=='skipped') ? 'unskipped' : 'skipped';
	
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