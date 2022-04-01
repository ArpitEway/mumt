
<style>
 .dropzone{
     border : "none";
 }

</style>
<form method="POST"  id="kt_dropzone_1" class="dropzone  "   action="<?php echo base_url('center/Center/activity/create'); ?>">
<div class="dz-message" data-dz-message><span>Drop files here to upload only pdf format</span></br>
<span>File Size Upto 5 MB</span></div>
    <div class="form-row">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Activity Name</label>
            <input type="text" class="form-control" id="" name = "activity_name"  placeholder="Enter activity name">
        </div>

        <div class="form-group col-md-6">
            <label for="name">Discription</label>
            <input type="text" class="form-control" id="" name = "description"  placeholder="Enter description">
        </div>
		
	
        <div class="form-group col-md-3">
            <label for="name">Date</label>
            <input type="date" class="form-control" id=""  name = "date">
        </div>
    </div>
	<div class="form-group text-center">
	<button class="btn btn-md btn-primary" id="uploadfiles"  type="button">Submit</button>
	</div>
</form>



<script type='text/javascript'>
      Dropzone.autoDiscover = false;
    
      var myDropzone = new Dropzone(".dropzone", { 
        autoProcessQueue: false,
        addRemoveLinks: true,
        parallelUploads: 4,    

        acceptedFiles: ".jpg,.png",
        maxFilesize: 10,
        timeout: 180000,
        uploadMultiple:true,
      });

      $('#uploadfiles').click(function(){       
       myDropzone.processQueue();
       myDropzone.on("complete", function(file) {
         window.location.href = BASE_URL + 'center/Center/activity';
        // alert("f");
      });
     });
   </script>