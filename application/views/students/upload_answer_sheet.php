
    <link href='https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.css' type='text/css' rel='stylesheet'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js' type='text/javascript'></script>

<div class="BoxD border- padding mar-bot">
        <div class="row">
            <div class="col-12">
                <table class="table ">
                <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
                    <input type="hidden" value="<?php echo $student['student_id'] ; ?>" id="student_id">
                <tbody>
                
                    <tr>
                        <td><b>Roll No: </b> <?=$student['roll_no'];?></td>
                        <td colspan="2"><b>Enrollment No: </b><?=$student['enrollment_no'];?></td>
                    </tr>
                    <tr>
                    <td><b>Student Name: </b> <?=$student['name'];?></td>
                    <td colspan="2"><b>Father/Husband Name: </b> <?=$student['f_h_name'];?></td>
                    </tr>
                    <tr>
                    <td><b>Course: </b> <?=$student['course_name'];?></td>
                    <td colspan="2"><b>Class: </b> <?=$student['class_name'];?></td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Dropzone File Upload Examples</h3>
            </div>
        </div>
        <!--begin::Form-->
        <form action="<?php echo  base_url('student/Student/upload_assignment_sub')?>" class="dropzone" >
            <div class="dz-message" data-dz-message><span>Drop files here to upload only pdf format</span></br>
            <span>File Size Upto 5 MB</span></div>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <input name="course_id" hidden value="<?php echo $student["course_group_id"];?>" />
            <input name="class_id"  hidden value="<?php echo $student["class_id"];?>" />
            <input name="center_id"  hidden value="<?php echo $student["center_id"];?>" />
            <input name="student_id"  hidden value="<?php echo $student["student_id"];?>" />
            <input name='paper_code' value="<?php echo $paper_code; ?>" hidden>
       
            <div class="container text-center" >
            <button type="button" class="btn btn-primary"  id="uploadfiles">Upload</button>
            </div>
            </form>  
          
        <!--end::Form-->
    </div>



 <script type='text/javascript'>
      
      Dropzone.autoDiscover = false;

      var myDropzone = new Dropzone(".dropzone", { 
          autoProcessQueue: false,
          addRemoveLinks: true,
          acceptedFiles: ".pdf,.PDF",
          parallelUploads: 1, // Number of files process at a time (default 2)
          maxFilesize: 10,
          timeout: 180000,
      });
     
      $('#uploadfiles').click(function(){       
     myDropzone.processQueue();
              myDropzone.on("complete", function(file) {
                  window.location.href = BASE_URL + 'exam_paper';
              });
      });
      </script>


