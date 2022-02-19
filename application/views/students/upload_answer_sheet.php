<style type="text/css">
 .form-block {
    background: hsl(53deg 100% 95% / 61%);
    border: 1px solid #f6d459;
    border-radius: 10px;
    padding: 20px;
}
</style>
    <div class="container mb-5 form-block">
        <div class="row">
            <div class="col-md-4 font-weight-bold p-2">Course / Class </div>
            <div class="p-2 col-md-8"><?=$student['course_name'];?> (<?=$student['class_name'];?>)</div>
            <div class="p-2 col-md-4 font-weight-bold">Paper</div>
            <div class="p-2 col-md-8"> <?=$paperData->paper_name;?> ( <?=$paperData->paper_code;?> )</div>
        </div>
    </div>        

    
    <div class="w-100">
        <!--begin::Form-->
        <div class="col-sm-8 m-auto">
            <form action="<?php echo  base_url('student/Student/upload_assignment_sub')?>"  id="kt_dropzone_1" class="dropzone" >
                <div class="dz-message" data-dz-message><span>Drop files here to upload only pdf format</span></br>
                    <span>File Size Upto 5 MB</span></div>
                    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
                    <input name="course_id" hidden value="<?php echo $student["course_group_id"];?>" />
                    <input name="class_id"  hidden value="<?php echo $student["class_id"];?>" />
                    <input name="center_id"  hidden value="<?php echo $student["center_id"];?>" />
                    <input name="student_id"  hidden value="<?php echo $student["student_id"];?>" />
                    <input name='paper_code' value="<?php echo $paperData->paper_code; ?>" hidden>
                </form>
            </div>
            <div class="mt-5 " >
                <button type="button" class="btn btn-primary btn-block col-sm-3 m-auto"  id="uploadfiles">Upload</button>
            </div>
        </div>
        <!--end::Form-->