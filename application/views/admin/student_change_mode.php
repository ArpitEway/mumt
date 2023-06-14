<div class="BoxD border- padding mar-bot">
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							 <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
							<input type="hidden" value="<?php echo $student[0]->student_id ; ?>" id="student_id">
						  <tbody>
						  <tr>
							  <td><b>Form No.: </b> <?=$student[0]->student_id;?></td>
							  <td ><b>Enrollment No: </b> <?=$student[0]->enrollment_no;?></td>
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
<!--  -->
</div>
<div style="text-align: center;">
<?php if($this->session->account_type=='Admins'){ ?>
<button  href="#" class="btn btn-primary btn-sm font-weight-bold mode m-auto" >Change Mode</button>
<?php }else if($this->session->account_type=='Enrollment' &&  empty($student[0]->enrollment_no)){ ?>1
<button  href="#" class="btn btn-primary btn-sm font-weight-bold mode m-auto" >Change Mode</button>
<?php } else{ echo "<b>Mode may not change ,Entrollement Number already alloated</b>"; }?>
</div>

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