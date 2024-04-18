<style type="text/css">
    .profile .text-heading {
        font-size: 15px;
     }
</style>
 <div class="card card-custom my-10 details-bg" id="profile">	
 	<div class="container-fluid profile mt-5"> 
 		<h4 class="card-title">Student Details</h4>
 			<div class="row ">
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Form No</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->student_id; ?>
                        </div>
                    </div>
                </div>
 				<div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Enrollment No</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->enrollment_no; ?>
 						</div>
 					</div>
 				</div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Roll No</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->roll_number; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading"> Name</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->name; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Father Name</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->f_h_name; ?>
                        </div>
                    </div>
                </div>
 				<div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">DOB</label>
 						<div class="col-sm-8 text-value">
 							<?=$this->Common_model->viewDate($student->dob); ?>

 						</div>
 					</div>
 				</div>
                <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Center Code</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->center_code; ?>
 						</div>
 					</div>
 				</div>
                 <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Session</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->session; ?>
 						</div>
 					</div>
 				</div>
<!--            
    <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Exam form</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student->exam_form;  ?>
                        </div>
                    </div>
                </div>
     <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading"> Course Complete</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student->course_complete; ?>
 						</div>
 					</div>
 				</div>
                  <div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Max Course Duration</label>
 						<div class="col-sm-8 text-value">
                         <?php $course_d = $this->Common_model->getRecordById('course','course_name',$student->course_name);
                          $due = explode(" ",$student->session);
                        //    $year = $due[1]+$course_d->max_duration;
                           if($due[0]=="July"){
                            $month = "June";
                            $year = $due[1]+$course_d->max_duration;
                           }else{
                            $month = "Dec";
                            $year = $due[1]+$course_d->max_duration-1;
                           }
                          echo $course_d->max_duration." Years".'('.$month." ".$year.')';
                          
                           ?>
 						</div>
 					</div>
 				</div>
 -->                <div class="col-md-12">
 					<div class="row py-4">
 						<label class="col-sm-2 text-heading">Course/Class</label>
 						<div class="col-sm-8 text-value">

 						<?php echo $student->course_name;  ?> (	<?php echo  $this->Common_model->getClassNameByClassId($student->class_id); ?>)
 						</div>
 					</div>
 				</div>
 	        </div>
 	    </div>
    </div> 
    <div id="examDetails">
        <div class="card card-custom my-10 details-bg">	
            <div class="container-fluid profile mt-5">
                <div class="row">
                   <div class="col-md-1">
                      <label class="text-heading">S.NO.</label>
                  </div>
                  <div class="col-md-1">
                      <label class="text-heading">Class</label>
                  </div>
                  <div class="col-md-2">
                      <label class="text-heading">Roll Number</label>
                  </div>

                   <div class="col-md-2">
                      <label class="text-heading">Exam Year</label>
                  </div>
                   <div class="col-md-2">
                      <label class="text-heading">Exam Result</label>
                  </div> 
                  <div class="col-md-2">
                      <label class="text-heading">Exam Status</label>
                  </div> 
                  <div class="col-md-1">
                      <label class="text-heading">View </label>
                  </div>
                  <?php if( $this->session->account_type == 'Admins' || $this->session->account_type == 'MsPrint'){ ?>
                  <div class="col-md-1">
                      <label class="text-heading">Edit Date</label>
                  </div> 
                  <?php } ?>   
                </div>
              <?php 
              $i=1;
              $grade_classes=array(101,104,110,125,128,131);
              foreach ($result as $res) {
                $backlogflag=true;
                $whCount = $this->Common_model->getCountByWhere('old_result_data',array('exam_data_id' =>$res->id,'theory_marks'=>'','type'=>"Theory"));
              
               ?>
               <div class="row mt-3">
                  <div class="col-md-1">
                     <label class="text-heading mt-3"><?= $i++; ?></label>
                 </div> 
                 <div class="col-md-1">
                     <label class="text-heading mt-3"><?php echo  $this->Common_model->getClassNameByClassId($res->class_id); ?></label>
                 </div>
                 <div class="col-md-2">
                 <label class="text-heading mt-3"><?= $res->roll_no; ?></label>
                </div>
                 <div class="col-md-2">
                    <label class="text-heading mt-3"><?= $res->exam_year;?></label>
                 </div> 
                 <div class="col-md-2">
                    <label class="text-heading mt-3"><?= $res->exam_result;?></label>
                    <?php if($res->exam_result == 'FAIL' && $this->session->account_type == 'Admins' ){?>
                        <label class="text-heading mt-3"><a href="<?= base_url( 'admin/scripts/Postexam/backlog_marks_add_scripts/'.$student->student_id.'/'.$res->class_id.'/'.$res->exam_year.'')?>" target="_blank"><i class="fa fa-plus"></i></a></label>
                        <?php } ?>    
                 </div> 
              <div class="col-md-2 text-heading mt-3">
                    <?php 
                    
                    if($res->exam_status == "B"){
                        if(($res->exam_year=="July 2023") && $res->marks_pattern=="GRADE" && (in_array($res->class_id, $grade_classes))){
                            $backlogflag=false;
                           
                        }
                        echo "Backlog";
                      }else{
                         echo "Main";
                      }
                   
                    ?>
                </div>
                      <?php 
                      $flag=true;
                       if($this->session->account_type == 'MsPrint' && $student->exam_pattern=='GRADE'){
                        $flag= $this->Common_model->checkGradePreviousResult($student->student_id,$res->class_id);
                        
                       }
                      ?>
                      <div class="col-md-1">
                        <?php $id = $this->Common_model->encrypt_decrypt($res->id,'encrypt');?>
                         <!-- <label class="text-heading mt-3"><a class="  btn-sm" href="<?= base_url('MsPrint/marksheet/'.$id.'')?>" target="_blank"><i class="fa fa-eye"></i></a></label> -->
                         <label class="text-heading mt-3">
                         <?php if($flag){  
                             if($whCount){ echo "WithHeld";}
                             elseif($backlogflag) {
                             ?>
                         <a href="<?= base_url( $this->session->account_type.'/marksheet/'.$id.'')?>" target="_blank"><i class="fa fa-eye"></i></a>
                        <?php } } ?>
                        </label>
                    </div>
                    <?php if( $this->session->account_type == 'Admins' || $this->session->account_type == 'MsPrint'){ ?>
                    <div class="col-md-1">  
                         <label class="text-heading mt-3">
                            
                              <a href="#"  class=" font-weight-bold marksheet_update" data-toggle="modal" data-student_name = "<?=$student->name ?>" data-record_id="<?php echo $id ?>"
                              data-roll_number = "<?= $res->roll_no; ?>" data-marksheet_date = "<?php echo date('d/m/Y', strtotime($res->marksheet_date)); ?>"  data-target="#kt_datepicker_modal" "  ><i class="fa fa-edit"></i></a>
                             
                        </label>
                     </div>
                     <?php } ?>
                 </div>
             <?php } ?>
        </div>
 	</div>
