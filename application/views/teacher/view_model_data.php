<form id="ajaxForm">
    <div class="row ">

        <fieldset class="form-group col-lg-6">
           <label for="enrollnum" class="col-lg-6 text-heading">Enrollment No</label>

           <?php echo $details[0]->enrollment_no; ?>

       </fieldset>

       <fieldset class="form-group col-lg-6">
           <label for="rollno" class="col-lg-6 text-heading">Roll No</label>

           <?php echo $details[0]->roll_no; ?>

       </fieldset>

       <fieldset class="form-group col-lg-6">
           <label for="coursename" class="col-lg-8 text-heading">Course Name</label>

           <?php echo $details[0]->course_name; ?>

       </fieldset>        


       <fieldset class="form-group col-lg-6">
           <label for="classname" class="col-lg-4 text-heading">Class Name</label>

           <?php echo $details[0]->class_name; ?>

       </fieldset>   


       <fieldset class="form-group col-lg-6">
           <label for="papername" class="col-lg-6 text-heading">Paper Name</label>

           <?php
           $papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$details[0]->paper_code));
           ?>
           <?php echo  $papername[0]->paper_name ;

                           ?> 

       </fieldset>   

 <div class="col-2">
   
                     <label class="text-heading mt-3">   <a  class="btn btn-info" href="<?php  echo base_url('Teacher/uplode_answersheet_pdf/').$details[0]->id;?>" >View</a></label>

    </div> 
       

   </div>


<!--   <form id="ajaxForm" method="POST" action="<?php echo site_url('teacher/Teacher/question_paper_sub/'.$details[0]->id); ?>">  -->
    
 <input  type="hidden" name="id" id="student_id" value="<?= $details[0]->id ?>"> 
                    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
  


<fieldset class="form-group col-md-12">
    <label for="Questions1" class="text-heading">1.</label>
                    <label for="Questions1" class="col-5 col-form-label">Questions 1</label>
                                        
<label class="text-heading mt-3">
                                               <select name="marks1" class="form-control" id="marks1">
                                    <option value="">N/A</option>
                                   
                                    <?php
                                    for ($i=0; $i<=14; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                               </select>  </label>          
                </fieldset>


<fieldset class="form-group col-md-12">
    <label for="Questions1" class="text-heading">2.</label>
                    <label for="Questions2" class="col-5 col-form-label">Questions 2</label>
                                        
<label class="text-heading mt-3">
                                               <select name="marks2" class="form-control" id="marks2">
                                    <option value="">N/A</option>
                                   
                                    <?php
                                    for ($i=0; $i<=14; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                               </select>  </label>          
                </fieldset>



<fieldset class="form-group col-md-12">
    <label for="Questions3" class="text-heading">2.</label>
                    <label for="Questions3" class="col-5 col-form-label">Questions 3</label>
                                        
<label class="text-heading mt-3">
                                               <select name="marks3" class="form-control" id="marks3">
                                    <option value="">N/A</option>
                                   
                                    <?php
                                    for ($i=0; $i<=14; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                               </select>  </label>          
                </fieldset>

<fieldset class="form-group col-md-12">
    <label for="Questions4" class="text-heading">4.</label>
                    <label for="Questions4" class="col-5 col-form-label">Questions 4</label>
                                        
<label class="text-heading mt-3">
                                               <select name="marks4" class="form-control" id="marks4">
                                    <option value="">N/A</option>
                                   
                                    <?php
                                    for ($i=0; $i<=14; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                               </select>  </label>          
                </fieldset>


<fieldset class="form-group col-md-12">
    <label for="Questions5" class="text-heading">5.</label>

                    <label for="Questions5" class="col-5 col-form-label">Questions 5</label>
                                        
<label class="text-heading mt-3">
                                               <select name="marks5" class="form-control" id="marks5">
                                    <option value="">N/A</option>
                                   
                                    <?php
                                    for ($i=0; $i<=14; $i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i; ?>"  ><?php echo $i; ?></option>
                                        <?php
                                    } 
                                    ?>
                               </select>  </label>          
                </fieldset>

  
  <div class="card-footer pb-0">
   <div class="row justify-content-center">
  
 
     <button type="submit" class="btn btn-success mr-2"  id="markssubmit">Submit</button>
     
   
   </div>
  </div>
 
 </form>


 <script>

  $("#markssubmit").on('click',function (e){
       e.preventDefault();
           var frm = $('#ajaxForm').serialize();
         
        $.ajax({
        url: '<?php echo site_url('teacher/Teacher/question_paper_sub'); ?>',
        method: 'post',
            data: frm,
            dataType: 'JSON',
        
        
        success: function (data) {
        if(data.success){
            toastr.success(data.success);
            // $('.student').remove();
            // $('#kt_datepicker_modal').modal('toggle');
            
            // $('.modal-backdrop').remove();
        
            toastr.success(data.success);
             window.location.reload();
        }else{
            toastr.error(data.error);
        }
            },
            
        }); 
    
 });    
    
</script> 