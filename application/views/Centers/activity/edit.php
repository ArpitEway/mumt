<?php

$activities = $this->db->get_where('activity', array('id' => $param1))->result_array();
// $activity_file = $this->Common_model->getRecordByWhere("activity_file",array("activity_id"=>$param1));
//  echo "<pre>";
//  print_r($activity_file);
//  die ;

 ?>

<form method="POST" enctype="multipart/form-data"   class="ajaxForm"  action="<?php echo site_url('center/Center/activity/update/'.$param1); ?>">

<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" id="csrf" value="<?= $hash_csrf; ?>">

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="name">Activity Name</label>
            <input type="text" class="form-control" id="activity_name" value="<?= $activities[0]['activity_name']; ?>" name = "activity_name" required placeholder="Enter activity name">
            <input type="hidden" value="<?php echo  $param1  ?>" name="activity_id" id="activity_id">
        </div>

        <div class="form-group col-md-6">
            <label for="name">Discription</label>
            <input type="text" class="form-control" id="description" value="<?= $activities[0]['description']; ?>" name = "description" required placeholder="Enter description">
        </div>
		
		
        <div class="form-group col-md-3">
            <label for="name">Date</label>
            <input type="date" class="form-control" id="date"  value="<?= $activities[0]['date']; ?>"  name = "date">
        </div>
        
    </div>
    <div class="form-row">
    <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" id="csrf" value="<?= $hash_csrf; ?>">
       <div class="dropzone col-12 " id="kt_dropzone_1" >
          <div class="dz-message" data-dz-message><span>Drop files here to upload</span></br></div>
       </div>
    </div>
	<div class="form-group text-center mt-3">
	<button class="btn btn-md btn-primary" id="uploadfiles" type="button">Update</button>
	</div>
    <div id="preview"></div>
</form>

<script>
$(document).ready(function(){
  list_img();
});

function list_img()
{
var a = document.getElementById("activity_id").value
//    console.log(a);
   var frm = $('.ajaxForm').serialize();
   $.ajax({
            url: '<?php echo site_url('center/center/show_activity_file'); ?>',
            type: 'POST',
            dataType : 'json',
            data: frm ,
              
            success: function (data) {
            $("#preview").html(data.data)
              console.log(data.data);
 
                // if(data.error){
                //     toastr.error(data.error);
                // }else{
                //     toastr.success(data.success);
                // }
            },
    });
}

function confirmation(id) {
  let text = "Are you sure you want to remove";
  if (confirm(text) == true) {
     remove(id);
  } 
}

const remove = (id)=>{
var csrfName = $('.csrfname').attr('name');
 var csrfHash = $('.csrfname').val();
 $.ajax({
            url: '<?php echo site_url('center/center/delete_activity_file'); ?>',
            type: 'POST',
            dataType : 'json',
            data: {id:id,[csrfName]:csrfHash} ,
              
            success: function (data) { 
                list_img()
            },
    });
}



Dropzone.autoDiscover = false;
    
    var myDropzone = new Dropzone(".dropzone", { 
        url: BASE_URL + 'center/Center/activity/update',

      autoProcessQueue: false,
     
       addRemoveLinks: true,
       dictRemoveFileConfirmation:  "Are you sure? You want to remove this image?",

       acceptedFiles: ".jpg,.png",
       parallelUploads: 8,     
     
      timeout: 180000,
      uploadMultiple:true,
      autoQueue : true,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        },
      init: function() {

        dzClosure = this; // Makes sure that 'this' is understood inside the functions below.
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
            formData.append("activity_id", jQuery("#activity_id").val());
            formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
        });
        
      },
    });


</script>

