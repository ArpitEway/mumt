<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							 <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
							<input type="hidden" value="<?php echo $student[0]->student_id ; ?>" id="student_id">
						  <tbody>
						  <tr>
							  <td><b>Form No.: </b> <?=$student[0]->student_id;?></td>
                              <td ><b>Session: </b> <?=$student[0]->session;?></td>
							</tr>
						
							<tr>
							  <td><b>Course: </b> <?=$student[0]->course_name;?> </td>
							  <td ><b>Class: </b> <?=$student[0]->class_name;?></td>
							</tr>
							<tr>
							  <td><b>Student Name: </b> <?=$student[0]->name;?></td>
							  <td ><b>Father Name: </b><?=$student[0]->f_h_name;?></td>
							</tr>	
							<tr>
							  <td><b>Student Email: </b> <?=$student_data[0]->p_email;?></td>
							  <td ><b>Mobile No: </b> <?=$student_data[0]->p_mobile_no;?></td>
							</tr>	
							<tr>
							  <td><b>Center: </b><?php echo $this->Common_model->getCenterNameById($student[0]->center_id); ?></td>
							  <td ><b>Center Code:</b> <?=$student[0]->center_code;?></td>
							</tr>				
							<tr>
							   <div>
								<td  id="mode"><b>Mode:</b> <?php
								if($student[0]->university_mode=='REG'){
									echo 'Regular';
										}
									else{
                                    echo 'Private';	
									}

								 ;?></td>
								</div>
								<td>
									<strong>Payment: </strong>
									<?=($student[0]->payment_status=='N') ? 'Unpaid' : 'Paid'; ?>
								</td>
							</tr>	
                          		
						  </tbody>
						</table>
					</div>
				</div>
