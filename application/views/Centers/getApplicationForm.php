
          <form action="<?= base_url()?>center/center/application_submit" method="post" onsubmit="return validate()" enctype='multipart/form-data' >
        <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="row">
            
           
                <input type="hidden" class="form-control" name="name_eng" value="<?= $students[0]['name']?>" readonly/>
                <input type="hidden" class="form-control" name="student_id" value="<?= $students[0]['student_id']?>" readonly/>
            <?php 
            $col="col-md-6"; 
            if($apply=="DUPLICATE-MARKSHEET"){ ?>
                <div class="form-group col-md-2">
                <label>Class </label>
               <?php 
              $mode = explode(' ',$students[0]['class_name']);
             
              if($mode[1] == "SEM"){
                $class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$students[0]['course_group_id']."' AND mode='Semester'");
              }else  if($mode[1] == "Year"){
                  $class_list = $this->Common_model->get_record('class_master','*',"course_group_id='".$students[0]['course_group_id']."' AND mode='Annual'");
               } ?>
             
                <select class="form-control" name="class_id" id="class" required>
                  <option value=""><?=(isset($all)) ? 'All': 'Select Class';?></option>
                  <?php foreach($class_list as $class){
                    
                    if($class['id']<=$students[0]['class_id']){
                    ?>
                    <option value="<?=$class['id']?>"><?=$class['class_name']?></option>
                  <?php }} ?>
                </select>
            </div>
            <?php $col="col-md-5"; } ?>

            <div class="form-group <?=$col?>">
                <label>Name of Student(In Hindi)</label>
                <input type="text" class="form-control" name="name_hindi" id="sname" required />
                <span class="text-danger" id="serr"></span>
            </div>
           
                <input type="hidden" class="form-control" name="fname_eng" value="<?= $students[0]['f_h_name']?>" readonly/>
            
            <div class="form-group <?=$col?>">
                <label>Father's Name(In Hindi)</label>
                <input type="text" class="form-control" name="fname_hindi" id="fname" required />
                <span class="text-danger" id="ferr"></span>
            </div>
        </div>
        
                <input type="hidden" class="form-control" name="center_id" value="<?= $center_id?>" />
                <input type="hidden" class="form-control" name="apply_for" value="<?= $apply?>" />
                <input type="hidden" class="form-control" name="roll_no" value="<?= $students[0]['roll_no']?>" readonly/>
           
                <input type="hidden" class="form-control" name="enrollment" id="student_enroll" value="<?= $students[0]['enrollment_no'] ?>" readonly/>
           
        
                <input type="hidden" class="form-control" name="session" value="<?= $students[0]['session']?>" readonly/>
           
           
                <input type="hidden" class="form-control" name="course" id="course" value="<?= $students[0]['course_name']?>" readonly/>
           
       
        <div class="row">
            
            <div class="form-group col-md-6">
                <label>Phone No.</label>
                <input type="text" class="form-control" name="phone" value="<?= $students[0]['p_mobile_no']?>" />
            </div>
            <div class="form-group col-md-6">
                <label>Address</label>
                <textarea type="text" class="form-control" col="10" name="address" ><?= $students[0]['c_address']?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="name">Adhar Card</label>
                <input type="file" class="form-control imgupload" id="adhar" name ="adhar" accept=".png, .jpg, .jpeg" required>
                <span class="text-danger" id="adharerr"></span>
            </div>
            <div class="form-group col-md-6">
                <label for="name">Final Marksheet</label>
                <input type="file" class="form-control imgupload" id="marksheet" name ="marksheet" accept=".png, .jpg, .jpeg" >
                <span class="text-danger" id="marksheeterr"></span>
            </div>
        </div>    
       
        <div class="row d-felx justify-content-center m-2">
            
               <button name="submit" type="submit" class="btn btn-success" width="50%">Submit</button>
            
        </div>
   
   <script>
$(".imgupload").change(function(){
    var file = this.files[0];
    var fileType = file["type"];
    var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    if ($.inArray(fileType, validImageTypes) < 0) {
        // invalid file type code goes here.
       
       var a=$(this).attr("id");
       b=a+"err";
       document.getElementById(b).innerHTML= "*Please select proper file!";
       document.getElementById(a).value = null;
            adhar.focus();
        this.val("");
    }
});
function validate(){

       const apply = document.getElementById("apply");
       const sname = document.getElementById("sname");
       const fname = document.getElementById("fname");
       const adhar = document.getElementById("adhar");
       const marksheet = document.getElementById("marksheet");
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
          if (adhar.value === "") {
            document.getElementById("adharerr").innerHTML= "*Please select Adhar Card!";
            adhar.focus();
            return false;
          }else{
            document.getElementById("adharerr").innerHTML="";
          }
        
          if (marksheet.value === "") {
            document.getElementById("marksheeterr").innerHTML= "*Please select Adhar Card!";
            adhar.focus();
            return false;
          }else{
            document.getElementById("marksheeterr").innerHTML="";
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