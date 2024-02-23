<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							 <input type="hidden" class="csrfnamen" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
							<input type="hidden" value="<?php echo $students[0]['student_id'] ; ?>" id="student_id">
						  <tr>
							  <td width="50%"><b>Form No.: </b> <?=$students[0]['student_id'];?></td>
							  <td width="50%"><b>Enrollment No: </b> <?=$students[0]['enrollment_no'];?></td>
							</tr>
						
							<tr>
							  <td><b>Course: </b> <?=$student[0]->course_name;?>  <?=$students[0]['course_name'];?></td>
							  <td ><b>Session: </b> <?=$students[0]['session'];?></td>
							</tr>
							<tr>
							  <td><b>Student Name: </b> <?=$students[0]['name'];?></td>
							  <td ><b>Father/Husband Name: </b>  <?=$students[0]['f_h_name'];?></td>
							</tr>	
							<tr>
							  
							  <td ><b>Mobile No: </b> <?=$students[0]['p_mobile_no'];?></td>
                              <td ><b>DOB: </b> <?=date("d-m-Y", strtotime($students[0]['dob']));?></td>
							</tr>	
										
									
						  </tbody>
						</table>
					</div>
				</div>
<!--  -->
</div>

         <!--Start-->        
         <!-- <div class="container p-3"> -->
    
    <!-- <form action="<?= base_url()?>center/center/application_submit" method="post" onsubmit="return validate()" enctype='multipart/form-data' > -->
    <!-- <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> -->
    <!-- <input type="hidden" class="csrfname" name="center_id" value="<?= $center_id; ?>"> -->
        <div class="row d-felx justify-content-center m-2">
            <?php $course_group = $this->Common_model->getRecordByWhere('course_group',array('course_name'=>$students[0]['course_name']));
            
             if($course_group[0]->mode == 'Month'){
                $course_group[0]->mode = 'Semester';
                }
            if($students[0]['university_mode'] == "REG"){
                 
                $mode = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=>$course_group[0]->id,'mode'=>$course_group[0]->mode,'id <='=>$students[0]['class_id']));
                }else{
                $mode = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=>$course_group[0]->id,'mode'=>$course_group[0]->private_mode,'id <='=>$students[0]['class_id']));
              
            }
           
            ?>
            <div class="form-group col-md-2">
                <!-- <label>Apply For</label> -->
                <select name="apply_for" class="form-control" id="apply">
                <option value="">Select</option>
                <?php 
                if($students[0]['course_complete'] == 'N' || $course_group[0]->course_type == 'Certificate'){
                    $field = $this->Common_model->getRecordByWhere('application_field',array('field'=>'DUPLICATE-MARKSHEET','status'=>'Y'));
                }else if($course_group[0]->course_type == 'PGDiploma' || $course_group[0]->course_type == 'Diploma'){
                    $this->db->where_not_in('field', 'NOC' );
                    $field = $this->Common_model->getRecordByWhere('application_field',array('field !='=>'DEGREE','status'=>'Y'));
                }
                else{
                    $field = $this->Common_model->getRecordByWhere('application_field',array('field !='=>'DIPLOMA','status'=>'Y')); 
                }
               
                     foreach($field as $row){?>
                    
                    <option value="<?= $row->field?>"><?= $row->field?></option>
                 <?php }?>
                </select>
                <span class="text-danger" id="aerr"></span>
            </div>
            <input type="hidden" class="form-control" name="enrollment" id="student_enroll" value="<?= $students[0]['enrollment_no'] ?>" />
            <div class="form-group col-md-2">
        
                    <button type="button" class="btn btn-primary btn-sm m-auto" onclick="open_form()">Apply For</button>
            </div>
        </div>
        <div align="center" id="myLoaderNext" class="loader_div" style="display: none;" >
            <svg>
                <circle cx="50" cy="50" r="40" stroke="red" stroke-dasharray="78.5 235.5" stroke-width="3" fill="none" />
                <circle cx="50" cy="50" r="30" stroke="blue" stroke-dasharray="62.8 188.8" stroke-width="3" fill="none" />
                <circle cx="50" cy="50" r="20" stroke="green" stroke-dasharray="47.1 141.3" stroke-width="3" fill="none" />
            </svg>
        </div>
            <span id="apply_form"></span>
    </form>

<!-- </div> -->
         <!--End--->                   




<script>
function open_form(){
    var csrfName = $('.csrfnamen').attr('name');
        var csrfHash = $('.csrfnamen').val();
        var text_val = $('#apply').val();
        var center_id = $('#center_id').val();
        var student_enroll=$('#student_enroll').val();
        if(text_val =='')
        { 
            alert('Please select apply for form !');
        }
       
        else
        {
          
            let studentData = {
                    'apply':text_val,
                    'center_id':center_id,
                    'student_enroll':student_enroll,
                    [csrfName]:csrfHash
                }
                console.log("student_enroll"+studentData);
                console.log("csrfName"+csrfName);
                console.log("csrfHash"+csrfHash);
            $.ajax({
                url:site_url+'center/<?=$this->session->account_type; ?>/applyApplicationForm',
                type:'post',
               // processData: false,
                dataType : 'JSON',
              
                data: studentData,
                
                beforeSend: function()
              {
                $("#myLoaderNext").show();
               },
                success:function(resp)
                {
                    console.log(resp.data);
                    if( $("#myLoaderNext").show()){
						$('#apply_form').hide();
						 $('#apply_form').html(resp.data);

					}if( $('#myLoaderNext').hide()){
                       
						$('#apply_form').show();
						
					}
                            
                }//success
                
            })//ajax
        }
}
</script>