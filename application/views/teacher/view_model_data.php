<form id="ajaxForm">
    <div class="row ">



<div class="col-lg-6">
                    <div class="row py-2">
                        <label class="col-lg-6 text-heading">Enrollment No</label>
                        <div class="col-lg-6 text-value">
                            <?php echo $details[0]->enrollment_no; ?>
                        </div>
                    </div>
                </div>


       <div class="form-group col-lg-6">
                    <div class="row py-2">
                        <label class="col-lg-6 form-label text-heading">Roll No</label>
                        <div class="col-lg-6 text-value">
                            <?php echo $details[0]->roll_no; ?>
                        </div>
                    </div>
                </div>
    </div>
  <div class="row ">


<div class="col-lg-6">
                    <div class="row py-2">
                        <label class="col-lg-6 text-heading">Course Name</label>
                        <div class="col-lg-6 text-value">
   <?php echo $details[0]->course_name; ?>
                        </div>
                    </div>
                </div>


       <div class="form-group col-lg-6">
                    <div class="row py-2">
                        <label class="col-lg-6 form-label text-heading">Class Name</label>
                        <div class="col-lg-6 text-value">
                           <?php echo $details[0]->class_name; ?>
                        </div>
                    </div>
                </div>
    </div>


   

       
<div class="row ">
<div class="col-lg-6 ">
                    <div  class="row py-2">
                        <label class="col-lg-6 text-heading">Paper Name</label>
                        <div class="col-lg-6 px-1">
<?php
           $papername=$this->Common_model->getRecordByWhere('paper_master',array('paper_code'=>$details[0]->paper_code));
           ?>
           <?php echo  $papername[0]->paper_name ;

                           ?> 
                        </div>
                    </div>
                </div>


                <div class="m-auto">
   
                       <a   target="_blank" class="btn btn-info" href="<?php  echo base_url('Teacher/check_answersheet_pdf/'.$this->Common_model->encrypt_decrypt($details[0]->id,'encrypt'));?>" >View</a>
    </div>   </div> 


             
   </div>
<div class="card-footer pb-0"></div>
    
 <input  type="hidden" name="id" id="student_id" value="<?= $details[0]->id ?>"> 
                    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>"> 
  
<fieldset class="form-group col-md-12">
    <label for="Questions1" class="text-heading"><strong>1.</strong></label>
                    <label for="Questions1" class="col-5 col-form-label "><strong>Questions 1 </strong> </label>    
                                        
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
    <label for="Questions1" class="text-heading"><strong>2.</strong></label>
                    <label for="Questions2" class="col-5 col-form-label"><strong>Questions 2</strong></label>
                                        
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
    <label for="Questions3" class="text-heading"><strong>3.</strong></label>
                    <label for="Questions3" class="col-5 col-form-label"><strong>Questions 3</strong></label>
                                        
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
    <label for="Questions4" class="text-heading"><strong>4.</strong></label>
                    <label for="Questions4" class="col-5 col-form-label"><strong>Questions 4</strong></label>
                                        
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
    <label for="Questions5" class="text-heading"><strong>5.</strong></label>

                    <label for="Questions5" class="col-5 col-form-label"><strong>Questions 5</strong></label>
                                        
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

        var marks1 = $('#marks1').val();
var marks2 = $('#marks2').val();
var marks3 = $('#marks3').val();
var marks4 = $('#marks4').val();
var marks5 = $('#marks5').val();

  var submit = true;
if(marks1==''){
        $(marks1).next().text('Please Select Marks');
        submit = false;
      }else{
        $(marks1).next().text('');
      }

if(marks2==''){
        $(marks2).next().text('Please Select Marks');
        submit = false;
      }else{
        $(marks2).next().text('');
      }
if(marks3==''){
        $(marks3).next().text('Please Select Marks');
        submit = false;
      }else{
        $(marks3).next().text('');
      }

      if(marks4==''){
        $(marks4).next().text('Please Select Marks');
        submit = false;
      }else{
        $(marks4).next().text('');
      }
       if(marks5==''){
        $(marks5).next().text('Please Select Marks ');
        submit = false;
      }else{
        $(marks5).next().text('');
      }
      if(submit==false){
        return false;
      }
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
        
            
             window.location.reload();
        }else{
            toastr.error(data.error);
        }
            },
            
        }); 
    
 });    
    
</script> 