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
                        <label class="col-sm-4 text-heading">Enrollment No</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student[0]->enrollment_no; ?>
                        </div>
                    </div>
                </div>
 				<div class="col-md-4">
 					<div class="row py-2">
 						<label class="col-sm-4 text-heading">Roll No</label>
 						<div class="col-sm-8 text-value">
 							<?php echo $student[0]->roll_no; ?>
 						</div>
 					</div>
 				</div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Course Name</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student[0]->course_name; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading"> Name</label>
                        <div class="col-sm-8 text-value">
                            <?php echo $student[0]->class_name; ?>
                        </div>
                    </div>
                </div>
               
 				
               <div class="col-md-4">
                    <div class="row py-2">
                        <label class="col-sm-4 text-heading">Paper Name</label>
                        <div class="col-sm-8 text-value">


  <?php
                    $papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$student[0]->paper_code));
                    ?>
                    <?php echo  $papername[0]->paper_name ;

                 //   $this->Common_model->last_query();
                     ?>  
                     
                       
                        </div>
                    </div>
                </div>
                
 		</div>
 	</div>
 </div>  




<form id="ajaxForm" method="POST" action="<?php echo site_url('Teacher/question_paper_sub/'.$student[0]->student_id); ?>">
                <input type="hidden" name="student_id" value="<?=$student[0]->student_id?>">
      
    <div class="container-fluid profile mt-5">
        <h4 class="card-title">Student Details</h4>
        <div class="row">
            <div class="col-md-1">
                <label class="text-heading">Questions1</label>
            </div>
            <div class="col-md-1">
                <label class="text-heading">Questions2</label>
            </div>
            <div class="col-md-2">
                <label class="text-heading">Questions3</label>
            </div>
            <div class="col-md-2">
                <label class="text-heading">Questions4</label>
            </div>
            <div class="col-md-2">
                <label class="text-heading">Questions5</label>
            </div>
           
            
        </div>
            
                <div class="row mt-3">
                    
                    <div class="col-md-1">
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
                                </select> 

                        </label>
                    </div>
                    <div class="col-md-2">
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
                                </select>

                        </label>
                    </div>
                    <div class="col-md-2">
                        <label class="text-heading mt-3">
                            

                            <select name="marks3" class="form-control" id="marks3>">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label class="text-heading mt-3">
                            

                            <select name="marks4" class="form-control" id="marks4>">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>
                        </label>
                    </div>



<div class="col-md-2">
                        <label class="text-heading mt-3">
                            

                            <select name="marks5" class="form-control" id="5">
                                    <option value="">Select</option>
                                   
                                    <?php
                                    for ($i=0; $i<=70; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                                </select>
                        </label>
                    </div>

                   
                   
                </div>


        
           <div class="row">
                    <button type="submit"  id="submitCertificate" name="submit" class="btn btn-primary m-auto">submit</button>
                </div>
            </form>



            
        </div>
