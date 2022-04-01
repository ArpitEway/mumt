<?php

$activities = $this->db->get_where('activity', array('id' => $param1))->result_array();
// $activity_file = $this->Common_model->getRecordByWhere("activity_file",array("activity_id"=>$param1));
//  echo "<pre>";
//  print_r($activity_file);
//  die ;

 ?>

<form method="POST"  id="kt_dropzone_1" class="d-block dropzone ajaxForm"  action="<?php echo site_url('center/Center/activity/update/'.$param1); ?>">
<div class="dz-message" data-dz-message><span>Drop files here to upload</span></br>
</div>
    <div class="form-row">
<input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
        <div class="form-group col-md-6">
            <label for="name">Activity Name</label>
            <input type="text" class="form-control" id="" value="<?= $activities[0]['activity_name']; ?>" name = "activity_name" required placeholder="Enter activity name">
            <input type="hidden" value="<?php echo  $param1  ?>" name="activity_id" id="a">
        </div>

        <div class="form-group col-md-6">
            <label for="name">Discription</label>
            <input type="text" class="form-control" id="" value="<?= $activities[0]['description']; ?>" name = "description" required placeholder="Enter description">
        </div>
		
		
        <div class="form-group col-md-3">
            <label for="name">Date</label>
            <input type="date" class="form-control" id=""  value="<?= $activities[0]['date']; ?>"  name = "date">
        </div>
        
    </div>
	<div class="form-group text-center">
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
var a = document.getElementById("a").value
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
 
                if(data.error){
                    toastr.error(data.error);
                }else{
                    toastr.success(data.success);
                }
            },
    });
}

$(document).on('click','.remove_image',function(){
var id = $(this).attr('id');
//  alert(id);
var csrfName = $('.csrfname').attr('name');
 var csrfHash = $('.csrfname').val();

 $.ajax({
            url: '<?php echo site_url('center/center/delete_activity_file'); ?>',
            type: 'POST',
            dataType : 'json',
            data: {id:id,[csrfName]:csrfHash} ,
              
            success: function (data) { 
                list_img()
            //   console.log(data);
 
            //     if(data.error){
            //         toastr.error(data.error);
            //     }else{
            //         toastr.success(data.success);
            //     }
            },
    });
});




Dropzone.autoDiscover = false;
    
    var myDropzone = new Dropzone(".dropzone", { 
      autoProcessQueue: false,
     
       addRemoveLinks: true,
       acceptedFiles: ".jpg,.png",
       parallelUploads: 8,     
     
      timeout: 180000,
      uploadMultiple:true,
      autoQueue : true,
    });

    $('#uploadfiles').click(function(){  
     myDropzone.processQueue();
     myDropzone.on("complete", function(file) {
        myDropzone.options.autoProcessQueue = false;
        window.location.href = BASE_URL + 'center/Center/activity';
      // alert("f");
    });
    
       myDropzone.on("processing", function () {
                this.options.autoProcessQueue = true;
            });
   });
</script>

