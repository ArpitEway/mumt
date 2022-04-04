
<style>
 .dropzone{
     border : "none";
 }

</style>

<form method="POST"  enctype="multipart/form-data"    action="<?php echo base_url('center/Center/activity/create'); ?>">
<div class="row">
        <div class="form-group col-md-4">
                    <label for="name">Activity Name</label>
                    <input type="text" class="form-control" id="activity_name" name = "activity_name"  placeholder="Enter activity name">
                </div>

                <div class="form-group col-md-4">
                    <label for="name">Discription</label>
                    <input type="text" class="form-control" id="description" name = "description"  placeholder="Enter description">
                </div>
                
            
                <div class="form-group col-md-4">
                    <label for="name">Date</label>
                    <input type="date" class="form-control" id="date"  name = "date">
                </div>
            </div>
</div>

    <div class="form-row">
          <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" id="csrf" value="<?= $hash_csrf; ?>">
                <div class="dropzone col-12 " id="kt_dropzone_1" >
                <div class="dz-message" data-dz-message><span>Drop files here to upload only pdf format</span></br>
                <span>File Size Upto 5 MB</span></div>
    </div>
            <div class="col-12 text-center m-4">
            <button class="btn btn-md btn-primary" id="uploadfiles"  type="button">Submit</button>
	</div>
</form>



<script type='text/javascript'>
      Dropzone.autoDiscover = false;
    
      var myDropzone = new Dropzone(".dropzone", { 
        url: BASE_URL + 'center/Center/activity/create',

        autoProcessQueue: false,
        addRemoveLinks: true,
        parallelUploads: 4,    

        acceptedFiles: ".jpg,.png",
        maxFilesize: 10,
        timeout: 180000,
        uploadMultiple:true,
        headers: {
        'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        },
        init: function() {
        dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

        // for Dropzone to process the queue (instead of default form behavior):
        document.getElementById("uploadfiles").addEventListener("click", function(e) {
            // Make sure that the form isn't actually being sent.
            e.preventDefault();
            e.stopPropagation();
            dzClosure.processQueue();
            myDropzone.processQueue();
         myDropzone.on("complete", function(file) {
        myDropzone.options.autoProcessQueue = false;
        window.location.href = BASE_URL + 'center/Center/activity';
      // alert("f");
    });
        });

        //send all the form data along with the files:
        this.on("sendingmultiple", function(data, xhr, formData) {
            formData.append("activity_name", jQuery("#activity_name").val());
            formData.append("description", jQuery("#description").val());
            formData.append("date", jQuery("#date").val());
            formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
        });
     
    },
      
      });

//       $('#uploadfiles').click(function(){       
//     //   myDropzone.processQueue();
//       myDropzone.on("sendingmultiple", function(file, xhr, formData) { 

// // Will sendthe filesize along with the file as POST data.
// var activity_name = document.getElementById('activity_name').value ;
// console.log(activity_name);

//  formData.append("activity_name", activity_name);  

// });
    //    myDropzone.on("complete", function(file) {
    //    //  window.location.href = BASE_URL + 'center/Center/activity';
    //     // alert("f");
    //   });
    //  });
   </script>