</div>


				 
<div class="modal fade" id="kt_datepicker_modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Marksheet Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="card card-custom">

            <!--begin::Form-->
            <form method="POST" class="d-block" id="ajaxForm" action="<?php echo site_url('admin/MsPrint/update_marksheet_date'); ?>">
            <div class="card-body">
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
           
            <div class="form-group row">
                <label for="example-date-input" class="col-5 col-form-label">Student Name</label>
                <div class="col-7">
                <label for="example-date-input" class="col-form-label"><span id="student_name"></span></label>
                </div>
                
            </div>
            <div class="form-group row">
                <label for="example-date-input" class="col-5 col-form-label">Roll Number</label>
                <div class="col-7">
                <label for="example-date-input" class="col-form-label"><span id="roll_number"></span></label>
                </div>
                
            </div>
            <div class="form-group row">
                <label for="example-date-input" class="col-5 col-form-label">Marksheet Date</label>
                <div class="col-7">
                    
                <!-- <input class="form-control" type="date" name="marksheet_date"   id="marksheet_date"  /> -->
                <input type="text" class="form-control "  name="marksheet_date" id="marksheet_date">
                <div class="text-danger" id="error"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-date-input" class="col-5 col-form-label">Remark</label>
                <div class="col-7 mt-3">
                    
                <input type="checkbox" class="all_checked_permitt" name="remark_date" value='Duplicate'> Duplicate
                <input type="checkbox" class="all_checked_permitt" name="remark_date" value='After Correction'> After Correction
                <div class="text-danger" id="date_error"></div>
                <input type="hidden" value="" name="record_id" id="record_id">
              
                </div>
            </div>
           <div class="card-footer pb-0">
            <div class="row justify-content-center">
            
                <button type="reset" class="btn btn-success mr-2" id="date_submit">Submit</button>
                
            
            </div>
            </div>
            </form>
</div>
<script>
    $('#marksheet_date').mask('99/99/9999',{placeholder:"dd/mm/yyyy"});
    $(document).on('click','.marksheet_update',function(){
	    var name_csrf = $(this).attr('data-name_csrf');
	    var hash_csrf = $(this).attr('data-hash_csrf');
	    var record_id=$(this).attr('data-record_id');
		var student_name = $(this).attr('data-student_name');
		var roll_no = $(this).attr('data-roll_number');
		//var marksheet_date=$(this).attr('data-marksheet_date');
		$('#student_name').html(student_name);
		$('#roll_number').html(roll_no);
       // $('#marksheet_date').val(marksheet_date);
        $('#record_id').val(record_id);
		
	});

    $("#date_submit").on('click',function (e){
	var formimage = $('#ajaxForm');
	var frm = new FormData(formimage[0]);
		
      
        var record_id = $('#record_id').val();
		var marksheet_date = $('#marksheet_date').val();
        var remark_date = $("input:checkbox").filter(":checked").val();
       
       
		var roll_number = $('#roll_number').html();
        // console.log(marksheet_date);
		if(marksheet_date==''){
			$('#error').text('Please Select Date');
			return false;
		}

        if($("input:checkbox").filter(":checked").length>1){
            $('#date_error').text('Please one checkbox');
        return false ;
        }
        
				
		$.ajax({
		url: '<?php echo site_url('admin/MsPrint/update_marksheet_date'); ?>',
		type: 'POST',
		dataType : 'json',
		data: frm,
		cache:false,
		contentType: false,
		processData: false,
		beforsend: function()
              {
                console.log('loading..');
                $("#myLoader").show();
               },
		success: function (data) {
		if(data){
			console.log(data);
			$('#kt_datepicker_modal').modal('toggle');
				toastr.success("Date Updated Successfully!");
		}else{
			toastr.error("Something wrong");
		}
			},
			complete: function()
              {
                console.log('loading...over');
                $("#myLoader").hide();
               },
		});	
	
});	
</script>    