<table id="" class="table " width="100%" >
	<thead>
		<tr>
			<th>Sno</th>
			<th>Roll No</th>
			<th>Enrollment No</th>
		    <th> Course</th>
 <th>Class</th>
  <th>paper</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$i = 1;
		foreach($student as $paper_code)
		{ ?>
			<tr>
				<td><?= $i; ?></td>
				
				<td><?= $paper_code->roll_no; ?></td>
				<td><?= $paper_code->enrollment_no; ?></td>
				<td><?= $paper_code->course_name; ?></td>
				<td><?= $paper_code->class_name; ?></td>




					<td class="col-md-2 text-center">
						
							<label class="text-heading mt-3"><button type="button" class="btn btn-primary modalOpen" data-toggle="modal"  data-student_id="<?=$paper_code->student_id;?>" data-target="#exampleModalCenter">View Paper</button></label>

						
					</td>



	

			
			</tr>
		<?php
		$i++; } 
		?>
	</tbody>
</table>





<div class="modal fade" id="exampleModalCenter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
			
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>



 
 		<h4 class="card-title">Student Details</h4>
 			<div class="row ">

<fieldset class="form-group col-md-6">
					<label for="enrollnum" class="col-sm-6 text-heading">Enrollment No</label>
					
                            <?php echo $student[0]->enrollment_no; ?>
                       
				</fieldset>

<fieldset class="form-group col-md-6">
					<label for="rollno" class="col-sm-6 text-heading">Roll No</label>
					
                            <?php echo $student[0]->roll_no; ?>
                        
				</fieldset>
                
            <fieldset class="form-group col-md-6">
					<label for="coursename" class="col-sm-6 text-heading">Course Name</label>
				
                          <?php echo $student[0]->course_name; ?>
                        
				</fieldset>        
               
 				
 				<fieldset class="form-group col-md-6">
					<label for="classname" class="col-sm-6 text-heading">Class Name</label>
					
                            <?php echo $student[0]->class_name; ?>
                        
				</fieldset>   
                
              	
 				<fieldset class="form-group col-md-6">
					<label for="papername" class="col-sm-6 text-heading">Paper Name</label>
					
                             <?php
                    $papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$student[0]->paper_code));
                    ?>
                    <?php echo  $papername[0]->paper_name ;

                 //   $this->Common_model->last_query();
                     ?> 
                        
				</fieldset>   
               
 				
       
                
 		</div>
 	







<form >

<input  type="hidden" name="student_id" id="student_id" value="<?= $student->student_id; ?>">
					<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 

			<div class="modal-body row">
				<fieldset class="form-group col-md-12">
					<label for="Questions1" class="text-heading">Questions1</label>
					
					
<label class="text-heading mt-3">
					                           <select name="marks1" class="form-control" id="marks1">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>  </label>
					
				</fieldset>
				<fieldset class="form-group col-md-12">
					<label for="Questions2" class="text-heading">Questions2</label>
					
<label class="text-heading mt-3">
					                           <select name="marks2" class="form-control" id="marks2">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>  </label>
					
				</fieldset>






				<fieldset class="form-group col-md-12">
					<label for="Questions3" class="text-heading">Questions3</label>
					
<label class="text-heading mt-3">
					                           <select name="marks3" class="form-control" id="marks3">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>  </label>
					
				</fieldset>



				<fieldset class="form-group col-md-12">
					<label for="Questions4" class="text-heading">Questions4</label>
					
<label class="text-heading mt-3">
					                           <select name="marks4" class="form-control" id="marks4">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>  </label>
					
				</fieldset>



				<fieldset class="form-group col-md-12">
					<label for="Questions5" class="text-heading">Questions5</label>
					
<label class="text-heading mt-3">
					                           <select name="marks5" class="form-control" id="marks5">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>  </label>
					
				</fieldset>



			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
				<button type="submit" id="submit"  class="btn btn-primary font-weight-bold">Submit</button>
			</div>
		</form>
		</div>
	</div>
</div>





<script type="text/javascript">
	// $( ".modalOpen" ).on('click', function(){
		
	// 	$('#student_id').val($(this).attr("data-student_id"));
	// });

	

	$('#submit').on('click',function (e) {
		e.preventDefault();
		
		let formData = $('form').serialize();
		$.ajax({
			url: BASE_URL+ 'Teacher/question_paper_sub',
			method: 'post',
			data: formData,
			dataType: 'JSON',
			success: function (response) {
				if(response.success){
					toastr.success(response.success);
				
					$('.modalOpen').remove();
					$('#exampleModalCenter').toggle();
					$('.modal-backdrop').remove();
					
				}else if(response.error){
					toastr.error(response.error);
				}
			}
		})
	})
</script>