<form method="POST" class="d-block ajaxForm" action="<?php echo site_url('admin/ExamController/teachers/create'); ?>">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="course">Name</label>
            <input type="hidden" class="csrfname" name="<?= $name_csrf; ?>" value="<?= $hash_csrf; ?>">
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Teacher Name">        
        </div>
        <div class="form-group col-md-6">
            <label for="class">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter address " required >        
        </div>
        <div class="form-group col-md-6">
            <label for="session">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email " required >        
        </div> 
        <div class="form-group col-md-6">
            <label for="Select_Group_Type">Mobile No</label>
            <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter Mobile No. " required >        
        </div> 
        <div class="form-group col-md-6">
            <label for="session">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject " required >                    
        </div>
        <div class="form-group col-md-6">
            <label for="session">College Name</label>
            <input type="text" class="form-control" id="clg_name" name="clg_name" placeholder="Enter College " required >                    
        </div>
    </div>
    <div class="form-group text-center">
       <button class="btn btn-md btn-primary" type="submit">Submit</button>
   </div>
</form>
<style>
    .plus_btn{
        color: #FFFFFF;
        background-color: #052C68;
        border-color: #052C68;
    }
    .minus_btn{
        color: #FFFFFF;
        background-color: #052C68;
        border-color: #052C68;
    }
</style>
<script>
    //$(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAlldepartment);
    });

    $('#class_group').on('change', function() {
      if(this.value == "Y"){

         addRow(this.form);
     }
 });


    function addRow(frm){ 

       var field = '<div class="form-group col-sd-8"><input type="text" class="form-control" id="group" name="group_name[]" placeholder="Enter group name" required ><br><button class="plus_btn" onclick="addMoreRows(this.form);" >Add more</button></div>';
       $('#addedRow').append(field);
   }

   function addMoreRows(frm){
       var rowCount = $("#rowcount").val();
       var field = '<div class="group_'+rowCount+' "><input type="text" class="groups form-control " placeholder="Enter group name" id="group" name="group_name[]" required=""><br><button class="minus_btn" onclick="RemoveRow('+rowCount+');">Remove </button></div><input type="hidden" id="rowcount" value="1">';
       rowCount++;
       $("#rowcount").val(rowCount);
       $('#addedRows').append(field);
   }

   function RemoveRow(id){
       $('.group_'+id).remove();
   }

</script>