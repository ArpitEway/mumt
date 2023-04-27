<div class="container p-3">
    <div class="row d-felx justify-content-center m-3" >
            <h3>Application Form</h3>
        </div>
    <form action="<?= base_url()?>center/center/application_submit" method="post" onsubmit="return validate()">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
    <input type="hidden" class="csrfname" name="center_id" value="<?= $center_id; ?>">
        <div class="row d-felx justify-content-center m-2">
            <?php $course_group = $this->Common_model->getRecordByWhere('course_group',array('course_name'=>$students[0]['course_name']));
            
             if($course_group[0]->mode == 'Month'){
                $course_group[0]->mode = 'Semester';
                }
            if($students[0]['university_mode'] == "REG"){
                 
                $mode = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=>$course_group[0]->id,'mode'=>$course_group[0]->mode));
              
            }else{
                $mode = $this->Common_model->getRecordByWhere('class_master',array('course_group_id'=>$course_group[0]->id,'mode'=>$course_group[0]->private_mode));
              
            }
           
            ?>
            <div class="form-group col-md-6">
                <label>Apply For</label>
                <select name="apply_for" class="form-control" id="apply">
                <option value="">Select</option>
                <?php 
                if($students[0]['course_complete'] == 'N'){
                    $field = $this->Common_model->getRecordByWhere('application_field',array('field'=>'DUPLICATE-MARKSHEET','status'=>'Y'));
                }else if($course_group[0]->course_type == 'PGDiploma' || $course_group[0]->course_type == 'Diploma'){
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
        </div>
        <div class="row d-felx justify-content-center m-2" style="display:none;" id="c_class">
           
           <div class="form-group col-md-6">
               <label>Class</label>
              
               <select name="class" class="form-control" id="cls">
                    <option value="">Select</option>
                    <?php
                    foreach($mode as $rs){
                        ?>
                        <option value="<?= $rs->class_name?>"><?= $rs->class_name?></option>
                        <?php
                        
                    }?>
                   
               </select>
              
           </div>
       </div>
      
        <div class="row">
            
            <div class="form-group col-md-3">
                <label>Name of Student (In English)</label>
                <input type="text" class="form-control" name="name_eng" value="<?= $students[0]['name']?>" readonly/>
                <input type="hidden" class="form-control" name="student_id" value="<?= $students[0]['student_id']?>" readonly/>
            </div>
            <div class="form-group col-md-3">
                <label>Name of Student(In Hindi)</label>
                <input type="text" class="form-control" name="name_hindi" id="sname"/>
                <span class="text-danger" id="serr"></span>
            </div>
            <div class="form-group col-md-3">
                <label>Father's Name(In English)</label>
                <input type="text" class="form-control" name="fname_eng" value="<?= $students[0]['f_h_name']?>" readonly/>
            </div>
            <div class="form-group col-md-3">
                <label>Father's Name(In Hindi)</label>
                <input type="text" class="form-control" name="fname_hindi" id="fname"/>
                <span class="text-danger" id="ferr"></span>
            </div>
        </div>
      
        <div class="row">
            
            <div class="form-group col-md-3">
                <label>Roll No.</label>
                <input type="text" class="form-control" name="roll_no" value="<?= $students[0]['roll_no']?>" readonly/>
            </div>
            <div class="form-group col-md-3">
                <label>Enroll. No.</label>
                <input type="text" class="form-control" name="enrollment" value="<?= $students[0]['enrollment_no'] ?>" readonly/>
            </div>
            <div class="form-group col-md-3">
                <label>Session</label>
                <input type="text" class="form-control" name="session" value="<?= $students[0]['session']?>" readonly/>
            </div>
            <div class="form-group col-md-3">
                <label>Course</label>
                <input type="text" class="form-control" name="course" id="course" value="<?= $students[0]['course_name']?>" readonly/>
            </div>
        </div>
       
        <div class="row">
            
            <div class="form-group col-md-6">
                <label>Phone No.</label>
                <input type="text" class="form-control" name="phone" value="<?= $students[0]['p_mobile_no']?>" readonly/>
            </div>
            <div class="form-group col-md-6">
                <label>Address</label>
                <textarea type="text" class="form-control" col="10" name="address" readonly><?= $students[0]['c_address']?></textarea>
            </div>
        </div>
        <!-- <div class="row">
            
            <div class="form-group col-md-12">
                
                <input type="checkbox" name="check" id="check"/> &nbsp <strong>Rs.<?= $field[0]->amount?>/- is Fixed for Degree by the University. The Demand Draft Should be in favour of 
                    MMYVV DDE RECEIPT A/C JABALPUR. Without Demand Draft the Application will not be 
                    considered. Attach Photocopy of Mark sheet.</strong>
                    <span class="text-danger" id="cerr"></span> 
            </div>
           
        </div> -->
        <div class="row d-felx justify-content-center m-2">
            
               <button name="submit" type="submit" class="btn btn-success" width="50%">Submit</button>
            
        </div>
    </form>

</div>
<script>

function validate(){

       const apply = document.getElementById("apply");
       const sname = document.getElementById("sname");
        const fname = document.getElementById("fname");
        // const check = document.getElementById("check");
        const cls = document.getElementById("cls");
        
        

        if (apply.value === "") {
            document.getElementById("aerr").innerHTML= "*Please select purpose!";
            apply.focus();
            return false;
          }else{
            document.getElementById("aerr").innerHTML="";
          }
         
        
          if (sname.value === "") {
            document.getElementById("serr").innerHTML= "*Please enter student name!";
            sname.focus();
            return false;
          }else{
            document.getElementById("serr").innerHTML="";
          }
          if (fname.value === "") {
            document.getElementById("ferr").innerHTML= "*Please enter father name!";
            fname.focus();
            return false;
          }else{
            document.getElementById("ferr").innerHTML="";
          }
        // if (!check.checked) {
        //     // alert();
        //     document.getElementById("cerr").innerHTML= "*Please check checkbox!";
        //     check.focus();
        //     return false;
        //   }else{
        //     document.getElementById("cerr").innerHTML="";
        //   }
          if(apply.value == "DUPLICATE-MARKSHEET"){
           
             if(cls.value == ""){
                alert("Please select Class")
                return false;
                }
            

          }

}

$("#apply").on('change',function(){

   var app =  $("#apply").val();
  if(app == "DUPLICATE-MARKSHEET"){
   $("#c_class").show();
    
  }else{
    $("#c_class").hide();
  } 

})


</script>