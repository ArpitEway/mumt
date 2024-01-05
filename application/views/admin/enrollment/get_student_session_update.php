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
							  <td><b>Course: </b> <?=$student[0]->course_name;?> <b>Class: </b> <?=$student[0]->class_name;?></td>
							  <td ><b>Session: </b> <?=$student[0]->session;?></td>
							</tr>
							<tr>
							  <td><b>Student Name: </b> <?=$student[0]->name;?></td>
							  <td ><b>DOB: </b> <?=date("d-m-Y", strtotime($student[0]->dob));?></td>
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

                <div class="row">
                            <div class="col-md-6">

                                <?php 
                                $sessions = $this->db->get_where('session', array())->result_array();
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

<button  href="#" class="btn btn-primary btn-sm font-weight-bold mode m-auto" >Change Mode</button>

</div>
<script src="<?=base_url();?>assets/theme/admission.js?token=<?=date('YmdHis')?>"></script>
<script>
$(document).ready(function(){

$(".mode").click(function(){
    var student_id = document.getElementById('student_id').value ;
   var csrfName = $('.csrfname').attr('name');
    var csrfHash = $('.csrfname').val();
    $.ajax({
        type: "POST",
        url: BASE_URL+"admin/Admins/update_student_mode",
        dataType:"json",
        data: {student_id: student_id,[csrfName]:csrfHash},
        success: function(response){
        console.log(response);
            if(response.status==true){
                toastr.success("Mode Changed Successfully");
				search_student_data();
				// if(response.mode=="REG")
				// {
				// 	$('#mode').replaceWith("<td  id='mode'><b>Mode:</b> Regular</td>");
				// }
				// if(response.mode=="PVT"){
				// 	$('#mode').replaceWith("<td  id='mode'><b>Mode:</b> Private</td>");
				// }
            }else{
                toastr.error(response.message);
            }
        }
    });
});
});

</script>