<?php if($student[0]->enrollment_no=='-' && $student[0]->enrolled=='N'){  ?>
                <div class="row">
                            <div class="col-md-6">

                                <?php 
                                $sessions = $this->db->get_where('session', array('enrollment_permission'=>'Y'))->result_array();
                                    ?>
                                    <div class="form-group"  >
                                        <label>Session</label><span class="text-danger"> *</span>

                                        <select class="form-control" name="session" id="session">
                
                                            <?php foreach ($sessions as $session) {
                                                $selected = ($session['session']==$student[0]->session) ? 'selected' : '';
                                                ?>
                                                <option <?=$selected?>><?php echo $session['session']; ?></option> 
                                                <?php 
                                            } ?>
                                        </select>
                                </div>
                        
                             </div>

                             <div class="col-md-6">
                                <!--begin::Input-->
                                <div class="form-group ">
                                    <label>Eligibility</label>
                                    <select name="eligibility" id="eligibility" class="form-control" >
                                        <option value="">--Select--</option>
                                        <?php foreach ($eligibility_list as $row) { ?>
                                            <option <?php if($student_data[0]->eligibility == $row['eligibility'] ) {echo "selected";} ?> ><?=$row['eligibility']?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <input type="hidden" name="old_course_group_id" id="old_course_group_id"  value="<?=$student[0]->course_group_id;?>">
                                <input type="hidden" name="mode" id="mode"  value="<?=$student[0]->university_mode;?>">
                                <label>Course</label><span class="text-danger"> *</span>
                                <select name="course_group_id" id="course_group_id_admission" class="form-control " >
                                <option value="" >--Select--</option>
                                <?php
                            
                                $eligibility=$student_data[0]->eligibility;  
                            $this->db->select('course_group.id,course.course_name');
                            $this->db->from('course');
                            $this->db->join('course_group', 'course_group.id = course.course_group_id'); 
                            $this->db->where('eligibility',$eligibility);
                            $this->db->where('course.session',$student[0]->session);
                            if($student[0]->university_mode=='REG' ){
                                
                                $this->db->where(" (admission_permission_regular = 'Y' or course_group.id=".$student[0]->course_group_id.") ");
                                }else{
                                
                                    $this->db->where(" (admission_permission_private = 'Y' or course_group.id=".$student[0]->course_group_id.") ");
                                }
                            
                                $query = $this->db->get();
                                $course_group_list= $query->result_array();
                              
                                foreach ($course_group_list as $row) { ?>
                                            <option value="<?=$row['id'];?>" <?php if($student[0]->course_group_id == $row['id']){ echo "selected";} ?>><?=$row['course_name'];?></option>
                                <?php } ?>

                                    </select>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                                <!--end::Input-->
                            </div>
                            <div class="col-md-6">
                                <!--begin::Input-->
                                <div class="form-group ">
                                    <label>Class</label><span class="text-danger"> *</span>
                                    <select name="class_id" id="class_id" class="form-control " >
                                        <option value="">Select Class</option>
                                        <?php 
                                        
                                        $class_list = $this->Common_model->get_record('class_master','*',"id='".$student[0]->class_id."'  ");
                                        foreach ($class_list as $row) { ?>
                                            <option value="<?=$row['id'];?>" <?php if($student[0]->class_id == $row['id']){ echo "selected";} ?>><?=$row['class_name'];?></option>
                                        <?php } ?>
                                        
                                    </select>
                                    <div class="fv-plugins-message-container"></div>
                                </div>
                            </div>
                                    

                    </div>
<!--  -->
</div>
<div style="text-align: center;">

<button  href="#" class="btn btn-primary btn-sm font-weight-bold studentSession m-auto" >Change Session</button>

</div>
<?php }else{
    ?>
     <div class="alert alert-warning alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> Student is already Enrolled!.
  </div>
    <?php
} ?>
<script>
$(document).ready(function(){

    $("#eligibility").on('change', function(){
	var eligibility = $(this).val();
	
	var csrfName = $('.csrfname').attr('name');
	var csrfHash = $('.csrfname').val();
	var mode ='<?=$student[0]->university_mode;?>' ; 
	var session=$('#session').val(); 
    $("#class_id").html('');
	$('input[name="qualifying_exam"]').val(eligibility); 
	$.ajax({
		method: "POST",
		url: BASE_URL+"center/center/getCourseByEligibility",
		data: {mode:mode,session:session,eligibility : eligibility,[csrfName]:csrfHash},
	})
	.done(function( msg ) {
		$('#course_group_id').html(msg);
		$('#course_group_id_admission').html(msg);
	});
});

$("#session").on('change', function(){
    $("#eligibility").val('');
    $("#course_group_id").html('');
    $('#course_group_id_admission').html('');
    $("#class_id").html('');
    

});



    $(".studentSession").click(function(){
        var student_id = document.getElementById('student_id').value ;
        var eligibility = document.getElementById('eligibility').value ;
        var mode = document.getElementById('mode').value ;
        var course_group_id = document.getElementById('course_group_id_admission').value ;
        var old_course_group_id = document.getElementById('old_course_group_id').value ;
        var class_id = document.getElementById('class_id').value ;
        var session = document.getElementById('session').value ;
        var csrfName = $('.csrfname').attr('name');
        var csrfHash = $('.csrfname').val();
        if(session==''){
		$('select[name="session"]').next('div').text('Session is Required');
		document.getElementById('session').focus();
		// submit = false
		
            return false;
        }else{
            $('select[name="session"]').next('div').text('');
        }
        if(eligibility==''){
            $('select[name="eligibility"]').next('div').text('eligibility is Required');
            document.getElementById('eligibility').focus();
            return false;
        }else{
            $('select[name="eligibility"]').next('div').text('');
        }
        if(course_group_id==''){
            $('select[name="course_group_id"]').next('div').text('Course is Required');
            document.getElementById('course_group_id').focus();
            // course_group_id = false
            return false;
        }else{
            $('select[name="course_group_id"]').next('div').text('');
        }
        if(class_id==''){
            $('select[name="class_id"]').next('div').text('Class is Required');
            document.getElementById('class_id').focus();
            // submit = false
            return false;
        }else{
            $('select[name="class_id"]').next('div').text('');
        }

        if(session!="" && eligibility!="" && course_group_id!="" && class_id!=""){
                $.ajax({
                    type: "POST",
                    url: BASE_URL+"admin/Enrollment/update_student_session",
                    dataType:"json",
                    data: {student_id: student_id,eligibility:eligibility,mode:mode,session:session,course_group_id:course_group_id,old_course_group_id:old_course_group_id,class_id:class_id,[csrfName]:csrfHash},
                    success: function(response){
                    console.log(response);
                        if(response.status==true){
                            toastr.success("Session Changed Successfully");
                            search_student_data();
                        
                        }else{
                            toastr.error(response.message);
                        }
                    }
                });
            }
            else{

            }
    });

    $("#course_group_id_admission").on('change', function(){
		var csrfName = $('.csrfname').attr('name');
		var csrfHash = $('.csrfname').val(); 
		var mode = document.getElementById('mode').value;
		var course = $(this).val();
			$.ajax({
				method: "POST",
				url: BASE_URL+"admin/Admins/getClassByCourseInAdmission",
				data: { course_group_id : course,
						[csrfName]:csrfHash
						, mode : mode
						},
			})
			.done(function( msg ) {
				$('#class_id').html(msg);
			});
		});
});

</script>